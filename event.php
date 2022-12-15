<?php
require_once __DIR__ . '/src/Db.php';
require_once __DIR__ . '/src/User.php';

session_start();
if (!isset($_GET['id'])) {
    header("Refresh:5; url=indexAdmin.php");
}
$rol = "user_nr";
if (isset($_SESSION['id'])) {
    if ($_SESSION['rol'] == 1) {
        header("Location:close.php");
    }
    $rol = "user_r";
}

if ($rol == "user_r") {
    $id = $_GET["id"];
    $db = new Db();
    $db->setTable('favorites');
    $query = $db->getQuery('SELECT', ['col' => '*', 'where' => "idEvent={$id} AND idUser={$_SESSION['id']}"]);
    $data = $db->executeS($query);
    if (!$data) {
        echo $db->getLastError();
        die();
    }

    if ($data->rowCount() == 0) {
        $fav = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
      </svg>';
    } else {
        $fav = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
      </svg>';
    }

    $btn = '<div class="btn-group dropstart d-flex">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
      Perfil
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
      <li><a class="dropdown-item" href="../favorites.php">Favoritos</a></li>
      <li><a class="dropdown-item" href="../close.php">Cerrar Seción</a></li>
    </ul>
  </div>';
} else {
    $btn = '<form class="d-flex" action="../login.php">
    <button class=" btn btn-outline-primary">Iniciar Sesion</button></form>';
    $fav = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
  </svg>';
}

$db = new Db();
$db->setTable('event');
$query = $db->getQuery('SELECT', ['col' => '*', 'where' => "id={$_GET["id"]}"]);
if ($query == false) {
    die($db->getLastError());
}

$data = $db->executeS($query);
if (!$data) {
    echo $db->getLastError();
    die();
}

$data = $data->fetchAll(PDO::FETCH_CLASS, "User");
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
    <title>Evento</title>
    <link rel="shortcut icon" type="image/x-icon" href="../views/img/favicon.ico" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarTogglerDemo01">
                <a class="navbar-brand" href="../index.php"><img src="../views/img/favicon.ico" width="20" height="20">AsturEvent</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <?php echo $btn; ?>
            </div>
        </div>
    </nav>
    <br>
    <div class="container card mb-3 border-primary" style="max-width: 540px;">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="../<?php echo $data[0]->photo; ?>" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <h3 class="card-title text-primary"><?php echo $data[0]->name; ?></h3>
                    <p class="card-text"><b class="text-primary">Tipo: </b><?php echo $data[0]->type; ?></p>
                    <p class="card-text"><b class="text-primary">Lugar: </b><?php echo $data[0]->place . "<br>" . $fav; ?></p>

                </div>
            </div>
            <div class="col-md-12">
                <div class="card-body">
                    <p class="card-text "><b class="text-primary">Descripción:</b> <br> <?php echo $data[0]->description; ?></p>
                    <p class="card-text"><small class="text-muted">Fecha del <?php echo $data[0]->date_init; ?> al <?php echo $data[0]->date_end; ?></small></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
        var idEvent = <?= json_encode($_GET["id"]) ?>;
        var rol = <?= json_encode($rol) ?>;
    </script>
    <script src="../views/js/scriptEvent.js"></script>
</body>

</html>