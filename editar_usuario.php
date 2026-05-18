<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once "conexao.php";

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: clientes.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM dados_login WHERE id_login = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$u = $stmt->get_result()->fetch_assoc();

if (!$u) {
    header("Location: clientes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário | CredTech</title>
    <link rel="icon" type="image/x-icon" href="img/icon.jpeg">
    <link rel="stylesheet" href="css/dados.css">
    <style>
        .mensagem { margin: 10px 0; font-weight: bold; display: none; }
        .aviso-senha { font-size: 0.8rem; color: #888; margin-top: 4px; }
    </style>
</head>
<body>

    <div class="box_img">
        <img src="img/dados.jpeg" alt="CredTech">
    </div>

    <div class="content">
        <h1>EDITAR USUÁRIO</h1>

        <form id="form_editar_usuario">
            <input type="hidden" name="id_login" value="<?= $u['id_login'] ?>">

            <div class="form_row">
                <div class="form_group">
                    <label for="usuario">Usuário</label>
                    <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($u['usuario']) ?>" required>
                    <small id="erro_usuario"></small>
                </div>
                <div class="form_group">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        <option value="usuario" <?= $u['tipo'] === 'usuario' ? 'selected' : '' ?>>Usuário</option>
                        <option value="admin"   <?= $u['tipo'] === 'admin'   ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <small></small>
                </div>
            </div>

            <div class="form_row">
                <div class="form_group">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha" placeholder="Deixe em branco para não alterar">
                    <small class="aviso-senha">Só preencha se quiser trocar a senha.</small>
                </div>
                <div class="form_group">
                    <label for="confirmar_senha">Confirmar Nova Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a nova senha">
                    <small id="erro_senha"></small>
                </div>
            </div>

            <div class="form_row">
                <div class="form_group">
                    <label>Status</label>
                </div>
                <div class="form_group">
                    <label><input type="radio" name="ativo" value="1" <?= $u['ativo'] ? 'checked' : '' ?>> Ativo</label>
                </div>
                <div class="form_group">
                    <label><input type="radio" name="ativo" value="0" <?= !$u['ativo'] ? 'checked' : '' ?>> Inativo</label>
                </div>
            </div>

            <br><br>

            <div id="mensagem_feedback" class="mensagem"></div>

            <button type="submit" id="botao_salvar">Salvar Alterações</button>
            &nbsp;
            <a href="clientes.php" style="color:#888; font-size:0.9rem;">← Voltar</a>

        </form><br><br>
    </div>

    <script>
    document.getElementById("form_editar_usuario").addEventListener("submit", function(e) {
        e.preventDefault();

        const nova     = document.getElementById("nova_senha").value;
        const confirma = document.getElementById("confirmar_senha").value;
        const errSenha = document.getElementById("erro_senha");
        const feedback = document.getElementById("mensagem_feedback");
        const botao    = document.getElementById("botao_salvar");

        if (nova && nova !== confirma) {
            errSenha.textContent = "As senhas não coincidem.";
            errSenha.style.color = "red";
            return;
        }
        errSenha.textContent = "";

        const formData = new FormData(this);
        botao.disabled    = true;
        botao.textContent = "Salvando...";

        fetch("salvar_edicao_usuario.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            feedback.style.display = "block";
            if (data.sucesso) {
                feedback.textContent = data.mensagem;
                feedback.style.color = "green";
                setTimeout(() => window.location.href = "clientes.php", 1500);
            } else {
                feedback.textContent = data.mensagem;
                feedback.style.color = "red";
                botao.disabled       = false;
                botao.textContent    = "Salvar Alterações";
            }
        })
        .catch(() => {
            feedback.style.display = "block";
            feedback.textContent   = "Erro de conexão com o servidor.";
            feedback.style.color   = "red";
            botao.disabled         = false;
            botao.textContent      = "Salvar Alterações";
        });
    });
    </script>

</body>
</html>