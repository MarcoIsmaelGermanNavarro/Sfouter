<?php

// Es la prueba para ver si funciona todo de manera correcta. 

namespace Sfouter\controllers;

// El use para saber donde esta el padre, para no poner toda la ruta 
// en el extends. 
use Sfouter\controllers\BaseController;
use Sfouter\models\repositories\JugadorRepository;
/* Las vistas no son clases no pueden usar Use, ya el padre se encarga en este caso de 
de asignarle las rutas de la vista a la que tiene en este caso que acceder. 
use Sfouter\views\home; 
*/
class HomeController extends BaseController {


// En este caso esta es la funcion que pone el codinero, para poder mostrar en este caso el contenido no?
// Como Home Controller esta usando la herencia todos los meyoso publico sy protegisdos del padre le pertencen. 
// Es como si el padre le hubiera dado una caja de herramientas (métpos),. 
// El hijp abre la caja al hacver $this-> renderizar, el hijo abre la caja que el padre en este caso fabrico. 
// Los parametros que le pasas ('home/home' y $datos), son el material que la herramienta necesitab para trabajar.

// ¿Porque la funcion en este caso se llama Index?
public function index() {
  
        
    $jugadorRepo = new JugadorRepository(); 
    $listaJugadores = $jugadorRepo->cargarTodo();
    
    $datos = [ 
        'titulo'    => "Panel de Control", 
        'jugadores' => $listaJugadores, 
        ]; 
        

    $this->renderizar('home/Home', $datos); 
}



}



?> 