<?php
session_start();
require "vendor/autoload.php";

define("DB_HOST", "localhost");
define("DB_USERNAME", "emprepay_geniat");
define("DB_PASSWORD", "0S4nd_pIq[ms");
define("APP_NAME", "GENIAT");
define("DB_DATABASE_NAME", "emprepay_geniat");
define("VIEW_ROUTE", BASE_ROUTE . "views/");
define("ROUTE", BASE_ROUTE . "routes/");
$protocol = (trim($_SERVER["SERVER_PROTOCOL"]) =="HTTP/1.1" ? "http" : "https") . '://';
define("ROUTE_SYSTEM", $protocol . $_SERVER['HTTP_HOST'] . "/");
define("SECRET_KEY", "geniat-app");

include ROUTE . "route.php";
include ROUTE . "web.php";
