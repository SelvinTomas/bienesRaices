<?php

require '../../includes/app.php';

use App\Propiedad;
use Intervention\Image\ImageManagerStatic as Image;

estaAutenticado();


$db = conectarDB();

// Realizar consulta para vendedores
$consulta = "SELECT * FROM vendedores";
$resultado  = mysqli_query($db, $consulta);

// $arreglo con mensaje de errores
$errores = Propiedad::getErrores();

$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorId = '';

// Ejecutar el codigo despues de que el usuario envíe el formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // SUBIDA DE ARCHIVOS
    // Crear instancia
    $propiedad = new Propiedad($_POST);

    // Crear carpeta
    $carpetaImagenes = '../../imagenes/';

    if (!is_dir($carpetaImagenes)) {

        mkdir($carpetaImagenes);
    }

    // Generar el nombre unico
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    // Realiza un resize a la imagen con Intervention/image
    if($_FILES['imagen']['tmp_name']){
        $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
   
    }
    
    // Validar
    $errores = $propiedad->validar();


    // Revisar que el arreglo de errores este vacio

    if (empty($errores)) {


        // Crear carpeta para subir imagenes
        if(!is_dir(CARPETA_IMAGENES)){
            mkdir(CARPETA_IMAGENES);
        };



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
        <fieldset>
            <legend>Información General</legend>
            <label for="titulo">Titulo</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo de la propiedad" value="<?php echo $titulo ?>">

            <label for="precio">Precio</label>
            <input type="text" id="precio" name="precio" placeholder="Precio de la propiedad" value="<?php echo $precio ?>">

            <label for="imagen">Imagen</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion"><?php echo $descripcion ?></textarea>

        </fieldset>

        <fieldset>
            <legend>Información de la propiedad</legend>

            <label for="habitaciones">Habitaciones</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones ?>">

            <label for="wc">Baños</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc ?>">

            <label for="estacionamiento">Estacionamiento</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>
            <select name="vendedorId">
                <option value="">--Seleccione--</option>
                <?php while ($vendedor = mysqli_fetch_assoc($resultado)) : ?>

                    <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>">
                        <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?></option>
                <?php endwhile ?>
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>
<?php
incluirTemplates('footer');
?>