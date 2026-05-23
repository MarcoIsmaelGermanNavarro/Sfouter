<?php if (!empty($Errores)): ?>
    <div class="row justify-content-center mt-4">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="alert alert-danger p-3 shadow-sm border-0" role="alert">
                <h5 class="alert-heading fw-bold small">Por favor, corrige el siguiente problema:</h5>
                <ul class="mb-0 small ps-3">
                    <?php foreach ($Errores as $error): ?>
                        <li class="fw-medium"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row justify-content-center my-5">
    <div class="col-12 col-md-8 col-lg-6">
        
        <div class="card shadow-sm border-0 bg-white p-4">
            <div class="card-body">
                
                <div class="d-flex align-items-center mb-4">
                    <span class="fs-3 me-2">🛡️</span>
                    <div>
                        <h2 class="fw-bold text-dark mb-0"><?= $titulo ?></h2>
                        <p class="text-muted small mb-0">Añade un nuevo club o escuela de fútbol base al sistema.</p>
                    </div>
                </div>
                
                <form action="index.php?controller=Equipo&action=guardar" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-medium text-secondary small">Nombre del equipo</label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control py-2" 
                               placeholder="Ej: F.C. Barcelona o Real Madrid" 
                               value="<?= isset($dataFormulario['nombre']) ? trim($dataFormulario['nombre']) : '' ?>" 
                               required>
                        <div class="form-text text-muted xsmall">Asegúrate de comprobar que el nombre no esté registrado previamente.</div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="index.php?controller=Usuario&action=mostrarDashboard" class="btn btn-light fw-medium px-4">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success fw-semibold px-4 shadow-sm">
                            Crear Equipo
                        </button>
                    </div>
                    
                </form>

            </div>
        </div>

    </div>
</div>