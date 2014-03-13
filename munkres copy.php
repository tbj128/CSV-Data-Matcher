<?php
	// Munkres PHP Implementation
	// Inspired from github.com/bmc/munkres
	// @author: Tom Jin


class Munkres {

	private $C;
	private $row_covered;
	private $col_covered;
	private $n;
	private $Z0_r;
	private $Z0_c;
	private $marked;
	private $path;
	
    public function __construct() {
    	$C = array();
        $row_covered = array();
        $col_covered = array();
        $n = 0;
        $Z0_r = 0;
        $Z0_c = 0;
    }
//         """
//         Pad a possibly non-square matrix to make it square.
// 
//         :Parameters:
//             matrix : list of lists
//                 matrix to pad
// 
//             pad_value : int
//                 value to use to pad the matrix
// 
//         :rtype: list of lists
//         :return: a new, possibly padded, matrix
//         """
    private function pad_matrix($matrix, $pad_value = 0) {
        $max_columns = 0;
        $total_rows = count($matrix);

        foreach ($matrix as $row) {
            $max_columns = max($max_columns, count($row));
		}
        
        $total_rows = max($max_columns, $total_rows);
        
        $new_matrix = array();
        
        foreach ($matrix as $row) {
            $row_len = count($row);
            $new_row = $row;
            if ($total_rows > $row_len) {
                // Row too short. Pad it.
                for ($i = 0; $i < ($total_rows - $row_len); $i++) {
                	$new_row[] = 0;
                }
            }
            $new_matrix[] = $new_row;
        }

        while (count($new_matrix) < $total_rows) {
            $new_matrix[] = array_fill(0, $total_rows, 0);
        }

        return $new_matrix;
    }

//         """
//         Compute the indexes for the lowest-cost pairings between rows and
//         columns in the database. Returns a list of (row, column) tuples
//         that can be used to traverse the matrix.
// 
//         :Parameters:
//             cost_matrix : list of lists
//                 The cost matrix. If this cost matrix is not square, it
//                 will be padded with zeros, via a call to ``pad_matrix()``.
//                 (This method does *not* modify the caller's matrix. It
//                 operates on a copy of the matrix.)
// 
//                 **WARNING**: This code handles square and rectangular
//                 matrices. It does *not* handle irregular matrices.
// 
//         :rtype: list
//         :return: A list of ``(row, column)`` tuples that describe the lowest
//                  cost path through the matrix
// 
//         """
    public function compute($cost_matrix) {

        $this->C = $this->pad_matrix($cost_matrix);
        $this->n = count($this->C);
        $original_length = count($cost_matrix);
        $original_width = count($cost_matrix[0]);
        $this->row_covered = array_fill(0, $this->n, FALSE);
        $this->col_covered = array_fill(0, $this->n, FALSE);
        $this->Z0_r = 0;
        $this->Z0_c = 0;
        $this->path = $this->__make_matrix($this->n * 2, 0);
        $this->marked = $this->__make_matrix($this->n, 0);
        $done = FALSE;
        $step = 1;

        $steps = array( 1 => '__step1',
                  2 => '__step2',
                  3 => '__step3',
                  4 => '__step4',
                  5 => '__step5',
                  6 => '__step6');
		//var_dump($this->C);
        while ($done === FALSE) {
        	if ($step <= count($steps)) {
				try {
					$step = call_user_func(array($this, $steps[$step]));
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "<br />";
					$done = TRUE;
				}
            } else {
            	$done = TRUE;
            }
		}
		
        # Look for the starred columns
        $results = array();
        for ($i = 0; $i < $original_length; $i++) {
            for ($j = 0; $j < $original_width; $j++) {
                if ($this->marked[$i][$j] == 1) {
                    $results[] = array($i, $j);
                }
            }
        }

        return $results;
    }

        //"""Return an exact copy of the supplied matrix"""
    private function __copy_matrix($matrix) {
    	$cpy_matrix = $matrix;
    	return $cpy_matrix;
    }


