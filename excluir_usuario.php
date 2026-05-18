<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once "conexao.php";

$id = intval($_GET['id'] ?? 0);

// Impede o admin de se auto-excluir
if ($id <= 0 || $id === intval($_SESSION['usuario_id'])) {
    header("Location: clientes.php");
    exit;
}

$stmt = $conn->prepare("UPDATE dados_login SET ativo = 0 WHERE id_login = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: clientes.php");
exit;