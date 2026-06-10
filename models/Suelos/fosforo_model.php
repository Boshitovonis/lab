<?php

require_once __DIR__ . '/../conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

// GUARDAR ANÁLISIS DE FÓSFORO
function guardarFosforo($abs_blanco, $absorbancia, $ppm_sol, $ppm_p, $control) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO fosforo
        (abs_blanco, absorbancia, ppm_sol, ppm_p, control)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ddddd",
        $abs_blanco,
        $absorbancia,
        $ppm_sol,
        $ppm_p,
        $control
    );

    if ($stmt->execute()) {

        return [
            "exito" => true,
            "mensaje" => "Fósforo guardado correctamente.",
            "id" => $conn->insert_id
        ];

    } else {

        return [
            "exito" => false,
            "mensaje" => "Error al guardar: " . $stmt->error
        ];
    }
}

// GUARDAR PUNTO DE CURVA
function guardarCurvaFosforo($punto_curva, $absorbancia) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO curva_fosforo
        (punto_curva, absorbancia)
        VALUES (?, ?)"
    );

    $stmt->bind_param(
        "dd",
        $punto_curva,
        $absorbancia
    );

    if ($stmt->execute()) {

        return $conn->insert_id;

    } else {

        return false;
    }
}
// RELACIONAR Fósforo↔ CURVA
function relacionarFosforoCurva($id_fosforo, $id_curva) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO fosforo_curva
        (id_fosforo, id_curva_fosforo)
        VALUES (?, ?)"
    );

    $stmt->bind_param(
        "ii",
        $id_fosforo,
        $id_curva
    );

    return $stmt->execute();
}

function obtenerCurvaFosforo($id_fosforo) {

    global $conn;

    $sql = "
        SELECT
            cf.punto_curva,
            cf.absorbancia
        FROM curva_fosforo cf

        INNER JOIN fosforo_curva fcf
            ON cf.id_curva = fcf.id_curva_fosforo

        WHERE fcf.id_fosforo = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id_fosforo);

    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function obtenerHistorialFosforo() {

    global $conn;

    $sql = "
        SELECT
            id,
            abs_blanco,
            absorbancia,
            ppm_sol,
            ppm_p,
            control
        FROM fosforo

        ORDER BY id ASC
    ";

    $resultado = $conn->query($sql);

    return $resultado->fetch_all(MYSQLI_ASSOC);
}
?>