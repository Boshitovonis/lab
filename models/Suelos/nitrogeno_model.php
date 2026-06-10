<?php
require_once __DIR__ . '/../conexion.php';

function guardarNitrogeno($peso, $ml_blanco, $ml_muestra, $porcentaje_nitro, $normalidad, $x_nitrogeno,
$control) {
    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO nitrogeno (peso, ml_blanco, ml_muestra, porcentaje_nitro, normalidad, x_nitrogeno,
        control)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("ddddddd", $peso, $ml_blanco, $ml_muestra, $porcentaje_nitro, $normalidad, 
    $x_nitrogeno, $control);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Porcentaje de nitrogeno guardado correctamente."];
    } else {
        return ["exito" => false, "mensaje" => "Error al guardar: " . $stmt->error];
    }
}
?>