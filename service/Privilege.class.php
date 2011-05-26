<?php 
	require_once('../init.php');
	
	class Privilege {
		
		public static function check($model){
			$conn = $model['conn'];
			$uid = $model['uid'];
			$type = $model['type']);

			$query = "select uid from previleges where uid=$uid and type=$type;";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
				
			if(count($result) != 1){
				$model['valid'] = false;
				$model['msg'] = 'Invalid Privilege';
				return $model;
			}
			
			$model['valid'] = true;
			$model['uid'] = $result[0][0];
			return $model;
		}
		
		public static function grant($model){
			$conn = $model['conn'];
			$guid = $model['guid'];
			$type = $model['type']);

			$query = "insert into previleges (type, uid) values ($type, $guid);";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
		
		public static function revoke($model){
			$conn = $model['conn'];
			$ruid = $model['ruid'];
			$type = $model['type']);

			$query = "delete from previleges where type=$type and uid=$ruid;";
			$result = $conn->getResult($query);
			
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
