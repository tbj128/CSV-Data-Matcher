<?php
	// Project Maple
	// Created for UBC CS - Trimentoring
	// Match and download the data
	// @author: Tom Jin
	// @date: Feb 2, 2014
	
	include_once 'config.php';
	include_once 'functions.php';
	include_once 'munkres.php';
	
	session_start();
	
	// Data Variables
	$data_headers = $_SESSION['headers'];
	$data = $_SESSION['data'];
	$relationships = $_SESSION['relationships'];
	$matrices = $_SESSION['matrices'];
	$relationships_data = $_SESSION['relationships_data'];
	
	$a = 0;
 	foreach ($relationships_data as $relationship_data) {
 		$m = new Munkres();
//  		$a = array(array(400, 150, 400),
// 			  array(400, 450, 600),
// 			  array(300, 225, 300));  // expected cost
		// $a = make_cost_matrix($a);
		$matrices[$a] = make_cost_matrix($matrices[$a]);
 		// var_dump($matrices[$a]);
		$indexes = $m->compute($matrices[$a]);
		// $indexes = $m->compute($matrices[$a], true);
		// $total_cost = 0;
		foreach ($indexes as $rc) {
			$r = $rc[0];
			$c = $rc[1];
			//$x = $a[$r][$c];
			$x = $matrices[$a][$r][$c];
			// $total_cost += $x;
			echo '<br />(' . $r . ', ' . $c . ') -> ' . $x . '<br />';
		}
		$a++;
		echo '<br /><br /><br />==============<br /><br /><br />';
	}


// 	$titles_array = array();
// 	$matrices = array();
// 	foreach ($relationships_data as $relationship_data) {
// 		$titles = array();
// 		$matrix = array();
// 		$y_count = 0;
// 		$x_count = 0;
// 		$titles_a = array();
// 		$titles_b = array();
// 		
// 		foreach ($data[$relationship_data['pair_b']] as $data_b) {
// 			$x_count = 0;
// 			$pair_a_row = array();
// 						
// 			foreach ($data[$relationship_data['pair_a']] as $data_a) {
// 				$total_match = 0;
// 				foreach ($relationship_data['pair_data'] as $pair_data) {
// 					$total_match = $total_match + match_score_item((string) $data_a[$pair_data['column_title']], (string) $data_b[$pair_data['column_match_with']], $pair_data['column_importance'], $pair_data['column_type'], $pair_data['column_conditional']);
// 				}
// 				// TODO - specific which row to use as name?
// 				$titles_a[] = reset($data_a);
// 					
// 				if ($total_match > 0) {
// 					$pair_a_row[] = $total_match;
// 				} else {
// 					$pair_a_row[] = 0;
// 				}
// 				
// 				$x_count++;
// 			}
// 					
// 			if ($y_count == 0) {
// 				$titles['a'] = $titles_a;
// 			}
// 			// TODO - specific which row to use as name?
// 			$titles_b[] = reset($data_b);
// 			
// 			$matrix[] = $pair_a_row;
// 			$y_count++;
// 		}
// 		$titles['b'] = $titles_b;
// 		//echo 'asdf';
// 		//var_dump($relationships_data);
// 		$matrices[] = $matrix;
// 				//var_dump(count($matrix));
// 				//echo 'asfsfd';
// 		$titles_array[] = $titles;
// 	}
?>