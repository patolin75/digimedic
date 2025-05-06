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
$per=new especialidad_model();

$txtcodigo= $_POST['txtcodigo'];
$filtrado = $_GET['filtro'];

if ($txtcodigo){
    $valores[0] = $txtcodigo;
    $valores[1] = $_POST['cmbinstitu'];
    $valores[2] = $_POST['txtnombres'];
    $valores[3] = $_POST['txtobserva'];
    $accion = $per->set_guardarespecialidad($valores);
}
if ($pri) $accion = $pri;
$datos = $per->get_especialidades($filtrado);
$boton= $_SESSION['boot'];
$institu= $per->get_instituciones();

cargar_vista($vista,$open);
$verhome=new especialidades_view();
$verhome->visualizaEsp($configura.'lenguaje.php',$datos,$institu,$roles,$accion,$clases,$open,$id,$sub,$boton,$filtrado);
?>
