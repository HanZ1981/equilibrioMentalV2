<?php
// Inicia a sessão para que possamos LER as variáveis de login
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo - Cuidado Emocional</title>
    <meta name="description" content="Transforme dor em cuidado e vulnerabilidade em força. Oferecemos atendimento psicológico especializado.">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <main class="pagina-principal">
        <div class="container-principal">
            
            <aside class="painel-lateral">
                <div class="cabecalho-boas-vindas">
                    
                    <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] == true): ?>
                        <h1 class="titulo-boas-vindas">Olá, <?php echo htmlspecialchars($_SESSION['usua_nome']); ?>!</h1>
                        
                        <p style="color: #6c757d; font-size: 14px; margin-top: -5px;">
                            (Perfil: <?php echo isset($_SESSION['papel']) ? htmlspecialchars($_SESSION['papel']) : 'Indefinido'; ?>)
                        </p>

                    <?php else: ?>
                        <h1 class="titulo-boas-vindas">Olá seja bem vindo!</h1>
                    <?php endif; ?>

                </div>
                
                <nav class="menu-navegacao">
                    <ul class="lista-menu">
                        <li class="item-menu">
                            <a href="atendimento.php" class="link-menu">• Atendimento</a>
                        </li>
                        <li class="item-menu">
                            <a href="depoimento.php" class="link-menu">• Depoimentos</a>
                        </li>
                        <li class="item-menu">
                            <a href="contato.php" class="link-menu">• Contato</a>
                        </li>
                        <li class="item-menu">
                            <a href="profissionais.php" class="link-menu">• Conheça nossos profissionais</a>
                        </li>
                    </ul>
                </nav>
                
                <div class="area-botao">

                    <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] == true): ?>
                        <a href="php/logout.php" class="botao-login">Sair</a>
                    <?php else: ?>
                        <a href="Login.html" class="botao-login">Login</a>
                    <?php endif; ?>

                </div>
            </aside>
            <section class="conteudo-principal">
                <div class="area-central">
                    <div class="ilustracao-container">
                        <img src="img/icon.png" alt="Ilustração de duas cabeças com engrenagens representando cuidado mental" class="ilustracao-cerebros">
                    </div>
                    
                    <div class="texto-principal">
                        <p class="descricao-servicos">
                            <strong>Ansiedade, depressão e outros desafios emocionais não definem quem você é.</strong> Conversar sobre isso é o primeiro passo para transformar dor em cuidado e vulnerabilidade em força.
                        </p>
                    </div>
                    
                    <div class="baloes-motivacionais">
                        <div class="balao-fala balao-1">
                            <p>"Você não precisa estar no seu melhor para começar. Comece, e o melhor virá."</p>
                        </div>
                        
                        <div class="balao-fala balao-2">
                            <p>"Buscar ajuda é um ato de amor-próprio, não de fraqueza."</p>
                        </div>
                        
                        <div class="balao-fala balao-3">
                            <p>"Respire. Você chegou até aqui. E isso já é incrível."</p>
                        </div>
                    </div>
                </div>
                
                <footer class="rodape-navegacao">
                    <a href="sobre.php" class="link-sobre">Sobre nós</a>
                </footer>
            </section>
        </div>
    </main>
</body>
</html>