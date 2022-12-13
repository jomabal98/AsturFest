<?php
session_start();
echo $_SESSION['id'];
if(!isset($_SESSION['id'])){
    header("Location: index.php");
    die();
}

if ($_SESSION['rol']==1) {
    header("Location: indexAdmin.php");
}else{
    header("Location: index.php");
}