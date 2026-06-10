<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('suelos.nitrogeno');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Suelos/nitrogeno_model.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $peso       = (float) $_POST['peso'];
    $ml_blanco  = (float) $_POST['ml_blanco'];
    $ml_muestra = (float) $_POST['ml_muestra'];
    $normalidad = (float) $_POST['normalidad'];
    $x_nitrogeno = (float) $_POST['x_nitrogeno'];
    $control = (float) $_POST['control'];

    $porcentaje_nitro = $peso > 0
        ? (($ml_muestra - $ml_blanco) *0.0099779*1.408/$peso)
        : 0;

    $resultado = guardarNitrogeno($peso, $ml_blanco, $ml_muestra, $porcentaje_nitro, 
    $normalidad, $x_nitrogeno, $control);
    $resultado['porcentaje_nitro'] = $porcentaje_nitro;
}

require_once __DIR__ . '/../../view/Suelos/nitrogeno_view.php';
?>
