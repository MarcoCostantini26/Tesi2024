<?php
require_once("./database/db_connection.php");

$segmentedImageId = $_POST['segmentedImageId'];
$originalImageId = $_POST['originalImageId']; 
$datasetId = $_POST['datasetId'];

$sqlPalette = "SELECT dp_ID_Palette
                FROM dp_dataset_palette
                WHERE dp_ID_Dataset = $datasetId";

$resultPalette = $conn->query($sqlPalette);
if ($resultPalette->num_rows > 0){
    while($row = $resultPalette->fetch_assoc()) {
        $IDPalette = $row["ID_Palette"];
    }

}

$uniqueName = uniqid('result_image_') . '_' . time() . '.png'; 

$sql = "INSERT INTO fi_final_images (fi_ID_ImmaginePartenza, fi_ID_ImmagineSegmentata, fi_nome, fi_IDPalette) VALUES ('$originalImageId', '$segmentedImageId', '$uniqueName', '$IDPalette')";
if ($conn->query($sql) === TRUE) {
    echo "Immagine risultante salvata nel database con successo";
} else {
    echo "Errore durante il salvataggio dell'immagine risultante nel database: " . $conn->error;
}

$uploadDirectory = 'finalImages/'; 
$finalImagePath = $uploadDirectory . $uniqueName; 

if (move_uploaded_file($_FILES['finalImage']['tmp_name'], $finalImagePath)) {
    echo "Immagine risultante salvata sul server con successo";
} else {
    echo "Errore durante il salvataggio dell'immagine risultante sul server";
}

$conn->close();
?>
