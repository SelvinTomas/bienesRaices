<?php

use App\Propiedad;
use Intervention\Image\ImageManagerStatic as Image;

require '../../includes/app.php';
estaAutenticado();


// Validar el id
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin');
}

$propiedad = Propiedad::find($id);



// Realizar consulta para vendedores
$consulta = "SELECT * FROM vendedores";
$resultado  = mysqli_query($db, $consulta);

// $arreglo con mensaje de errores
$errores = Propiedad::getErrores();


// Ejecutar el codigo despues de que el usuario envíe el formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Asignar los atributos
    $args = $_POST['propiedad'];

    $propiedad->sincronizar($args);


    $errores = $propiedad->validar();

    // Validación subida de archivos

    // Generar el nombre unico
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
    // Realiza un resize a la imagen con Intervention/image
    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }

    // Revisar que el arreglo de errores este vacio

    if (empty($errores)) {
        // Almacenar la imagen
        $image->save(CARPETA_IMAGENES . $nombreImagen);

        $propiedad->actualizar();
    

    }
}


incluirTemplates('header');

?>

<main class="contenedor seccion">
    <h1>Actualizar Propiedad</h1>


    <a href="/admin/index.php" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach ?>
    <form class="formulario" method="POST" enctype="multipart/form-data">

        <?php include '../../includes/templates/formulario_propiedades.php' ?>

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>
</main>
<?php
incluirTemplates('footer');
?>