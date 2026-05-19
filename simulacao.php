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
    <title>Simulação | CredTech</title>
    <style>
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
        /* ========================= Header ========================= */
.header{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 25px 80px;
    background: #FFFFFF;
    box-shadow:0 4px 20px rgba(0,0,0,0.04);
    position: sticky;
    top: 0;
    z-index: 1000;
}
.menu{
    display:flex;
    align-items:center;
    gap:40px;
}
.menu a{
    position: relative;
    font-size: 15px;
    font-weight: 600;
    color: #4b5563;
    transition: 0.3s;
}
.menu a:hover{
    color: #FF6B00;
}
.menu a::after{
    content: "";
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0%;
    height: 2px;
    background: #FF6B00;
    transition: 0.3s;
}
.menu a:hover::after{
    width: 100%;
}
.btn-sair{
    width:45px;
    height:45px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#FF6B00;
    border-radius:12px;
    font-size:18px;
    transition:0.3s;
}
.btn-sair:hover{
    background:#FF6B00;
    color:#FFFFFF;
    transform:translateY(-2px);
}
.logo img{
    width: 150px;
    object-fit: contain;
}

/* ========================= FOOTER ========================= */
.footer {
    background: #ffffff;
    padding-top: 80px;
}
.footer-cta {
    max-width: 1200px;
    margin: 0 auto 80px auto;
    position: relative;
    border-radius: 32px;
    overflow: hidden;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 40px 20px;
    background: #FF6B00; 
}
.video-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
}
.footer-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,107,0,0.85), rgba(255,140,0,0.85));
    z-index: 2;
}
.footer-cta-content {
    position: relative;
    z-index: 3;
    color: #ffffff;
    max-width: 600px;
}
.footer-cta-content h2 {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 20px;
    letter-spacing: -1px;
}
.footer-cta-content p {
    font-size: 18px;
    margin-bottom: 35px;
    opacity: 0.95;
    line-height: 1.6;
}
.btn-cta-footer {
    background: #ffffff;
    color: #111827;
    padding: 16px 36px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}
.btn-cta-footer:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    color: #FF6B00;
}
/* ---- Links do Footer ---- */
.footer-links {
    padding: 60px 0;
    border-top: 1px solid #f3f4f6;
    background: #ffffff;
}
.footer-links-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr; 
    gap: 40px;
    align-items: flex-start;
}
.footer-logo img {
    width: 140px;
    margin-bottom: 20px;
}
.footer-description {
    color: #6b7280;
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 25px;
    max-width: 280px;
}
.social-icons {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
}
.social-icons a {
    display: inline-block;
    transition: transform 0.2s ease;
}
.social-icons a img {
    width: 36px;  
    height: 36px;
    object-fit: contain;
    display: block;
}
.social-icons a:hover {
    transform: translateY(-3px);
}
.footer-column h3 {
    font-size: 18px;
    color: #111827;
    margin-bottom: 24px;
    font-weight: 600;
}
.footer-column ul {
    list-style: none;
}
.footer-column ul li {
    margin-bottom: 14px;
}
.footer-column ul li a {
    color: #6b7280;
    font-size: 15px;
    transition: color 0.3s;
}
.footer-column ul li a:hover {
    color: #FF6B00;
}
/* ---- Newsletter ---- */
.footer-newsletter p {
    color: #6b7280;
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 20px;
}
.newsletter-form {
    display: flex;
    align-items: center;    
    background: #f9fafb;
    border-radius: 50px;
    padding: 6px;
    border: 1px solid #e5e7eb;
    width: 100%;
    max-width: 380px;       
}
.newsletter-form input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 10px 16px;
    outline: none;
    font-size: 15px;
    color: #111827;
}
.newsletter-form button {
    background: #FF6B00;
    color: #fff;
    border: none;
    padding: 12px 26px;     
    border-radius: 50px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
    white-space: nowrap;    
}
.newsletter-form button:hover {
    background: #e55d00;
    transform: scale(1.02); 
}
.footer-bottom {
    padding: 25px 0;
    border-top: 1px solid #f3f4f6;
    background: #ffffff;
}
.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #9ca3af;
    font-size: 14px;
}
.legal-links a {
    margin-left: 24px;
    transition: color 0.3s;
}
.legal-links a:hover {
    color: #FF6B00;
}
.seu-botao-inscrever {
    white-space: nowrap; 
    padding: 0 24px; 
}
    </style>
</head>
<body>

