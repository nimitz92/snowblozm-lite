<?php 
require_once('Curl.class.php');

class Mail {
	private static $delegate, $value, $user, $pass, $from;
	
	public static function initialize($delegate, $value, $user="", $pass="", $from=""){
		self::$delegate = $delegate;
		self::$value = $value;
		self::$user = $user;
		self::$pass = $pass;
		self::$from = $from;
	}
	
	public static function send($to, $sub, $msg){
		if(self::$delegate)
			return self::delegateto($to, $sub, $msg);
		else {
			$headers = "From: ".self::$from;
			$headers .= "\r\nReply-To: ".self::$from;
			$headers .= "\r\nX-Mailer: PHP/".phpversion();
			return mail($to, $sub, $msg ,$headers, self::$value);
		}
	}
	
	private static function delegateto($to, $sub, $msg) 
	{
        $params  = array(
            'to' => $to,
            'sub' => $sub,
            'msg' => $msg,
			'from' => self::$from,
			'smtpuser' => self::$user,
			'smtppass' => self::$pass
        );
		//print_r($params);
		$curl = new Curl(self::$value, $params);
		return $curl->send();
	}

}

?>
