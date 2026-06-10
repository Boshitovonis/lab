<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('aguas.cloruros');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Aguas/cloruros_model.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $ml_muestra = (float) $_POST['ml_muestra'];
    $ml_agno3_blanco = (float) $_POST['ml_agno3_blanco'];
    $ml_agno3_muestra = (float) $_POST['ml_agno3_muestra'];

    //Cálculos a realizar
    $normalidad_agno3 = 0.0141;

    $cloruros_mgl = (($ml_agno3_muestra - $ml_agno3_blanco) * $normalidad_agno3 * 35450)/$ml_muestra ?? 0;

    $resultado = guardarCloruros($ml_muestra, $ml_agno3_blanco, $ml_agno3_muestra, $normalidad_agno3, 
    $cloruros_mgl);

    $resultado['cloruros_mgl'] = $cloruros_mgl;
    
}

require_once __DIR__ . '/../../view/Aguas/cloruros_view.php';
?>
