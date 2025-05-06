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
$per=new exa_model();

$vertodo= $_SESSION['vertodo'];
$codus = $_SESSION['codus'];
$boton= $_SESSION['boot'];

$pacientes = $per->get_pacientes($_SESSION['institucion']);
$especia= $per->get_especislistas($_SESSION['institucion']);
$examenes=$per->get_examenes($_SESSION['institucion']);

cargar_vista($vista,$open);
$verhome=new exa_view();
$verhome->visualizaView($clases,$configura.'lenguaje.php',$pacientes,$boton,$especia,$examenes,$_SESSION['institucion']);
?>
