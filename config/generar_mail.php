<?php
function procesa_mail($host,$usermail,$portmail,$smtpsecure,$passmail,$mailenvia,$nombre,$mensajes,$tipo){
        $mensaje = explode('*', $mensajes);
        
        $estilo='<!DOCTYPE html><html><head><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"></head>';
        $encabezado='<body><table class="table"><thead class="table-primary"><tr><td><img src="'.$mensaje[7].'"  width="60" height="60" style="height:60px;" /> 
                     <label style="font-size:30px; color:#0069A6;">'.$mensaje[5].'</label></td></tr></thead>';
        $pie='<div class="col-md-12 "><p class="text-muted small">Atentamente,<br> <b> NOMBRE DEL SISTEMA.</b></p><br>Direccion: '.$mensaje[6].' Telefono: '.$mensaje[8].'</div>';
    if ($tipo==1){  
        $titulo= "Activar usuario";
        $cuerpo='<tbody><tr><td>
                detalle
                </td></tr></tbody>';
    }
    if ($tipo==2){  
        $titulo= "Cita medica agendada";
        $cuerpo='<tbody><tr><td>
                Estimado(a),<br>'.$nombre.'<br><br>
                Se realizo con exito el agendamiento de la cita con el Dr(a). '.$mensaje[0].'  , bajo los siguientes parametros: <br><br>
                Fecha: '.$mensaje[1].' '.$mensaje[2].'<br>
                Tipo de cita: '.$mensaje[3].'<br>
                Cita confirmada: '.$mensaje[4].'<br><br>
                Por favor rogamos mantener pendiente el estado de su cita y su horario para evitar cualquier inconveniente, sus dudas comuniquelas al '.$mensaje[8].'
                </td></tr></tbody>';
    }

    if ($tipo==3){  
        $titulo= "Cancelacion de cita medica";
        $cuerpo='<tbody><tr><td>
                Estimado(a),<br>'.$nombre.'<br><br>
                Informamos la cancelacion de la cita con el Dr(a). '.$mensaje[0].'  , bajo los siguientes parametros: <br><br>
                Fecha: '.$mensaje[1].' '.$mensaje[2].'<br>
                Tipo de cita: '.$mensaje[3].'<br><br>
                Por favor sus dudas comuniquelas al '.$mensaje[8].'
                </td></tr></tbody>';
    }
    if ($tipo==4){  
        $titulo= "Recordatorio de cita médica";
        $cuerpo='<tbody><tr><td>
                Estimado(a),<br>'.$nombre.'<br><br>
                Es de nuestro agrado recordarle la cita con el/la Dr(a). '.$mensaje[0].'  , bajo los siguientes parametros: <br><br>
                Fecha: '.$mensaje[1].' '.$mensaje[2].'<br>
                Tipo de cita: '.$mensaje[3].'<br>
                Cita confirmada: '.$mensaje[4].'<br><br>
                Por favor rogamos mantener pendiente el estado de su cita y su horario para evitar cualquier inconveniente, sus dudas comuniquelas al '.$mensaje[8].'
                </td></tr></tbody>';
    }

    $cuerpo.='</table></body></html>';
    $mregistro= $estilo.$encabezado.$cuerpo.$pie;
    $passmail = decrypt($passmail,leer()); 
    $result= notificar($host,$usermail,$portmail,$passmail,$mailenvia,$nombre,$titulo,$mregistro,$smtpsecure,'','Razy','');
    
    return $result;
}
?>