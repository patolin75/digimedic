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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include ("api_config.php");
    require_once("../../core/models/adm_role_model.php");
    $per=new rol_model();

    if (isset($_GET['code'])) $code = $_GET['code']; else $code="";
    
    if (isset($_GET['fin'])){
        $fin = $_GET['fin']; 
        $abrir = $_GET['abrir']; 
        $id = $_GET['id']; 
        $sub = $_GET['sub']; 
    }else{
        $fin="";
        $abrir="";
        $id="";
        $sub="";
    }

    if (strlen($code)>0){
        $resp= $per->get_roles($code);
        $result = json_encode($resp);
    }

    if ($fin==1){
        $result = $per->set_eliminarol($code);
        $result = encrypt($abrir,leer())."&kode=".encrypt('id='.$id.'&sub='.$sub.'&pri='.$result,leer());
    }

    echo $result;
}

?>