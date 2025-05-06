<?php
class empresas_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }


    public function get_instituciones($code=""){
        $personas=null;

        if (!$code)
            $consulta= "select * from tb_institucion";
        else
            $consulta= "select * from tb_institucion where id_institucion='".$code."'";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function set_instituciones($valores){
        $consulta=null;

        if (strlen($valores[6]) == 0 || self::es_image($valores[6])){
            if ($valores[0]==0)
                $sql= "insert into tb_institucion (name_institucion, detalle_institucion, direccion_institucion, mail_institucion,telefono_institucion,logo,estado) values ('".$valores[1]."','".$valores[2]."','".$valores[3]."','".$valores[4]."','".$valores[5]."','".$valores[6]."','".$valores[7]."')";
            else
                $sql= "update tb_institucion SET  name_institucion = '".$valores[1]."', detalle_institucion = '".$valores[2]."', direccion_institucion='".$valores[3]."', mail_institucion='".$valores[4]."', telefono_institucion='".$valores[5]."', logo='".$valores[6]."', estado='".$valores[7]."' WHERE id_institucion = '".$valores[0]."'";
            $consulta= Conectar::accion($this->db,$sql);
        }
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function set_eliminains($codigo){
        $sql= "delete from tb_institucion WHERE id_institucion = '".$codigo."'";
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    public static function es_image($path)
    {
       if (substr($path,0,10) =='data:image') 
            return true; 
        else 
            return false;
    }

}
?>