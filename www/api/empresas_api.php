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
    require_once("../../core/models/adm_emp_model.php");
    $per=new empresas_model();

    if (isset($_POST['code'])) $code = $_POST['code']; else $code = 0;

    if (isset($_POST['fin'])) $final = $_POST['fin']; else $final = 0;
    if (isset($_POST['abrir'])) $open = $_POST['abrir']; else $open = 0;
    if (isset($_POST['id'])) $id = $_POST['id']; else $id = 0;
    if (isset($_POST['sub'])) $sub = $_POST['sub']; else $sub = 0;

    if ($final==1){
        $result= $per->set_eliminains($code);
        if ($result ==1) 
            $result = encrypt($open,leer())."&kode=".encrypt('id='.$id.'&sub='.$sub.'&pri=1',leer());
        else 
            $result = encrypt($open,leer())."&kode=".encrypt('id='.$id.'&sub='.$sub.'&pri=2',leer());
    }else{
        $empresa= $per->get_instituciones($code);
        $result = json_encode($empresa);
    }
    echo $result; 
}

?>
