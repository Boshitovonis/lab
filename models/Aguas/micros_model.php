<?php
require_once __DIR__ . '/../conexion.php';

function guardarMicros( $conc_cu, $conc_zn, $conc_fe, $conc_mn,
                        $blk_cu, $blk_zn, $blk_fe, $blk_mn,
                        $cu_mgl, $zn_mgl, $fe_mgl, $mn_mgl){
    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO micros_ag
            (conc_cu, conc_zn, conc_fe, conc_mn,
             blk_cu, blk_zn, blk_fe, blk_mn,
             cu_mgl, zn_mgl, fe_mgl, mn_mgl)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("dddddddddddd",
        $conc_cu, $conc_zn, $conc_fe, $conc_mn,
        $blk_cu,  $blk_zn,  $blk_fe,  $blk_mn,
        $cu_mgl,  $zn_mgl,  $fe_mgl,  $mn_mgl
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
