<?php
// Inicia a sessão para mostrar o cabeçalho dinâmico (Login/Sair)
session_start();

// Inclui a conexão com o banco
require_once 'php/db_connect.php';

// --- Busca os Psicólogos no Banco ---
try {
    // Vamos buscar os dados do PSICOLOGO e também o NOME do USUARIO associado
    $sql = "SELECT 
                u.usua_nome, 
                p.psic_crp, 
                p.psic_especialidade_titulo, 
                p.psic_descricao
            FROM PSICOLOGO p
            JOIN USUARIO u ON p.usua_id = u.usua_id
            ORDER BY u.usua_nome";
            
    $stmt = $pdo->query($sql);
    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    // Se der erro, cria um array vazio para não quebrar a página
    $profissionais = [];
    $erro_banco = "Erro ao buscar profissionais: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profissionais - Cuidado Emocional</title>
    <meta name="description" content="Conheça nossa equipe de psicólogos especializados, suas formações e áreas de atuação.">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <main class="pagina-interna">
        <div class="container-interno">
            <header class="cabecalho-pagina">
                <h1 class="titulo-pagina">Nossos Profissionais</h1>
                <p class="subtitulo-pagina">Equipe especializada e comprometida com seu bem-estar</p>
            </header>

            <div class="conteudo-pagina">
                <p>Nossa equipe é formada por psicólogos experientes e dedicados, cada um com suas especialidades e abordagens terapêuticas. Todos são registrados no Conselho Regional de Psicologia e mantêm-se atualizados através de formação continuada.</p>
                
                <div class="grid-profissionais">
                    
                    <?php if (isset($erro_banco)): ?>
                        <p style="color: red;"><?php echo htmlspecialchars($erro_banco); ?></p>
                    
                    <?php elseif (empty($profissionais)): ?>
                        <p>Nenhum profissional cadastrado no momento.</p>

                    <?php else: ?>
                        <?php foreach ($profissionais as $prof): ?>
                            <div class="card-profissional">
                                <h3 class="nome-profissional"><?php echo htmlspecialchars($prof['usua_nome']); ?></h3>
                                <p class="especialidade">CRP: <?php echo htmlspecialchars($prof['psic_crp']); ?></p>
                                <p class="especialidade"><?php echo htmlspecialchars($prof['psic_especialidade_titulo']); ?></p>
                                <p class="descricao-profissional">
                                    <?php echo nl2br(htmlspecialchars($prof['psic_descricao'])); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
                <h2>Nossa Abordagem</h2>
                <p>Acreditamos que cada pessoa é única...</p>
                
                <h3>Abordagens Disponíveis:</h3>
                <ul>
                    <li><strong>Terapia Cognitivo-Comportamental (TCC):</strong> Foca na identificação e modificação de padrões de pensamento...</li>
                    </ul>

                </div>

            <nav class="navegacao-volta">
                <a href="index.php" class="botao-voltar">← Voltar ao Início</a>
            </nav>
        </div>
    </main>
</body>
</html>