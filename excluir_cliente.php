<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require_once "conexao.php";

$id         = intval($_GET['id'] ?? 0);
$id_usuario = $_SESSION['usuario_id'];
$tipo       = $_SESSION['tipo'] ?? 'usuario';

if ($id <= 0) {
    header("Location: clientes.php");
    exit;
}

// Admin pode excluir qualquer cliente; usuário só os seus
if ($tipo === 'admin') {
    $stmt = $conn->prepare("UPDATE info_clientes SET ativo = 0 WHERE id_cliente = ?");
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conn->prepare("UPDATE info_clientes SET ativo = 0 WHERE id_cliente = ? AND id_usuario_criador = ?");
    $stmt->bind_param("ii", $id, $id_usuario);
}

$stmt->execute();
$stmt->close();
$conn->close();

header("Location: clientes.php");
exit;