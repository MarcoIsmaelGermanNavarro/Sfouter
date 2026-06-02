<?php
// 1. Importación limpia en la cabecera
use Sfouter\config\Parameters; 
?>

<div class="card shadow-sm border-0 bg-white p-4 mb-4">
    <div class="row align-items-center g-3">
        
        <div class="col-12 col-sm-auto text-center text-sm-start">
            <img src="<?= Parameters::getRutaWeb() . htmlspecialchars($jugador->getFoto()) ?>"
                 alt="<?= $jugador->getNombreCompleto() ?>"
                 class="rounded-circle object-fit-cover shadow-sm border border-2 border-light"
                 style="width: 100px; height: 100px;">
        </div>
        
        <div class="col-12 col-sm text-center text-sm-start">
            <span class="badge bg-secondary text-uppercase px-3 py-2 mb-1 small tracking-wider">Ficha Técnica</span>
            <h1 class="fw-bold text-dark mb-0"><?= $jugador->getNombreCompleto() ?></h1>
        </div>
        
        <div class="col-12 col-sm-auto text-center text-sm-end d-flex flex-column gap-2">
            <a href="index.php?controller=Informe&action=crear&idjugador=<?= $jugador->getId() ?>"
               class="btn btn-success btn-lg fw-semibold shadow-sm">
                Añadir Informe Técnico
            </a>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']->getRol() === 'admin'): ?>
            <a href="index.php?controller=Jugador&action=editarJugador&id=<?= $jugador->getId() ?>"
               class="btn btn-outline-secondary fw-medium">
                ✏️ Editar jugador
            </a>
            <?php elseif (isset($_SESSION['user'])): ?>
            <?php
                $nombreJugador = $jugador->getNombreCompleto();
                $idJugador     = $jugador->getId();
                $asunto        = rawurlencode("Solicitud de eliminación: {$nombreJugador} (ID {$idJugador})");
                $cuerpo        = rawurlencode("Hola,\n\nSolicito la eliminación del jugador {$nombreJugador} (ID: {$idJugador}) por el siguiente motivo:\n\n[Indica el motivo aquí]\n\nGracias.");
                $adminEmail    = "admin@sfouter.com";
            ?>
            <a href="mailto:<?= $adminEmail ?>?subject=<?= $asunto ?>&body=<?= $cuerpo ?>"
               class="btn btn-outline-danger btn-sm fw-medium">
                Solicitar eliminación
            </a>
            <?php endif; ?>
        </div>
        
    </div>
</div>

<div class="my-4">
    <h3 class="fw-bold text-dark mb-3">Historial de Análisis</h3>
</div>

<?php if (empty($informes)): ?>
    <div class="alert alert-info border-0 shadow-sm p-4 text-center" role="alert">
        <span class="fs-4 d-block mb-2">📋</span>
        <h5 class="fw-bold text-dark">Aún no hay informes para este jugador</h5>
        <p class="mb-0 text-muted small">Sé el primero en redactar un análisis técnico sobre su rendimiento.</p>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach($informes as $i) : ?> 
            <div class="col">
                <article class="card h-100 shadow-sm border-0 bg-white">
                    
                    <div class="card-body d-flex flex-column p-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">Informe #<?= $i->id ?></h5>
                            <span class="badge bg-light text-dark border fw-medium small">
                                <?= $i->getFechaInf() -> format('d/m/Y')?>
                            </span>
                        </div>
                        
                        <p class="text-muted small mb-3">
                            <strong class="text-secondary">Posición analizada:</strong> 
                            <span class="badge bg-dark-subtle text-dark fw-semibold ms-1"><?= $i->posicion ?></span>
                        </p>
                        
                        <hr class="text-muted opacity-25 my-2">
                        
                        <div class="mb-4 mt-2">
                            
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-secondary fw-medium">Velocidad</span>
                                    <span class="fw-bold text-dark"><?= $i->velocidad ?>/10</span>
                                </div>
                                <div class="progress" style="height: 6px;" role="progressbar" aria-label="Velocidad" aria-valuenow="<?= $i->velocidad * 10 ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-primary" style="width: <?= $i->velocidad * 10 ?>%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-secondary fw-medium">Resistencia</span>
                                    <span class="fw-bold text-dark"><?= $i->resistencia ?>/10</span>
                                </div>
                                <div class="progress" style="height: 6px;" role="progressbar" aria-label="Resistencia" aria-valuenow="<?= $i->resistencia * 10 ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-info" style="width: <?= $i->resistencia * 10 ?>%"></div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <a href="index.php?controller=Informe&action=verDetalle&id=<?= $i->id ?>" 
                           class="btn btn-outline-dark btn-sm w-100 mt-auto fw-medium">
                            Ver análisis completo →
                        </a>
                        
                    </div>
                    
                </article>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>