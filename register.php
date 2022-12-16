<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="views/css/style2.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <title>Registro</title>
    <link rel="shortcut icon" type="image/x-icon" href="views/img/favicon.ico" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarTogglerDemo01">
                <a class="navbar-brand" data-bs-toggle="tooltip" title="Vaya a la página principal" href="index.php"><img src="views/img/favicon.ico" width="20" height="20">AsturEvent</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="wrapper">
            <div class="logo">
                <a data-bs-toggle="tooltip" title="Vaya a la página principal" href="index.php"><img src="views/img/favicon.ico" alt=""></a>
            </div>
            <div class="text-center mt-4 name">
                AsturEvent
            </div>
            <form class="p-3 mt-3" method="POST">
                <div class="form-field d-flex align-items-center">
                    <span class="fas fa-user"></span>
                    <input type="text" name="userName" id="userName" placeholder="Username" data-bs-toggle="tooltip" title="Introduzca el nombre de usuario">
                </div>
                <div class="form-field d-flex align-items-center">
                    <span class="fas fa-key"></span>
                    <input type="password" name="password" id="pwd" placeholder="Password" data-bs-toggle="tooltip" title="Introduzca la contraseña con un numero y una letra al menos, y longitud minima de 4 caracteres">
                </div>
                <div class="form-field d-flex align-items-center">
                    <span class="fas fa-envelope"></span>
                    <input type="text" name="mail" id="mail" placeholder="Mail"data-bs-toggle="tooltip" title="Introduzca mail example: mail@mail.com">
                </div>
                <div class="form-field d-flex align-items-center">
                    <span class="fas fa-birthday-cake"></span>
                    <input type="number" name="age" id="age" placeholder="Age" min=0 data-bs-toggle="tooltip" title="Introduzca su edad">
                </div>
                <button class="btn btn-primary mt-3">Registrarse</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="views/js/scriptRegister.js"></script>
</body>
<footer class="page-footer font-small blue">
    <div class="footer-copyright text-center py-3">© 2020 Copyright:
        <a href="/Asturfest/index.php"> AsturFest</a>
    </div>
</footer>

</html>