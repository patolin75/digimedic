<?php
class atenpormes_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }
    
}
?>