<?php
	function csv_get_headers($raw_filename) {
		$filename = 'csv/' . $raw_filename;
		if(!is_readable($filename)) {
			return FALSE;
		}

		$delimiter = ',';
		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE) {
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
				// Just take the first row
				$data = $row;
				break;
			}
			fclose($handle);
		}
		return $data;
	}
	
	function csv_get_array($raw_filename) {
		$filename = 'csv/' . $raw_filename;
		if(!is_readable($filename)) {
			return FALSE;
		}

		$delimiter = ',';
		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE) {
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
				if(!$header) {
					$header = $row;
				}
				else {
					$data[] = array_combine($header, $row);
				}
			}
			fclose($handle);
		}
		return $data;
	}
	
	
	
	
	function string_clean($str) {
		$new_str = str_replace('and', '', $str);
		$new_str = str_replace('or', '', $str);
		return strtolower(trim($new_str));
	}
	
	function string_to_array($str) {
		$new_array = array();
		$tmp_array = explode(',', $str);
		foreach ($tmp_array as $tmp_array_item) {
			$new_array[] = string_clean($tmp_array_item);
		}
		return $new_array;
	}
	
		// Calculates the similarity between two arrays word-by-word
	// @requires: the two arrays are alphabetically sorted
	function array_similarity($array_a, $array_b) {
		
		$scores = array();
		
		$pos_a = 0;
		$pos_b = 0;
		$inc_a = true;
		
		$len_a = count($array_a);
		$len_b = count($array_b);
		
		$tmp_scores = array();
		
		while (($pos_a < $len_a) && ($pos_b < $len_b)) {
			if ($pos_a >= $len_a)
				$pos_a = $len_a - 1;
		
			if ($pos_b >= $len_b)
				$pos_b = $len_b - 1;
			
			
			if (strcmp($array_a[$pos_a], $array_b[$pos_b]) == 0) {
				$scores[] = 100;
				//echo "0 A: " . $array_a[$pos_a] . "   B: " . $array_b[$pos_b] . '<br />';
				$pos_a++;
				$pos_b++;
				$inc_a = true;
			} else if (strcmp($array_a[$pos_a], $array_b[$pos_b]) < 0) {
				if ($inc_a === false) {
					// Switch occurred
					if (count($tmp_scores) > 0) {
						$scores[] = max($tmp_scores);
					}
					$tmp_scores = array();
				}
				//similar_text($array_a[$pos_a], $array_b[$pos_b], $similarity);
				$similarity = string_similarity2($array_a[$pos_a], $array_b[$pos_b]);
				//echo "1 A: " . $array_a[$pos_a] . "   B: " . $array_b[$pos_b] . ' with ' . $similarity. '<br />';
				$pos_a++;
				$tmp_scores[] = $similarity;
				$inc_a = true;
			} else {
				if ($inc_a === true) {
					// Switch occurred
					if (count($tmp_scores) > 0)
						$scores[] = max($tmp_scores);
					$tmp_scores = array();
				}
				//similar_text($array_a[$pos_a], $array_b[$pos_b], $similarity);
				$similarity = string_similarity2($array_a[$pos_a], $array_b[$pos_b]);
				//echo "2 A: " . $array_a[$pos_a] . "   B: " . $array_b[$pos_b] . ' with ' . $similarity. '<br />';
				$pos_b++;
				$tmp_scores[] = $similarity;
				$inc_a = false;
			}
		}
		
		// Returns the average of the scores
		$total_score = 0;
		foreach ($scores as $score) {
			$total_score += $score;
		}
		
		if (count($scores) < 1) {
			return 0;
		} else {
			return $total_score / (count($scores));
		}
	}
	function n2($a, $b) {
	$scores = array();
	foreach($a as $a_item) {
		$tmpscores = array();
		$tmpscores[] = 0;
		foreach($b as $b_item) {
		//echo "A " . $a_item . " B " . $b_item . " sim " . string_similarity($a_item, $b_item) . "<Br />";
			$tmpscores[] = string_similarity2($a_item, $b_item);
		}
		// echo sqr(max($tmpscores)) . "<Br />";
		$scores[] = pow(max($tmpscores), 2);
	}
	// Returns the average of the scores
	$total_score = 0;
	foreach ($scores as $score) {
		$total_score += $score;
	}
	
	if (count($scores) < 1) {
		return 0;
	} else {
		return sqrt($total_score / (count($scores)));
	}
}
	function string_similarity2($str_a, $str_b) {
		$ham = ham($str_a, $str_b);
		if ($ham < 2) {
			// Treat as identical
			return 100;
		} else {
			similar_text($str_a, $str_b, $per);
			return $per;
		}
	}
	
	function ham($a, $b) {

		$ham_distance = 10000;
		
		$len_a = strlen($a);
		$len_b = strlen($b);
		
		$a_arr = str_split($a);
		$b_arr = str_split($b);
			
		if ($len_a == $len_b) {
			// How many characters do they differ by?
			for($i = 0; $i < $len_a; $i++) {
				if ($a_arr[$i] != $b_arr[$i]) {
					$ham_distance++;
				}
			}
		} 
		
		return $ham_distance;
	}
	
	
	// Calculates the similarity between two arrays word-by-word
	// @requires: the two arrays are alphabetically sorted
	function array_similarity2($array_a, $array_b) {
		
		$scores = array();
		
		$pos_a = 0;
		$pos_b = 0;
		$inc_a = true;
		
		$len_a = count($array_a);
		$len_b = count($array_b);
		
		$tmp_scores = array();
		
		while (($pos_a < $len_a) && ($pos_b < $len_b)) {
			if ($pos_a >= $len_a)
				$pos_a = $len_a - 1;
		
			if ($pos_b >= $len_b)
				$pos_b = $len_b - 1;
			
			
			if (strcmp($array_a[$pos_a], $array_b[$pos_b]) == 0) {
				$scores[] = 100;
				$pos_a++;
				$pos_b++;
				$inc_a = true;
			} else if (strcmp($array_a[$pos_a], $array_b[$pos_b]) < 0) {
				if ($inc_a === false) {
					// Switch occurred
					if (count($tmp_scores) > 0) {
						$scores[] = max($tmp_scores);
					}
					$tmp_scores = array();
				}
				similar_text($array_a[$pos_a], $array_b[$pos_b], $similarity);
				$pos_a++;
				$tmp_scores[] = $similarity;
				$inc_a = true;
			} else {
				if ($inc_a === true) {
					// Switch occurred
					if (count($tmp_scores) > 0)
						$scores[] = max($tmp_scores);
					$tmp_scores = array();
				}
				similar_text($array_a[$pos_a], $array_b[$pos_b], $similarity);
				$pos_b++;
				$tmp_scores[] = $similarity;
				$inc_a = false;
			}
		}
		
		// Returns the average of the scores
		$total_score = 0;
		foreach ($scores as $score) {
			$total_score += $score;
		}
		
		if (count($scores) < 1) {
			return 0;
		} else {
			return $total_score / (count($scores));
		}
	}
	
	function string_similarity($str_a, $str_b, $match_type) {
		if ($match_type == 1) {
			// Exact Match
			if ($str_a == $str_b) {
				return 100;
			} else {
				return 0;
			}
		} else if ($match_type == 2) {
			// Similar Match
			similar_text($str_a, $str_b, $percent_similarity);
			return $percent_similarity;
		} else if ($match_type == 3) {
			// Keyword Match
			$a = string_to_array($str_a);
			$b = string_to_array($str_b);
			sort($a);
			sort($b);
			if (count($a) < 1 || count($b) < 1) {
				return 0;
			} else {
				return array_similarity($a, $b);
			}
		} else if ($match_type == 4) {
			$a = string_to_array($str_a);
			$b = string_to_array($str_b);
			return n2($a, $b);
		}
	}
	
	
	// Returns a score between how closely matched a and b are
	// @param: Higher $priority will result in a higher score
	// @return: Higher score denotes a more closely related item
	function match_score_item($a, $b, $priority, $match_type) {
		return string_similarity($a, $b, $match_type) * $priority;
	}
?>