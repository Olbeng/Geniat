<?php
class BaseController extends DataBase
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
            $data = array("view" => "dash", "token" => $_SESSION['token'], "res" => $res);
            include VIEW_ROUTE . 'template.php';
        } else {
            echo json_encode(array("status" => "200", "message" => "Acceso pagina de inicio"));
        }
    }
}
