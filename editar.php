<?php
include 'conexao.php';

$mensagem = "";
$contato = null;

// Buscar contato para edição
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM contatos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $contato = $result->fetch_assoc();
    } else {
        die("Contato não encontrado!");
    }
    $stmt->close();
}

// Processar atualização
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);

    if (!empty($nome)) {
        $sql = "UPDATE contatos SET nome = ?, telefone = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $telefone, $email, $id);

        if ($stmt->execute()) {
            header("Location: index.php?sucesso=Contato atualizado com sucesso!");
            exit();
        } else {
            $mensagem = "Erro ao atualizar contato: " . $conn->error;
        }
        $stmt->close();
    } else {
        $mensagem = "O nome é obrigatório!";
    }
}

if (!$contato) {
    die("Contato não encontrado!");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contato - Agenda</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-edit"></i> Editar Contato</h1>
            <p>Atualize as informações do contato</p>
        </header>

        <div class="form-container">
            <?php if (!empty($mensagem)): ?>
                <div class="mensagem erro"><?php echo $mensagem; ?></div>
            <?php endif; ?>

            <form method="POST" class="contato-form">
                <input type="hidden" name="id" value="<?php echo $contato['id']; ?>">
                
                <div class="form-group">
                    <label for="nome"><i class="fas fa-user"></i> Nome *</label>
                    <input type="text" id="nome" name="nome" 
                           value="<?php echo htmlspecialchars($contato['nome']); ?>" 
                           required placeholder="Digite o nome completo">
                </div>

                <div class="form-group">
                    <label for="telefone"><i class="fas fa-phone"></i> Telefone</label>
                    <input type="tel" id="telefone" name="telefone" 
                           value="<?php echo htmlspecialchars($contato['telefone']); ?>"
                           placeholder="(11) 99999-9999">
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($contato['email']); ?>"
                           placeholder="exemplo@email.com">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Atualizar Contato
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>