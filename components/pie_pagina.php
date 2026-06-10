<div class="form-footer">
  <div class="footer-grid">
    <div class="field">
      <label>Fecha</label>
      <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="field">
      <label>Lote</label>
      <input type="text" name="lote" value="<?= htmlspecialchars($lote_actual) ?>" required>
    </div>
    <div class="field">
      <label>Técnico</label>
      <select name="tecnico_id" required>
        <?php foreach ($tecnicos as $t): ?>
          <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field full">
      <label>Observaciones</label>
      <textarea name="observaciones" placeholder="Opcional..."><?= htmlspecialchars($observaciones ?? '') ?></textarea>
    </div>
  </div>
    <button type="submit" class="btn-submit">Guardar registro</button>
  </div>