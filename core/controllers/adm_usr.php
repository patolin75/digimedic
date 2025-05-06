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
 * @note      clase que permite cargar el menu superior del aplicativoç
 *              usuario_controler.php
 */
cargar_model($modelo,$open);
$per=new usuario_model();

$txtcodigo = $_POST['txtcodigo'];
$filtrado = $_GET['filtro'];

if ($txtcodigo) {    
    $txtiduser=$_POST['txtiduser'];
    $valor[0]=$txtcodigo;
    $valor[1]=$_POST['txtnombres'];
    $valor[2]=$_POST['txtapellidos'];
    $valor[3]=$_POST['txtdireccion'];
    $valor[4]=$_POST['txtmail'];
    $valor[5]=$_POST['txttelefono'];
    $valor[6]=$_POST['txtsexo'];
    $valor[7]=$_POST['cmbtipo'];
    $valor[8]=$_POST['txtimage'];
    $valor[9]=$_POST['cmbestado'];
    $valor[10]=$_POST['cmbroles'];
    $valor[11]=$_POST['txtuser'];
    $valor[12]=$_POST['txtpassw'];
    $valor[13]=$_POST['txtpaswr'];
    $valor[14]=$_POST['cmbinstitu'];
    
    if ($valor[12] == $valor[13]){
        if ($valor[7]==2) {
            $valor[15] =$per->generar_user($valor[1],$valor[2],$valor[0]);
            $valor[12] = encrypt($valor[12],leer());
        }
        $valor[16]=$_POST['cmbespe'];
        $accion = $per->set_guardar($txtiduser, $valor);
    } 
    else
        $accion= 2;
}
if ($pri) $accion = $pri;
$datos = $per->get_usuarios("",$filtrado);
$institu = $per->get_instituciones();
$roles = $per->get_roles();
$boton= $_SESSION['boot'];

cargar_vista($vista,$open);
$verhome=new usuario_view();
$verhome->visualizaUs($configura.'lenguaje.php',$datos,$institu,$roles,$accion,$clases,$open,$id,$sub,$boton,$filtrado);
?>
