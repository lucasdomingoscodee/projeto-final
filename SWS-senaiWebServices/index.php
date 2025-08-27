<?php
$erro = isset($_GET['erro']) ? $_GET['erro'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <title>Login Web Services</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <header>
        <img src="imagens/sesi_senai_negativo.png" alt="logosesisenai"><br>
        <h2>SENAI Web Services</h2>
    </header>
    
    <div class="entrar">
    <?php if (!empty($erro)): ?>
            <div style="color: red; padding: 10px; margin-bottom: 15px;">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>
            <h2>Insira seu login para entrar.</h2>
            <br><br>
        <form action="processos/login.php" method="POST">
            
            <label>Digite seu usuário: </label>
            <input type="text" id="user" name="user" required><br><br>

            <label>Digite sua senha: </label>
            <input type="password" id="senha" name="senha" required><br><br>

            <button type="Entrar">Entrar</button>
        </form>
    </div>
    
</body>

</html>