        //"""Create an *n*x*n* matrix, populating it with the specific value."""
    private function __make_matrix($n, $val) {
        $matrix = array();
        for ($i = 0; $i < $n; $i++) {
        	$matrix[] = array_fill(0, $n, $val);
        }
        return $matrix;
    }

    private function __step1() {
//         """
//         For each row of the matrix, find the smallest element and
//         subtract it from every element in its row. Go to Step 2.
//         """
        $C = $this->C;
        $n = $this->n;
        for ($i = 0; $i < $n; $i++) {
            $minval = min($this->C[$i]);
            # Find the minimum value for this row and subtract that minimum
            # from every element in the row.
            for ($j = 0; $j < $n; $j++) {
            	$this->C[$i][$j] -= $minval;
            }
        }
        
        return 2;
    }

    private function __step2() {
//         """
//         Find a zero (Z) in the resulting matrix. If there is no starred
//         zero in its row or column, star Z. Repeat for each element in the
//         matrix. Go to Step 3.
//         """
        $n = $this->n;
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if (($this->C[$i][$j] == 0)
                    && (!$this->col_covered[$j])
                    && (!$this->row_covered[$i])) {
                    $this->marked[$i][$j] = 1;
                    $this->col_covered[$j] = TRUE;
                    $this->row_covered[$i] = TRUE;
                }
            }
		}
		
        $this->__clear_covers();
        return 3;
    }

    private function __step3() {
//         """
//         Cover each column containing a starred zero. If K columns are
//         covered, the starred zeros describe a complete set of unique
//         assignments. In this case, Go to DONE, otherwise, Go to Step 4.
//         """
        $n = $this->n;
        $count = 0;
        $step = 4;
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($this->marked[$i][$j] == 1) {
                    $this->col_covered[$j] = TRUE;
                    $count += 1;
                }
			}
		}
        if ($count >= $n) {
            $step = 7; // done
        }
        
        return $step;
	}
	
    private function __step4() {
//         """
//         Find a noncovered zero and prime it. If there is no starred zero
//         in the row containing this primed zero, Go to Step 5. Otherwise,
//         cover this row and uncover the column containing the starred
//         zero. Continue in this manner until there are no uncovered zeros
//         left. Save the smallest uncovered value and Go to Step 6.
//         """
        $step = 0;
        $done = FALSE;
        $row = -1;
        $col = -1;
        $star_col = -1;
        while ($done === FALSE) {
            $rowcol = $this->__find_a_zero();
            $row = $rowcol[0];
            $col = $rowcol[1];
            if ($row < 0) {
                $done = TRUE;
                $step = 6;
            } else {
                $this->marked[$row][$col] = 2;
                $star_col = $this->__find_star_in_row($row);
                if ($star_col >= 0) {
                    $col = $star_col;
                    $this->row_covered[$row] = TRUE;
                    $this->col_covered[$col] = FALSE;
                } else {
                    $done = TRUE;
                    $this->Z0_r = $row;
                    $this->Z0_c = $col;
                    $step = 5;
                }
            }
		}
		
        return $step;
    }

    private function __step5() {
//         """
//         Construct a series of alternating primed and starred zeros as
//         follows. Let Z0 represent the uncovered primed zero found in Step 4.
//         Let Z1 denote the starred zero in the column of Z0 (if any).
//         Let Z2 denote the primed zero in the row of Z1 (there will always
//         be one). Continue until the series terminates at a primed zero
//         that has no starred zero in its column. Unstar each starred zero
//         of the series, star each primed zero of the series, erase all
//         primes and uncover every line in the matrix. Return to Step 3
//         """
        $count = 0;
        $path = $this->path;
        $path[$count][0] = $this->Z0_r;
        $path[$count][1] = $this->Z0_c;
        $done = FALSE;
        while ($done === FALSE) {
            $row = $this->__find_star_in_col($path[$count][1]);
            if ($row >= 0) {
                $count += 1;
                $path[$count][0] = $row;
                $path[$count][1] = $path[$count - 1][1];
            } else {
                $done = TRUE;
            }

            if ($done === FALSE) {
                $col = $this->__find_prime_in_row($path[$count][0]);
                $count += 1;
                $path[$count][0] = $path[$count - 1][0];
                $path[$count][1] = $col;
            }
		}
        $this->__convert_path($path, $count);
        $this->__clear_covers();
        $this->__erase_primes();
        return 3;
    }

    private function __step6() {
//         """
//         Add the value found in Step 4 to every element of each covered
//         row, and subtract it from every element of each uncovered column.
//         Return to Step 4 without altering any stars, primes, or covered
//         lines.
//         """
        $minval = $this->__find_smallest();
        for ($i = 0; $i < $this->n; $i++) {
        	for ($j = 0; $j < $this->n; $j++) {
                if ($this->row_covered[$i]) {
                    $this->C[$i][$j] += $minval;
                }
                if ($this->col_covered[$j] === FALSE) {
                    $this->C[$i][$j] -= $minval;
                }
            }
        }
        return 4;
    }

    private function __find_smallest() {
        // """Find the smallest uncovered value in the matrix."""
        $minval = 2147483647;
        for ($i = 0; $i < $this->n; $i++) {
        	for ($j = 0; $j < $this->n; $j++) {
                if (($this->row_covered[$i] === FALSE) && ($this->col_covered[$j] === FALSE)) {
                    if ($minval > $this->C[$i][$j]) {
                        $minval = $this->C[$i][$j];
                    }
                }
            }
        }
        return $minval;
    }

    private function __find_a_zero() {
        // """Find the first uncovered element with value 0"""
        $rowcol = array();
        $row = -1;
        $col = -1;
        $rowcol[0] = $row;
        $rowcol[1] = $col;
        $i = 0;
        $n = $this->n;
        $done = FALSE;

        while ($done === FALSE) {
            $j = 0;
            while (TRUE) {
                if (($this->C[$i][$j] == 0)
                        && ($this->row_covered[$i] === FALSE)
                        && ($this->col_covered[$j] === FALSE)) {
                    $row = $i;
                    $col = $j;
                    $rowcol[0] = $row;
                    $rowcol[1] = $col;
                    $done = TRUE;
                    
                }
                $j += 1;
                
                if ($j >= $n) {
                    break;
                }
            }
            $i += 1;
            if ($i >= $n) {
                $done = TRUE;
            }
		}
		
        return $rowcol;
    }

    private function __find_star_in_row($row) {
//         """
//         Find the first starred element in the specified row. Returns
//         the column index, or -1 if no starred element was found.
//         """
        $col = -1;
        for ($j = 0; $j < $this->n; $j++) {
            if ($this->marked[$row][$j] == 1) {
                $col = $j;
                break;
            }
        }

        return $col;
	}

    private function __find_star_in_col($col) {
//         """
//         Find the first starred element in the specified row. Returns
//         the row index, or -1 if no starred element was found.
//         """
        $row = -1;
        for ($i = 0; $i < $this->n; $i++) {
            if ($this->marked[$i][$col] == 1) {
                $row = $i;
                break;
            }
        }

        return $row;
    }

    private function __find_prime_in_row($row) {
//         """
//         Find the first prime element in the specified row. Returns
//         the column index, or -1 if no starred element was found.
//         """
        $col = -1;
        for ($j = 0; $j < $this->n; $j++) {
            if ($this->marked[$row][$j] == 2) {
                $col = $j;
                break;
            }
        }

        return $col;
    }

    private function __convert_path($path, $count) {
        for ($i = 0; $i < ($count + 1); $i++) {
            if ($this->marked[$path[$i][0]][$path[$i][1]] == 1) {
                $this->marked[$path[$i][0]][$path[$i][1]] = 0;
            } else {
                $this->marked[$path[$i][0]][$path[$i][1]] = 1;
            }
        }
    }

    private function __clear_covers() {
        // """Clear all covered matrix cells"""
        for ($i = 0; $i < $this->n; $i++) {
            $this->row_covered[$i] = FALSE;
            $this->col_covered[$i] = FALSE;
        }
    }

    private function __erase_primes() {
        // """Erase all prime markings"""
        for ($i = 0; $i < $this->n; $i++) {
        	for ($j = 0; $j < $this->n; $j++) {
                if ($this->marked[$i][$j] == 2) {
                    $this->marked[$i][$j] = 0;
                }
            }
        }
    }
}

