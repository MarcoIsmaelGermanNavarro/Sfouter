<div class="row justify-content-center my-5">
    <div class="col-12 col-md-10 col-lg-8">
        
        <?php if (!empty($Errores)): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4 p-3" role="alert">
                <div class="d-flex align-items-center">
                    <span class="fs-5 me-2">⚠️</span>
                    <div>
                        <h6 class="alert-heading fw-bold mb-1">Por favor, corrige los siguientes errores:</h6>
                        <ul class="mb-0 small ps-3">
                            <?php foreach ($Errores as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 bg-white p-4">
            <div class="card-body">
                
                <div class="d-flex align-items-center mb-4">
                    <span class="fs-3 me-2">⚽</span>
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Registrar Nuevo Jugador</h2>
                        <p class="text-muted small mb-0">Introduce los datos técnicos y la ficha del futbolista para la base de datos.</p>
                    </div>
                </div>
                
                <form action="index.php?controller=Jugador&action=Crear" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                            <label for="nombre" class="form-label fw-medium text-secondary small">Nombre</label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control" 
                                   placeholder="Ej: Kylian" 
                                   value="<?= htmlspecialchars($dataFormulario['nombre'] ?? '') ?>"
                                   required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="apellidos" class="form-label fw-medium text-secondary small">Apellidos</label>
                            <input type="text" 
                                   name="apellidos" 
                                   id="apellidos" 
                                   class="form-control" 
                                   placeholder="Ej: Mbappé" 
                                   value="<?= htmlspecialchars($dataFormulario['apellidos'] ?? '') ?>"
                                   required> 
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-4">
                            <label for="FechaNac" class="form-label fw-medium text-secondary small">Fecha de Nacimiento</label>
                            <input type="date" 
                                   name="FechaNac" 
                                   id="FechaNac" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($dataFormulario['FechaNac'] ?? '') ?>"
                                   required> 
                        </div>
                        <div class="col-12 col-sm-8">
                            <label for="foto" class="form-label fw-medium text-secondary small">Foto del Jugador (Opcional)</label>
                            <input type="file" 
                                   name="foto" 
                                   id="foto" 
                                   class="form-control" 
                                   accept="image/*">
                            <div class="form-text text-muted xsmall">Soporta formatos JPEG, PNG.</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="index.php?controller=Home&action=index" class="btn btn-light fw-medium px-4">Cancelar</a>
                        <button type="submit" class="btn btn-success fw-semibold px-5">
                            Crear Jugador
                        </button>
                    </div>
                    
                </form>

            </div>
        </div>

    </div>
</div>