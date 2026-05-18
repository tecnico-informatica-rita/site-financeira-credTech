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
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; color: #333; }
        header { background: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.4rem; }
        header a { color: #aaa; text-decoration: none; font-size: 0.9rem; }
        header a:hover { color: white; }
        .container { padding: 30px; max-width: 1200px; margin: 0 auto; }

        /* CARDS ADMIN */
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); text-align: center; }
        .card h3 { font-size: 0.85rem; color: #888; margin-bottom: 8px; text-transform: uppercase; }
        .card p { font-size: 2rem; font-weight: bold; color: #2c3e50; }
        .card.destaque p { color: #27ae60; }

        /* FILTROS */
        .filtros { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
        .filtros input, .filtros select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem; }
        .filtros input { flex: 1; min-width: 200px; }

        /* TABELA */
        .tabela-wrap { background: white; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        thead { background: #2c3e50; color: white; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #f0f0f0; }
        tr:hover { background: #f9f9f9; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: bold; }
        .badge.fisica { background: #d6eaf8; color: #2980b9; }
        .badge.juridica { background: #d5f5e3; color: #27ae60; }
        .badge.admin { background: #fde8d8; color: #e67e22; }
        .badge.usuario { background: #e8daef; color: #8e44ad; }
        .badge.ativo { background: #d5f5e3; color: #27ae60; }
        .badge.inativo { background: #fadbd8; color: #e74c3c; }

        /* AÇÕES */
        .acoes { display: flex; gap: 8px; }
        .btn { padding: 5px 12px; border-radius: 5px; border: none; cursor: pointer; font-size: 0.8rem; text-decoration: none; display: inline-block; }
        .btn-edit { background: #3498db; color: white; }
        .btn-edit:hover { background: #2980b9; }
        .btn-del { background: #e74c3c; color: white; }
        .btn-del:hover { background: #c0392b; }

        /* SEÇÃO */
        .secao-titulo { font-size: 1.1rem; font-weight: bold; color: #2c3e50; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #e0e0e0; }
        .secao { margin-bottom: 40px; }

        .vazio { text-align: center; padding: 40px; color: #aaa; font-size: 0.95rem; }
    </style>
</head>
<body>

<header>
    <h1>CredTech <?= $tipo === 'admin' ? '— Painel Admin' : '— Meus Clientes' ?></h1>
    <div>
        Olá, <strong><?= htmlspecialchars($usuario) ?></strong> &nbsp;|&nbsp;
        <a href="index.php">Sair</a>
    </div>
</header>

<div class="container">

<?php if ($tipo === 'admin'): ?>

    <!-- ==================== VISÃO ADMIN ==================== -->

    <?php
    // Cards panorâmicos
    $total_clientes   = $conn->query("SELECT COUNT(*) as total FROM info_clientes WHERE ativo = 1")->fetch_assoc()['total'];
    $clientes_mes     = $conn->query("SELECT COUNT(*) as total FROM info_clientes WHERE ativo = 1 AND MONTH(data_cadastro) = MONTH(NOW()) AND YEAR(data_cadastro) = YEAR(NOW())")->fetch_assoc()['total'];
    $total_usuarios   = $conn->query("SELECT COUNT(*) as total FROM dados_login WHERE ativo = 1")->fetch_assoc()['total'];
    $top_usuario      = $conn->query("SELECT d.usuario, COUNT(c.id_cliente) as qtd FROM info_clientes c JOIN dados_login d ON c.id_usuario_criador = d.id_login WHERE c.ativo = 1 GROUP BY c.id_usuario_criador ORDER BY qtd DESC LIMIT 1")->fetch_assoc();
    ?>

    <div class="cards">
        <div class="card">
            <h3>Total de Clientes</h3>
            <p><?= $total_clientes ?></p>
        </div>
        <div class="card destaque">
            <h3>Clientes este mês</h3>
            <p><?= $clientes_mes ?></p>
        </div>
        <div class="card">
            <h3>Usuários ativos</h3>
            <p><?= $total_usuarios ?></p>
        </div>
        <div class="card">
            <h3>Quem mais cadastrou</h3>
            <p style="font-size:1.2rem"><?= $top_usuario ? htmlspecialchars($top_usuario['usuario']) . ' (' . $top_usuario['qtd'] . ')' : '—' ?></p>
        </div>
    </div>

    <!-- Tabela de usuários -->
    <div class="secao">
        <div class="secao-titulo">Usuários do Sistema</div>

        <div class="filtros">
            <input type="text" id="busca_usuario" placeholder="Buscar por usuário...">
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
                        <td><?= $u['id_login'] ?></td>
                        <td><?= htmlspecialchars($u['usuario']) ?></td>
                        <td><span class="badge <?= $u['tipo'] ?>"><?= ucfirst($u['tipo']) ?></span></td>
                        <td><?= $u['data_acesso'] ?? '—' ?></td>
                        <td><span class="badge <?= $status ?>"><?= ucfirst($status) ?></span></td>
                        <td class="acoes">
                            <a href="editar_usuario.php?id=<?= $u['id_login'] ?>" class="btn btn-edit">Editar</a>
                            <a href="excluir_usuario.php?id=<?= $u['id_login'] ?>" class="btn btn-del" onclick="return confirm('Desativar este usuário?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="6" class="vazio">Nenhum usuário encontrado.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabela de todos os clientes (visão admin) -->
    <div class="secao">
        <div class="secao-titulo">Todos os Clientes Cadastrados</div>

        <div class="filtros">
            <input type="text" id="busca_cliente_admin" placeholder="Buscar por nome ou CPF...">
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
                        <td><?= $c['id_cliente'] ?></td>
                        <td><?= htmlspecialchars($c['nome']) ?></td>
                        <td><?= htmlspecialchars($c['cpf']) ?></td>
                        <td><?= htmlspecialchars($c['telefone']) ?></td>
                        <td><?= htmlspecialchars($c['cidade']) ?>/<?= htmlspecialchars($c['estado']) ?></td>
                        <td><span class="badge <?= $c['tipo_pessoa'] ?>"><?= ucfirst($c['tipo_pessoa']) ?></span></td>
                        <td><?= htmlspecialchars($c['nome_usuario'] ?? '—') ?></td>
                        <td class="acoes">
                            <a href="editar_cliente.php?id=<?= $c['id_cliente'] ?>" class="btn btn-edit">Editar</a>
                            <a href="excluir_cliente.php?id=<?= $c['id_cliente'] ?>" class="btn btn-del" onclick="return confirm('Desativar este cliente?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="8" class="vazio">Nenhum cliente cadastrado.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>

    <!-- ==================== VISÃO USUÁRIO ==================== -->

    <div class="secao">
        <div class="secao-titulo">Meus Clientes</div>

        <div class="filtros">
            <input type="text" id="busca_cliente" placeholder="Buscar por nome ou CPF...">
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
                        <td><?= $c['id_cliente'] ?></td>
                        <td><?= htmlspecialchars($c['nome']) ?></td>
                        <td><?= htmlspecialchars($c['cpf']) ?></td>
                        <td><?= htmlspecialchars($c['telefone']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['cidade']) ?>/<?= htmlspecialchars($c['estado']) ?></td>
                        <td><span class="badge <?= $c['tipo_pessoa'] ?>"><?= ucfirst($c['tipo_pessoa']) ?></span></td>
                        <td class="acoes">
                            <a href="editar_cliente.php?id=<?= $c['id_cliente'] ?>" class="btn btn-edit">Editar</a>
                            <a href="excluir_cliente.php?id=<?= $c['id_cliente'] ?>" class="btn btn-del" onclick="return confirm('Desativar este cliente?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="8" class="vazio">Nenhum cliente cadastrado ainda.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>
</div>

<script>
// Filtro tabela clientes (usuário)
function filtrarTabela(buscaId, filtroId, tabelaId, colBusca, colFiltro) {
    const busca   = document.getElementById(buscaId);
    const filtro  = document.getElementById(filtroId);
    const tabela  = document.getElementById(tabelaId);
    if (!busca || !tabela) return;

    function aplicar() {
        const texto = busca.value.toLowerCase();
        const tipo  = filtro ? filtro.value.toLowerCase() : '';
        tabela.querySelectorAll('tbody tr').forEach(tr => {
            const tdBusca  = tr.cells[colBusca]  ? tr.cells[colBusca].textContent.toLowerCase()  : '';
            const tdFiltro = tr.cells[colFiltro] ? tr.cells[colFiltro].textContent.toLowerCase() : '';
            const passaBusca  = !texto || tdBusca.includes(texto);
            const passaFiltro = !tipo  || tdFiltro.includes(tipo);
            tr.style.display = passaBusca && passaFiltro ? '' : 'none';
        });
    }

    busca.addEventListener('input', aplicar);
    if (filtro) filtro.addEventListener('change', aplicar);
}

// Coluna 1 = nome (e CPF col 2), buscamos nas duas
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
            const passaBusca  = !texto || td1.includes(texto) || td2.includes(texto);
            const passaFiltro = !tipo  || tdFiltro.includes(tipo);
            tr.style.display = passaBusca && passaFiltro ? '' : 'none';
        });
    }

    busca.addEventListener('input', aplicar);
    if (filtro) filtro.addEventListener('change', aplicar);
}

// Inicializa filtros conforme a view
filtrarComDuasColunas('busca_cliente',       'filtro_tipo',        'tabela_clientes',       1, 2, 6);
filtrarComDuasColunas('busca_cliente_admin', 'filtro_tipo_admin',  'tabela_clientes_admin', 1, 2, 5);
filtrarTabela        ('busca_usuario',       'filtro_tipo_usuario','tabela_usuarios',        1, 2);
</script>

</body>
</html>