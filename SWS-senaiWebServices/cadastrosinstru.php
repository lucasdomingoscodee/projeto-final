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
        <h1>SWS-CADASTROS INSTRUTOR</h1>
        <nav>
            <ul>
                <li><a href="#">Cadastros</a>
                    <ul>
                        <li><a href="cadastrosinstru.php">Instrutores</a></li>
                        <li><a href="#">Salas</a></li>
                        <li><a href="#">Modalidades</a></li>
                        <li><a href="#">Áreas</a></li>
                        <li><a href="#">Cursos</a></li>
                        <li><a href="#">Turmas</a></li>
                        <li><a href="#">Feriados</a></li>
                        <li><a href="#">Atividades</a></li>
                    </ul>
                </li>
                <li><a href="#">placeholder</a></li>
                <li><a href="#">Consultas</a>
                    <ul>
                        <li><a href="#">Horário</a></li>
                        <li><a href="#">Instrutor</a></li>
                        <li><a href="#">Instrutor calendário</a></li>
                        <li><a href="#">Sala</a></li>
                        <li><a href="#">Sala calendário</a></li>
                        <li><a href="#">Matérias por turma</a></li>
                        <li><a href="#">Matérias a lançar</a></li>
                        <li><a href="#">Programação de turma</a></li>
                        <li><a href="#">Ocorrências</a></li>
                    </ul>
                </li>
                <li><a href="#">placeholder</a></li>
                <li><a href="#">placeholder</a></li>
                <li><a href="index.php">logout</a></li>
            </ul>
        </nav>
    </header>


    <div class="instrutor">
        <form action="processos/instrutor.php" method="POST">
            <label>Nome do instrutor: </label>
            <input type="text" name="nome" required><br><br>

            <label>E-mail: </label>
            <input type="email" name="email" required><br><br>

            <label>Especialidade do profissional: </label>
            <input type="text" name="especialidade" required><br><br>

            <button type="submit">Cadastrar</button>
        </form>
    </div>



    <div class="sala">
        <form action="processos/salas.php" method="POST">
            <label>Número da Sala: </label>
            <input type="text" name="numero" required><br><br>

            <label>Capacidade: </label>
            <input type="number" name="capacidade" min="1" required><br><br>

            <label>Tipo de Sala: </label>
            <select name="tipo" required>
                <option value="">Selecione...</option>
                <option value="Laboratório">Laboratório</option>
                <option value="Sala de Aula">Sala de Aula</option>
                <option value="Oficina">Oficina</option>
                <option value="Auditório">Auditório</option>
            </select><br><br>

            <label>Descrição (opcional): </label>
            <textarea name="descricao" rows="3"></textarea><br><br>

            <label>Disponível: </label>
            <input type="checkbox" name="disponivel" value="1" checked><br><br>

            <button type="submit">Cadastrar Sala</button>
        </form>

</body>

</html>