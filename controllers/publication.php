<?php
class PublicationController extends DataBase
{
    public function index()
    {
        try {
            $sql = "SELECT publicaciones.*, usuarios.nombre, usuarios.apellido, usuarios.rol FROM publicaciones INNER JOIN usuarios ON usuarios.id = publicaciones.usuario_id WHERE publicaciones.deleted = 0";
            $statement = $this->prepare($sql);
            $statement->execute();
            $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
            echo json_encode(array("status" => "500", "message" => "Error en el servidor"));
            exit();
        }
        if (isset($_SESSION['system']) && $_SESSION['system'] == "system" || !isset($_SESSION['token']) && $_SESSION['token'] == "") {
            $data = array("view" => "list_publication", "token" => $_SESSION['token'], "res" => $res);
            include VIEW_ROUTE . 'template.php';
        } else {
            echo json_encode(array("status" => "200", "result" => $res));
        }
    }
    public function show($id)
    {
        try {

            if ($id != "") {
                $sql = "SELECT * FROM publicaciones WHERE deleted = 0 and id = :id";
                $statement = $this->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_STR);
                $statement->execute();
                $res = $statement->fetch(PDO::FETCH_ASSOC);
                $view = 'edit_publication';
            } else {
                $sql = "SELECT publicaciones.*, usuarios.nombre, usuarios.apellido, usuarios.rol FROM publicaciones INNER JOIN usuarios ON usuarios.id = publicaciones.usuario_id WHERE publicaciones.deleted = 0";
                $statement = $this->prepare($sql);
                $statement->execute();
                $res = $statement->fetchAll(PDO::FETCH_ASSOC);
                $view = 'list_publication';
            }
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
            echo json_encode(array("status" => "500", "message" => "Error en el servidor"));
            exit();
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
            echo json_encode(array("status" => "200", "message" => "La api no existe"));
        }
    }
    public function registro()
    {
        if (isset($_SESSION['system']) && $_SESSION['system'] == "system" || !isset($_SESSION['token']) && $_SESSION['token'] == "") {
            $data = array("view" => "edit_publication", "token" => isset($_SESSION['token']) ? $_SESSION['token'] : "", "res" => "");
            include VIEW_ROUTE . 'template.php';
        } else {
            echo json_encode(array("status" => "200", "message" => "Registro de Publicaci??n"));
        }
    }
    public function registro_data($Request)
    {
        try {
            $input = $Request;
            if ($input['Titulo'] == "" || $input['Descripcion'] == "") {
                echo json_encode(array("status" => "400", "message" => "Algun valor esta vacio, o el parametro no es valido"));
                exit();
            }
            $sql = "INSERT INTO publicaciones (titulo, descripcion, usuario_id) VALUES (:titulo, :descripcion, :usuario_id)";
            $statement = $this->prepare($sql);
            $statement->bindParam(':titulo', $input['Titulo'], PDO::PARAM_STR);
            $statement->bindParam(':descripcion', $input['Descripcion'], PDO::PARAM_STR);
            $statement->bindParam(':usuario_id', $_SESSION['user_id'], PDO::PARAM_STR);
            $statement->execute();
            header("HTTP/1.1 201 OK");
            echo json_encode(array("status" => "201", "message" => "Publicaci??n creada de alta de manera correcta"));
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
            echo json_encode(array("status" => "500", "message" => "Error en el servidor"));
        }
    }
    public function edit_publication($Request, $id)
    {
        try {
            $input = $Request;
            if ($input['Titulo'] == "" || $input['Descripcion'] == "") {
                echo json_encode(array("status" => "400", "message" => "Algun valor esta vacio, o el parametro no es valido"));
                exit();
            }
            $sql = "UPDATE publicaciones SET titulo = :titulo, descripcion = :descripcion WHERE deleted = 0 and id = :id";
            $statement = $this->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_STR);
            $statement->bindParam(':titulo', $input['Titulo'], PDO::PARAM_STR);
            $statement->bindParam(':descripcion', $input['Descripcion'], PDO::PARAM_STR);
            $statement->execute();
            header("HTTP/1.1 200 OK");
            echo json_encode(array("status" => "200", "message" => "Publicaci??n actualizada de manera correcta"));
        } catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
            echo json_encode(array("status" => "500", "message" => "Error en el servidor"));
        }
    }
    public function delete_publication($id)
    {
        try {
            if($id!=""){
                $sql = "UPDATE publicaciones SET deleted = 1 WHERE publicaciones.deleted = 0 and id = :id";
                $statement = $this->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_STR);
                $statement->execute();
                header("HTTP/1.1 200 OK");
                echo json_encode(array("status" => "200", "message" => "publicaci??n eliminada de manera correcta"));
            }else{
                header("HTTP/1.1 404 OK");
                echo json_encode(array("status" => "404", "message" => "No existe la publicaci??n a eliminar"));
            }
        } catch (\Throwable $th) {
            header("HTTP/1.1 500 OK");
            echo json_encode(array("status" => "500", "message" => "Error en el servidor"));
        }
    }
}
