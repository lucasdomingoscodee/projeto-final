<?php
// Usando o seu arquivo de conexão que já existe.
// Garanta que este arquivo cria uma variável de conexão, por exemplo: $conn
require_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENAI WEB SERVICES</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        /* Adicionei um estilo básico para a tabela para garantir a visualização */
        main table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        main th, main td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        main th { background-color: #f2f2f2; font-weight: bold; }
        main .welcome-message { text-align: center; padding: 50px; }
    </style>
</head>

<body>
    <header>
        <div class="headlogo">
            <a href="consultas.php"><img src="imagens/sesi_senai_negativo.png" alt="logomenu"></a>
        </div>
        <h1>CONSULTAS</h1>
        <nav>
            <ul>
                <li><a href="#">Cadastros</a>
                    <ul>
                        <li><a href="./home.php">Cadastrar Instrutores</a></li>
                        <li><a href="./home.php">Cadastrar Salas</a></li>
                        <li><a href="./home.php">Cadastrar Aulas</a></li>
                    </ul>
                </li>
                <li><a href="#">Movimentação</a>
                    </li>
                <li><a href="#">Consultas</a>
                    <ul>
                        <li><a href="consultas.php?view=horario">Horário</a></li>
                        <li><a href="consultas.php?view=instrutores">Instrutor</a></li>
                        <li><a href="consultas.php?view=instrutor_calendario">Instrutor calendário</a></li>
                        <li><a href="consultas.php?view=salas">Sala</a></li>
                        <li><a href="consultas.php?view=sala_calendario">Sala calendário</a></li>
                        <li><a href="consultas.php?view=aulas">Aulas</a></li>
                    </ul>
                </li>
                <li><a href="#">Utilitario</a>
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

        <?php
        // Função reutilizável para criar a tabela HTML a partir de um resultado SQL
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
                    foreach ($linha as $coluna) {
                        echo "<td>" . htmlspecialchars($coluna) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>Nenhum resultado encontrado.</p>";
            }
        }

        // Verifica se o parâmetro 'view' foi passado na URL
        if (isset($_GET['view'])) {
            $view = $_GET['view'];

            // Decide qual consulta executar com base no valor de 'view'
            switch ($view) {
                case 'instrutores':
                    echo "<h2>Lista de Instrutores</h2>";
                    // Sua consulta para instrutores (corrigi o nome da coluna id)
                    $sql = "SELECT id_instrutores, nome, email, especialidade FROM instrutores";
                    $resultado = $conn->query($sql);
                    exibir_tabela($resultado, ['ID', 'Nome', 'Email', 'Especialidade']);
                    break;

                case 'salas':
                    echo "<h2>Lista de Salas</h2>";
                    // ATENÇÃO: Adapte o nome da tabela e colunas para a sua estrutura de Salas
                    $sql = "SELECT id_sala, nome_sala, descricao, disponivel FROM salas";
                    $resultado = $conn->query($sql);
                    exibir_tabela($resultado, ['ID', 'Nome da Sala', 'Descrição' ,'Disponivel']);
                    break;
                
                    case 'aulas':
                        echo "<h2>Consulta de Aulas por Período</h2>";
                    
                        // --- 1. Formulário de Filtro de Data (HTML) ---
                        $data_inicio = $_GET['data_inicio'] ?? '';
                        $data_fim = $_GET['data_fim'] ?? '';
                    
                        echo '
                        <form method="GET" action="consultas.php" style="margin-bottom: 20px;">
                            <input type="hidden" name="view" value="aulas">
                            
                            <label for="data_inicio">De:</label>
                            <input type="date" id="data_inicio" name="data_inicio" value="' . htmlspecialchars($data_inicio) . '">
                            
                            <label for="data_fim" style="margin-left: 15px;">Até:</label>
                            <input type="date" id="data_fim" name="data_fim" value="' . htmlspecialchars($data_fim) . '">
                            
                            <input type="submit" value="Filtrar Aulas" style="margin-left: 15px;">
                        </form>
                        ';
                    
                    
                        // --- 2. Lógica de Construção da Query (PHP) ---
                        // Usando exatamente as colunas da sua tabela 'aulas'
                        $sql = "SELECT id_aula, data, materia, periodo, id_instrutores, id_sala FROM aulas";
                        
                        $params = [];
                        $types = "";
                    
                        // Adiciona o filtro de data se o usuário preencheu o período
                        if (!empty($data_inicio) && !empty($data_fim)) {
                            // Usando a sua coluna 'data' para o filtro
                            $sql .= " WHERE data BETWEEN ? AND ?";
                            $params[] = $data_inicio;
                            $params[] = $data_fim;
                            $types = "ss"; // Dois parâmetros string
                        }
                    
                        // Ordena as aulas pela sua coluna 'data'
                        $sql .= " ORDER BY data DESC";
                    
                    
                        // --- 3. Execução Segura com Prepared Statements ---
                        $stmt = $conn->prepare($sql);
                    
                        if (!empty($params)) {
                            $stmt->bind_param($types, ...$params);
                        }
                        
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                    
                        // --- 4. Exibição da Tabela ---
                        // Usando exatamente os seus cabeçalhos
                        exibir_tabela($resultado, ['ID', 'Data', 'Matéria', 'Periodo', 'Instrutor', 'Sala']);
                        
                        $stmt->close();
                        break;
            }
        } else {
            // Mensagem inicial quando nenhuma consulta for selecionada
            echo '<div class="welcome-message">';
            echo '<h1>Bem-vindo ao Painel de Consultas</h1>';
            echo '<p>Selecione uma consulta no menu ou nos botões acima para exibir os dados.</p>';
            echo '</div>';
        }
        
        // A conexão é fechada no final, se necessário. Depende do seu arquivo conexao.php
        $conn->close();
        ?>
    </main>

    </body>
</html> 