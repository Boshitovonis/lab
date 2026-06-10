<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('aguas.alcanilidad');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Aguas/alcanilidad_model.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $ml_h2oso4 = (float) $_POST['ml_h2oso4'];

    //Cálculos a realizar
    $normalidad_h2oso4 = 0.02;
    $vol_muestra = 100; 

    $alcanilidad_mgl = ($ml_h2oso4 * $normalidad_h2oso4 * 50000)/$vol_muestra ?? 0;

    $resultado = guardarAlcanilidad($ml_h2oso4, $normalidad_h2oso4, $vol_muestra, $alcanilidad_mgl);

    $resultado['alcanilidad_mgl'] = $alcanilidad_mgl;
    
}

require_once __DIR__ . '/../../view/Aguas/alcanilidad_view.php';
?>
