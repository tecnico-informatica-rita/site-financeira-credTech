<?php
session_start();
$_SESSION['admin'] = 1;      // coloca o id de um usuário que existe no banco
$_SESSION['usuario']    = 'rita'; // o nome do usuário
$_SESSION['tipo']       = 'usuario'; // 'usuario' ou 'admin'

header("Location: clientes.php");
exit;