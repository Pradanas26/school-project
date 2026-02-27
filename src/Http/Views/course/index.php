<?php require __DIR__ . '/../partials/layout.php'; ?>
<h1>Cursos</h1>
<div class="card">
    <a href="index.php?route=course/create" class="btn" style="margin-bottom:1.2rem;display:inline-block">
        + Nou curs
    </a>
    <?php if (empty($courses)): ?>
        <p class="empty">Encara no hi ha cap curs registrat.</p>
    <?php else: ?>
    <table>
        <tr><th>Nom del curs</th><th>Any acadèmic</th></tr>
        <?php foreach ($courses as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c->name()) ?></td>
            <td><?= htmlspecialchars((string)$c->year()) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
