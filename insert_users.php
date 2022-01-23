<?php
include "config.php";

$Connection =  connect();

/* listar todos los posts o solo uno */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        //Mostrar un post
        $sql = $Connection->prepare("SELECT * FROM usuarios where id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
        exit();
    } else {
        //Mostrar lista de post
        $sql = $Connection->prepare("SELECT * FROM usuarios");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
        exit();
    }
}

// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = $_POST;
    $sql = "INSERT INTO usuarios (nombre, apellido, correo, password, rol) VALUES (:nombre, :apellido, :correo, :password, :rol)";
    $statement = $Connection->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $user_id = $Connection->lastInsertId();
    if ($user_id) {
        $input['id'] = $user_id;
        header("HTTP/1.1 200 OK");
        echo json_encode($input);
        exit();
    }
}

//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = $_GET['id'];
    $statement = $Connection->prepare("DELETE FROM usuarios where id=:id");
    $statement->bindValue(':id', $id);
    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}

//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = $_GET;
    $user_id = $input['id'];
    $fields = getParams($input);

    $sql = "UPDATE usuarios SET $fields WHERE id='$user_id' ";
    $statement = $Connection->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}


//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
