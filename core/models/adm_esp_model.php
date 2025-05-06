<?php
class especialidad_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_especialidades($code=""){
        if (!$code || $code==0)
            $sql="select * FROM tb_especialidad";
        else
            $sql="select * FROM tb_especialidad where esp_idinstitucion='".$code."'";

        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function get_especialidad($codigo){
        $sql="select * FROM tb_especialidad where id_especialidad = '".$codigo."'";
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function set_guardarespecialidad($valores){
        if ($valores[0]==-1){//add
            $sql="insert INTO tb_especialidad (esp_idinstitucion,esp_nombre, esp_observacion, log_especialidad) VALUES ('".$valores[1]."','".$valores[2]."','".$valores[3]."','Creado el ".date('Y-m-d')." por ".$_SESSION['usernc']."')";
        }else{ //upd
            $sql= "update tb_especialidad SET esp_idinstitucion ='".$valores[1]."',esp_nombre = '".$valores[2]."', esp_observacion='".$valores[3]."', log_especialidad = 'Actualizado el ".date('Y-m-d')." por ".$_SESSION['usernc']."' WHERE id_especialidad = '".$valores[0]."'";
        }
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function set_eliminarespecialidad($codigo){
        $sql= "delete from tb_especialidad where id_especialidad = '".$codigo."'";
        $consulta = Conectar::accion($this->db,$sql);

    
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function get_instituciones($code=""){
        $personas=null;
        if (!$code)
            $consulta= "select * from tb_institucion";
        else
            $consulta= "select id_institucion,name_institucion from tb_institucion where id_institucion='".$code."'";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

}
?>