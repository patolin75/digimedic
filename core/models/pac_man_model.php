<?php
class pacientes_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_pacientes($idinstitu){
        $consulta= "select * FROM tb_clientes where id_empresa='".$idinstitu."'";
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_paciente($id){
        $sql= "select id_cliente,clientes_identificacion, clientes_foto,clientes_foto,clientes_apellidos,clientes_nombres,clientes_telefono,
        clientes_email,clientes_tiposangre,clientes_estadocivil,clientes_sexo,DATE_FORMAT(clientes_fechanac,'%Y-%m-%d'),concat (TIMESTAMPDIFF(YEAR,clientes_fechanac,CURDATE()),' años') AS edad,
        clientes_nacionalida,clientes_provincia,clientes_canton, clientes_parroquia, clientes_fecharegistro from tb_clientes where id_cliente='".$id."'";
        $consulta= Conectar::select($this->db,$sql);
        return $consulta;
    }

    function get_residencia($id){
        $consulta= "select residencia_provincia,residencia_canton,residencia_parroquia,residencia_cprincipal,residencia_numero,
        residencia_csecundaria,residencia_barrio,residencia_referencia FROM tb_residencia where id_cliente='".$id."'";
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_adicionales($id){
        $consulta= "select adicional_ideetnica,adicional_nacioetnica,adicional_niveleduc,adicional_estadonivel,adicional_ocupacion,
        adicional_trabajo,adicional_tiposeguro,adicional_discapa from tb_adicionales where id_cliente='".$id."'";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_contacto($id){
        $consulta= "select contacto_llamara,contacto_parentezco,contacto_telefono,contacto_direccion from tb_contacto where id_cliente='".$id."'";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function guardar_datos($insert){
        if ($insert[1] ==0){ //ADD
            if (strlen($insert[2]) >0 && strlen($insert[5]) >0 && strlen($insert[6]) >0 && strlen($insert[7]) >0 && strlen($insert[8]) >0 && strlen($insert[9]) >0 && strlen($insert[12]) >0){
                $sql1 ="select max(id_cliente) +1 FROM tb_clientes FOR UPDATE";
                $id = Conectar::select($this->db,$sql1);

                $sql= "select clientes_identificacion from tb_clientes where clientes_identificacion='".$insert[2]."'";
                $consulta= Conectar::select_contar($this->db,$sql);
                $i=$consulta->num_rows;
                if ($i >0) 
                    $error='r';
                else
                    $sql="insert into tb_clientes (id_cliente, id_empresa, clientes_identificacion, clientes_nombres, clientes_apellidos, clientes_telefono, 
                        clientes_email, clientes_tiposangre, clientes_fechanac, clientes_foto, clientes_estadocivil, clientes_sexo, 
                        clientes_nacionalida, clientes_provincia, clientes_canton, clientes_parroquia, clientes_fecharegistro) values (".$id[0][0].",".$insert[0].",'".$insert[2]."','".$insert[6]."','".$insert[5]."','".
                        $insert[7]."','".$insert[8]."','".$insert[9]."','".date('Y-m-d',strtotime($insert[12]))."','".$insert[4]."','".$insert[10]."','".$insert[11]."','".$insert[14]."','".$insert[15]."','".
                        $datos[16]."','".$datos[17]."','".date('Y-m-d')."')";
            }else
                $error='f';
        }else{ //UPD
            if (strlen($insert[2]) >0 && strlen($insert[5]) >0 && strlen($insert[6]) >0 && strlen($insert[7]) >0 && strlen($insert[8]) >0 && strlen($insert[9]) >0 && strlen($insert[12]) >0){
                $sql="update tb_clientes SET clientes_identificacion='".$insert[2]."', clientes_nombres ='".$insert[6]."', clientes_apellidos='".$insert[5]."', clientes_telefono='".$insert[7].
                "', clientes_email='".$insert[8]."', clientes_tiposangre='".$insert[9]."', clientes_fechanac='".date('Y-m-d',strtotime($insert[12]))."', clientes_foto='".$insert[4]."', clientes_estadocivil='".
                $insert[10]."', clientes_sexo='".$insert[11]."', clientes_nacionalida='".$insert[14]."', clientes_provincia='".$insert[15]."', clientes_canton='".$insert[16]."', clientes_parroquia='".
                $insert[17]."' where id_cliente='".$insert[1]."'";
                $id[0][0]=$insert[1];
            }
            else
                $error='f';
        }
        if (!$error){
            $consulta= Conectar::accion($this->db,$sql);
            if (!$consulta) $error='f'; else $error = $id[0][0];
        }
       
        return $error;
    }

    function guardar_residencia($insert){
        $pos = strpos($insert[0],'-',0);
        $cod = substr($insert[0],0,$pos);
        $acc = substr($insert[0],$pos+1,strlen($insert[0]));

        $sql = "select * from tb_residencia where id_cliente=".$cod;
        $consultac= Conectar::select_contar($this->db,$sql);
        $i=$consultac->num_rows;
        if ($i >0) $acc=1; else $acc=0;

        if ($acc ==0){ //ADD
            $sql="insert into tb_residencia (id_cliente, residencia_provincia, residencia_canton, residencia_parroquia, residencia_cprincipal, residencia_numero, residencia_csecundaria, residencia_barrio, residencia_referencia)
            VALUES (".$cod.",'".$insert[1]."','".$insert[2]."','".$insert[3]."','".$insert[4]."','".$insert[5]."','".$insert[6]."','".$insert[7]."','".$insert[8]."')";
        }else{ //UPD
            $sql="update tb_residencia SET residencia_provincia='".$insert[1]."', residencia_canton='".$insert[2]."', residencia_parroquia='".$insert[3]."', residencia_cprincipal='".$insert[4]."', residencia_numero='".$insert[5]."', residencia_csecundaria='".$insert[6]."', residencia_barrio='".$insert[7]."', residencia_referencia='".$insert[8]."' where id_cliente='".$cod."'";
        }
        if (!$error){
            $consulta= Conectar::accion($this->db,$sql);
            if (!$consulta) $error='e'; else $error= 0;
        }
        $error = $insert[0];
        return $error;
    }

    function guardar_adicionales($insert){
        $pos = strpos($insert[0],'-',0);
        $cod = substr($insert[0],0,$pos);
        $acc = substr($insert[0],$pos+1,strlen($insert[0]));

        $sql = "select * from tb_adicionales where id_cliente=".$cod;
        $consultac= Conectar::select_contar($this->db,$sql);
        $i=$consultac->num_rows;
        if ($i >0) $acc=1; else $acc=0;

        if ($acc ==0){ //ADD
                $sql="insert into tb_adicionales (id_cliente, adicional_ideetnica, adicional_nacioetnica, adicional_niveleduc, adicional_estadonivel, adicional_ocupacion, adicional_trabajo, adicional_tiposeguro, adicional_discapa)
                VALUES ('".$cod."','".$insert[1]."','".$insert[2]."','".$insert[3]."','".$insert[4]."','".$insert[5]."','".$insert[6]."','".$insert[7]."','".$insert[8]."')";
        }else{ //UPD
                $sql="update tb_adicionales SET adicional_ideetnica='".$insert[1]."', adicional_nacioetnica='".$insert[2]."', adicional_niveleduc='".$insert[3]."', adicional_estadonivel='".$insert[4]."', adicional_ocupacion='".$insert[5]."', adicional_trabajo='".$insert[6]."', adicional_tiposeguro='".$insert[7]."', adicional_discapa='".$insert[8]."' where id_cliente='".$cod."'";
        }
        
        if (!$error){
            $consulta= Conectar::accion($this->db,$sql);
            if (!$consulta) $error='e'; else $error= 0;
        }
        return $error;
    }

    function guardar_contactos($insert){
        $pos = strpos($insert[0],'-',0);
        $cod = substr($insert[0],0,$pos);
        $acc = substr($insert[0],$pos+1,strlen($insert[0]));

        $sql = "select * from tb_contacto where id_cliente=".$cod;
        $consultac= Conectar::select_contar($this->db,$sql);
        $i=$consultac->num_rows;
        if ($i >0) $acc=1; else $acc=0;

        if ($acc ==0){ //ADD
                $sql="insert into tb_contacto (id_cliente, contacto_llamara, contacto_parentezco, contacto_telefono, contacto_direccion)
                VALUES ('".$cod."','".$insert[1]."','".$insert[2]."','".$insert[3]."','".$insert[4]."')";
        }else{ //UPD
                $sql="update tb_contacto SET contacto_llamara='".$insert[1]."', contacto_parentezco='".$insert[2]."', contacto_telefono='".$insert[3]."', contacto_direccion='".$insert[4]."' where id_cliente='".$cod."'";
        }
        if (!$error){
            $consulta= Conectar::accion($this->db,$sql);
            if (!$consulta) $error='e'; else $error= 0;
        }
        return $error;
    }

    function set_eliminar($codigo){
        $sql="delete a,b,c,d FROM tb_clientes a LEFT JOIN tb_contacto b ON b.id_cliente = a.id_cliente LEFT JOIN tb_adicionales c  ON c.id_cliente = a.id_cliente
        LEFT JOIN tb_residencia d ON d.id_cliente = a.id_cliente LEFT JOIN tb_historias e ON e.id_cliente = a.id_cliente WHERE a.id_cliente='".$codigo."'";

        $consulta= Conectar::accion($this->db,$sql);
         
        if (!$consulta) $error='e'; else $error= 0;
    }

    function get_citactual($code){
        $hora=date('G:i');
        $hora=date ('G:i',strtotime ('-1 hour' , strtotime ( $hora ) ));
        if (strlen($hora)==4) $hora='0'.$hora;
       /* $sql="select  DATE_FORMAT(cita_fechadesde, '%Y-%m-%d') as fecha, cita_nombrepaciente as nombre,cita_apellidopaciente as apellido,  DATE_FORMAT(cita_fechadesde, '%H:%i') 
        as hora from tb_citas where  DATE_FORMAT(cita_fechadesde, '%Y-%m-%d') = '".date('Y-m-d')."' and 
        DATE_FORMAT(cita_fechadesde, '%H:%i') >= '".$hora."' 
        and cita_finalizada=0 and id_especialista='".$code."' and cita_confirmada=1 ORDER by fecha LIMIT 3";*/

        $sql="select  DATE_FORMAT(cita_fechadesde, '%Y-%m-%d') as fecha, cita_nombrepaciente as nombre,cita_apellidopaciente as apellido,  DATE_FORMAT(cita_fechadesde, '%H:%i') 
        as hora, tipocitas_nombre as tipocita from tb_citas a, tb_tipocitas b where  DATE_FORMAT(cita_fechadesde, '%Y-%m-%d') = '".date('Y-m-d')."' and DATE_FORMAT(cita_fechadesde, '%H:%i') >=  '".$hora."'
        and a.cita_finalizada=0 and a.id_especialista='".$code."' and cita_confirmada=1 and b.id_especialista = a.id_especialista and b.id_tipocitas= cita_tipocita  ORDER by fecha LIMIT 3";
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error='e'; else $error= 0;

        return $consulta;
    }
}
?>