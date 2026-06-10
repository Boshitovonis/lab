<?php
require_once __DIR__ . '/../conexion.php';

function guardarBrixPol($brix, $pol, $peso_torta,
    $pureza_jugo, $porcentaje_jugo, 
    $rendimiento_comercial_lbs, $rendimiento_comercial_kg,
    $rendimiento_real_lbs, $rendimiento_real_kg,
    $porcentaje_pol_cana){

    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO brixpol_ca
            (brix, pol, peso_torta,
            pureza_jugo, porcentaje_jugo, 
            rendimiento_comercial_lbs, rendimiento_comercial_kg,
            rendimiento_real_lbs, rendimiento_real_kg,
            porcentaje_pol_cana)
         VALUES (?, ?, ?, ?, ?, ?, ?, ? , ? ,?)"
    );

    $stmt->bind_param("dddddddddd",
        $brix, $pol, $peso_torta,
        $pureza_jugo, $porcentaje_jugo, 
        $rendimiento_comercial_lbs, $rendimiento_comercial_kg,
        $rendimiento_real_lbs, $rendimiento_real_kg,
        $porcentaje_pol_cana);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Brox y Pol guardados correctamente."];
    } elseif ($stmt->errno === 1062) {
        return ["exito" => false, "mensaje" => "Ya existe un registro con los mismos datos."];
    }else{
        return ["exito" => false, "mensaje" => "Error al guardar:" . $stmt->error];
    }
}
?>
