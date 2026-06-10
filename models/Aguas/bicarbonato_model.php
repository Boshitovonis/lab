<?php
require_once __DIR__ . '/../conexion.php';

function guardarBicarbonato($ml_acl, $ml_carbonatos, $normalidad_h2oso4, $volumen_muestra, 
$bicarbonatos_mgl){
    $conn = (new Conexion())->conectar();

    $stmt = $conn->prepare(
        "INSERT INTO bicarbonato_ag
            (ml_acl, ml_carbonatos, normalidad_h2oso4, volumen_muestra, bicarbonatos_mgl)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("ddddd",
        $ml_acl, $ml_carbonatos, $normalidad_h2oso4, $volumen_muestra, $bicarbonatos_mgl);

    if ($stmt->execute()) {
        return ["exito" => true, "mensaje" => "Cloruros guardados correctamente."];
    } elseif ($stmt->errno === 1062) {
        return ["exito" => false, "mensaje" => "Ya existe un registro con los mismos datos."];
    }else{
        return ["exito" => false, "mensaje" => "Error al guardar:" . $stmt->error];
    }
}
?>
