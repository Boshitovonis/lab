<?php
require_once __DIR__ . '/../includes/auth.php';

lab_require_permission('laboratorio.lotes.ver');

require_once __DIR__ . '/../conexion.php';

$stmt = $conexion->prepare("
    SELECT *
    FROM lote
    ORDER BY id_lote DESC
");

$stmt->execute();

$lotes = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Listado de Lotes</title>

<link rel="stylesheet" href="../css/listar_lotes.css">

</head>

<body>

<nav>

    <div class="nav-brand">
        Laboratorio
    </div>

    <div class="nav-links">

        <!-- CAMBIAR URL -->
        <a href="../index.php" class="nav-link back">
            ← Regresar
        </a>

    </div>

</nav>

<main>

    <div class="doc-header">

        <div class="doc-header-left">

            <div>
                <div class="doc-title">
                    Listado de Lotes
                </div>

                <div class="doc-subtitle">
                    Consulta de lotes registrados
                </div>
            </div>

        </div>

    </div>

    <a href="../index.php" class="btn-regresar">
        ← Regresar
    </a>

    <div class="total-lotes">
        Total de lotes: <?= count($lotes) ?>
    </div>

    <div class="table-container">

        <table class="lotes-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código de Lote</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach($lotes as $lote): ?>

                <tr>
                    <td><?= $lote['id_lote'] ?></td>
                    <td><?= htmlspecialchars($lote['codigo_lote']) ?></td>
                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</main>

</body>
</html>
