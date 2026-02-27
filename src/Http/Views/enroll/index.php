<?php
/**
 * VISTA: enroll/index.php
 * CAS D'ÚS 5: EnrollStudent — Matriculació d'estudiants en cursos.
 *
 * Mostra:
 *  1. Formulari per seleccionar estudiant + curs i matricular
 *  2. Taula de matrícules existents
 *
 * Variables: $students, $courses, $enrollments (del controlador)
 */
require __DIR__ . '/../partials/layout.php';

// Mapes per mostrar noms en lloc d'IDs a la taula
$studentMap = [];
foreach ($students as $s) { $studentMap[$s->id()->value()] = $s->name(); }
$courseMap = [];
foreach ($courses as $c)  { $courseMap[$c->id()->value()]  = $c->name(); }
?>
<h1>Matriculació d'Estudiants</h1>
<p style="color:#718096;margin-bottom:1.5rem;font-size:.9rem">
    Cas d'ús 5: <strong>EnrollStudent</strong> — Assigna un estudiant a un curs.
</p>

<!-- Formulari de matriculació -->
<div class="card">
    <h2>Nova Matrícula</h2>
    <?php if (isset($error)): ?>
        <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($students) || empty($courses)): ?>
        <p class="empty">
            Necessites tenir almenys un estudiant i un curs per poder matricular.
            <br><a href="index.php?route=student/create">Crea un estudiant</a> o
            <a href="index.php?route=course/create">crea un curs</a> primer.
        </p>
    <?php else: ?>
    <form method="post" action="index.php?route=enroll/store"
          style="display:flex;gap:1.5rem;align-items:flex-end;flex-wrap:wrap">

        <div style="flex:1;min-width:200px">
            <label>Estudiant</label>
            <select name="studentId" required>
                <option value="">-- Selecciona estudiant --</option>
                <?php foreach ($students as $s): ?>
                <option value="<?= htmlspecialchars($s->id()->value()) ?>">
                    <?= htmlspecialchars($s->name()) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="flex:1;min-width:200px">
            <label>Curs</label>
            <select name="courseId" required>
                <option value="">-- Selecciona curs --</option>
                <?php foreach ($courses as $c): ?>
                <option value="<?= htmlspecialchars($c->id()->value()) ?>">
                    <?= htmlspecialchars($c->name()) ?> (<?= $c->year() ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="padding-bottom:1rem">
            <button type="submit" class="btn">Matricular</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<!-- Taula de matrícules existents -->
<?php if (!empty($enrollments)): ?>
<div class="card">
    <h2>Matrícules registrades</h2>
    <table>
        <tr><th>Estudiant</th><th>Curs</th><th>Data de matrícula</th></tr>
        <?php foreach ($enrollments as $e): ?>
        <tr>
            <!-- Usem els mapes per mostrar noms en lloc dels IDs interns -->
            <td><?= htmlspecialchars($studentMap[$e->studentId()->value()] ?? $e->studentId()->value()) ?></td>
            <td><?= htmlspecialchars($courseMap[$e->courseId()->value()]   ?? $e->courseId()->value()) ?></td>
            <td><?= $e->enrolledAt()->format('d/m/Y H:i') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
