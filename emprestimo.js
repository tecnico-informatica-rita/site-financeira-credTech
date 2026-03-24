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

