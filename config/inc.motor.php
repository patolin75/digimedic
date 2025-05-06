<?php
/**
 * class.
 * PHP Version 8.0
 *
 * @see      https://www.iscosystem.com.ec
 *
 * @author    Pato Jimenez <pojimenez1@hotmail.com>
 * @test      Adriana Becerra <abbecerra@gmail.com>
 * @copyright Iscosystem Co. ltda.
 * @license   Su uso se restringue a lo descrito en la licencia en la compra del producto
 * @note      Super importante, permite cargar los controladores del sitios, no se puso el método AUTH para evitar demasiada carga en el sitio
 */

$open=trim(str_replace(" ","+",$open));
$open=decrypt($open,leer());

echo ".................................................................".$open;
if ($open) {
    echo '<div id="contenedor_uno">Cargando elementos, por favor espere...<div class="preloader_uno" id="preloader"></div></div>';
    if (!file_exists($controlador.$open.".php")){
        $open="home";
        include $controlador.$open."_controller.php";
    }
    else
        include $controlador.$open.".php";
    echo '<script>var div = document.getElementById("contenedor_uno");div.style.display = "none";</script>';
}
else {
        $open="home";
        include $controlador.$open."_controller.php";
}
?>