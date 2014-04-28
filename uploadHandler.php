<?php
  	session_start();
  
	require('uploader/Uploader.php');

	$upload_dir = 'data/';
	
	$uploaded_files = array();
	if (isset($_SESSION['uploaded_files'])) {
		$uploaded_files = $_SESSION['uploaded_files'];
	}
	
	// Delete all existing data files
	$existing_files = scandir($upload_dir);
	foreach($existing_files as $file){
		if(is_file($upload_dir . '/' . $file) && !in_array($file, $uploaded_files)) {
			unlink($upload_dir . '/' . $file);
		}
	}
	$valid_extensions = array('csv');

	$Upload = new FileUpload('uploadfile');
	$result = $Upload->handleUpload($upload_dir, $valid_extensions);

	if (!$result) {
		echo json_encode(array('success' => false, 'msg' => $Upload->getErrorMsg()));   
	} else {
		echo json_encode(array('success' => true, 'file' => $Upload->getFileName(), 'u' => $uploaded_files));
		if (!in_array($Upload->getFileName(), $uploaded_files))
			$uploaded_files[] = $Upload->getFileName();
		$_SESSION['uploaded_files'] = $uploaded_files;
	}

?>