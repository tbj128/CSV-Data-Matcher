<?php
	// Project Maple
	// Created for UBC CS - Trimentoring
	// @author: Tom Jin
	// @date: Feb 2, 2014
	
	session_start();

	// Data Variables
	$data_headers = $_SESSION['headers'];
	$data = $_SESSION['data'];
	$relationships = $_SESSION['relationships'];
	
	$relationships_data = array();
	$identifier_data = array();

	for ($j = 0; $j < count($relationships); $j++) {
		$relationship_data = array();
		$relationship_data['pair_a'] = $_POST[$j . '_pair_a'];
		$relationship_data['pair_b'] = $_POST[$j . '_pair_b'];
		$row_count = $_POST[$j . '_row_count'];
		for ($i = 0; $i < $row_count; $i++) {
			$relationship_column_data = array();
			if (isset($_POST[$j . '_identifier'][$i])) {
				$relationship_column_data['column_identifier'] = true;
				$identifier_data[$relationship_data['pair_a']] = $_POST[$j . '_' . $i . '_header'];
				$identifier_data[$relationship_data['pair_b']] = $_POST[$j . '_' . $i . '_match_with'];
			} else {
				$relationship_column_data['column_identifier'] = false;
			}
			$relationship_column_data['column_title'] = $_POST[$j . '_' . $i . '_header'];
			$relationship_column_data['column_match_with'] = $_POST[$j . '_' . $i . '_match_with'];
			$relationship_column_data['column_importance'] = $_POST[$j . '_' . $i . '_importance'];
			$relationship_column_data['column_type'] = $_POST[$j . '_' . $i . '_type'];
			$relationship_column_data['column_conditional'] = $_POST[$j . '_' . $i . '_conditional'];
			if ($_POST[$j . '_' . $i . '_importance'] != 0) {
				$max_match_headers++;
			}
			$relationship_data['pair_data'][] = $relationship_column_data;
		}
		$relationships_data[] = $relationship_data;
	}
	
	$_SESSION['relationships_data'] = $relationships_data;
	$_SESSION['identifier_data'] = $identifier_data;
	
	header( 'Location: visualize.php' ) ;
?>