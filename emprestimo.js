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

    if (valor < 0){
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
    if(numParcelas > 0 && numParcelas <= 12){
        juros = credito * taxaJuros1_12 * numParcelas;
    } else if (numParcelas >= 13 && numParcelas <=24){
        juros = credito * taxaJuros13_24 * numParcelas;
    } else if (numParcelas > 24){
        juros = credito * taxaJuros24_ * numParcelas;
    }

    let valorParcela = (credito + juros)/ numParcelas;

    document.getElementById("tela_emprestimo").style.display = "none";
    if (valorParcela > rendaMensal30){
        document.getElementById("tela_reprovado").style.display = "block";
    } else {
        document.getElementById("tela_aprovado").style.display = "block";
    }
}

