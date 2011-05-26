<?php 
	require_once('../init.php');
	
	class Group {
	
		public static function create($model){
			$conn = $model['conn'];
			$gname = $conn->escape($model['gname']);
			$level = $model['level'];
						
			$result = $conn->getResult("insert into groups (gname, level) values ('$gname', $level);", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$gid = $conn->getAutoId();
			
			$model['valid'] = true;
			$model['gid'] = $gid;
			return $model;
		}
		
		public static function delete($model){
			$conn = $model['conn'];
			$gid = $model['gid'];
			
			$query = "delete from groups where gid=$gid;";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$result = $conn->getResult("delete from members where gid=$gid;", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
		
		public static function add($model){
			$conn = $model['conn'];
			$gid = $model['gid'];
			$member = $model['member'];
				
			$result = $conn->getResult("insert into members (gid, member) values ($gid, $member);", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
		
		public static function remove($model){
			$conn = $model['conn'];
			$gid = $model['gid'];
			$member = $model['member'];
				
			$result = $conn->getResult("delete from members where gid=$gid and member=$member);", true);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
		
		public static function info($model){
			$conn = $model['conn'];
			$gid = $model['gid'];
			
			$query = "select gname, level from groups where gid=$gid ;";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['members'] = $result;
			
			return $model;
		}
		
		public static function members($model){
			$conn = $model['conn'];
			$gid = $model['gid'];
			
			$query = "select level from groups where gid=$gid;";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$level = $result[0][0];
			
			$query = "(select member from members where gid=$gid);";
			if($level == 1)
				$query = "select uid, username, email from users where uid in ".$query;
			else
				$query = "select gid, gname from groups where gid in ".$query;
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['members'] = $result;
			
			return $model;
		}
		
		public static function leaves($model){
			$conn = $model['conn'];
			$gid = $model['gid'];
			
			$query = "select level from groups where gid=$gid;";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$level = $result[0][0];
			
			$querystart = "select member from members where gid in ";
			$query = "(select member from members where gid=$gid)";
			while($level > 1){
				$query = "(".$querystart.$query.")";
				$level--;
			}
			$query = "select uid, username, email from users where uid in ".$query.";";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['leaves'] = $result;
			
			return $model;
		}
		
		public static function parents($model){
			$conn = $model['conn'];
			$member = $model['member'];
			
			$query = "select gid from members where member=$member;";
			$result = $conn->getResult($query);
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['parents'] = $result;
			
			return $model;
		}
	
	}
	
?>
