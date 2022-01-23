<?php
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

        $data = array("view" => "list_user", "token" => $_SESSION['token'], "res" => $res);
        include VIEW_ROUTE . 'template.php';
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


        $data = array("view" => $view, "token" => $_SESSION['token'], "res" => $res);
        include VIEW_ROUTE . 'template.php';
    }
    public function Error404()
    {
        $data = array("view" => "404", "token" => $_SESSION['token']);
        include VIEW_ROUTE . 'template.php';
    }
    public function Login()
    {
        $data = array("view" => "login", "token" => '');
        include VIEW_ROUTE . 'template.php';
    }
    public function registro()
    {
        $data = array("view" => "register", "token" => isset($_SESSION['token']) ? $_SESSION['token'] : "");
        include VIEW_ROUTE . 'template.php';
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
                    $_SESSION['token'] = '1';
                    $_SESSION['user_id'] = $data_user['id'];
                    $_SESSION['user_rol'] = $data_user['rol'];
                    $_SESSION['user_name'] = $data_user['nombre']." ".$data_user['apellido'];
                    echo json_encode(array("status" => "200", "message" => "acceso autorizado"));
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
                }
            } else {
                echo json_encode(array("status" => "400", "message" => "El correo ya esta dado de alta"));
                exit();
            }
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
        }
        echo json_encode(array("status" => "201", "message" => "Usuario dado de alta de manera correcta"));
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
        if($id==""){
            $sql = "SELECT * FROM usuarios WHERE deleted = 0 and correo = :correo";
            $statement = $this->prepare($sql);
            $statement->bindParam(':correo', $input['Correo'], PDO::PARAM_STR);
        }else{            
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
        header('Location:'.ROUTE_SYSTEM);
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
            } else {
                echo json_encode(array("status" => "400", "message" => "El correo ya esta dado de alta"));
                exit();
            }
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
        }
        
        echo json_encode(array("status" => "200", "message" => "Usuario actualizado de manera correcta"));
    }
    public function delete_user($id)
    {
        try {
            $sql = "UPDATE usuarios SET deleted = 1 WHERE deleted = 0 and id = :id";
            $statement = $this->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_STR);
            $statement->execute();
            header("HTTP/1.1 200 OK");
            echo json_encode(array("status" => "200", "message" => "Usuario eliminado de manera correcta"));
        } catch (\Throwable $th) {
            header("HTTP/1.1 500 OK");
        }
    }
}
