<?php
require_once("../database/db_connection.php");

$paletteId = isset($_GET['paletteId']) ? $_GET['paletteId'] + 1 : null;

$sql = "SELECT fi.fi_ID, fi.fi_nome 
        FROM fi_final_images fi";

if ($paletteId !== null) {
    $sql .= " WHERE fi.fi_IDPalette = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $paletteId);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$images = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}

echo json_encode($images);

$conn->close();
?>
