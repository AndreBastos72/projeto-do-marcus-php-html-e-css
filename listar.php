<?php
include 'conexao.php';

// Buscar todos os contatos
$sql = "SELECT * FROM contatos ORDER BY nome";
$result = $conn->query($sql);

// Configurações para exportação
$exportar_csv = isset($_GET['exportar']) && $_GET['exportar'] == 'csv';
$exportar_pdf = isset($_GET['exportar']) && $_GET['exportar'] == 'pdf';

// Exportar para CSV
if ($exportar_csv) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=contatos.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Nome', 'Telefone', 'Email', 'Data de Criação'), ';');
    
    while($row = $result->fetch_assoc()) {
        fputcsv($output, array(
            $row['id'],
            $row['nome'],
            $row['telefone'],
            $row['email'],
            date('d/m/Y H:i', strtotime($row['data_criacao']))
        ), ';');
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Contatos - Agenda</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--dark);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 30px;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header h1 {
            color: var(--primary);
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 700;
        }

        header p {
            color: var(--gray);
            font-size: 1.1em;
        }

        /* Botões */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #3ab0d0;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background: #e08c00;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #e1156d;
        }

        .btn-secondary {
            background: var(--gray);
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Ações do Header */
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .export-actions {
            display: flex;
            gap: 10px;
        }

        /* Stats */
        .stats {
            background: var(--light);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }

        /* Tabela */
        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .contatos-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .contatos-table th {
            background: var(--primary);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border: none;
        }

        .contatos-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .contatos-table tr:hover {
            background: #f8f9fa;
        }

        .contatos-table tr:last-child td {
            border-bottom: none;
        }

        /* Avatar */
        .avatar {
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1em;
        }

        /* Ações da Tabela */
        .table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Estados Vazios */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
        }

        .empty-icon {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: var(--dark);
        }

        /* Badges */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-secondary {
            background: #e2e3e5;
            color: #383d41;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin: 10px;
            }
            
            header h1 {
                font-size: 2em;
            }
            
            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .export-actions {
                justify-content: center;
            }
            
            .contatos-table {
                font-size: 0.9em;
            }
            
            .table-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .contatos-table tr {
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-list"></i> Lista de Contatos</h1>
            <p>Visualize e gerencie todos os seus contatos</p>
        </header>

        <div class="header-actions">
            <div>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar para Agenda
                </a>
                <a href="criar.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Contato
                </a>
            </div>
            
            <div class="export-actions">
                <a href="?exportar=csv" class="btn btn-success">
                    <i class="fas fa-file-csv"></i> Exportar CSV
                </a>
                <a href="javascript:window.print()" class="btn btn-warning">
                    <i class="fas fa-print"></i> Imprimir
                </a>
            </div>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="stats">
                <i class="fas fa-users"></i> Total de <?php echo $result->num_rows; ?> 
                <?php echo $result->num_rows == 1 ? 'contato' : 'contatos'; ?> cadastrados
            </div>

            <div class="table-container">
                <table class="contatos-table">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Contato</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Data de Criação</th>
                            <th width="200">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $contador = 1;
                        $result->data_seek(0); // Reset do ponteiro do resultado
                        while($row = $result->fetch_assoc()): 
                        ?>
                            <tr>
                                <td>
                                    <div class="avatar">
                                        <?php echo strtoupper(substr($row['nome'], 0, 1)); ?>
                                    </div>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['nome']); ?></strong>
                                    <br>
                                    <span class="badge badge-secondary">ID: <?php echo $row['id']; ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($row['telefone'])): ?>
                                        <i class="fas fa-phone"></i> 
                                        <?php echo htmlspecialchars($row['telefone']); ?>
                                    <?php else: ?>
                                        <span style="color: var(--gray);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['email'])): ?>
                                        <i class="fas fa-envelope"></i> 
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    <?php else: ?>
                                        <span style="color: var(--gray);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($row['data_criacao'])); ?>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="editar.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-warning" style="padding: 8px 12px;">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a href="excluir.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-danger" style="padding: 8px 12px;"
                                           onclick="return confirm('Tem certeza que deseja excluir <?php echo htmlspecialchars($row['nome']); ?>?')">
                                            <i class="fas fa-trash"></i> Excluir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                        $contador++;
                        endwhile; 
                        ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3>Nenhum contato cadastrado</h3>
                <p>Comece adicionando seu primeiro contato à agenda</p>
                <a href="criar.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Adicionar Primeiro Contato
                </a>
            </div>
        <?php endif; ?>
    </div>

    <footer style="text-align: center; margin-top: 30px; color: white;">
        <p>Sistema de Agenda de Contatos &copy; 2024</p>
    </footer>

    <script>
        // Adiciona efeito de hover nas linhas da tabela
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.contatos-table tr');
            rows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                    this.style.transition = 'transform 0.2s ease';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>