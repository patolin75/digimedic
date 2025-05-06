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
//Llamada al modelo
if (file_exists($modelo."home_model.php")) require_once($modelo."home_model.php"); else die(header('Location: ?'));
    global $sub;
    global $id;
    global $pri;
    global $result;

    if(isset($_GET['kode'])) $id =$_GET['kode']; 
    if(isset($_GET['open'])) $open =$_GET['open']; 

    $id = trim(str_replace(" ","+",$id));
    $id = decrypt($id,leer());
    $id = valida_parametros($id);
    $i=0;
    foreach ($id as $tempo){
        if ($i==0){
            $pos=strpos($tempo,"=");  $tab = substr($tempo,$pos+1,strlen($tempo));
        }
        if ($i==1) {
            $pos=strpos($tempo,"=");  $sub = substr($tempo,$pos+1,strlen($tempo));
        }
        if ($i==2) {
            $pos=strpos($tempo,"=");  $pri = substr($tempo,$pos+1,strlen($tempo));
        }
        $i++;
    }
    $id=$tab;
    
    $superior=new home_model();

    if ($_POST['txtuseru'] && $_POST['txtpassan'] && $_POST['txtpasswa']){
        $iduse= $_POST['txtuseru'];
        $passant= encrypt($_POST['txtpassan'],leer());
        $passact= encrypt($_POST['txtpasswa'],leer());
        $resulta = $superior->set_actualizapass($iduse,$passant,$passact);
    }
    //$usuario=$superior->get_usuario($_SESSION['codus']);
    $institucion=$superior->get_superior();
    $menus=$superior->get_menus();
    $permisos=$superior->get_permisos(); 

    if ($permisos){
        foreach($permisos as $permisito){
            if ($permisito['id_menus']==$sub){
                $boton[0] = $permisito['crear_menus'];
                $boton[1] = $permisito['modificar_menus'];
                $boton[2] = $permisito['eliminar_menus'];
                $_SESSION['boot'] = $boton;
            }
        }
    }

    $superior->get_vertodo();

    require_once($vista."superior_view.phtml");
    $menu=new superior_view();
    $menu->presentar($resulta,$open,$id,$sub,$pri,$institucion,$menus,$permisos,$configura.'lenguaje.php');
?>