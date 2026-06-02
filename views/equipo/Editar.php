<div class="row justify-content-center my-5">
    <div class="col-12 col-md-8 col-lg-6">

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0 small ps-3">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 bg-white p-4">
            <div class="card-body">

                <div class="d-flex align-items-center mb-4">
                    <span class="fs-3 me-2">✏️</span>
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Editar Equipo</h2>
                        <p class="text-muted small mb-0">Modifica el nombre del equipo.</p>
                    </div>
                </div>

                <form action="index.php?controller=Equipo&action=actualizar" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="id" value="<?= $equipo->getid() ?>">

                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-medium text-secondary small">Nombre del equipo</label>
                        <input type="text"
                               name="nombre"
                               id="nombre"
                               class="form-control py-2"
                               value="<?= htmlspecialchars($equipo->getNombre()) ?>"
                               required>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="index.php?controller=Equipo&action=listar" class="btn btn-light fw-medium px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-semibold px-5">Guardar Cambios</button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
