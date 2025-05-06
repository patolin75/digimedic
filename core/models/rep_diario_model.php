<?php
class repdiario_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_reportediario($code,$fecha){
        $sql="select cita_nombrepaciente as nombre, cita_apellidopaciente as apellido, b.tipocitas_nombre as cita, b.tipocitas_valor  as valor
        from tb_citas a, tb_tipocitas b where DATE_FORMAT(a.cita_fechadesde, '%Y-%m-%d') = '".$fecha."' 
        and a.id_especialista = '".$code."' and b.id_especialista= a.id_especialista and b.id_tipocitas = a.cita_tipocita";
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

    function get_datosempresa($id){
        $sql="select * from tb_institucion where id_institucion=".$id;
        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }
}

?>