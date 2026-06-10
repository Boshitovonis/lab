<?php
require_once __DIR__ . '/../includes/auth.php';

lab_require_permission('laboratorio.solicitudes.crear');
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

            <a class="btn" href="solicitud_formulario.php?tipo=suelo-fisico">
                🌱 Suelos
            </a>

            <a class="btn" href="solicitud_formulario.php?tipo=foliares">
                🍃 Foliares
            </a>

            <a class="btn" href="solicitud_formulario.php?tipo=cana">
                🎋 Caña
            </a>

            <a class="btn" href="solicitud_formulario.php?tipo=miel">
                🍯 Miel
            </a>

            <a class="btn" href="solicitud_formulario.php?tipo=agua">
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
