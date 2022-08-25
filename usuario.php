<?php 

// Importar la conexion
require 'includes/app.php';
$db = conectarDB();

// Crear el email y password
$email = "tomas.a12108@gmail.com";
$password = "123456";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
// Query para crear el usuario
$query = "INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordHash}');";

// Agregar usuario a la base de datos
mysqli_query($db, $query);
