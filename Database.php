<?php

//Abrir conexion a la base de datos
function connect()
{
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE_NAME, DB_USERNAME, DB_PASSWORD);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    } catch (PDOException $exception) {
        exit($exception->getMessage());
    }
}


//Obtener parametros para updates
function getParams($input)
{
    $filterParams = [];
    foreach ($input as $param => $value) {
        $filterParams[] = "$param=:$param";
    }
    return implode(", ", $filterParams);
}

//Asociar todos los parametros a un sql
function bindAllValues($statement, $params)
{
    foreach ($params as $param => $value) {
        $statement->bindValue(':' . $param, $value);
    }
    return $statement;
}
