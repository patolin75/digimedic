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
 *            empresas_controler.php
 */
cargar_model($modelo,$open);
$per=new empresas_model();

if (strlen($_POST['txtcodigo'])>0){
    $valor[0] = $_POST['txtcodigo'];
    $valor[1] = $_POST['txtempresa'];
    $valor[2] = $_POST['txtdetalle'];
    $valor[3] = $_POST['txtdirecciom'];
    $valor[4] = $_POST['txtmail'];
    $valor[5] = $_POST['txttelefono'];
    
    $valor[6] = $_POST['txtimage'];

    $valor[7] = $_POST['cmbestado'];
    $accion = $per->set_instituciones($valor);
}
if ($pri) $accion = $pri;
$datos = $per->get_instituciones();
$boton= $_SESSION['boot'];

cargar_vista($vista,$open);
$verhome=new empresas_view();
$verhome->visualizaEm($datos,$configura.'lenguaje.php',$accion,$clases,$open,$id,$sub,$boton);
?>
