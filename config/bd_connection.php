<?php
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$database = "nome_do_seu_banco_de_dados";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
echo "Conectado com sucesso";
?>
