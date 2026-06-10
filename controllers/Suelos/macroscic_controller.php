<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('suelos.macroscic');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Suelos/macroscic_model.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $peso    = (float) $_POST['peso'] ?? 0;
    $ppm_ca  = (float) $_POST['ppm_ca'] ?? 0;
    $ppm_mg  = (float) $_POST['ppm_mg'] ?? 0;
    $ppm_k   = (float) $_POST['ppm_k'] ?? 0;
    $ppm_na  = (float) $_POST['ppm_na'] ?? 0;
    $blk_ca  = (float) $_POST['blk_ca'] ?? 0;
    $blk_mg  = (float) $_POST['blk_mg'] ?? 0;
    $blk_k  = (float) $_POST['blk_k'] ?? 0;
    $blk_na  = (float) $_POST['blk_na'] ?? 0;
    $control = (float) $_POST['control'] ?? 0;
    $cic_muestra = (float) ($_POST['cic_muestra'] ?? 0);

    //Cálculos a realizar
    $cic_blanco = 0.1;

    $meq_ca = (($ppm_ca - $blk_ca)*4.99)/$peso ?? 0;
    $meq_mg = (($ppm_mg - $blk_mg)*8.2264)/$peso ?? 0;
    $meq_k  = (($ppm_k  - $blk_k )*0.2557)/$peso ?? 0;
    $meq_na = (($ppm_na - $blk_na)*0.4348)/$peso ?? 0;
    $cic_meq = (($cic_muestra - $cic_blanco)*0.0298039*1000)/$peso ?? 0;

    $resultado = guardarMacroscic(
        $peso, $ppm_ca, $ppm_mg, $ppm_k, $ppm_na,
        $blk_ca, $blk_mg, $blk_k, $blk_na,
        $meq_ca, $meq_mg, $meq_k, $meq_na, $control,
        $cic_blanco, $cic_muestra, $cic_meq
    );

    $resultado['meq_ca'] = $ppm_ca;
    $resultado['meq_mg'] = $meq_mg;
    $resultado['meq_k'] = $meq_k;
    $resultado['meq_na'] = $meq_na;
    $resultado['cic_meq'] = $cic_meq;
}

require_once __DIR__ . '/../../view/Suelos/macroscic_view.php';
?>
