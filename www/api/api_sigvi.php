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
        if (isset($_POST['institu'])) $institu = $_POST['institu']; else $institu="";
        if (isset($_POST['user'])) $user = $_POST['user']; else $user="";
        if (isset($_POST['what'])) $what = $_POST['what']; else $what="";
        if (isset($_POST['hiscli'])) $hiscli = $_POST['hiscli']; else $hiscli="";
        if (isset($_POST['visihis'])) $visihis = $_POST['visihis']; else $visihis="";
        if (isset($_POST['valor'])) $valor = $_POST['valor']; else $valor="";

        include ("api_config.php");
        require_once("../../core/models/sig_vi_model.php");
        $per=new signos_model();
       // require_once ("../../Classes/qrcode/qrlib.php");
       // QRcode::png($data, $filename, 'Q', '2', 2);
       if ($what == 'savevis'){
            $result=$per->set_savevisita($code,$institu,$user,$hiscli,$visihis,$valor);
        }
        if ($what == 'eliminvi'){
            $result=$per->set_eliminavisita($code);
        }
        

     
        echo $result;
    }
?>