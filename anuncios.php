<?php
require 'includes/funciones.php';

incluirTemplates('header');

?>

<main class="contenedor seccion">
    <h2>Casas y departamentos en venta</h2>

    <?php
    $limite = 10;
    include 'includes/templates/anuncios.php';

    ?>
</main>
<?php
incluirTemplates('footer');
?>