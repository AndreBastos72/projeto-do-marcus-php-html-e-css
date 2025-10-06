<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Preparar e executar a exclusão
    $sql = "DELETE FROM contatos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = "✅ Contato excluído com sucesso!";
    } else {
        $mensagem = "❌ Erro ao excluir contato: " . $conn->error;
    }
    
    $stmt->close();
}

// Redirecionar de volta para a lista
header("Location: index.php");
exit();
?>