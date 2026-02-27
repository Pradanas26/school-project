<?php require __DIR__ . '/../partials/layout.php'; ?>
<h1>Nou Curs</h1>
<div class="card">
    <?php if (isset($error)): ?>
        <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="index.php?route=course/store">
        <label>Nom del curs</label>
        <input type="text" name="name" required placeholder="Ex: DAW 1r, SMX 2n...">
        <label>Any acadèmic</label>
        <input type="number" name="year" required value="<?= date('Y') ?>" min="2000" max="2100">
        <button type="submit" class="btn">Guardar curs</button>
        <a href="index.php?route=course" style="margin-left:1rem;color:#718096;font-size:.9rem">Cancel·lar</a>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
