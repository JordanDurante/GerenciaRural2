<?php
class TelaPadrao {
    protected function cabecalho() {
        return '
        <!DOCTYPE html>
        <html lang="pt-BR" data-bs-theme="dark">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../../imagens/logo.jpeg" alt="Logo" width="40" height="30" class="d-inline-block align-text-top">
                Pagina Inicial
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#">Cadastros</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#">Financeiro</a>
                    </li>
                </ul>
            </div>
        </div>
        </nav>
        <head>
            <title>Título Padrão</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <link rel="stylesheet" type="text/css" href="../uteis/include/TelaPadrao.css">
        </head>
        <body>
            <div class="container-sm custom-container rounded-top-4 bg-secondary-subtle">';
    }

    protected function rodape() {
        return '
        <!-- Bootstrap JS -->
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        </body></html>';
    }

    public function renderizar($conteudo) {
        echo $this->cabecalho();
        echo $conteudo;
        echo $this->rodape();
    }
}
?>
