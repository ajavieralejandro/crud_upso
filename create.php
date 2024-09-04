<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $sql = "INSERT INTO usuarios (nombre, email) VALUES ('$nombre', '$email')";
    
    if ($conn->query($sql) === TRUE) {
        //echo "<div class='alert alert-danger'>Registro Cargado Exitosamente </div>";

        header("Location: index.php");
        exit();     
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    $conn->close();
}
?>