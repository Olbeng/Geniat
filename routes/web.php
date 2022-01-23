<?php
$controller_data = array(
    "/" => "BaseController",
    "users" => "UserController",
    "publications" => "PublicationController",
);
$route_web = new Routes();
$route_web->controllers($controller_data);