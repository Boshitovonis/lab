<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('aguas.bicarbonato');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Aguas/bicarbonato_model.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $ml_acl = (float) $_POST['ml_acl'];

    //Cálculos a realizar
    $ml_carbonatos = 0;
    $normalidad_h2oso4 = 0.02;
    $volumen_muestra = 100;

    $bicarbonatos_mgl = ($ml_acl-2*$ml_carbonatos)*$normalidad_h2oso4*50000/$volumen_muestra ?? 0;

    $resultado = guardarBicarbonato($ml_acl, $ml_carbonatos, $normalidad_h2oso4, $volumen_muestra, 
    $bicarbonatos_mgl);

    $resultado['bicarbonatos_mgl'] = $bicarbonatos_mgl;
    
}

require_once __DIR__ . '/../../view/Aguas/bicarbonato_view.php';
?>
