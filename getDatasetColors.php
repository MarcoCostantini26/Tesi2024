<?php
require_once("./database/db_connection.php");

$datasetId = intval($_POST['dataset']);

$sql = "SELECT C.codice 
        FROM colore C, cd_colori_discreti CD, palette P, dp_dataset_palette DP
        WHERE DP.dp_ID_Dataset = $datasetId
        AND DP.dp_ID_Palette = P.ID
        AND P.ID = CD.cd_ID_Palette
        AND CD.cd_ID_Colore = C.ID
        AND CD.cd_ordine >= DP.dp_start_color 
        AND CD.cd_ordine <= DP.dp_end_color";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $colors = array();

    while($row = $result->fetch_assoc()) {
        array_push($colors, $row['codice']);
    }

    echo json_encode($colors);
} else {
    echo json_encode(array()); 
}

$conn->close();
?>