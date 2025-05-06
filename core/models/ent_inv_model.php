<?php
class entregainv_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_especialistas($code){
        $sql="select id_usuarios,nombres_usuarios,apellidos_usuarios FROM tb_usuarios where id_institucion =".$code;
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function get_inventarios($code){
        $sql="select id_inventario,inventario_nombre FROM tb_inventario where id_institucion =".$code;
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function get_producto($code){
        $sql="select inventario_nombre,inventario_cantidad,inventario_foto,inventario_precio,inventario_fechacompra,inventario_ubicacion,inventario_descripcion
                FROM tb_inventario where id_inventario =".$code;
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function set_producto($code,$uentre,$recib,$quita,$existe,$observa){
        $total= $existe - $quita;
        $sql1= "update tb_inventario SET inventario_cantidad = ".$total." WHERE id_inventario = '".$code."'";
        $sql = "insert into tb_movimientoInv (id_inventario, id_usuarioentrega, id_usuariorecibe, movimientoInvcol_fecha, movimientoInvcol_cantidad, 
        movimientoInvcol_observa) values ('".$code."','".$uentre."',".$recib.",'".date('Y-m-d')."','".$quita."','".$observa."')";
        $consulta1= Conectar::accion($this->db,$sql1);
        if (!$consulta1)
            $error=2; 
        else
            $consulta= Conectar::accion($this->db,$sql);
        
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function get_pedidos($institu,$fecha){
        $sql="select inventario_nombre producto, (select concat(nombres_usuarios,' ', apellidos_usuarios) from tb_usuarios 
        where id_usuarios = a.id_usuarioentrega) entrega, concat(nombres_usuarios,' ',apellidos_usuarios) recibe, movimientoInvcol_fecha fecha, 
        sum(movimientoInvcol_cantidad) total from tb_movimientoInv a, tb_inventario b, tb_usuarios c where a.id_inventario = b.id_inventario 
        and c.id_usuarios=a.id_usuariorecibe and movimientoInvcol_fecha ='".$fecha."' and b.id_institucion=".$institu." group by producto,entrega,fecha,recibe";
        
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }
}
?>
