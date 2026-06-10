<?php
require_once __DIR__ . '/../conexion.php';

function guardarTDS($lectura_tds, $tds_mgl) {
    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO tds_ag (lectura_tds, tds_mgl)
         VALUES (?, ?)"
    );

    $stmt->bind_param("dd", $lectura_tds, $tds_mgl);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "TDS guardado correctamente."];
    } else {
        return ["exito" => false, "mensaje" => "Error al guardar: " . $stmt->error];
    }
}
?>