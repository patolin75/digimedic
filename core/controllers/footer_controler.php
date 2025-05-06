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
require_once($vista."footer_view.phtml");

if ($_SESSION['vertodo'] ==0)
    $code =$_SESSION['codus'];
else
    $code =$_SESSION['vertodo'];


$foot = new footer_view();
$foot->vista($configura.'lenguaje.php',$code);
?>
