<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('aguas.micros');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Aguas/micros_model.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $conc_cu = (float) $_POST['conc_cu'];
    $conc_zn = (float) $_POST['conc_zn'];
    $conc_fe = (float) $_POST['conc_fe'];
    $conc_mn = (float) $_POST['conc_mn'];
    $blk_cu  = (float) $_POST['blk_cu'];
    $blk_zn  = (float) $_POST['blk_zn'];
    $blk_fe  = (float) $_POST['blk_fe'];
    $blk_mn  = (float) $_POST['blk_mn'];



    //Cálculos a realizar
    $cu_mgl = ($conc_cu - $blk_cu) ?? 0;
    $zn_mgl = ($conc_zn - $blk_zn) ?? 0;
    $fe_mgl = ($conc_fe - $blk_fe) ?? 0;
    $mn_mgl = ($conc_mn - $blk_mn) ?? 0;

    $resultado = guardarMicros(
        $conc_cu, $conc_zn, $conc_fe, $conc_mn,
        $blk_cu,  $blk_zn,  $blk_fe,  $blk_mn,
        $cu_mgl,  $zn_mgl,  $fe_mgl,  $mn_mgl
    );

    $resultado['cu_mgl'] = $cu_mgl;
    $resultado['zn_mgl'] = $zn_mgl;
    $resultado['fe_mgl'] = $fe_mgl;
    $resultado['mn_mgl'] = $mn_mgl;
}

require_once __DIR__ . '/../../view/Aguas/micros_view.php';
?>
