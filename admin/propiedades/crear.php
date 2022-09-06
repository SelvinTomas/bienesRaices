<?php

require '../../includes/app.php';

use App\Propiedad;


use Intervention\Image\ImageManagerStatic as Image;

estaAutenticado();


$db = conectarDB();

$propiedad = new Propiedad;

// Realizar consulta para vendedores
$consulta = "SELECT * FROM vendedores";
$resultado  = mysqli_query($db, $consulta);

// $arreglo con mensaje de errores
$errores = Propiedad::getErrores();

// Ejecutar el codigo despues de que el usuario envíe el formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // SUBIDA DE ARCHIVOS

    // Crear instancia
    $propiedad = new Propiedad($_POST);

    // Generar el nombre unico
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    // Realiza un resize a la imagen con Intervention/image
    if ($_FILES['imagen']['tmp_name']) {
        $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }

    $errores  = $propiedad->validar();


    // Revisar que el arreglo de errores este vacio

    if (empty($errores)) {


        // Crear carpeta para subir imagenes
        if (!is_dir(CARPETA_IMAGENES)) {
            mkdir(CARPETA_IMAGENES);
        }



        // Guardar la imagen en el servidor
        $image->save(CARPETA_IMAGENES . $nombreImagen);

        // Guardar en la base de datos
        $resultado = $propiedad->guardar();



        // Mensaje de exito
        if ($resultado) {
            // echo "insertado correctamente";
            // Redireccionar a los usuarios
            header('Location: /admin?resultado=1');
        }
    }
}

incluirTemplates('header');

?>

<main class="contenedor seccion">
    <h1>Crear</h1>


    <a href="/admin/index.php" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach ?>
    <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        

    <?php include '../../includes/templates/formulario_propiedades.php';
    ?>
        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>
<?php
incluirTemplates('footer');
?>