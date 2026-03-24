// não permitir que a pessoa entre na página sem login
document.addEventListener("DOMContentLoaded", function() {
    if(sessionStorage.getItem("logado") !== "true") {
        window.location.href = "index.html";
    }
});

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


// ============================================================================================================================
// ====================================== validação das entradas de dados ======================================================
// ============================================================================================================================


// --------------- texto -------------------------------------------------------------------------------------------------------
function validarTexto(texto){ 
    let validar = /^[A-Za-zÀ-ÿ\s]+$/.test(texto);
    if (texto.trim() === ""){
        return [false, "Esse campo tem preencimento obrigatório"];
    }
    if (!validar){
        return [false, "Digite apenas letras"]
    } 
        return [true, "Válido"];
}

// --------------- número ------------------------------------------------------------------------------------------------------
function validarNumero(numero){
    let validar = /^\d+$/.test(numero);
    if (numero.trim() === ""){
        return [false, "Esse campo tem preencimento obrigatório"];
    }
     if (!validar){
        return [false, "Digite apenas números"];
    } 
    if(numero.length > 6){
        return [false, "Número inválido"];
    }
        
    return [true, "Válido"];
}

// --------------- email -------------------------------------------------------------------------------------------------------
function validarEmail(email){
    if (email.trim() === ""){
        return [false, "Esse campo tem preencimento obrigatório"];
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
        return [false, "Email inválido"];
    }

    return [true, "Válido"];
}

// --------------- cep -------------------------------------------------------------------------------------------------------
function validarCEP(numero){
    let cep = numero.match(/\d/g);
    if (numero.trim() === ""){
        return [false, "Esse campo tem preencimento obrigatório"];
    }

    if (!/^[\d-]+$/.test(numero)){
        return [false, "Digite apenas números"];
    }

    if (!cep || cep.length !== 8){
        return [false, "CEP inválido"];
    } else {
        return [true, "Válido"];
    }
}

async function buscarCEP(cep){
    const numero = cep.replace(/\D/g, "");
    const erro = document.getElementById("erro_cep");
    const input = document.getElementById("cep");

    try {
        const cep_buscado = await fetch(`https://viacep.com.br/ws/${numero}/json/`);
        const dados = await cep_buscado.json();

        if(dados.erro){
            erro.textContent = "CEP não encontrado";
            erro.style.color = "red";
            input.style.border = "2px solid red";
            return;
        }

        document.getElementById("endereco").value = dados.logradouro;
        document.getElementById("bairro").value = dados.bairro;
        document.getElementById("cidade").value = dados.localidade;
        document.getElementById("estado").value = dados.uf;

        if (!dados.logradouro || dados.logradouro === ""){
            document.getElementById("endereco").style.border = "2px solid red";
            const erro = document.getElementById("erro_endereco");
            erro.textContent = "Esse campo tem preenchimento obrigatório";
            erro.style.color = "red";
        } 
        if (!dados.bairro || dados.bairro === ""){
            document.getElementById("bairro").style.border = "2px solid red";
            const erro = document.getElementById("erro_bairro");
            erro.textContent = "Esse campo tem preenchimento obrigatório";
            erro.style.color = "red";
        } 
        if (!dados.localidade || dados.localidade === ""){
            document.getElementById("cidade").style.border = "2px solid red";
            const erro = document.getElementById("erro_cidade");
            erro.textContent = "Esse campo tem preenchimento obrigatório";
            erro.style.color = "red";
        } 
        if (!dados.uf || dados.uf === ""){
            document.getElementById("estado").style.border = "2px solid red";
            const erro = document.getElementById("erro_estado");
            erro.textContent = "Esse campo tem preenchimento obrigatório";
            erro.style.color = "red";
        } 
            erro.textContent = "✔";
        erro.style.color = "green";
        input.style.border = "2px solid green";
        return;
        
        

        
        
    } catch (e) {
        erro.textContent = "CEP não encontrado";
        erro.style.color = "red";
        input.style.border = "2px solid red";
        return;
    }


}
function mascaraCEP(input) {
    let numeros = input.value.replace(/\D/g, "");
    if (numeros.length > 8){
        numeros = numeros.slice(0, 8);
    }

    let formatado = "";

    if (numeros.length >0) formatado += numeros.substring(0, Math.min(5, numeros.length));
    if (numeros.length >5) formatado += "-" + numeros.substring(5, 8);
    

    let posicaoCursor = input.selectionStart;
    posicaoCursor = Math.max(posicaoCursor, formatado.length);
    
    input.value = formatado;
    input.setSelectionRange(posicaoCursor, posicaoCursor);
}

// --------------- cpf -------------------------------------------------------------------------------------------------------
function validarCPF(cpf_input){
    if (cpf_input.trim() === ""){
        return [false, "Esse campo tem preencimento obrigatório"];
    }

    let cpf = cpf_input.match(/\d/g);
    let vetor_cpf = cpf.map(Number);
    
    if (!cpf || cpf.length !== 11 || vetor_cpf.every(n => n === vetor_cpf[0])){
        return [false, "CPF inválido"];
    } 

    // cálculo do primeiro dígito verificador
    let soma1 = 0;
    for(let i=0;i<9;i++){
        let n_decescente = 10
        let multiplicação = vetor_cpf[i] * (n_decescente - i);
        soma1 += multiplicação;
    }

    let digito1 = (soma1 * 10) % 11;
    if (digito1 === 10 || digito1 === 11){
        digito1 = 0;
    } 

    // cálculo do segundo dígito verificador
    let soma2 = 0;
    for(let i=0;i<10;i++){
        let n_decescente2 = 11;
        let multi;

        if (i === 9){
            multi = digito1 * (n_decescente2 - i);
        } else {
            multi = vetor_cpf[i] * (n_decescente2 - i);
        }
        
        soma2 += multi;
    }

    let digito2 = (soma2 * 10) % 11;
    if (digito2 === 10 || digito2 === 11){
        digito2 = 0;
    } 

    if (digito1 !== vetor_cpf[9] || digito2 !== vetor_cpf[10]){
        return [false, "CPF inválido"];
    }

    return [true, "CPF válido"];
}

