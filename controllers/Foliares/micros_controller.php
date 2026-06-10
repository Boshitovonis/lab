<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('foliares.micros');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Foliares/micros_model.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $peso    = (float) $_POST['peso'];
    $conc_cu = (float) $_POST['conc_cu'];
    $conc_zn = (float) $_POST['conc_zn'];
    $conc_fe = (float) $_POST['conc_fe'];
    $conc_mn = (float) $_POST['conc_mn'];
    $blk_cu  = (float) $_POST['blk_cu'];
    $blk_zn  = (float) $_POST['blk_zn'];
    $blk_fe  = (float) $_POST['blk_fe'];
    $blk_mn  = (float) $_POST['blk_mn'];
    $control = (float) $_POST['control'];


    //Cálculos a realizar
    $ppm_cu = (($conc_cu - $blk_cu)*50)/$peso ?? 0;
    $ppm_zn = (($conc_zn - $blk_zn)*50)/$peso ?? 0;
    $ppm_fe = (($conc_fe - $blk_fe)*50)/$peso ?? 0;
    $ppm_mn = (($conc_mn - $blk_mn)*50)/$peso ?? 0;

    $resultado = guardarMicros(
        $peso,
        $conc_cu, $conc_zn, $conc_fe, $conc_mn,
        $blk_cu,  $blk_zn,  $blk_fe,  $blk_mn,
        $ppm_cu,  $ppm_zn,  $ppm_fe,  $ppm_mn,  $control
    );

    $resultado['ppm_cu'] = $ppm_cu;
    $resultado['ppm_zn'] = $ppm_zn;
    $resultado['ppm_fe'] = $ppm_fe;
    $resultado['ppm_mn'] = $ppm_mn;

}
require_once __DIR__ . '/../../view/Foliares/micros_view.php';
?>
