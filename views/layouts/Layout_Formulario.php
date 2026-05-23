<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Formulario' ?> </title>
</head>
<body>



<main>
    <?= $contenido ?> 
</main>

<footer>
<footer>
    <?php if (isset($MostrarRegistro) && $MostrarRegistro): ?> 
        <p>
            <a href="<?= Parameters::viewsPath ?>register/Register.php"> Regístrate </a>
        </p>
    <?php endif; ?>
</footer>
 
</footer>
    
</body>
</html>