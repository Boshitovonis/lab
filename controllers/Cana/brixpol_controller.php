<?php
require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('cana.brixpol');

require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/../../models/Cana/brixpol_model.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');

    $brix = (float) $_POST['brix'];
    $pol = (float) $_POST['pol'];
    $peso_torta = (float) $_POST['peso_torta'];

    //Cálculos a realizar
    $pureza_jugo = (($pol/$brix) * 100) ?? 0;
    $porcentaje_jugo = ((500 - $peso_torta) * 100 / 500) ?? 0;

    $rendimiento_comercial_lbs = ($pol * 11.8) ?? 0;
    $rendimiento_comercial_kg = ($rendimiento_comercial_lbs) * 0.5 ?? 0;

    $rendimiento_real_lbs = ($pol * 16.44) ?? 0;
    $rendimiento_real_kg = ($rendimiento_real_lbs * 0.5) ?? 0;

    $porcentaje_pol_cana = ($rendimiento_real_lbs/20) ?? 0;

    $resultado = guardarBrixPol($brix, $pol, $peso_torta,
    $pureza_jugo, $porcentaje_jugo, 
    $rendimiento_comercial_lbs, $rendimiento_comercial_kg,
    $rendimiento_real_lbs, $rendimiento_real_kg,
    $porcentaje_pol_cana);

    $resultado['pureza_jugo'] = $pureza_jugo;
    $resultado['porcentaje_jugo'] = $porcentaje_jugo;
    $resultado['rendimiento_comercial_lbs'] = $rendimiento_comercial_lbs;
    $resultado['rendimiento_comercial_kg'] = $rendimiento_comercial_kg;
    $resultado['rendimiento_real_lbs'] = $rendimiento_real_lbs;
    $resultado['rendimiento_real_kg'] = $rendimiento_real_kg;
    $resultado['porcentaje_pol_cana'] = $porcentaje_pol_cana;
}

require_once __DIR__ . '/../../view/Cana/brixpol_view.php';
?>
