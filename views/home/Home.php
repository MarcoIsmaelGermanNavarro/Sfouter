<?php
// 1. Importación limpia en la cabecera
use Sfouter\config\Parameters; 
?>

<div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
    <div class="container-fluid py-3">
        <?php if (isset($_SESSION['user'])): ?>
            <h2 class="display-6 fw-bold text-dark">Bienvenido a tu Panel, <?= htmlspecialchars($_SESSION['user']->getNombreCompleto()) ?></h2>
            <p class="fs-5 text-muted">Tienes acceso completo a la gestión de informes, creación de equipos y análisis de rendimiento técnico.</p>
            <a href="index.php?controller=Usuario&action=mostrarDashboard" class="btn btn-success btn-lg px-4 mt-2">
                Ir a mis informes personales
            </a>
        <?php else: ?>
            <h2 class="display-5 fw-bold text-dark">Plataforma de Scouting Profesional</h2>
            <p class="fs-5 text-muted">Explora de forma abierta la base de datos de jugadores y revisa los informes técnicos del fútbol base.</p>
            <div class="alert alert-info d-inline-block p-2 px-3 my-2" role="alert">
                ¿Eres ojeador autorizado? Inicia sesión o regístrate para redactar nuevos informes.
            </div>
            <div class="mt-3">
                <a href="index.php?controller=Login&action=mostrarLogin" class="btn btn-outline-dark btn-lg px-4 me-2">Iniciar Sesión</a>
                <a href="index.php?controller=Usuario&action=mostrarRegistro" class="btn btn-primary btn-lg px-4">Registrarse</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="my-4">
    <hr class="text-muted">
</div>

<h3 class="mb-4 text-secondary fw-semibold">Últimos Jugadores Registrados</h3>

<?php if (!empty($jugadores)): ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        <?php foreach ($jugadores as $jugador): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 bg-white">
                    
                    <img src="<?= Parameters::getRutaWeb() . htmlspecialchars($jugador->getFoto()) ?>"
                         class="card-img-top w-100 object-fit-cover" 
                         alt="Foto de <?= htmlspecialchars($jugador->getNombre()) ?>"
                         style="height: 220px;"> 
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark mb-1">
                            <?= htmlspecialchars($jugador->getNombre() . " " . $jugador->getApellidos()) ?>
                        </h5>
                        
                        <div class="text-muted small mb-3">
                            <span><strong>Fecha nacimiento:</strong> 
                                <?php 
                                    // Formateo seguro independientemente de si viene como String o como DateTime
                                    $fecha = $jugador->getFechaNac();
                                    echo ($fecha instanceof \DateTime) ? $fecha->format('d/m/Y') : date('d/m/Y', strtotime($fecha));
                                ?>
                            </span>
                        </div>
                        
                        <a href="index.php?controller=Jugador&action=verPerfil&id=<?= $jugador->getId() ?>" 
                           class="btn btn-outline-primary btn-sm w-100 mt-auto fw-medium">
                            Ver Historial de Informes
                        </a>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

    </div>
<?php else: ?>
    <div class="alert alert-warning text-center p-4 shadow-sm" role="alert">
        <h4 class="alert-heading fw-bold">No hay jugadores</h4>
        <p class="mb-0 text-muted">Actualmente no existen perfiles de jugadores registrados en el sistema.</p>
    </div>
<?php endif; ?>