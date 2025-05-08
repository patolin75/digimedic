<?php
class configurar_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_instituciones($code=""){
        if (!$code)
            $consulta= "select * from tb_institucion";
        else
            $consulta= "select id_institucion,name_institucion from tb_institucion where id_institucion='".$code."'";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_medicos($instituc="",$espec=""){
        if ($instituc)
            $consulta= "select * from tb_usuarios a, tb_permisos b where id_institucion='".$instituc."' and  b.id_usuario=a.id_usuarios and rol_permiso in (".$espec.")";
           
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_mostraroles(){
        $idrol='';
        $consulta= "select * from tb_roles where activa_roles='1'";
        $personas= Conectar::select($this->db,$consulta);
        foreach($personas as $valores){
            $idrol .= $valores['id_roles'].",";
            
        }
        $idrol = substr($idrol, 0, -1);

        return $idrol; 
    }

    function get_listausuarios($instituc=""){
        if ($instituc)
            $consulta= "select id_usuarios,identificacion_usuarios,nombres_usuarios,apellidos_usuarios from tb_usuarios where id_institucion='".$instituc."'";
           
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_especilidades($code=""){
        $consulta= "select * FROM tb_roles WHERE activa_roles =0 and id_roles>0";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_especialistado($code=""){
        $consulta= "select * FROM tb_roles WHERE activa_roles =1";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 

    }

    function set_agregarespecista($rol){
        $sql= "update tb_roles SET  activa_roles = '1' WHERE id_roles = '".$rol."'";


        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }   

    function set_eliminaespecialista($rol){
        $sql= "update tb_roles SET activa_roles = '0' WHERE id_roles = '".$rol."'";
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function set_agregacosto($id,$nombre,$valor,$observac){
        $sql = "insert into tb_tipocitas (id_especialista,tipocitas_nombre,tipocitas_valor,tipocitas_observaciones) values ('".$id."','".$nombre."',".$valor.",'".$observac."')";
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error=2; else $error= 1;

        return $error;
    }

    function set_eliminacosto($codigo){
        $sql= "delete from tb_tipocitas WHERE id_tipocitas = '".$codigo."'";
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function get_cargarcostos($codigo){
        $consulta= "select * FROM tb_tipocitas WHERE id_especialista='".$codigo."'";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_listaizquierda($valores,$filtro,$roles){
        $consulta= "select * from tb_usuarios a, tb_permisos b where id_institucion=".$filtro." and  b.id_usuario=a.id_usuarios and rol_permiso in (".$roles.") and  a.id_usuarios not in (".$valores.")";
        $personas= Conectar::select($this->db,$consulta);
        return $personas;
    }

    function get_listaderecha($valores,$filtro,$roles){
        $consulta= "select * from tb_usuarios a, tb_permisos b where id_institucion=".$filtro." and  b.id_usuario=a.id_usuarios and rol_permiso in (".$roles.") and  a.id_usuarios in (".$valores.")";
        $personas= Conectar::select($this->db,$consulta);
        return $personas;
    }

    function get_valoresusuario($codigo){
        $consulta= "select usuarios_vertodo FROM tb_usuarios where id_usuarios ='".$codigo."'";

        $personas= Conectar::select($this->db,$consulta);

        foreach($personas as $valores){
            $resul = $valores['usuarios_vertodo'];
        }
        return $resul;
    }

    function set_actualiper($code,$listado){
        $sql= "update tb_usuarios SET usuarios_vertodo = '".$listado."' WHERE id_usuarios = '".$code."'";
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }


    function get_recuperahorarios($codigo){
        $consulta= "select * FROM tb_horarioatencion where id_especialista ='".$codigo."'";

        $personas= Conectar::select($this->db,$consulta);

        return $personas;
    }

    
    function set_agregahorario($id,$hdesde,$hhasta,$observac){
        $sql = "insert into tb_horarioatencion (id_especialista,hoariosatencion_horainicio,horarioatencion_horahasta,horarioatencion_observacion) values ('".$id."','".$hdesde."','".$hhasta."','".$observac."')";
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error=2; else $error= 1;

        return $error;
    }

    function set_eliminahorario($codigo){
        $sql= "delete from tb_horarioatencion WHERE id_horarioatencion = '".$codigo."'";
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function get_tb_especialidad($codigo){
        $consulta= "select id_especialidad id,esp_nombre nombre FROM tb_especialidad where esp_idinstitucion ='".$codigo."'";

        $especialid= Conectar::select($this->db,$consulta);

        return $especialid;
    }

    function set_guardarcampos($insti,$especial,$valores){
        $sql= "select * from tb_histoparametros where id_institucion=".$insti." and id_especialidad=".$especial;
        $consulta= Conectar::select_contar($this->db,$sql);
        $i=$consulta->num_rows;
        if ($i >0) {
            $sql= "update tb_histoparametros SET campo1 = '".$valores."' WHERE id_institucion = ".$insti." and id_especialidad=".$especial;
            $consulta1= Conectar::accion($this->db,$sql);
            if (!$consulta1) $error= 2; else $error=1;
        }else{
            $sql = "insert into tb_histoparametros (id_institucion, id_especialidad, campo1) values (".$insti.",".$especial.",'".$valores."')";
            $consulta1= Conectar::accion($this->db,$sql);
            if (!$consulta1) $error=2; else $error= 1;
        }
        return $error;
    }

    function get_recuperaobjetos($insti,$especial){
        $sql= "select campo1 FROM tb_histoparametros WHERE id_institucion = ".$insti." and id_especialidad=".$especial;
        $historia= Conectar::select($this->db,$sql);

        return $historia;
    }

}
?>