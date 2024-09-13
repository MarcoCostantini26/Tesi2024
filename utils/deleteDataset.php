<?php
require_once("../database/db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    
    $id = $_GET['id'];


    $sqlDeleteData = "DELETE dt_data, ds_dataset, dataset 
                  FROM dt_data
                  INNER JOIN ds_dataset ON dt_data.dt_ID_ds_dataset = ds_dataset.ds_ID 
                  INNER JOIN dataset ON ds_dataset.ds_ID_Dataset = dataset.ID
                  WHERE dataset.ID = $id";

    if ($conn->query($sqlDeleteData) === TRUE) {
        $conn->commit();

        http_response_code(200);
        echo "Dataset e dati associati eliminati con successo";
    } else {
        $conn->rollback();

        http_response_code(500);
        echo "Errore durante l'eliminazione del dataset e dei dati associati: " . $conn->error;
    }

    $conn->close();
} else {
    http_response_code(405);
    echo "Metodo non consentito";
}
?>