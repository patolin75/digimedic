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
    require_once("../../core/models/cal_citas_model.php");
    require_once("../../config/inc.config.php");
    require_once("../../config/generar_mail.php");
    $per=new calendar_model();

    if ($_POST['code']) $code=$_POST['code']; else $code="";
    if ($_POST['institu']) $institu=$_POST['institu']; else $institu="";
    if ($_POST['vertodo']) $vertodo=$_POST['vertodo']; else $vertodo="";
    if ($_POST['codus']) $codus=$_POST['codus']; else $codus="";

    if ($_POST['acc']) $acc=$_POST['acc']; else $acc="";
    if ($_POST['tipoc']) $tipoc=$_POST['tipoc']; else $tipoc="";
    if ($_POST['medio']) $medio=$_POST['medio']; else $medio="";

    if ($acc=="sear") 
        $result = $per->get_buscar($code,$institu);
    elseif ($acc =='reso'){
            $mostra = $per->get_mostrarespecialistas();
            $i=0;
            if (strlen($mostra) >1) {
                $especi= $per->get_dameespecialidades($institu);
                $medic=$per->get_medicos($institu,$mostra,$vertodo,$codus);
               
                foreach ($especi as $especialidades){
                    $id=$especialidades['id_especialidad'];
                    $json[$i]["id"] = "e".$id;
                    $json[$i]["title"] = $especialidades['esp_nombre'];
                    $j=0;
                    foreach ($medic as $medicos){
                        if ($id == $medicos['id_especialidad']){
                            $json[$i]["children"][$j]["id"]=$medicos['id_usuarios'];
                            $json[$i]["children"][$j]["title"]=$medicos['nombres_usuarios']. ' '.$medicos['apellidos_usuarios'];
                            $j++;
                        }
                    }
                    $i++;
                }
            }else{
                $json[$i]["id"] = "0";
                $json[$i]["title"] ="Ninguno";
            }
            $result =json_encode($json);
    }elseif ($acc =='event'){
            $dfecha = $per->get_damecitas($vertodo,$codus);
            $i=0;
            $json[$i]['id']="";
            $json[$i]['resourceId']="";
            $json[$i]['title']="";
            foreach($dfecha as $valores){
                $json[$i]['id'] = $valores['id_citas'];
                $json[$i]['resourceId'] = $valores['id_especialista'];
                $fecha = date('Y-m-d',strtotime($valores['cita_fechadesde']));
                $hora = date ('H:i:s',strtotime($valores['cita_fechadesde']));
                $json[$i]['start'] = $fecha.'T'.$hora;
                $fecha = date('Y-m-d',strtotime($valores['cita_fechahasta']));
                $hora = date ('H:i:s',strtotime($valores['cita_fechahasta']));
                $json[$i]['end'] = $fecha.'T'.$hora;
                $json[$i]['title'] =$valores['cita_nombrepaciente'];
                if ($valores['cita_confirmada'] ==0)  $json[$i]['color'] ='orange';
                if ($valores['cita_finalizada'] ==1)  $json[$i]['color'] ='grey';

                $i++;
            }
            $result =json_encode($json);
    }elseif ($acc =='alma'){
            $array = json_decode($code, true);
            $result = $per->set_guardarcita($array,$tipoc,$institu,$codus,$medio);
            if ($result==1){
                $costo = $per->get_costo($tipoc);
                $especiali = $per->dame_especialista($array[0]);
                $institucio = $per->dame_institucion($array[11]);
                if ($codus ==1) $confi = "SI"; else $confi = "NO";
                foreach($especiali as $valo)
                    $especialista = $valo['nombres_usuarios'].' '.$valo['apellidos_usuarios'];
                foreach($costo as $valor)
                    $costvi= $valor['tipocitas_nombre'];
                foreach($institucio as $valore)
                    $extras= $valore['name_institucion'].'*'.$valore['direccion_institucion'].'*'.$valore['logo'].'*'.$valore['telefono_institucion'];
                
                $result=procesa_mail($host,$usermail,$portmail,$smtpsecure,$passmail,$array[5],trim($array[3]).' '.trim($array[2]),$especialista.'*'.$array[8].'*'.$array[9].'*'.$costvi.'*'.$confi.'*'.$extras,2);
            }
    }elseif ($acc =='recu'){
            $json = $per->get_agenda($code);
            $result =json_encode($json);
    }elseif ($acc == "cos"){
            $json = $per->get_costos($code);
            $result = json_encode($json);
    }elseif ($acc =='horar'){
            $json = $per->get_horarios($code);
            $result = json_encode($json);
    }elseif ($acc == "espe"){
            $result1 = $per->dame_especialista($code);
            foreach ($result1 as $nombre)
                $result = $nombre['nombres_usuarios'].' '.$nombre['apellidos_usuarios'];
    }elseif ($acc == "eli"){
            $agend = $per->get_agenda($code);
            foreach($agend as $values){
                $tipoc=$values['cita_tipocita'];
                $idespe= $values['id_especialista'];
                $nombrespac = $values['cita_nombrepaciente'].' '.$values['cita_apellidopaciente'];
                $mailpac= $values['cita_email'];
                $fechain = $values['cita_fechadesde'];
            }
            $result = $per->get_eliminar($code);
            if ($result==1){
                $confi=" ";
                $hora=" ";
                $costo = $per->get_costo($tipoc);
                $especiali = $per->dame_especialista($idespe);
                $institucio = $per->dame_institucion($institu);
                foreach($especiali as $valo)
                    $especialista = $valo['nombres_usuarios'].' '.$valo['apellidos_usuarios'];
                foreach($costo as $valor)
                    $costvi= $valor['tipocitas_nombre'];
                foreach($institucio as $valore)
                    $extras= $valore['name_institucion'].'*'.$valore['direccion_institucion'].'*'.$valore['logo'].'*'.$valore['telefono_institucion'];
                
                $result=procesa_mail($host,$usermail,$portmail,$smtpsecure,$passmail,$mailpac,$nombrespac,$especialista.'*'.$fechain.'*'.$hora.'*'.$costvi.'*'.$confi.'*'.$extras,3);
            }
    }elseif ($acc == "cargc"){
            $result = $per->get_recuperacliente($code,$vertodo);
            $result = json_encode($result);
    }
    echo $result;
}
?>