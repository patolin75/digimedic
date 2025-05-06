<?php
class signos_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_pacientes($idinstitu){
        $consulta= "select * FROM tb_clientes where id_empresa='".$idinstitu."'";
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_especialidad($id){
        $consulta= "select id_especialidad from tb_usuarios a where a.id_usuarios =".$id;
        $personas= Conectar::select($this->db,$consulta);
        foreach($personas as $valor)
            $especia = $valor['id_especialidad'];
        return $especia; 
       
    }

    function get_medicos($instituc){
        $consulta= "select id_especialidad,id_usuarios, nombres_usuarios,apellidos_usuarios from tb_usuarios a, tb_permisos b, tb_roles c 
        where a.id_institucion=".$instituc." and b.id_usuario = a.id_usuarios and c.id_roles=b.rol_permiso and c.activa_roles =1";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function set_savevisita($code,$institu,$user,$hiscli,$visihis,$valor){
            $add=0;
            if ($hiscli ==0) { //AD
                $numhis = self::get_uniquename($code);
                $sql="insert INTO tb_historias (id_institucion, id_cliente,historias_fecha,num_historia) VALUES ('".$institu."', '".$code."','".date('Y-m-d')."','".$numhis."')";
                $consulta= Conectar::accion($this->db,$sql);

                $hiscli=self::get_idhistoria($institu,$code,$this->db);
                if (!$consulta) $error= 'e'; else $error=1;
            }
            if ($hiscli > 0){
                if ($visihis ==0){ //ADD
                    $sql="insert INTO tb_historiavisitas (id_historias, id_usuario, histovisitas_fecha,campo1) VALUES (".$hiscli.",".$user.",'".date('Y-m-d')."','".$valor."')";
                    $add=1;
                }else //UPDATE
                    $sql = "update tb_historiavisitas SET campo1='".$valor."' WHERE id_histovisitas=".$visihis." and id_historias=".$hiscli;

                $consulta1= Conectar::accion($this->db,$sql);
                if ($add ==1) $error=self::get_idvisita($hiscli,$this->db); else $error=$visihis;
                if (!$consulta1) $error= 'e';
            }
    
            return $error;
    }

    function set_eliminavisita($code){
        $sql= "delete from tb_historiavisitas WHERE id_histovisitas = ".$code;
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    static function get_idvisita($histo,$db){
        $sql = "select id_histovisitas from tb_historiavisitas where id_historias=".$histo." order by id_histovisitas desc limit 1";
        $historias= Conectar::select($db,$consulta);
        foreach($historias as $valor){
            $id = $valor['id_histovisitas'];
        }
        return $id; 

    }
    static function get_idhistoria($institu,$code,$db){
        $id=0;
        $consulta= "select id_historias from tb_historias where id_institucion='".$institu."' and id_cliente='".$code."'";
        $historias= Conectar::select($db,$consulta);
        foreach($historias as $valor){
            $id = $valor['id_historias'];
        }
        return $id; 
    }

   static function get_uniquename($name){
        $name = $name."_";
        $name= $name.date("YmdHis");
        $name= $name.substr(md5(rand(0, 10000000)), 6);
        return $name;
    }

}
?>