<?
include "libs/load.php";
Session::unset();
Session::destroy();
header("Location: login.php");
?>