// =================== função para preencher número de parcelas ===============================================================
const select_input = document.getElementById("parcelas");

if(select_input){
    for(let i=12;i<=48;i++){
        const option = document.createElement("option");
        option.value = i;
        option.textContent = i + "x";
        select_input.appendChild(option);
    }
}


// --------------- dinheiro ---------------------------------------------------------------------------------------------------
function validarDinheiro(dinheiro){
    if (dinheiro.trim() === ""){
        return [false, "Esse campo tem preencimento obrigatório"];
    }
    let valor = Number(dinheiro.replace(",", "."));

    if (isNaN(valor)){
        return [false, "Digite apenas números"];
    }

    if (valor <= 0){
        return [false, "Número inválido"];
    }

    return [true, "Válido"];
}

// ------------------- validar formulário ------------------------------------------------------------------------------------
// ================= BOTÃO DESATIVADO ATÉ VALIDAR =================
function verificarFormulario(){
    const campos = [
        ["renda", validarDinheiro],
        ["valor_pretendido", validarDinheiro],
    ];

    let tudoValido = true;

    campos.forEach(([id, func]) => {
        const valor = document.getElementById(id).value;
        const resultado = func(valor);

        if(!resultado[0]){
            tudoValido = false;
        }
    });

    document.getElementById("botao_simular").disabled = !tudoValido;
}



// ------------------ validação automática dos campos ---------------------------------------------------------------------------
function validacaoAutomatica(id, erroId, nome_funcao){
    const id_input = document.getElementById(id);
    const erro = document.getElementById(erroId);

    if(id_input.tagName === "SELECT"){
        id_input.addEventListener("change", function(){
            const resultado = nome_funcao(this.value);

            if(!resultado[0]){
                erro.textContent = resultado[1];
                erro.style.color = "red";
                this.style.border = "2px solid red";
            } else {
                erro.textContent = "✔";
                erro.style.color = "green";
                this.style.border = "2px solid green";
            }

        verificarFormulario();
        });

        return;
    }


    id_input.addEventListener("blur", function() {
        const resultado = nome_funcao(this.value);

        if (resultado[0] === false){
            erro.textContent = resultado[1];
            erro.style.color = "red";
            this.style.border = "2px solid red";
        } else {
            if(id_input.id === "cep"){
                buscarCEP(this.value);
            } else {
                erro.textContent = "✔";
                erro.style.color = "green";
                this.style.border = "2px solid green";
            }
        }
        verificarFormulario();
    });
}

document.getElementById("form_emprestimo").addEventListener("submit", function(e){
    e.preventDefault();

    let valido = true;

    const campos = [
        ["renda", "erro_renda", validarDinheiro],
        ["valor_pretendido","erro_valor_pretendido", validarDinheiro],
    ];

    campos.forEach(([id, erroId, func]) => {
        const input = document.getElementById(id);
        const erro = document.getElementById(erroId);

        const resultado = func(input.value);

        if(resultado[0] === false){
            erro.textContent = resultado[1];
            erro.style.color = "red";
            input.style.border = "2px solid red";
            valido = false;
        } else {
            erro.textContent = "✔";
            erro.style.color = "green";
            input.style.border = "2px solid green";
        }
    });

    if(valido){
        verificarEmprestimo("valor_pretendido", "renda", "parcelas");
    }
});

validacaoAutomatica("renda", "erro_renda", validarDinheiro);
validacaoAutomatica("valor_pretendido", "erro_valor_pretendido", validarDinheiro);




// ============================================================================================================================
// ====================== regras de negócios ==================================================================================
// ============================================================================================================================
function simularNovamente(){
    document.getElementById("tela_aprovado").style.display = "none";
    document.getElementById("tela_reprovado").style.display = "none";

    document.getElementById("tela_emprestimo").style.display = "block";

    document.getElementById("form_emprestimo").reset();

    const campos = ["renda", "valor_pretendido", "parcelas"];

    campos.forEach(id => {
        const input = document.getElementById(id);
        input.style.border = "";
    });

    document.getElementById("erro_renda").textContent = "";
    document.getElementById("erro_valor_pretendido").textContent = "";
}

