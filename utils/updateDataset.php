<?php
require_once("../database/db_connection.php");

$data = json_decode(file_get_contents("php://input"), true);

$name = $conn->real_escape_string($data['name']);
$datasets = $data['datasets'];

if (isset($data['ID']) && !empty($data['ID'])) {
    // Esegui un update se l'ID esiste
    $sql = "UPDATE dataset SET Nome = '$name' WHERE ID = {$data['ID']}";

    if ($conn->query($sql) === TRUE) {
        // Elimina i dati vecchi e reinserisci quelli nuovi
        $sql_delete_old_data = "DELETE FROM dt_data WHERE dt_ID_ds_dataset IN (SELECT ds_ID FROM ds_dataset WHERE ds_ID_Dataset = {$data['ID']})";
        $conn->query($sql_delete_old_data);

        foreach ($datasets as $dataset) {
            $ds_name = $conn->real_escape_string($dataset['ds_name']);
            $min_value = $conn->real_escape_string($dataset['min_value']);
            $max_value = $conn->real_escape_string($dataset['max_value']);
            $sql_insert_ds_dataset = "INSERT INTO ds_dataset (ds_ID_Dataset, ds_Nome, ds_min_value, ds_max_value) VALUES ({$data['ID']}, '$ds_name', $min_value, $max_value)";
            $conn->query($sql_insert_ds_dataset);

            $ds_dataset_id = $conn->insert_id;

            foreach ($dataset['data'] as $dataItem) {
                $description = $conn->real_escape_string($dataItem['description']);
                $value = $conn->real_escape_string($dataItem['value']);
                $sql_insert_data = "INSERT INTO dt_data (dt_ID_ds_dataset, dt_descrizione, dt_valore) VALUES ($ds_dataset_id, '$description', $value)";
                $conn->query($sql_insert_data);
            }
        }
        echo "Dataset aggiornato con successo.";
    } else {
        echo "Errore durante l'aggiornamento del dataset: " . $conn->error;
    }
} else {
    // Esegui un insert se l'ID non esiste (creazione di un nuovo dataset)
    $sql_insert_dataset = "INSERT INTO dataset (Nome) VALUES ('$name')";

    if ($conn->query($sql_insert_dataset) === TRUE) {
        $newDatasetID = $conn->insert_id;

        foreach ($datasets as $dataset) {
            $ds_name = $conn->real_escape_string($dataset['ds_name']);
            $min_value = $conn->real_escape_string($dataset['min_value']);
            $max_value = $conn->real_escape_string($dataset['max_value']);
            $sql_insert_ds_dataset = "INSERT INTO ds_dataset (ds_ID_Dataset, ds_Nome, ds_min_value, ds_max_value) VALUES ($newDatasetID, '$ds_name', $min_value, $max_value)";
            $conn->query($sql_insert_ds_dataset);

            $ds_dataset_id = $conn->insert_id;

            foreach ($dataset['data'] as $dataItem) {
                $description = $conn->real_escape_string($dataItem['description']);
                $value = $conn->real_escape_string($dataItem['value']);
                $sql_insert_data = "INSERT INTO dt_data (dt_ID_ds_dataset, dt_descrizione, dt_valore) VALUES ($ds_dataset_id, '$description', $value)";
                $conn->query($sql_insert_data);
            }
        }
        echo "Nuovo dataset inserito con successo.";
    } else {
        echo "Errore durante l'inserimento del nuovo dataset: " . $conn->error;
    }
}

$conn->close();
?>
