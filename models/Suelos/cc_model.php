<?php
require_once __DIR__ . '/../conexion.php';

function guardarCC($peso_caja, $peso_caja_mhumeda, $peso_caja_mseca, $psh, $pss, 
$porcentaje_cc, $no_caja, $control) {
    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO cc (peso_caja, peso_caja_mhumeda, peso_caja_mseca, psh, pss, porcentaje_cc,
        no_caja, control)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("dddddddd", 
    $peso_caja, $peso_caja_mhumeda, $peso_caja_mseca, $psh, $pss, $porcentaje_cc, $no_caja, $control);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Capacidad de Campo guardada correctamente."];
    } else {
        return ["exito" => false, "mensaje" => "Error al guardar: " . $stmt->error];
    }
}
?>
