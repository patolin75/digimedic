<?php
class botones{

    public function __construct(){
        
    }

    function get_botonesgrid($permiso1,$permiso2,$link1,$link2){
        $boton1="";
        $boton2="";
        if ($permiso1==1)
            $boton1= '<button class="btn btn-info" onclick="'.$link1.'"><i class="fa fa-edit"></i> Editar</button>';
        if ($permiso2==1)
            $boton2= '<button class="btn btn-danger" onclick="'.$link2.'"><i class="fa fa-trash"></i> Eliminar</button>';
            

        return $boton1.' '.$boton2;
    }

    function get_botonnuevo($permiso1,$link1){
        $boton1="";
        if ($permiso1==1)
            $boton1= '<button class="btn btn-info btn-block" onclick="'.$link1.'"><i class="fa fa-home"></i> Nuevo</button>';
        return $boton1;
    }

    function get_boton($permiso,$titulo,$link,$tamano,$nombre=""){
        if ($permiso==1){
            if ($tamano==1)
                $boton1= '<button class="btn btn-info btn-block '.$nombre.'" onclick="'.$link.'"><i class="fa fa-home"></i> '.$titulo.'</button>';
            else
                $boton1= '<button class="btn btn-info '.$nombre.'" onclick="'.$link.'"><i class="fa fa-home"></i> '.$titulo.'</button>';
        }
       
        return $boton1;
    }

    function get_botoasignar($permiso1,$link1){
        $boton1="";
        if ($permiso1==1)
            $boton1= '<button class="btn btn-info btn-block" onclick="'.$link1.'"><i class="fa fa-home"></i> Asignar permisos</button>';
        return $boton1;
    }

    function get_botonnuevop($permiso1,$link1){
        $boton1="";
        if ($permiso1==1)
            $boton1= '<button class="btn btn-info" id="bnuevo" onclick="'.$link1.'"><i class="fa fa-book"></i> Nuevo</button>';
        return $boton1;
    }

    function get_guardar($permiso1,$link1){
        $boton1="";
        if ($permiso1==1)
            if (strlen($link1) >0)
                $boton1= '<button class="btn btn-info" id="bguardar" onclick="'.$link1.'"><i class="fa fa-home"></i> Guardar</button>';
            else   
                $boton1= '<button class="btn btn-info" id="bguardar" type="submit"><i class="fa fa-home"></i> Guardar</button>';

        return $boton1;
    }
    function get_eliminar($permiso1,$link1){
        $boton1="";
        if ($permiso1==1)
        if (strlen($link1) >0)
            $boton1= '<button id="eliminar" class="btn btn-danger" onclick="'.$link1.'"><i class="fa fa-trash"></i> Eliminar</button>';
        else   
            $boton1= '<button id="eliminar" class="btn btn-danger" type="submit"><i class="fa fa-trash"></i> Eliminar</button>';

        return $boton1;
    }

    function get_ventana($accion){
        $ventana='<div class="modal modal-static fade" id="processing" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="text-center">
                                    <br><br>
                                        <div class="animationload">';
                                                if ($accion==1)
                                                    $ventana=$ventana.'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>';
                                                elseif ($accion==2)
                                                    $ventana=$ventana.'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, por favor validar la información</p></div>';
        $ventana=$ventana.'
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        return $ventana;
    }

    function get_mensajes(){
       $mensajes= '<div class="modal modal-static fade" id="frmmsg" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                                <br><br>
                                <div class="animationload" id="msg">
                                <br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> ';

        return $mensajes;
    }

    function tiempos(){
        $tiempos='<script>$(function(){$("#processing").modal({ backdrop: "toggle"});});
        setTimeout(function() { $(function(){$("#processing").modal("hide");}); }, 4000);</script>';

        return $tiempos;
    }

}
?>