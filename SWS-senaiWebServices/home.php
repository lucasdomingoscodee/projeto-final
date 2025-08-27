<?php
require_once 'conexao.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENAI WEB SERVICES</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

</head>

<body>
    <header>
        <div class="headlogo">
            <a href="home.php"><img src="imagens/sesi_senai_negativo.png" alt="logomenu"></a>
        </div>
        <h1>SWS-SENAI Web Service</h1>
        <nav>
            <ul>
                <li><a href="#">Cadastros</a>
                    <ul>
                        <li><a href="#" onclick="mostrarApenasEste('instrut')">Instrutores</a></li>
                        <li><a href="#" onclick="mostrarApenasEste('salaForm')">Salas</a></li>
                        <li><a href="#" onclick="mostrarApenasEste('aulas')">Aulas</a></li>
                    </ul>
                </li>
                <li><a href="#">Movimentação</a>
                    <ul>
                        <li><a href="#">Lançamento de aulas</a></li>
                        <li><a href="#">Lançamento de atividades</a></li>
                        <li><a href="#">Programação de turmas</a></li>
                    </ul>
                </li>
                <li><a href="#">Consultas</a>
                    <ul>
                        <li><a href="#">Horário</a></li>
                        <li><a href="#">Instrutor</a></li>
                        <li><a href="#">Instrutor calendário</a></li>
                        <li><a href="#">Sala</a></li>
                        <li><a href="#">Sala calendário</a></li>
                    </ul>
                </li>
                <li><a href="#">Utilitario</a>
                    <ul>
                        <li><a href="#">Troca de senha</a></li>
                    </ul>
                </li>

                <li><a href="index.php">logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="botoes">
            <a href="#" onclick="mostrarApenasEste('instrut')">Consulta Instrutores</a>

            <a href="#" onclick="mostrarApenasEste('salaForm')">Consulta Salas</a>

            <a href="#" onclick="mostrarApenasEste('aulas')">Consulta Aula</a>

        </div>
    </main>

    <!-- Formularios para as paginas -->
    <div class="instrutor" id="instrut" style="display: none;">
        <form action="processos/instrutor.php" method="POST">
            <h2>Cadastro instrutores</h2>
            <br>
            <label>Nome do instrutor: </label>
            <input type="text" name="nome" required placeholder="Exemplo da Costa"><br><br>

            <label>E-mail: </label>
            <input type="email" name="email" required placeholder="emailteste@teste.com"><br><br>

            <label>Especialidade do profissional: </label>
            <input type="text" name="especialidade" required placeholder="Exemplo especialidade"><br><br>

            <button type="submit">Cadastrar</button>
        </form>
    </div>


    <div class="sala" id="salaForm" style="display: none;">
        <form action="processos/salas.php" method="POST">
            <h2>Cadastro salas</h2>
            <br>
            <label>Nome da Sala: </label>
            <input type="text" name="nome_sala" required placeholder="Nome da sala"><br><br>

            <label>Descrição (opcional): </label>
            <textarea name="descricao" rows="3" placeholder="Faça uma breve descrição da sala"></textarea><br><br>

            <label>Disponível: </label>
            <input type="checkbox" name="disponivel" value="1" checked><br><br>

            <button type="submit">Cadastrar Sala</button>
        </form>

        <p id="mensagem"></p>
    </div>


    <div class="aulas" id="aulas">
    <form action="processos/aulas.php" method="POST">
        <h2>Cadastro de Aulas</h2>
        <br>
        <label>Data:</label>
        <input type="date" name="data" required><br><br>

        <label>Matéria:</label>
        <input type="text" name="materia" required placeholder="Exemplo: Matemática"><br><br>

        <label>Período: </label>
        <select name="periodo" required>
            <option value="" disabled selected>Selecione o período</option>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
            <option value="Noite">Noite</option>
        </select><br><br>

        <label for="id_instrutores">Instrutor:</label>
            <select name="id_instrutores" id="id_instrutores" required>
                <option value="" disabled selected>Selecione um instrutor</option>
                <?php
                // Prepara e executa a consulta para buscar as salas
                $sql = "SELECT id_instrutores, nome FROM instrutores ORDER BY nome ASC";
                $result = $conn->query($sql);

                // Verifica se retornou alguma sala
                if ($result->num_rows > 0) {
                    // Loop para criar uma <option> para cada sala encontrada
                    while ($instrutores = $result->fetch_assoc()) {
                        echo '<option value="' . $instrutores['id_instrutores'] . '">' . htmlspecialchars($instrutores['nome']) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>Nenhum instrutor cadastrado</option>';
                }
                ?>
            </select><br><br>

        <label for="id_sala">Sala:</label>
            <select name="id_sala" id="id_sala" required>
                <option value="" disabled selected>Selecione uma sala</option>
                <?php
                // Prepara e executa a consulta para buscar as salas
                $sql = "SELECT id_sala, nome_sala FROM salas ORDER BY nome_sala ASC";
                $result = $conn->query($sql);

                // Verifica se retornou alguma sala
                if ($result->num_rows > 0) {
                    // Loop para criar uma <option> para cada sala encontrada
                    while ($sala = $result->fetch_assoc()) {
                        echo '<option value="' . $sala['id_sala'] . '">' . htmlspecialchars($sala['nome_sala']) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>Nenhuma sala cadastrada</option>';
                }
                ?>
            </select><br><br>
            <button type="submit">Cadastrar</button>
        </form>
    </div>



    <script>
    function mostrarApenasEste(formId) {
        // Esconde todos os forms
        document.querySelectorAll('.instrutor, .sala, .aulas').forEach(form => {
            form.style.display = 'none';
        });

        // Mostra apenas o form clicado
        document.getElementById(formId).style.display = 'block';
    }
    </script>
</body>

</html>