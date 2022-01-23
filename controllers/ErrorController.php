<?php 
class ErrorController
{
    public function index(){
        if (isset($_SESSION['system']) && $_SESSION['system'] == "system" || !isset($_SESSION['token']) && $_SESSION['token'] == "") {
            $data = array("view" => "404", "token" => $_SESSION['token']);
            include VIEW_ROUTE.'template.php';
        } else {
            echo json_encode(array("status" => "200", "message" => "La api no existe"));
        }        
    }
}
