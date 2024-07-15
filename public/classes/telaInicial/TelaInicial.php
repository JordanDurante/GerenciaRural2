<?php
require_once '../../uteis/TelaPadrao.php';
error_reporting(1);

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login/login.php");
    exit;
}

class TelaInicial extends TelaPadrao {
    public function conteudo() {
?>
<h1>Tela Inicial</h1>
<div class="news-section">
    <h2>�ltimas Not�cias</h2>
    <div class="row" id="news-content">
        <!-- Not�cias ser�o carregadas aqui -->
    </div>
</div>
<div class="quotes-section mt-4">
    <h2>Cota��es de Produtos</h2>
    <div class="row" id="quotes-content">
        <!-- Cota��es ser�o carregadas aqui -->
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("../../uteis/obterDados/ObterDados.php?tipo=noticias")
            .then(response => response.json())
            .then(data => {
                const newsContent = document.getElementById("news-content");
                if (data.length === 0) {
                    newsContent.innerHTML = "<p>Nenhuma not�cia dispon�vel.</p>";
                } else {
                    data.forEach(noticia => {
                        const noticiaElement = document.createElement("div");
                        noticiaElement.className = "col-md-6 mb-4";
                        noticiaElement.innerHTML = `
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${noticia.titulo}</h5>
                                    <p class="card-text">${noticia.conteudo}</p>
                                </div>
                            </div>
                        `;
                        newsContent.appendChild(noticiaElement);
                    });
                }
            })
            .catch(error => console.error('Erro ao carregar not�cias:', error));

        fetch("../../uteis/obterDados/ObterDados.php?tipo=cotacoes")
            .then(response => response.json())
            .then(data => {
                const quotesContent = document.getElementById("quotes-content");
                if (data.length === 0) {
                    quotesContent.innerHTML = "<p>Nenhuma cota��o dispon�vel.</p>";
                } else {
                    const cotacoesElement = document.createElement("div");
                    cotacoesElement.className = "col-12";
                    cotacoesElement.innerHTML = data.map(cotacao => `
                        <span>${cotacao.produto}: ${cotacao.preco}</span>
                    `).join(' | ');
                    quotesContent.appendChild(cotacoesElement);
                }
            })
            .catch(error => console.error('Erro ao carregar cota��es:', error));
    });
</script>
<?php
    }
}

$pagina = new TelaInicial();
$pagina->renderizar([$pagina, 'conteudo']);
?>
