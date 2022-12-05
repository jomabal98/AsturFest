<?php

require_once __DIR__ . '/src/Db.php';

$db = new Db();
if (!$db->checkDataBase()) {
    echo $db->getLastError();
}

//create table user
$db->setTable('user');
$atr = ["id" => "smallint auto_increment PRIMARY KEY", "rol" => "smallint", "name" => "varchar(30)", "mail" => "varchar(30)", "age" => "smallint", "favs" => "varchar(255)"];
if (!$db->checkTable($atr)) {
    echo $db->getLastError();
}

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
