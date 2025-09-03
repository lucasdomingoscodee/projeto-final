<?php
require_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento - SENAI</title>
    <link rel="stylesheet" href="./css/style.css">
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <style>
        .actions a { margin-right: 10px; text-decoration: none; padding: 5px 10px; border-radius: 4px; }
        .edit-btn { background-color: #007bff; color: white; }
        .delete-btn { background-color: #dc3545; color: white; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .management-buttons { text-align: center; margin-top: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); margin-bottom: 20px; }
        .management-buttons a {
            display: inline-block; padding: 15px 30px; margin: 5px; font-size: 1.1em;
            text-decoration: none; color: white; background-color: #0056b3;
            border-radius: 8px; transition: background-color 0.3s;
        }
        .management-buttons a:hover { background-color: #004494; }
    </style>
</head>
<body>
    <header>
        <div class="headlogo"><a href="home.php"><img src="imagens/sesi_senai_negativo.png" alt="logomenu"></a></div>
        <h1>GERENCIAMENTO DE DADOS</h1>

        <button id="theme-toggle-button" class="theme-toggle-button">🌙</button>

        <nav>
             <ul>
                <li><a href="./home.php">Cadastros</a></li>
                <li><a href="./consultas.php">Consultas</a></li>
                <li><a href="#">Gerenciar</a>
                    <ul>
                        <li><a href="gerenciar.php?tabela=instrutores">Gerenciar Instrutores</a></li>
                        <li><a href="gerenciar.php?tabela=salas">Gerenciar Salas</a></li>
                        <li><a href="gerenciar.php?tabela=aulas">Gerenciar Aulas</a></li>
                    </ul>
                </li>
                <li><a href="index.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="content-wrapper">
            <?php
            // Mensagem de feedback (sucesso/erro)
            if (isset($_GET['status'])) {
                $status = $_GET['status'];
                $item = $_GET['item'] ?? 'Registro';
                $action = $_GET['action'] ?? 'realizada';
                if ($status == 'success') {
                    echo "<div class='message success'>{$item} {$action} com sucesso!</div>";
                } elseif ($status == 'error') {
                    echo "<div class='message error'>Ocorreu um erro na ação de {$action} do {$item}.</div>";
                }
            }
            ?>

            <div class="management-buttons">
                <a href="gerenciar.php?tabela=instrutores">Gerenciar Instrutores</a>
                <a href="gerenciar.php?tabela=salas">Gerenciar Salas</a>
                <a href="gerenciar.php?tabela=aulas">Gerenciar Aulas</a>
            </div>

            <?php
            // Lógica para preparar a exibição da tabela selecionada
            $tabela_selecionada = $_GET['tabela'] ?? '';
            $config = [];

            // O switch agora só prepara a configuração da tabela
            switch ($tabela_selecionada) {
                case 'instrutores':
                    $config = [
                        'titulo' => 'Gerenciar Instrutores', 'tabela_db' => 'instrutores', 'pk' => 'id_instrutores',
                        'colunas' => 'id_instrutores, nome, email, especialidade',
                        'cabecalhos' => ['ID', 'Nome', 'Email', 'Especialidade', 'Ações']
                    ];
                    break;
                case 'salas':
                    $config = [
                        'titulo' => 'Gerenciar Salas', 'tabela_db' => 'salas', 'pk' => 'id_sala',
                        'colunas' => 'id_sala, nome_sala, descricao, disponivel',
                        'cabecalhos' => ['ID', 'Nome da Sala', 'Descrição', 'Disponível', 'Ações']
                    ];
                    break;
                case 'aulas':
                    $config = [
                        'titulo' => 'Gerenciar Aulas', 'tabela_db' => 'aulas', 'pk' => 'id_aula',
                        'colunas' => 'id_aula, data, materia, periodo, id_instrutores, id_sala',
                        'cabecalhos' => ['ID', 'Data', 'Matéria', 'Período', 'ID Instrutor', 'ID Sala', 'Ações']
                    ];
                    break;
                default:
                    // Se nenhuma tabela for selecionada, não faz nada aqui.
                    // A tabela simplesmente não será exibida abaixo.
                    break;
            }

            // Este bloco só é executado se uma tabela válida foi selecionada
            if (!empty($config)) {
                echo "<h2>" . $config['titulo'] . "</h2>";
                $sql = "SELECT " . $config['colunas'] . " FROM " . $config['tabela_db'] . " ORDER BY " . $config['pk'] . " DESC";
                $resultado = $conn->query($sql);
                if ($resultado && $resultado->num_rows > 0) {
                    echo "<table><thead><tr>";
                    foreach ($config['cabecalhos'] as $cabecalho) { echo "<th>$cabecalho</th>"; }
                    echo "</tr></thead><tbody>";
                    while ($linha = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($linha as $key => $valor) {
                            if ($key === 'disponivel') {
                                echo "<td>" . ($valor ? 'Sim' : 'Não') . "</td>";
                            } else {
                                echo "<td>" . htmlspecialchars($valor) . "</td>";
                            }
                        }
                        echo '<td class="actions">';
                        echo '<a href="editar.php?tabela=' . $tabela_selecionada . '&id=' . $linha[$config['pk']] . '" class="edit-btn">Editar</a>';
                        echo '<a href="apagar.php?tabela=' . $tabela_selecionada . '&id=' . $linha[$config['pk']] . '" class="delete-btn" onclick="return confirm(\'Tem certeza que deseja apagar este registro? A ação não pode ser desfeita.\');">Apagar</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>Nenhum registro encontrado para esta categoria.</p>";
                }
            } elseif(empty($tabela_selecionada)) {
                // Exibe uma mensagem de boas-vindas apenas se nenhuma tabela foi selecionada na URL
                echo "<p style='text-align: center; font-size: 1.2em;'>Selecione um recurso acima para começar a gerenciar.</p>";
            }

            $conn->close();
            ?>
        </div>
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleButton = document.getElementById('theme-toggle-button');
            const htmlElement = document.documentElement;
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