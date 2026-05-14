<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

require_once "conexao.php";

$usuario        = $_POST['usuario'] ?? "";
$senha_digitada = $_POST['senha']   ?? "";

if (empty($usuario) || empty($senha_digitada)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Preencha todos os campos"]);
    exit;
}

$sql  = "SELECT id_login, usuario, senha FROM dados_login WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $dados = $resultado->fetch_assoc();

    if (password_verify($senha_digitada, $dados['senha'])) {
        $_SESSION['usuario_id']   = $dados['id_login'];
        $_SESSION['usuario_nome'] = $dados['usuario'];
        echo json_encode(["sucesso" => true, "mensagem" => "Login realizado com sucesso!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Usuário ou senha inválidos"]);
    }
} else {
    $senha = password_hash($senha_digitada, PASSWORD_DEFAULT);
    $sql   = "INSERT INTO dados_login (usuario, senha) VALUES (?, ?)";
    $stmt  = $conn->prepare($sql);
    $stmt->bind_param('ss', $usuario, $senha);

    if ($stmt->execute()) {
        $_SESSION['usuario_id']   = $conn->insert_id;
        $_SESSION['usuario_nome'] = $usuario;
        echo json_encode(["sucesso" => true, "mensagem" => "Usuário cadastrado com sucesso!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao cadastrar usuário"]);
    }
}

$stmt->close();
exit;