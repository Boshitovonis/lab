<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('cana.humedad');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Cana/humedad_model.php';

$conexion = new Conexion();
$conn = $conexion->conectar();

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $no_bandeja   = (float) $_POST['no_bandeja'];
    $peso_bandeja = (float) $_POST['peso_bandeja'];
    $peso_muestra = (float) $_POST['peso_muestra'];
    $peso_bandeja_seca = (float) $_POST['peso_bandeja_seca'];

    $peso_bandeja_humedad = ($peso_bandeja + $peso_muestra) ?? 0;
    $porcentaje_humedad = ($peso_bandeja_humedad - $peso_bandeja_seca) *100 / $peso_muestra ?? 0;

    $resultado = guardarHumedad($no_bandeja, $peso_bandeja, $peso_muestra, $peso_bandeja_seca, 
    $peso_bandeja_humedad, $porcentaje_humedad);

    $resultado['peso_bandeja_humedad'] = $peso_bandeja_humedad;
    $resultado['porcentaje_humedad'] = $porcentaje_humedad;
}

require_once __DIR__ . '/../../view/Cana/humedad_view.php';
?>
