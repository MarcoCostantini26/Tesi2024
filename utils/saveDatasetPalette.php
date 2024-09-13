<?php
require_once("../database/db_connection.php");

$data = json_decode(file_get_contents("php://input"), true);

$datasetId = intval($data['dataset']);
$paletteId = intval($data['palette']);
$minGradient = $data['minGradient'];
$maxGradient = $data['maxGradient'];

$checkIfExistsQuery = "SELECT * FROM dp_dataset_palette WHERE dp_ID_Dataset = $datasetId";
$result = $conn->query($checkIfExistsQuery);

if ($result->num_rows > 0) {
    $updateQuery = "UPDATE dp_dataset_palette SET dp_ID_Palette = $paletteId, dp_start_color = '$minGradient', dp_end_color = '$maxGradient' WHERE dp_ID_Dataset = $datasetId";
    
    if ($conn->query($updateQuery) === TRUE) {
        echo "Aggiornamento del collegamento nel database effettuato con successo";
    } else {
        echo "Errore durante l'aggiornamento del collegamento nel database: " . $conn->error;
    }
} else {
    $insertQuery = "INSERT INTO dp_dataset_palette (dp_ID_Dataset, dp_ID_Palette, dp_start_color, dp_end_color) VALUES ($datasetId, $paletteId, '$minGradient', '$maxGradient')";
    
    if ($conn->query($insertQuery) === TRUE) {
        echo "Collegamento salvato nel database con successo";
    } else {
        echo "Errore durante il salvataggio del collegamento nel database: " . $conn->error;
    }
}

$conn->close();
?>
