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
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['code'])) $code = $_POST['code']; else $code="";
        if (isset($_POST['what'])) $what = $_POST['what']; else $what="";
        if (isset($_POST['uentre'])) $uentre = $_POST['uentre']; else $uentre="";
        if (isset($_POST['recib'])) $recib = $_POST['recib']; else $recib="";
        if (isset($_POST['quita'])) $quita = $_POST['quita']; else $quita="";
        if (isset($_POST['existe'])) $existe = $_POST['existe']; else $existe="";
        if (isset($_POST['observa'])) $observa = $_POST['observa']; else $observa="";

        include ("api_config.php");
        require_once("../../core/models/ent_inv_model.php");
        $per=new entregainv_model();

        if ($what == 'recupera'){
            $datos=$per->get_producto($code);
            $result = json_encode($datos);
        }
        if ($what =='entrega'){
            $result=$per->set_producto($code,$uentre,$recib,$quita,$existe,$observa);
        }
        
        echo $result;
    }
?>