<?php

require_once __DIR__ . '/src/Db.php';

$db = new Db();
if (!$db->checkDataBase()) {
    echo $db->getLastError();
}

//create table user
$db->setTable('user');
$atr = ["id" => "smallint auto_increment PRIMARY KEY", "rol" => "smallint", "name" => "varchar(30)", "password" => "varchar(16)", "mail" => "varchar(30)", "age" => "smallint", "favs" => "varchar(255)"];
if (!$db->checkTable($atr)) {
    echo $db->getLastError();
}

$query = $db->getQuery('INSERT', ["rol" => 1, "name" => "Admin", "password" => "admin", "mail" => "admin", "age" => "24", "favs" => "admin"]);
if (!$query) {
    die($db->getLastError());
}

//insert admin
$resultado = $db->executeS($query);
if ($resultado) {
    echo "Consulta correctamente en " . $db->getTable() . "<hr>";
} else {
    echo $db->getLastError();
}

//create table event
$db->setTable('event');
$atr = ["id" => "smallint auto_increment PRIMARY KEY", "name" => "varchar(30)", "date_init" => "date", "date_end" => "date", "place" => "varchar(50)", "type" => "varchar(20)", "photo" => "varchar(50)"];
if (!$db->checkTable($atr)) {
    echo $db->getLastError();
}

$data = file_get_contents("src/data.json");
$data = json_decode($data, true);
$ins = [];
$query = '';
foreach ($data as $event) {
    $ins = [];
    foreach ($event as $key => $values) {
        $ins += [$key => $values];
    }

    $query .= $db->getQuery('INSERT', $ins);
}

if ($query == false) {
    die($db->getLastError());
}

$resultado = $db->executeS($query);
if ($resultado) {
    echo "Consulta correctamente en " . $db->getTable() . "<hr>";
} else {
    echo $db->getLastError();
}
