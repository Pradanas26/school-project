<?php require __DIR__ . '/../partials/layout.php'; ?>
<h1>Professors</h1>
<div class="card">
    <a href="index.php?route=teacher/create" class="btn" style="margin-bottom:1.2rem;display:inline-block">
        + Nou professor
    </a>
    <?php if (empty($teachers)): ?>
        <p class="empty">Encara no hi ha cap professor registrat.</p>
    <?php else: ?>
    <table>
        <tr><th>Nom</th><th>Especialitat</th></tr>
        <?php foreach ($teachers as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t->name()) ?></td>
            <td><?= htmlspecialchars($t->specialty()) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
