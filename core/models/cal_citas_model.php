<?php
class calendar_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_medicos($instituc,$espec="",$vertodo="",$codus="",$campo=""){
        if (strlen($vertodo)==0 || $vertodo==0) 
            $bloqueo="and a.id_usuarios = '".$codus."'";
        else 
            $bloqueo="and a.id_usuarios in (".$vertodo.")";

        if ($instituc && !$campo)
            $consulta= "select * from tb_usuarios a, tb_permisos b where id_institucion='".$instituc."' and  b.id_usuario=a.id_usuarios and rol_permiso in (".$espec.") ".$bloqueo;
        else
            $consulta="select * from tb_usuarios a where id_institucion='".$instituc."' and id_especialidad in (".$espec.") ".$bloqueo;
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_filtromedicos($instituc,$filtro,$espec=""){
        if ($_SESSION['vertodo']!=0) $bloqueo="and a.identificacion_usuarios in (".$_SESSION['codus'].")";

        if (strlen($filtro)>0)
            $consulta= "select * from tb_usuarios a, tb_permisos b where id_institucion='".$instituc."' and  b.id_usuario=a.identificacion_usuarios and id_especialidad in (".$filtro.") and rol_permiso in (".$espec.") ".$bloqueo;
        else
            $consulta= "select * from tb_usuarios a where id_institucion='".$instituc."' and rol_permiso in (".$espec.") ".$bloqueo;
           
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_especialidades($instituc=""){
        if ($instituc && $_SESSION['vertodo']!=0){
            $consulta= "select * from tb_especialidad where esp_idinstitucion='".$instituc."'";
            $personas= Conectar::select($this->db,$consulta);
        }

        return $personas; 
    }

    function get_mostrarespecialistas($filtro=""){
        $idrol=0;
        if (strlen($filtro) >0)
            $consulta= "select * from tb_roles where id_roles in ('".$filtro."') and activa_roles='1'";
        else
            $consulta= "select * from tb_roles where activa_roles='1'";

        $personas= Conectar::select($this->db,$consulta);

        foreach($personas as $valores){
            $idrol = $valores['id_roles'].",".$idrol;
            
        }
        //if (strlen($idrol)>1) $idrol = substr($idrol, 0, -1);

        return $idrol; 
    }

    function get_dameespecialidades($instituc){
            $sql= "select id_especialidad,esp_nombre from tb_especialidad where esp_idinstitucion='".$instituc."'";
            $especial= Conectar::select($this->db,$sql);

        return $especial; 
    }

    function get_damecitas($vertodo,$codus){
        if ($vertodo ==0 || strlen($vertodo) ==0) $vertodo = $codus;
        $consulta= "select * from tb_citas where id_especialista in (".$vertodo.")";
        $personas= Conectar::select($this->db,$consulta);

        return $personas; 
    }

    function set_guardarcita($valores,$tipoc,$quehago,$confirmada,$medio){  
        $inicio= date("Y-m-d H:i",strtotime($valores[8].' '.$valores[9]));
        $fin= date("Y-m-d H:i",strtotime($valores[8].' '.$valores[10]));

        if ($quehago > 0){
            $sql= "update tb_citas SET id_especialista='".$valores[0]."', id_paciente ='".$valores[1]."', cita_apellidopaciente='".trim($valores[2]).
            "', cita_nombrepaciente='".trim($valores[3])."', cita_telefono='".$valores[4]."', cita_email='".$valores[5]."', cita_fechadesde='".$inicio."', 
            cita_fechahasta='".$fin."', cita_observaciones='".$valores[7]."', cita_tipocita='".$tipoc."', cita_confirmada='".$confirmada."'".
            "WHERE id_citas =".$quehago;
        }else{
            $sql = "insert into tb_citas (id_empresa,id_especialista,id_paciente,cita_apellidopaciente,cita_nombrepaciente,
            cita_telefono,cita_email,cita_fechagenda,cita_fechadesde,cita_fechahasta,
            cita_observaciones,cita_tipocita,cita_confirmada,cita_notificada,cita_medio) values (".$valores[11].",'".$valores[0]."','".$valores[1]."','".trim($valores[2])."','".trim($valores[3])."','".
            $valores[4]."','".$valores[5]."','".date('Y-m-d H:i:s')."','".$inicio."','".$fin."','".$valores[7]."','".$tipoc."','".$confirmada."',1,'".$medio."')"; 
        }

        if ($confirmada==1){
            //validar si existe paciente
            $pacientes= Conectar::select_contar($this->db,"select * from tb_clientes where clientes_identificacion='".$valores[1]."'");
                $contar = $pacientes->num_rows;
                if ($contar==0){
                    $sqlpac = "insert into tb_clientes (id_empresa,clientes_identificacion,clientes_nombres,clientes_apellidos,clientes_telefono,clientes_email,clientes_fecharegistro) values ".
                                "('".$valores[11]."','".$valores[1]."','".trim($valores[3])."','".trim($valores[2])."','".trim($valores[4])."','".trim($valores[5])."','".date('Y-m-d')."')";
                    $consultapac= Conectar::accion($this->db,$sqlpac);
                }
        }
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error=2; else $error= 1;

        return $error;
    }

    function get_eliminar($idagenda){
        $sql="delete from tb_citas where id_citas='".$idagenda."'";
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error=2; else $error= 1;

        return $error; 
    }

    function get_agenda($idagenda){
        $sql="select id_especialista,id_paciente,cita_apellidopaciente,cita_nombrepaciente,cita_telefono,cita_email,
        cita_tipocita,cita_confirmada,cita_observaciones,cita_fechadesde, cita_fechahasta, id_citas from tb_citas where id_citas='".$idagenda."'";
        $personas= Conectar::select($this->db,$sql);

        return $personas; 
    }

    function get_costos($codigo){
        $sql="select * from tb_tipocitas where id_especialista='".$codigo."'";
        $personas= Conectar::select($this->db,$sql);

        return $personas; 
    }

    function get_costo($codigo){
        $sql="select * from tb_tipocitas where id_tipocitas='".$codigo."'";
        $personas= Conectar::select($this->db,$sql);

        return $personas; 
    }

    function get_horarios($codigo){
        $sql="select * from tb_horarioatencion where id_especialista='".$codigo."'";
        $personas= Conectar::select($this->db,$sql);

        return $personas; 
    }

    function dame_especialista($codigo){
        $sql="select nombres_usuarios, apellidos_usuarios FROM tb_usuarios where id_usuarios='".$codigo."'";
        $personas= Conectar::select($this->db,$sql);

        return $personas; 
    }

    function dame_institucion($codigo){
        $sql="select name_institucion, direccion_institucion, logo,telefono_institucion FROM tb_institucion where id_institucion='".$codigo."'";
        $personas= Conectar::select($this->db,$sql);

        return $personas; 
    }

    function get_buscar($cadena,$institu){
            $html='';
            $sql="select * from tb_clientes where id_empresa='".$institu."' and clientes_apellidos like '%".trim($cadena)."%'";
            $personas= Conectar::select($this->db,$sql);
            foreach($personas as $filas){
                $html .= '<button type="link" onclick="javascript:cargar_cliente('.$filas['id_cliente'].',1)" class="suggest-element" data="'.$filas['clientes_apellidos'].'" id="'.$filas['id_cliente'].'">'.$filas['clientes_apellidos'].' '.$filas['clientes_nombres'].'</button>';
            }
        return $html; 
    }

    function get_recuperacliente($code,$vertodo){
        if ($vertodo ==1)
            $sql="select clientes_identificacion,clientes_apellidos,clientes_nombres,clientes_telefono,clientes_email from tb_clientes where id_cliente ='".$code."'";
        else
            $sql="select clientes_identificacion,clientes_apellidos,clientes_nombres,clientes_telefono,clientes_email from tb_clientes where clientes_identificacion ='".trim($code)."'";

        $personas= Conectar::select($this->db,$sql);
       
        return $personas; 
    }
    
}
?>