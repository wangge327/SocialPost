<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>store on db</title>
		
	</head>
	<body>
		<h1>hi your response is recorded.</h1>
		
		<?php
		/*echo "<pre>";
		print_r($_FILES);
		echo("</pre>");exit;*/
		/*if(!empty($_POST)){
			$data = $_POST['avatar'];
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			list(, $type)      = explode('/', $type);
			$directory = getcwd().'/uploads/';
			if (!is_dir($directory)) {
				mkdir($directory, 0755, true);
			}
			$filename = uniqid().".".$type
			$data = base64_decode($data);
			$file= file_put_contents($directory.$filename, $data);
		}*/
		//upload file

        use Simcify\Database;

	  if(isset($_FILES['avatar'])){
		  $errors= array();
		  $file_name = $_FILES['avatar']['name'];
		  $file_size = $_FILES['avatar']['size'];
		  $file_tmp = $_FILES['avatar']['tmp_name'];
		  $file_type = $_FILES['avatar']['type'];
		  $file_ext=strtolower(end(explode('.',$_FILES['avatar']['name'])));
		  
		  $extensions= array("jpeg","jpg","png");
		  
		  if(in_array($file_ext,$extensions)=== false){
			 $errors[]="extension not allowed, please choose a JPEG or PNG file.";
		  }
		  
		  if($file_size > 2097152) {
			 $errors[]='File size must be excately 2 MB';
		  }
		  $directory = getcwd().'/uploads/';
		  if (!is_dir($directory)) {
			mkdir($directory, 0755, true);
		  }
		  $filename = uniqid().".".$file_ext;
		  try{
              if(empty($errors)==true) {
                  move_uploaded_file($file_tmp,$directory.$filename);
                  echo "Success ".$filename;
              }else{
                  print_r($errors);
              }
          }catch (Exception $e){
		      print_r($e->getMessage());
          }
	   }
?>
	
	</body>
</html>