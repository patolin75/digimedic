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
    include ("api_config.php");
    require_once("../../core/models/rep_diario_model.php");
    $per=new repdiario_model();

    if (isset($_POST['code'])) $code = $_POST['code']; else $code="";
    if (isset($_POST['fecha'])) $fecha = $_POST['fecha']; else $fecha="";

    $valores = $per->get_reportediario($code,$fecha);
    $result = json_encode($valores);

    echo $result;
}

?>