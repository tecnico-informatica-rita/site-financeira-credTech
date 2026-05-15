<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

require_once "conexao.php";

// 1. Verifica sessão
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Sessão expirada. Faça login novamente."]);
    exit;
}

// 2. Coleta os dados
$id_criador = $_SESSION['usuario_id'];
$nome       = $_POST['nome'] ?? '';
$cpf        = $_POST['cpf'] ?? '';
$telefone   = $_POST['telefone'] ?? '';
$email      = $_POST['email'] ?? '';
$cep        = $_POST['cep'] ?? '';
$endereco   = $_POST['endereco'] ?? '';
$numero     = $_POST['numero'] ?? '';
$bairro     = $_POST['bairro'] ?? '';
$cidade     = $_POST['cidade'] ?? '';
$estado     = $_POST['estado'] ?? '';
$tipo_p     = $_POST['tipo'] ?? '';

// 3. Validação básica
if (empty($nome) || empty($cpf) || empty($id_criador)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Campos obrigatórios (Nome, CPF) não preenchidos."]);
    exit;
}

// 4. Verifica se o CPF já existe para esse usuário
$check = $conn->prepare("SELECT id_cliente FROM info_clientes WHERE cpf = ? AND id_usuario_criador = ?");
$check->bind_param("si", $cpf, $id_criador);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["sucesso" => false, "mensagem" => "Este CPF já está cadastrado na sua base de clientes."]);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// 5. Prepara o INSERT
$sql = "INSERT INTO info_clientes 
        (id_usuario_criador, nome, cpf, telefone, email, cep, endereco, numero, bairro, cidade, estado, tipo_pessoa) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro na preparação do banco: " . $conn->error]);
    exit;
}

// 6. Bind e execução
$stmt->bind_param("isssssssssss",
    $id_criador,
    $nome,
    $cpf,
    $telefone,
    $email,
    $cep,
    $endereco,
    $numero,
    $bairro,
    $cidade,
    $estado,
    $tipo_p
);

// 7. Executa e retorna resposta
if ($stmt->execute()) {
    echo json_encode(["sucesso" => true, "mensagem" => "Cliente cadastrado com sucesso!"]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar: " . $stmt->error]);
}

// 8. Fecha conexões
$stmt->close();
$conn->close();