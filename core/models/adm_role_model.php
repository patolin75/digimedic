<?php
class rol_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_roles($code=""){
        $consulta=null;
        if (!$code)
            $sql="select * from tb_roles where id_roles >0";
        else
            $sql="select * FROM tb_roles where id_roles='".$code."'";
    
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function set_guardarrol($valores){
        if ($valores[0]==-1){//add
            $sql="insert INTO tb_roles (nombre_roles, observacion_roles,log_roles) VALUES ('".$valores[1]."', '".$valores[2]."', 'Creado el ".date('Y-m-d')." por ".$_SESSION['usernc']."')";
        }else{ //upd
            $sql= "update tb_roles SET nombre_roles = '".$valores[1]."', observacion_roles = '".$valores[2]."', log_roles = 'Actualizado el ".date('Y-m-d')." por ".$_SESSION['usernc']."' WHERE id_roles = '".$valores[0]."'";
        }
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function set_eliminarol($codigo){
        $sql= "delete from tb_roles where id_roles = '".$codigo."'";
        $consulta = Conectar::accion($this->db,$sql);

        $sql = "delete from tb_perfiles where id_roles ='".$codigo."'";
        $consulta = Conectar::accion($this->db,$sql);
        
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }
}
?>