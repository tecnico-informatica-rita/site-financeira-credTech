<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require_once "conexao.php";

$tipo       = $_SESSION['tipo'] ?? 'usuario';
$id_usuario = $_SESSION['usuario_id'];
$usuario    = $_SESSION['usuario'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | CredTech</title>
    <link rel="icon" type="image/x-icon" href="img/icon.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/dados_clientes.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="header">

    <div class="logo">
        <a href="home.html">
            <img src="img/logo.png" alt="Logo CredTech">
        </a>
    </div>

    <nav class="menu">
        <a href="simulador.php">Simulador</a>
        <a href="clientes.php" class="menu-ativo">Clientes</a>
        <a href="sobre_nos.html">Sobre nós</a>
        <a href="central_ajuda.html"><span class="laranja">Central</span> de Ajuda</a>
    </nav>

    <div class="header-right">
        <div class="header-user">
            <div class="user-avatar">
                <?= strtoupper(substr($usuario, 0, 1)) ?>
            </div>
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($usuario) ?></span>
                <span class="user-role"><?= $tipo === 'admin' ? 'Administrador' : 'Usuário' ?></span>
            </div>
        </div>
        <a href="despedida.html" class="btn-sair" title="Sair">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>

</header>

<!-- ================= CONTEÚDO ================= -->
<div class="container">

    <!-- Título da página -->
    <div class="page-title">
        <div class="section-tag">
            <?= $tipo === 'admin' ? '<i class="fa-solid fa-shield-halved"></i> Painel Admin' : '<i class="fa-solid fa-users"></i> Clientes' ?>
        </div>
        <h1>
            <?= $tipo === 'admin' ? 'Visão geral do sistema' : 'Meus clientes cadastrados' ?>
        </h1>
    </div>

<?php if ($tipo === 'admin'): ?>

    <!-- ==================== VISÃO ADMIN ==================== -->

    <?php
    $total_clientes = $conn->query("SELECT COUNT(*) as total FROM info_clientes WHERE ativo = 1")->fetch_assoc()['total'];
    $clientes_mes   = $conn->query("SELECT COUNT(*) as total FROM info_clientes WHERE ativo = 1 AND MONTH(data_cadastro) = MONTH(NOW()) AND YEAR(data_cadastro) = YEAR(NOW())")->fetch_assoc()['total'];
    $total_usuarios = $conn->query("SELECT COUNT(*) as total FROM dados_login WHERE ativo = 1")->fetch_assoc()['total'];
    $top_usuario    = $conn->query("SELECT d.usuario, COUNT(c.id_cliente) as qtd FROM info_clientes c JOIN dados_login d ON c.id_usuario_criador = d.id_login WHERE c.ativo = 1 GROUP BY c.id_usuario_criador ORDER BY qtd DESC LIMIT 1")->fetch_assoc();
    ?>

    <div class="cards">
        <div class="card">
            <div class="card-icon"><i class="fa-solid fa-users"></i></div>
            <h3>Total de Clientes</h3>
            <p><?= $total_clientes ?></p>
        </div>
        <div class="card destaque">
            <div class="card-icon"><i class="fa-solid fa-calendar-day"></i></div>
            <h3>Clientes este mês</h3>
            <p><?= $clientes_mes ?></p>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fa-solid fa-user-check"></i></div>
            <h3>Usuários ativos</h3>
            <p><?= $total_usuarios ?></p>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fa-solid fa-trophy"></i></div>
            <h3>Quem mais cadastrou</h3>
            <p class="card-p-small"><?= $top_usuario ? htmlspecialchars($top_usuario['usuario']) . ' (' . $top_usuario['qtd'] . ')' : '—' ?></p>
        </div>
    </div>

    <!-- Tabela de usuários -->
    <div class="secao">
        <div class="secao-titulo">
            <i class="fa-solid fa-user-gear"></i> Usuários do Sistema
        </div>

        <div class="filtros">
            <div class="filtro-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="busca_usuario" placeholder="Buscar por usuário...">
            </div>
            <select id="filtro_tipo_usuario">
                <option value="">Todos os tipos</option>
                <option value="admin">Admin</option>
                <option value="usuario">Usuário</option>
            </select>
        </div>

        <div class="tabela-wrap">
            <table id="tabela_usuarios">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuário</th>
                        <th>Tipo</th>
                        <th>Último acesso</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $usuarios = $conn->query("SELECT * FROM dados_login ORDER BY id_login ASC");
                if ($usuarios->num_rows > 0):
                    while ($u = $usuarios->fetch_assoc()):
                        $status = $u['ativo'] ? 'ativo' : 'inativo';
                ?>
                    <tr>
                        <td class="td-id"><?= $u['id_login'] ?></td>
                        <td>
                            <div class="td-user">
                                <div class="td-avatar"><?= strtoupper(substr($u['usuario'], 0, 1)) ?></div>
                                <?= htmlspecialchars($u['usuario']) ?>
                            </div>
                        </td>
                        <td><span class="badge <?= $u['tipo'] ?>"><?= ucfirst($u['tipo']) ?></span></td>
                        <td><?= $u['data_acesso'] ?? '—' ?></td>
                        <td><span class="badge <?= $status ?>"><?= ucfirst($status) ?></span></td>
                        <td class="acoes">
                            <a href="editar_usuario.php?id=<?= $u['id_login'] ?>" class="btn btn-edit"><i class="fa-solid fa-pen"></i> Editar</a>
                            <a href="excluir_usuario.php?id=<?= $u['id_login'] ?>" class="btn btn-del" onclick="return confirm('Desativar este usuário?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="6" class="vazio"><i class="fa-solid fa-inbox"></i>
Nenhum usuário encontrado.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabela de todos os clientes -->
    <div class="secao">
        <div class="secao-titulo">
            <i class="fa-solid fa-address-card"></i> Todos os Clientes Cadastrados
        </div>

        <div class="filtros">
            <div class="filtro-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="busca_cliente_admin" placeholder="Buscar por nome ou CPF...">
            </div>
            <select id="filtro_tipo_admin">
                <option value="">Todos os tipos</option>
                <option value="fisica">Física</option>
                <option value="juridica">Jurídica</option>
            </select>
        </div>

        <div class="tabela-wrap">
            <table id="tabela_clientes_admin">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Cidade/UF</th>
                        <th>Tipo</th>
                        <th>Cadastrado por</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $clientes_admin = $conn->query("
                    SELECT c.*, d.usuario as nome_usuario
                    FROM info_clientes c
                    LEFT JOIN dados_login d ON c.id_usuario_criador = d.id_login
                    WHERE c.ativo = 1
                    ORDER BY c.id_cliente ASC
                ");
                if ($clientes_admin->num_rows > 0):
                    while ($c = $clientes_admin->fetch_assoc()):
                ?>
                    <tr>
                        <td class="td-id"><?= $c['id_cliente'] ?></td>
                        <td class="td-bold"><?= htmlspecialchars($c['nome']) ?></td>
                        <td class="td-mono"><?= htmlspecialchars($c['cpf']) ?></td>
                        <td><?= htmlspecialchars($c['telefone']) ?></td>
                        <td><?= htmlspecialchars($c['cidade']) ?>/<?= htmlspecialchars($c['estado']) ?></td>
                        <td><span class="badge <?= $c['tipo_pessoa'] ?>"><?= ucfirst($c['tipo_pessoa']) ?></span></td>
                        <td><?= htmlspecialchars($c['nome_usuario'] ?? '—') ?></td>
                        <td class="acoes">
                            <a href="editar_cliente.php?id=<?= $c['id_cliente'] ?>" class="btn btn-edit"><i class="fa-solid fa-pen"></i> Editar</a>
                            <a href="excluir_cliente.php?id=<?= $c['id_cliente'] ?>" class="btn btn-del" onclick="return confirm('Desativar este cliente?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="8" class="vazio"><i class="fa-solid fa-inbox"></i>
Nenhum cliente cadastrado.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>

    <!-- ==================== VISÃO USUÁRIO ==================== -->

    <div class="secao">
        <div class="secao-titulo">
            <i class="fa-solid fa-address-card"></i> Meus Clientes
        </div>

        <div class="filtros">
            <div class="filtro-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="busca_cliente" placeholder="Buscar por nome ou CPF...">
            </div>
            <select id="filtro_tipo">
                <option value="">Todos os tipos</option>
                <option value="fisica">Física</option>
                <option value="juridica">Jurídica</option>
            </select>
        </div>

        <div class="tabela-wrap">
            <table id="tabela_clientes">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Cidade/UF</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM info_clientes WHERE id_usuario_criador = ? AND ativo = 1 ORDER BY id_cliente ASC");
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $clientes = $stmt->get_result();
                if ($clientes->num_rows > 0):
                    while ($c = $clientes->fetch_assoc()):
                ?>
                    <tr>
                        <td class="td-id"><?= $c['id_cliente'] ?></td>
                        <td class="td-bold"><?= htmlspecialchars($c['nome']) ?></td>
                        <td class="td-mono"><?= htmlspecialchars($c['cpf']) ?></td>
                        <td><?= htmlspecialchars($c['telefone']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['cidade']) ?>/<?= htmlspecialchars($c['estado']) ?></td>
                        <td><span class="badge <?= $c['tipo_pessoa'] ?>"><?= ucfirst($c['tipo_pessoa']) ?></span></td>
                        <td class="acoes">
                            <a href="editar_cliente.php?id=<?= $c['id_cliente'] ?>" class="btn btn-edit"><i class="fa-solid fa-pen"></i> Editar</a>
                            <a href="excluir_cliente.php?id=<?= $c['id_cliente'] ?>" class="btn btn-del" onclick="return confirm('Desativar este cliente?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="8" class="vazio"><i class="fa-solid fa-inbox"></i>
Nenhum cliente cadastrado ainda.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>
</div>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <section class="footer-cta">
        <video autoplay muted loop playsinline class="video-background">
            <source src="img/boasvindas.mp4" type="video/mp4">
        </video>
        <div class="footer-cta-content">
            <h2>Realize mais com a CredTech.</h2>
            <p>Crédito inteligente para transformar seus planos em conquistas.</p>
            <a href="#" class="btn-cta-footer">Simular agora →</a>
        </div>
    </section>

    <section class="footer-links">
        <div class="container footer-links-grid">
            <div class="footer-column footer-company-info">
                <div class="footer-logo">
                    <img src="img/logo.png" alt="Logo CredTech Footer">
                </div>
                <p class="footer-description">Sua fintech de crédito digital confiável e rápida. Cuidando das suas finanças com tecnologia.</p>
                <div class="social-icons">
                    <a href="#"><img src="img/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="img/linkedin.png" alt="LinkedIn"></a>
                    <a href="#"><img src="img/instagram.png" alt="Instagram"></a>
                </div>
            </div>
            <div class="footer-column">
                <h3>Institucional</h3>
                <ul>
                    <li><a href="simulador.php">Simulador</a></li>
                    <li><a href="clientes.php">Simulador</a></li>
                    <li><a href="sobre_nos.html">Sobre nós</a></li>
                    <li><a href="central_ajuda.html">Central de Ajuda</a></li>
                </ul>
            </div>
            <div class="footer-column footer-newsletter">
                <h3>Fique por Dentro</h3>
                <p>Receba dicas financeiras, novidades e ofertas exclusivas da CredTech.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Endereço de e-mail" required>
                    <button type="submit">Inscrever-se</button>
                </form>
            </div>
        </div>
    </section>

    <section class="footer-bottom">
        <div class="container footer-bottom-content">
            <p>&copy; 2026 CredTech. Todos os direitos reservados.</p>
            <div class="legal-links">
                <a href="#">Política de Privacidade</a>
                <a href="#">Termos de Uso</a>
                <a href="#">Avisos Legais</a>
            </div>
        </div>
    </section>
</footer>

<!-- ================= SCRIPT ================= -->
<script>
function filtrarTabela(buscaId, filtroId, tabelaId, colBusca, colFiltro) {
    const busca  = document.getElementById(buscaId);
    const filtro = document.getElementById(filtroId);
    const tabela = document.getElementById(tabelaId);
    if (!busca || !tabela) return;

    function aplicar() {
        const texto = busca.value.toLowerCase();
        const tipo  = filtro ? filtro.value.toLowerCase() : '';
        tabela.querySelectorAll('tbody tr').forEach(tr => {
            const tdBusca  = tr.cells[colBusca]  ? tr.cells[colBusca].textContent.toLowerCase()  : '';
            const tdFiltro = tr.cells[colFiltro] ? tr.cells[colFiltro].textContent.toLowerCase() : '';
            tr.style.display = (!texto || tdBusca.includes(texto)) && (!tipo || tdFiltro.includes(tipo)) ? '' : 'none';
        });
    }
    busca.addEventListener('input', aplicar);
    if (filtro) filtro.addEventListener('change', aplicar);
}

function filtrarComDuasColunas(buscaId, filtroId, tabelaId, col1, col2, colFiltro) {
    const busca  = document.getElementById(buscaId);
    const filtro = document.getElementById(filtroId);
    const tabela = document.getElementById(tabelaId);
    if (!busca || !tabela) return;

    function aplicar() {
        const texto = busca.value.toLowerCase();
        const tipo  = filtro ? filtro.value.toLowerCase() : '';
        tabela.querySelectorAll('tbody tr').forEach(tr => {
            const td1      = tr.cells[col1]      ? tr.cells[col1].textContent.toLowerCase()      : '';
            const td2      = tr.cells[col2]      ? tr.cells[col2].textContent.toLowerCase()      : '';
            const tdFiltro = tr.cells[colFiltro] ? tr.cells[colFiltro].textContent.toLowerCase() : '';
            tr.style.display = (!texto || td1.includes(texto) || td2.includes(texto)) && (!tipo || tdFiltro.includes(tipo)) ? '' : 'none';
        });
    }
    busca.addEventListener('input', aplicar);
    if (filtro) filtro.addEventListener('change', aplicar);
}

filtrarComDuasColunas('busca_cliente',       'filtro_tipo',        'tabela_clientes',       1, 2, 6);
filtrarComDuasColunas('busca_cliente_admin', 'filtro_tipo_admin',  'tabela_clientes_admin', 1, 2, 5);
filtrarTabela        ('busca_usuario',       'filtro_tipo_usuario','tabela_usuarios',        1, 2);
</script>

</body>
</html>