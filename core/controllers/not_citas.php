<?php
/**
 * class.
 * PHP Version 8.0.
 *
 * @see       https://www.iscosystem.com.ec
 *
 * @author    Pato Jimenez <pojimenez1@hotmail.com>
 * @test      Adriana Becerra <abbecerra@gmail.com>
 * @copyright Iscosystem Co. ltda.
 * @license   Su uso se restringue a lo descrito en la licencia en la compra del producto
 * @note      clase que permite cargar el menu superior del aplicativo
 */
cargar_model($modelo,$open);
$per=new notificaciones_model();

$boton= $_SESSION['boot'];
$vertodo= $_SESSION['vertodo'];
$codus = $_SESSION['codus'];

$clientes = $per->get_climensajes($_SESSION['institucion'],$vertodo,$codus);

cargar_vista($vista,$open);
$verhome=new notificaciones_view();
$verhome->visualizaNo($configura.'lenguaje.php',$boton,$clases,$clientes);
?>
