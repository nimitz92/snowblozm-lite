<?php 
	require_once('../init.php');
	
	class Storage {
	
		public static function upload($model){
			$filekey = $model['filekey'];
			$maxsize = $model['maxsize'];
			$savepath = $model['savepath'];
			
			$file = $_FILES[$filekey];
			$model['filename'] = basename($file['name']);
			
			$filename = ((isset($model['rename']) ? $model['rename'] : $model['filename']));
			
			if( $file['size'] > $maxsize ){
				$model['valid'] = false;
				$model['msg'] = "File is larger than maximum allowed.";
				return $model;
			}

			switch($file['error']){
				case UPLOAD_ERR_OK: 
					break;
				case UPLOAD_ERR_INI_SIZE: 
					$model['valid'] = false;
					$model['msg'] = "File is larger than maximum possible to be uploaded.";
					return $model;
				default: 
					$model['valid'] = false;
					$model['msg'] = "Internal error";
					return $model;
			}
			
			if( !move_uploaded_file($file['tmp_name'], ECROOT.$savepath.$filename) ){
				$model['valid'] = false;
				$model['msg'] = "Internal unexpected error";
				return $model;
			}
			
			$model['valid'] = true;
			return $model;
		}
	
	}
	
?>
