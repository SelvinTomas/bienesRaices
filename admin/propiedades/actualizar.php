<?php

require '../../includes/funciones.php';
$auth = estaAutenticado();
if(!$auth){
    header('Location: /');
}


// Validar el id
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin');
}
// Base de datos
require '../../includes/config/database.php';

$db = conectarDB();
// Obtener los datos de la propiedad
$consulta = "SELECT * FROM propiedades WHERE id = ${id}";
$resultado = mysqli_query($db, $consulta);
$propiedad = mysqli_fetch_assoc($resultado);



// Realizar consulta para vendedores
$consulta = "SELECT * FROM vendedores";
$resultado  = mysqli_query($db, $consulta);

// $arreglo con mensaje de errores
$errores = [];
$titulo = $propiedad['titulo'];
$precio = $propiedad['precio'];
$descripcion = $propiedad['descripcion'];
$habitaciones = $propiedad['habitaciones'];
$wc = $propiedad['wc'];
$estacionamiento = $propiedad['estacionamiento'];
$vendedorId = $propiedad['vendedorId'];
$imagenPropiedad = $propiedad['imagen'];

// Ejecutar el codigo despues de que el usuario envíe el formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";

    $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
    $precio = mysqli_real_escape_string($db, $_POST['precio']);
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
    $wc = mysqli_real_escape_string($db, $_POST['wc']);
    $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento']);
    $vendedorId = mysqli_real_escape_string($db, $_POST['vendedor']);
    $creado = date('Y/m/d');

    // Asignar files a una variable
    $imagen = $_FILES['imagen'];



    if (!$titulo) {
        $errores[] = "Debes añadir un titulo";
    }
    if (!$precio) {
        $errores[] = "El precio es obligatorio";
    }
    if (strlen($descripcion) < 50) {
        $errores[] = "La descripcion es obligatoria y debe tener almenos 50 caracteres";
    }
    if (!$habitaciones) {
        $errores[] = "El número de habitaciones es obligatorio";
    }
    if (!$wc) {
        $errores[] = "El número de baños es obligatorio";
    }
    if (!$estacionamiento) {
        $errores[] = "El número de estacionamiento es obligatorio";
    }
    if (!$vendedorId) {
        $errores[] = "Debe de elegir un vendedor";
    }


    // Validar por tamaño
    $medida = 1000 * 1000;

    if ($imagen['size'] > $medida) {
        $errores[] = "La imagen es muy grande";
    }
    // echo "<pre>";
    // var_dump($errores);
    // echo "</pre>";

    // Revisar que el arreglo de errores este vacio

    if (empty($errores)) {

        // Crear carpeta
        $carpetaImagenes = '../../imagenes/';

        if (!is_dir($carpetaImagenes)) {

            mkdir($carpetaImagenes);
        }

        $nombreImagen = '';

        // Subida de archivos
        if ($imagen['name']) {
            // Eliminar la imagen previa
            unlink($carpetaImagenes . $propiedad['imagen']);

            // Generar nombre unico para la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            // Subir la imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);

        }else{
            $nombreImagen = $propiedad['imagen'];
        }




        // Insertar en la base de datos

        $query = "UPDATE propiedades SET titulo='${titulo}', precio='${precio}', imagen = '${nombreImagen}',descripcion='${descripcion}',
        habitaciones=${habitaciones},wc=${wc},estacionamiento=${estacionamiento},vendedorId=${vendedorId} WHERE id = ${id}";

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            // echo "insertado correctamente";
            // Redireccionar a los usuarios
            header('Location: /admin?resultado=2');
        }
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
        <fieldset>
            <legend>Información General</legend>
            <label for="titulo">Titulo</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo de la propiedad" value="<?php echo $titulo ?>">

            <label for="precio">Precio</label>
            <input type="text" id="precio" name="precio" placeholder="Precio de la propiedad" value="<?php echo $precio ?>">

            <label for="imagen">Imagen</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
            <img src="/imagenes/<?php echo $imagenPropiedad; ?>" class="imagen-small" alt="">

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
            <select name="vendedor">
                <option value="">--Seleccione--</option>
                <?php while ($vendedor = mysqli_fetch_assoc($resultado)) : ?>

                    <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>">
                        <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?></option>
                <?php endwhile ?>
            </select>
        </fieldset>

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>
</main>
<?php
incluirTemplates('footer');
?>