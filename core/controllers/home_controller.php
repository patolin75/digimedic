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
$per=new home_model();

$nuevito = $_GET['new'];
if ($nuevito) $nuevis= decrypt($nuevito,leer());

cargar_vista($vista,$open);
$verhome=new home_view();
$verhome->visualizar($datos,$nuevis,$configura.'lenguaje.php');

?>

