<?php
class perfil_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_perfiles(){
        $sql="select a.id_roles, a.nombre_roles, count(id_menus) total_menus FROM tb_roles a left join tb_perfiles b on b.id_roles = a. id_roles where a.id_roles >0 group by a.id_roles, a.nombre_roles";
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function get_menusprincipales(){
        $sql="select * from tb_menus where menus_principal=0";
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function get_submenus($rol,$menu){
        $sql="select a.id_menus as codmen, a.menus_principal, a.menus_nombre, b.* FROM tb_menus a left join tb_perfiles b on b.id_menus = a.id_menus and id_roles ='".$rol."' where menus_principal='".$menu."'";
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function set_agregarpermisos($rol,$menu,$id,$tipo,$accion){
        //rol id rol
        //menu cod menus
        //id es sub
        //tipo C M E
        //$accion add del adds

        //SUBMENUS
       
        if ($tipo=='f'){
            if ($accion =='add'){
                $sql= "insert into tb_perfiles(id_roles,id_menus,menu_principal) VALUES ('".$rol."','".$id."','".$menu."')";
                $consulta= Conectar::accion($this->db,$sql);

                $menupri= Conectar::select_contar($this->db,"select * from tb_menus a, tb_perfiles b  where a.id_menus='".$menu."' and b.id_menus=a.id_menus and b.id_roles='".$rol."'");
                $contar = $menupri->num_rows;
                if ($contar==0){
                    //insertar menu princi
                    unset ($consulta);
                    $sql= "insert into tb_perfiles(id_roles,id_menus) VALUES ('".$rol."','".$menu."')";
                    $consulta= Conectar::accion($this->db,$sql);
                }
            }elseif($accion=='del'){
                $sql= "delete from tb_perfiles WHERE id_roles='".$rol."' and id_menus='".$id."'";
                $consulta= Conectar::accion($this->db,$sql);

                $menupri= Conectar::select_contar($this->db,"select * from tb_menus a, tb_perfiles b  where a.menus_principal='".$menu."' and b.id_menus=a.id_menus and b.id_roles='".$rol."'");
                $contar = $menupri->num_rows;
                if ($contar==0){
                    //eliminar menu princi
                    unset ($consulta);
                    $sql= "delete from tb_perfiles WHERE id_roles='".$rol."' and id_menus='".$menu."'";
                    $consulta= Conectar::accion($this->db,$sql);
                }
            }
            if (!$consulta) $error= 2; else $error=1; 
        }else{
           
            if ($accion =='adds'){
                $sql= "insert into tb_perfiles(id_roles,id_menus) VALUES ('".$rol."','".$id."')";
                $consulta= Conectar::accion($this->db,$sql);

                $menupri= Conectar::select_contar($this->db,"select * from tb_menus a, tb_perfiles b  where a.id_menus='".$menu."' and b.id_menus=a.id_menus and b.id_roles='".$rol."'");
                $contar = $menupri->num_rows;
                if ($contar==0){
                    //insertar menu princi
                    unset ($consulta);
                    $sql= "insert into tb_perfiles(id_roles,id_menus) VALUES ('".$rol."','".$menu."')";
                    $consulta= Conectar::accion($this->db,$sql);
                }

                if ($tipo =='c') 
                    $senten = "crear_menus='1' ";
                elseif($tipo =='m') 
                    $senten = "modificar_menus ='1' ";
                elseif($tipo=='e')
                    $senten = "eliminar_menus = '1' ";
                $sql = "update tb_perfiles SET ".$senten." WHERE id_menus = '".$id."' and id_roles='".$rol."'";
                $consulta= Conectar::accion($this->db,$sql);

            }elseif ($accion =='add'){
                if ($tipo =='c') 
                    $senten = "crear_menus='1' ";
                elseif($tipo =='m')
                    $senten = "modificar_menus ='1' ";
                elseif($tipo=='e')
                    $senten = "eliminar_menus = '1' ";
                $sql = "update tb_perfiles SET ".$senten." WHERE id_menus = '".$id."' and id_roles='".$rol."'";
                $consulta= Conectar::accion($this->db,$sql);
                $error=$sql;

            }elseif ($accion =='del'){
                if ($tipo =='c') 
                    $senten = "crear_menus='0' ";
                elseif($tipo =='m')
                    $senten = "modificar_menus ='0' ";
                elseif($tipo=='e')
                    $senten = "eliminar_menus = '0' ";
                $sql = "update tb_perfiles SET ".$senten." WHERE id_menus = '".$id."' and id_roles='".$rol."'";
                $consulta= Conectar::accion($this->db,$sql);

            }

            if (!$consulta) $error= 2; else $error=1;
           
        }
        return $error;
    }

  

}
?>