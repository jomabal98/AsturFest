<?php
require_once __DIR__ . '/src/PaginationTable.php';
require_once __DIR__ . '/src/Db.php';

$rol = "user_nr";
session_start();
if (isset($_SESSION['id'])) {
    if ($_SESSION['rol'] == 1) {
        header("Location:close.php");
    }
    $rol = "user_r";
}


$page = 1;
$limit = 5;
$new_columns = ["favs" => ''];
$nameTable = "event";
$fieldsTranslated = [
    'id' => [
        'translator' => 'Identificador'
    ],
    'name' => [
        'translator' => 'Nombre',
        'type' => 'text'
    ],
    'date_init' => [
        'translator' => 'Fecha_de_inicio',
        'type' => 'date'
    ],
    'date_end' => [
        'translator' => 'Fecha_de_finalizacion',
        'type' => 'date'
    ],
    'place' => [
        'translator' => 'Lugar',
        'type' => 'text'
    ],
    'type' => [
        'translator' => 'Tipo',
        'type' => 'text'
    ],
    'photo' => [
        'translator' => 'Imagen',
        'type' => 'text'
    ]
];
$paginationTable = new PaginationTable(new Db(), $rol, $nameTable, $page, $limit, $new_columns, $fieldsTranslated);
$table = $paginationTable->get();
if (!$table) {
    die($paginationTable->getLastError());
}

if ($rol == "user_r") {
    $btn = '<div class="btn-group" style="width:15%">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
      Perfil
    </button>
    <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton1">
      <li><a class="dropdown-item" href="favorites.php">Favoritos</a></li>
      <li><a class="dropdown-item" href="close.php">Cerrar Sesión</a></li>
    </ul>
  </div>';
} else {
    $btn = '<form class="d-flex" action="login.php">
    <button class=" btn btn-outline-primary">Iniciar Sesion</button></form>';
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
    <title>Home</title>
    <link rel="shortcut icon" type="image/x-icon" href="views/img/favicon.ico" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarTogglerDemo01">
                <a class="navbar-brand" data-bs-toggle="tooltip" title="Vaya a la página principal" href="#"><img src="views/img/favicon.ico" width="20" height="20">AsturEvent</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <?php echo $btn; ?>
            </div>
        </div>
    </nav>

    <?php
    echo $table;
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
        var new_col = <?= json_encode($new_columns) ?>;
        var nameTable = <?= json_encode($nameTable) ?>;
        var fieldsTranslated = <?= json_encode($fieldsTranslated) ?>;
        var rol = <?= json_encode($rol) ?>;
    </script>
    <script src="views/js/script.js"></script>
</body>
<footer class="page-footer font-small blue">
    <div class="footer-copyright text-center py-3">© 2020 Copyright:
        <a href="/Asturfest/index.php"> AsturFest</a>
    </div>
</footer>

</html>