<?php require __DIR__ . '/../partials/layout.php'; ?>
<h1>Nova Assignatura</h1>
<div class="card">
    <?php if (isset($error)): ?>
        <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="index.php?route=subject/store">
        <label>Nom de l'assignatura</label>
        <input type="text" name="name" required placeholder="Ex: Programació, Bases de Dades...">
        <label>Curs al que pertany</label>
        <select name="courseId" required>
            <option value="">-- Selecciona un curs --</option>
            <?php foreach ($courses as $c): ?>
            <!-- Mostrem el nom del curs però enviem el seu ID com a valor -->
            <option value="<?= htmlspecialchars($c->id()->value()) ?>">
                <?= htmlspecialchars($c->name()) ?> (<?= $c->year() ?>)
            </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Guardar assignatura</button>
        <a href="index.php?route=subject" style="margin-left:1rem;color:#718096;font-size:.9rem">Cancel·lar</a>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
