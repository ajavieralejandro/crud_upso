<?php

include 'conexion.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        //Tengo que fijarme que el usuario que voy a insertar no exista

        $sql_usuario = "SELECT * FROM usuarios WHERE username = '$username'";
        $result = $conn->query($sql_usuario);
    
        if($result->num_rows > 0){
            echo "<div class='alert alert-danger mt-3'>Usuario ya existente en la base de datos</div>";
            exit;
        }

        $sql = "INSERT INTO usuarios (username, password) VALUES ('$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            header("Location: dashboard.php");
        } else {
            echo "<div class='alert alert-danger mt-3'>Error: " . $conn->error . "</div>";
        }
    }
    ?>