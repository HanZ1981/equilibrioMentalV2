<?php
require_once 'db_connect.php'; // O $pdo (conexão) vem daqui

$resposta = [
    'sucesso' => false,
    'mensagem' => 'Ocorreu um erro desconhecido ao cadastrar.'
];

// Inicia a Transação:
// Vamos mexer em múltiplas tabelas. Se qualquer passo falhar,
// o $pdo->rollBack() desfaz tudo, mantendo o banco íntegro.
$pdo->beginTransaction();

try {
    // 1. Pega TODOS os dados do formulário
    $usua_nome = $_POST['nome'];
    $usua_email = $_POST['email'];
    $usua_senha = $_POST['senha'];
    $usua_confirm_senha = $_POST['confirmarSenha'];
    $tel_numero = $_POST['telefone'];     // Novo
    $inst_nome = $_POST['instituicao'];  // Novo

    // --- VALIDAÇÕES ---
    if (empty($usua_nome) || empty($usua_email) || empty($usua_senha)) {
        $resposta['mensagem'] = 'Nome, e-mail e senha são obrigatórios.';
        throw new Exception($resposta['mensagem']);
    }
    if ($usua_senha !== $usua_confirm_senha) {
        $resposta['mensagem'] = 'As senhas não coincidem!';
        throw new Exception($resposta['mensagem']);
    }
    if (strlen($usua_senha) < 6) {
        $resposta['mensagem'] = 'A senha deve ter no mínimo 6 caracteres.';
        throw new Exception($resposta['mensagem']);
    }

    // --- INSERÇÃO 1: Tabela USUARIO ---

    // Verifica se o e-mail já existe
    $sql_check = "SELECT usua_id FROM USUARIO WHERE usua_email = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$usua_email]);
    if ($stmt_check->fetch()) {
        $resposta['mensagem'] = 'Este e-mail já está em uso.';
        throw new Exception($resposta['mensagem']);
    }

    // Cria o hash da senha
    $usua_senha_hash = password_hash($usua_senha, PASSWORD_BCRYPT);

    // Insere o usuário base
    $sql_user = "INSERT INTO USUARIO (usua_nome, usua_email, usua_senha_hash) VALUES (?, ?, ?)";
    $stmt_user = $pdo->prepare($sql_user);
    $stmt_user->execute([$usua_nome, $usua_email, $usua_senha_hash]);

    // Pega o ID do usuário que acabamos de criar
    $novo_usua_id = $pdo->lastInsertId();

    // --- INSERÇÃO 2: Tabela TELEFONE (Opcional) ---
    if (!empty($tel_numero)) {
        $sql_tel = "INSERT INTO TELEFONE (usua_id, tel_numero) VALUES (?, ?)";
        $stmt_tel = $pdo->prepare($sql_tel);
        $stmt_tel->execute([$novo_usua_id, $tel_numero]);
    }

    // --- INSERÇÃO 3: Tabela INSTITUICAO (Lógica "Encontre ou Crie") ---
    $inst_id = null; // Começa como nulo
    if (!empty($inst_nome)) {
        // 3a. Verifica se a instituição JÁ EXISTE
        $sql_inst_check = "SELECT inst_id FROM INSTITUICAO WHERE inst_nome = ?";
        $stmt_inst_check = $pdo->prepare($sql_inst_check);
        $stmt_inst_check->execute([$inst_nome]);
        
        $instituicao = $stmt_inst_check->fetch(PDO::FETCH_ASSOC);
        
        if ($instituicao) {
            // 3b. Se existe, usa o ID dela
            $inst_id = $instituicao['inst_id'];
        } else {
            // 3c. Se NÃO existe, cria uma nova
            $sql_inst_new = "INSERT INTO INSTITUICAO (inst_nome) VALUES (?)";
            $stmt_inst_new = $pdo->prepare($sql_inst_new);
            $stmt_inst_new->execute([$inst_nome]);
            // Pega o ID da instituição que acabamos de criar
            $inst_id = $pdo->lastInsertId();
        }
    }

    // --- INSERÇÃO 4: Tabela ALUNO (Obrigatório) ---
    // Finalmente, criamos o registro do ALUNO, ligando o USUARIO à INSTITUICAO
    $sql_aluno = "INSERT INTO ALUNO (usua_id, inst_id) VALUES (?, ?)";
    $stmt_aluno = $pdo->prepare($sql_aluno);
    $stmt_aluno->execute([$novo_usua_id, $inst_id]); // $inst_id será null se o campo veio vazio

    // Se chegou até aqui, tudo deu certo. Confirma as mudanças no banco.
    $pdo->commit();
    
    $resposta['sucesso'] = true;
    $resposta['mensagem'] = 'Aluno cadastrado com sucesso! Redirecionando...';

} catch (Exception $e) {
    // Se qualquer comando falhou, desfaz TUDO
    $pdo->rollBack();
    
    $resposta['sucesso'] = false;
    // Se a mensagem de erro não foi uma das nossas, usa a mensagem do sistema
    if ($resposta['mensagem'] == 'Ocorreu um erro desconhecido ao cadastrar.') {
         $resposta['mensagem'] = $e->getMessage();
    }
}

// --- Resposta Final ---
header('Content-Type: application/json');
echo json_encode($resposta);
?>