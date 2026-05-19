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

$id_usuario   = $_SESSION['usuario_id'];
$id_cliente   = intval($_POST['id_cliente']   ?? 0);
$valor        = floatval($_POST['valor']       ?? 0);
$parcelas     = intval($_POST['parcelas']      ?? 0);
$valor_parcela = floatval($_POST['valor_parcela'] ?? 0);
$valor_total  = floatval($_POST['valor_total'] ?? 0);
$taxa_juros   = floatval($_POST['taxa_juros']  ?? 0);

if ($id_cliente <= 0 || $valor <= 0 || $parcelas <= 0) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos."]);
    exit;
}

$sql  = "INSERT INTO propostas (id_cliente, id_usuario, valor, parcelas, valor_parcela, valor_total, taxa_juros) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iididdd", $id_cliente, $id_usuario, $valor, $parcelas, $valor_parcela, $valor_total, $taxa_juros);

if ($stmt->execute()) {
    $id_proposta = $conn->insert_id;
    echo json_encode(["sucesso" => true, "mensagem" => "Proposta salva com sucesso!", "id_proposta" => $id_proposta]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar: " . $stmt->error]);
}

$stmt->close();
$conn->close();