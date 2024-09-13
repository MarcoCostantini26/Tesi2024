<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tirocinio";
$port = 3307;

// Crea la connessione
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Controlla la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}
?>