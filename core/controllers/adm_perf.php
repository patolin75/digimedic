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
 *              perfil_controler.php
 */
cargar_model($modelo,$open);
$per=new perfil_model();


$datos = $per->get_perfiles();
$boton= $_SESSION['boot'];

cargar_vista($vista,$open);
$verhome=new perfil_view();
$verhome->visualizaPe($configura.'lenguaje.php',$datos,$institu,$roles,$accion,$clases,$open,$id,$sub,$boton);
?>
