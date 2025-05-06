<?php

//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET,POST');
//header('Access-Control-Allow-Headers: Content-Type,  X-Requested-With, Access-Control-Allow-Headers, Accept');
//header("Access-Control-Allow-Credentials: true");

function getdecode($access_token){
	$text=base64_decode(str_replace(array('-', '_'), array('+', '/'), $access_token));
	$pos = strpos($text,'}',0);
	
	if ($pos ==0){
		return $access_token;
	}else{
		$second=substr($text,$pos+1,strlen($text));
		$pos1=strpos($second,"}",0);
		$second=substr($second,0,$pos1);
		$text = substr($text,0,$pos);
		return $second.'}';
	}
}


function getdecode64($idclient){
	//$input = $_POST;
    //$idclient = $input['client_id'];
	$idclient = getdecode($idclient);
	return (array) json_decode($idclient);
}
//if ($_SERVER['REQUEST_METHOD'] == 'POST')
//{	
	
	//exit();
//}



//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");