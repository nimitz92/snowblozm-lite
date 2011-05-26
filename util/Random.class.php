<?php 

class Random {
	public static function getNumber($min=-1, $max=-1) {
		if( $min==-1 && $max==-1 ) return rand();
		else return rand($min, $max);
	}
	
	public static function getString($len=10, $charset = 'qwert12yuiop34asdf56ghjkl78zxcv90bnm') {
		$randStr = '';
		$charsetLen = strlen($charset)-1;

		for($i = 0 ; $i < $len; $i++)
			$randStr .= $charset[ rand(0,$charsetLen) ];

		return $randStr;
	}
} 

?>
