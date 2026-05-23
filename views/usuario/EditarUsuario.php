


<?php if (!empty($errores)) : ?>
    <div class="row justify-content-center mt-4">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="alert alert-danger p-3 shadow-sm">
                <ul class="mb-0 small ps-3">
                    <?php foreach ($errores as $error) : ?>
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
                
                <h2 class="fw-bold text-dark mb-2">Editar Mi Perfil</h2>
                <p class="text-muted small mb-4">Modifica tus datos de ojeador en la plataforma.</p>
                
                <form action="index.php?controller=Usuario&action=guardarEdicion" method="POST">

                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-medium text-secondary small">Nombre</label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control form-control-lg" 
                               value="<?= $identidad->getNombre() ?>" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellidos" class="form-label fw-medium text-secondary small">Apellidos</label>
                        <input type="text" 
                               name="apellidos" 
                               id="apellidos" 
                               class="form-control form-control-lg" 
                               value="<?= $identidad->getApellidos() ?>" 
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="form-label fw-medium text-secondary small">Correo Electrónico</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control form-control-lg" 
                               value="<?= $identidad->getEmail() ?>" 
                               required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">
                        Guardar Cambios
                    </button>
                    
                </form>

            </div>
        </div>

    </div>
</div>