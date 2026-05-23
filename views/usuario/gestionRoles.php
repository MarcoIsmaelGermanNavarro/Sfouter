<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <div>
        <h1 class="fw-bold text-dark mb-1"><?= $titulo ?></h1>
        <p class="text-muted small mb-0">Panel exclusivo de administración para la asignación y control de privilegios en Sfouter.</p>
    </div>
</div>

<div class="card shadow-sm border-0 bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase font-monospace">
                    <tr>
                        <th class="ps-4 py-3">Usuario</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Fecha Registro</th>
                        <th class="py-3">Rol Actual</th>
                        <th class="pe-4 py-3 text-end">Acción Táctica</th>
                    </tr>
                </thead>
                <tbody class="text-dark small">
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    <?= htmlspecialchars($u->getNombre() . ' ' . $u->getApellidos()) ?>
                                    <?php if ($u->getId() === $_SESSION['user']->getId()): ?>
                                        <span class="badge bg-info-subtle text-info ms-1 xsmall fw-medium">Tú</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-secondary"><?= htmlspecialchars($u->getEmail()) ?></td>
                                
                                <td class="text-secondary"><?= $u->getFechaReg()->format('d/m/Y') ?></td>
                                
                                <td>
                                    <?php if ($u->getRol() === 'admin'): ?>
                                        <span class="badge bg-danger px-2.5 py-1.5 fw-semibold text-uppercase">Administrador</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary px-2.5 py-1.5 fw-semibold text-uppercase">Ojeador (Scout)</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="pe-4 text-end">
                                    <form action="index.php?controller=Usuario&action=actualizarRol" method="POST" class="d-inline-flex gap-2 align-items-center justify-content-end">

                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                    
                                        <input type="hidden" name="id_usuario" value="<?= $u->getId() ?>">
                                        
                                        <select name="nuevo_rol" class="form-select form-select-sm border-light-subtle py-1" style="width: 140px;" <?= $u->getId() === $_SESSION['user']->getId() ? 'disabled' : '' ?>>
                                            <option value="scout" <?= $u->getRol() === 'scout' ? 'selected' : '' ?>>Ojeador</option>
                                            <option value="admin" <?= $u->getRol() === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                        </select>
                                        
                                        <button type="submit" class="btn btn-sm btn-dark fw-medium px-2.5" <?= $u->getId() === $_SESSION['user']->getId() ? 'disabled' : '' ?> onclick="return confirm('¿Confirmas el cambio de privilegios para este usuario?')">
                                            Guardar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No hay más usuarios registrados en el sistema.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="index.php?controller=Usuario&action=mostrarDashboard" class="btn btn-light border fw-medium px-4">
        ⬅ Volver al Dashboard
    </a>
</div>