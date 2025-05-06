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
        if (isset($_POST['what'])) $what = $_POST['what']; else $what="";
        if (!$what) header('Content-type: application/json; charset=utf-8');

        include ("api_config.php");
        require_once("../../core/models/pac_man_model.php");
        $per=new pacientes_model();

        if (isset($_POST['code'])) $code = $_POST['code']; else $code="";
        

        if ($what == 'datos'){
            if (isset($_POST['id'])) $id = $_POST['id']; else $id="";
            if (isset($_POST['sub'])) $sub = $_POST['sub']; else $sub="";

            $paciente = $per->get_paciente($code);

            $link = '?open='.encrypt('his_cli',leer()).'&kode='.encrypt('id=&sub=17&pri='.$code,leer());

            array_push($paciente,array("link"=>$link));
            $result = json_encode($paciente);
        }
        if ($what == 'resid'){
            $residencia = $per->get_residencia($code);
            $result = json_encode($residencia);
        }
        if ($what == 'adici'){
            $adicional = $per->get_adicionales($code);
            $result = json_encode($adicional);
        }
        if ($what == 'conta'){
            $contacto = $per->get_contacto($code);
            $result = json_encode($contacto);
        }
        if ($what == 'delta'){
            $result = $per->set_eliminar($code);
        }
        if ($what == "cit"){
            $citas = $per->get_citactual($code);
            $valores= "No tiene citas el resto del dia";
            $i=0;
            foreach($citas as $val){
                if ($i==0) $valores = "Cita actual:<br/>".$val['nombre'].' '.$val['apellido'].' Hora: '.$val['hora'].' ('.$val['tipocita'].') <br/> Siguientes citas: <br/>';
                else $valores=$valores.$val['nombre'].' '.$val['apellido'].' Hora: '.$val['hora'].' ('.$val['tipocita'].') <br/>';
                $i++;
            }
            $result=$valores;
        }
        if (!$what){
            $data = file_get_contents('php://input');
            $data = json_decode($data,true);
            header('Content-Type: text/html; charset=utf-8');
            $i=0;
            foreach ($data as $valor){
                $insert[$i] = $valor['name'];  
                $valos[$i] = $valor['id']; 
                $i++;
            }
            if ($valos[0]=='valtmp') 
                $result = $per->guardar_datos($insert);
            if ($valos[1]=='txtprovin')
                $result = $per->guardar_residencia($insert);
            if ($valos[1]=='txtautoiden')
                $result = $per->guardar_adicionales($insert);
            if ($valos[1]=='txtcasone') 
                $result = $per->guardar_contactos($insert);
        }
        echo $result;
    }
?>