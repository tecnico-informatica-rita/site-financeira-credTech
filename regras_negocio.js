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

