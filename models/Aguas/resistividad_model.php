<?php
require_once __DIR__ . '/../conexion.php';

function guardarResistividad($lectura_resistividad) {
    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO resistividad_ag (lectura_resistividad)
        VALUES (?)"
    );

    $stmt->bind_param("d", $lectura_resistividad);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Resistividad guardada correctamente."];
    } else {
        return ["exito" => false, "mensaje" => "Error al guardar: " . $stmt->error];
    }
}
?>
