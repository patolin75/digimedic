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
       // if (isset($_POST['elemen'])) $elemen = $_POST['elemen']; else $elemen="";
        if (isset($_POST['valor'])) $valor = $_POST['valor']; else $valor="";
        
        include ("api_config.php");
        require_once("../../core/models/his_cli_model.php");
        $per=new hiscli_model();
        
        require_once ("../../Classes/qrcode/qrlib.php");

        if ($what == 'pac'){
            $datos=$per->get_paciente($code);
            $result = json_encode($datos);
        }elseif ($what == 'hist'){
            $datos=$per->get_historia($institu,$code);
            $result = json_encode($datos);
        }elseif ($what == 'qr'){
            $datos = $per->get_qr($code);
            $imgQR= QRcode::png($datos,'*','Q', '2', 2);
            $result = $imgQR;
        }elseif ($what == 'visi'){
            $datos=$per->get_visitas($user,$code);
            $result = json_encode($datos);
        }elseif ($what == 'valvisit'){
            $datos=$per->get_valoresvisitas($code);
            $result = json_encode($datos);
        }elseif ($what =='exam'){
            $datos=$per->get_examenes($code,$user,$institu);
            $result = json_encode($datos);
        }elseif ($what =='vade'){
            $datos=$per->get_vademecum();
            $result = json_encode($datos);
        }elseif ($what =='grabar')
           $result=$per->set_savevisita($code,$institu,$user,$hiscli,$visihis,$valor);
        elseif ($what =='grabarexa')
            $result=$per->set_updaexam($code,$valor);
        elseif ($what =='cerrarse')
            $result=$per->set_cerrarse($code);
        elseif ($what =='grafica'){
            $datos=$per->get_valgrafica($code,$hiscli);
            $result = json_encode($datos);
        }

        echo $result;
    }
?>