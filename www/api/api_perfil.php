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


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include ("api_config.php");
    require_once("../../core/models/adm_perf_model.php");
    $per=new perfil_model();

    if (isset($_GET['code'])) $code = $_GET['code']; else $code="";
    if (isset($_GET['perm'])) $perm = $_GET['perm']; else $perm="";

    if (isset($_GET['rol'])) $rol = $_GET['rol']; else $rol="";

    if (isset($_GET['menu'])) $menu = $_GET['menu']; else $menu=""; //menu
    if (isset($_GET['sub'])) $sub = $_GET['sub']; else $sub=""; //sub
    if (isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo=""; // C M E
    if (isset($_GET['accion'])) $accion = $_GET['accion']; else $accion=""; //add del adds

    if (strlen($code)>0){
        $menus = $per->get_menusprincipales();
        $result = json_encode($menus);
    } 
    if (strlen($perm)>0 && strlen($rol)>0){
        $menus = $per->get_submenus($rol,$perm);
        $result = json_encode($menus);
    }
    if (strlen($menu)>0){
        $result = $per->set_agregarpermisos($rol,$menu,$sub,$tipo,$accion);
    }

    echo $result;
}
?>