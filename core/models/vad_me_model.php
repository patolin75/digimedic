
<?php
class vademecum_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_vademecum(){
        $sql="select * FROM tb_vademecum";
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function get_elemenvadem($code){
        $sql="select id_vademecum,vademecum_regsan,vademecum_laboratorio, vademecum_nombreprod,vademecum_administracion,vademecum_presentacion
         FROM tb_vademecum where id_vademecum=".$code;
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function set_producto($valores){
        if ($valores[0]==0) //ADD
        $sql= "insert into tb_vademecum (vademecum_regsan, vademecum_laboratorio, vademecum_nombreprod, vademecum_administracion, vademecum_presentacion)
                values ('".$valores[1]."','".$valores[2]."','".$valores[3]."','".$valores[4]."','".$valores[5]."')";
        else
            $sql= "update tb_vademecum SET vademecum_regsan='".$valores[1]."', vademecum_laboratorio ='".$valores[2]."', vademecum_nombreprod='".$valores[3]."', vademecum_administracion ='".$valores[4]."', 
            vademecum_presentacion='".$valores[5]."' WHERE id_vademecum = '".$valores[0]."'";
        $consulta= Conectar::accion($this->db,$sql);

        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function set_eliminavadem($code){
        $sql= "delete from tb_vademecum WHERE id_vademecum=".$codigo;
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }
}
?>