function verificarEmprestimo(valor_pretendido, renda, parcelas){
    // taxas de juros ao mês
    const taxaJuros1_12 = 0.025;
    const taxaJuros13_24 = 0.035;
    const taxaJuros24_ = 0.05;

    // cálculo das parcelas
    let renda_bruta = Number(document.getElementById(renda).value.replace(",", "."));
    let credito = Number(document.getElementById(valor_pretendido).value.replace(",", "."));
    let selectParcelas = document.getElementById(parcelas);
    let numParcelas = Number(selectParcelas.value.replace(",", "."));

    let rendaMensal30 = (renda_bruta * 30) / 100;

    let juros = 0;
    let taxa = 0;
    if(numParcelas > 0 && numParcelas <= 12){
        juros = credito * taxaJuros1_12 * numParcelas;
        taxa = 2.5;
    } else if (numParcelas >= 13 && numParcelas <=24){
        juros = credito * taxaJuros13_24 * numParcelas;
        taxa = 3.5;
    } else if (numParcelas > 24){
        juros = credito * taxaJuros24_ * numParcelas;
        taxa = 5;
    }

    if(!numParcelas || numParcelas <= 0){
        alert("Selecione o número de parcelas");
        return;
    }

    let valorParcela = (credito + juros)/ numParcelas;

    document.getElementById("tela_emprestimo").style.display = "none";
    if (valorParcela > rendaMensal30){
        document.getElementById("tela_reprovado").style.display = "block";

        const nome = sessionStorage.getItem("nome") || "Cliente";
        document.getElementById("mensagem_reprovado").textContent = `${nome}, infelizmente seu empréstimo não foi aprovado.`;
        document.getElementById("res_renda").textContent = renda_bruta.toFixed(2);
        document.getElementById("res_30").textContent = rendaMensal30.toFixed(2);
        document.getElementById("res_valor_r").textContent = credito.toFixed(2);
        document.getElementById("res_taxa_r").textContent = taxa.toFixed(2);
        document.getElementById("res_juros_r").textContent = juros.toFixed(2);
        document.getElementById("res_parcela_r").textContent = valorParcela.toFixed(2);


        document.getElementById("mensagem_reprovado").innerHTML += `<br>O valor da parcela ultrapassa 30% da sua renda mensal, por isso o empréstimo não pode ser concedido.`;
    } else {
        document.getElementById("tela_aprovado").style.display = "block";

        const nome = sessionStorage.getItem("nome") || "Cliente";
        document.getElementById("mensagem_aprovado").textContent = `Parabéns ${nome}, seu empréstimo foi aprovado!`;
        document.getElementById("res_valor").textContent = credito.toFixed(2);
        document.getElementById("res_juros").textContent = juros.toFixed(2);
        document.getElementById("res_taxa").textContent = taxa.toFixed(2);
        document.getElementById("res_total").textContent = (credito + juros).toFixed(2);
        document.getElementById("res_parcelas").textContent = numParcelas
        document.getElementById("res_parcela").textContent = valorParcela.toFixed(2);
        document.getElementById("res_meses").textContent = numParcelas;
    }
}

async function gerarPDF(status) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Configurações de estilo
    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.text("CredTech - Simulação de Empréstimo", 20, 20);
    
    doc.setFontSize(12);
    doc.setFont("helvetica", "normal");
    doc.text(`Data: ${new Date().toLocaleDateString('pt-BR')}`, 20, 30);
    doc.line(20, 35, 190, 35); // Linha divisória

    if (status === 'aprovado') {
        doc.setFont("helvetica", "bold");
        doc.setTextColor(40, 167, 69); // Verde
        doc.text("STATUS: PRÉ-APROVADO", 20, 45);
        
        doc.setTextColor(0, 0, 0); // Preto
        doc.setFont("helvetica", "normal");
        
        // Pegando os dados da tela
        const dados = [
            `Valor Pretendido: R$ ${document.getElementById("res_valor").innerText}`,
            `Taxa de Juros: ${document.getElementById("res_taxa").innerText}% ao mês`,
            `Valor dos Juros: R$ ${document.getElementById("res_juros").innerText}`,
            `Total com Juros: R$ ${document.getElementById("res_total").innerText}`,
            `Parcelas: ${document.getElementById("res_parcelas").innerText}x`,
            `Valor da Parcela: R$ ${document.getElementById("res_parcela").innerText}`
        ];

        let y = 60;
        dados.forEach(linha => {
            doc.text(linha, 20, y);
            y += 10;
        });

    } else {
        doc.setFont("helvetica", "bold");
        doc.setTextColor(220, 53, 69); // Vermelho
        doc.text("STATUS: REPROVADO", 20, 45);
        
        doc.setTextColor(0, 0, 0);
        doc.setFont("helvetica", "normal");

        const dados = [
            `Renda Mensal: R$ ${document.getElementById("res_renda").innerText}`,
            `Margem (30% da renda): R$ ${document.getElementById("res_30").innerText}`,
            `Valor Pretendido: R$ ${document.getElementById("res_valor_r").innerText}`,
            `Parcela Calculada: R$ ${document.getElementById("res_parcela_r").innerText}`
        ];

        let y = 60;
        dados.forEach(linha => {
            doc.text(linha, 20, y);
            y += 10;
        });
    }

    doc.setFontSize(10);
    doc.text("Este documento é apenas uma simulação e não garante a aprovação do crédito.", 20, 150);
    
    // Nome do arquivo baseado no nome do usuário salvo no login
    const nomeUsuario = localStorage.getItem("usuarioLogado") || "cliente";
    doc.save(`Simulacao_CredTech_${nomeUsuario}.pdf`);
}