# ---------------------------------------------------------------------------
# Functions
# ---------------------------------------------------------------------------

function make_cost_matrix($profit_matrix) {
//     """
//     Create a cost matrix from a profit matrix by calling
//     'inversion_function' to invert each value. The inversion
//     function must take one numeric argument (of any type) and return
//     another numeric argument which is presumed to be the cost inverse
//     of the original profit.
// 
//     This is a static method. Call it like this:
// 
//     .. python::
// 
//         cost_matrix = Munkres.make_cost_matrix(matrix, inversion_func)
// 
//     For example:
// 
//     .. python::
// 
//         cost_matrix = Munkres.make_cost_matrix(matrix, lambda x : sys.maxsize - x)
// 
//     :Parameters:
//         profit_matrix : list of lists
//             The matrix to convert from a profit to a cost matrix
// 
//         inversion_function : function
//             The function to use to invert each entry in the profit matrix
// 
//     :rtype: list of lists
//     :return: The converted matrix
//     """
    $cost_matrix = array();
    foreach ($profit_matrix as $row) {
    	$cost_matrix_row = array();
    	foreach ($row as $value) {
    		if ($value != 0) {
    			$cost_matrix_row[] = 1 / ($value);
    		} else {
    			$cost_matrix_row[] = 0;
    		}
    	}
        $cost_matrix[] = $cost_matrix_row;
    }
    return $cost_matrix;

}

