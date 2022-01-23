<?php 
class ErrorController
{
    public function index(){
        $data = array("view" => "404", "token" => $_SESSION['token']);
        include VIEW_ROUTE.'template.php';
    }
}
