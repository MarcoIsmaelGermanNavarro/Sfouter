<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <div>
        <h1 class="fw-bold text-dark mb-1"><?= $titulo ?></h1>
        <p class="text-muted small mb-0">Listado de todos los equipos registrados en el sistema.</p>
    </div>
    <a href="index.php?controller=Equipo&action=mostrarFormulario" class="btn btn-primary fw-semibold">
        Nuevo Equipo
    </a>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <?php foreach ($errores as $e): ?>
            <div><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($equipos)): ?>
    <p class="text-muted">No hay equipos registrados todavía.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipos as $equipo): ?>
                    <tr>
                        <td class="text-muted small"><?= $equipo->getid() ?></td>
                        <td class="fw-medium"><?= htmlspecialchars($equipo->getNombre()) ?></td>
                        <td class="text-end">
                            <a href="index.php?controller=Equipo&action=editar&id=<?= $equipo->getid() ?>"
                               class="btn btn-outline-secondary btn-sm me-2">Editar</a>
                            <a href="index.php?controller=Equipo&action=eliminar&id=<?= $equipo->getid() ?>"
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('¿Eliminar este equipo y todos sus informes asociados?')">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
