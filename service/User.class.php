<?php 
	require_once('../init.php');
	require_once('../util/Random.class.php');
	require_once('../util/Mail.class.php');
	require_once('Session.class.php');

	class User {
	
		public static function authenticate($model){
			$conn = $model['conn'];
			$user = $conn->escape($model['username']);
			$pass = $conn->escape($model['password']);

			$result = $conn->getResult("select uid from users where username='$user' and password=MD5('$user$pass');");
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
				
			if(count($result) != 1){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$model['valid'] = true;
			$model['uid'] = $result[0][0];
			return $model;
		}
		
		public static function login($model){
			$model = self::authenticate($model);
			
			if(!$model['valid']){
				return $model;
			}
			
			return Session::create($model);
		}
		
		public static function register($model){
			$conn = $model['conn'];
			$username = $conn->escape($model['username']);
			$email = $conn->escape($model['email']);
			$subject = $model['subject'];
			$message = $model['message'];
			
			$result = $conn->getResult("select uid from users where (username='$username' or email='$email');");
			
			if($result === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
				
			if(count($result) != 0){
				$model['valid'] = false;
				$model['msg'] = 'Username / Email already registered';
				return $model;
			}
			
			$password = Random::getString(16);
			$result = $conn->getResult("insert into users (username, password, email) values ('$username', MD5('$username$password'), '$email');", true);
			
			if($result === false){
				$model['valid'] = false;
				return $model;
			}
			
			$uid = $conn->getAutoId();
			
			$message = str_replace('{username}', $username, $message);
			$message = str_replace('{password}', $password, $message);
			
			$sent = Mail::send($email, $subject, $message);
			
			if($sent === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Sending Mail';
				return $model;
			}
			
			$model['valid'] = true;
			$model['uid'] = $uid;
			return $model;
		}
		
		public static function reset($model){
			$conn = $model['conn'];
			$username = $conn->escape($model['username']);
			$email = $conn->escape($model['email']);
			$subject = $model['subject'];
			$message = $model['message'];
			
			$password = Random::getString(16);
			
			$result = $conn->getResult("update users set password=MD5('$username$password') where username='$username' and email='$email';", true);
			
			if($result === false || $result != 1){
				$model['valid'] = false;
				$model['msg'] = 'Error in Database';
				return $model;
			}
			
			$message = str_replace('{username}', $username, $message);
			$message = str_replace('{password}', $password, $message);
			
			$sent = Mail::send($email, $subject, $message);
			
			if($sent === false){
				$model['valid'] = false;
				$model['msg'] = 'Error in Sending Mail';
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
		
		public static function delete($model){
			$conn = $model['conn'];
			$uid = $model['uid'];
			
			$result = $conn->getResult("delete from users where uid=$uid;");
			
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
