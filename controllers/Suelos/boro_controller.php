<?php

require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('suelos.boro');

require_once __DIR__ . '/../../models/Suelos/boro_model.php';
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

    // CÁLCULO PPM B
    $ppm_b = (
        (($absorbancia - $abs_blanco) * 40 * (100 + 1.408))
        / (20 * 100)
    );

    if ($ppm_b < 0) {
        $ppm_b = 0;
    }

    // GUARDAR BORO
    $resultado = guardarBoro(
        $abs_blanco,
        $absorbancia,
        $ppm_b,
        $control
    );

    // Si se guardó correctamente
    if ($resultado['exito']) {

        $id_boro = $resultado['id_boro'];

        // CURVA DE CALIBRACIÓN
        if (
            isset($_POST['punto_curva']) &&
            isset($_POST['abs_curva'])
        ) {

            foreach ($_POST['punto_curva'] as $i => $punto) {

                $punto = (float) $punto;

                $abs_curva = (float) $_POST['abs_curva'][$i];

                // Guardar punto
                $id_curva = guardarCurvaBoro(
                    $punto,
                    $abs_curva
                );

                // Relacionar
                if ($id_curva) {

                    relacionarBoroCurva(
                        $id_boro,
                        $id_curva
                    );
                }
            }
        }
    }

    $resultado['ppm_b'] = $ppm_b;
}

require_once __DIR__ . '/../../view/Suelos/boro_view.php';

?>
