<?php
	// Functions that power the matching process including match score algorithms
	// @author: Tom Jin
	// @date: Feb 10, 2014

	include_once 'config.php';

	// ***
	// Raw CSV Parsing Functions
	// ***

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
	
	
	// ***
	// String Operations
	// ***
	
	function string_clean($str) {
		$new_str = str_replace(' and ', '', $str);
		$new_str = str_replace(' or ', '', $str);
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
	
	
	// ***
	// Similarity Determination
	// ***
	
	
	// Determines how similar two arrays are
	// These array items may be one or more words in length so this has to be taken 
	// into consideration when performing similarity matching
	// eg. "hockey" and "playing hockey" are obviously similar but are not completely the same
	// (future consideration: "playing hockey" and "watching hockey")
	// As well, plural items should be taken into consideration as well
	// eg. "cat" and "cats" should be very similar
	function array_similarity($a, $b) {
		$similarity = 0;
		if (count($b) > count($a)) {
			$a_scores = array();
			foreach ($a as $a_item) {
				$scores = array();
				foreach ($b as $b_item) {
					$score = 0;
					if ($a_item == '' || $b_item == '') {
						continue;
					}
					if (strcmp($a_item , $b_item) == 0) {
						// Same item
						$score = 100;
					} else if (strpos($a_item, $b_item) !== FALSE) {
						$score = 75;
					} else if (strpos($b_item, $a_item) !== FALSE) {
						$score = 75;
					}
					$scores[] = $score;
				}
				if (count($scores) > 0)
					$a_scores[] = max($scores);
				else 
					$a_scores[] = 0;
			}
			if (count($a_scores) > 0) {
				$similarity = array_sum($a_scores) / count($a_scores);
			}
		} else {
			$b_scores = array();
			foreach ($b as $b_item) {
				$scores = array();
				foreach ($a as $a_item) {
					$score = 0;
					if ($a_item == '' || $b_item == '') {
						continue;
					}
					if (strcmp($a_item , $b_item) == 0) {
						// Same item
						$score = 100;
					} else if (strpos($a_item, $b_item) !== FALSE) {
						$score = 75;
					} else if (strpos($b_item, $a_item) !== FALSE) {
						$score = 75;
					}
					$scores[] = $score;
				}
				if (count($scores) > 0)
					$b_scores[] = max($scores);
				else 
					$b_scores[] = 0;
			}
			if (count($b_scores) > 0) {
				$similarity = array_sum($b_scores) / count($b_scores);
			}
		}
		return $similarity;
	}

	// Returns 100 if and only if a and b are exact matches
	// Returns 0 otherwise
	// eg. If for example, one needs to match genders
	// eg. male must match exactly with male (and not female - which
	// male is a substring of)
	function complete_match($a, $b) {
		if (is_string($a) || is_string($b)) {
			if (strcmp($a , $b) == 0) {
				return 100;
			} else {
				return 0;
			}
		} else {
			if ($a == $b) {
				return 100;
			} else {
				return 0;
			}
		}
	}
	
	// Returns 100 if and only if a and b are numbers within the range provided of each other
	// Returns 0 otherwise
	// eg. Given a range of 10, and a=4 and b=13, a return value of 100 will be returned as 
	// 13 - 4 < 10
	function match_within_range($a, $b, $range) {
		if (is_int($a) && is_int($b)) {
			if (abs($b - $a) <= $range) {
				return 100;	
			}
		}
		return 0;
	}

	// Returns 100 if and only if a and b both match with the given match parameter
	// Returns 0 otherwise
	// eg. If for example, one wants to match people by if they can come a given day
	// This day would be either "true" (they can come) or "false" (they cannot come)
	// Giving a high match score for two people who cannot come on a given day would be 
	// pretty useless
	function both_match_with($a, $b, $match) {
		if (strcmp((string) $a, (string) $match) == 0 &&
			strcmp((string) $b, (string) $match) == 0) {
			return 100;	
		} else {
			return 0;
		}
	}

	// Returns a score between how closely matched a and b are
	// @param: Higher $priority will result in a higher score
	// @return: Higher score denotes a more closely related item
	function match_score_item($a, $b, $priority, $match_type, $match_term) {
		global $MATCH_NOT_USED;
		global $MATCH_EXACT;
		global $MATCH_INEXACT;
		global $MATCH_EXACT_WITH_PROVIDED;
		global $MATCH_SIMILAR_TEXT;
		global $MATCH_WITHIN_RANGE;
		
		switch ($match_type) {
			case $MATCH_NOT_USED:
				return 0;
			case $MATCH_EXACT:
				return complete_match($a, $b) * $priority;
			case $MATCH_INEXACT:
				$a_arr = string_to_array($a);
				$b_arr = string_to_array($b);
				return array_similarity($a_arr, $b_arr) * $priority;
			case $MATCH_EXACT_WITH_PROVIDED:
				return both_match_with($a, $b, $match_term) * $priority;
			case $MATCH_SIMILAR_TEXT:
				similar_text($a, $b, $per);
				return $per * $priority;
			case $MATCH_WITHIN_RANGE:
				return match_within_range($a, $b, $match_term);
		}
	}
?>