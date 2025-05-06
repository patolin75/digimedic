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
        if (isset($_POST['fin'])) $fin = $_POST['fin']; else $fin="";
        
        include ("api_config.php");
        require_once("../../core/models/ing_inv_model.php");
        $per=new inventario_model();

        if ($fin ==1){
            $result = $per->set_eliminaprod($code);
        }else{
            $result = $per->get_producto($code);
            $result = json_encode($result);
        }

        echo $result;
    }
?>