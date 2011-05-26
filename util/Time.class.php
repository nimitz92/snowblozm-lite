<?php 

class Time {
	static $timediff = 0;
	
	public static function getTime() {
		return time()+self::$timediff;
	}
	
	public static function getFormattedDate() {
		return date("c", self::getTime());
	}
	
	public static function setTimeDifference($diff) {
		if( is_numeric($diff) ) self::$timediff=$diff;
	}
}
?>
