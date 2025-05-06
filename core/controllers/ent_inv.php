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
$per=new entregainv_model();

$especialistas = $per->get_especialistas($_SESSION['institucion']);
$inventario = $per->get_inventarios($_SESSION['institucion']);

$botones =  $_SESSION['boot'];
$entregas = $per->get_pedidos($_SESSION['institucion'],date('Y-m-d'));

cargar_vista($vista,$open);
$verhome=new entregainv_view();
$verhome->visualizaEInv($clases, $configura.'lenguaje.php',$botones,$especialistas,$inventario,$_SESSION['codus'],$entregas);
?>
