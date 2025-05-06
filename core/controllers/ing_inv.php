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
$per=new inventario_model();

$boton= $_SESSION['boot'];

$txtnombre = $_POST['txtnombre'];
if ($txtnombre){
    $valores[0]=$_POST['txtcodigo'];
    $valores[1]=$_SESSION['institucion'];
    $valores[2]=$txtnombre;
    $valores[3]=$_POST['txtcantidad'];
    $valores[4]=$_POST['txtprecio'];
    $valores[5]=$_POST['txtubica'];
    $valores[6]=$_POST['txtdescrip'];
    $valores[7]=$_POST['txtfecha'];
    $valores[8]=$_POST['txtimage'];
    $accion = $per->set_producto($valores);
}
$productos = $per->get_productos($_SESSION['institucion']);

cargar_vista($vista,$open);
$verhome=new inventario_view();
$verhome->visualizaInv($clases,$configura.'lenguaje.php',$botones,$productos,$boton,$accion,$open,$id,$sub);
?>
