<?php
class marketing_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_usuario($code){
        $sql="select a.nombres_usuarios nombre,a.apellidos_usuarios apellido,a.email_usuarios mail,a.foto_usuarios foto, b.esp_nombre nomes  FROM tb_usuarios a, tb_especialidad b where a.id_usuarios=".$code." and b.id_especialidad=a.id_especialidad";
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function get_clientes($institu){
        $sql="select * from tb_clientes where id_empresa=".$institu;
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }
}
?>