<?php
include 'conexao.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    // Validar dados
    if (!empty($nome)) {
        $sql = "INSERT INTO contatos (nome, telefone, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $telefone, $email);

        if ($stmt->execute()) {
            $mensagem = "✅ Contato criado com sucesso!";
            header("Location: index.php");
            exit();
        } else {
            $mensagem = "❌ Erro ao criar contato: " . $conn->error;
        }
        $stmt->close();
    } else {
        $mensagem = "⚠️ O nome é obrigatório!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Contato - Agenda</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>➕ Criar Novo Contato</h1>
        
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <form method="POST" class="contato-form">
            <div class="form-group">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="tel" id="telefone" name="telefone">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Salvar Contato</button>
                <a href="index.php" class="btn btn-secondary">↩️ Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>