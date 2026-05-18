<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="img/icon.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <title>Simulação | CredTech</title>
    <link rel="stylesheet" href="css/simulador.css">

    <style>
        /* STEPS */
        .step.done .step-circle {
            background: #27ae60;
            color: white;
            border-color: #27ae60;
        }
        .step.done .step-label {
            color: #27ae60;
        }
        .step-line.done {
            background: #27ae60;
        }
    </style>
</head>

<body>

<header class="header">
    <div class="logo">
        <a href="#">
            <img src="img/logo.png" alt="Logo CredTech">
        </a>
    </div>
    <nav class="menu">
        <a href="simulador.html" class="active">Simulador</a>
        <a href="clientes.php">Clientes</a>
        <a href="sobre_nos.html">Sobre nós</a>
        <a href="central_ajuda.html">Central de Ajuda</a>
    </nav>
    <a class="btn-sair" href="#"><i class="fa-solid fa-right-from-bracket"></i></a>
</header>

<main class="page-wrapper">

    <div class="page-header">
        <div class="breadcrumb">
            <a href="#">Início</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Simulador</span>
        </div>

        <span class="label">Passo 1 de 2</span>
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

    <p class="clients-count">
        <strong id="total"></strong> clientes encontrados
    </p>

    <div class="clients-grid" id="grid"></div>

</main>

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

<div class="toast" id="toast">
    <i class="fa-solid fa-circle-check"></i>
    <span id="toastMsg"></span>
</div>

<script>
let clientes = [
    { id: 1, nome: "Maria Oliveira", cpf: "111", email: "maria@email.com", telefone: "119999" },
    { id: 2, nome: "João Silva",     cpf: "222", email: "joao@email.com",  telefone: "219888" },
    { id: 3, nome: "Ana Souza",      cpf: "333", email: "ana@email.com",   telefone: "319777" }
];

let selected = null;

function render(list = clientes) {
    const grid = document.getElementById("grid");
    grid.innerHTML = "";
    document.getElementById("total").innerText = list.length;

    list.forEach(c => {
        const card = document.createElement("div");
        card.className = "client-card";
        const iniciais = c.nome.split(" ").map(n => n[0]).slice(0,2).join("");
        card.innerHTML = `
            <div class="client-avatar">${iniciais}</div>
            <div class="client-name">${c.nome}</div>
            <div class="client-meta">
                <span><i class="fa-solid fa-envelope"></i>${c.email}</span>
                <span><i class="fa-solid fa-phone"></i>${c.telefone}</span>
            </div>
            <div class="client-cpf"><i class="fa-solid fa-id-card"></i>${c.cpf}</div>
        `;
        card.onclick = () => select(c, card);
        grid.appendChild(card);
    });
}

function select(c, el) {
    document.querySelectorAll(".client-card").forEach(e => e.classList.remove("selected"));
    el.classList.add("selected");
    selected = c;
    document.getElementById("avatar").innerText = c.nome[0];
    document.getElementById("nomeSel").innerText = c.nome;
    document.getElementById("floatBar").classList.add("show");
}

document.getElementById("search").addEventListener("input", e => {
    const q = e.target.value.toLowerCase();
    render(clientes.filter(c =>
        (c.nome + c.cpf + c.email).toLowerCase().includes(q)
    ));
});

function irSimulacao() {
    if (!selected) return;
    sessionStorage.setItem("clienteSelecionado", JSON.stringify(selected));
    window.location.href = "simulacao.html";
}

function toast(msg) {
    const t = document.getElementById("toast");
    document.getElementById("toastMsg").innerText = msg;
    t.classList.add("show");
    setTimeout(() => t.classList.remove("show"), 2500);
}

render();
</script>

</body>
</html>