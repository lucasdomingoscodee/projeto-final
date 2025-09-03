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

    <script>
        (function() {
            // Check for saved theme in localStorage and apply it
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
        <h1>ADMIN CADASTRO - SENAI Web Service</h1>
        
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
                <li><a href="./consultas.php">Consultas</a>
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
            <a href="#" onclick="mostrarApenasEste('instrut')">Cadastro Instrutores</a>
            <a href="#" onclick="mostrarApenasEste('salaForm')">Cadastro Salas</a>
            <a href="#" onclick="mostrarApenasEste('aulas')">Cadastro Aula</a>
        </div>
    </main>

    <div class="instrutor form-container" id="instrut" style="display: none;">
        <form id="formInstrutor">
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

    <div class="sala form-container" id="salaForm" style="display: none;">
        <form id="formSala">
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
    </div>

    <div class="aulas form-container" id="aulas" style="display: none;">
        <form id="formAulas">
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
            <select name="id_instrutores" id="selectInstrutorAulas" required>
                <option value="">Carregando...</option>
            </select><br><br>

            <label for="id_sala">Sala:</label>
            <select name="id_sala" id="selectSalaAulas" required>
                <option value="">Carregando...</option>
            </select><br><br>
            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <script>
        function mostrarApenasEste(formId) {
            document.querySelectorAll('.instrutor, .sala, .aulas').forEach(form => {
                form.style.display = 'none';
            });
            document.getElementById(formId).style.display = 'block';
        }

        async function atualizarDropdowns() {
            // ... (your existing function is perfect, no changes needed)
            const instrutorSelect = document.getElementById('selectInstrutorAulas');
            const salaSelect = document.getElementById('selectSalaAulas');
            
            try {
                const [instrutoresRes, salasRes] = await Promise.all([
                    fetch('processos/get_instrutores.php'),
                    fetch('processos/get_salas.php')
                ]);

                if (!instrutoresRes.ok || !salasRes.ok) {
                    throw new Error('A network response was not ok.');
                }

                const instrutores = await instrutoresRes.json();
                const salas = await salasRes.json();

                // Build Instrutor Dropdown
                instrutorSelect.innerHTML = ''; 
                let defaultInstrutor = new Option("Selecione um instrutor", "");
                defaultInstrutor.disabled = true; defaultInstrutor.selected = true;
                instrutorSelect.add(defaultInstrutor);
                instrutores.forEach(instrutor => {
                    instrutorSelect.add(new Option(instrutor.nome, instrutor.id_instrutores));
                });

                // Build Sala Dropdown
                salaSelect.innerHTML = ''; 
                let defaultSala = new Option("Selecione uma sala", "");
                defaultSala.disabled = true; defaultSala.selected = true;
                salaSelect.add(defaultSala);
                salas.forEach(sala => {
                    salaSelect.add(new Option(sala.nome_sala, sala.id_sala));
                });
            } catch (error) {
                console.error('Failed to update dropdowns:', error);
                instrutorSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                salaSelect.innerHTML = '<option value="">Erro ao carregar</option>';
            }
        }

        function handleFormSubmit(form, url, onSuccessCallback) {
            // ... (your existing function is perfect, no changes needed)
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form);

                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === 'success') {
                        form.reset();
                        if (onSuccessCallback) {
                            onSuccessCallback();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error during form submission:', error);
                    alert('Ocorreu um erro de comunicação.');
                });
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            handleFormSubmit(document.getElementById('formInstrutor'), 'processos/instrutor.php', atualizarDropdowns);
            handleFormSubmit(document.getElementById('formSala'), 'processos/salas.php', atualizarDropdowns);
            handleFormSubmit(document.getElementById('formAulas'), 'processos/aulas.php');
            
            atualizarDropdowns();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleButton = document.getElementById('theme-toggle-button');
            const htmlElement = document.documentElement;

            // Set initial button icon based on current theme
            if (htmlElement.getAttribute('data-theme') === 'dark') {
                themeToggleButton.textContent = '☀️'; // Sun icon for dark mode
            } else {
                themeToggleButton.textContent = '🌙'; // Moon icon for light mode
            }

            themeToggleButton.addEventListener('click', function() {
                // Check the current theme
                const currentTheme = htmlElement.getAttribute('data-theme');

                if (currentTheme === 'dark') {
                    // Switch to light mode
                    htmlElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    themeToggleButton.textContent = '🌙';
                } else {
                    // Switch to dark mode
                    htmlElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeToggleButton.textContent = '☀️';
                }
            });
        });
    </script>
</body>

</html>