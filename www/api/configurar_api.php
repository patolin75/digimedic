<?php
/**
 * class.
 * PHP Version 8.0.
 *
 * @see       https://www.iscosystem.com.ec
 *
 * @author    Pato Jimenez <pojimenez1@hotmail.com>
 * @test      Adriana Becerra <abbecerra@gmail.com>
 * @copyright Iscosystem Co. ltda.
 * @license   Su uso se restringue a lo descrito en la licencia en la compra del producto
 * @note      clase que permite cargar el menu superior del aplicativo
 */
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include ("api_config.php");
    require_once("../../core/models/conf_sis_model.php");
    $per=new configurar_model();

    if (isset($_POST['code'])) $code = $_POST['code']; else $code="";
    if (isset($_POST['accion'])) $accion = $_POST['accion']; else $accion="";
    if (isset($_POST['accionc'])) $accionc = $_POST['accionc']; else $accionc="";

    if (isset($_POST['nombre'])) $nombre = $_POST['nombre']; else $nombre="";
    if (isset($_POST['valor'])) $valor = $_POST['valor']; else $valor="";
    if (isset($_POST['observa'])) $observa = $_POST['observa']; else $observa="";

    if (isset($_POST['listado'])) $listado = $_POST['listado']; else $listado="";
    if (isset($_POST['permisi'])) $permisi = $_POST['permisi']; else $permisi="";

    if (isset($_POST['permisis'])) $permisis = $_POST['permisis']; else $permisis="";
    if (isset($_POST['insti'])) $insti = $_POST['insti']; else $insti="";
    
    if (isset($_POST['hdesde'])) $hdesde = $_POST['hdesde']; else $hdesde="";
    if (isset($_POST['hhasta'])) $hhasta = $_POST['hhasta']; else $hhasta="";

    
    if ($accion){
        if ($accion =='add') $result = $per->set_agregarespecista($code);
        if ($accion =='del') $result = $per->set_eliminaespecialista($code);
    }

    if ($accionc){
        $result =$accionc;
        if ($accionc =='add') $result = $per->set_agregacosto($code,$nombre,$valor,$observa);
        if ($accionc =='del') $result = $per->set_eliminacosto($code);
    }

    $menus = $per->get_cargarcostos($code);
    $result = json_encode($menus);
    if ($accionc){
        if ($accionc =='addh') $result = $per->set_agregahorario($code,$hdesde,$hhasta,$observa);
        if ($accionc =='delh') $result = $per->set_eliminahorario($code);
        if ($accionc =='chorario') {
            $result = $per->get_recuperahorarios($code);
            $result = json_encode($result);
        }
    }

    if ($permisi){
        if (strlen($listado) ==0) $listado=0; else $listado = substr($listado, 0, -1);
        $result = $per->set_actualiper($code,$listado);
    }


    if ($permisis==1){
        $valores = $per->get_valoresusuario($code);
        $roles = $per->get_mostraroles();
        //$listaiz = $per->get_listaizquierda($valores,$insti,$roles);
        $result = $per->get_listaderecha($valores,$insti,$roles);     
        $result = json_encode($result);
    }

    if ($permisis==2){
        $valores = $per->get_valoresusuario($code);
        $roles = $per->get_mostraroles();
        $result = $per->get_listaizquierda($valores,$insti,$roles);
        $result = json_encode($result);
    }
   
    if ($permisis==3){
        $result = $per->get_listausuarios($code);
        $result = json_encode($result);
    }

    if ($permisis==4){
        $roles = $per->get_mostraroles();
        $result = $per->get_medicos($code,$roles);
        $result = json_encode($result);
    }
    if ($permisis==5){
        $result = $per->get_tb_especialidad($code);
        $result = json_encode($result);
    }

    if ($permisis==6){
        $result = $per->set_guardarcampos($hdesde, $hhasta, $code);
    }

    if ($permisis==7){
        $result = $per->get_recuperaobjetos($hdesde, $hhasta);
        foreach($result as $valores)
            $result = $valores['campo1'];
    }
    
    echo $result;
}
?>