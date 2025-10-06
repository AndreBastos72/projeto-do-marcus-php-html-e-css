<?php
include 'conexao.php';

// Buscar todos os contatos
$sql = "SELECT * FROM contatos ORDER BY nome";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Contatos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📒 Agenda de Contatos</h1>
        
        <div class="header-actions">
            <a href="criar.php" class="btn btn-primary">➕ Novo Contato</a>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="contatos-grid">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="contato-card">
                        <div class="contato-info">
                            <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                            <p>📞 <?php echo htmlspecialchars($row['telefone']); ?></p>
                            <p>📧 <?php echo htmlspecialchars($row['email']); ?></p>
                            <small>Criado em: <?php echo date('d/m/Y', strtotime($row['data_criacao'])); ?></small>
                        </div>
                        <div class="contato-actions">
                            <a href="editar.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">✏️ Editar</a>
                            <a href="excluir.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Tem certeza que deseja excluir este contato?')">
                               🗑️ Excluir
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>Nenhum contato cadastrado ainda.</p>
                <a href="criar.php" class="btn btn-primary">Adicionar Primeiro Contato</a>
                <div class="header-actions">
    <a href="listar.php" class="btn btn-success">📋 Lista Completa</a>
</div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>