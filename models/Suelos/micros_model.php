<?php
require_once __DIR__ . '/../conexion.php';

function guardarMicros($peso, $conc_cu, $conc_zn, $conc_fe, $conc_mn, $conc_k,
                        $blk_cu, $blk_zn, $blk_fe, $blk_mn, $blk_k,
                        $ppm_cu, $ppm_zn, $ppm_fe, $ppm_mn, $ppm_k, $control){
    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO micros
            (peso, conc_cu, conc_zn, conc_fe, conc_mn, conc_k,
             blk_cu, blk_zn, blk_fe, blk_mn, blk_k,
             ppm_cu, ppm_zn, ppm_fe, ppm_mn, ppm_k, control)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("ddddddddddddddddd",
        $peso, $conc_cu, $conc_zn, $conc_fe, $conc_mn, $conc_k,
        $blk_cu,  $blk_zn,  $blk_fe,  $blk_mn,  $blk_k,
        $ppm_cu,  $ppm_zn,  $ppm_fe,  $ppm_mn,  $ppm_k, $control
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
