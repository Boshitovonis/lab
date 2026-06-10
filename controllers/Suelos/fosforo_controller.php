<?php

require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('suelos.fosforo');

require_once __DIR__ . '/../../models/Suelos/fosforo_model.php';
require_once __DIR__ . '/../../models/conexion.php';

$conexion = new Conexion();
$conn = $conexion->conectar();

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');


    // DATOS PRINCIPALES
    $abs_blanco  = (float) $_POST['abs_blanco'];
    $absorbancia = (float) $_POST['absorbancia'];
    $control     = (float) $_POST['control'];

    // CÁLCULO PPM_sol
    $ppm_sol = (
        (($absorbancia - $abs_blanco)/0.0481)
    );

    if ($ppm_sol < 0) {
        $ppm_sol = 0;
    }

    $ppm_p = $ppm_sol * 5;

    // GUARDAR Fósforo
        $resultado = guardarFosforo(
        $abs_blanco,
        $absorbancia,
        $ppm_sol,
        $ppm_p,
        $control
    );

    // Si se guardó correctamente
    if ($resultado['exito']) {

        $id_fosforo = $resultado['id'];

        // CURVA DE CALIBRACIÓN
        if (
            isset($_POST['punto_curva']) &&
            isset($_POST['abs_curva'])
        ) {

            foreach ($_POST['punto_curva'] as $i => $punto) {

                $punto = (float) $punto;

                $abs_curva = (float) $_POST['abs_curva'][$i];

                // Guardar punto
                $id_curva = guardarCurvaFosforo(
                    $punto,
                    $abs_curva
                );

                // Relacionar
                if ($id_curva) {

                    relacionarFosforoCurva(
                        $id_fosforo,
                        $id_curva
                    );
                }
            }
        }
    }

    $resultado['ppm_sol'] = $ppm_sol;
    $resultado['ppm_p'] = $ppm_p;
}

require_once __DIR__ . '/../../view/Suelos/fosforo_view.php';

?>
