<?php
class nav_pub_citas_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }
    function get_foto($idempresa){
        $consulta= "select logo,name_institucion FROM tb_institucion where id_institucion='".$idempresa."'";
        $institu= Conectar::select($this->db,$consulta);
        foreach($institu as $logoc){
            $texto[0] = $logoc['logo'];
            $texto[1] = $logoc['name_institucion'];
        }
        if (!$texto[0] && strlen($texto[0]) <=5) $texto[0]= 'images/noimagen.jpg';
        return $texto; 
    }

    function get_citas($institu, $especia){
        $hora=date('G:i');
        $hora=date ('G:i',strtotime ('-1 hour' , strtotime ( $hora ) ));
        if (strlen($hora)==4) $hora='0'.$hora;

        $consulta= "select cita_nombrepaciente as nompac,cita_apellidopaciente as apepac,DATE_FORMAT(cita_fechadesde, '%H:%i') as hora,nombres_usuarios as nomespec,
        apellidos_usuarios as apellespec FROM tb_citas a, tb_usuarios b where b.id_usuarios = a.id_especialista and id_empresa=".$institu." and 
        id_especialista in (".$especia.") and DATE_FORMAT(cita_fechadesde, '%Y-%m-%d') = '".date('Y-m-d')."' 
        and DATE_FORMAT(cita_fechadesde, '%H:%i') >='".$hora."' and a.cita_finalizada=0 and cita_confirmada=1";
        $citas= Conectar::select($this->db,$consulta);
        return $citas; 
    }

    function get_publicidad($institu){
        $consulta= "select imagenes FROM tb_publicidad where id_empresa='".$institu."'";
        $fotos= Conectar::select($this->db,$consulta);

        return $fotos;
    }
}

?>