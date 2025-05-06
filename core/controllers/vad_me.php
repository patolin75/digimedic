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
$per=new vademecum_model();

$boton= $_SESSION['boot'];

$txtnombre = $_POST['txtlab'];
if ($txtnombre){
    $valores[0]=$_POST['txtcodigo'];
    $valores[1]=$_POST['txtregsan'];
    $valores[2]=$txtnombre;
    $valores[3]=$_POST['txtnombre'];
    $valores[4]=$_POST['txtadmin'];
    $valores[5]=$_POST['txtpresenta'];
    $accion = $per->set_producto($valores);
}
$vademecum = $per->get_vademecum();

cargar_vista($vista,$open);
$verhome=new vademecum_view();
$verhome->visualizaVade($clases,$configura.'lenguaje.php',$botones,$vademecum,$boton,$accion,$open,$id,$sub);
?>