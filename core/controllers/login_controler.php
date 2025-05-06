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
    if (file_exists($modelo."login_model.php")) require_once($modelo."login_model.php"); else die(header('Location: ?'));
    require_once($base."database.php");
    include ($configura."inc.config.php");
    global $id_user;
    global $ingreso;
    global $kode;
    global $result;
    global $error;
    global $loginUrl;

    $login=new login_model();

    if (isset($_GET['kode'])) $kode=$_GET['kode'];

    if(isset($_POST['txtuserc'])) $usuarioc=htmlentities($_POST['txtuserc']);
    if(isset($_POST['txtpasswordc'])) $clavec=htmlentities($_POST['txtpasswordc']);

    if ((isset($usuarioc) && isset($clavec)) || $id_user){
        if ($id_user){
            $usuarioc=$upn;
            //$tip_lo  //si es de google o face
            $llenar=$login->get_contarusuario($id_user);
            if ($llenar==-1) $ingreso=0; else $ingreso=1; 
        }else{
            $ingreso=$login->get_login($usuarioc,$clavec); 
            $llenar=$login->get_contarusuario($_SESSION['codus']); //no topar
        }

        if ($ingreso==1) {
            if ($llenar==0) {
                if (!$id_user) $valores[0]='110444'; else $valores[0]=$id_user;
                $valores[1]=$upn;
                $valores[2]=$apell;
                $valores[3]=0;
                $valores[4]=$foto;
                $_SESSION['foto_us'] = $foto;
                //$mail=$id_user;
                $telefono='000000';
                $direc= 'nn';
                $sexo= $genero;
                if (strlen($email)>0 && $sexo) {
                    $error = $login->set_llenarusuario($valores,$direc,$email,$telefono,$sexo);
                    $link = "new=".encrypt('new=1',leer());
                }
                else
                    $error=2;
                if ($error >1) $ingreso=2;
                //$usuarioc=$email;
            }
           $login->get_tipous();
        }
      
        if ($ingreso==1) {
            $idsesion= uniqid();
            
            setcookie(
                'identified',
               $idsesion,
                time() + (60 * 60 * 24),
                '/',
                $_SERVER['HTTP_HOST']
            );
            $_SESSION['usernc']=$usuarioc;
            $_SESSION['tiempo']=time();
            //$_SESSION['idsession']=uniqid();
            $idsesion=$login->set_sessionid($_SESSION['codus'],$idsesion);
            echo "<meta http-equiv='refresh' content='0; url=?".$link."'>";
        }
        else{
            $error=1;
        }
    }
    elseif (isset($_POST['txtcedulas']) && isset($_POST['txtnombre']) && isset($_POST['txtapellido']) && isset($_POST['txtmail'])){
        if ($_POST['txtdef']=='path'){
            require_once($clases."inc.validar.cedula.php");

            $valor[0]=$_POST['txtcedulas'];
            $valor[1]=$_POST['txtnombre'];
            $valor[2]=$_POST['txtapellido'];
            $valor[3]=$_POST['cmbsexo'];
            $valor[4]=$_POST['txtinstitucion'];
            $valor[5]=$_POST['txtdirec'];
            $valor[6]=$_POST['txttelef'];
            $valor[7]=$_POST['txtmail'];
    
            $valinter=new ValidarIdentificacion();
            $result=$valinter->validarCedula($valor[0]);
            
            if ($result==1){
                $userau = $login->generar_user($valor[1],$valor[2],$valor[0]);
                $valor[8]= $userau; 
                
                $result=$login->set_registro($valor);
                if ($result ==1){
                    $link='da='.$userau.'&uid='.$valor[0];
                    $link=encrypt($link,leer());
                   // $result=procesa_mail($my['direc'],$host,$usermail,$portmail,$passmail,$link,$valor[7],$valor[1],'',$smtpsecure,1,$userau);
                }
            }else $result=2;
        }
    }elseif(isset($_POST['txtmailre'])){
        $datosus = $login->obtener_datos($_POST['txtmailre']);

        if ($datosus){
            foreach($datosus as $valor){
                $nusu= $valor['nombres_usuarios'];
                $usua = $valor['usuario_usuario'];
            }
        }
       
      
        if (strlen($nusu)>0 && strlen($usua) >0){
            $clave= $login->generar_clave($usua);
            $cencry=encrypt($clave,leer());
            $result = $login->set_actualclave($usua,$cencry);
            //procesa_mail($my['direc'],$host,$usermail,$portmail,$passmail,$link,$_POST['txtmailre'],$nusu,$usua,$smtpsecure,8,$clave);
        }else $result=5;
    }
    //Llamada a la vista
    require_once($vista."login_view.phtml");
    $formalog = new login_view();
    $formalog->presentar($ingreso,$kode,$configura.'lenguaje.php',$client,$loginUrl,$result,$error);
?>
