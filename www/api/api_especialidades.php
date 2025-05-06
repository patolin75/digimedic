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
    require_once("../../core/models/adm_esp_model.php");
    $per=new especialidad_model();

    if (isset($_GET['code'])) $code = $_GET['code']; else $code="";
   
    if (isset($_GET['fin'])){
        $fin = $_GET['fin']; 
        $open = $_GET['abrir']; 
        $id = $_GET['id']; 
        $sub = $_GET['sub']; 
    }else{
        $fin="";
        $open="";
        $id="";
        $sub="";
    }
    
    if ($fin==1){
        $result = $per->set_eliminarespecialidad($code);
        if ($result ==1) 
            $result = encrypt($open,leer())."&kode=".encrypt('id='.$id.'&sub='.$sub.'&pri=1',leer());
        else 
            $result = encrypt($open,leer())."&kode=".encrypt('id='.$id.'&sub='.$sub.'&pri=2',leer());
        }
    if (strlen($code)>0 && !$fin){
        $resp= $per->get_especialidad($code);
        $result = json_encode($resp);
    }


    echo $result;
}
?>