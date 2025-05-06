<?php
class login_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_login($usuario,$clave){
        $sql= "select b.id_usuarios, a.usuario_identificacion, a.id_empresa from tb_usuario a, tb_usuarios b where a.usuario_usuario='".limpiar($this->db,$usuario)."' and a.usuario_clave='".limpiar($this->db,encrypt($clave,leer()))."' and b.id_usuarios = a.usuario_identificacion and b.activo_usuarios=1";
        $consulta= Conectar::select($this->db,$sql);

        if ($consulta){
            foreach ($consulta as $filas){
                $code=$filas['id_empresa'];
                $identifica=$filas['id_usuarios'];
            }

            $sql= "select estado from tb_institucion where id_institucion='".$code."'";
            $consulta1= Conectar::select($this->db,$sql);
            if ($consulta1){
                foreach ($consulta1 as $valores){
                    $estint = $valores['estado'];
                }
            }
            if ($estint == 1){
                $_SESSION['codus']=$identifica;
                $validar=1;
            }
        }
        return $validar;
    }

    function set_sessionid($usuario,$idsession){
        $sql="update tb_usuarios SET idsesion_usuario = '".$idsession."' WHERE id_usuarios='".$usuario."' or identificacion_usuarios='".$usuario."'";
        $consulta= Conectar::accion($this->db,$sql);

        return $idsession;
    }

    function get_tipous(){
         //usuarios externo
        $sql= "select tipo_usuarios FROM  tb_usuarios b where b.id_usuarios='".$_SESSION['codus']."' or b.identificacion_usuarios='".$_SESSION['codus']."'";
        $consulta= Conectar::select($this->db,$sql);

        foreach ($consulta as $filas)
            $tipous=$filas['tipo_usuarios'];
        
        $_SESSION['tipous']=$tipous;

        return $tipous;
    }

    function get_usuario($usuario){
        $sql= "select usuario_usuario from tb_usuario where usuario_usuario='".limpiar($this->db,$usuario)."'";
        $consulta= Conectar::select_contar($this->db,$sql);
        $i=$consulta->num_rows;
        if ($i >0) 
            return 2;
        else 
            return 1;
    }

    function get_cedula($ced){
        $sql="select identificacion_usuarios from tb_usuarios where identificacion_usuarios='".limpiar($this->db,$ced)."'";
        $consulta= Conectar::select_contar($this->db,$sql);
        $total=$consulta->num_rows;
        if ($total >0)
            return 2;
        else 
            return 1;
    }


    function generar_clave($usua,$length = 6){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function set_actualclave($user,$clave){
        $sql="update tb_usuario set usuario_clave='".limpiar($this->db,$clave)."' where usuario_usuario='".limpiar($this->db,$user)."'";

        if (Conectar::accion($this->db,$sql)){
            $error=2;
        }else $error=4;

        return $error;
    }

    function obtener_datos($mail){
        $sql= "select a.nombres_usuarios,b.usuario_usuario FROM tb_usuarios a, tb_usuario b where a.email_usuarios='".limpiar($this->db,$mail)."' and b.usuario_identificacion = a.id_usuarios and a.tipo_usuarios=2";

        $consulta= Conectar::select($this->db,$sql);
        return $consulta;
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

    function set_registro($valores){
        $consulta= Conectar::select_contar($this->db,"select usuario_usuario from tb_usuario where usuario_identificacion='".limpiar($this->db,$valores[0])."'");
        $j=$consulta->num_rows;
        $consulta= Conectar::select_contar($this->db,"select identificacion_usuarios from tb_usuarios where id_usuarios='".limpiar($this->db,$valores[0])."'");
        $i=$consulta->num_rows;
        if ($i==0 && $j==0){
            $consulta= Conectar::accion($this->db,"insert into tb_usuarios (identificacion_usuarios,id_institucion, nombres_usuarios, apellidos_usuarios, direccion_usuarios, email_usuarios, telefono_usuarios,sexo_usuarios,tipo_usuarios,foto_usuarios,activo_usuarios) values ('".limpiar($this->db,$valores[0])."', '".limpiar($this->db,$valores[1])."', '".limpiar($this->db,$valores[2])."', '".limpiar($this->db,$valores[3])."', '".limpiar($this->db,$valores[4])."', '".limpiar($this->db,$valores[5])."','".limpiar($this->db,$valores[6])."','".limpiar($this->db,$valores[7])."','".limpiar($this->db,$valores[8])."','".limpiar($this->db,$valores[9])."','".limpiar($this->db,$valores[10])."','".limpiar($this->db,$valores[11])."')");
            if ($consulta) $consulta1= Conectar::accion($this->db,"insert into tb_usuario (id_empresa, usuario_identificacion, usuario_usuario) values ('0', '".limpiar($this->db,$valores[0])."', '".limpiar($this->db,$valores[12])."')"); else  $error=2;
            if ($consulta1) $error=1; else $error=2;
        }
        else $error=2;
        return $error;
    }

    function get_contarusuario($identifica){ 
        $sql="select b.id_usuarios, tipo_usuarios,id_institucion,foto_usuarios,activo_usuarios FROM  tb_usuarios b where b.id_usuarios='".$identifica."' or b.identificacion_usuarios='".$identifica."' or b.email_usuarios='".$identifica."'";
        $resultado= Conectar::select($this->db,$sql);
        foreach($resultado as $filas){
            if ($filas['activo_usuarios']==0) 
                $total=-1;
            else{
                $code=$filas['id_institucion'];
                $_SESSION['codus']=$filas['id_usuarios'];  //$id_user;
                $_SESSION['institucion']= $code;
                $_SESSION['foto_us'] = $filas['foto_usuarios'];
                $total=1;
            }
        }
       
        $sql= "select estado from tb_institucion where id_institucion='".$code."'";
        $consulta1= Conectar::select($this->db,$sql);
        if ($consulta1){
            foreach ($consulta1 as $valores){
                if ($valores['estado']==0)  $total=-1; else  $total=1;
            }
        }
        return $total;
    }

    function set_llenarusuario($valores,$direc,$correo,$telefono,$sexo){
        $sql="insert into tb_usuarios (identificacion_usuarios, id_institucion, nombres_usuarios, apellidos_usuarios, direccion_usuarios, email_usuarios, telefono_usuarios, sexo_usuarios, tipo_usuarios,foto_usuarios,activo_usuarios,fechacreacion_usuarios) values ('".$valores[0]."','0','".$valores[1]."', '".$valores[2]."', '".$direc."', '".$correo."', '".$telefono."','".$sexo."','1','".$valores[4]."','1','".date('Y-m-d H:i:s')."')";
        $consulta= Conectar::accion($this->db,$sql);

        $sql="select b.id_usuarios, tipo_usuarios,id_institucion,foto_usuarios,activo_usuarios FROM  tb_usuarios b where b.identificacion_usuarios='".$valores[0]."'";
        $resultado= Conectar::select($this->db,$sql);
        foreach($resultado as $filas){
            if ($filas['activo_usuarios']==0) 
                $total=-1;
            else{
                $code=$filas['id_usuarios'];
                $_SESSION['codus']=$code;  //$id_user;
                $_SESSION['institucion']= $filas['id_institucion'];
                $_SESSION['foto_us'] = $filas['foto_usuarios'];
                $total=1;
            }
        }

        $sql1="insert INTO tb_permisos (id_usuario, rol_permiso, date_permisos, desc_permisos) VALUES ('".$code."', '0', '".date('Y-m-d')."', 'Generado por creacion de us')";
        $consulta1= Conectar::accion($this->db,$sql1);


        if (!$consulta)
            return 2;
        else
            return 0;
    }
}

?>