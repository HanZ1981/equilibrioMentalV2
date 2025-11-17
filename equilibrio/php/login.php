<?php
// Inicia uma sessão PHP
session_start(); 

require_once 'db_connect.php';

$resposta = [
    'sucesso' => false,
    'mensagem' => 'E-mail ou senha incorretos.'
];

try {
    $usua_email = $_POST['email-login'];
    $usua_senha = $_POST['senha'];

    if (empty($usua_email) || empty($usua_senha)) {
        $resposta['mensagem'] = 'Por favor, preencha e-mail e senha.';
        throw new Exception($resposta['mensagem']);
    }

    // 1. Procura o usuário e o hash
    $sql = "SELECT usua_id, usua_nome, usua_senha_hash FROM USUARIO WHERE usua_email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usua_email]);
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Verifica a senha
    if ($usuario && password_verify($usua_senha, $usuario['usua_senha_hash'])) {
        
        $resposta['sucesso'] = true;
        $resposta['mensagem'] = 'Login bem-sucedido! Redirecionando...';

        $usua_id = $usuario['usua_id'];

        // --- NOVO: BUSCA O PAPEL (ROLE) DO USUÁRIO ---
        // Vamos usar LEFT JOINs para checar as 3 tabelas de papéis de uma vez
        $sql_papel = "
            SELECT 
                a.alun_id,
                p.psic_crp,
                adm.admi_codigo
            FROM USUARIO u
            LEFT JOIN ALUNO a ON u.usua_id = a.usua_id
            LEFT JOIN PSICOLOGO p ON u.usua_id = p.usua_id
            LEFT JOIN ADMINISTRADOR adm ON u.usua_id = adm.usua_id
            WHERE u.usua_id = ?
        ";
        
        $stmt_papel = $pdo->prepare($sql_papel);
        $stmt_papel->execute([$usua_id]);
        $papeis = $stmt_papel->fetch(PDO::FETCH_ASSOC);

        // --- NOVO: SALVA OS DADOS BÁSICOS E O PAPEL NA SESSÃO ---
        $_SESSION['usua_id'] = $usua_id;
        $_SESSION['usua_nome'] = $usuario['usua_nome'];
        $_SESSION['logado'] = true;
        $_SESSION['papel'] = 'indefinido'; // Padrão caso não seja nenhum dos 3

        // Define o papel principal (Admin > Psicólogo > Aluno)
        if (!empty($papeis['alun_id'])) {
            $_SESSION['papel'] = 'aluno';
            $_SESSION['alun_id'] = $papeis['alun_id']; // ID do Aluno (para o chat)
        }
        if (!empty($papeis['psic_crp'])) {
            $_SESSION['papel'] = 'psicologo';
            $_SESSION['psic_crp'] = $papeis['psic_crp']; // CRP (para o chat)
        }
        if (!empty($papeis['admi_codigo'])) {
            $_SESSION['papel'] = 'admin';
            $_SESSION['admi_codigo'] = $papeis['admi_codigo'];
        }

    } else {
        // Se a senha falhar ou o usuário não existir
        throw new Exception('E-mail ou senha incorretos.');
    }

} catch (Exception $e) {
    $resposta['sucesso'] = false;
    $resposta['mensagem'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($resposta);
?>