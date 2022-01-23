<?php
include BASE_ROUTE . "Database.php";
include BASE_ROUTE . "controllers/users.php";
include BASE_ROUTE . "controllers/ErrorController.php";
include BASE_ROUTE . "controllers/BaseController.php";
include BASE_ROUTE . "controllers/publication.php";
class Routes
{
    //método que nos permitira ingresar a los controladores con sus respectiva ruta asignada
    private $_controllers = array();
    public function controllers($_controller)
    {
        $this->_controllers = $_controller;
        //Llamada al método que hace el proceso de rutas
        self::submit();
    }
    //Función o método que se ejecuta cada vez que se envía la petición en la url
    public function submit()
    {
        $login_methods = array("Login", "registro", "ingreso_data", "registro_data");
        $login_methods_into = array("Login", "ingreso_data");
        $uri = isset($_GET["uri"]) ? $_GET["uri"] : "/";
        $paths = explode("/", $uri);
        $params = isset($paths[2]) ? $paths[2] : "";
        if (isset($_SESSION['token']) && $_SESSION['token'] != "") {
            if ($uri == "/") {
                $res = array_key_exists("/", $this->_controllers);
                if ($res != "" && $res == "1") {
                    foreach ($this->_controllers as $route => $controller_name) {
                        if ($route == "/") {
                            $controller = $controller_name;
                        }
                    }
                }
                $method = "index";
            } else {
                $res = array_key_exists($paths[0], $this->_controllers);
                if ($res != "" && $res == "1") {
                    foreach ($this->_controllers as $route => $controller_name) {
                        if ($route == $paths[0]) {
                            $controller = $controller_name;
                        }
                    }
                    $method = isset($paths[1]) && !in_array($paths[1], $login_methods_into) ? $paths[1] : "index";
                    // $method = isset($paths[1]) ? $paths[1] : "index";
                } else {
                    $controller = "ErrorController";
                    $method = "index";
                }
            }
        } else {
            $controller = "UserController";
            if ($uri == "/") {
                $method = "Login";
            } else {
                if (in_array($paths[0], $login_methods) || in_array($paths[1], $login_methods)) {
                    if (isset($paths[1])) {
                        $method = $paths[1];
                    } else {
                        $method = $paths[0];
                    }
                } else {
                    $method = "Login";
                }
            }
        }
        $method_rol = $method == "" ? "index" : $method;
        $method = $this->app_rol($method_rol);
        if($method=="null"){
            $controller = "BaseController";
            $method = "index";
        }
        $this->getController($controller, $method, $params);
    }
    public function getController($controller, $method, $params)
    {
        $controller_class = new $controller();
        if (method_exists($controller_class, $method)) {
            switch ($_SERVER['REQUEST_METHOD']) {
                case "GET":
                    $controller_class->$method($params);
                    break;
                case "POST":
                    $controller_class->$method($_REQUEST);
                    break;
                case "PUT":
                    parse_str(file_get_contents("php://input"), $put_vars);
                    $controller_class->$method($put_vars, $params);
                    break;
                case "DELETE":
                    $controller_class->$method($params);
                    break;
            }
            // $controller_class->$method();
        } else {
            $controller_class->Error404();
        }
    }
    public function app_rol($method)
    {         
        $methodReturn = "null";
        if ($_SESSION['user_rol'] == "Rol básico") {
            $roles_method = array("Error404", "logout");
        } else if ($_SESSION['user_rol'] == "Rol medio") {
            $roles_method = array("index", "Error404", "logout");
        } else if ($_SESSION['user_rol'] == "Rol medio alto") {
            $roles_method = array("index", "Error404", "registro", "registro_data", "logout");
        } else if ($_SESSION['user_rol'] == "Rol alto medio") {
            $roles_method = array("index", "show", "Error404", "registro", "registro_data", "logout", "edit_user", "edit_publication");
        } else if ($_SESSION['user_rol'] == "Rol alto") {
            $roles_method = array("index", "show", "Error404", "registro", "registro_data", "logout", "edit_user", "delete_user", "edit_publication", "delete_publication");
        }else{
            $roles_method = array("Error404", "Login", "registro", "ingreso_data", "registro_data");
        }
        if(in_array($method, $roles_method)){
            $methodReturn = $method;
        }
        return $methodReturn;
    }
}
