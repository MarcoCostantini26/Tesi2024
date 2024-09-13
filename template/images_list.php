
<style>
    .image-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: left;
    }

    .image-box {
        margin: 10px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .image-box img {
        max-width: 400px;
        max-height: 300px;
        object-fit: cover;
    }
</style>

<?php

    $sql = "SELECT ID, nome FROM images";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="image-container">';
        while($row = $result->fetch_assoc()) {
            $imagePageUrl = 'pagina_immagine.php?id=' . $row["ID"];
            echo '<div class="image-box">';
            echo '<a href="' . $imagePageUrl . '" class="image-link">';
            echo '<img src="' . $row["nome"] . '" alt="Immagine">';
            echo '</a>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo "Nessuna immagine trovata nel database.";
    }

    $conn->close();
?>


