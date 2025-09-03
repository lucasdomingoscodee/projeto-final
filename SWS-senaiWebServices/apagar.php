<?php
require_once 'conexao.php';

// 1. VERIFICAR SE A TABELA E O ID FORAM ENVIADOS
if (isset($_GET['tabela']) && isset($_GET['id'])) {
    $tabela = $_GET['tabela'];
    $id = $_GET['id'];
    
    $config = [];

    // 2. CONFIGURAR OS DETALHES DA TABELA PARA SEGURANÇA
    switch ($tabela) {
        case 'instrutores':
            $config = ['tabela_db' => 'instrutores', 'pk' => 'id_instrutores'];
            break;
        case 'salas':
            $config = ['tabela_db' => 'salas', 'pk' => 'id_sala'];
            break;
        case 'aulas':
            $config = ['tabela_db' => 'aulas', 'pk' => 'id_aula'];
            break;
        default:
            // Se a tabela for desconhecida, para a execução
            header("Location: gerenciar.php?status=error&item=Recurso&action=excluir (tabela inválida)");
            exit();
    }

    // 3. EXECUTAR O COMANDO DELETE DE FORMA SEGURA
    if (!empty($config)) {
        $sql = "DELETE FROM " . $config['tabela_db'] . " WHERE " . $config['pk'] . " = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id); // "i" para integer
        
        $sucesso = $stmt->execute();
        
        $stmt->close();
        $conn->close();

        // 4. REDIRECIONAR DE VOLTA PARA A LISTA COM UMA MENSAGEM
        if ($sucesso) {
            header("Location: gerenciar.php?tabela=$tabela&status=success&item=" . ucfirst($tabela) . "&action=excluído");
        } else {
            header("Location: gerenciar.php?tabela=$tabela&status=error&item=" . ucfirst($tabela) . "&action=excluir");
        }
        exit();
    }
} else {
    // Se faltar algum parâmetro, redireciona com erro
    header("Location: gerenciar.php?status=error&action=excluir (parâmetros ausentes)");
    exit();
}
?>