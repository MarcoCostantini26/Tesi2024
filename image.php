<?php

session_start();

require_once("./database/db_connection.php");


$templateParams["nome"] = "image.php";
$templateParams["titolo"] = "Carica Immagine";

require("template/base.php");
?>