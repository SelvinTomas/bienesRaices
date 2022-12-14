<?php
require '../includes/app.php';
estaAutenticado();

use App\Propiedad;

// Implementar metodo para obtener todas las propiedades
$propiedades = Propiedad::all();


// Muestra mensaje condicional
$mensaje = $_GET['resultado'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    
    $id = filter_var($id, FILTER_VALIDATE_INT);
    

    if ($id) {
        $propiedad = Propiedad::find($id);
        $propiedad->eliminar();

    }
}



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
            <?php foreach($propiedades as $propiedad ): ?>
                <tr>
                    <td><?php echo $propiedad->id; ?></td>
                    <td><?php echo $propiedad->titulo; ?></td>
                    <td><img class="imagen-tabla" src="/imagenes/<?php echo $propiedad->imagen; ?>" alt=""></td>
                    <td><?php echo "Q." . $propiedad->precio; ?></td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad->id; ?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>
                        <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php
// Cerrar la conexion a la base de datos

mysqli_close($db);
incluirTemplates('footer');
?>