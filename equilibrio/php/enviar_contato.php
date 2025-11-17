<?php
// Inclui a conexão com o banco
require_once 'db_connect.php';

// Resposta padrão
$resposta = [
    'sucesso' => false,
    'mensagem' => 'Ocorreu um erro ao enviar sua solicitação.'
];

try {
    // Pega os dados do formulário (os 'name' do HTML)
    // Os campos obrigatórios no HTML são 'nome', 'email', 'telefone'
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    // Campos opcionais
    // Usamos o 'operador de coalescência nula' (??) para definir null se não vier
    $idade = !empty($_POST['idade']) ? $_POST['idade'] : null;
    $modalidade = !empty($_POST['modalidade']) ? $_POST['modalidade'] : null;
    $horario = !empty($_POST['preferencia-horario']) ? $_POST['preferencia-horario'] : null;
    $como_conheceu = !empty($_POST['como-conheceu']) ? $_POST['como-conheceu'] : null;
    $mensagem = !empty($_POST['mensagem']) ? $_POST['mensagem'] : null;

    // Validação simples
    if (empty($nome) || empty($email) || empty($telefone)) {
        $resposta['mensagem'] = 'Por favor, preencha os campos obrigatórios (Nome, E-mail, Telefone).';
        throw new Exception($resposta['mensagem']);
    }

    // --- Inserção no Banco ---
    $sql = "INSERT INTO SOLICITACOES_CONTATO 
                (soli_nome_completo, soli_email, soli_telefone, soli_idade, soli_modalidade_interesse, soli_preferencia_horario, soli_como_conheceu, soli_mensagem) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // Executa a inserção com todos os dados
    if ($stmt->execute([$nome, $email, $telefone, $idade, $modalidade, $horario, $como_conheceu, $mensagem])) {
        $resposta['sucesso'] = true;
        $resposta['mensagem'] = 'Sua solicitação foi enviada com sucesso! Entraremos em contato em breve.';
    } else {
        $resposta['mensagem'] = 'Erro ao salvar a solicitação no banco de dados.';
    }

} catch (Exception $e) {
    // A $resposta['mensagem'] já foi definida no 'throw'
    $resposta['sucesso'] = false;
}

// --- Resposta Final ---
header('Content-Type: application/json');
echo json_encode($resposta);
?>