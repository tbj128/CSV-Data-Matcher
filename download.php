<?php
	// Project Maple
	// Created for UBC CS - Trimentoring
	// @date: Feb 2, 2014
	// @author: Tom Jin
	ini_set('max_execution_time', 300);
	
	include_once 'config.php';
	include_once 'functions.php';
	include_once 'munkres.php';
	
	session_start();

	// Data Variables
	$relationships = $_SESSION['relationships'];
	$relationships_data = $_SESSION['relationships_data'];
	$csv_output = $_SESSION['csv_output'];
	
	if (isset($_GET['r'])) {
		// The dataset pairing to generate the CSV for
		$data_pair = $_GET['r'];
		if ($data_pair >= 0 && $data_pair < count($relationships)) {
			$dataset_name_a = $relationships[$data_pair][0];
			$dataset_name_b = $relationships[$data_pair][1];
			array_to_csv_download($csv_output[$data_pair], "match_" . str_replace(' ', '', basename($dataset_name_a, ".csv")) . "_" . str_replace(' ', '', basename($dataset_name_b, ".csv")) . '.csv');
		}
	}
?>