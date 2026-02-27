<?php require __DIR__ . '/../partials/layout.php'; ?>
<h1>Nou Professor</h1>
<div class="card">
    <?php if (isset($error)): ?>
        <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="index.php?route=teacher/store">
        <label>Nom complet</label>
        <input type="text" name="name" required placeholder="Ex: Jordi López">
        <label>Especialitat</label>
        <input type="text" name="specialty" required placeholder="Ex: Informàtica, Matemàtiques...">
        <button type="submit" class="btn">Guardar professor</button>
        <a href="index.php?route=teacher" style="margin-left:1rem;color:#718096;font-size:.9rem">Cancel·lar</a>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
