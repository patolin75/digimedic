<?php
class exa_model{
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

    function get_especislistas($idins){
        $sql="select id_usuarios,apellidos_usuarios,nombres_usuarios FROM tb_usuarios a, tb_permisos b, tb_roles c where id_institucion='".$idins."' and b.id_usuario=a.id_usuarios and c.id_roles=b.rol_permiso and activa_roles=1";
        $consulta= Conectar::select($this->db,$sql);
        return $consulta;
    }

    function get_examenes($institu,$idexa=""){
        if ($idexa) $condi=" and id_cliente=".$idexa; else $condi="";
        $sql="select id_examenes as id, examenes_fechapedido as fechaped from tb_examenes where id_institucion='".$institu."' and examenes_fechaentrega is null ".$condi.' order by examenes_fechapedido desc';
        $exmanes= Conectar::select($this->db,$sql);
        return $exmanes; 
    }

    function get_examene($institu,$idexa){
        $sql="select id_examenes as idexa, id_cliente as idcliente, id_usuario as idespe,examenes_nombreespecialista as nombres,
        examenes_apellidosespecialista as apellidos,examenes_detallepedido as detalle,examenes_telefonoespe as telefono,examenes_mail as mail, 
        concat(apellidos_usuarios,' ',nombres_usuarios) as nombrealiza from tb_examenes a, tb_usuarios b where a.id_institucion='".$institu."' 
        and id_examenes=".$idexa." and b.id_usuarios=id_usuario";
        
        $exmanes= Conectar::select($this->db,$sql);
        return $exmanes; 
    }

    function get_especialista($codigo){
        $sql="select id_usuarios as id, nombres_usuarios as nombres, apellidos_usuarios as apellidos, telefono_usuarios as telefono, email_usuarios as mail from tb_usuarios where id_usuarios='".$codigo."'";
        $exmanes= Conectar::select($this->db,$sql);
        return $exmanes; 
    }
    
    function get_resultados($codigo){
        $sql="select examenes_archivo1 as archivo1, examenes_archivo2 as archivo2, examenes_archivo3 as archivo3, examenes_archivo4 as archivo4, examenes_observaciones as resultados from tb_examenes where id_examenes='".$codigo."'";
        $resulta= Conectar::select($this->db,$sql);
        return $resulta; 
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
                        $insert[16]."','".$insert[17]."','".date('Y-m-d')."')";
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

    function guardar_examen($insert){
        $cadenatmp = "";
        if ($insert[0] ==0){ //ADD
            $cadenatmp = strip_tags($insert[9]);
            if (strlen($cadenatmp) > 3){
                $campos = ",examenes_fechaentrega";
                $fecha = ",'".date('Y-m-d')."'";
            }
            $sql="insert into tb_examenes (id_usuario,examenes_fechapedido,examenes_nombreespecialista,examenes_apellidosespecialista,examenes_detallepedido,examenes_mail,examenes_telefonoespe,id_cliente,id_institucion,examenes_observaciones".$campos.") values 
            (".$insert[1].",'".date('Y-m-d')."', '".$insert[3]."','".$insert[2]."','".$insert[6]."','".$insert[5]."','".$insert[4]."','".$insert[7]."','".$insert[8]."','".$insert[9]."'".$fecha.")";
            $error=1;
        }else{
            if (strlen($insert[9]) > 12){
                $campos = ",examenes_fechaentrega='".date('Y-m-d')."'";
            }
            $sql="update tb_examenes SET examenes_nombreespecialista='".$insert[3]."',examenes_apellidosespecialista='".$insert[2]."', examenes_detallepedido='".$insert[6]."',examenes_mail='".$insert[5]."',examenes_telefonoespe='".$insert[4]."', examenes_observaciones='".$insert[9]."'".$campos." where id_examenes=".$insert[0];
            $error=2;
        }
        $consulta= Conectar::accion($this->db,$sql);

        if (!$consulta) $error='f';

        return $error;
    }

    function eliminar_examen($code){
        $sql="delete FROM tb_examenes WHERE id_examenes='".$code."'";
        $consulta= Conectar::accion($this->db,$sql);

        if (!$consulta) $error='e'; else $error= 0;
    }

    function set_guardarimagen($code,$campo,$archivo){
        $campo = "examenes_archivo".$campo;
        $error=0;
        $sql="update tb_examenes SET ".$campo."='".$archivo."' where id_examenes=".$code;
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error='f';

        return $error;
    }

    function get_uniquename($name,$original){
        $extension = pathinfo($original, PATHINFO_EXTENSION);
        $name = $name."_";
        $name= $name.date("YmdHis");
        $name= $name.substr(md5(rand(0, 10000000)), 6);
        $name= $name.".".$extension;
        return $name;
    }

    function eliminar_imagen($id,$campo){
        $campo = "examenes_archivo".$campo;
        $error=0;
        $sql="update tb_examenes SET ".$campo."=NULL where id_examenes=".$id;
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error='f';

        return $sql;
    }
}
?>