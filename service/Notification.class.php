<?php 
	require_once('../init.php');
	require_once('../util/Mail.class.php');
	require_once('Group.class.php');
	
	class Notification {
		
		public static function create($model){
			$conn = $model['conn'];
			$nname = $conn->escape($model['nname']);
			$ndesc = $conn->escape($model['ndesc']);
			
			$query = "select nname from notifications where nname='$nname'";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
				
			if(count($result) != 0){
				$model['valid'] = false;
				$model['msg'] = 'Notification name already registered';
				return $model;
			}
			
			$model['valid'] = true;
			$model['gname'] = $nname;
			
			$model = Group::create($model);
			if(!$model['valid'])
				return $model;
			
			$nid = $model['nid'];		
			$result = $conn->getResult("insert into notifications (nid, nname, ndescription) values ($nid, '$nname', '$ndesc');", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
		
		public static function delete($model){
			$conn = $model['conn'];
			$nname = $conn->escape($model['nname']);
			
			$query = "select nid from notifications where nname='$nname'";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['gid'] = $result[0][0];
			$model['valid'] = true;
			
			$model = Group::delete($model);
			if(!$model['valid'])
				return $model;
				
			$nid = $model['gid'];
			$result = $conn->getResult("delete from notifications where nid=$nid);", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
		
		public static function subscribe($model){
			$conn = $model['conn'];
			$nname = $conn->escape($model['nname']);
			
			$query = "select nid from notifications where nname='$nname'";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['gid'] = $result[0][0];
			
			return Group::add($model);
		}
		
		public static function unsubscribe($model){
			$conn = $model['conn'];
			$nname = $conn->escape($model['nname']);
			
			$query = "select nid from notifications where nname='$nname'";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['gid'] = $result[0][0];
			return Group::remove($model);
		}
		
		public static function notify($model){
			$conn = $model['conn'];
			$nname = $conn->escape($model['nname']);
			
			$query = "select nid from notifications where nname='$nname'";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['gid'] = $result[0][0];
			
			$model = Group::leaves($model);
			if(!$model['valid'])
				return $model;
			
			$users = $model['leaves'];
			$subject = $model['subject'];
			$message = $model['message'];
			
			$to = "";
			$size = count($users);
			for($i=0; $i < $size; $i++){
				$to = $to.",".$users[$i][2];
			}
			
			$to = substr($to, 1);
			//echo $to;
			$model['sent'] = Mail::send($to, $subject, $message);
			//echo $model['sent'];
			if($model['sent'] === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Sending Mail';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
		
		public static function subscription($model){
			$model = Group::parents($model);
			if(!$model['valid'])
				return $model;
			
			$conn = $model['conn'];
			$parents = $model['parents'];
			
			$query = "";
			$size = count($parents);
			for($i=0; $i < $size, $i++){
				$query = $query." or nid = ".$parents[$i][0];
			}
			
			$query = "select * from notifications where ".substr($query, 4);
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['subscriptions'] = $result;
			return $model;
		}
		
	}
	
?>
