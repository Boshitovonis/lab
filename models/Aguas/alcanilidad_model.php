<?php
require_once __DIR__ . '/../conexion.php';

function guardarAlcanilidad($ml_h2oso4, $normalidad_h2oso4, $vol_muestra, $alcanilidad_mgl){
    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO alcanilidad_ag
            (ml_h2oso4, normalidad_h2oso4, vol_muestra, alcanilidad_mgl)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("dddd",
        $ml_h2oso4, $normalidad_h2oso4, $vol_muestra, $alcanilidad_mgl);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Alcanilidad guardada correctamente."];
    } elseif ($stmt->errno === 1062) {
        return ["exito" => false, "mensaje" => "Ya existe un registro con los mismos datos."];
    }else{
        return ["exito" => false, "mensaje" => "Error al guardar:" . $stmt->error];
    }
}
?>
