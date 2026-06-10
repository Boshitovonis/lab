<?php

require_once __DIR__ . '/../conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

// GUARDAR ANÁLISIS DE AZUFRE
function guardarAzufre($abs_blanco, $absorbancia, $ppm_so4, $control) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO azufre
        (abs_blanco, absorbancia, ppm_so4, control)
        VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "dddd",
        $abs_blanco,
        $absorbancia,
        $ppm_so4,
        $control
    );

    if ($stmt->execute()) {

        return [
            "exito" => true,
            "mensaje" => "Azufre guardado correctamente.",
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
function guardarCurvaAzufre($punto_curva, $absorbancia) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO curva_azufre
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
// RELACIONAR Azufre ↔ CURVA
function relacionarAzufreCurva($id_azufre, $id_curva) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO azufre_curva
        (id_azufre, id_curva_azufre)
        VALUES (?, ?)"
    );

    $stmt->bind_param(
        "ii",
        $id_azufre,
        $id_curva
    );

    return $stmt->execute();
}

function obtenerCurvaAzufre($id_azufre) {

    global $conn;

    $sql = "
        SELECT
            ca.punto_curva,
            ca.absorbancia
        FROM curva_azufre ca

        INNER JOIN azufre_curva ac
            ON ca.id_curva = ac.id_curva_azufre

        WHERE ac.id_azufre = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id_azufre);

    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function obtenerHistorialAzufre() {

    global $conn;

    $sql = "
        SELECT
            id,
            abs_blanco,
            absorbancia,
            ppm_so4,
            control
        FROM azufre

        ORDER BY id DESC
    ";

    $resultado = $conn->query($sql);

    return $resultado->fetch_all(MYSQLI_ASSOC);
}
?>