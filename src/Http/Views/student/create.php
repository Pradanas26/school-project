<?php
/**
 * VISTA: student/create.php
 * Formulari per crear un nou estudiant.
 * Si hi ha un error de validació, $error ve del controlador.
 */
require __DIR__ . '/../partials/layout.php';
?>
<h1>Nou Estudiant</h1>
<div class="card">
    <?php if (isset($error)): ?>
        <!-- Mostrem l'error retornat pel domini (ex: "Email invàlid") -->
        <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- El formulari envia per POST al handler del controlador -->
    <form method="post" action="index.php?route=student/store">
        <label>Nom complet</label>
        <input type="text" name="name" required placeholder="Ex: Anna Garcia">

        <label>Correu electrònic</label>
        <input type="email" name="email" required placeholder="Ex: anna@escola.cat">

        <button type="submit" class="btn">Guardar estudiant</button>
        <a href="index.php?route=student" style="margin-left:1rem;color:#718096;font-size:.9rem">
            Cancel·lar
        </a>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