function print_matrix($matrix, $msg='') {
//     """
//     Convenience function: Displays the contents of a matrix of integers.
// 
//     :Parameters:
//         matrix : list of lists
//             Matrix to print
// 
//         msg : str
//             Optional message to print before displaying the matrix
//     """

    if ($msg != '') {
        echo $msg;
    }

    # Calculate the appropriate format width.
    $width = 0;
    foreach ($matrix as $row) {
        foreach ($row as $val) {
            $width = max($width, (int) (log10($val)) + 1);
        }
    }

    # Make the format string
    // $format = '%%%dd' % $width;

    # Print the matrix
    foreach ($matrix as $row) {
        $sep = '[';
        foreach ($row as $val) {
            // echo sep . $format % val);
            echo $sep . $val;
            $sep = ', ';
        }
        echo ']<br />';
    }
}

# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------

$matrices = array(
	// Square
	array(array(400, 150, 400),
	  array(400, 450, 600),
	  array(300, 225, 300)),  // expected cost

	// Rectangular variant
	array(array(400, 150, 400, 1),
	  array(400, 450, 600, 2),
	  array(300, 225, 300, 3)),  // expected cost


	// Square
	array(array(10, 10,  8),
	  array(9,  8,  1),
	  array(9,  7,  4)),

	// Rectangular variant
	array(array(10, 10,  8, 11),
	  array(9,  8,  1, 1),
	  array(9,  7,  4, 10)));

    $m = new Munkres();
    foreach ($matrices as $cost_matrix) {
    	$cost_matrix = make_cost_matrix($cost_matrix);
        print_matrix($cost_matrix, $msg='cost matrix');
        $indexes = $m->compute($cost_matrix);
        $total_cost = 0;
        foreach ($indexes as $rc) {
        	$r = $rc[0];
        	$c = $rc[1];
            $x = $cost_matrix[$r][$c];
            $total_cost += $x;
            echo '<br />(' . $r . ', ' . $c . ') -> ' . $x . '<br />';
        }
        echo '<br />lowest cost=' . $total_cost . '<br /><br />';
    }

?>