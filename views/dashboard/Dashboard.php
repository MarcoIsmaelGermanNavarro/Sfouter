<div class="row mb-4 border-bottom pb-3">
    <div class="col-12 text-center text-md-start">
        <span class="badge bg-primary text-uppercase px-3 py-2 mb-2 small tracking-wider">Panel de Control</span>
        <h1 class="fw-bold text-dark mb-1"><?= $titulo ?></h1>
        <p class="text-muted small mb-0">Bienvenido de nuevo. Aquí tienes el rendimiento y el impacto de tu actividad de scouting.</p>
    </div>
</div>

<div class="row g-4 mb-5">
    
    <div class="col-12 col-md-6">
        <div class="card h-100 shadow-sm border-0 bg-white p-4">
            <div class="card-body d-flex flex-column">
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-primary-subtle text-primary p-3 rounded-3 fs-3">
                        
                    </div>
                    <span class="text-secondary small fw-bold text-uppercase tracking-wider">Cartera</span>
                </div>
                
                <h4 class="card-title text-muted small fw-medium mb-1">Jugadores Únicos</h4>
                <h2 class="display-5 fw-bold text-dark mb-3"><?= $NumeroJugadores ?></h2>
                
                <p class="card-text text-muted small mb-4 flex-grow-1">
                    Futbolistas independientes a los que has realizado un seguimiento técnico en los campos.
                </p>
                
                <a href="index.php?controller=Jugador&action=listarJugadores&propios=1" 
                   class="btn btn-outline-primary w-100 fw-semibold py-2">
                     Ver Mis Jugadores
                </a>
                
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6">
        <div class="card h-100 shadow-sm border-0 bg-white p-4">
            <div class="card-body d-flex flex-column">
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-success-subtle text-success p-3 rounded-3 fs-3">
                        
                    </div>
                    <span class="text-secondary small whitespace-nowrap fw-bold text-uppercase tracking-wider">Informes</span>
                </div>
                
                <h4 class="card-title text-muted small fw-medium mb-1">Evaluaciones Totales</h4>
                <h2 class="display-5 fw-bold text-dark mb-3"><?= $NumeroInformes ?></h2>
                
                <p class="card-text text-muted small mb-4 flex-grow-1">
                    Informes de partidos y fichas de atributos completadas almacenadas en tu historial.
                </p>
                
                <a href="index.php?controller=Informe&action=misInformes" 
                   class="btn btn-outline-success w-100 fw-semibold py-2">
                     Examinar Historial
                </a>
                
            </div>
        </div>
    </div>

</div>

<div class="card bg-dark text-white p-4 shadow-sm border-0 bg-gradient">
    <div class="row align-items-center g-3">
        <div class="col-12 col-md-8 text-center text-md-start">
            <h5 class="fw-bold mb-1">¿Has asistido a un nuevo partido o entrenamiento?</h5>
            <p class="text-white-50 small mb-0">Registra un jugador al instante o añade nuevos clubes para tus reportes tácticos.</p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end d-flex gap-2 justify-content-center justify-content-md-end">
            <a href="index.php?controller=Equipo&action=mostrarFormulario" class="btn btn-sm btn-outline-light px-3 fw-medium">
                🛡️ Añadir Equipo
            </a>
            <a href="index.php?controller=Jugador&action=listarJugadores" class="btn btn-sm btn-primary px-3 fw-bold">
                ⚽ Buscar Jugador
            </a>
        </div>
    </div>
</div>