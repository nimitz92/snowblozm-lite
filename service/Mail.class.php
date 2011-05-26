<?php 
	require_once('../init.php');
	
	class Mail {
		
		public static function smtp($model){
			$to = $model['to'];
			$from = $model['from'];
			$sub = $model['sub'];
			$msg = $model['msg'];
			$smtpuser = $model['smtpuser'];
			$smtppass = $model['smtppass'];
			$talk = array();
			
			if ($SMTPIN = fsockopen (MAILHOST, MAILPORT)) {
				$talk["http_host"] = "Http_Host ".$_SERVER['HTTP_HOST'];
				$talk["start"] = fgets ( $SMTPIN, 1024); 

				fputs ($SMTPIN, "HELO ".$_SERVER['HTTP_HOST']."\r\n");  
				$talk["helo"] = fgets ( $SMTPIN, 1024); 
						   
				fputs($SMTPIN, "AUTH LOGIN\r\n");
				$talk["auth login"]=fgets($SMTPIN,1024);
					
				fputs($SMTPIN, $smtpuser."\r\n");
				$talk["user"]=fgets($SMTPIN,1024);
						
				fputs($SMTPIN, $smtppass."\r\n");
				$talk["pass"]=fgets($SMTPIN,256);
								
				fputs ($SMTPIN, "MAIL FROM: <$from>\r\n");  
				$talk["from"] = fgets ( $SMTPIN, 1024 );  
					
				$rcpts = split(',', $to);
				foreach($rcpts as $rcpt){
					fputs ($SMTPIN, "RCPT TO: <$rcpt>\r\n");  
					$talk["to ".$rcpt] = fgets ($SMTPIN, 1024); 
				}
					   
				fputs($SMTPIN, "DATA\r\n");
				$talk["data"]=fgets( $SMTPIN,1024 );
						
				fputs($SMTPIN, "To: $to\r\nFrom: $from\r\nSubject:$sub\r\n\r\n\r\n$msg\r\n.\r\n");
				$talk["send"]=fgets($SMTPIN, 1024);
					   
				fputs ($SMTPIN, "QUIT\r\n");  
				$talk["quit"]=fgets($SMTPIN, 1024);
				fclose($SMTPIN); 
			}
			$model['result'] = $talk;
			
			return $model;
		}
		
	}
	
?>
