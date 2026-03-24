// ============================================================================================================================
// =================== função para preencher número de parcelas ===============================================================
// ============================================================================================================================
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

    if (id_input.id === "telefone"){
        id_input.addEventListener("input", function(){
            
            mascaraTelefone(this);
             
            erro.textContent = "";
        });
    } else if(id_input.id === "cpf"){
        id_input.addEventListener("input", function(){
            
            mascaraCPF(this);
             
            erro.textContent = "";
        });
    } else if(id_input.id === "cep"){
        id_input.addEventListener("input", function(){
            
            mascaraCEP(this);

            document.getElementById("endereco").value = "";
            document.getElementById("bairro").value = "";
            document.getElementById("cidade").value = "";
            document.getElementById("estado").value = "";
            erro.textContent = "";
        });
    } else {
        id_input.addEventListener("input", function(){
            erro.textContent = "";
        });
    }
    

    id_input.addEventListener("blur", function() {
        const resultado = nome_funcao(this.value);

        // ================= CEP (CASO ESPECIAL) =================
        if(id_input.id === "cep"){
            const resultado = validarCEP(this.value);

            if(!resultado[0]){
                erro.textContent = resultado[1];
                erro.style.color = "red";
                this.style.border = "2px solid red";
            } else {
                buscarCEP(this.value);
            }

            verificarFormulario();
            return;
        }

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

    if (valorParcela > rendaMensal30){
        return [false, valorParcela, rendaMensal30];
    } else {
        return [true, valorParcela, rendaMensal30];
    }
}

