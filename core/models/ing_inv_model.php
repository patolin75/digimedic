<?php
class inventario_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function set_producto($valores){
            if ($valores[0]==0) //ADD
                $sql= "insert into tb_inventario (id_institucion, inventario_nombre, inventario_cantidad, inventario_precio, inventario_ubicacion, inventario_descripcion, inventario_fechacompra, inventario_foto)
                 values ('".$valores[1]."','".$valores[2]."','".$valores[3]."','".$valores[4]."','".$valores[5]."','".$valores[6]."','".$valores[7]."','".$valores[8]."')";
            else
                $sql= "update tb_inventario SET  inventario_nombre = '".$valores[2]."', inventario_cantidad = '".$valores[3]."', inventario_precio='".$valores[4]."', inventario_ubicacion='".$valores[5]."', inventario_descripcion='".$valores[6]."', inventario_fechacompra='".$valores[7]."', inventario_foto='".$valores[8]."' WHERE id_inventario = '".$valores[0]."'";
            $consulta= Conectar::accion($this->db,$sql);

        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }
    function get_productos($institu){
        $sql="select id_inventario, inventario_nombre nombre, inventario_cantidad cantidad, inventario_precio precio, inventario_ubicacion ubicacion,
        inventario_descripcion descripcion, inventario_fechacompra fecha, inventario_foto foto FROM tb_inventario where id_institucion =".$institu;
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function get_producto($codigo){
        $sql="select inventario_foto foto, inventario_foto, id_inventario, inventario_nombre nombre, inventario_cantidad cantidad, inventario_precio precio, inventario_fechacompra fecha,
        inventario_ubicacion ubicacion, inventario_descripcion descripcion FROM tb_inventario where id_inventario =".$codigo;
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function set_eliminaprod($codigo){
        $sql= "delete from tb_inventario WHERE id_inventario=".$codigo;
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

}
?>