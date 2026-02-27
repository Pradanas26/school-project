<?php
/**
 * VISTA: assign/index.php
 * CAS D'ÚS 6: AssignTeacherToSubject — Assignació de professor a assignatura.
 *
 * Mostra:
 *  1. Formulari per seleccionar professor + assignatura
 *  2. Taula d'assignacions existents
 *
 * Variables: $teachers, $subjects, $assignments (del controlador)
 */
require __DIR__ . '/../partials/layout.php';

// Mapes per mostrar noms en lloc d'IDs a la taula
$teacherMap = [];
foreach ($teachers as $t) { $teacherMap[$t->id()->value()] = $t->name(); }
$subjectMap = [];
foreach ($subjects as $s) { $subjectMap[$s->id()->value()] = $s->name(); }
?>
<h1>Assignació de Professors a Assignatures</h1>
<p style="color:#718096;margin-bottom:1.5rem;font-size:.9rem">
    Cas d'ús 6: <strong>AssignTeacherToSubject</strong> — Vincula un professor a una assignatura.
</p>

<!-- Formulari d'assignació -->
<div class="card">
    <h2>Nova Assignació</h2>
    <?php if (isset($error)): ?>
        <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($teachers) || empty($subjects)): ?>
        <p class="empty">
            Necessites tenir almenys un professor i una assignatura.
            <br><a href="index.php?route=teacher/create">Crea un professor</a> o
            <a href="index.php?route=subject/create">crea una assignatura</a> primer.
        </p>
    <?php else: ?>
    <form method="post" action="index.php?route=assign/store"
          style="display:flex;gap:1.5rem;align-items:flex-end;flex-wrap:wrap">

        <div style="flex:1;min-width:200px">
            <label>Professor</label>
            <select name="teacherId" required>
                <option value="">-- Selecciona professor --</option>
                <?php foreach ($teachers as $t): ?>
                <option value="<?= htmlspecialchars($t->id()->value()) ?>">
                    <?= htmlspecialchars($t->name()) ?> — <?= htmlspecialchars($t->specialty()) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="flex:1;min-width:200px">
            <label>Assignatura</label>
            <select name="subjectId" required>
                <option value="">-- Selecciona assignatura --</option>
                <?php foreach ($subjects as $s): ?>
                <option value="<?= htmlspecialchars($s->id()->value()) ?>">
                    <?= htmlspecialchars($s->name()) ?>
                    <?= $s->hasTeacher() ? ' (ja té professor)' : '' ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="padding-bottom:1rem">
            <button type="submit" class="btn">Assignar</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<!-- Taula d'assignacions existents -->
<?php if (!empty($assignments)): ?>
<div class="card">
    <h2>Assignacions registrades</h2>
    <table>
        <tr><th>Professor</th><th>Assignatura</th><th>Data d'assignació</th></tr>
        <?php foreach ($assignments as $a): ?>
        <tr>
            <td><?= htmlspecialchars($teacherMap[$a->teacherId()->value()] ?? $a->teacherId()->value()) ?></td>
            <td><?= htmlspecialchars($subjectMap[$a->subjectId()->value()] ?? $a->subjectId()->value()) ?></td>
            <td><?= $a->assignedAt()->format('d/m/Y H:i') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
