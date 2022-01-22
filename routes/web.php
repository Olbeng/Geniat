<?php 
$route_web = new Routes();
$route_web->controllers(array(
    "/" => "BaseController",
    "users" => "UserController"
));