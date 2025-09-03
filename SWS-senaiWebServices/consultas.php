<?php
require_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENAI WEB SERVICES - Consultas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    
    </head>

<body>
    <header>
        <div class="headlogo">
            <a href="home.php"><img src="imagens/sesi_senai_negativo.png" alt="logomenu"></a>
        </div>
        <h1>CONSULTAS</h1>

        <button id="theme-toggle-button" class="theme-toggle-button">🌙</button>

        <nav>
            <ul>
                <li><a href="./home.php">Cadastros</a>
                    <ul>
                        <li><a href="./home.php">Cadastrar Instrutores</a></li>
                        <li><a href="./home.php">Cadastrar Salas</a></li>
                        <li><a href="./home.php">Cadastrar Aulas</a></li>
                    </ul>
                </li>
                <li><a href="#">Consultas</a>
                    <ul>
                        <li><a href="consultas.php?view=instrutores">Instrutor</a></li>
                        <li><a href="consultas.php?view=salas">Sala</a></li>
                        <li><a href="consultas.php?view=aulas">Aulas</a></li>
                    </ul>
                </li>
                <li><a href="gerenciar.php">Gerenciar</a>
            <ul>
                <li><a href="gerenciar.php?tabela=instrutores">Instrutores</a></li>
                <li><a href="gerenciar.php?tabela=salas">Salas</a></li>
                <li><a href="gerenciar.php?tabela=aulas">Aulas</a></li>
            </ul>
        </li>
                <li><a href="index.php">logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="botoes">
             <a href="consultas.php?view=instrutores">Consulta Instrutores</a>
             <a href="consultas.php?view=salas">Consulta Salas</a>
             <a href="consultas.php?view=aulas">Consulta Aulas</a>
        </div>
        
        <div class="content-wrapper">
        <?php
        function exibir_tabela($resultado, $cabecalhos) {
            if ($resultado && $resultado->num_rows > 0) {
                echo "<table>";
                echo "<thead><tr>";
                foreach ($cabecalhos as $cabecalho) {
                    echo "<th>" . htmlspecialchars($cabecalho) . "</th>";
                }
                echo "</tr></thead>";
                echo "<tbody>";
                while ($linha = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($linha as $key => $coluna) {
                         // Check if the column is 'disponivel' to show Yes/No
                        if ($key === 'disponivel') {
                            echo "<td>" . ($coluna ? 'Sim' : 'Não') . "</td>";
                        } else {
                            echo "<td>" . htmlspecialchars($coluna) . "</td>";
                        }
                    }
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>Nenhum resultado encontrado.</p>";
            }
        }

        if (isset($_GET['view'])) {
            $view = $_GET['view'];
            switch ($view) {
                case 'instrutores':
                    echo "<h2>Lista de Instrutores</h2>";
                    $sql = "SELECT id_instrutores, nome, email, especialidade FROM instrutores";
                    $resultado = $conn->query($sql);
                    exibir_tabela($resultado, ['ID', 'Nome', 'Email', 'Especialidade']);
                    break;

                case 'salas':
                    echo "<h2>Lista de Salas</h2>";
                    $sql = "SELECT id_sala, nome_sala, descricao, disponivel FROM salas";
                    $resultado = $conn->query($sql);
                    exibir_tabela($resultado, ['ID', 'Nome da Sala', 'Descrição', 'Disponível']);
                    break;
                
                case 'aulas':
                    echo "<h2>Consulta de Aulas por Período</h2>";
                    $data_inicio = $_GET['data_inicio'] ?? '';
                    $data_fim = $_GET['data_fim'] ?? '';
                
                    echo '
                    <form method="GET" action="consultas.php">
                        <input type="hidden" name="view" value="aulas">
                        <div>
                            <label for="data_inicio">De:</label>
                            <input type="date" id="data_inicio" name="data_inicio" value="' . htmlspecialchars($data_inicio) . '">
                        </div>
                        <div>
                            <label for="data_fim">Até:</label>
                            <input type="date" id="data_fim" name="data_fim" value="' . htmlspecialchars($data_fim) . '">
                        </div>
                        <input type="submit" value="Filtrar Aulas">
                    </form>
                    ';
                
                    // Query to fetch class details along with instructor name and room name
                    $sql = "SELECT a.id_aula, a.data, a.materia, a.periodo, i.nome as instrutor_nome, s.nome_sala 
                            FROM aulas a
                            LEFT JOIN instrutores i ON a.id_instrutores = i.id_instrutores
                            LEFT JOIN salas s ON a.id_sala = s.id_sala";
                    
                    $params = [];
                    $types = "";
                    
                    if (!empty($data_inicio) && !empty($data_fim)) {
                        $sql .= " WHERE a.data BETWEEN ? AND ?";
                        $params[] = $data_inicio;
                        $params[] = $data_fim;
                        $types = "ss";
                    }
                    
                    $sql .= " ORDER BY a.data DESC";
                    
                    $stmt = $conn->prepare($sql);
                    if (!empty($params)) {
                        $stmt->bind_param($types, ...$params);
                    }
                    
                    $stmt->execute();
                    $resultado = $stmt->get_result();
                    
                    exibir_tabela($resultado, ['ID', 'Data', 'Matéria', 'Periodo', 'Instrutor', 'Sala']);
                    $stmt->close();
                    break;
            }
        } else {
            echo '<div class="welcome-message">';
            echo '<h1>Bem-vindo ao Painel de Consultas</h1>';
            echo '<p>Selecione uma consulta no menu ou nos botões acima para exibir os dados.</p>';
            echo '</div>';
        }
        
        $conn->close();
        ?>
        </div>
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleButton = document.getElementById('theme-toggle-button');
            const htmlElement = document.documentElement;

            // Set initial button icon based on current theme
            if (htmlElement.getAttribute('data-theme') === 'dark') {
                themeToggleButton.textContent = '☀️';
            } else {
                themeToggleButton.textContent = '🌙';
            }

            themeToggleButton.addEventListener('click', function() {
                const currentTheme = htmlElement.getAttribute('data-theme');
                if (currentTheme === 'dark') {
                    htmlElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    themeToggleButton.textContent = '🌙';
                } else {
                    htmlElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeToggleButton.textContent = '☀️';
                }
            });
        });
    </script>
</body>
</html>