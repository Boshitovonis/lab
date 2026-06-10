<?php
// Variables esperadas (definidas en cada formulario antes del include):
$doc_elemento    = "Fósforo";
$doc_tipo        = "Suelos";
$doc_codigo      = "LAB-001";
$doc_fecha_doc   = "2024-01-15";
$doc_edicion     = "03";
$doc_vf          = "V2";
?>

<div class="doc-header">
    <div class="doc-col logo-col">
        <img src="<?= $logo_path ?? '../../assets/logo.png' ?>" alt="Logo laboratorio">
    </div>
    <div class="doc-col title-col">
        <span class="doc-registro-label">Registro</span>
        <span class="doc-registro-main">Determinación de <?= htmlspecialchars($doc_elemento) ?></span>
        <span class="doc-registro-sub">en <?= htmlspecialchars($doc_tipo) ?></span>
    </div>
    <div class="doc-col meta-col">
        <div class="meta-row"><span>Código</span><strong><?= htmlspecialchars($doc_codigo) ?></strong></div>
        <div class="meta-row"><span>Fecha doc.</span><strong><?= htmlspecialchars($doc_fecha_doc) ?></strong></div>
        <div class="meta-row"><span>Edición</span><strong><?= htmlspecialchars($doc_edicion) ?></strong></div>
        <div class="meta-row"><span>VF</span><strong><?= htmlspecialchars($doc_vf) ?></strong></div>
    </div>
</div>