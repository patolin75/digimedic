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
  require_once($modelo."validaid_model.php");
  require_once($base."database.php");
  
  $can=new validaid_model();
  
  $idvalidado=$can->recupera_session($_SESSION['codus'], $_COOKIE['identified']);
?>