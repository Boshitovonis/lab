<?php
require_once __DIR__ . '/../includes/auth.php';

lab_require_permission('laboratorio.solicitudes.crear');

$loteSeleccionado = trim((string) ($_GET['lote'] ?? ''));

function menuSolicitudUrl(string $tipo, string $lote): string
{
    $url = 'solicitud_formulario.php?tipo=' . rawurlencode($tipo);
    if ($lote !== '') {
        $url .= '&lote=' . rawurlencode($lote);
    }
    return $url;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Seleccionar formulario — AgroLab</title>

    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="../css/menu_solicitud.css?v=2">
</head>

<body>

<div class="contenedor">

    <div class="card">

        <h1>Nuevo análisis</h1>

        <p class="subtitulo">
            Seleccione el tipo de muestra que desea registrar
        </p>

        <div class="btns">

            <a class="btn" href="<?= htmlspecialchars(menuSolicitudUrl('suelo-fisico', $loteSeleccionado), ENT_QUOTES, 'UTF-8') ?>">
                🌱 Suelos
            </a>

            <a class="btn" href="<?= htmlspecialchars(menuSolicitudUrl('foliares', $loteSeleccionado), ENT_QUOTES, 'UTF-8') ?>">
                🍃 Foliares
            </a>

            <a class="btn" href="<?= htmlspecialchars(menuSolicitudUrl('cana', $loteSeleccionado), ENT_QUOTES, 'UTF-8') ?>">
                🎋 Caña
            </a>

            <a class="btn" href="<?= htmlspecialchars(menuSolicitudUrl('miel', $loteSeleccionado), ENT_QUOTES, 'UTF-8') ?>">
                🍯 Miel
            </a>

            <a class="btn" href="<?= htmlspecialchars(menuSolicitudUrl('agua', $loteSeleccionado), ENT_QUOTES, 'UTF-8') ?>">
                💧 Agua
            </a>

        </div>

        <div class="note">
            Al elegir un tipo, el sistema abrirá automáticamente
            el formulario correspondiente con sus análisis y filtros configurados.
        </div>

        <a class="btn_volver" href="../index.php">
            ← Volver al inicio
        </a>

    </div>

</div>

</body>
</html>
