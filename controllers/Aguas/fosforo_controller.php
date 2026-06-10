<?php

require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('aguas.fosforo');

require_once __DIR__ . '/../../models/Aguas/fosforo_model.php';
require_once __DIR__ . '/../../models/conexion.php';

$conexion = new Conexion();
$conn = $conexion->conectar();

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');


    // DATOS PRINCIPALES
    $abs_blanco  = (float) $_POST['abs_blanco'];
    $absorbancia = (float) $_POST['absorbancia'];

    // CÁLCULO PPM_sol
    $ppm_sol = (
        (($absorbancia - $abs_blanco)/0.0312)
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

require_once __DIR__ . '/../../view/Aguas/fosforo_view.php';

?>
