<?php
require_once '../../uteis/TelaPadrao.php';
require_once '../../../config/bd_connection.php'; 
error_reporting(1);

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login/login.php");
    exit;
}

class TelaGestaoFinanceira extends TelaPadrao {
    private $conn;

    public function __construct() {
        global $conn; 
        $this->conn = $conn;
    }

    public function conteudo() {
        $data_inicio_default = date('Y-m-01');
        $data_fim_default = date('Y-m-d');
        ?>
        <div class="container">
            <h2>Gestão Financeira</h2>
            <form id="filtro-form">
                <div class="row mb-4 align-items-end">
                    <div class="col">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo $data_inicio_default; ?>">
                    </div>
                    <div class="col">
                        <label for="data_fim" class="form-label">Data Fim</label>
                        <input type="date" id="data_fim" name="data_fim" class="form-control" value="<?php echo $data_fim_default; ?>">
                    </div>
                    <div class="col">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select id="tipo" name="tipo" class="form-select">
                            <option value="">Todos</option>
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saída</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="cultura" class="form-label">Cultura</label>
                        <select id="cultura" name="cultura" class="form-select">
                            <option value="">Todas</option>
                            <?php
                            $stmt = $this->conn->prepare("SELECT id, nome FROM cultura");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value=\"{$row['id']}\">{$row['nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary mt-2 mt-md-0" onclick="aplicarFiltro()">Filtrar</button>
                    </div>
                </div>
            </form>

            <div id="tabela-resultados">
                <?php $this->mostrar($data_inicio_default, $data_fim_default); ?>
            </div>
        </div>

        <script>
            function aplicarFiltro() {
                const dataInicio = $('#data_inicio').val();
                const dataFim = $('#data_fim').val();
                const tipo = $('#tipo').val();
                const cultura = $('#cultura').val();

                $.ajax({
                    url: 'TelaGestaoFinanceira.php',
                    type: 'GET',
                    data: {
                        data_inicio: dataInicio,
                        data_fim: dataFim,
                        tipo: tipo,
                        cultura: cultura
                    },
                    success: function(response) {
                        const parsedResponse = $('<div>').html(response);
                        const tableContent = parsedResponse.find('#tabela-resultados').html();
                        $('#tabela-resultados').html(tableContent);
                    },
                    error: function() {
                        alert('Erro ao carregar os dados.');
                    }
                });
            }
        </script>
    <?
    }

    public function mostrar($data_inicio_default = null, $data_fim_default = null) {
        $data_inicio = $_GET['data_inicio'] ? $_GET['data_inicio'] : $data_inicio_default;
        $data_fim = $_GET['data_fim'] ? $_GET['data_fim'] : $data_fim_default;

        $saldo_anterior = 0;
        $query_saldo_anterior = "SELECT SUM(lf.valor) as saldo
                                    FROM lancamento_financeiro lf
                                    WHERE lf.id_propriedade = {$_SESSION['id_propriedade']}";

        if (!empty($data_inicio)) {
            $query_saldo_anterior .= " AND lf.data < '$data_inicio'";
        }
        $result_saldo_anterior = $this->conn->query($query_saldo_anterior);
        if ($row = $result_saldo_anterior->fetch_assoc()) {
            $saldo_anterior = $row['saldo'];
        }

        $query = "SELECT lf.id, lf.tipo, DATE_FORMAT(lf.data, '%d/%m/%Y') AS data_formatada, lf.valor, lf.quantidade, u.nome AS unidade, c.nome AS cultura
                    FROM lancamento_financeiro lf
                    LEFT JOIN unidade u ON lf.id_unidade = u.id
                    LEFT JOIN cultura c ON lf.id_cultura = c.id
                    WHERE lf.id_propriedade = {$_SESSION['id_propriedade']}";

        if (!empty($data_inicio)) {
            $query .= " AND lf.data >= '$data_inicio'";
        }
        if (!empty($data_fim)) {
            $query .= " AND lf.data <= '$data_fim'";
        }
        if (!empty($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
            $query .= " AND lf.tipo = '$tipo'";
        }
        if (!empty($_GET['cultura'])) {
            $cultura = $_GET['cultura'];
            $query .= " AND lf.id_cultura = $cultura";
        }

        $result = $this->conn->query($query);
        $saldo_periodo = 0;
        echo '<table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>Valor (R$)</th>
                        <th>Quantidade</th>
                        <th>Unidade</th>
                        <th>Cultura</th>
                    </tr>
                </thead>
                <tbody>';
        
        echo "<tr>
                <td colspan='3'><strong>Saldo Anterior</strong></td>
                <td colspan='4'><strong>" . number_format($saldo_anterior, 2, ',', '.') . "</strong></td>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            $saldo_periodo += $row['valor'];
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['tipo']}</td>
                    <td>{$row['data_formatada']}</td>
                    <td>" . number_format($row['valor'], 2, ',', '.') . "</td>
                    <td>{$row['quantidade']}</td>
                    <td>{$row['unidade']}</td>
                    <td>{$row['cultura']}</td>
                    </tr>";
        }
        
        $saldo_final = $saldo_anterior + $saldo_periodo;

        echo "<tr>
                <td colspan='3'><strong>Saldo Final</strong></td>
                <td colspan='4'><strong>" . number_format($saldo_final, 2, ',', '.') . "</strong></td>
                </tr>";

        echo '</tbody>
                </table>';

        $this->conn->close();
    }
}

$page = new TelaGestaoFinanceira();
$page->renderizar([$page, 'conteudo']);
?>
