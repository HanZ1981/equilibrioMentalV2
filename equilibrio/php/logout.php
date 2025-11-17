<?php
// Sempre inicie a sessão em qualquer script que vá mexer com ela
session_start();

// Destrói todas as variáveis da sessão (apaga o 'usua_id', 'usua_nome', etc.)
session_destroy();

// Redireciona o usuário de volta para a página inicial
header("Location: ../index.php"); // ../ sobe um nível da pasta 'php'
exit(); // Garante que o script pare aqui
?>