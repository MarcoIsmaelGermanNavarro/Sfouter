<form action="index.php?controller=Jugador&action=VerUnSoloJugador" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="number" name="id" placeholder="ID del jugador">
    <button type="submit"> Buscar </button>
</form>


<?php if (isset($errores)) : ?> 

    <div class="alert-danger">

        <p> <?= implode('<br>', $errores) ?> </p>    
    </div>

<?php endif;  ?>

<?php if(isset($j)): ?> 

    <div class="jugador"> 

        <h3><?= $j -> getnombreCompleto() ?> </h3>
        <p> Fecha nacimiento: <?= $j -> getFechaNac() -> format('d/m/Y') ?> </p>
        <!-- Como en este caso ponemos las fotos acuerdaoe que era en este caso las meatiamos, en 
         un repositorio y las metiamso en este caso el nombre de la ruta en src. -->

        <img class="image-card" src="uploads/Fotos/<?= $j -> getFoto() ?> " alt="<?= $j -> getNombreCompleto() ?> ">

    </div>

<?php endif; ?> 

