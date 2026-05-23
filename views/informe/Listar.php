<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <div>
        <h1 class="fw-bold text-dark mb-1"><?= $titulo ?></h1>
        <p class="text-muted small mb-0">Registro cronológico de todas las evaluaciones técnicas que has redactado.</p>
    </div>
</div>

<?php if (empty($informes)): ?>
    <div class="alert alert-info border-0 shadow-sm p-4 text-center my-4" role="alert">
        <span class="fs-3 d-block mb-2"></span>
        <h5 class="fw-bold text-dark">Aún no has realizado ningún informe técnico</h5>
        <p class="text-muted small mb-3">Comienza a explorar el catálogo de futbolistas para iniciar tu labor de scouting.</p>
        <a href="index.php?controller=Jugador&action=listarJugadores" class="btn btn-primary fw-semibold px-4">
            Buscar jugadores para ojear
        </a>
    </div>
<?php else: ?>
    
    <div class="card shadow-sm border-0 bg-white mb-4">
        <div class="table-responsive">
            
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark text-uppercase fs-7 tracking-wider">
                    <tr>
                        <th scope="col" class="ps-4">Fecha</th>
                        <th scope="col">Jugador</th>
                        <th scope="col" class="text-center">Nota Global</th>
                        <th scope="col" class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($informes as $inf): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="text-secondary fw-medium small">
                                    <?= $inf->getFechaFormateada()  ?>
                                </span>
                            </td>
                            
                            <td>
                                <div class="fw-bold text-dark"><?= $inf->getNombreJugador() ?></div>
                            </td>
                            
                            <td class="text-center">
                                <?php 
                                    $nota = $inf->getNotaMedia();
                                    // Asignamos color inteligente según la nota
                                    $badgeClass = ($nota >= 7) ? 'bg-success-subtle text-success' : (($nota >= 5) ? 'bg-warning-subtle text-warning-dark' : 'bg-danger-subtle text-danger');
                                ?>
                                <span class="badge <?= $badgeClass ?> px-3 py-2 fw-bold fs-6 border">
                                    <?= $nota ?>/10
                                </span>
                            </td>
                            
                            <td class="text-end pe-4">
                                <a href="index.php?controller=Informe&action=verDetalle&id=<?= $inf->getId() ?>" 
                                   class="btn btn-outline-dark btn-sm fw-medium px-3">
                                    Leer Detalle →
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
        </div>
    </div>
    
<?php endif; ?>

<div class="d-flex justify-content-start mt-3">
    <a href="index.php?controller=Usuario&action=dashboard" class="btn btn-light border fw-medium px-4">
        ⬅ Volver al Panel
    </a>
</div>