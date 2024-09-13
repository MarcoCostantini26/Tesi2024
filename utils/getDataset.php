<?php
require_once("../database/db_connection.php");


$sql = "SELECT * FROM dataset";
$result = $conn->query($sql);

$datasets = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $datasets[] = array(
            "ID" => $row["ID"],
            "nome" => $row["Nome"]
        );
    }
}

echo json_encode($datasets);

$conn->close();
?>
