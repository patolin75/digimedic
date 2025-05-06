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
        require_once("../../core/models/exa_paciente_model.php");
        $per=new exa_model();
            
        if ($_POST['code']) $code=$_POST['code']; else $code="";
        if ($_POST['what']) $what=$_POST['what']; else $what="";
        if ($_POST['institu']) $institu=$_POST['institu']; else $institu="";
        if ($_POST['campo']) $campo=$_POST['campo']; else $campo="";

        if ($what == 'datos'){
            $paciente = $per->get_paciente($code);
            $result = json_encode($paciente);
        }elseif ($what == 'exam'){
            $examenes = $per->get_examenes($institu,$code);
            $result =  json_encode($examenes);
        }elseif ($what == 'exm1'){
            $examenes = $per->get_examene($institu,$code);
            $result =  json_encode($examenes);
        }elseif ($what == 'especi'){
            $especialista= $per->get_especialista($code);
            $result =  json_encode($especialista);
        }elseif ($what == 'result'){
            $resultados= $per->get_resultados($code);
            $result =  json_encode($resultados);
        }elseif($what=='almac'){
            $data = json_decode($code,true);
            $i=0;
            foreach ($data as $valor){
                $insert[$i] = $valor['name'];  
                $valos[$i] = $valor['id']; 
                $i++;
            }
            if ($valos[0]=='guardar_pac') 
                $result = $per->guardar_datos($insert);
        }elseif ($what=='almaex'){
            $data = json_decode($code,true);
            $result = var_dump($data);
            $i=0;
            foreach ($data as $valor){
                $insert[$i] = $valor['name'];  
                $valos[$i] = $valor['id']; 
                $i++;
            }
            if ($valos[0]=='idexma') 
                $result = $per->guardar_examen($insert);
        }elseif ($what=='eliexa'){
            $archivos = $per->get_resultados($code);
            foreach($archivos as $valores){
                $archivo = $valores['archivo1'];
                    if ($archivo) $listo= elimina_imagen('../'.$archivo);
                $archivo = $valores['archivo2'];
                    if ($archivo) $listo= elimina_imagen('../'.$archivo);
                $archivo = $valores['archivo3'];
                    if ($archivo) $listo= elimina_imagen('../'.$archivo);
                $archivo = $valores['archivo4'];
                    if ($archivo) $listo= elimina_imagen('../'.$archivo);
            }
            $result = $per->eliminar_examen($code);
        }elseif($what=='filesub'){
            if ($code>0){
                $nombre = $per->get_uniquename($code,$_FILES['file']["name"]);
                $result = $per->set_guardarimagen($code,$campo,'images/carpeta_'.$institu.'/'.$nombre);
                if ($listo ==0) $result = cargar_archivo("../images/carpeta_".$institu, $_FILES['file']["name"],$_FILES['file']['tmp_name'],$nombre);
                $result='images/carpeta_'.$institu.'/'.$nombre;
            }else $result='f';
           
        }elseif($what=='eliima'){
            $result= elimina_imagen('../'.$institu);
            if ($result==0) $result = $per->eliminar_imagen($code,$campo);
        }

        echo $result;
    }
?>