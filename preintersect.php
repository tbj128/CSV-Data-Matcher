<?php
	include_once 'config.php';
	include_once 'functions.php';
	
	session_start();
	
	$data_headers = $_SESSION['headers'];
	$data = $_SESSION['data'];
	$number_pairs = count($data) - 1;
	
	$relationships = array();
	if ($_POST) {
		for($i = 0; $i < $number_pairs; $i++) {
			$pair = array();
			$pair[] = $_POST['match_' . $i . '_a'];
			$pair[] = $_POST['match_' . $i . '_b'];
			$relationships[] = $pair;
		}
		$_SESSION['relationships'] = $relationships;
		header( 'Location: intersect.php' ) ;
	}
	
?>