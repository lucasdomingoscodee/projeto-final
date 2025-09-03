<?php
// ... (toda a lógica PHP do topo do arquivo - sem alterações) ...
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabela = $_POST['tabela'] ?? '';
    $id = $_POST['id'] ?? 0;
    $sql = '';
    $types = '';
    $params = [];

    switch ($tabela) {
        case 'instrutores':
            $nome = $_POST['nome']; $email = $_POST['email']; $especialidade = $_POST['especialidade'];
            $sql = "UPDATE instrutores SET nome = ?, email = ?, especialidade = ? WHERE id_instrutores = ?";
            $types = "sssi";
            $params = [$nome, $email, $especialidade, $id];
            break;
        case 'salas':
            $nome_sala = $_POST['nome_sala']; $descricao = $_POST['descricao']; $disponivel = isset($_POST['disponivel']) ? 1 : 0;
            $sql = "UPDATE salas SET nome_sala = ?, descricao = ?, disponivel = ? WHERE id_sala = ?";
            $types = "ssii";
            $params = [$nome_sala, $descricao, $disponivel, $id];
            break;
        case 'aulas':
            $data = $_POST['data']; $materia = $_POST['materia']; $periodo = $_POST['periodo']; $id_instrutores = $_POST['id_instrutores']; $id_sala = $_POST['id_sala'];
            $sql = "UPDATE aulas SET data = ?, materia = ?, periodo = ?, id_instrutores = ?, id_sala = ? WHERE id_aula = ?";
            $types = "sssiii";
            $params = [$data, $materia, $periodo, $id_instrutores, $id_sala, $id];
            break;
    }

    if (!empty($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $sucesso = $stmt->execute();
        $stmt->close();
        $conn->close();
        if ($sucesso) {
            header("Location: gerenciar.php?tabela=$tabela&status=success&item=" . ucfirst($tabela));
        } else {
            header("Location: gerenciar.php?tabela=$tabela&status=error&item=" . ucfirst($tabela));
        }
        exit();
    }
}

$tabela = $_GET['tabela'] ?? '';
$id = $_GET['id'] ?? 0;
$registro = null;
$config = [];

if (empty($tabela) || empty($id)) {
    $conn->close();
    die("Recurso ou ID não especificado.");
}

switch ($tabela) {
    case 'instrutores':
        $config = ['tabela_db' => 'instrutores', 'pk' => 'id_instrutores'];
        break;
    case 'salas':
        $config = ['tabela_db' => 'salas', 'pk' => 'id_sala'];
        break;
    case 'aulas':
        $config = ['tabela_db' => 'aulas', 'pk' => 'id_aula'];
        $instrutores = $conn->query("SELECT id_instrutores, nome FROM instrutores ORDER BY nome");
        $salas = $conn->query("SELECT id_sala, nome_sala FROM salas ORDER BY nome_sala");
        break;
    default: 
        $conn->close();
        die("Recurso inválido.");
}

$sql = "SELECT * FROM " . $config['tabela_db'] . " WHERE " . $config['pk'] . " = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
if ($resultado->num_rows === 1) {
    $registro = $resultado->fetch_assoc();
} else {
    $stmt->close();
    $conn->close();
    die("Registro não encontrado.");
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar <?= ucfirst($tabela) ?> - SENAI</title>
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
        <div class="headlogo"><a href="home.php"><img src="imagens/sesi_senai_negativo.png" alt="logomenu"></a></div>
        <h1>EDITAR <?= strtoupper($tabela) ?></h1>
        <button id="theme-toggle-button" class="theme-toggle-button">🌙</button>
        <nav>
            <ul>
                <li><a href="./home.php">Cadastros</a></li>
                <li><a href="gerenciar.php?tabela=<?= $tabela ?>">Voltar para Gerenciamento</a></li>
                <li><a href="index.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Editando <?= htmlspecialchars(ucfirst($tabela)) ?></h2>

            <form action="editar.php" method="POST">
                <input type="hidden" name="tabela" value="<?= $tabela ?>">
                <input type="hidden" name="id" value="<?= $id ?>">

                <?php switch ($tabela): case 'instrutores': ?>
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($registro['nome']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($registro['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="especialidade">Especialidade:</label>
                        <input type="text" id="especialidade" name="especialidade" value="<?= htmlspecialchars($registro['especialidade']) ?>">
                    </div>
                <?php break; ?>

                <?php case 'salas': ?>
                    <div class="form-group">
                        <label for="nome_sala">Nome da Sala:</label>
                        <input type="text" id="nome_sala" name="nome_sala" value="<?= htmlspecialchars($registro['nome_sala']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($registro['descricao']) ?>">
                    </div>
                    <div class="form-group-checkbox">
                        <input type="checkbox" id="disponivel" name="disponivel" <?= $registro['disponivel'] ? 'checked' : '' ?>>
                        <label for="disponivel">Disponível</label>
                    </div>
                <?php break; ?>

                <?php case 'aulas': ?>
                    <div class="form-group">
                        <label for="data">Data:</label>
                        <input type="date" id="data" name="data" value="<?= htmlspecialchars($registro['data']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="materia">Matéria:</label>
                        <input type="text" id="materia" name="materia" value="<?= htmlspecialchars($registro['materia']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="periodo">Período:</label>
                        <input type="text" id="periodo" name="periodo" value="<?= htmlspecialchars($registro['periodo']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="id_instrutores">Instrutor:</label>
                        <select id="id_instrutores" name="id_instrutores" required>
                            <?php while($instrutor = $instrutores->fetch_assoc()): ?>
                                <option value="<?= $instrutor['id_instrutores'] ?>" <?= ($registro['id_instrutores'] == $instrutor['id_instrutores']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($instrutor['nome']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_sala">Sala:</label>
                        <select id="id_sala" name="id_sala" required>
                            <?php while($sala = $salas->fetch_assoc()): ?>
                                <option value="<?= $sala['id_sala'] ?>" <?= ($registro['id_sala'] == $sala['id_sala']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sala['nome_sala']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                <?php break; ?>
                <?php endswitch; ?>

                <div class="form-group">
                    <button type="submit">Salvar Alterações</button>
                </div>
            </form>
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
<?php $conn->close(); ?>