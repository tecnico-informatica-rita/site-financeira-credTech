<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
require_once "conexao.php";

$id_usuario = $_SESSION['usuario_id'];
$tipo       = $_SESSION['tipo'] ?? 'usuario';

// Admin vê todos os clientes, usuário só os seus
if ($tipo === 'admin') {
    $stmt = $conn->prepare("SELECT id_cliente, nome, cpf, telefone, email, cidade, estado, tipo_pessoa FROM info_clientes WHERE ativo = 1 ORDER BY nome ASC");
} else {
    $stmt = $conn->prepare("SELECT id_cliente, nome, cpf, telefone, email, cidade, estado, tipo_pessoa FROM info_clientes WHERE id_usuario_criador = ? AND ativo = 1 ORDER BY nome ASC");
    $stmt->bind_param("i", $id_usuario);
}
$stmt->execute();
$clientes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/icon.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/simulador.css">
    <title>Simulador | CredTech</title>
    <style>
        .step.done .step-circle { background:#27ae60; color:white; border-color:#27ae60; }
        .step.done .step-label  { color:#27ae60; }
        .step-line.done         { background:#27ae60; }
        .vazio { text-align:center; padding:60px 20px; color:#aaa; }
        .vazio i { font-size:2.5rem; margin-bottom:12px; display:block; }
    </style>
</head>
<body>

<header class="header">
    <div class="logo">
        <a href="home.php"><img src="img/logo.png" alt="Logo CredTech"></a>
    </div>
    <nav class="menu">
        <a href="simulador.php" class="active">Simulador</a>
        <a href="clientes.php">Clientes</a>
        <a href="sobre_nos.html">Sobre nós</a>
        <a href="central_ajuda.html">Central de Ajuda</a>
    </nav>
    <a class="btn-sair" href="index.php"><i class="fa-solid fa-right-from-bracket"></i></a>
</header>

<main class="page-wrapper">

    <div class="page-header">
        <div class="breadcrumb">
            <a href="#">Início</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Simulador</span>
        </div>
        <span class="label">Passo 1 de 3</span>
        <h1>Selecione um <span>cliente</span></h1>
        <p>Escolha o cliente para simulação.</p>
    </div>

    <!-- STEPS -->
    <div class="steps">
        <div class="step active">
            <div class="step-circle">1</div>
            <span class="step-label">Selecionar cliente</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="step-circle">2</div>
            <span class="step-label">Simulação</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="step-circle">3</div>
            <span class="step-label">Proposta</span>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="toolbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="search" placeholder="Buscar cliente...">
        </div>
        <a href="dados_cliente.php" class="btn-novo">
            <i class="fa-solid fa-plus"></i> Novo cliente
        </a>
    </div>

    <p class="clients-count"><strong id="total"><?= count($clientes) ?></strong> clientes encontrados</p>

    <!-- GRID -->
    <div class="clients-grid" id="grid">
        <?php if (empty($clientes)): ?>
            <div class="vazio">
                <i class="fa-solid fa-users-slash"></i>
                Nenhum cliente cadastrado ainda.<br>
                <a href="dados_cliente.php">Cadastrar primeiro cliente</a>
            </div>
        <?php else: ?>
            <?php foreach ($clientes as $c):
                $iniciais = '';
                foreach (explode(' ', $c['nome']) as $parte) $iniciais .= strtoupper($parte[0]);
                $iniciais = substr($iniciais, 0, 2);
            ?>
            <div class="client-card"
                data-id="<?= $c['id_cliente'] ?>"
                data-nome="<?= htmlspecialchars($c['nome']) ?>"
                data-cpf="<?= htmlspecialchars($c['cpf']) ?>"
                data-email="<?= htmlspecialchars($c['email']) ?>"
                data-telefone="<?= htmlspecialchars($c['telefone']) ?>"
                data-busca="<?= strtolower($c['nome'] . $c['cpf'] . $c['email']) ?>"
                onclick="select(this)">
                <div class="client-avatar"><?= $iniciais ?></div>
                <div class="client-name"><?= htmlspecialchars($c['nome']) ?></div>
                <div class="client-meta">
                    <span><i class="fa-solid fa-envelope"></i><?= htmlspecialchars($c['email']) ?></span>
                    <span><i class="fa-solid fa-phone"></i><?= htmlspecialchars($c['telefone']) ?></span>
                </div>
                <div class="client-cpf"><i class="fa-solid fa-id-card"></i><?= htmlspecialchars($c['cpf']) ?></div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</main>

<!-- FLOAT BAR -->
<div class="float-bar" id="floatBar">
    <div class="float-bar-info">
        <div class="float-bar-avatar" id="avatar">?</div>
        <div class="float-bar-text">
            <p>Cliente selecionado</p>
            <strong id="nomeSel">—</strong>
        </div>
    </div>
    <button class="btn-continuar" onclick="irSimulacao()">
        Continuar <i class="fa-solid fa-arrow-right"></i>
    </button>
</div>

<script>
let selected = null;

function select(el) {
    document.querySelectorAll(".client-card").forEach(e => e.classList.remove("selected"));
    el.classList.add("selected");

    selected = {
        id:       el.dataset.id,
        nome:     el.dataset.nome,
        cpf:      el.dataset.cpf,
        email:    el.dataset.email,
        telefone: el.dataset.telefone
    };

    document.getElementById("avatar").innerText  = selected.nome.split(" ").map(n => n[0]).slice(0,2).join("");
    document.getElementById("nomeSel").innerText = selected.nome;
    document.getElementById("floatBar").classList.add("show");
}

function irSimulacao() {
    if (!selected) return;
    sessionStorage.setItem("clienteSelecionado", JSON.stringify(selected));
    window.location.href = "simulacao.php";
}

// Busca
document.getElementById("search").addEventListener("input", function() {
    const q     = this.value.toLowerCase();
    const cards = document.querySelectorAll(".client-card");
    let visiveis = 0;

    cards.forEach(card => {
        const match = card.dataset.busca.includes(q);
        card.style.display = match ? "" : "none";
        if (match) visiveis++;
    });

    document.getElementById("total").innerText = visiveis;
});
</script>

</body>
</html>