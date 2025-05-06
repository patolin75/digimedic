<?php
/**
 * PHPMailer Exception class.
 * PHP Version 8.0
 *
 * @see      https://www.iscosystem.com.ec
 *
 * @author    Pato Jimenez <pojimenez1@hotmail.com>
 * @test      Adriana Becerra <abbecerra@gmail.com>
 * @copyright Iscosystem Co. ltda.
 * @license   Su uso se restringue a lo descrito en la licencia en la compra del producto
 * @note      Libreria propiedad de ISCOSYSTEM, su modificación se limita solo a la empresa.
 */

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (file_exists("../Classes/mailer/src/Exception.php")) require ("../Classes/mailer/src/Exception.php"); else  require_once("../../Classes/mailer/src/Exception.php");
    if (file_exists("../Classes/mailer/src/PHPMailer.php")) require ("../Classes/mailer/src/PHPMailer.php"); else  require_once("../../Classes/mailer/src/PHPMailer.php");
    if (file_exists("../Classes/mailer/src/SMTP.php"))  require ("../Classes/mailer/src/SMTP.php"); else  require_once("../../Classes/mailer/src/SMTP.php");

    function sessions(){
        global $my;
        if (session_status() == PHP_SESSION_NONE) 
        {
            if ($my)
                session_set_cookie_params(0,$my['direc']);
                session_name('razy');
                session_start();
        }
    }

    function encrypt($string, $key) {
        $result = '';
        for($i=0; $i < strlen($string); $i++) {
           $char = substr($string, $i, 1);
           $keychar = substr($key, ($i % strlen($key))-1, 1);
           $char = chr(ord($char)+ord($keychar));
           $result.=$char;
        }
        return base64_encode($result);
     }
     
     function decrypt($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
           $char = substr($string, $i, 1);
           $keychar = substr($key, ($i % strlen($key))-1, 1);
           $char = chr(ord($char)-ord($keychar));
           $result.=$char;
        }
        return $result;
     }

     function salir($sinred=''){
         session_destroy();
          setcookie(
            'identified',
            '',
            time() - 3600,
            '/',
            $_SERVER['HTTP_HOST']
          );
         //$antess= $_SESSION['tipous'];
         $_SESSION['tiempo']=null;
         $_SESSION['institucion']=null;
         $_SESSION['foto_us']=null;
         $_SESSION['usernc']=null;
         $_SESSION['codus']=null;
         $_SESSION['vertodo']=null;
         $_SESSION['tipous']=null;
         $_SESSION['idsession']=null;
         session_unset();
         // Aquí redireccionas a la url especifica 
        if(strlen($sinred)==0) header("Location: ?");
     }

    function closeadfs($authorize_url){
        $authorization_redirect_url = $authorize_url;
        header("Location: ".$authorization_redirect_url);	
     }

    function leer(){
        if (file_exists("../asCdfrsKey.less"))
            $fp = fopen("../asCdfrsKey.less", "r");
        else
            $fp = fopen("../../asCdfrsKey.less", "r");

        while (!feof($fp)){
            $linea = fgets($fp);
        }
        fclose($fp);
        return $linea;
    }

    function limpiar($conexion, $post)
    {
        $codehtml = htmlentities($post);
        $codephp = mysqli_real_escape_string($conexion, $codehtml);
        return $codephp;
    }

    function cargar_archivo($ruta,$file,$filetmp,$namatmp=""){
        $files  =  array("index.php","main.php","index.html");  if(in_array($file,  $files)) exit(0); 
        if(file_exists($ruta.$file)) {
            unlink($ruta.htmlentities($file, ENT_QUOTES,  'utf-8'));
        }
        if (!$namatmp){
            $nama_file = $file;
            $nama_file = stripslashes($nama_file);
            $nama_file = str_replace("'","",$nama_file);
            $nama_file = str_replace(" ","-",$nama_file);
            $nama_file = $nama_file;
        }else $nama_file=$namatmp;
       
        move_uploaded_file($filetmp, $ruta.'/'.$nama_file);

        return 'f';
    }

    function elimina_imagen($archivo){
        $files  =  array(".js",".phtml",".php",".html");  if(in_array($archivo,  $files)) exit(0); 
        if(file_exists($archivo)) {
            unlink($archivo);	
            return 0;
        }else return 'f';
    }

    function optimiza_pagina($buffer) {
        $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s','/<!--(.|\s)*?-->/');
        $replace = array( '>', '<', '\\1', '');
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }

    function inserta_logs($db, $detalle){
        $consulta= $db->query("insert into tb_logs (log_fecha,log_detalle) values ('".date('Y-m-d H:i:s')."','".$detalle."')");
        if (!$consulta)
            return 2;
        else
            return 1;
    }
    
    
    function valida_parametros($parametros){
        $parametro = explode("&", $parametros);
        return $parametro;
    }

    function cargar_model($modelo,$file){
        if (file_exists($modelo.$file."_model.php")) 
            return require_once($modelo.$file."_model.php"); 
        else 
            die(header('Location: ?'));
    }

    function cargar_vista($vista,$file){
        if (file_exists($vista.$file."_view.phtml")) 
            return require_once($vista.$file."_view.phtml");
        else 
            die(header('Location: ?'));
    }

    function notificar($host,$usermail,$portmail,$passmail,$destino,$nombre,$asunto,$mensaje,$smtpsecure,$cc='', $nenvia='',$atta=''){
        $mail = new PHPMailer(true);                             
        try {
            $mail->SMTPDebug = 0;    
            $mail->isSMTP();                                    
            $mail->Host = $host;                  
            $mail->SMTPAuth = true;               
            $mail->Username = $usermail;      
            $mail->Password = $passmail;               
            $mail->SMTPSecure = $smtpsecure;                            
            $mail->Port = $portmail;
            $mail->addCustomHeader('Mime-Version','1.0');
            //$mail->addCustomHeader('Content-Type: text/html; charset="iso-8859-1"');
            //$mail->addCustomHeader('Content-Transfer-Encoding: quoted-printable');
            $mail->addCustomHeader('Content-Transfer-Encoding: base64');
            $mail->CharSet = 'UTF-8';       
            $mail->setFrom($usermail, $nenvia);          
            $mail->addAddress($destino, $nombre); // Agregar una dirección de destinatario
            if (strlen($cc) >0) $mail->addReplyTo($cc, $cc);
            if (strlen($atta)>0) $mail->addAttachment($atta, 'adjunto.jpg');    // Nombre opcional
            $mail->isHTML(true);                           
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;
            $mail->send();
            return 1;
        } catch (Exception $e) {
            return  $mail->ErrorInfo;
            //echo 'Error de correo: ' . $mail->ErrorInfo;
        }
    }
?>