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
$per=new calendar_model();

$vertodo= $_SESSION['vertodo'];
$codus = $_SESSION['codus'];

$mostra = $per->get_mostrarespecialistas(); //todos los roles que pueden agendar
$especialis=$per->get_medicos($_SESSION['institucion'],$mostra,$vertodo,$codus);

$especialidades=$per->get_especialidades($_SESSION['institucion']);
//Llamada a la vista
$boton= $_SESSION['boot'];

cargar_vista($vista,$open);
$verhome=new calendar_view();
$verhome->visualizaC($clases,$configura.'lenguaje.php',$especialis,$especialidades,$boton);
?>
