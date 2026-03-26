// ============================================================================================================================
// ====================== validação do login ==================================================================================
// ============================================================================================================================


// --------------- usuario ---------------------------------------------------------------------------------------------------
function validarUsuario(usuario){
    if (usuario.trim() === ""){
        return [false, "Esse campo tem preencimento obrigatório"];
    }

    if(!/^[a-zA-Z0-9._-]{3,20}$/.test(usuario)){
        return [false, "Usuário inválido. Use 3-20 letras, números, '_' ou '.'"];
    }
    return [true, "Válido"];
}

// --------------- senha ---------------------------------------------------------------------------------------------------
function validarSenha(senha){
    if (senha.trim() === ""){
        return [false, "Esse campo tem preencimento obrigatório"];
    }

    if(senha.length < 8 || senha.length > 20){
        return [false, "Usuário inválido. A senha deve ter no mímimo 8 caracteres e no máximo 20 caracteres"];
    }

    if(!/[A-Z]/.test(senha) || !/[a-z]/.test(senha) || !/[0-9]/.test(senha)){
        return [false, "Usuário inválido. A senha deve ter pelo menos uma letra maiúscula, uma letra minúscula e um número"];
    }
    if(!/[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/.test(senha)){
        return [false, "Usuário inválido. A senha deve ter pelo menos um símbolo"];
    }

    return [true, "Válido"];
}


// ============================================================================================================================
// ====================== validação automática ==================================================================================
// ============================================================================================================================


function validacaoAutomaticaLogin(id_usuario, erro_usuario_input, id_senha, erro_senha_input, validar_usuario, validar_senha){
    const form = document.getElementById("formLogin");
    const id_input_u = document.getElementById(id_usuario);
    const id_input_s = document.getElementById(id_senha);
    const erro_usuario = document.getElementById(erro_usuario_input);
    const erro_senha = document.getElementById(erro_senha_input);
    let valido = [false, false]

    id_input_u.addEventListener("input", function(){
        erro_usuario.textContent = "";
    });
    id_input_s.addEventListener("input", function(){
        erro_senha.textContent = "";
    });

    id_input_u.addEventListener("blur", function() {
        const resultado = validar_usuario(this.value);

        if (resultado[0] === false){
            erro_usuario.textContent = resultado[1];
            erro_usuario.style.color = "red";
            valido[0] = false;
        } else {
            erro_usuario.textContent = "✔";
            erro_usuario.style.color = "green";
            valido[0] = true;
        }
    });

    id_input_s.addEventListener("blur", function() {
        const resultado = validar_senha(this.value);

        if (resultado[0] === false){
            erro_senha.textContent = resultado[1];
            erro_senha.style.color = "red";
             valido[1] = false;
        } else {
            erro_senha.textContent = "✔";
            erro_senha.style.color = "green";
            valido[1] = true;
        }
    });

    form.addEventListener("submit", function(event){
        event.preventDefault(); 
        // Garante que validações foram disparadas
        const resultadoU = validar_usuario(id_input_u.value);
        const resultadoS = validar_senha(id_input_s.value);

        valido[0] = resultadoU[0];
        valido[1] = resultadoS[0];

        if (resultadoU[0] === false){
            erro_usuario.textContent = resultadoU[1];
            erro_usuario.style.color = "red";
        } else {
            erro_usuario.textContent = "✔";
            erro_usuario.style.color = "green";
        }
        
        if (resultadoS[0] === false){
            erro_senha.textContent = resultadoS[1];
            erro_senha.style.color = "red";
        } else {
            erro_senha.textContent = "✔";
            erro_senha.style.color = "green";
        }

        if(valido[0] && valido[1]){
            sessionStorage.setItem("logado", "true");
            window.location.href = "dados_cliente.html";
        }
    });
}

// limpar os dados da página de login
window.addEventListener("pageshow", function(event) {
    const form = document.getElementById("formLogin");
    form.reset();
    document.getElementById("erro_usuario").textContent = "";
    document.getElementById("erro_senha").textContent = "";
});

// remover o login assim que ele for efetuado
window.addEventListener("DOMContentLoaded", function() {
    sessionStorage.removeItem("logado");
});

validacaoAutomaticaLogin("usuario", "erro_usuario", "senha", "erro_senha", validarUsuario, validarSenha);

