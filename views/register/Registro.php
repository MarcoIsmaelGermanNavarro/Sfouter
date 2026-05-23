
<?php 
// Ahora lo entiendo,pone los erroes en una alerta de manera ordenada, y tambin si lo vuelve ea enviar pone los datos que habias puesto 
// ya que recoge en este caso la sesion que es de manera global. Y despues estoa forma me gusta, porque no usaus echo. 
// Puedes juntar nomeclatura php, de forma mas sencilla, en este caso, Indicando cual es la accion que en este caso estas 
// cerrando

// Muestra los errores en el caso de que existan. 
if (!empty($errores)): ?> 
    <div class="row justify-content-center mt-4">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="alert alert-danger p-3 mb-0 shadow-sm" role="alert">
                <h5 class="alert-heading fw-bold small">Por favor, corrige los siguientes errores:</h5>
                <ul class="mb-0 small ps-3">
                    <?php foreach ($errores as $error): ?>
                        <li class="fw-medium"><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>  

<div class="row justify-content-center my-5">
    <div class="col-12 col-md-8 col-lg-5">
        
        <div class="card shadow-sm border-0 bg-white p-4">
            <div class="card-body">
                
                <h2 class="text-center fw-bold text-dark mb-2">Crear Cuenta de Ojeador</h2>
                <p class="text-center text-muted small mb-4">Regístrate en Sfouter para empezar a redactar informes técnicos.</p>
                
                <form action="index.php?controller=Usuario&action=register" method="POST">

                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6">
                    <!-- Separamos ene ste caso los label de los input.  -->
                            <label for="nombre" class="form-label fw-medium text-secondary small">Nombre</label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($old['nombre'] ?? "") ?>" 
                                   required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="apellidos" class="form-label fw-medium text-secondary small">Apellidos</label>
                            <input type="text" 
                                   name="apellidos" 
                                   id="apellidos" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($old['apellidos'] ?? "") ?>" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium text-secondary small">Correo Electrónico</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control" 
                               placeholder="ejemplo@sfouter.com" 
                               value="<?= htmlspecialchars($old['email'] ?? "") ?>" 
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium text-secondary small">Contraseña</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control" 
                               placeholder="Mínimo 8 caracteres" 
                               required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold mb-3">
                        Registrar Usuario
                    </button>
                    
                </form>
                
                <div class="text-center mt-2">
                    <p class="text-muted small mb-0">
                        ¿Ya tienes una cuenta activa? 
                        <a href="index.php?controller=Login&action=mostrarLogin" class="text-decoration-none fw-medium">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>