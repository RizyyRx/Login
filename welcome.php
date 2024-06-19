<?
ob_start();
include "libs/load.php";

Session::renderPage();
ob_end_flush();
?>