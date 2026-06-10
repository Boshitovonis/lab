<?php

require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('foliares.fosforo');

require_once __DIR__ . '/../../models/Foliares/fosforo_model.php';
require_once __DIR__ . '/../../models/conexion.php';

$conexion = new Conexion();
$conn = $conexion->conectar();

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    lab_require_permission('laboratorio.analisis.crear');


    // DATOS PRINCIPALES
    $peso        = (float) $_POST['peso'];
    $abs_blanco  = (float) $_POST['abs_blanco'];
    $absorbancia = (float) $_POST['absorbancia'];
    $control     = (float) $_POST['control'];

    // CÁLCULO PPM_sol
    $ppm_p_sol = (
        (($absorbancia - $abs_blanco)/0.0329)
    );

    if ($ppm_p_sol < 0) {
        $ppm_p_sol = 0;
    };

    $porcentaje_p = (
        ($ppm_p_sol/$peso)
    );

    if  ($porcentaje_p < 0) {
        $porcentaje_p = 0;
    };

    // GUARDAR Fósforo
        $resultado = guardarFosforo(
        $peso,
        $abs_blanco,
        $absorbancia,
        $ppm_p_sol,
        $porcentaje_p,
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

    $resultado['ppm_p_sol'] = $ppm_p_sol;
    $resultado['porcentaje_p'] = $porcentaje_p;
}

require_once __DIR__ . '/../../view/Foliares/fosforo_view.php';

?>
