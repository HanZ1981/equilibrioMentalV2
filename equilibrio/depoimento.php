<?php
// Inicia a sessão (para o menu Login/Sair)
session_start();

// Inclui a conexão com o banco
require_once 'php/db_connect.php';

// --- Busca os Depoimentos no Banco ---
try {
    $sql = "SELECT depo_texto, depo_autor_nome, depo_autor_info FROM DEPOIMENTOS ORDER BY depo_id";
    $stmt = $pdo->query($sql);
    $depoimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $depoimentos = [];
    $erro_banco = "Erro ao buscar depoimentos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depoimentos - Cuidado Emocional</title>
    <meta name="description" content="Conheça os depoimentos de nossos pacientes e suas experiências de transformação e crescimento pessoal.">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <main class="pagina-interna">
        <div class="container-interno">
            <header class="cabecalho-pagina">
                <h1 class="titulo-pagina">Depoimentos</h1>
                <p class="subtitulo-pagina">Histórias reais de transformação e crescimento pessoal</p>
            </header>

            <div class="conteudo-pagina">
                <p>Cada jornada é única e especial. Aqui compartilhamos algumas experiências de pessoas que escolheram cuidar de sua saúde mental conosco:</p>
                
                <div class="grid-depoimentos">

                    <?php if (isset($erro_banco)): ?>
                        <p style="color: red;"><?php echo htmlspecialchars($erro_banco); ?></p>
                    
                    <?php elseif (empty($depoimentos)): ?>
                        <p>Nenhum depoimento cadastrado no momento.</p>

                    <?php else: ?>
                        <?php foreach ($depoimentos as $depo): ?>
                            <div class="card-depoimento">
                                <p class="texto-depoimento">"<?php echo htmlspecialchars($depo['depo_texto']); ?>"</p>
                                <p class="autor-depoimento">— <?php echo htmlspecialchars($depo['depo_autor_nome']); ?>, <?php echo htmlspecialchars($depo['depo_autor_info']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
                <h2>Sua História Também Pode Ser Transformada</h2>
                <p>Cada pessoa que passa por nossa porta traz consigo uma história única, com desafios e potenciais próprios. Acreditamos que todos têm a capacidade de crescer, se transformar e encontrar maneiras mais saudáveis de viver.</p>
                
                <p>Se você se identificou com algum desses depoimentos ou simplesmente sente que precisa de apoio emocional, não hesite em entrar em contato conosco. Estamos aqui para acompanhá-lo em sua jornada de autoconhecimento e bem-estar.</p>
            </div>

            <nav class="navegacao-volta">
                <a href="index.php" class="botao-voltar">← Voltar ao Início</a>
            </nav>
        </div>
    </main>
</body>
</html>