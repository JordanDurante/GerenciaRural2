<?php
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$database = "nome_do_seu_banco_de_dados";

// Criar conex�o
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conex�o
if ($conn->connect_error) {
    die("Conex�o falhou: " . $conn->connect_error);
}
echo "Conectado com sucesso";
?>
