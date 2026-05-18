<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require_once "conexao.php";

$id         = intval($_GET['id'] ?? 0);
$id_usuario = $_SESSION['usuario_id'];
$tipo       = $_SESSION['tipo'] ?? 'usuario';

if ($id <= 0) {
    header("Location: clientes.php");
    exit;
}

// Admin acessa qualquer cliente; usuário só os seus
if ($tipo === 'admin') {
    $stmt = $conn->prepare("SELECT * FROM info_clientes WHERE id_cliente = ? AND ativo = 1");
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conn->prepare("SELECT * FROM info_clientes WHERE id_cliente = ? AND id_usuario_criador = ? AND ativo = 1");
    $stmt->bind_param("ii", $id, $id_usuario);
}

$stmt->execute();
$result = $stmt->get_result();
$c = $result->fetch_assoc();

if (!$c) {
    header("Location: clientes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente | CredTech</title>
    <link rel="icon" type="image/x-icon" href="img/icon.jpeg">
    <link rel="stylesheet" href="css/dados.css">
    <style>
        .mensagem { margin: 10px 0; font-weight: bold; display: none; }
    </style>
</head>
<body>

    <div class="box_img">
        <img src="img/dados.jpeg" alt="CredTech">
    </div>

    <div class="content">
        <h1>EDITAR CLIENTE</h1>

        <form id="form_editar">
            <input type="hidden" name="id_cliente" value="<?= $c['id_cliente'] ?>">

            <div class="form_row">
                <div class="form_group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($c['nome']) ?>" required>
                    <small id="erro_nome"></small>
                </div>
                <div class="form_group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($c['cpf']) ?>" maxlength="14" required>
                    <small id="erro_cpf"></small>
                </div>
            </div>

            <div class="form_row">
                <div class="form_group">
                    <label for="telefone">Celular</label>
                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($c['telefone']) ?>" maxlength="15" required>
                    <small id="erro_telefone"></small>
                </div>
                <div class="form_group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($c['email']) ?>" required>
                    <small id="erro_email"></small>
                </div>
            </div>

            <div class="form_row">
                <div class="form_group">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($c['cep']) ?>" maxlength="9" required>
                    <small id="erro_cep"></small>
                </div>
                <div class="form_group">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($c['endereco']) ?>" required>
                    <small id="erro_endereco"></small>
                </div>
                <div class="form_group">
                    <label for="numero">Número</label>
                    <input type="number" id="numero" name="numero" value="<?= htmlspecialchars($c['numero']) ?>" min="0" required>
                    <small id="erro_numero"></small>
                </div>
            </div>

            <div class="form_row">
                <div class="form_group">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($c['cidade']) ?>" required>
                    <small id="erro_cidade"></small>
                </div>
                <div class="form_group">
                    <label for="bairro">Bairro</label>
                    <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($c['bairro']) ?>" required>
                    <small id="erro_bairro"></small>
                </div>
                <div class="form_group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" required>
                        <option value="">Selecione</option>
                        <?php
                        $estados = ["AC","AL","AP","AM","BA","CE","DF","ES","GO","MA","MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN","RS","RO","RR","SC","SP","SE","TO"];
                        $nomes   = ["Acre","Alagoas","Amapá","Amazonas","Bahia","Ceará","Distrito Federal","Espírito Santo","Goiás","Maranhão","Mato Grosso","Mato Grosso do Sul","Minas Gerais","Pará","Paraíba","Paraná","Pernambuco","Piauí","Rio de Janeiro","Rio Grande do Norte","Rio Grande do Sul","Rondônia","Roraima","Santa Catarina","São Paulo","Sergipe","Tocantins"];
                        foreach ($estados as $i => $uf):
                            $sel = $c['estado'] === $uf ? 'selected' : '';
                        ?>
                            <option value="<?= $uf ?>" <?= $sel ?>><?= $nomes[$i] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small id="erro_estado"></small>
                </div>
            </div>

            <div class="form_row">
                <div class="form_group"><label>Tipo</label></div>
                <div class="form_group">
                    <label>
                        <input type="radio" name="tipo" value="fisica" <?= $c['tipo_pessoa'] === 'fisica' ? 'checked' : '' ?>> Física
                    </label>
                </div>
                <div class="form_group">
                    <label>
                        <input type="radio" name="tipo" value="juridica" <?= $c['tipo_pessoa'] === 'juridica' ? 'checked' : '' ?>> Jurídica
                    </label>
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
    document.getElementById("form_editar").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const botao    = document.getElementById("botao_salvar");
        const feedback = document.getElementById("mensagem_feedback");

        botao.disabled    = true;
        botao.textContent = "Salvando...";

        fetch("salvar_edicao_cliente.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            feedback.style.display = "block";
            if (data.sucesso) {
                feedback.textContent  = data.mensagem;
                feedback.style.color  = "green";
                setTimeout(() => window.location.href = "clientes.php", 1500);
            } else {
                feedback.textContent  = data.mensagem;
                feedback.style.color  = "red";
                botao.disabled        = false;
                botao.textContent     = "Salvar Alterações";
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