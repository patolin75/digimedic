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
$per=new configurar_model();

$instituc = $per->get_instituciones();
$especi = $per->get_especilidades($filtrado);
$eslis = $per->get_especialistado($filtrado);

cargar_vista($vista,$open);
$verhome=new configurar_view();
$verhome->visualizaConf($configura.'lenguaje.php',$instituc,$open,$id,$sub,$especi,$eslis,$listade,$listaiz,$clases);
?>
