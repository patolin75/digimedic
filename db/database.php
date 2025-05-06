<?php
/**
 * Class.
 * PHP Version 8.0
 *
 * @see      https://www.iscosystem.com.ec
 *
 * @author    Pato Jimenez <pojimenez1@hotmail.com>
 * @test      Adriana Becerra <abbecerra@gmail.com>
 * @copyright Iscosystem Co. ltda.
 * @license   Su uso se restringue a lo descrito en la licencia en la compra del producto
 * @note      clase que permite manejar la conexión con la base de datos.
 */
class Conectar{
    public function __construct(){

    }
    
    public static function conexion(){
        error_reporting(0);
        if (!file_exists("config/inc.config.php")) {
            if (!file_exists("../config/inc.config.php"))
                require("../../config/inc.config.php"); 
            else    
                require("../config/inc.config.php"); 
        }else
            require("config/inc.config.php");

        $coneccion	= mysqli_connect($my['host'], decrypt($my['user'],leer()), decrypt($my['pass'],leer()));
        if (!$coneccion) {
            echo '<br><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">x</button><strong>Alerta! </strong>Fallo de conexion a base de datos.</div>';
            exit;
        }
        mysqli_select_db($coneccion,$my['dbs']);
        return $coneccion;
    }

    public static function select($coneccion,$sql){
        $consulta= $coneccion->query($sql);
        //if ($consulta)
            while($filas= mysqli_fetch_array($consulta)){
                $personas[]=$filas;
            }
        return $personas; 
    }

    public static function accion($coneccion,$sql){
        $consulta= $coneccion->query($sql);
        return $consulta; 
    }

    public static function select_contar($coneccion,$sql){
        $consulta= $coneccion->query($sql);
        return $consulta; 
    }
}

?>
