<?php
/**
 * VISTA: student/index.php
 * Mostra la llista de tots els estudiants registrats.
 * La variable $students ve del SchoolController::student()
 */
require __DIR__ . '/../partials/layout.php';
?>
<h1>Estudiants</h1>
<div class="card">
    <a href="index.php?route=student/create" class="btn" style="margin-bottom:1.2rem;display:inline-block">
        + Nou estudiant
    </a>

    <?php if (empty($students)): ?>
        <p class="empty">Encara no hi ha cap estudiant registrat.</p>
    <?php else: ?>
    <table>
        <tr>
            <th>Nom</th>
            <th>Email</th>
        </tr>
        <?php foreach ($students as $s): ?>
        <tr>
            <!-- htmlspecialchars prevé XSS: converteix < > " & a entitats HTML -->
            <td><?= htmlspecialchars($s->name()) ?></td>
            <td><?= htmlspecialchars($s->email()->value()) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
