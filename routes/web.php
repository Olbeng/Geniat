<?php
$route_web = new Routes();
$controller_data = array(
    "/" => "BaseController",
    "users" => "UserController",
    "publications" => "PublicationController",
);
$route_web->controllers($controller_data);
