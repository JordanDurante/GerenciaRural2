<?php
require_once '../../uteis/TelaPadrao.php';

class TelaInicial extends TelaPadrao {
    public function conteudo() {
?>
<h1>Culturas</h1>

<?php
    }
}

$pagina = new TelaInicial();
$pagina->renderizar([$pagina, 'conteudo']);
?>
