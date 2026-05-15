<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);
require_once "conexao.php"; // Verifique se o nome do arquivo de conexão está correto

// 1. Verifica se o usuário está logado para capturar o ID da sessão
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Sessão expirada. Faça login novamente."]);
    exit;
}

// 2. Coleta os dados enviados pelo formulário (via FormData no JS)
// O operador ?? '' garante que a variável não fique nula caso o campo venha vazio
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

// 3. Validação simples para evitar campos vazios no banco (trava extra)
if (empty($nome) || empty($cpf) || empty($id_criador)) {
    echo json_encode(["sucesso" => false, "mensagem" => "Campos obrigatórios (Nome, CPF) não preenchidos."]);
    exit;
}

// 4. Prepara a Query SQL
$sql = "INSERT INTO info_clientes 
        (id_usuario_criador, nome, cpf, telefone, email, cep, endereco, numero, bairro, cidade, estado, tipo_pessoa) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro na preparação do banco: " . $conn->error]);
    exit;
}

// 5. Faz a vinculação dos parâmetros (bind_param)
// "i" para inteiro (ID), "s" para strings (o restante)
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

// 6. Executa a gravação e retorna a resposta para o JavaScript
if ($stmt->execute()) {
    echo json_encode(["sucesso" => true, "mensagem" => "Cliente cadastrado com sucesso!"]);
} else {
    // Tratamento de erro de CPF duplicado (Código de erro MySQL 1062)
    if ($conn->errno == 1062) {
        echo json_encode(["sucesso" => false, "mensagem" => "Este CPF já consta em nossa base de dados."]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar: " . $conn->error]);
    }
}

// 7. Fecha as conexões
$stmt->close();
$conn->close();