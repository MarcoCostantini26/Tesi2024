<?php

session_start();

require_once("./database/db_connection.php");

$templateParams["nome"] = "images_list.php";
$templateParams["titolo"] = "Lista Immagini";

require("template/base.php");
?>