<?php
session_start();

$auth = $_SESSION['login'];

if(!$auth){
    header('Location: /');
}


// Importar la conexion de la base de datos
require '../includes/config/database.php';
$db = conectarDB();

// Escribir el query
$query = "SELECT * FROM propiedades";

// Consultar la base de datos
$consulta = mysqli_query($db, $query);


// Muestra mensaje condicional
$mensaje = $_GET['resultado'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    
    $id = filter_var($id, FILTER_VALIDATE_INT);
    

    if ($id) {

        // Eliminar el archivo
        $query = "SELECT imagen FROM propiedades WHERE id = ${id}";
        $resultado = mysqli_query($db, $query);
        $propiedad = mysqli_fetch_assoc($resultado);
        unlink('../imagenes/' . $propiedad['imagen']);


        // Eliminar la propiedad
        $query = "DELETE FROM propiedades WHERE id = ${id}";
       

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            header('Location: /admin?resultado=3');
        }
    }
}


require '../includes/funciones.php';
// Incluye el template
incluirTemplates('header');

?>

<main class="contenedor seccion">
    <h1>Administrador de bienes raices</h1>
    <?php if (intval($mensaje) === 1) : ?>
        <p class="alerta exito">Anuncio creado correctamente</p>
    <?php elseif (intval($mensaje) === 2) : ?>
        <p class="alerta exito">Anuncio modificado correctamente</p>
    <?php elseif (intval($mensaje) === 3) : ?>
        <p class="alerta exito">Anuncio eliminado correctamente</p>
    <?php endif; ?>
    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva propiedad</a>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <!-- Mostrar los resultados -->
        <tbody>
            <?php while ($propiedad = mysqli_fetch_assoc($consulta)) : ?>
                <tr>
                    <td><?php echo $propiedad['id']; ?></td>
                    <td><?php echo $propiedad['titulo']; ?></td>
                    <td><img class="imagen-tabla" src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt=""></td>
                    <td><?php echo "Q." . $propiedad['precio']; ?></td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>
                        <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php
// Cerrar la conexion a la base de datos

mysqli_close($db);
incluirTemplates('footer');
?>