<?php
require_once __DIR__ . '/../conexion.php';

function guardarHumedad($no_bandeja, $peso_bandeja, $peso_muestra, $peso_bandeja_seca,
        $peso_bandeja_humedad, $porcentaje_humedad) {
    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO humedad_ca (no_bandeja, peso_bandeja, peso_muestra, peso_bandeja_seca, 
        peso_bandeja_humedad, porcentaje_humedad)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("dddddd", 
    $no_bandeja, $peso_bandeja, $peso_muestra, $peso_bandeja_seca,
    $peso_bandeja_humedad, $porcentaje_humedad);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "El porcentaje de humedad se guardó correctamente."];
    } else {
        return ["exito" => false, "mensaje" => "Error al guardar: " . $stmt->error];
    }
}
?>
