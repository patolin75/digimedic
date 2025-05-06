<?php
class footer_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_totalnotifica($codigo){
        $sql="select id_citas from tb_citas where id_especialista in (".$codigo.") and date_format(cita_fechadesde, '%Y-%m-%d') = '".date("Y-m-d")."' and cita_confirmada =0 and cita_notificada =0";
        $consulta=$this->db->query($sql);
        if ($consulta) 
            $total=$consulta->num_rows;

        $sql="select id_citas from tb_citas where id_especialista in (".$codigo.") and cita_confirmada =0";
        $consulta1=$this->db->query($sql);
        $total1=$consulta1->num_rows;
        $json[0]=$total;
        $json[1]=$total1;
        //$json = array($total,$total1);
        return $json; 
    }

    function set_notificado($code){
        $sql= "update tb_citas SET cita_notificada = '1' WHERE id_especialista in (".$code.") and date_format(cita_fechadesde, '%Y-%m-%d') = '".date("Y-m-d")."' and cita_confirmada =0 and cita_notificada =0";
        
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error= 2; else $error=1;
        return $error;
    }

}
?>