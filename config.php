<?php 
define("APP_NAME", "GENIAT");
define("DB_HOST", "162.241.61.203");
define("DB_USERNAME", "emprepay_geniat");
define("DB_PASSWORD", "0S4nd_pIq[ms");
define("DB_DATABASE_NAME", "emprepay_geniat");
define("VIEW_ROUTE", BASE_ROUTE."views/");
define("ROUTE",BASE_ROUTE."routes/");
$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
define("ROUTE_SYSTEM",$protocol.$_SERVER['HTTP_HOST']."/");

include ROUTE."route.php";
include ROUTE."web.php";
