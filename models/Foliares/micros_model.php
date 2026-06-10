<?php
require_once __DIR__ . '/../conexion.php';

function guardarMicros($peso, $conc_cu, $conc_zn, $conc_fe, $conc_mn,
                        $blk_cu, $blk_zn, $blk_fe, $blk_mn,
                        $ppm_cu, $ppm_zn, $ppm_fe, $ppm_mn, $control){
    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO micros_fo
            (peso, conc_cu, conc_zn, conc_fe, conc_mn,
             blk_cu, blk_zn, blk_fe, blk_mn,
             ppm_cu, ppm_zn, ppm_fe, ppm_mn, control)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("dddddddddddddd",
        $peso, $conc_cu, $conc_zn, $conc_fe, $conc_mn,
        $blk_cu,  $blk_zn,  $blk_fe,  $blk_mn,
        $ppm_cu,  $ppm_zn,  $ppm_fe,  $ppm_mn, $control
    );

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Micro Nutrientes guardados correctamente."];
    } elseif ($stmt->errno === 1062) {//Clave duplicada
        return ["exito" => false, "mensaje" => "Ya existe un registro con los mismos datos."];
    }else{
        return ["exito" => false, "mensaje" => "Error al guardar:" . $stmt->error];
    }
}
?>
