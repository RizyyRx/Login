<?
include "libs/load.php";

//unset the $_SESSION array
Session::unset();

//destroy session
Session::destroy();

//redirect to login page
header("Location: login.php");
?>