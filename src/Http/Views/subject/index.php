<?php
/**
 * VISTA: subject/index.php
 * Mostra totes les assignatures amb el seu curs i si tenen professor assignat.
 * Variables disponibles: $subjects (Subject[]) i $courses (Course[])
 */
require __DIR__ . '/../partials/layout.php';

// Creem un mapa courseId => nomCurs per mostrar el nom en lloc de l'ID
$courseMap = [];
foreach ($courses as $c) {
    $courseMap[$c->id()->value()] = $c->name();
}
?>
<h1>Assignatures</h1>
<div class="card">
    <a href="index.php?route=subject/create" class="btn" style="margin-bottom:1.2rem;display:inline-block">
        + Nova assignatura
    </a>
    <?php if (empty($subjects)): ?>
        <p class="empty">Encara no hi ha cap assignatura registrada.</p>
    <?php else: ?>
    <table>
        <tr><th>Assignatura</th><th>Curs</th><th>Professor</th></tr>
        <?php foreach ($subjects as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s->name()) ?></td>
            <td><?= htmlspecialchars($courseMap[$s->courseId()->value()] ?? '—') ?></td>
            <td>
                <?php if ($s->hasTeacher()): ?>
                    <!-- Assignatura amb professor: badge verd -->
                    <span class="badge-ok">✅ Assignat</span>
                <?php else: ?>
                    <!-- Sense professor: badge groc -->
                    <span class="badge-no">⏳ Pendent</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
