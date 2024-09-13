<?php
require_once("./database/db_connection.php");

// Ricevi i dati dal frontend (Vue.js)
$data = json_decode(file_get_contents("php://input"), true);
$imageId = $data['deleteImageId'];
$imageName = $data['deletedImageName'];

// Prima di eliminare l'immagine dal database, cancelliamo il file dal server
$imagePath = './finalImages/' . $imageName;

if (file_exists($imagePath)) {
    if (unlink($imagePath)) {
        // Se il file viene cancellato correttamente, elimina l'immagine dal database
        $sql = "DELETE FROM fi_final_images WHERE fi_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $imageId);

        if ($stmt->execute()) {
            // Risposta di successo
            echo json_encode(['success' => true, 'message' => 'Immagine eliminata con successo.']);
        } else {
            // Se l'eliminazione dal database fallisce
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione dal database.']);
        }

        $stmt->close();
    } else {
        // Se il file non viene cancellato
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione del file immagine.']);
    }
} else {
    // Se il file non esiste, ma vogliamo comunque eliminare la voce dal database
    $sql = "DELETE FROM fi_final_images WHERE fi_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $imageId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'File non trovato, ma record eliminato.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione dal database.']);
    }

    $stmt->close();
}

$conn->close();
?>
