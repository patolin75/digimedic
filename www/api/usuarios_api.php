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
    require_once("../../core/models/adm_usr_model.php");
    $per=new usuario_model();

    if (isset($_GET['search'])) $search = $_GET['search']; else $search="";
    if (isset($_GET['code'])) $code = $_GET['code']; else $code="";

    if (isset($_GET['fin'])) $fin = $_GET['fin']; else $fin="";
    if (isset($_GET['abrir'])) $open = $_GET['abrir']; else $open = 0;
    if (isset($_GET['id'])) $id = $_GET['id']; else $id = 0;
    if (isset($_GET['sub'])) $sub = $_GET['sub']; else $sub = 0;
    if (isset($_GET['tipous'])) $tipous = $_GET['tipous']; else $tipous = 0;

    if (isset($_GET['sesion'])) $sesion = $_GET['sesion']; else $sesion = 0;

    if (isset($_GET['espe'])) $espe = $_GET['espe']; else $espe = "";
    

    
    $resp='';
    $result='';
    if (strlen($search)>0){
        $resp= $per->get_buscar($search);
        $result =  $resp;
    }

    if (strlen($code)>0 && !$fin && !$sesion && !$espe){
        $resp= $per->get_usuarios($code);
        $result = json_encode($resp);
    }

    if ($fin==1){
        $result = $per->set_eliminar($code,$tipous);
        $result = encrypt($open,leer())."&kode=".encrypt('id='.$id.'&sub='.$sub.'&pri='.$result,leer());
    }

    if ($sesion==1){
        $result = $per->set_cerrarsesion($code);
        $result = encrypt($open,leer())."&kode=".encrypt('id='.$id.'&sub='.$sub.'&pri='.$result,leer());
    }

    if ($espe){
        $result = $per->get_especialidades($code);
        $result = json_encode($result);
    }

    echo $result;
}
?>