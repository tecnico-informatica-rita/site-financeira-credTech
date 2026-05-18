<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

require_once "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Sessão expirada. Faça login novamente."]);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$tipo       = $_SESSION['tipo'] ?? 'usuario';

$id_cliente = intval($_POST['id_cliente'] ?? 0);
$nome       = $_POST['nome']     ?? '';
$cpf        = $_POST['cpf']      ?? '';
$telefone   = $_POST['telefone'] ?? '';
$email      = $_POST['email']    ?? '';
$cep        = $_POST['cep']      ?? '';
$endereco   = $_POST['endereco'] ?? '';
$numero     = $_POST['numero']   ?? '';
$bairro     = $_POST['bairro']   ?? '';
$cidade     = $_POST['cidade']   ?? '';
$estado     = $_POST['estado']   ?? '';
$tipo_p     = $_POST['tipo']     ?? '';

if ($id_cliente <= 0 || empty($nome) || empty($cpf)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos."]);
    exit;
}

// Verifica CPF duplicado para o mesmo usuário (exceto o próprio cliente sendo editado)
if ($tipo === 'admin') {
    $check = $conn->prepare("SELECT id_cliente FROM info_clientes WHERE cpf = ? AND id_cliente != ? AND ativo = 1");
    $check->bind_param("si", $cpf, $id_cliente);
} else {
    $check = $conn->prepare("SELECT id_cliente FROM info_clientes WHERE cpf = ? AND id_usuario_criador = ? AND id_cliente != ? AND ativo = 1");
    $check->bind_param("sii", $cpf, $id_usuario, $id_cliente);
}
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(["sucesso" => false, "mensagem" => "Este CPF já está cadastrado na sua base de clientes."]);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// Admin pode editar qualquer cliente; usuário só os seus
if ($tipo === 'admin') {
    $sql  = "UPDATE info_clientes SET nome=?, cpf=?, telefone=?, email=?, cep=?, endereco=?, numero=?, bairro=?, cidade=?, estado=?, tipo_pessoa=? WHERE id_cliente=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssi", $nome, $cpf, $telefone, $email, $cep, $endereco, $numero, $bairro, $cidade, $estado, $tipo_p, $id_cliente);
} else {
    $sql  = "UPDATE info_clientes SET nome=?, cpf=?, telefone=?, email=?, cep=?, endereco=?, numero=?, bairro=?, cidade=?, estado=?, tipo_pessoa=? WHERE id_cliente=? AND id_usuario_criador=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssii", $nome, $cpf, $telefone, $email, $cep, $endereco, $numero, $bairro, $cidade, $estado, $tipo_p, $id_cliente, $id_usuario);
}

if ($stmt->execute()) {
    echo json_encode(["sucesso" => true, "mensagem" => "Cliente atualizado com sucesso!"]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao atualizar: " . $stmt->error]);
}

$stmt->close();
$conn->close();