<?php
class notificaciones_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_climensajes($idistitu,$vertodo,$codus){
        $fecha_actual = date("Y-m-d");
        
        if ($vertodo==0 || strlen($vertodo) ==0) $vertodo = $codus;
        $sql="select clientes_identificacion,clientes_nombres,clientes_apellidos,cita_telefono,clientes_email,cita_fechadesde 
        from tb_clientes a, tb_citas b where a.id_empresa='".$idistitu."' and b.id_paciente=a.clientes_identificacion 
        and DATE(cita_fechadesde) = '".date("Y-m-d",strtotime($fecha_actual."+ 1 days"))."' and id_especialista in (".$vertodo.")";

        $consulta= Conectar::select($this->db,$sql);
        return $consulta; 
    }

}