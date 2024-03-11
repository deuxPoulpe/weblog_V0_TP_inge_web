<?php
    global $errors;

    if (!empty($errors)) {
        echo "<div class='error'>";
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
        echo "</div>";
    }