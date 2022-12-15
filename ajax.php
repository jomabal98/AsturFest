<?php
require_once __DIR__ . '/src/PaginationTable.php';
require_once __DIR__ . '/src/Db.php';

session_start();
$action = $_GET['action'];
switch ($action) {
    case 'delete':
        if (!isset($_GET['id']) || $_GET['id'] < 1) {
            echo json_encode(['error' => "id incorrect"]);
        }
        if (!isset($_GET['nameTable']) && !empty($_GET['nameTable'])) {
            echo json_encode(['error' => "nameTable incorrect"]);
        }

        $id = $_GET['id'];
        $db = new Db();
        $db->setTable($_GET['nameTable']);
        $query = $db->getQuery('DELETE', ["value" => "id = {$id}"]);
        $result = $db->executeS($query);
        if (!$result) {
            $result = $db->getLastError();
        } else {
            $result = true;
        }

        if ($_GET['nameTable'] == "event") {
            $field = "idEvent";
        } else {
            $field = "idUser";
        }

        $db->setTable("favorites");
        $query = $db->getQuery('DELETE', ["value" => "{$field} = {$id}"]);
        $result = $db->executeS($query);
        if (!$result) {
            $result = $db->getLastError();
        } else {
            $result = true;
        }

        echo json_encode(['result' => $result]);
        break;

    case 'updateTable':
        $where = "";
        if (isset($_POST['date1']) && !empty($_POST['date1']) && isset($_POST['date2']) && !empty($_POST['date2'])) {
            $where = " (date_init BETWEEN {$_POST['date1']} AND {$_POST['date2']} OR date_end BETWEEN {$_POST['date1']} AND {$_POST['date2']} OR {$_POST['date1']} BETWEEN date_init AND date_end)";
        }

        if (isset($_POST['where']) && !empty($_POST['where'])) {
            if ($where != "") {
                $where = $where . " AND ";
            }
            $where .= "(" . $_POST['where'] . ")";
        }

        if (!isset($_POST['page']) || $_POST['page'] < 1) {
            echo json_encode(['error' => "page incorrect"]);
        }

        if (!isset($_POST['numSelector']) || $_POST['numSelector'] < 1) {
            echo json_encode(['error' => "num selector incorrect"]);
        }

        if (!isset($_POST['orderBy']) || empty($_POST['orderWay'])) {
            echo json_encode(['error' => "orderBy incorrect"]);
        }

        if (!isset($_POST['orderWay']) || empty($_POST['orderWay'])) {
            echo json_encode(['error' => "orderWay incorrect"]);
        }

        if (!isset($_POST['new_columns']) || !is_array($_POST['new_columns'])) {
            $_POST['new_columns'] = ["" => ""];
        }

        if (!isset($_POST['nameTable']) || empty($_POST['nameTable'])) {
            echo json_encode(['error' => "nameTable incorrect"]);
        }

        if (!isset($_POST['fieldsTranslated']) || !is_array($_POST['fieldsTranslated'])) {
            echo json_encode(['error' => "fieldsTranslated incorrect"]);
        }

        if (!isset($_POST['rol']) || empty($_POST['rol'])) {
            echo json_encode(['error' => "rol incorrect"]);
        }

        $paginationTable = new PaginationTable(new Db(), (string) $_POST['rol'], (string) $_POST['nameTable'], (int) $_POST['page'], (int) $_POST['numSelector'], (array) $_POST['new_columns'], (array)$_POST['fieldsTranslated'], (string) $_POST['orderBy'], (string) $_POST['orderWay'], (string)$where);
        $table = $paginationTable->get(true);
        if (!$table) {
            echo json_encode(['error' => $paginationTable->getLastError()]);
            die();
        }

        echo json_encode(['tbody' => $table['tbody'], 'pages' => $table['paginator']]);
        break;

    case 'changeTable':
        if (!isset($_POST['page']) || $_POST['page'] < 1) {
            echo json_encode(['error' => "page incorrect"]);
        }

        if (!isset($_POST['new_columns']) || !is_array($_POST['new_columns'])) {
            echo json_encode(['error' => "new_columns incorrect"]);
        }

        if (!isset($_POST['nameTable']) || empty($_POST['nameTable'])) {
            echo json_encode(['error' => "nameTable incorrect"]);
        }

        if (!isset($_POST['limit']) || $_POST['limit'] < 1) {
            echo json_encode(['error' => "limit incorrect"]);
        }

        if (!isset($_POST['fieldsTranslated']) || !is_array($_POST['fieldsTranslated'])) {
            echo json_encode(['error' => "fieldsTranslated incorrect"]);
        }

        $paginationTable = new PaginationTable(new Db(), (string) $_POST['rol'], (string) $_POST['nameTable'], (int) $_POST['page'], (int) $_POST['limit'], (array) $_POST['new_columns'], (array)$_POST['fieldsTranslated']);
        $table = $paginationTable->get();
        if (!$table) {
            echo json_encode(['error' => $paginationTable->getLastError()]);
            die();
        }

        echo json_encode(['table' => $table]);
        break;

    case 'insertUser':
        if (!isset($_POST['nameTable']) || empty($_POST['nameTable'])) {
            echo json_encode(['error' => "nameTable incorrect"]);
        }

        if (!isset($_POST['mail']) || empty($_POST['mail'])) {
            echo json_encode(['error' => "mail incorrect"]);
        }

        if (!isset($_POST['name']) || empty($_POST['name'])) {
            echo json_encode(['error' => "name incorrect"]);
        }

        if (!isset($_POST['password']) || empty($_POST['password'])) {
            echo json_encode(['error' => "password incorrect"]);
        }

        if (!isset($_POST['age']) || $_POST['age'] < 1) {
            echo json_encode(['error' => "mail incorrect"]);
        }

        $db = new Db();
        $db->setTable($_POST['nameTable']);
        $query = $db->getQuery('INSERT', ["name" => "{$_POST['name']}", "password" => "{$_POST['password']}", "rol" => 0, "mail" => "{$_POST['mail']}", "age" => $_POST['age']]);
        $result = $db->executeS($query);
        if (!$result) {
            $result = $db->getLastError();
        } else {
            $result = true;
        }

        echo json_encode(['result' => $result]);
        break;

    case 'insertEvent':
        if (!isset($_POST['nameTable']) || empty($_POST['nameTable'])) {
            echo json_encode(['error' => "nameTable incorrect"]);
        }

        if (!isset($_POST['place']) || empty($_POST['place'])) {
            echo json_encode(['error' => "place incorrect"]);
        }

        if (!isset($_POST['name']) || empty($_POST['name'])) {
            echo json_encode(['error' => "name incorrect"]);
        }

        if (!isset($_POST['photo']) || empty($_POST['photo'])) {
            echo json_encode(['error' => "photo incorrect"]);
        }

        if (!isset($_POST['type']) || empty($_POST['type'])) {
            echo json_encode(['error' => "type incorrect"]);
        }

        if (!isset($_POST['date_init']) || empty($_POST['date_init'])) {
            echo json_encode(['error' => "date_init incorrect"]);
        }

        if (!isset($_POST['date_end']) || empty($_POST['date_end'])) {
            echo json_encode(['error' => "date_end incorrect"]);
        }

        $db = new Db();
        $db->setTable($_POST['nameTable']);
        $query = $db->getQuery('INSERT', ["name" => "{$_POST['name']}", "date_init" => "{$_POST['date_init']}", "date_end" => "{$_POST['date_end']}", "photo" => "{$_POST['photo']}", "type" => "{$_POST['type']}", "place" => "{$_POST['place']}",]);
        $result = $db->executeS($query);
        if (!$result) {
            $result = $db->getLastError();
        } else {
            $result = true;
        }

        echo json_encode(['result' => $result]);
        break;

    case 'editUser':
        if (!isset($_POST['nameTable']) || empty($_POST['nameTable'])) {
            echo json_encode(['error' => "nameTable incorrect"]);
        }

        if (!isset($_POST['mail']) || empty($_POST['mail'])) {
            echo json_encode(['error' => "mail incorrect"]);
        }

        if (!isset($_POST['name']) || empty($_POST['name'])) {
            echo json_encode(['error' => "name incorrect"]);
        }

        if (!isset($_POST['password']) || empty($_POST['password'])) {
            echo json_encode(['error' => "password incorrect"]);
        }

        if (!isset($_POST['age']) || $_POST['age'] < 1) {
            echo json_encode(['error' => "age incorrect"]);
        }

        if (!isset($_POST['id']) || $_POST['id'] < 1) {
            echo json_encode(['error' => "id incorrect"]);
        }

        $db = new Db();
        $db->setTable($_POST['nameTable']);
        $query = $db->getQuery('UPDATE', ['set' => ['mail' => $_POST['mail'], 'name' => $_POST['name'], 'password' => $_POST['password'], 'age' => $_POST['age']], 'where' => ['id' => $_POST['id']]]);
        $result = $db->executeS($query);
        if (!$result) {
            $result = $db->getLastError();
        } else {
            $result = true;
        }

        echo json_encode(['result' => $result]);
        break;
    case 'editEvent':
        if (!isset($_POST['nameTable']) || empty($_POST['nameTable'])) {
            echo json_encode(['error' => "nameTable incorrect"]);
        }

        if (!isset($_POST['place']) || empty($_POST['place'])) {
            echo json_encode(['error' => "place incorrect"]);
        }

        if (!isset($_POST['name']) || empty($_POST['name'])) {
            echo json_encode(['error' => "name incorrect"]);
        }

        if (!isset($_POST['photo']) || empty($_POST['photo'])) {
            echo json_encode(['error' => "photo incorrect"]);
        }

        if (!isset($_POST['type']) || empty($_POST['type'])) {
            echo json_encode(['error' => "type incorrect"]);
        }

        if (!isset($_POST['date_init']) || empty($_POST['date_init'])) {
            echo json_encode(['error' => "date_init incorrect"]);
        }

        if (!isset($_POST['date_end']) || empty($_POST['date_end'])) {
            echo json_encode(['error' => "date_end incorrect"]);
        }

        if (!isset($_POST['id']) || $_POST['id'] < 1) {
            echo json_encode(['error' => "id incorrect"]);
        }

        $db = new Db();
        $db->setTable($_POST['nameTable']);
        $query = $db->getQuery('UPDATE', ['set' => ['place' => $_POST['place'], 'name' => $_POST['name'], 'photo' => $_POST['photo'], 'type' => $_POST['type'], 'date_init' => $_POST['date_init'], 'date_end' => $_POST['date_end']], 'where' => ['id' => $_POST['id']]]);
        $result = $db->executeS($query);
        if (!$result) {
            $result = $db->getLastError();
        } else {
            $result = true;
        }

        echo json_encode(['result' => $result]);
        break;
    case 'log':
        if (!isset($_POST['name']) || empty($_POST['name'])) {
            echo json_encode(['error' => "name incorrect"]);
        }

        if (!isset($_POST['password']) || empty($_POST['password'])) {
            echo json_encode(['error' => "password incorrect"]);
        }

        $name = $_POST['name'];
        $password = $_POST['password'];
        $db = new Db();
        $db->setTable("user");
        $query = $db->getQuery('SELECT', ["col" => "*", "where" => "name='{$name}' AND password='{$password}'"]);
        $result = $db->executeS($query);
        if (!$result) {
            $result = $db->getLastError();
        } else {
            $data = $result->fetchAll(PDO::FETCH_CLASS, "User");
            if (empty($data)) {
                echo json_encode(['result' => "Credenciales erroneas"]);
                return;
            }

            $_SESSION["id"] = $data[0]->id;
            $_SESSION["rol"] = $data[0]->rol;
            $result = true;
        }

        echo json_encode(['result' => $result]);
        break;

    case 'favAction':
        if (!isset($_POST['idEvent']) || empty($_POST['idEvent'])) {
            echo json_encode(['error' => "idEvent incorrect"]);
        }
        $db = new Db();
        $db->setTable("favorites");
        $query = $db->getQuery('SELECT', ["col" => "*", "where" => "idUser='{$_SESSION["id"]}' AND idEvent='{$_POST['idEvent']}'"]);
        $result = $db->executeS($query);
        if (!$result) {
            $result = $db->getLastError();
        } else {
            if ($result->rowCount() == 0) {
                $query = $db->getQuery('INSERT', ["idUser" => "{$_SESSION["id"]}", "idEvent" => "{$_POST['idEvent']}"]);
                $result = $db->executeS($query);
                if (!$result) {
                    $result = $db->getLastError();
                } else {
                    $result = true;
                    $acction = "insert";
                }
            } else {
                $query = $db->getQuery('DELETE', ["value" => "idUser = {$_SESSION["id"]} AND idEvent = {$_POST['idEvent']}"]);
                $result = $db->executeS($query);
                if (!$result) {
                    $result = $db->getLastError();
                } else {
                    $result = true;
                    $acction = "delete";
                }
            }
        }

        echo json_encode(['result' => $result, 'acction' => $acction]);
        break;

    case 'selectFavs':
        $db = new Db();
        $db->setTable("favorites");
        $query = $db->getQuery('SELECT', ["col" => "*", "where" => "idUser = {$_SESSION['id']}"]);
        $result = $db->executeS($query);
        if (!$result) {
            echo json_encode(['result' => $result]);
            die();
        }

        $where = "";
        if ($result->rowCount() != 0) {
            $data = $result->fetchAll(PDO::FETCH_CLASS, "User");
            foreach ($data as $event => $user) {
                $where .= "id = " . $user->idEvent . " OR ";
            }

            $where = substr($where, 0, -4);
        }

        echo json_encode(['result' => $where]);
        break;

    case 'validateName':
        if (!isset($_POST['name']) || empty($_POST['name'])) {
            echo json_encode(['error' => "name incorrect"]);
        }

        $db = new Db();
        $db->setTable("user");
        $query = $db->getQuery('SELECT', ["col" => "*", "where" => "name = '{$_POST['name']}'"]);
        $result = $db->executeS($query);
        if (!$result) {
            echo json_encode(['result' => $result]);
            die();
        }

        if ($result->rowCount() != 0) {
            echo json_encode(['result' => true]);
            die();
        }

        echo json_encode(['result' => false]);
        break;

    default:
        die(header("Refresh:5; url=index.php"));
        break;
}
