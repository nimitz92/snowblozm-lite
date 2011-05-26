<?php

class Curl {
			
		protected 
			$url,
			$params,
			$err,
			$info;
		
		public function __construct($url, $params){
			$this->url = $url;
			$this->params = $params;
		}
		
		public function send(){
//echo "Curl : ".$this->url." "; print_r($this->params);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);
			$result = curl_exec($ch);

			$this->info = curl_getinfo($ch);

			if ($result === false || $this->info['http_code'] != 200) {
				$this->err =curl_error($ch);
				curl_close($ch);
				echo "Curl Error : ".$this->err;
				return false;
			}
			curl_close($ch);
			return $result;
		}	
		
		public function getInfo(){ return $this->info; }
		public function getError(){ return $this->err; }
}


?>
