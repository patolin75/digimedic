<?php
class home_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    public function get_menus(){
        $personas=null;
        $consulta="select * from tb_menus where menus_principal=0 order by menus_principal";
        $personas= Conectar::select($this->db,$consulta);
        return $personas;
    }

    public function get_permisos(){
        $personas=null;
        $consulta= "select b.id_menus,menus_principal,c.menus_nombre,c.link, c.menu_imagen,menu_principal,crear_menus,modificar_menus,eliminar_menus from tb_permisos a, tb_perfiles b, tb_menus c where id_usuario='".limpiar($this->db,$_SESSION['codus'])."' and b.id_roles = a.rol_permiso and c.id_menus=b.id_menus order by id_menus";
        $personas= Conectar::select($this->db,$consulta);
        return $personas;
    }

    public function get_superior(){
        $consulta=null;
        $consulta= "select * from tb_institucion where id_institucion='".limpiar($this->db,$_SESSION['institucion'])."'";

        $instituciones= Conectar::select($this->db,$consulta);
        return $instituciones;
    }


    function set_actualizapass($idus,$passant,$pass){
        $sql="select * from tb_usuario WHERE usuario_usuario='".$idus."' and usuario_clave='".$passant."'";
        $consulta= Conectar::select_contar($this->db,$sql);
        $contar = $consulta->num_rows;
        if ($contar>0) {
            $actualiza= Conectar::accion($this->db,"update tb_usuario SET usuario_clave = '".$pass."' WHERE usuario_usuario='".$idus."' and usuario_clave='".$passant."'");
            $error=1;
        } else $error=2;
        return $error;
    }

    public function get_vertodo(){
        $personas=null;
        $consulta="select usuarios_vertodo from tb_usuarios where id_usuarios='".limpiar($this->db,$_SESSION['codus'])."'";
        $personas= Conectar::select($this->db,$consulta);

        foreach($personas as $value){
            $_SESSION['vertodo']=$value['usuarios_vertodo'];
        }
        return $error;
    }
}
?>