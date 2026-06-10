<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('aguas.conductividad');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Aguas/conductividad_model.php';

$conexion = new Conexion();
$conn = $conexion->conectar();

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $lectura_conductividad         = (float) $_POST['lectura_conductividad'];
    $temperatura = (float) $_POST['temperatura'];

    // Cálculos
    $ce           = (($lectura_conductividad * 0.9985) / 1000);

    $resultado = guardarConductividad($lectura_conductividad, $temperatura, $ce);

    // Pasar valores calculados a la vista
    $resultado['ce'] = $ce;
}

require_once __DIR__ . '/../../view/Aguas/conductividad_view.php';
?>
