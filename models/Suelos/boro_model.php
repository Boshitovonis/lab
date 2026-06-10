<?php

require_once __DIR__ . '/../conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

// GUARDAR ANÁLISIS DE BORO
function guardarBoro($abs_blanco, $absorbancia, $ppm_b, $control) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO boro
        (abs_blanco, absorbancia, ppm_b, control)
        VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "dddd",
        $abs_blanco,
        $absorbancia,
        $ppm_b,
        $control
    );

    if ($stmt->execute()) {

        return [
            "exito" => true,
            "mensaje" => "Boro guardado correctamente.",
            "id_boro" => $conn->insert_id
        ];

    } else {

        return [
            "exito" => false,
            "mensaje" => "Error al guardar: " . $stmt->error
        ];
    }
}

// GUARDAR PUNTO DE CURVA
function guardarCurvaBoro($punto_curva, $absorbancia) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO curva_boro
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
// RELACIONAR BORO ↔ CURVA
function relacionarBoroCurva($id_boro, $id_curva) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO boro_curva
        (id_boro, id_curva_boro)
        VALUES (?, ?)"
    );

    $stmt->bind_param(
        "ii",
        $id_boro,
        $id_curva
    );

    return $stmt->execute();
}

function obtenerCurvaBoro($id_boro) {

    global $conn;

    $sql = "
        SELECT
            cb.punto_curva,
            cb.absorbancia
        FROM curva_boro cb

        INNER JOIN boro_curva bc
            ON cb.id_curva = bc.id_curva_boro

        WHERE bc.id_boro = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id_boro);

    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function obtenerHistorialBoro() {

    global $conn;

    $sql = "
        SELECT
            id,
            abs_blanco,
            absorbancia,
            ppm_b,
            control
        FROM boro

        ORDER BY id DESC
    ";

    $resultado = $conn->query($sql);

    return $resultado->fetch_all(MYSQLI_ASSOC);
}
?>