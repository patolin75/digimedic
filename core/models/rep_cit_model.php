<?php
class mensajes_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_medicos($instituc,$espec="",$vertodo="",$codus="",$campo=""){
        if ($vertodo==0 || strlen($vertodo)==0) 
            $bloqueo="and a.id_usuarios in (".$codus.")";
        else 
            $bloqueo="and a.id_usuarios in (".$vertodo.")";

        if ($instituc && !$campo)
            $consulta= "select * from tb_usuarios a, tb_permisos b where id_institucion='".$instituc."' and  b.id_usuario=a.id_usuarios and rol_permiso in (".$espec.") ".$bloqueo;
        else
            $consulta="select * from tb_usuarios a where id_institucion='".$instituc."' and id_especialidad in (".$espec.") and a.id_usuarios in (87,42) ".$bloqueo;

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_mostrarespecialistas($filtro=""){
        if (strlen($filtro) >0)
            $consulta= "select * from tb_roles where id_roles in ('".$filtro."') and activa_roles='1'";
        else
            $consulta= "select * from tb_roles where activa_roles='1'";

        $personas= Conectar::select($this->db,$consulta);

        foreach($personas as $valores){
            $idrol = $valores['id_roles'].",".$idrol;
            
        }
        $idrol = substr($idrol, 0, -1);

        return $idrol; 
    }

    function obtener_pacientes($filtro, $fecha){
            $consulta= "select * from tb_citas where DATE(cita_fechadesde) >= '".$fecha."' and id_especialista in (".$filtro.")";
            $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_cita($idagenda){
        $sql="select a.id_especialista,id_paciente,cita_apellidopaciente,cita_nombrepaciente,cita_telefono,cita_email, 
        concat(c.nombres_usuarios,' ',c.apellidos_usuarios, ' (',esp_nombre,')') as especialista, concat(tipocitas_nombre,' ',tipocitas_valor) 
        as tipo_cita,cita_confirmada,cita_observaciones ,cita_fechadesde, cita_fechahasta, id_citas from tb_citas a, tb_tipocitas b,
        tb_usuarios c,tb_especialidad d where b.id_tipocitas = a.cita_tipocita and c.id_usuarios = a.id_especialista and 
        d.id_especialidad = c.id_especialidad and id_citas='".$idagenda."'";
        $personas= Conectar::select($this->db,$sql);

        return $personas; 
    }


}