<?php 
	require_once('../init.php')
	require_once('../util/Time.class.php');
	require_once('../util/Random.class.php');
	
	class Session {
	
		public static function create($model){
			$conn = $model['conn'];
			$uid = $model['uid'];

			$sessionid = Random::getString(32); 
			$ts = Time::getTime();
			$ts_exp = $ts + 2592000; // 30 days
			
			$conn->getResult("delete from sessions where expiry < $ts;", true);
			
			$result = $conn->getResult("insert into sessions values('$sessionid', $uid, $ts, $ts_exp);", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['sessionid'] = $sessionid;

			return $model;
		}
		
		public static function get($model){
			$conn = $model['conn'];
			$sessionid = $conn->escape($model['sessionid']);
			
			$ts = Time::getTime();
			$conn->getResult("delete from sessions where expiry < $ts;", true);

			$result = $conn->getResult("select uid from sessions where sessionid='$sessionid';");
			
			if($result === false){
				$model['valid'] = false;
				$model['uid'] = null;
				return $model;
			}
				
			if(count($result) != 1){
				$model['valid'] = false;
				$model['uid'] = null;
				return $model;
			}
			
			$model['valid'] = true;
			$model['uid'] = $result[0][0];
			return $model;
		}
		
		public static function invalidate($model){
			$conn = $model['conn'];
			$sessionid = $conn->escape($model['sessionid']);

			$result = $conn->getResult("delete from sessions where sessionid='$sessionid';", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
	
	}

?>