function mascaraCPF(input) {
    let numeros = input.value.replace(/\D/g, "");
    if (numeros.length > 11){
        numeros = numeros.slice(0, 11);
    }

    let formatado = "";

    if (numeros.length >0) formatado += numeros.substring(0, Math.min(3, numeros.length));
    if (numeros.length >3) formatado += "." + numeros.substring(3, Math.min(6, numeros.length));
    if (numeros.length >6) formatado += "." + numeros.substring(6, Math.min(9, numeros.length));
    if (numeros.length >9) formatado += "-" + numeros.substring(9, 11);
    

    let posicaoCursor = input.selectionStart;
    posicaoCursor = Math.max(posicaoCursor, formatado.length);
    
    input.value = formatado;
    input.setSelectionRange(posicaoCursor, posicaoCursor);
}

// --------------- telefone -------------------------------------------------------------------------------------------------------
function validarTelefone(telefone){
    if (telefone.trim() === ""){
        return [false, "Esse campo tem preencimento obrigatório"];
    }

    if (!(/^[\d\(\)\-\s]+$/.test(telefone))){
        return [false, "Digite apenas números"];
    }

    let numero = telefone.match(/\d/g);
    
    if (!numero || numero.length !== 11){
        return [false, "Número de telefone inválido"];
    } else {
        return [true, "Válido"];
    }
}

function mascaraTelefone(input) {
    let numeros = input.value.replace(/\D/g, "");
    if (numeros.length > 11){
        numeros = numeros.slice(0, 11);
    }

    let formatado = "";

    if (numeros.length > 0) formatado += "(" + numeros.substring(0, 2);
    if (numeros.length >= 3) formatado += ") " + numeros.substring(2, Math.min(7, numeros.length));
    if (numeros.length >= 8) formatado += "-" + numeros.substring(7, numeros.length);

    let posicaoCursor = input.selectionStart;
    posicaoCursor = Math.max(posicaoCursor, formatado.length);
    
    input.value = formatado;
    input.setSelectionRange(posicaoCursor, posicaoCursor);
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

// ----------------------------------- estado ------------------------------------------------------------------------------
function validarEstado(valor){
    if(valor === ""){
        return [false, "Selecione um estado"];
    }
    return [true, "Válido"];
}

// ================= BOTÃO DESATIVADO ATÉ VALIDAR =================
function verificarFormulario(){
    const campos = [
        ["nome", validarTexto],
        ["cpf", validarCPF],
        ["telefone", validarTelefone],
        ["email", validarEmail],
        ["cep", validarCEP],
        ["endereco", validarTexto],
        ["numero", validarNumero],
        ["cidade", validarTexto],
        ["bairro", validarTexto]
    ];

    let tudoValido = true;

    campos.forEach(([id, func]) => {
        const valor = document.getElementById(id).value;
        const resultado = func(valor);

        if(!resultado[0]){
            tudoValido = false;
        }
    });

    document.getElementById("botao_cliente").disabled = !tudoValido;
}


// ============================================================================================================================
// ====================== validação automática ==================================================================================
// ============================================================================================================================

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


document.getElementById("form_cliente").addEventListener("submit", function(e){
    e.preventDefault();

    let valido = true;

    const campos = [
        ["nome", "erro_nome", validarTexto],
        ["cpf", "erro_cpf", validarCPF],
        ["telefone", "erro_telefone", validarTelefone],
        ["email", "erro_email", validarEmail],
        ["cep", "erro_cep", validarCEP],
        ["endereco", "erro_endereco", validarTexto],
        ["numero", "erro_numero", validarNumero],
        ["cidade", "erro_cidade", validarTexto],
        ["bairro", "erro_bairro", validarTexto]
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
        window.location.href = "emprestimo.html";
    }
});

validacaoAutomatica("cidade", "erro_cidade", validarTexto);
validacaoAutomatica("nome", "erro_nome", validarTexto);
validacaoAutomatica("bairro", "erro_bairro", validarTexto);
validacaoAutomatica("endereco", "erro_endereco", validarTexto);
validacaoAutomatica("cpf", "erro_cpf", validarCPF);
validacaoAutomatica("telefone", "erro_telefone", validarTelefone);
validacaoAutomatica("numero", "erro_numero", validarNumero);
validacaoAutomatica("email", "erro_email", validarEmail);
validacaoAutomatica("cep", "erro_cep", validarCEP);
validacaoAutomatica("renda", "erro_renda", validarDinheiro);
validacaoAutomatica("valor_pretendido", "erro_valor_pretendido", validarDinheiro);
validacaoAutomatica("estado", "erro_estado", validarEstado);