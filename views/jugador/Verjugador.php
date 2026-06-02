<!-- Lo que hacemos en este caso, es mostrar en cartas a todos los jugadores.  
 Listados en este caso de manera total y completa. -->

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <div>
        <h1 class="fw-bold text-dark mb-1"><?= $titulo ?></h1>
        <p class="text-muted small mb-0">Listado completo de futbolistas registrados bajo seguimiento técnico.</p>
    </div>
    <?php if (isset($_SESSION['user'])): ?>
        <a href="index.php?controller=Jugador&action=FormularioCrear" class="btn btn-primary fw-semibold">
            Nuevo Jugador
        </a>
    <?php endif; ?>
</div>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    
    <?php foreach($datos as $jugador) : ?>
        <div class="col">
            <article class="card h-100 shadow-sm border-0 bg-white text-center p-3">
                
                <div class="mt-2 mb-3">
                    <img src="<?= \Sfouter\config\Parameters::getRutaWeb() . $jugador->getFoto() ?>" 
                         alt="<?= $jugador->getNombreCompleto() ?>"
                         class="rounded-circle object-fit-cover shadow-sm border border-3 border-white mx-auto"
                         style="width: 120px; height: 120px;">
                </div>
                
                <div class="card-body d-flex flex-column p-0">
                    <h5 class="card-title fw-bold text-dark mb-1"><?= $jugador->getNombreCompleto() ?></h5>
                    <p class="card-text text-muted small mb-4">
                         Naciendo: <span class="fw-medium text-secondary"><?= $jugador->getFechaNac()->format('d/m/Y') ?></span>
                    </p>
                    
                    <div class="mt-auto d-flex flex-column gap-2">
                        <a href="index.php?controller=Jugador&action=verPerfil&id=<?= $jugador->getId() ?>" 
                           class="btn btn-outline-primary btn-sm fw-semibold w-100 py-2">
                             Ver Informes
                        </a>
                        
                       <?php if (isset($_SESSION['user']) && $_SESSION['user']->getRol() === 'admin') : ?>
                        <a href="index.php?controller=Jugador&action=editarJugador&id=<?= $jugador->getId() ?>"
                           class="btn btn-outline-secondary btn-sm fw-semibold w-100">
                            Editar
                        </a>
                        <a href="index.php?controller=Jugador&action=eliminarJugador&id=<?= $jugador->getId() ?>"
                           class="btn btn-link link-danger btn-sm text-decoration-none fw-medium p-0 mt-1"
                           onclick="return confirm('¿Estás seguro? Se eliminarán también sus informes.')">
                            Eliminar jugador
                        </a>
                       <?php endif; ?>
                    </div>
                </div>
                
            </article>
        </div>
    <?php endforeach; ?>

</div>