<!--  Esta es de la que se encrgad e los informes en detalle. --> 

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <div>
        <h1 class="fw-bold text-dark mb-1"><?= $titulo ?></h1>
        <p class="text-muted small mb-0">Análisis detallado de rendimiento recopilado por el ojeador técnico.</p>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-12 col-lg-5">
        
        <div class="card shadow-sm border-0 bg-white mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">📋 Datos Generales</h5>
                
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    <li class="d-flex justify-content-between">
                        <span class="text-secondary small fw-medium">Equipo en el partido:</span>
                        <span class="fw-bold text-dark"><?= $nombreEquipo ?></span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-secondary small fw-medium">Fecha de la observación:</span>
                        <span class="badge bg-light text-dark border fw-medium">
                            <?= $informe->fechaInf->format('d/m/Y') ?>
                        </span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small fw-medium">Posición en el campo:</span>
                        <span class="badge bg-dark px-3 py-1.5 fw-semibold"><?= $informe->posicion ?></span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card shadow-sm border-0 bg-white">
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">📊 Evaluación Técnica</h5>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-secondary fw-medium">Velocidad</span>
                        <span class="fw-bold text-dark"><?= $informe->velocidad ?>/10</span>
                    </div>
                    <div class="progress" style="height: 8px;" role="progressbar" aria-valuenow="<?= $informe->velocidad * 10 ?>" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: <?= $informe->velocidad * 10 ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-secondary fw-medium">Dribbling (Regate)</span>
                        <span class="fw-bold text-dark"><?= $informe->dribbling ?>/10</span>
                    </div>
                    <div class="progress" style="height: 8px;" role="progressbar" aria-valuenow="<?= $informe->dribbling * 10 ?>" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-warning" style="width: <?= $informe->dribbling * 10 ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-0">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-secondary fw-medium">Resistencia</span>
                        <span class="fw-bold text-dark"><?= $informe->resistencia ?>/10</span>
                    </div>
                    <div class="progress" style="height: 8px;" role="progressbar" aria-valuenow="<?= $informe->resistencia * 10 ?>" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-info" style="width: <?= $informe->resistencia * 10 ?>%"></div>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>

    <div class="col-12 col-lg-7">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-body p-4 d-flex flex-column">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">📝 Análisis del Ojeador</h5>
                
                <p class="text-secondary lh-lg mb-0 p-3 bg-light rounded border border-light-subtle flex-grow-1" style="white-space: pre-line;">
                    <?= htmlspecialchars($informe->observaciones) ?>
                </p>
            </div>
        </div>
    </div>
    
</div>

<div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
    <a href="index.php?controller=Jugador&action=verPerfil&id=<?= $idJugador ?>" class="btn btn-light border fw-medium px-4">
        ⬅ Volver al Perfil
    </a>
    
    <a href="index.php?controller=Informe&action=eliminar&id=<?= $informe->id ?>&idjugador=<?= $idJugador ?>" 
       class="btn btn-outline-danger btn-sm px-4" 
       onclick="return confirm('¿Estás completamente seguro de eliminar este informe? Esta acción no se puede deshacer.')">
         Eliminar Informe
    </a>
</div>