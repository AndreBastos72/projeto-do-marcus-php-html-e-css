<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "agenda_contatos";

// Criar conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se a tabela existe, se não, criar
$table_check = $conn->query("SHOW TABLES LIKE 'contatos'");
if ($table_check->num_rows == 0) {
    // Tabela não existe, vamos criar
    $sql = "CREATE TABLE contatos (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        telefone VARCHAR(20),
        email VARCHAR(100),
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "<!-- Tabela 'contatos' criada com sucesso -->";
    } else {
        die("Erro ao criar tabela: " . $conn->error);
    }
}

// Agora a tabela existe, podemos trabalhar
?>