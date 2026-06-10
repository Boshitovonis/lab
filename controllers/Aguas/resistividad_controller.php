<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('aguas.resistividad');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Aguas/resistividad_model.php';

$conexion = new Conexion();
$conn = $conexion->conectar();

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $lectura_resistividad = (float) $_POST['lectura_resistividad'];

    $resultado = guardarResistividad($lectura_resistividad);

}

require_once __DIR__ . '/../../view/Aguas/resistividad_view.php';
?>
