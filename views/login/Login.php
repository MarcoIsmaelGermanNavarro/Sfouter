
    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <?php foreach($errores as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    

<form action="index.php?controller=Login&action=loguear" method="POST">
    
<input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

<div class="row justify-content-center my-5">
    <div class="col-12 col-md-6 col-lg-4">
        
        <div class="card shadow-sm border-0 bg-white p-4">
            <div class="card-body">
                
                <h2 class="text-center fw-bold text-dark mb-4">Acceso Ojeadores</h2>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium text-secondary">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control form-control-lg" 
                               placeholder="ejemplo@sfouter.com" 
                               value="<?= htmlspecialchars($old['email'] ?? "") ?> "
                               required> </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium text-secondary">Contraseña</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control form-control-lg" 
                               placeholder="••••••••"
                               required> </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">
                        Loguear Usuario
                    </button>
                    
                </form>

            </div>
        </div>

    </div>
</div>
</form>