<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('aguas.tds');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Aguas/tds_model.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $lectura_tds = (float) $_POST['lectura_tds'];

    //Son los mismo datos en el excel que compartieron
    $tds_mgl = $lectura_tds;

    $resultado = guardarTDS($lectura_tds, $tds_mgl);

    $resultado['tds_mgl'] = $tds_mgl;
}

require_once __DIR__ . '/../../view/Aguas/tds_view.php';
?>
