<?php
class rep_pacientes_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_pacientes($idinstitu){
        $consulta= "select * FROM tb_clientes where id_empresa='".$idinstitu."'";
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }
}
?>