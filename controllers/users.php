<?php

use \Firebase\JWT\JWT;

class UserController extends DataBase
{
    public function index()
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE deleted = 0";
            $statement = $this->prepare($sql);
            $statement->execute();
            $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
        }

        if (isset($_SESSION['system']) && $_SESSION['system'] == "system" || !isset($_SESSION['token']) && $_SESSION['token'] == "") {
            $data = array("view" => "list_user", "token" => $_SESSION['token'], "res" => $res);
            include VIEW_ROUTE . 'template.php';
        } else {
            echo json_encode(array("status" => "200", "message" => "Acceso pagina de inicio"));
        }
    }
    public function show($id)
    {
        try {
            if ($id != "") {
                $sql = "SELECT * FROM usuarios WHERE deleted = 0 and id = :id";
                $statement = $this->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_STR);
                $statement->execute();
                $res = $statement->fetch(PDO::FETCH_ASSOC);
                $view = 'edit_user';
            } else {
                $sql = "SELECT * FROM usuarios WHERE deleted = 0";
                $statement = $this->prepare($sql);
                $statement->execute();
                $res = $statement->fetchAll(PDO::FETCH_ASSOC);
                $view = 'list_user';
            }
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
        }

        if (isset($_SESSION['system']) && $_SESSION['system'] == "system" || !isset($_SESSION['token']) && $_SESSION['token'] == "") {
            $data = array("view" => $view, "token" => $_SESSION['token'], "res" => $res);
            include VIEW_ROUTE . 'template.php';
        } else {
            echo json_encode(array("status" => "200", "result" => $res));
        }
    }
    public function Error404()
    {
        if (isset($_SESSION['system']) && $_SESSION['system'] == "system" || !isset($_SESSION['token']) && $_SESSION['token'] == "") {
            $data = array("view" => "404", "token" => $_SESSION['token']);
            include VIEW_ROUTE . 'template.php';
        } else {
            echo json_encode(array("status" => "404", "error" => "api no existe"));
        }
    }
    public function Login()
    {
        if (isset($_SESSION['system']) && $_SESSION['system'] == "system" || !isset($_SESSION['token']) && $_SESSION['token'] == "") {
            $data = array("view" => "login", "token" => '');
            include VIEW_ROUTE . 'template.php';
        } else {
            echo json_encode(array("status" => "200", "message" => "Login"));
        }
    }
    public function registro()
    {
        if (isset($_SESSION['system']) && $_SESSION['system'] == "system" || !isset($_SESSION['token']) && $_SESSION['token'] == "") {
            $data = array("view" => "register", "token" => isset($_SESSION['token']) ? $_SESSION['token'] : "");
            include VIEW_ROUTE . 'template.php';
        } else {
            echo json_encode(array("status" => "200", "message" => "Registro"));
        }
    }
    public function ingreso_data($Request)
    {
        try {
            $input = $Request;
            if ($input['Correo'] == "" || $input['Password'] == "") {
                echo json_encode(array("status" => "400", "message" => "Algun valor esta vacio, o el parametro no es valido"));
                exit();
            }
            if ($this->is_valid_email($input['Correo']) != "1") {
                echo json_encode(array("status" => "400", "message" => "El correo no es valido"));
                exit();
            }
            $data_user = $this->valid_user($input);
            if ($data_user) {
                $encryp = crypt($input['Password'], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                if ($encryp == $data_user['password'] && $input['Correo'] == $data_user['correo']) {
                    $name_user = $data_user['nombre'] . " " . $data_user['apellido'];
                    $token = $this->token($data_user['id'], $data_user['rol'],  $name_user);
                    $_SESSION['user_id'] = $data_user['id'];
                    $_SESSION['user_rol'] = $data_user['rol'];
                    $_SESSION['user_name'] = $name_user;
                    if (isset($input['login']) && $input['login'] == "system") {
                        $_SESSION['system'] = "system";
                    }
                    $_SESSION['token'] = $token;
                    echo json_encode(array("status" => "200", "jwt" => $token, "message" => "acceso autorizado"));
                    exit();
                } else {
                    echo json_encode(array("status" => "400", "message" => "acceso denegado"));
                    exit();
                }
            } else {
                echo json_encode(array("status" => "400", "message" => "El usuario no existe"));
                exit();
            }
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
            echo json_encode(array("status" => "500", "message" => "Error en el servidor"));
            exit();
        }
    }
    public function registro_data($Request)
    {
        try {
            $input = $Request;
            if ($input['Nombre'] == "" || $input['Apellido'] == "" || $input['Correo'] == "" || $input['Password'] == "" || $input['Rol'] == "") {
                echo json_encode(array("status" => "400", "message" => "Algun valor esta vacio, o el parametro no es valido"));
                exit();
            }
            if ($this->is_valid_email($input['Correo']) != "1") {
                echo json_encode(array("status" => "400", "message" => "El correo no es valido"));
                exit();
            }
            if (!$this->exist($input, '')) {
                $encryp = crypt($input['Password'], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                $sql = "INSERT INTO usuarios (nombre, apellido, correo, password, rol) VALUES (:nombre, :apellido, :correo, :password, :rol)";
                $statement = $this->prepare($sql);
                $statement->bindParam(':nombre', $input['Nombre'], PDO::PARAM_STR);
                $statement->bindParam(':apellido', $input['Apellido'], PDO::PARAM_STR);
                $statement->bindParam(':correo', $input['Correo'], PDO::PARAM_STR);
                $statement->bindParam(':password', $encryp, PDO::PARAM_STR);
                $statement->bindParam(':rol', $input['Rol'], PDO::PARAM_STR);
                $statement->execute();
                $user_id = $this->lastInsertId();
                if ($user_id) {
                    header("HTTP/1.1 201 OK");                    
                    echo json_encode(array("status" => "201", "message" => "Usuario dado de alta de manera correcta"));
                }
            } else {
                echo json_encode(array("status" => "400", "message" => "El correo ya esta dado de alta"));
                exit();
            }
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
            echo json_encode(array("status" => "500", "message" => "Error en el servidor"));
        }
    }
    public function valid_user($input)
    {
        $sql = "SELECT * FROM usuarios WHERE deleted = 0 and correo = :correo";
        $statement = $this->prepare($sql);
        $statement->bindParam(':correo', $input['Correo'], PDO::PARAM_STR);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }
    public function exist($input, $id)
    {
        if ($id == "") {
            $sql = "SELECT * FROM usuarios WHERE deleted = 0 and correo = :correo";
            $statement = $this->prepare($sql);
            $statement->bindParam(':correo', $input['Correo'], PDO::PARAM_STR);
        } else {
            $sql = "SELECT * FROM usuarios WHERE deleted = 0 and correo = :correo and id != :id ";
            $statement = $this->prepare($sql);
            $statement->bindParam(':correo', $input['Correo'], PDO::PARAM_STR);
            $statement->bindParam(':id', $id, PDO::PARAM_STR);
        }
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            return true;
        } else {
            return false;
        }
    }
    public function is_valid_email($str)
    {
        return (false !== strpos($str, "@") && false !== strpos($str, "."));
    }
    public function logout($Request)
    {        
        session_destroy();
        if (isset($_SESSION['system']) && $_SESSION['system'] == "system" || !isset($_SESSION['token']) && $_SESSION['token'] == "") {
            header('Location:' . ROUTE_SYSTEM);
        } else {
            echo json_encode(array("status" => "200", "message" => "sessión terminada"));
        }
    }
    public function edit_user($Request, $id)
    {
        try {
            $input = $Request;
            if ($input['Nombre'] == "" || $input['Apellido'] == "" || $input['Correo'] == "" || $input['Password'] == "" || $input['Rol'] == "") {
                echo json_encode(array("status" => "400", "message" => "Algun valor esta vacio, o el parametro no es valido"));
                exit();
            }

            if ($this->is_valid_email($input['Correo']) != "1") {
                echo json_encode(array("status" => "400", "message" => "El correo no es valido"));
                exit();
            }

            if (!$this->exist($input, $id)) {

                $encryp = crypt($input['Password'], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, correo = :correo, password = :password, rol = :rol WHERE deleted = 0 and id = :id";
                $statement = $this->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_STR);
                $statement->bindParam(':nombre', $input['Nombre'], PDO::PARAM_STR);
                $statement->bindParam(':apellido', $input['Apellido'], PDO::PARAM_STR);
                $statement->bindParam(':correo', $input['Correo'], PDO::PARAM_STR);
                $statement->bindParam(':password', $encryp, PDO::PARAM_STR);
                $statement->bindParam(':rol', $input['Rol'], PDO::PARAM_STR);
                $statement->execute();
                header("HTTP/1.1 200 OK");                
                echo json_encode(array("status" => "200", "message" => "Usuario actualizado de manera correcta"));
            } else {
                echo json_encode(array("status" => "400", "message" => "El correo ya esta dado de alta"));
                exit();
            }
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
            echo json_encode(array("status" => "500", "message" => "Error en el servidor"));
        }
    }
    public function delete_user($id)
    {
        try {
            if($id!=""){
                $sql = "UPDATE usuarios SET deleted = 1 WHERE deleted = 0 and id = :id";
                $statement = $this->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_STR);
                $statement->execute();
                header("HTTP/1.1 200 OK");
                echo json_encode(array("status" => "200", "message" => "Usuario eliminado de manera correcta"));
            }else{
                header("HTTP/1.1 404 OK");
                echo json_encode(array("status" => "404", "message" => "No existe el usuario a eliminar"));
            }
        } catch (\Throwable $th) {
            header("HTTP/1.1 500 OK");
            echo json_encode(array("status" => "500", "message" => "Error en el servidor"));
        }
    }
    public function token($id, $rol, $name_user)
    {
        $secret_key = SECRET_KEY;
        $issuedat_claim = time(); // issued at
        $token = array(
            "iat" => $issuedat_claim,
            "exp" => $issuedat_claim + (60 * 60), // Tiempo que expirará el token (+1 hora)
            "data" => array(
                "id" => $id,
                "rol" => $rol,
                "name_user" => $name_user
            )
        );

        $jwt = JWT::encode($token, $secret_key);
        return $jwt;
    }
}
