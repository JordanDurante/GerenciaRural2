<?php
require_once '../uteis/TelaPadrao.php';

class TelaInicial extends TelaPadrao {
    public function conteudo() {
        return '<h1>Tela Inicial</h1>';
    }
}

$pagina = new TelaInicial();
$pagina->renderizar($pagina->conteudo());

?>
