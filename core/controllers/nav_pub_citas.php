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
//Llamada al modelo


if (file_exists($modelo."nav_pub_citas_model.php")) require_once($modelo."nav_pub_citas_model.php"); else die(header('Location: ?'));
$per=new nav_pub_citas_model();

$foto = $per->get_foto($_SESSION['institucion']);

$vertodo= $_SESSION['vertodo'];
if (strlen($vertodo)==0 || $vertodo==0) $vertodo = $_SESSION['codus'];

$publici = $per->get_publicidad($_SESSION['institucion']);

require_once($vista."nav_pub_citas_view.phtml");
$verhome=new nav_pub_citas_view();
$verhome->visualizaNap($configura.'lenguaje.php',$boton,$clases,$foto,$vertodo,$publici);
?>