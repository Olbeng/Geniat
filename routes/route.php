<?php

use \Firebase\JWT\JWT;

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
        
        if($paths[0]=="api"){
            $level = $paths[0];
            $level0 = $paths[1];
            $level1 = $paths[2];
            $level2 = $paths[3];
        }else{
            $level = "system";
            $level0 = $paths[0];
            $level1 = $paths[1];
            $level2 = $paths[2];
        }

        $params = isset($level2) ? $level2 : "";
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
                $res = array_key_exists($level0, $this->_controllers);
                if ($res != "" && $res == "1") {
                    foreach ($this->_controllers as $route => $controller_name) {
                        if ($route == $level0) {
                            $controller = $controller_name;
                        }
                    }
                    $method = isset($level1) && !in_array($level1, $login_methods_into) ? $level1 : "index";
                    // $method = isset($level1) ? $level1 : "index";
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
                if (in_array($level0, $login_methods) || in_array($level1, $login_methods)) {
                    if (isset($level1)) {
                        $method = $level1;
                    } else {
                        $method = $level0;
                    }
                } else {
                    $method = "Login";
                }
            }
        }
        
        $method_rol = $method == "" ? "index" : $method;
        $method = $this->app_rol($method_rol);
        if ($method == "null") {
            $controller = "BaseController";
            $method = "index";
        }
        // session_destroy();
        if (isset($_SESSION['token']) && $_SESSION['token'] != "") {
            $BearerToken = $this->getBearerToken();
            $BearerToken = isset($_SESSION['system']) && $_SESSION['system'] == "system" ? $_SESSION['token'] : $BearerToken;
            
            $token_data = $this->verification($BearerToken);
            if ($token_data['status'] == "401") {
                echo json_encode(array("error" => "Token No valido"));//$token_data['message']
                session_destroy();
                exit();
            }
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
        } else {
            $roles_method = array("Error404", "Login", "registro", "ingreso_data", "registro_data");
        }
        if (in_array($method, $roles_method)) {
            $methodReturn = $method;
        }
        return $methodReturn;
    }
    public function verification($token)
    {
        $jwt = "$token"; //$_SESSION['token']; // emitido token
        $message = "";
        $status = "200";
        try {
            JWT::$leeway = 60; // hora actual menos 60, deje el tiempo para salir de la habitación
            $decoded = JWT::decode($jwt, SECRET_KEY, ['HS256']); // Método HS256, aquí debería estar y emitido
            $arr = (array) $decoded;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  // la firma es incorrecta
            $message = $e->getMessage();
            $status = "401";
        } catch (\Firebase\JWT\BeforeValidException $e) {  // Firma después de un punto en el tiempo
            $message = $e->getMessage();
            $status = "401";
        } catch (\Firebase\JWT\ExpiredException $e) {  // Token expiró
            $message = $e->getMessage();
            $status = "401";
        } catch (Exception $e) {  // otros errores
            $message = $e->getMessage();
            $status = "401";
        }
        return array("status" => $status, "message" => $message);
        // Firsebase define el lanzamiento múltiple nuevo, podemos capturar múltiples capturas para definir problemas, la captura se une a su propio negocio, como el vencimiento del token, puede usar el token actual para actualizar un nuevo token
    }

    function getAuthorizationHeader()
    {
        $headers = null;
        
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    /**
     * get access token from header
     * */
    function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
