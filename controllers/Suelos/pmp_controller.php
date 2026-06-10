<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('suelos.pmp');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Suelos/pmp_model.php';

$conexion = new Conexion();
$conn = $conexion->conectar();

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $peso_caja         = (float) $_POST['peso_caja'];
    $peso_caja_mhumeda = (float) $_POST['peso_caja_mhumeda'];
    $peso_caja_mseca   = (float) $_POST['peso_caja_mseca'];
    $no_caja           = (string)$_POST['no_caja'];
    $control           = (float) $_POST['control'];

    // Cálculos
    $psh            = $peso_caja_mhumeda - $peso_caja;
    $pss            = $peso_caja_mseca   - $peso_caja;
    $porcentaje_pmp = ($pss != 0) ? (($psh - $pss) / $pss) * 100 : 0;

    $resultado = guardarPMP($peso_caja, $peso_caja_mhumeda, $peso_caja_mseca, $psh, $pss, $porcentaje_pmp,
    $no_caja, $control);

    // Pasar valores calculados a la vista
    $resultado['psh']            = $psh;
    $resultado['pss']            = $pss;
    $resultado['porcentaje_pmp'] = $porcentaje_pmp;
}

require_once __DIR__ . '/../../view/Suelos/pmp_view.php';
?>
