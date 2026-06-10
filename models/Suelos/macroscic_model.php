<?php
require_once __DIR__ . '/../conexion.php';

function guardarMacroscic($peso, $ppm_ca, $ppm_mg, $ppm_k, $ppm_na,
                            $blk_ca, $blk_mg, $blk_k, $blk_na,
                            $meq_ca, $meq_mg, $meq_k, $meq_na, $control,
                            $cic_blanco, $cic_muestra, $cic_meq){
    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO macroscic
            (peso, ppm_ca, ppm_mg, ppm_k, ppm_na,
            blk_ca, blk_mg, blk_k, blk_na,
            meq_ca, meq_mg, meq_k, meq_na, control,
            cic_blanco, cic_muestra, cic_meq )
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("ddddddddddddddddd",
        $peso, $ppm_ca, $ppm_mg, $ppm_k, $ppm_na,
        $blk_ca, $blk_mg, $blk_k, $blk_na,
        $meq_ca, $meq_mg, $meq_k, $meq_na, $control,
        $cic_blanco, $cic_muestra, $cic_meq
    );

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Macro Nutrientes y Cic guardados correctamente."];
    } elseif ($stmt->errno === 1062) {
        return ["exito" => false, "mensaje" => "Ya existe un registro con los mismos datos."];
    }else{
        return ["exito" => false, "mensaje" => "Error al guardar:" . $stmt->error];
    }
}
?>
