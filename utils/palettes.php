<?php
require_once("../database/db_connection.php");

$minGradient = $_GET['minGradient'] ?? 0;
$maxGradient = $_GET['maxGradient'] ?? 100;


$sql = "SELECT palette.ID AS palette_id, palette.nome AS palette_name, GROUP_CONCAT(colore.codice) AS colors 
        FROM palette 
        JOIN cd_colori_discreti ON palette.ID = cd_colori_discreti.cd_ID_Palette 
        JOIN colore ON cd_colori_discreti.cd_ID_Colore = colore.ID 
        WHERE cd_colori_discreti.cd_ordine BETWEEN $minGradient AND $maxGradient
        GROUP BY palette.ID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $palettes = array();

    while($row = $result->fetch_assoc()) {
        $palette = array(
            'id' => $row['palette_id'],
            'name' => $row['palette_name'],
            'colors' => explode(',', $row['colors'])
        );
        array_push($palettes, $palette);
    }

    echo json_encode($palettes);
} else {
    echo "0 results";
}
$conn->close();
?>
