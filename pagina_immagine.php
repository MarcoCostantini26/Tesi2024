<?php

session_start();

require_once("./database/db_connection.php");

$templateParams["nome"] = "pagina_immagine.php";
$templateParams["titolo"] = "Generazione finale";

require("template/base.php");
?>