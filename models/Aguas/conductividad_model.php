<?php
require_once __DIR__ . '/../conexion.php';

function guardarConductividad($lectura_conductividad, $temperatura, $ce) {
    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO conductividad_ag (lectura_conductividad, temperatura, ce)
         VALUES (?, ?, ?)"
    );

    $stmt->bind_param("ddd", 
    $lectura_conductividad, $temperatura, $ce);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Conductividad Eléctrica de agua guardada correctamente."];
    } else {
        return ["exito" => false, "mensaje" => "Error al guardar: " . $stmt->error];
    }
}
?>
