<?php
require_once __DIR__ . '/src/PaginationTable.php';
require_once __DIR__ . '/src/Db.php';

$page = 1;
$limit = 5;
$new_columns = ['' => "<button class='btn btn-danger delete'>DELETE</button>"];
$nameTable = "user";
$paginationTable = new PaginationTable(new Db(), $nameTable, $page, $limit, $new_columns);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>EventosAdmin</title>
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
                        <a class="nav-link active user" aria-current="page" href="#">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active event" aria-current="page" href="#">Eventos</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-danger">Cerrar Sesion</button>
                </form>
            </div>
        </div>
    </nav>


    
    <?php
    echo $table;
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
        var new_col = <?= json_encode($new_columns) ?>;
        var nameTable = <?= json_encode($nameTable) ?>
    </script>
    <script src="views/js/srciptAdminMenu.js"></script>
</body>

</html>