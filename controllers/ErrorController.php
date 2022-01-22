<?php 
class ErrorController
{
    public function index(){
        $view = '404';
        include VIEW_ROUTE.'template.php';
    }
}
