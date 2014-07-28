<?php

	include_once 'config.php';
	include_once 'functions.php';
	
	session_start();
	
	$csv_files = array();
	if (isset($_POST['upload_items'])) {
		$uploaded_items = explode(",", $_POST['upload_items']);
		foreach ($uploaded_items as $uploaded_item) {
			$csv_files[] = $uploaded_item;
		}
	}
	
	$data_headers = array();
	$data = array();
	foreach ($csv_files as $csv_file) {
		$data_headers[$csv_file] = csv_get_headers($csv_file);
		$data[$csv_file] = csv_get_array($csv_file);
	}
	
	$_SESSION['headers'] = $data_headers;
	$_SESSION['data'] = $data;
	header( 'Location: diverge.php' ) ;
?>