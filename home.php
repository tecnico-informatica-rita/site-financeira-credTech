<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <link rel="icon" type="image/x-icon" href="img/icon.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Home | CredTech</title>
</head>
<body>

    <!-- ================= HEADER ================= -->
    <header class="header">
        <!--Logo-->
        <div class="logo">
            <a href="home.html">
                <img src="img/logo.png" alt="Logo_CredTech">
            </a>
        </div>
        <!--Navegação-->
        <nav class="menu">
            <a href="#">Simulador</a>
            <a href="#">Empréstimos</a>

            <a href="dados_clientes.php">Clientes</a>
            <a href="#">Sobre nós</a>
            <a href="#">Central de Ajuda</a>
        </nav>
        <div class="header-buttons">
            <a href="despedida.html" class="btn-sair">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </header>

    <!-- ================= MAIN ================= -->
    <main>
        <section class="hero">
            <!--Container do texto inicial a esquerda-->
            <div class="hero-text">
                <!--Texto-->
                <span class="hero-mini-text">Crédito Inteligente, 100% Digital</span>
                <h1>O crédito que <span class="text-orange">impulsiona</span> seus planos.</h1>
                <p class="hero-description">Empréstimos rápidos, seguros e acessíveis para você conquistar o que importa.</p>
                
                <!--Botão para simular-->
                <div class="hero-buttons">
                    <a href="#" class="btn-simular">Simular agora →</a>
                </div>
                
                <!--Frase com uma figura de Cadeado-->
                <div class="security-info">
                    <img src="img/cadeado.png" alt="Ícone de segurança">
                    <p>Seus dados protegidos com segurança de ponta.</p>
                </div>

                <!--Dados-->
                <div class="hero-data">
                    <div class="data-item">
                        <img src="img/usuario.png" alt="Numero_Clientes">
                        <h3>+250 mil</h3>
                        <p>clientes</p>
                    </div>
                    <div class="data-item">
                        <img src="img/baixar.png" alt="Numero_Creditos">
                        <h3>+R$ 1,2 bi</h3>
                        <p>em créditos liberados</p>
                    </div>
                    <div class="data-item">
                        <img src="img/aprovado.png" alt="Aprovação">
                        <h3>98%</h3>
                        <p>de aprovação</p>
                    </div>
                </div>
            </div>

            <!--Container do texto inicial a direita-->
            <div class="hero-card">

                <!--Card Principal-->
                <div class="card-security">
                    <img src="img/seguranca.png" alt="Selo_Segurança">
                    <h3>Segurança que você pode confiar.</h3>
                    <p>Tecnologia avançada para proteger o que é seu.</p>
                    
                    <div class="security-list">
                        <div class="security-item">
                            <img src="img/check.png" alt="Check">
                            <p>Criptografia de ponta a ponta.</p>
                        </div>
                        <div class="security-item">
                            <img src="img/check.png" alt="Check">
                            <p>Análise inteligente de risco.</p>
                        </div>
                        <div class="security-item">
                            <img src="img/check.png" alt="Check">
                            <p>Privacidade garantida.</p>
                        </div>
                    </div>
                </div>

                <!--Card Decorativo-->
                <div class="card-background"></div>
            </div>
        </section>

        <!--Segunda Parte-->
        <section class="segunda-parte">
            <!--Quadro 1-->
            <div class="quadro-um">
                <img src="img/moedas.png" alt="Ilustração de moedas digitais">
                <!--Texto do quadro-->
                <div class="quadro-um-conteudo">
                    <h3>Dinheiro na conta em poucos minutos.</h3>
                    <p>Processo simples, 100% digital e sem burocracia.</p>
                </div>
                <!--Botão para Fazer Empréstimo-->
                <a href="#" class="btn-seta">→</a>
            </div>

            <!--Quadro 2-->
            <div class="quadro-dois">
                <!--Texto-->
                <div class="quadro-dois-topo">
                    <h3>Simule e descubra seu melhor plano.</h3>
                    
                    <!--Taxa-->
                    <div class="taxa">
                        <span>Taxas a partir de</span>
                        <h4>1,19%</h4><p>ao mês</p>
                    </div>
                </div>

                <!--Caixa do Simulador-->
                <div class="box-simulador">
                    <div class="valor">
                        <p>Valor desejado</p>
                        <h2>R$ 15.000,00</h2>
                    </div>

                    <!--Barra-->
                    <div class="barra-range">
                        <input type="range" min="1000" max="50000">
                    </div>

                    <div class="limites">
                        <span>R$ 1.000</span>
                        <span>R$ 50.000</span>
                    </div>

                    <!--Informações do esmpréstimo simulado-->
                    <div class="informacoes">
                        <div class="info">
                            <p>Parcelas em até</p>
                            <h3>24x</h3>
                        </div>
                        <div class="info">
                            <p>Valor da parcela</p>
                            <h3>R$ 725,98</h3>
                        </div>
                    </div>
                    <!--Botão para simular-->
                    <a href="#" class="btn-simular-card">Simular agora</a>
                </div>
            </div>
        </section>

        <section class="terceira-parte">
            <div class="text-terc">
                <span>Por que escolher a CredTech?</span>
                <h2>Tecnologia para <span class="text-orange">simplificar</span> sua vida financeira.</h2>
            </div>

            <div class="cards-terc">
                <div class="card-dados">
                    <img src="#" alt="Ícone de aplicativo digital">
                    <h4>100% Digital</h4>
                    <p>Contrate do seu jeito, onde estiver, a qualquer hora.</p>
                </div>
                <div class="card-dados">
                    <img src="#" alt="Ícone de raio">
                    <h4>Liberação rápida</h4>
                    <p>Dinheiro na conta em minutos.</p>
                </div>
                <div class="card-dados">
                    <img src="#" alt="Ícone de porcentagem">
                    <h4>Taxas justas</h4>
                    <p>Condições transparentes e que cabem no seu bolso.</p>
                </div>
                <div class="card-dados">
                    <img src="#" alt="Ícone de fone">
                    <h4>Atendimento humano</h4>
                    <p>Suporte de verdade sempre que precisar.</p>
                </div>
            </div>
        </section>

        <section class="quarta-parte">
            <div class="text-qua">
                <span>Cartão CredTech</span>
                <h2>Mais liberdade para o seu dia a dia.</h2>
                <p>Compre, pague e controle tudo diretamente pelo app.</p>
                <a href="#" class="btn-cartao">Pedir meu cartão →</a>
            </div>

            <div class="img-cartao">
                <img src="img/cartao.png" alt="Cartão CredTech">
            </div>
        </section>

        <section class="quinta-parte">
            <div class="text-qui">
                <span>Dúvidas frequentes</span>
                <h2>Perguntas que <span class="text-orange">respondemos sempre.</span></h2>
            </div>

            <div class="perguntas-qui">
                <div class="pergunta-qui">
                    <button class="questao-qui">Como funciona análise de crédito? <span>+</span></button>
                    <div class="resposta-qui">
                        <p>Nossa análise é feita de forma
                        inteligente e segura, considerando
                        diferentes fatores financeiros
                        para oferecer as melhores condições.</p>
                    </div>
                </div>
                <div class="pergunta-qui">
                    <button class="questao-qui">Em quanto tempo o dinheiro cai na conta? <span>+</span></button>
                    <div class="resposta-qui">
                        <p>Após a aprovação, o valor pode ser
                        liberado em poucos minutos diretamente
                        na sua conta.</p>
                    </div>
                </div>
                <div class="pergunta-qui">
                    <button class="questao-qui">A CredTech é segura? <span>+</span></button>
                    <div class="resposta-qui">
                        <p>Sim. Utilizamos criptografia avançada
                        e tecnologia de proteção de dados
                        para garantir total segurança.</p>
                    </div>
                </div>
                <div class="pergunta-qui">
                    <button class="questao-qui">Posso antecipar parcelas? <span>+</span></button>
                    <div class="resposta-qui">
                        <p>Sim. Você pode antecipar parcelas
                        diretamente pelo sistema e obter
                        descontos proporcionais.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

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

        <!-- Seção Principal de Links (Fundo Claro) -->
        <section class="footer-links">
            <div class="container footer-links-grid">
                <!-- Coluna 1: Logo e Descrição da Empresa -->
                <div class="footer-column footer-company-info">
                    <div class="footer-logo">
                        <img src="#" alt="Logo_CredTech_Footer"> <!-- Placeholder para o seu logo -->
                    </div>
                    <p class="footer-description">Sua fintech de crédito digital confiável e rápida. Cuidando das suas finanças com tecnologia.</p>
                    <div class="social-icons">
                        <!-- Placeholders para os ícones de mídia social -->
                        <a href="#"><img src="#" alt="Facebook"></a> 
                        <a href="#"><img src="#" alt="LinkedIn"></a>
                        <a href="#"><img src="#" alt="Instagram"></a>
                    </div>
                </div>

                <!-- Coluna 2: Institucional -->
                <div class="footer-column">
                    <h3>Institucional</h3>
                    <ul>
                        <li><a href="#">Simulador</a></li>
                        <li><a href="#">Empréstimos</a></li>
                        <li><a href="#">Sobre nós</a></li>
                        <li><a href="#">Central de Ajuda</a></li>
                    </ul>
                </div>

                <!-- Coluna 4: Newsletter -->
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

        <!-- Rodapé Inferior (Direitos Autorais e Legal) -->
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
</body>
</html>