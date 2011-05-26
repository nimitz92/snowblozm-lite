<?php 
	require_once('../init.php');
	
	class Remark {
		
		public static function create($model){
			$conn = $model['conn'];
			$uid = $model['uid']
			$comment = $conn->escape($model['comment']);
			$rating = $model['rating'];
			
			$result = $conn->getResult("insert into remarks (uid, comment, rating) values ($uid, '$comment', $rating);", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$rkid = $conn->getAutoId();
			
			$model['valid'] = true;
			$model['rkid'] = $rkid;
			return $model;
		}
		
		public static function get($model){
			$conn = $model['conn'];
			$rkid = $model['rkid'];
			
			$result = $conn->getResult("select uid, comment, rating from categories where rkid=$rkid;");
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['result'] = $result;
			
			return $model;
		}
		
		public static function delete($model){
			$conn = $model['conn'];
			$rkid = $model['rkid'];
			
			$result = $conn->getResult("delete from remarks where rkid=$rkid;");
			
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
