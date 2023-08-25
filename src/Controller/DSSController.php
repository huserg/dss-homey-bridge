<?php
namespace src\Controller;
  //dSS JSON Doc: http://developer.digitalstrom.org/Architecture/dss-json.pdf
  
class DSSController {
  
  public function RaiseEvent($eventId){
	$eventName = 'highlevelevent';

	$ret = $this->SendCommand("/json/event/raise", $eventName, $eventId);

	return $ret; 
  }
  
  private function SendCommand($call, $eventName, $eventId) {
	
	$server = getenv('DSSERVER_IP');
	$app_token = getenv('DSS_APP_TOKEN');

	$ret = false;

	$url = "https://".$server.":8080/json/system/loginApplication";
	$query = http_build_query(array (
		'loginToken' => $app_token,
	));
	$sessionToken = file_get_contents($url . "?" . $query, 'false', stream_context_create(array(
        "ssl" => array(
            "verify_peer" => false,
			"verify_peer_name" => false,
            "allow_self_signed" => false,
        )
	)));
	
	$sessionToken = json_decode($sessionToken);
	$sessionToken = $sessionToken->result->token;

	$url = "https://".$server.":8080/".$call;
	$query = http_build_query(array (
		'name' => $eventName,
		'parameter' => 'id%3D'.$eventId,
		'token' => $sessionToken,
	));
	$ret = file_get_contents($url . "?" . urldecode($query), false, stream_context_create(array(
        "ssl" => array(
            "verify_peer" => false,
			"verify_peer_name" => false,
            "allow_self_signed" => false,
        )
	)));

	return $ret;
  }

}