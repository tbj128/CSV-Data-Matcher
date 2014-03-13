<?php
	include_once 'functions.php';
	
	function substr_check($a, $b) {
		$a_len = strlen($a);
		$b_len = strlen($b);
		$a_p = str_split($a);
		$b_p = str_split($b);
		
		$a_pos = 0;
		$b_pos = 0;
		$b_num_letters_in_a_row = 0;
		$b_max_num_letters_in_a_row = 0;
		if ($a_len < $b_len) {
			for ($b_pos = 0; $b_pos < $b_len; $b_pos += 1) {
				if ($a_pos >= $a_len) {
					break;;
				}
				if ($b[$b_pos] == $a[$a_pos]) {
					$b_num_letters_in_a_row += 1;
					$a_pos++;
				} else {
					$b_max_num_letters_in_a_row = max($b_max_num_letters_in_a_row, $b_num_letters_in_a_row);
					$b_num_letters_in_a_row = 0;
					$a_pos = 0;
				}
			}
			if ($b_max_num_letters_in_a_row == $a_len) {
				return 100;
			} else {
				return 100 * ($b_max_num_letters_in_a_row / $a_len);
			}
		} else {
			for ($a_pos = 0; $a_pos < $a_len; $a_pos += 1) {
				if ($b_pos >= $b_len) {
					break;
				}
				if ($b[$b_pos] == $a[$a_pos]) {
					$b_num_letters_in_a_row += 1;
					$b_pos++;
				} else {
					$b_max_num_letters_in_a_row = max($b_max_num_letters_in_a_row, $b_num_letters_in_a_row);
					$b_num_letters_in_a_row = 0;
					$b_pos = 0;
				}
			}
			if ($b_max_num_letters_in_a_row == $b_len) {
				return 100;
			} else {
				return 100 * ($b_max_num_letters_in_a_row / $b_len);
			}
		}
	}
	

   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $starttime = $mtime; 
	
	$c = "twilight, fluttershy, rainbow dash, rarity and spike";
	$d = "rarity and spike, fluttershy, rainbow dash, twilight";
	$e = "applebloom, Sweetie Belle";
	$f = "rinbow dash, futtershy, twilight, twilight, applejack, eqd";
	$g = "rinbow dashs, futtershy, twilights";
	
	$b = 	"biking, running, investing";
	$a = 	"Photography";
	$x = "hockeys";
	$y = "hockey";
	$s = "playing hockey";
	$t = "watching hockey";
	
	// playing hockey
	// watching hockey  => 0.5
	// hockey => 0.5
	
	// hockey > 1
	// playing > 1
	
	// 
	
	$tags_a = string_to_array($x);
	$tags_b = string_to_array($y);
	
	// echo both_match_with($y, $y, "hockey");
	echo complete_match($y, $x);
	
	// echo array_similarity($tags_a, $tags_b);
	//similar_text($g, $f, $p);
	//echo $p;
	// echo substr_check($x, $y);
	
// 	if (strpos($f, $g) !== FALSE) {
// 		echo 'asfd';
// 	} else {
// 		echo 'qwer';
// 	}
	
	$mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
   echo "<br />This page was created in ".$totaltime." seconds<br />"; 
	echo "Max memory usage: " . memory_get_peak_usage() / 1000 . "kB";
	// echo $percent_similarity;
?>