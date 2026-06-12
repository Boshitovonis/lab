<?php
$lote_actual = trim((string) ($_POST['lote'] ?? $_GET['lote'] ?? $lote_actual ?? ''));

if (!function_exists('labFooterE')) {
    function labFooterE($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('labFooterLower')) {
    function labFooterLower(string $value): string
    {
        return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
    }
}

if (!function_exists('labFooterContextoAnalisis')) {
    function labFooterContextoAnalisis(): ?array
    {
        $script = strtolower(str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '')));

        $mapa = [
            'suelos/cc_controller.php' => [
                'tipos' => ['suelos', 'suelo'],
                'analisis' => ['Capacidad de Campo', 'Capacidad campo'],
                'label' => 'Capacidad de Campo',
            ],
            'suelos/pmp_controller.php' => [
                'tipos' => ['suelos', 'suelo'],
                'analisis' => ['Punto de Marchitez Permanente', 'Marchitez Permanente', 'PMP'],
                'label' => 'Punto de Marchitez Permanente',
            ],
            'suelos/macroscic_controller.php' => [
                'tipos' => ['suelos', 'suelo'],
                'analisis' => ['Macronutrientes y CIC', 'Macronutrientes', 'CIC'],
                'label' => 'Macronutrientes y CIC',
            ],
            'suelos/micros_controller.php' => [
                'tipos' => ['suelos', 'suelo'],
                'analisis' => ['Micro Nutrientes', 'Micronutrientes', 'Cu, Zn, Fe, Mn, K'],
                'label' => 'Micro Nutrientes de Suelos',
            ],
            'suelos/nitrogeno_controller.php' => [
                'tipos' => ['suelos', 'suelo'],
                'analisis' => ['Nitrógeno', 'Nitrogeno', 'Nitrógeno total', 'Nitrogeno total'],
                'label' => 'Nitrogeno de Suelos',
            ],
            'suelos/boro_controller.php' => [
                'tipos' => ['suelos', 'suelo'],
                'analisis' => ['Boro'],
                'label' => 'Boro de Suelos',
            ],
            'suelos/azufre_controller.php' => [
                'tipos' => ['suelos', 'suelo'],
                'analisis' => ['Azufre', 'SO4'],
                'label' => 'Azufre de Suelos',
            ],
            'suelos/fosforo_controller.php' => [
                'tipos' => ['suelos', 'suelo'],
                'analisis' => ['Fósforo', 'Fosforo', 'Fósforo disponible', 'Fosforo disponible'],
                'label' => 'Fosforo de Suelos',
            ],
            'foliares/micros_controller.php' => [
                'tipos' => ['foliares', 'foliar'],
                'analisis' => ['Micro Nutrientes', 'Micronutrientes', 'Cu, Zn, Fe, Mn, K'],
                'label' => 'Micro Nutrientes Foliares',
            ],
            'foliares/fosforo_controller.php' => [
                'tipos' => ['foliares', 'foliar'],
                'analisis' => ['Fósforo', 'Fosforo', 'Fósforo foliar', 'Fosforo foliar'],
                'label' => 'Fosforo Foliar',
            ],
            'aguas/micros_controller.php' => [
                'tipos' => ['agua', 'aguas'],
                'analisis' => ['Micro Nutrientes', 'Micronutrientes', 'Cu, Zn, Fe, Mn'],
                'label' => 'Micro Nutrientes de Aguas',
            ],
            'aguas/fosforo_controller.php' => [
                'tipos' => ['agua', 'aguas'],
                'analisis' => ['Fósforo', 'Fosforo'],
                'label' => 'Fosforo de Aguas',
            ],
            'aguas/conductividad_controller.php' => [
                'tipos' => ['agua', 'aguas'],
                'analisis' => ['Conductividad Eléctrica', 'Conductividad Electrica', 'CE'],
                'label' => 'Conductividad Electrica',
            ],
            'aguas/tds_controller.php' => [
                'tipos' => ['agua', 'aguas'],
                'analisis' => ['TDS', 'Sólidos totales disueltos', 'Solidos totales disueltos', 'STD'],
                'label' => 'TDS',
            ],
            'aguas/resistividad_controller.php' => [
                'tipos' => ['agua', 'aguas'],
                'analisis' => ['Resistividad'],
                'label' => 'Resistividad',
            ],
            'aguas/cloruros_controller.php' => [
                'tipos' => ['agua', 'aguas'],
                'analisis' => ['Cloruros'],
                'label' => 'Cloruros',
            ],
            'aguas/alcanilidad_controller.php' => [
                'tipos' => ['agua', 'aguas'],
                'analisis' => ['Alcalinidad', 'Alcanilidad'],
                'label' => 'Alcalinidad',
            ],
            'aguas/bicarbonato_controller.php' => [
                'tipos' => ['agua', 'aguas'],
                'analisis' => ['Bicarbonatos', 'Bicarbonato'],
                'label' => 'Bicarbonatos',
            ],
            'cana/humedad_controller.php' => [
                'tipos' => ['cañas', 'caña', 'canas', 'cana'],
                'analisis' => ['% de Humedad', 'Humedad'],
                'label' => '% de Humedad en Caña',
            ],
            'cana/brixpol_controller.php' => [
                'tipos' => ['cañas', 'caña', 'canas', 'cana'],
                'analisis' => ['Determinación de Brix y Pol', 'Determinacion de Brix y Pol', 'Brix', 'Pol'],
                'label' => 'Brix y Pol',
            ],
        ];

        foreach ($mapa as $needle => $contexto) {
            if (strpos($script, $needle) !== false) {
                return $contexto;
            }
        }

        return null;
    }
}

