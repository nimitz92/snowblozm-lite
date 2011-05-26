<?php 
	require_once('../init.php');
	require_once('../util/Time.class.php');
	
	class Log {
	
		public static function record($model){
			$conn = $model['conn'];
			$address = $conn->escape($model['address']);
			$message = $conn->escape($model['message']);
			
			$ts = Time::getTime();
			$result = $conn->getResult("insert into logs (message, address, time) values ('$message', '$address', $ts);", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
		
		public static function search($model){
			$conn = $model['conn'];
			$address = $conn->escape($model['address']);
			$message = $conn->escape($model['message']);
			$fromtime = $model['fromtime'];
			$totime = $model['totime'];
			
			$result = $conn->getResult("select message, address, time from logs where message like '%$message%' and address like '%$address%' and time>=$fromtime and time <=$totime;", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['result'] = $result;
			return $model;
		}
	
	}
	
?>
