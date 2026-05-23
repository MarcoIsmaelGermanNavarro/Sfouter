<?php if (!empty($Errores)): ?>
    <div class="row justify-content-center mt-4">
        <div class="col-12 col-lg-10">
            <div class="alert alert-danger p-3 shadow-sm border-0" role="alert">
                <h5 class="alert-heading fw-bold small">Por favor, corrige los siguientes errores:</h5>
                <ul class="mb-0 small ps-3">
                    <?php foreach ($Errores as $error): ?>
                        <li class="fw-medium"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row justify-content-center my-4">
    <div class="col-12 col-lg-10">
        
        <div class="card shadow-sm border-0 bg-white p-4">
            <div class="card-body">
                
                <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3 mb-4 gap-2">
                    <div>
                        <h1 class="fw-bold text-dark mb-1"><?= $titulo ?></h1>
                        <p class="text-muted small mb-0">
                            Evaluando al futbolista: <strong class="text-primary"><?= $nombreJugador . " " .  $apellidos ?></strong>
                        </p>
                    </div>
                    <span class="badge bg-dark px-3 py-2 text-uppercase tracking-wider small">Scouting Activo</span>
                </div>

                <form action="index.php?controller=Informe&action=Guardar" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <input type="hidden" name="idJugador" value="<?= $idJugador ?>">

                    <h5 class="fw-bold text-secondary text-uppercase tracking-wider small mb-3">Contexto de la Observación</h5>
                    <div class="row g-3 mb-4">
                        
                        <div class="col-12 col-md-4">
                            <label for="fechaInf" class="form-label fw-medium text-secondary small">Fecha del partido</label>
                            <input type="date" 
                                   id="fechaInf" 
                                   name="fechaInf" 
                                   class="form-control"
                                   value="<?= $dataFormulario['fechaInf'] ?? date('Y-m-d') ?>" 
                                   required>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="posicion" class="form-label fw-medium text-secondary small">Posición ocupada</label>
                            <select name="posicion" id="posicion" class="form-select" required>
                                <option value="" disabled <?= !isset($dataFormulario['posicion']) ? 'selected' : '' ?>>-- Seleccionar --</option>
                                <?php 
                                $posiciones = ['Portero', 'Defensa', 'Medio', 'Delantero'];
                                foreach ($posiciones as $p): ?>
                                    <option value="<?= $p ?>" <?= (isset($dataFormulario['posicion']) && $dataFormulario['posicion'] == $p) ? 'selected' : '' ?>>
                                        <?= $p ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="idEquipo" class="form-label fw-medium text-secondary small">Equipo implicado</label>
                            <select name="idEquipo" id="idEquipo" class="form-select" required>
                                <option value="" disabled <?= !isset($dataFormulario['idEquipo']) ? 'selected' : '' ?>>-- Seleccionar --</option>
                                <?php foreach($equipos as $equipoActual): ?> 
                                    <option value="<?= $equipoActual->getId() ?>" 
                                        <?= (isset($dataFormulario['idEquipo']) && $dataFormulario['idEquipo'] == $equipoActual->getId()) ? 'selected' : '' ?>>
                                        <?= $equipoActual->getNombre() ?> 
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <hr class="text-muted opacity-25 my-4">

                    <h5 class="fw-bold text-secondary text-uppercase tracking-wider small mb-3">Evaluación de Atributos (1 al 10)</h5>
                    <div class="row g-4 mb-4">
                        
                        <div class="col-12 col-md-4">
                            <div class="bg-light p-3 rounded border border-light-subtle">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="velocidad" class="form-label fw-semibold text-dark small mb-0">Velocidad</label>
                                    <span id="val-velocidad" class="badge bg-primary fw-bold fs-6">5</span>
                                </div>
                                <input type="range" class="form-range" min="1" max="10" step="1" id="velocidad" name="velocidad" 
                                       value="<?= $dataFormulario['velocidad'] ?? '5' ?>" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="bg-light p-3 rounded border border-light-subtle">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="resistencia" class="form-label fw-semibold text-dark small mb-0">Resistencia</label>
                                    <span id="val-resistencia" class="badge bg-info text-dark fw-bold fs-6">5</span>
                                </div>
                                <input type="range" class="form-range" min="1" max="10" step="1" id="resistencia" name="resistencia" 
                                       value="<?= $dataFormulario['resistencia'] ?? '5' ?>" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="bg-light p-3 rounded border border-light-subtle">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="dribbling" class="form-label fw-semibold text-dark small mb-0">Dribbling (Regate)</label>
                                    <span id="val-dribbling" class="badge bg-warning text-dark fw-bold fs-6">5</span>
                                </div>
                                <input type="range" class="form-range" min="1" max="10" step="1" id="dribbling" name="dribbling" 
                                       value="<?= $dataFormulario['dribbling'] ?? '5' ?>" required>
                            </div>
                        </div>

                    </div>

                    <hr class="text-muted opacity-25 my-4">

                    <div class="mb-4">
                        <label for="observaciones" class="form-label fw-bold text-secondary text-uppercase tracking-wider small mb-2">Análisis Táctico y Notas de Campo</label>
                        <textarea id="observaciones" 
                                  name="observaciones" 
                                  class="form-control" 
                                  rows="6" 
                                  placeholder="Escribe aquí los detalles técnicos, movimientos tácticos, pie dominante, toma de decisiones..."><?= $dataFormulario['observaciones'] ?? '' ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="index.php?controller=Jugador&action=verPerfil&id=<?= $idJugador ?>" class="btn btn-light fw-medium px-4">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success fw-semibold px-5 shadow-sm">
                            Guardar Informe Técnico
                        </button>
                    </div>

                </form>

            </div>
        </div>
        
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const sliders = ['velocidad', 'resistencia', 'dribbling'];
    sliders.forEach(id => {
        const input = document.getElementById(id);
        const badge = document.getElementById('val-' + id);
        
        // Sincronizar el valor inicial del input (por si viene de dataFormulario)
        badge.textContent = input.value;
        
        // Escuchar los cambios en directo del ojeador al deslizar
        input.addEventListener('input', function() {
            badge.textContent = this.value;
        });
    });
});
</script>