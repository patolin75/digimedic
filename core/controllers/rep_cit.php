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
$per=new mensajes_model();

$boton= $_SESSION['boot'];

$vertodo= $_SESSION['vertodo'];
$codus = $_SESSION['codus'];
$mostra = $per->get_mostrarespecialistas();

$especialist = $per->get_medicos($_SESSION['institucion'],$mostra,$vertodo,$codus);

cargar_vista($vista,$open);
$verhome=new mensajes_view();
$verhome->visualizaMe($configura.'lenguaje.php',$boton,$clases,$especialist);
?>
