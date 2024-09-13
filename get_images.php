<?php
require_once("./database/db_connection.php");

$sql_images = "SELECT ID, nome FROM images";
$result_images = $conn->query($sql_images);

if ($result_images->num_rows > 0) {
    $images = array();
    while ($row = $result_images->fetch_assoc()) {
        $images[] = array(
            'ID' => $row['ID'],
            'nome' => $row['nome']
        );
    }
    echo json_encode($images);
} else {
    echo json_encode(array());
}

$conn->close();
?>
