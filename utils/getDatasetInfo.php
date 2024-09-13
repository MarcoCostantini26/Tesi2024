<?php
require_once("../database/db_connection.php");

$dataset_id = $_GET['id'];

$sql = "SELECT ds.ID AS dataset_id, ds.Nome AS dataset_name, dss.ds_ID AS ds_dataset_id, dss.ds_Nome AS ds_name, dss.ds_min_value, dss.ds_max_value, d.dt_ID AS data_id, d.dt_descrizione, d.dt_valore
        FROM dataset AS ds, ds_dataset AS dss, dt_data AS d
        WHERE d.dt_ID_ds_dataset = dss.ds_ID
        AND dss.ds_ID_Dataset = ds.ID
        AND ds.ID = $dataset_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $dataset_details = array();
    while ($row = $result->fetch_assoc()) {
        $dataset_details['ID'] = $row['dataset_id'];
        $dataset_details['name'] = $row['dataset_name'];
        $dataset_details['datasets'][] = array(
            'ds_id' => $row['ds_dataset_id'],
            'ds_name' => $row['ds_name'],
            'min_value' => $row['ds_min_value'],
            'max_value' => $row['ds_max_value'],
            'data' => array(
                'data_id' => $row['data_id'],
                'description' => $row['dt_descrizione'],
                'value' => $row['dt_valore']
            )
        );
    }
    echo json_encode($dataset_details);
} else {
    echo json_encode(array('error' => 'Nessun dataset trovato con ID: ' . $dataset_id));
}

$conn->close();
?>