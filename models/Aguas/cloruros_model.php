<?php
require_once __DIR__ . '/../conexion.php';

function guardarCloruros($ml_muestra, $ml_agno3_blanco, $ml_agno3_muestra, $normalidad_agno3, 
$cloruros_mgl){
    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO cloruros_ag
            (ml_muestra, ml_agno3_blanco, ml_agno3_muestra, normalidad_agno3, cloruros_mgl)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("ddddd",
        $ml_muestra, $ml_agno3_blanco, $ml_agno3_muestra, $normalidad_agno3, $cloruros_mgl);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Cloruros guardados correctamente."];
    } elseif ($stmt->errno === 1062) {
        return ["exito" => false, "mensaje" => "Ya existe un registro con los mismos datos."];
    }else{
        return ["exito" => false, "mensaje" => "Error al guardar:" . $stmt->error];
    }
}
?>
