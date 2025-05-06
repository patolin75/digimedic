<?php
class usuario_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
       
    }

    function get_usuarios($code="",$filtro=""){
        $personas=null;
        if (!$code)
            if (!$filtro || $filtro==0)
                $consulta= "select * from tb_usuarios";
            else
                $consulta= "select * from tb_usuarios where id_institucion='".$filtro."'";
        else
            $consulta= "select a.*,b.rol_permiso,c.usuario_usuario FROM tb_usuarios a  LEFT JOIN tb_permisos b on b.id_usuario=a.id_usuarios LEFT JOIN tb_usuario c on c.usuario_identificacion=a.identificacion_usuarios where a.identificacion_usuarios='".$code."'";
        
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_roles($code=""){
        $personas=null;
        $consulta= "select * from tb_roles";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_buscar($cadena){
        $sql="select * from tb_usuarios where nombres_usuarios like '%".strip_tags($cadena)."%' or apellidos_usuarios like '%".strip_tags($cadena)."%'";
        $consulta= Conectar::select($this->db,$sql);
        $html='';
        
        foreach($consulta as $filas){
            if (strlen($filas['foto_usuarios'])>0){
                $fotous=$filas['foto_usuarios'];
            }else $fotous='images/foto.jpg';
            $html .= '<div><table><tr><td><img class="img-profile rounded-circle" src="'.$fotous.'" width="50" height="50"></td><td><a class="suggest-element" data="'.$filas['identificacion_usuarios'].'" id="'.$filas['identificacion_usuarios'].'">'.$filas['nombres_usuarios'].' '.$filas['apellidos_usuarios'].'</a></td></tr></table></div>';    
        }
        return $html; 
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

    function set_guardar($iduser,$valor){
        $id=0;
        if (strlen($valor[8]) == 0 || self::es_image($valor[8])){ 
            if ($iduser==0){//add
                $sql= "insert INTO tb_usuarios (identificacion_usuarios, id_institucion, nombres_usuarios, apellidos_usuarios, direccion_usuarios, email_usuarios, telefono_usuarios, sexo_usuarios, tipo_usuarios, foto_usuarios, activo_usuarios, fechacreacion_usuarios,id_especialidad) VALUES ('".$valor[0]."', '".$valor[14]."','".$valor[1]."', '".$valor[2]."', '".$valor[3]."', '".$valor[4]."', '".$valor[5]."', '".$valor[6]."', '".$valor[7]."', '".$valor[8]."', '".$valor[9]."', '".date('Y-m-d')."','".$valor[16]."')";
            }else{ //upd
                $sql= "update tb_usuarios SET identificacion_usuarios = '".$valor[0]."', id_institucion = '".$valor[14]."', nombres_usuarios = '".$valor[1]."', apellidos_usuarios = '".$valor[2]."', direccion_usuarios = '".$valor[3]."', email_usuarios = '".$valor[4]."', telefono_usuarios = '".$valor[5]."', sexo_usuarios = '".$valor[6]."', tipo_usuarios = '".$valor[7]."', foto_usuarios = '".$valor[8]."', activo_usuarios = '".$valor[9]."', id_especialidad = '".$valor[16]."' WHERE id_usuarios = '".$iduser."' and id_usuarios = '".$iduser."'";
                $sql1= "update tb_permisos SET rol_permiso = '".$valor[10]."', date_permisos = '".date('Y-m-d')."', desc_permisos = 'Actualizado por ".$_SESSION['usernc']."' WHERE id_usuario = '".$iduser."'";
                
                if ($valor[7]==2 && $valor[12])
                    $sql2 = "update tb_usuario SET  usuario_clave = '".$valor[12]."' WHERE usuario_identificacion = '".$iduser."'";
                
                $consulta1= Conectar::accion($this->db,$sql1);
            }
            $consulta= Conectar::accion($this->db,$sql);
            if ($valor[7]==2 && $iduser==0){ //si ADD
                $id = self::get_iduser($valor[0],$valor[14],$this->db);
                if ($id >0){
                    $sql1="insert INTO tb_permisos (id_usuario, rol_permiso, date_permisos, desc_permisos) VALUES ('".$id."', '".$valor[10]."', '".date('Y-m-d')."', 'Generado por ".$_SESSION['usernc']."')";
                    $sql2="insert INTO tb_usuario (id_empresa, usuario_identificacion, usuario_usuario, usuario_clave) VALUES ('".$valor[14]."', '".$id."', '".$valor[15]."', '".$valor[12]."')";
                    $consulta1= Conectar::accion($this->db,$sql1);
                }
            } 
            if ($valor[7]==2 && $valor[12])  $consulta2= Conectar::accion($this->db,$sql2);
        }
        if ($valor[7]==2)
            if (!$consulta || !$consulta1) $error= 2; else $error=1;
            if ($valor[7]==2 && $valor[12]) if (!$consulta2) $error= 2; else $error=1; 
        else
            if (!$consulta || !$consulta1) $error= 2; else $error=1;

        return $error;
    }

    static function get_iduser($cedula,$empresa,$db){
        $id=0;
        $consulta= "select id_usuarios from tb_usuarios where identificacion_usuarios='".$cedula."' and id_institucion='".$empresa."'";
        $personas= Conectar::select($db,$consulta);
        foreach($personas as $valor){
            $id = $valor['id_usuarios'];
        }
        return $id; 
    }

    function get_especialidades($code){
        if (!$code)
            $consulta= "select * from tb_especialidad";
        else
            $consulta= "select id_especialidad,esp_nombre from tb_especialidad where esp_idinstitucion='".$code."'";

        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function set_eliminar($iduser,$tipous){
        $sql= "delete from tb_usuarios where identificacion_usuarios = '".$iduser."'";
        $sql1="delete from tb_permisos where id_usuario = '".$iduser."'";

        if ($tipous==2)
            $sql2="delete from tb_usuario where usuario_identificacion = '".$iduser."'";
       
        $consulta= Conectar::accion($this->db,$sql);
        $consulta1= Conectar::accion($this->db,$sql1);
        if ($tipous==2)  $consulta2= Conectar::accion($this->db,$sql2);

        if ($tipous==2)
            if (!$consulta || !$consulta1 || !$consulta2) $error= 2; else $error=1;
        else
            if (!$consulta || !$consulta1) $error= 2; else $error=1;

        return $error;
    }

    function set_cerrarsesion($code){
        $sql = "update tb_usuarios SET idsesion_usuario = '' WHERE identificacion_usuarios = '".$code."'";
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;

        return $error;
    }

    function generar_user($nombres,$apellidos,$cedula){
        $i=1;
        $correcto=false;
        $search = array("á", "é", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ");
        $replace= array("a", "e", "i", "o", "u", "n", "A", "E", "I", "O", "U", "N");
        $nombres = str_replace($search, $replace, $nombres);
        $apellidos = str_replace($search, $replace, $apellidos);

        while (!$correcto){
            if ($i == strlen(trim($nombres))) {
                $correcto=true;
                $final=2;
            }
            $primer = strtolower(substr(trim($nombres),0,$i));
            $pos = strpos($apellidos,' ',0);
            if ($pos==0)
                $segundo = strtolower(substr($apellidos,0,strlen($apellidos)));
            else
                $segundo = strtolower(substr($apellidos,0,$pos));

            $tercero = substr($cedula, -2); 
            $final = $primer.$segundo.$tercero;
    
            $sql= "select usuario_usuario from tb_usuario where usuario_usuario='".limpiar($this->db,$final)."'";
            $consulta= Conectar::select_contar($this->db,$sql);
            $j=$consulta->num_rows;
            if ($j==0){
                $correcto=true;
            }else{
                $i++;
            }
        }
        return $final;
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