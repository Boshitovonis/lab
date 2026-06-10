<?php
require_once __DIR__ . '/includes/auth.php';

lab_require_module_access();

$canSolicitudes = lab_can_any([
    'laboratorio.solicitudes.ver',
    'laboratorio.solicitudes.crear',
    'laboratorio.solicitudes.editar',
]);
$canAnalisis = lab_can('laboratorio.analisis.ver');
$canConsolidacion = lab_can('laboratorio.consolidacion.ver');
$canLotes = lab_can('laboratorio.lotes.ver');
$canBlancoControl = lab_can('laboratorio.blanco_control.ver');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorio</title>

    <link rel="stylesheet" href="css/laboratorio.css?v=2">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar closed" id="sidebar">

    <!-- BOTON -->
    <div class="toggle-btn" id="toggleBtn">
        <i class="fas fa-bars"></i>
    </div>

    <!-- MENU -->
    <ul class="menu">

        <?php if (lab_can('laboratorio.usuarios.gestionar') && is_file(__DIR__ . '/usuarios.php')): ?>
            <li>
                <a href="usuarios.php">
                    <i class="fas fa-user"></i>
                    <span>Usuarios</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($canSolicitudes): ?>
            <li>
                <a href="view/menu_solicitud.php">
                    <i class="fas fa-file-signature"></i>
                    <span>Solicitud</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (is_file(__DIR__ . '/muestras.php') && lab_can('laboratorio.muestras.ver')): ?>
            <li>
                <a href="muestras.php">
                    <i class="fas fa-vial"></i>
                    <span>Muestras</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($canLotes): ?>
            <li>
                <a href="view/listar_lotes.php">
                    <i class="fas fa-box"></i>
                    <span>Lotes</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($canAnalisis || $canBlancoControl || $canConsolidacion): ?>
            <li>
                <a href="view/labc_index.php">
                    <i class="fas fa-flask-vial"></i>
                    <span>LABC</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($canConsolidacion): ?>
            <li>
                <a href="controllers/consolidacion_controller.php">
                    <i class="fas fa-eye"></i>
                    <span>Vista</span>
                </a>
            </li>
        <?php endif; ?>

    </ul>

    <!-- LOGOUT -->
    <a href="<?= htmlspecialchars(lab_logout_url(), ENT_QUOTES, 'UTF-8') ?>" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i>
        <span>Cerrar sesion</span>
    </a>

</div>

<!-- CONTENIDO -->
<div class="main-content" id="mainContent">

    <!-- LOGO -->
    <div class="background-logo">
        <img src="img/logo.png" alt="">
    </div>
  
    <!-- TITULO -->
    <h1 class="titulo-modulo">Laboratorio</h1>

    <!-- CARDS -->
    <div class="cards-container">

        <div class="info-card">

            <img src="img/gusano.png" alt="">

            <div class="card-body">

                <h2>Barrenador del tallo</h2>

                <ul>
                    <li>Es una de las plagas mas daninas de la cana de azucar. Las larvas perforan el tallo y forman tuneles internos, lo que reduce el transporte de agua y nutrientes. Puede disminuir el rendimiento y el contenido de azucar.</li>
                </ul>

            </div>

        </div>

        <div class="info-card">

            <img src="img/plaga.png" alt="">

            <div class="card-body">

                <h2>Salivazo o mosca pinta</h2>

                <ul>
                    <li>Este insecto chupa la savia de las hojas y produce una espuma parecida a saliva. Provoca amarillamiento, secado de hojas y menor crecimiento de la planta.</li>
                </ul>

            </div>

        </div>

        <div class="info-card">

            <img src="img/plaga.png" alt="">

            <div class="card-body">

                <h2>Picudo de la cana</h2>

                <ul>
                    <li>El picudo perfora los tallos y cepas de la cana. Los adultos son negros y tienen un pico largo curvo. Los danos favorecen la pudricion y debilitan la planta.</li>
                </ul>

            </div>

        </div>

    </div>

</div>

<script src="js/sidebar.js?v=2"></script>

</body>
</html>
