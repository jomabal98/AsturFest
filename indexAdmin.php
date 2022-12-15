<?php
require_once __DIR__ . '/src/PaginationTable.php';
require_once __DIR__ . '/src/Db.php';

session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("location: index.php");
    die();
}

$page = 1;
$limit = 5;
$new_columns = ['' => "<button class='btn btn-danger delete'>DELETE</button>"];
$nameTable = "user";
$fieldsTranslated = [
    'id' => [
        'translator' => 'Identificador'
    ],
    'rol' => [
        'translator' => 'Rol'
    ],
    'name' => [
        'translator' => 'Nombre',
        'type' => 'text'
    ],
    'password' => [
        'translator' => 'Contraseña',
        'type' => 'password'
    ],
    'mail' => [
        'translator' => 'Mail',
        'type' => 'text'
    ],
    'age' => [
        'translator' => 'Edad',
        'type' => 'number'
    ]
];
$rol = "admin";
$paginationTable = new PaginationTable(new Db(), $rol, $nameTable, $page, $limit, $new_columns, $fieldsTranslated);
$table = $paginationTable->get();
if (!$table) {
    die($paginationTable->getLastError());
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="views/css/style1.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>AdminHome</title>
    <link rel="shortcut icon" type="image/x-icon" href="views/img/favicon.ico" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarTogglerDemo01">
                <a class="navbar-brand" href="#"><img src="views/img/favicon.ico" width="20" height="20">AsturEvent</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link user" aria-current="page" href="#">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link event" aria-current="page" href="#">Eventos</a>
                    </li>
                </ul>
                <form class="d-flex" action="index.php">
                    <button class=" btn btn-outline-danger" href="close.php">Cerrar Sesion</button>
                </form>
            </div>
        </div>
    </nav>


    <?php
    echo $table;
    ?>

    <!-- <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3"><label for="Rol" class="col-form-label">Rol:</label><select type="select" class="form-control Rol">
                            <option value="0">0</option>
                            <option value="1">1</option>
                        </select>
                        </div>
                        <div class="mb-3"><label for="Name" class="col-form-label">Nombre:</label><input type="text" class="form-control Name">
                        </div>
                        <div class="mb-3"><label for="Password" class="col-form-label">Contraseña:</label><input type="password" class="form-control Password">
                        </div>
                        <div class="mb-3"><label for="eMail" class="col-form-label">Mail:</label><input type="text" class="form-control eMail">
                        </div>
                        <div class="mb-3"><label for="Age" class="col-form-label">Edad:</label><input type="number" class="form-control Age">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
        var new_col = <?= json_encode($new_columns) ?>;
        var nameTable = <?= json_encode($nameTable) ?>;
        var fieldsTranslated = <?= json_encode($fieldsTranslated) ?>;
    </script>
    <script src="views/js/srciptAdminMenu.js"></script>
</body>

</html>