if (!function_exists('labFooterConexion')) {
    function labFooterConexion(): ?PDO
    {
        global $conn, $conexion;

        if ($conn instanceof PDO) {
            return $conn;
        }

        if ($conexion instanceof PDO) {
            return $conexion;
        }

        if (class_exists('Conexion')) {
            $pdo = Conexion::conectar();
            return $pdo instanceof PDO ? $pdo : null;
        }

        $conexionPath = __DIR__ . '/../models/conexion.php';
        if (file_exists($conexionPath)) {
            require_once $conexionPath;
            if (class_exists('Conexion')) {
                $pdo = Conexion::conectar();
                return $pdo instanceof PDO ? $pdo : null;
            }
        }

        return null;
    }
}

if (!function_exists('labFooterCondiciones')) {
    function labFooterCondiciones(array $contexto, array &$params): array
    {
        $tipoParts = [];
        foreach ($contexto['tipos'] as $tipo) {
            $tipoParts[] = 'LOWER(tm.nombre) = ?';
            $params[] = labFooterLower($tipo);
        }

        $analisisParts = [];
        foreach ($contexto['analisis'] as $analisis) {
            $analisisLower = labFooterLower($analisis);
            $analisisParts[] = 'LOWER(ta.nombre) = ?';
            $params[] = $analisisLower;
            $analisisParts[] = 'LOWER(ta.nombre) LIKE ?';
            $params[] = '%' . $analisisLower . '%';
        }

        return [
            'tipo' => '(' . implode(' OR ', $tipoParts) . ')',
            'analisis' => '(' . implode(' OR ', $analisisParts) . ')',
        ];
    }
}

