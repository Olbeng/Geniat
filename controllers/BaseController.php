<?php 
class BaseController extends DataBase
{
    public function index(){
        try {
            $sql = "SELECT * FROM usuarios WHERE deleted = 0";
            $statement = $this->prepare($sql);       
            $statement->execute();
            $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        }  catch (PDOExecption $e) {
            header("HTTP/1.1 500 OK");
        }

        $data = array("view" => "dash", "token" => $_SESSION['token'], "res"=>$res);
        include VIEW_ROUTE . 'template.php';
    }
}
