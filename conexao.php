<?php

require_once "config.php";

$conn = new my sqli(DB_HOST, DB_USER, DB_PASS, DB_BANK);
if($conn->connect_error){
    die ("Erro ao conectar com o banco de dados" . $conn->connect_error);
}

echo "banco conectado";

?>