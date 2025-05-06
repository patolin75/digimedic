<?php
class validaid_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }
    
    function recupera_session($usuario, $idsession){
        $sql="select idsesion_usuario FROM tb_usuarios where id_usuarios='".$usuario."' and idsesion_usuario='".$idsession."'";
        $consulta= Conectar::select_contar($this->db,$sql);
        $id1=$consulta->num_rows;
        if ($id1==0){
            $id=0;
        }else{
            $id=$id1;
        }
        return $id; 
    }
}
?>