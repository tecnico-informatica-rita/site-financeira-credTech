<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

require_once "conexao.php";

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    echo json_encode(["sucesso" => false, "mensagem" => "Acesso negado."]);
    exit;
}

$id_login    = intval($_POST['id_login']  ?? 0);
$usuario     = trim($_POST['usuario']     ?? '');
$tipo        = $_POST['tipo']             ?? 'usuario';
$ativo       = intval($_POST['ativo']     ?? 1);
$nova_senha  = $_POST['nova_senha']       ?? '';

if ($id_login <= 0 || empty($usuario)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos."]);
    exit;
}

// Verifica se o nome de usuário já existe em outro registro
$check = $conn->prepare("SELECT id_login FROM dados_login WHERE usuario = ? AND id_login != ?");
$check->bind_param("si", $usuario, $id_login);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(["sucesso" => false, "mensagem" => "Este nome de usuário já está em uso."]);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// Atualiza com ou sem senha
if (!empty($nova_senha)) {
    $hash = password_hash($nova_senha, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE dados_login SET usuario=?, senha=?, tipo=?, ativo=? WHERE id_login=?");
    $stmt->bind_param("sssii", $usuario, $hash, $tipo, $ativo, $id_login);
} else {
    $stmt = $conn->prepare("UPDATE dados_login SET usuario=?, tipo=?, ativo=? WHERE id_login=?");
    $stmt->bind_param("ssii", $usuario, $tipo, $ativo, $id_login);
}

if ($stmt->execute()) {
    echo json_encode(["sucesso" => true, "mensagem" => "Usuário atualizado com sucesso!"]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao atualizar: " . $stmt->error]);
}

$stmt->close();
$conn->close();