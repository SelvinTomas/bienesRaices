<?php 

// Importar la conexion
require 'includes/config/database.php';
$db = conectarDB();

// Crear el email y password
$email = "tomas.a12108@gmail.com";
$password = "123456";


// Query para crear el usuario
$query = "INSERT INTO usuarios (email, password) VALUES ('${email}', '${password}');";

// Agregar usuario a la base de datos
mysqli_query($db, $query);
