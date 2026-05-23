<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= \Sfouter\config\Parameters::$BASE_URL ?>assets/css/style.css">
    <title><?= $titulo ?? 'Sfouter' ?></title>
</head>
<body>
    
    <?php 
        // El controlador ya sabe dónde están las vistas, solo incluimos las partes
        require_once \Sfouter\config\Parameters::viewsPath() . "layouts/Header.php"; 
    ?> 



    <main>
        <?= $contenido ?>
    </main>

    <?php 
        require_once \Sfouter\config\Parameters::viewsPath() . "layouts/Footer.php"; 
    ?>

</body>
</html>