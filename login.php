<?php

require 'includes/config/database.php';
$db = conectarDB();
// Autenticar el usuario

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (!$email) {
        $errores[] = "El email es obligatorio o no es valido";
    }
    if (!$password) {
        $errores[] = "El password es obligatorio ";
    }


}


// incluye el header
require 'includes/funciones.php';

incluirTemplates('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesión</h1>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error;  ?>

        </div>
    <?php endforeach; ?>
    <form method="POST" action="" class="formulario">
        <fieldset>
            <legend>Email y password</legend>


            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Tu Email" id="email">

            <label for="password">Password</label>
            <input type="password" placeholder="Tu password" id="password" name="password">

        </fieldset>
        <input type="submit" value="Iniciar Sesión" class="boton boton-verde">

    </form>
</main>
<?php
incluirTemplates('footer');
?>