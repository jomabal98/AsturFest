<?php
require_once __DIR__ . '/src/PaginationTable.php';
require_once __DIR__ . '/src/Db.php';

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
        $query = $db->getQuery('DELETE', ["value" => $id]);
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
            $where = " date_init between {$_POST['date1']} and {$_POST['date2']}";
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
            echo json_encode(['error' => "new_columns incorrect"]);
        }

        if (!isset($_POST['nameTable']) || empty($_POST['nameTable'])) {
            echo json_encode(['error' => "nameTable incorrect"]);
        }

        if (!isset($_POST['fieldsTranslated']) || !is_array($_POST['fieldsTranslated'])) {
            echo json_encode(['error' => "fieldsTranslated incorrect"]);
        }

        $paginationTable = new PaginationTable(new Db(), (string) $_POST['nameTable'], (int) $_POST['page'], (int) $_POST['numSelector'], (array) $_POST['new_columns'], (array)$_POST['fieldsTranslated'], (string) $_POST['orderBy'], (string) $_POST['orderWay'], (string)$where);
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

        $paginationTable = new PaginationTable(new Db(), (string) $_POST['nameTable'], (int) $_POST['page'], (int) $_POST['limit'], (array) $_POST['new_columns'], (array)$_POST['fieldsTranslated']);
        $table = $paginationTable->get();
        if (!$table) {
            echo json_encode(['error' => $paginationTable->getLastError()]);
            die();
        }

        echo json_encode(['table' => $table]);
        break;
    default:
        die(header("Refresh:5; url=indexAdmin.php"));
        break;
}