<header class="header">
    <div class="logo">
        <a href="#"><img src="img/logo.png" alt="Logo CredTech"></a>
    </div>
    <nav class="menu">
        <a href="simulador.php" class="menu-ativo">Simulador</a>
        <a href="clientes.php">Clientes</a>
        <a href="sobre_nos.html">Sobre nós</a>
        <a href="central_ajuda.html">Central de Ajuda</a>
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
        <div class="step active">
            <div class="step-circle">2</div>
            <span class="step-label">Simulação</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="step-circle">3</div>
            <span class="step-label">Proposta</span>
        </div>
    </div>

    <!-- CLIENT CHIP -->
    <div class="client-chip">
        <div class="chip-avatar" id="chipAvatar">?</div>
        <div>
            <small>Simulando para</small><br>
            <strong id="chipNome">—</strong>
        </div>
    </div>

    <div class="sim-layout">

        <div class="sim-card">
            <h2>Simulação</h2>
            <p>Ajuste valor e prazo</p>

            <div class="val-display">R$ <span id="valorDisplay">15.000,00</span></div>

            <input type="range" id="slider" min="1000" max="50000" step="500" value="15000">

            <div class="prazo-options">
                <div class="prazo-btn" onclick="setPrazo(6)">6x</div>
                <div class="prazo-btn" onclick="setPrazo(12)">12x</div>
                <div class="prazo-btn selected" onclick="setPrazo(24)">24x</div>
                <div class="prazo-btn" onclick="setPrazo(36)">36x</div>
            </div>

            <div class="info-row"><span>Parcelas</span><strong id="parcelas">24x</strong></div>
            <div class="info-row"><span>Parcela</span><strong id="parcela">—</strong></div>
            <div class="info-row"><span>Total</span><strong id="total">—</strong></div>

            <div style="display:flex; gap:12px; margin-top:20px;">
                <button class="btn" style="background:#aaa; flex:1" onclick="window.location.href='simulador.php'">
                    <i class="fa-solid fa-arrow-left"></i> Voltar
                </button>
                <button class="btn" style="flex:2" onclick="irProposta()">
                    Gerar proposta <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <div class="sim-card">
            <h3>Resumo</h3>
            <div class="info-row"><span>Cliente</span><strong id="resumoNome">—</strong></div>
            <div class="info-row"><span>Valor</span><strong id="resumoValor">—</strong></div>
            <div class="info-row"><span>Prazo</span><strong id="resumoPrazo">—</strong></div>
            <div class="info-row"><span>Parcela</span><strong id="resumoParcela">—</strong></div>
            <div class="info-row"><span>Total</span><strong id="resumoTotal">—</strong></div>
        </div>



    </div>
</div>

<script>
// Recupera cliente do sessionStorage
const clienteSalvo = sessionStorage.getItem("clienteSelecionado");
if (!clienteSalvo) { window.location.href = "simulador.php"; }

const cliente = JSON.parse(clienteSalvo);
document.getElementById("chipNome").innerText   = cliente.nome;
document.getElementById("chipAvatar").innerText = cliente.nome.split(" ").map(n => n[0]).slice(0,2).join("");
document.getElementById("resumoNome").innerText = cliente.nome;

let valor = 15000;
let prazo = 24;
const TAXA = 0.0119;

function format(v) {
    return v.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
}

function calc(pv, n) {
    return pv * (TAXA * Math.pow(1 + TAXA, n)) / (Math.pow(1 + TAXA, n) - 1);
}

function update() {
    const pmt = calc(valor, prazo);
    document.getElementById('valorDisplay').innerText  = format(valor);
    document.getElementById('parcelas').innerText      = prazo + "x";
    document.getElementById('parcela').innerText       = "R$ " + format(pmt);
    document.getElementById('total').innerText         = "R$ " + format(pmt * prazo);
    document.getElementById('resumoValor').innerText   = "R$ " + format(valor);
    document.getElementById('resumoPrazo').innerText   = prazo + "x";
    document.getElementById('resumoParcela').innerText = "R$ " + format(pmt);
    document.getElementById('resumoTotal').innerText   = "R$ " + format(pmt * prazo);

    document.querySelectorAll('.prazo-btn').forEach(btn => {
        btn.classList.toggle('selected', btn.innerText === prazo + 'x');
    });
}

document.getElementById('slider').addEventListener('input', e => {
    valor = +e.target.value;
    update();
});

function setPrazo(p) {
    prazo = p;
    update();
}

function irProposta() {
    const pmt = calc(valor, prazo);
    sessionStorage.setItem("simulacao", JSON.stringify({
        valor:         valor,
        parcelas:      prazo,
        valor_parcela: pmt,
        valor_total:   pmt * prazo,
        taxa_juros:    TAXA
    }));
    window.location.href = "proposta.php";
}

update();
</script>

</body>
</html>