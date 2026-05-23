<?php
namespace Sfouter\src\controllers;


class ErroresController extends BaseController {

    public function error404() {
        $datos = [
            'titulo' => 'Página no encontrada - Error 404',
            'mensaje' => 'El recurso que estás buscando no existe o ha sido movido por el administrador.'
        ];

        // Renderiza una vista limpia de error
        $this->renderizar("error/404", $datos);
    }

    public function error500() {
            $datos = [
                'titulo' => 'Error Interno del Servidor - Error 500',
                'mensaje' => 'Ha ocurrido un problema inesperado en el servidor. Estamos trabajando para solucionarlo.'
            ];
            // Renderiza una vista específica para errores críticos
            $this->renderizar("error/500", $datos);
        }
}



?> 