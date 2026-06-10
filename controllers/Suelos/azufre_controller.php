<?php

require_once __DIR__ . '/../../includes/auth.php';
lab_require_analysis_access('suelos.azufre');

require_once __DIR__ . '/../../models/Suelos/azufre_model.php';
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

    // CÁLCULO PPM s04
    $ppm_so4 = (
        ((($absorbancia*1) - $abs_blanco) * (25/10) * (100 + 1.408/100))
    );

    if ($ppm_so4 < 0) {
        $ppm_so4 = 0;
    }

    // GUARDAR Azufre
        $resultado = guardarAzufre(
        $abs_blanco,
        $absorbancia,
        $ppm_so4,
        $control
    );

    // Si se guardó correctamente
    if ($resultado['exito']) {

        $id_azufre = $resultado['id'];

        // CURVA DE CALIBRACIÓN
        if (
            isset($_POST['punto_curva']) &&
            isset($_POST['abs_curva'])
        ) {

            foreach ($_POST['punto_curva'] as $i => $punto) {

                $punto = (float) $punto;

                $abs_curva = (float) $_POST['abs_curva'][$i];

                // Guardar punto
                $id_curva = guardarCurvaAzufre(
                    $punto,
                    $abs_curva
                );

                // Relacionar
                if ($id_curva) {

                    relacionarAzufreCurva(
                        $id_azufre,
                        $id_curva
                    );
                }
            }
        }
    }

    $resultado['ppm_so4'] = $ppm_so4;
}

require_once __DIR__ . '/../../view/Suelos/azufre_view.php';

?>
