<?php 
include BASE_ROUTE."controllers/users.php";
include BASE_ROUTE."controllers/ErrorController.php";
include BASE_ROUTE."controllers/BaseController.php";
class Routes
{
    //método que nos permitira ingresar a los controladores con sus respectiva ruta asignada
    private $_controllers = array();
    public function controllers($controller){
        $this->_controllers = $controller;
        //Llamada al método que hace el proceso de rutas
        self::submit();
    }
    //Función o método que se ejecuta cada vez que se envía la petición en la url
    public function submit(){
        $uri = isset($_GET["uri"]) ? $_GET["uri"] : "/";
        $paths = explode("/", $uri);
        if($uri=="/"){
            $res = array_key_exists("/", $this->_controllers);
            if($res!="" && $res =="1"){
                foreach ($this->_controllers as $route => $controller_name) {
                    if($route=="/"){
                        $controller = $controller_name;
                    }
                }
            }            
            $method = "index";
        }else{
            $res = array_key_exists($paths[0], $this->_controllers);
            if($res!="" && $res =="1"){
                foreach ($this->_controllers as $route => $controller_name) {
                    if($route==$paths[0]){
                        $controller = $controller_name;
                    }
                }
                $method = isset($paths[1]) ? $paths[1] : "index";
            }else{
                $controller = "ErrorController";
                $method = "index";
            }
        }
        $method = $method =="" ? "index" : $method;
        $this->getController($controller, $method);
    }
    public function getController($controller, $method){
        $controller_class = new $controller();
        if(method_exists($controller_class,$method)){
            $controller_class->$method();
        }else{
            $controller_class->Error404();
        }
    }
}
