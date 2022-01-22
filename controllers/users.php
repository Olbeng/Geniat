<?php 
class UserController
{
    public function index(){
        $view = 'list_user';
        include VIEW_ROUTE.'template.php';
    }
    public function show(){
        $view = 'detail_user';
        include VIEW_ROUTE.'template.php';
    }
    public function Error404(){
        $view = '404';
        include VIEW_ROUTE.'template.php';
    }
}
