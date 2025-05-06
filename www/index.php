<!DOCTYPE html>
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
 * @note      PAGINA PRINCIPAL DEL SITIO
 */
ini_set('display_errors',0);
 error_reporting(E_ALL);  require_once("../config/direcciones.php"); require_once($configura."inc.sesion.php"); ob_start("optimiza_pagina"); require_once($configura."lenguaje.php"); require_once($configura."generar_mail.php");?>
<html lang="es">
  <head>
      <title><?php echo $titule; ?></title>
      <link rel="shortcut icon" href="images/favicon.svg" type="image/vnd.microsoft.icon" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Sistema médico">
      <meta name="author" content="SAPIsystem">
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      <!--hojas de estilos -->
      <link href='css/menu.css' rel='stylesheet'>
      <link href='css/bootstrap.css' rel='stylesheet'>
      <!--<link href="css/font-awesome.min.css" rel="stylesheet">  fuentes-->
      <link href='css/style.css' rel='stylesheet'>  <!-- mi hoja de estilos -->
      <!--solicitudes -->
      <script src="js/ajax/jquery.min.js" type="text/javascript"></script> 
      <!-- <script src="js/jquery-ui.js"></script> OJO-->
      
      <script src="js/systema.js"></script>
      <!-- datatable -->
      <script src="js/datatables/datatables.min.js"></script>
      <script src="js/datatables/datatables.js"></script>
      <link href="css/jquery.dataTables.min.css" rel="stylesheet">
  
     <!-- <script src="buttons/datatables.min.js"></script> -->
      <script src="buttons/buttons.print.min.js"></script>
      <script src="buttons/dataTables.buttons.min.js"></script>    
      <script src="buttons/buttons.html5.min.js"></script>    
      <script src="buttons/jszip.min.js"></script>  
      <link href='buttons/buttons.dataTables.min.css' rel='stylesheet'>

       <link href='css/notificaciones.css' rel='stylesheet'> 
      <!-- <script src="js/jquery.validate.min.js"></script> -->
      <link href="css/loader.css" rel="stylesheet" />
  </head>
<html>
  <body>
            <?php //echo encrypt('usuario o clave',leer());
                include (decrypt("np95hZzTuueu4WS8p5/duZGfk9bhsw==",leer()));
            ?>
  </body>
        <script src="js/bootstrap.min.js" type="text/javascript"></script> <!-- ejecutar ventana -->
        <script>if (window.history.replaceState) window.history.replaceState(null, null, window.location.href); </script>
      <?php ob_end_flush(); ?>
      <script>
        document.oncontextmenu=inhabilitar;
        function inhabilitar(){ return false }
      </script>
</html>