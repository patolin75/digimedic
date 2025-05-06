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
$per=new repdiario_model();

$boton= $_SESSION['boot'];

$reporte = $per->get_reportediario($_SESSION['codus'],date('Y-m-d'));
$empresa = $per->get_datosempresa($_SESSION['institucion']);

cargar_vista($vista,$open);
$verhome=new repdiario_view();
$verhome->visualizaRepd($configura.'lenguaje.php',$boton,$clases,$reporte,$empresa,$_SESSION['codus']);
?>
