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
 *              rol_controler.php
 */
cargar_model($modelo,$open);
$per=new rol_model();

$txtcodigo= $_POST['txtcodigo'];
if ($txtcodigo){
    $valores[0] = $txtcodigo;
    $valores[1] = $_POST['txtnombres'];
    $valores[2] = $_POST['txtobserva'];
    $accion = $per->set_guardarrol($valores);
}
if ($pri) $accion = $pri;
$datos = $per->get_roles();
$boton= $_SESSION['boot'];

cargar_vista($vista,$open);
$verhome=new rol_view();
$verhome->visualizaRo($configura.'lenguaje.php',$datos,$institu,$roles,$accion,$clases,$open,$id,$sub,$boton);
?>
