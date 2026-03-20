// validação das entradas de dados


function validarNomeCompleto(nome){
    let validar = /^[A-Za-zÀ-ÿ\s]+$/.test(nome);
    if (nome.trim() === ""){
        return false
    }
    if (!validar){
        return false
    } else {
        return true
    }
}

function validarCEP(numero){
    let cep = numero.match(/\+d/g);
    if (cep.legth !== 8){
        return false;
    } else {
        return true
    }
}

function validarTelefone(telefone){
    let numero = telefone.match(/\+d/g);
    if (numero.legth !== 11){
        return false;
    } else {
        return true;
    }
}


// leitura de variáveis
let nome = document.getElementById("nome")