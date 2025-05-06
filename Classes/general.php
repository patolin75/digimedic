<?php 
/**
 * class.
 * PHP Version 8.0
 *
 * @see      https://www.iscosystem.com.ec
 *
 * @author    Pato Jimenez <pojimenez1@hotmail.com>
 * @test      Adriana Becerra <abbecerra@gmail.com>
 * @copyright Iscosystem Co. ltda.
 * @license   Su uso se restringue a lo descrito en la licencia en la compra del producto
 * @note      Permite administrar la carga de las páginas del sitio
 */
        $salir="";
        $fboat="";
        $code="";
        $drview="";
        sessions();
        if(isset($_GET['exit'])) $salir=$_GET['exit'];
        if(isset($_GET["code"])) $code=$_GET["code"];

        if (!$_SESSION['tipous'] || $salir){
            require_once($clases.'google/vendor/autoload.php'); 
            require_once($clases.'Facebook/autoload.php');

            $fboat=$_GET['fboat'];
            $client = new Google_Client(); 
            $client->setClientId($my['idcliente']);
            $client->setClientSecret($my['secret']);
            $client->setRedirectUri($my['redirec']);
            $client->addScope("email");
            $client->addScope("profile");
            $fb = new Facebook\Facebook([
                'app_id' => '798432227774224',
                'app_secret' => 'b401ffd2972ceac72e7d86e55638439d',
                'default_graph_version' => 'v2.4'
                ]);
                $helper = $fb->getRedirectLoginHelper();
                $permissions = ['email'];
                $loginUrl = $helper->getLoginUrl($my['uri'], $permissions);
        }

        if ($code && !$fboat){ //GOOGLE
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);
            
            if ($token['access_token']){
                $google_oauth = new Google_Service_Oauth2($client);
                $google_account_info = $google_oauth->userinfo->get();
                $email =  $google_account_info->email;
                $name =  $google_account_info->name;
                $uid = $google_account_info->id;
                $genero='N/D';
                $id_user = $uid; 
                $foto=$google_account_info->picture;
                $pos= strpos($name,' ',0);
                if ($pos==0){
                    $upn=$name;
                    $apell=$name;
                }else{
                    $upn=substr($name,0,$pos);
                    $apell=substr($name,$pos+1,strlen($name));
                }                
            } else  $result=2;

            require_once($controlador."login_controler.php");
        }elseif ($fboat){ //FACEBOOK
            try {
                $accessToken = $helper->getAccessToken();
              } catch(Facebook\Exceptions\FacebookResponseException $e) {
                $result=2;
              } catch(Facebook\Exceptions\FacebookSDKException $e) {
                $result=2;
              }

              if ($result<>2){
                    $oAuth2Client = $fb->getOAuth2Client();
                    $tokenMetadata =  $oAuth2Client->debugToken($accessToken);
                    $tokenMetadata->validateExpiration();

                    if ($accessToken->isLongLived()) {
                            try {
                                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                                $result=2;
                            }

                            $loggedUser = $fb->get('/me?fields=id,name,email,first_name,last_name,gender,picture', $accessToken);
                            $gu = $loggedUser->getGraphUser();
                            $name = $gu->getName();
                            $foto = json_decode($gu->getPicture());
                            $foto = $foto->url;
                            $id = $gu->getId();
                            $genero = 'N/D';
                            $email = $gu->getEmail();
                            $id_user = $id; 
                            $pos= strpos($name,' ',0);
                            $upn=substr($name,0,$pos);
                            $apell=substr($name,$pos+1,strlen($name));
                    }
                }
                require_once($controlador."login_controler.php");
        }else{ //APP
                require_once ("$controlador/validaid_controler.php");
                
                $drview = $_GET['drview'];
                if (isset($drview) && isset($_SESSION['usernc']) && $idvalidado >0){
                    $open = $_GET['open'];
                    require_once("$configura/inc.motor.php"); 
                }
                elseif ($salir=="@3er56") 
                    salir();
                    //if ($_SESSION['tipous'] ==1 ) $idvalidado=1;
                elseif (isset($_SESSION['usernc']) && $idvalidado >0 ){
                        require_once($controlador."superior_controler.php"); //siempre fijos
                        require_once($configura."inc.motor.php");      
                        require_once($controlador."footer_controler.php");
                }
                else {
                        require_once($clases.'google/vendor/autoload.php'); 
                        $client = new Google_Client(); 
                        $client->setClientId($my['idcliente']);
                        $client->setClientSecret($my['secret']);
                        $client->setRedirectUri($my['redirec']);
                        $client->addScope("email");
                        $client->addScope("profile");
                        $_SESSION['tipous']=null;
                        require_once($controlador."login_controler.php");
                }
                //}
        }
?>