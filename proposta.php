<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/icon.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/simulacao.css">
    <title>Proposta | CredTech</title>
    <style>
        /* STEPS */
        .steps { display:flex; align-items:center; gap:8px; margin:24px 0 32px; }
        .step  { display:flex; flex-direction:column; align-items:center; gap:6px; }
        .step-circle { width:36px; height:36px; border-radius:50%; border:2px solid #ccc; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.9rem; color:#ccc; }
        .step-label  { font-size:0.75rem; color:#aaa; white-space:nowrap; }
        .step-line   { flex:1; height:2px; background:#ddd; margin-bottom:20px; }
        .step.done .step-circle { background:#27ae60; color:white; border-color:#27ae60; }
        .step.done .step-label  { color:#27ae60; }
        .step-line.done         { background:#27ae60; }
        .step.active .step-circle { border-color:#2c3e50; color:#2c3e50; font-weight:800; }
        .step.active .step-label  { color:#2c3e50; font-weight:600; }

        /* PROPOSTA */
        .proposta-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            max-width: 680px;
            margin: 0 auto;
        }
        .proposta-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid #f0f0f0;
        }
        .proposta-header h2 { font-size: 1.4rem; color: #2c3e50; }
        .proposta-header small { color: #aaa; font-size: 0.8rem; }
        .proposta-numero { font-size: 0.8rem; color: #aaa; text-align: right; }
        .proposta-numero strong { display:block; font-size:1.1rem; color:#2c3e50; }

        .secao-titulo {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #aaa;
            letter-spacing: 1px;
            margin: 24px 0 12px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 8px;
        }
        .info-item label { font-size:0.75rem; color:#aaa; display:block; margin-bottom:2px; }
        .info-item span  { font-size:1rem; font-weight:600; color:#2c3e50; }

        .destaque {
            background: #f0faf4;
            border: 1px solid #a8dbb9;
            border-radius: 8px;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            margin: 20px 0;
            text-align: center;
        }
        .destaque label { font-size:0.72rem; color:#888; display:block; margin-bottom:4px; }
        .destaque span  { font-size:1.2rem; font-weight:800; color:#27ae60; }

        .acoes {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            flex-wrap: wrap;
        }
        .btn-acao {
            flex: 1;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-voltar  { background:#f0f0f0; color:#555; }
        .btn-pdf     { background:#e74c3c; color:white; }
        .btn-salvar  { background:#27ae60; color:white; }
        .btn-acao:hover { opacity:0.9; }
        .btn-acao:disabled { opacity:0.5; cursor:not-allowed; }

        .msg-salvo {
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            margin-top: 16px;
            font-weight: 600;
            display: none;
        }
        .msg-salvo.sucesso { background:#d5f5e3; color:#27ae60; }
        .msg-salvo.erro    { background:#fadbd8; color:#e74c3c; }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            background: #fef9e7;
            color: #f39c12;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #f9e79f;
        }

        /* ÁREA DE IMPRESSÃO */
        @media print {
            header, .steps, .acoes, .msg-salvo { display: none !important; }
            .proposta-card { box-shadow: none; padding: 0; }
            body { background: white; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="logo">
        <a href="#"><img src="img/logo.png" alt="Logo CredTech"></a>
    </div>
    <nav class="menu">
        <a href="simulador.php">Simulador</a>
        <a href="clientes.php">Clientes</a>
        <a href="#">Sobre nós</a>
        <a href="#">Central de Ajuda</a>
    </nav>
    <div class="header-buttons">
        <a href="index.php" class="btn-sair"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
</header>

<div class="page-wrapper">

    <!-- STEPS -->
    <div class="steps">
        <div class="step done">
            <div class="step-circle"><i class="fa-solid fa-check" style="font-size:0.8rem"></i></div>
            <span class="step-label">Selecionar cliente</span>
        </div>
        <div class="step-line done"></div>
        <div class="step done">
            <div class="step-circle"><i class="fa-solid fa-check" style="font-size:0.8rem"></i></div>
            <span class="step-label">Simulação</span>
        </div>
        <div class="step-line done"></div>
        <div class="step active">
            <div class="step-circle">3</div>
            <span class="step-label">Proposta</span>
        </div>
    </div>

    <!-- PROPOSTA -->
    <div class="proposta-card" id="proposta">

        <div class="proposta-header">
            <div>
                <h2><i class="fa-solid fa-file-contract"></i> Proposta de Crédito</h2>
                <small id="dataAtual"></small>
            </div>
            <div class="proposta-numero">
                <span>Nº da Proposta</span>
                <strong id="numeroProposta">—</strong>
            </div>
        </div>

        <div class="secao-titulo">Dados do Cliente</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Nome</label>
                <span id="pNome">—</span>
            </div>
            <div class="info-item">
                <label>CPF</label>
                <span id="pCpf">—</span>
            </div>
            <div class="info-item">
                <label>Telefone</label>
                <span id="pTelefone">—</span>
            </div>
            <div class="info-item">
                <label>E-mail</label>
                <span id="pEmail">—</span>
            </div>
        </div>

        <div class="secao-titulo">Condições do Empréstimo</div>
        <div class="destaque">
            <div>
                <label>Valor</label>
                <span id="pValor">—</span>
            </div>
            <div>
                <label>Parcelas</label>
                <span id="pParcelas">—</span>
            </div>
            <div>
                <label>Parcela mensal</label>
                <span id="pParcela">—</span>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <label>Taxa de juros (mensal)</label>
                <span id="pTaxa">—</span>
            </div>
            <div class="info-item">
                <label>Valor total a pagar</label>
                <span id="pTotal">—</span>
            </div>
            <div class="info-item">
                <label>Status</label>
                <span class="status-badge">Pendente</span>
            </div>
        </div>

        <div class="acoes">
            <button class="btn-acao btn-voltar" onclick="window.location.href='simulacao.php'">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </button>
            <button class="btn-acao btn-pdf" onclick="gerarPDF()">
                <i class="fa-solid fa-file-pdf"></i> Baixar PDF
            </button>
            <button class="btn-acao btn-salvar" id="btnSalvar" onclick="salvarProposta()">
                <i class="fa-solid fa-floppy-disk"></i> Salvar Proposta
            </button>
        </div>

        <div class="msg-salvo" id="msgSalvo"></div>

    </div>
</div>

<script>
const clienteSalvo  = sessionStorage.getItem("clienteSelecionado");
const simulacaoSalva = sessionStorage.getItem("simulacao");

if (!clienteSalvo || !simulacaoSalva) {
    window.location.href = "simulador.php";
}

const cliente  = JSON.parse(clienteSalvo);
const sim      = JSON.parse(simulacaoSalva);

function format(v) {
    return "R$ " + parseFloat(v).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
}

function formatPct(v) {
    return (parseFloat(v) * 100).toFixed(2).replace('.', ',') + "% a.m.";
}

// Número de proposta temporário (será substituído pelo ID do banco após salvar)
const numTemp = "TEMP-" + Date.now().toString().slice(-6);
document.getElementById("numeroProposta").innerText = numTemp;

// Data atual
const agora = new Date();
document.getElementById("dataAtual").innerText = agora.toLocaleDateString('pt-BR', {
    day: '2-digit', month: 'long', year: 'numeric'
});

// Preenche dados do cliente
document.getElementById("pNome").innerText     = cliente.nome;
document.getElementById("pCpf").innerText      = cliente.cpf;
document.getElementById("pTelefone").innerText = cliente.telefone;
document.getElementById("pEmail").innerText    = cliente.email;

// Preenche dados da simulação
document.getElementById("pValor").innerText    = format(sim.valor);
document.getElementById("pParcelas").innerText = sim.parcelas + "x";
document.getElementById("pParcela").innerText  = format(sim.valor_parcela);
document.getElementById("pTaxa").innerText     = formatPct(sim.taxa_juros);
document.getElementById("pTotal").innerText    = format(sim.valor_total);

// Gerar PDF via impressão
function gerarPDF() {
    window.print();
}

// Salvar proposta no banco
function salvarProposta() {
    const btn = document.getElementById("btnSalvar");
    const msg = document.getElementById("msgSalvo");

    btn.disabled    = true;
    btn.innerHTML   = '<i class="fa-solid fa-spinner fa-spin"></i> Salvando...';

    const body = new URLSearchParams({
        id_cliente:    cliente.id,
        valor:         sim.valor,
        parcelas:      sim.parcelas,
        valor_parcela: sim.valor_parcela,
        valor_total:   sim.valor_total,
        taxa_juros:    sim.taxa_juros
    });

    fetch("salvar_proposta.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body
    })
    .then(res => res.json())
    .then(data => {
        msg.style.display = "block";
        if (data.sucesso) {
            msg.className = "msg-salvo sucesso";
            msg.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + data.mensagem;
            document.getElementById("numeroProposta").innerText = "#" + data.id_proposta;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Salvo!';
        } else {
            msg.className = "msg-salvo erro";
            msg.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> ' + data.mensagem;
            btn.disabled  = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Salvar Proposta';
        }
    })
    .catch(() => {
        msg.style.display = "block";
        msg.className     = "msg-salvo erro";
        msg.innerHTML     = '<i class="fa-solid fa-circle-xmark"></i> Erro de conexão com o servidor.';
        btn.disabled      = false;
        btn.innerHTML     = '<i class="fa-solid fa-floppy-disk"></i> Salvar Proposta';
    });
}
</script>

</body>
</html>