if (!function_exists('labFooterLotesPorAnalisis')) {
    function labFooterLotesPorAnalisis(?array $contexto): array
    {
        if (!$contexto) {
            return [];
        }

        try {
            $pdo = labFooterConexion();
            if (!$pdo) {
                return [];
            }

            $params = [];
            $condiciones = labFooterCondiciones($contexto, $params);
            $stmt = $pdo->prepare("
                SELECT DISTINCT l.codigo_lote
                  FROM lote l
                  INNER JOIN solicitud s ON s.id_lote = l.id_lote
                  INNER JOIN tipo_muestra tm ON tm.id_tipo = s.id_tipo
                  INNER JOIN solicitud_analisis sa ON sa.id_solicitud = s.id_solicitud
                  INNER JOIN tipo_analisis ta ON ta.id_tipo = sa.id_tipo_analisis
                 WHERE l.codigo_lote IS NOT NULL
                   AND l.codigo_lote <> ''
                   AND {$condiciones['tipo']}
                   AND {$condiciones['analisis']}
                 ORDER BY l.codigo_lote
            ");
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }
}

if (!function_exists('labFooterDestinoFormulario')) {
    function labFooterDestinoFormulario(?array $contexto, string $codigoLote): ?array
    {
        if (!$contexto || $codigoLote === '') {
            return null;
        }

        try {
            $pdo = labFooterConexion();
            if (!$pdo) {
                return null;
            }

            $params = [$codigoLote];
            $condiciones = labFooterCondiciones($contexto, $params);
            $stmt = $pdo->prepare("
                SELECT lr.id_rango, ta.id_tipo AS id_tipo_analisis
                  FROM lote l
                  INNER JOIN solicitud s ON s.id_lote = l.id_lote
                  INNER JOIN tipo_muestra tm ON tm.id_tipo = s.id_tipo
                  INNER JOIN solicitud_analisis sa ON sa.id_solicitud = s.id_solicitud
                  INNER JOIN tipo_analisis ta ON ta.id_tipo = sa.id_tipo_analisis
                  LEFT JOIN lote_rango lr ON lr.id_lote = l.id_lote
                 WHERE l.codigo_lote = ?
                   AND {$condiciones['tipo']}
                   AND {$condiciones['analisis']}
                 ORDER BY s.id_solicitud DESC, lr.id_rango DESC
                 LIMIT 1
            ");
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('labFooterGuardarFormularioBase')) {
    function labFooterGuardarFormularioBase(?array $contexto, string $codigoLote, string $fecha, string $analista, string $metodo, string $observaciones): array
    {
        if (!$contexto) {
            return ['ok' => false, 'message' => 'No se pudo identificar el analisis actual.'];
        }

        if ($codigoLote === '' || $fecha === '' || $analista === '') {
            return ['ok' => false, 'message' => 'Complete lote, fecha y analista para guardar el registro.'];
        }

        try {
            $pdo = labFooterConexion();
            if (!$pdo) {
                return ['ok' => false, 'message' => 'No se pudo conectar a la base de datos.'];
            }

            $destino = labFooterDestinoFormulario($contexto, $codigoLote);
            if (!$destino) {
                return ['ok' => false, 'message' => 'El lote seleccionado no corresponde a este analisis.'];
            }

            $stmt = $pdo->prepare("
                INSERT INTO formulario (id_estado, id_rango, id_tipo_analisis, fecha, analista)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                null,
                $destino['id_rango'] !== null ? (int) $destino['id_rango'] : null,
                (int) $destino['id_tipo_analisis'],
                $fecha,
                $analista,
            ]);

            $idFormulario = (int) $pdo->lastInsertId();
            $comentarios = [];
            if ($metodo !== '') {
                $comentarios[] = 'Metodo: ' . $metodo;
            }
            if ($observaciones !== '') {
                $comentarios[] = 'Observaciones: ' . $observaciones;
            }
            $comentarioFinal = implode("\n", $comentarios);

            if ($idFormulario > 0 && $comentarioFinal !== '') {
                $historial = $pdo->prepare("
                    INSERT INTO historial_formulario (id_formulario, accion, estado_anterior, estado_nuevo, usuario, fecha, comentario)
                    VALUES (?, ?, ?, ?, ?, NOW(), ?)
                ");
                $historial->execute([
                    $idFormulario,
                    'Registro creado',
                    null,
                    null,
                    $analista,
                    $comentarioFinal,
                ]);
            }

            return ['ok' => true, 'message' => 'Registro base del formulario guardado correctamente.'];
        } catch (Throwable $e) {
            return ['ok' => false, 'message' => 'No se pudo guardar el registro base: ' . $e->getMessage()];
        }
    }
}

$labFooterContexto = labFooterContextoAnalisis();
$labFooterLotes = labFooterLotesPorAnalisis($labFooterContexto);
$fecha_actual_footer = trim((string) ($_POST['fecha'] ?? date('Y-m-d')));
$analista_actual = trim((string) ($_POST['analista'] ?? $_POST['tecnico'] ?? ''));
$metodo_actual = trim((string) ($_POST['metodo'] ?? ''));
$observaciones = trim((string) ($_POST['observaciones'] ?? $observaciones ?? ''));
$labFooterGuardado = null;

if ($lote_actual !== '' && !in_array($lote_actual, $labFooterLotes, true)) {
    array_unshift($labFooterLotes, $lote_actual);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $labFooterContexto) {
    $analisisGuardado = !isset($resultado)
        || !is_array($resultado)
        || !array_key_exists('exito', $resultado)
        || (bool) $resultado['exito'];

    if ($analisisGuardado) {
        $labFooterGuardado = labFooterGuardarFormularioBase(
            $labFooterContexto,
            $lote_actual,
            $fecha_actual_footer,
            $analista_actual,
            $metodo_actual,
            $observaciones
        );
    }
}
?>
<div class="form-footer">
  <?php if ($labFooterGuardado): ?>
    <div class="alerta <?= $labFooterGuardado['ok'] ? 'exito' : 'error' ?>">
      <?= labFooterE($labFooterGuardado['message']) ?>
    </div>
  <?php endif; ?>

  <div class="footer-grid">
    <div class="field">
      <label>Fecha análisis</label>
      <input type="date" name="fecha" value="<?= labFooterE($fecha_actual_footer) ?>" required>
    </div>
    <div class="field">
      <label>Analista</label>
      <input type="text" name="analista" value="<?= labFooterE($analista_actual) ?>" placeholder="Nombre del analista" required>
    </div>
    <div class="field">
      <label>Lote</label>
      <?php if ($labFooterContexto): ?>
        <select name="lote" required>
          <option value="">Seleccione un lote</option>
          <?php foreach ($labFooterLotes as $lote): ?>
            <option value="<?= labFooterE($lote) ?>" <?= $lote === $lote_actual ? 'selected' : '' ?>>
              <?= labFooterE($lote) ?>
            </option>
          <?php endforeach; ?>
          <?php if (empty($labFooterLotes)): ?>
            <option value="" disabled>No hay lotes para este analisis</option>
          <?php endif; ?>
        </select>
        <span class="field-hint">Filtrado por <?= labFooterE($labFooterContexto['label']) ?></span>
      <?php else: ?>
        <input type="text" name="lote" value="<?= labFooterE($lote_actual) ?>" required>
      <?php endif; ?>
    </div>
    <div class="field">
      <label>Método</label>
      <input type="text" name="metodo" value="<?= labFooterE($metodo_actual) ?>" placeholder="Método utilizado">
    </div>
    <div class="field full">
      <label>Observaciones</label>
      <textarea name="observaciones" placeholder="Opcional..."><?= labFooterE($observaciones) ?></textarea>
    </div>
  </div>

  <?php if (!function_exists('lab_can') || lab_can('laboratorio.analisis.crear') || lab_can('laboratorio.analisis.editar')): ?>
    <button type="submit" class="btn-submit">Guardar registro</button>
  <?php endif; ?>
</div>
