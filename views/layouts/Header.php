<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sfouter - Scouting Profesional</title>
    <link href="<?= \Sfouter\config\Parameters::getBaseUrl() ?>assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="<?= \Sfouter\config\Parameters::getBaseUrl() ?>assets/bootstrap/bootstrap.bundle.min.js" defer></script>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="<?= \Sfouter\config\Parameters::getBaseUrl() ?>assets/img/Logo.png" 
                 alt="Sfouter Logo" style="height: 40px; width: auto;">
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2 text-center">
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="index.php?controller=Jugador&action=listarJugadores">Lista Jugadores</a>
                </li>
                
                <?php if (isset($_SESSION['user'])): 
                    // Guardamos el rol en una variable para limpiar el HTML y evitar errores de acceso
                    $rol = $_SESSION['user']->getRol();
                ?>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="index.php?controller=Jugador&action=FormularioCrear">Registrar Jugador</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="index.php?controller=Equipo&action=mostrarFormulario">Añadir Equipo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="index.php?controller=Usuario&action=mostrarDashboard">Mis Informes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="index.php?controller=Usuario&action=editar">Mi Perfil</a>
                    </li>
                    
                    <?php if($rol === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link fw-medium text-warning" href="index.php?controller=Usuario&action=gestionRoles">Gestión Roles</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-danger btn-sm px-3 fw-bold" href="index.php?controller=Usuario&action=logOut">Cerrar Sesión</a>
                    </li>
                
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="index.php?controller=Login&action=mostrarLogin">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary btn-sm px-3 fw-bold" href="index.php?controller=Usuario&action=mostrarRegistro">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container my-5 flex-grow-1">
    
    <?php if(isset($_SESSION['Success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <span class="fw-medium"> <?= $_SESSION['Success']; ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['Success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['Error_Global'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <span class="fw-medium"> <?= $_SESSION['Error_Global']; ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['Error_Global']); ?>
        </div>
    <?php endif; ?>

  <?php if(isset($_SESSION['Errores'])): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <ul class="mb-0 ps-3">
            <?php 
            // 1. Normalizamos: si es un string, lo metemos en un array para iterar igual
            $mensajesError = is_array($_SESSION['Errores']) ? $_SESSION['Errores'] : [$_SESSION['Errores']];
            
            // 2. Mostramos cada mensaje
            foreach($mensajesError as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['Errores']); // Limpiamos la sesión ?>
<?php endif; ?>