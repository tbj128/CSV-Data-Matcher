<?php
	// Project Maple
	// Created for UBC CS - Trimentoring
	// @author: Tom Jin
	// @date: Feb 2, 2014
	ini_set('max_execution_time', 300);
	include_once 'config.php';
	include_once 'functions.php';
	include_once 'munkres.php';
	
	session_start();
	$curr_page = 3;
	
	// Temp Variables
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime; 
	$max_match_headers = 0;
	
	// Data Variables
	$data_headers = $_SESSION['headers'];
	$data = $_SESSION['data'];
	$relationships = $_SESSION['relationships'];
	
	$relationships_data = $_SESSION['relationships_data'];
	$identifier_data = $_SESSION['identifier_data'];
	
	$min_score = 0;
	$max_score = 100;
	$highest_obtained_score = 0;
	$score_intervals = 8;
	$score_interval = (int) ($max_score / ($score_intervals - 1));
	
	$titles_array = array();
	$matrices = array();
	foreach ($relationships_data as $relationship_data) {
		$titles = array();
		$matrix = array();
		$y_count = 0;
		$x_count = 0;
		$titles_a = array();
		$titles_b = array();
		
		foreach ($data[$relationship_data['pair_b']] as $data_b) {
			$x_count = 0;
			$pair_a_row = array();
			$identifier_b_column = '';
						
			foreach ($data[$relationship_data['pair_a']] as $data_a) {
				$total_match = 0;
				// Gets the default identifier column (the first column)
				$identifier_a_column = '';
				foreach ($relationship_data['pair_data'] as $pair_data) {
					if ($pair_data['column_title'] != '' && $pair_data['column_match_with'] != '') {
						$total_match = $total_match + match_score_item((string) $data_a[$pair_data['column_title']], (string) $data_b[$pair_data['column_match_with']], $pair_data['column_importance'], $pair_data['column_type'], $pair_data['column_conditional']);
						if ($pair_data['column_identifier']) {
							$identifier_a_column = $pair_data['column_title'];
							$identifier_b_column = $pair_data['column_match_with'];
						}
					}
				}
				
				if ($identifier_a_column == '') {
					$titles_a[] = reset($data_a);
				} else {
					$titles_a[] = $data_a[$identifier_a_column];
				}
					
				// Writes the matches
				if ($total_match > 0) {
					$pair_a_row[] = $total_match;
				} else {
					$pair_a_row[] = 0;
				}
				
				// Records the highest obtained score
				if ($highest_obtained_score < $total_match) {
					$highest_obtained_score = $total_match;
				}
				
				$x_count++;
			}
					
			if ($y_count == 0) {
				$titles['a'] = $titles_a;
			}
			
			if ($identifier_b_column == '') {
				$titles_b[] = reset($data_b);
			} else {
				$titles_b[] = $data_b[$identifier_b_column];
			}
			
			$matrix[] = $pair_a_row;
			$y_count++;
		}
		$titles['b'] = $titles_b;
		$matrices[] = $matrix;
		$titles_array[] = $titles;
	}
	
	$_SESSION['matrices'] = $matrices;
	$_SESSION['relationships_data'] = $relationships_data;
	$_SESSION['identifier_data'] = $identifier_data;
	$_SESSION['highest_obtained_score'] = $highest_obtained_score;
	
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $APP_NAME; ?> - Visualize</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Google Web Font -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!-- Add custom CSS here -->
    <link href="css/landing-page.css" rel="stylesheet">
    <link rel="stylesheet" href="slickgrid/slick.grid.css" type="text/css"/>
	<link rel="stylesheet" href="slickgrid/css/smoothness/jquery-ui-1.8.16.custom.css" type="text/css"/>
	<link rel="stylesheet" href="slickgrid/controls/slick.pager.css" type="text/css"/>
	<style>
		.cell-title {
		font-weight: bold;
		}

		.cell-effort-driven {
		text-align: center;
		}

		.cell-selection {
		border-right-color: silver;
		border-right-style: solid;
		background: #f5f5f5;
		color: gray;
		text-align: right;
		font-size: 10px;
		}
		
		.slick-cell {
			padding:0px !important;
			margin:0px !important;
			vertical-align:middle;
		}
		
		.slick-cell, .slick-headerrow-column {
			border:1px solid #DDD !important;
		}
		
		
		.vi-header {
			width:100%;
			height:40px;
			padding:8px;
			background-color:#F8F8F8;
		}

		<?php
			// To deal with the different shades of colors
			
			$color = array('#ffffff', '#ebfae4', '#c3edad', '#8bdd60', '#76d643', '#54cc14', '#48b80c', '#3fac05');
			for ($s = 0; $s < $score_intervals; $s++) {
				echo '.vi-' . $s . ' {
					width:100%;
					height:100%;
					padding:8px;
					background-color:' . $color[$s] . ';
				}';
			}
			
		?>
    </style>

</head>

<body>

	<?php
		include "page_header.php";
	?>

    <div class="content-section-a">

        <div class="container">
		
			<div>
				<div class="row">
					<h2>Visualize</h2>
					<h4><br /><small></small></h4>
					<br />
				</div>
				<?php
					$m = 0;
					foreach ($relationships_data as $relationship_data) {
						echo '<h5>' . $relationship_data['pair_b'] . ' (Vertical) vs ' . $relationship_data['pair_a'] . ' (Horizontal)</h5>';
						echo '<div id="relationship_grid_' . $m . '" class="row" style="height:500px;">';
						echo '</div>';
						$m++;
					}
				?>
				<hr />
				<a href="match.php" class="btn btn-primary btn-lg btn-block has-spinner">
					<span class="spinner"><i class="fa fa-spin fa-refresh"></i></span>
					&nbsp;&nbsp;Save and Continue
				</a>
			</div>
        </div>
        <!-- /.container -->

    </div>

	<?php
		include "page_footer.php";
	?>
	<?php
		$mtime = microtime(); 
		$mtime = explode(" ",$mtime); 
		$mtime = $mtime[1] + $mtime[0]; 
		$endtime = $mtime; 
		$totaltime = ($endtime - $starttime); 
		echo "<p>This page was created in ".$totaltime." seconds<br />"; 
		echo "Max memory usage: " . memory_get_peak_usage() / 1000 . "kB </p>";
	?>

    <!-- JavaScript -->
	<script src="slickgrid/lib/jquery-1.7.min.js"></script>
    <script src="js/bootstrap.js"></script>
	<script src="slickgrid/lib/jquery-ui-1.8.16.custom.min.js"></script>
	<script src="slickgrid/lib/jquery.event.drag-2.2.js"></script>

	<script src="slickgrid/slick.core.js"></script>
	<script src="slickgrid/slick.formatters.js"></script>
	<script src="slickgrid/slick.grid.js"></script>
	<script src="slickgrid/slick.dataview.js"></script>
	<script src="slickgrid/controls/slick.pager.js"></script>

	<script>
		
		function f_h(row, cell, value, columnDef, dataContext) {
	    	return "<div class='vi-header'>" + value + "</div>";
		}
		function f_i(row, cell, value, columnDef, dataContext) {
	    	var colorInterval = parseInt(value / <?php echo $score_interval; ?>);
	    	return "<div class='vi-" + colorInterval + "'>" + value + "</div>";
		}
		
		$(function() {
			$('a.has-spinner').click(function() {
				$(this).toggleClass('active');
			});
		});
		
		<?php
			$s = 0;
			foreach ($titles_array as $titles) {
				echo 'var titles_' . $s . ' = [[';
				$row_pos = 0;
				$last_row_pos = count($titles['a']) - 1;
				foreach ($titles['a'] as $titles_item) {
					if ($row_pos != $last_row_pos) {
						echo '"' . $titles_item . '",';
					} else {
						echo '"' . $titles_item . '"';
					}
					$row_pos++;
				}
				echo '],[';
				$row_pos = 0;
				$last_row_pos = count($titles['b']) - 1;
				foreach ($titles['b'] as $titles_item) {
					if ($row_pos != $last_row_pos) {
						echo '"' . $titles_item . '",';
					} else {
						echo '"' . $titles_item . '"';
					}
					$row_pos++;
				}
				echo ']];';
				$s++;
			}
			
			echo 'var max_score = ' . $max_score . ';';
			echo 'var num_matrices = ' . count($matrices) . ';';
			
			for ($x = 0; $x < count($matrices); $x++) {
				echo 'var grid_' . $x . ';';
				echo 'var columns_' . $x . ' = [';
				$z = 0;
				$last_row_pos = count($titles_array[$x]['a']) - 1;
				echo '{id: "vertical-items-' . $x . '", name: "&times;", field: "vi_' . $x . '", formatter: f_h},';
				foreach ($titles_array[$x]['a'] as $title) {
					echo '{id: "' . $x . '-' . $z . '", name: "' . $title . '", field: "' . $x . '_' . $z . '", formatter: f_i, sortable: true}';
					if ($z != $last_row_pos) {
						echo ',';
					}
					$z++;
				}
				echo '];';
			}
			
			$s = 0;
			foreach ($matrices as $matrix) {
				echo 'var matrix_' . $s . ' = [';
				$row_pos = 0;
				$last_row_pos = count($matrix) - 1;
				foreach ($matrix as $matrix_row) {
					echo '[';
					$col_pos = 0;
					$last_col_pos = count($matrix_row) - 1;
					foreach ($matrix_row as $matrix_column) {
						if ($highest_obtained_score > 0) {
							echo round(($matrix_column / $highest_obtained_score), 3) * 100;
						} else {
							echo $matrix_column;
						}
						if ($col_pos != $last_col_pos) {
							echo ',';
						}
						$col_pos++;
					}
					echo ']';
					if ($row_pos != $last_row_pos) {
						echo ',';
					}
					$row_pos++;
				}
				echo '];';
				$s++;
			}
			
			echo 'var options = {
				headerRowHeight: 38,
				rowHeight: 38,
				enableCellNavigation: true,
				enableColumnReorder: false,
    			multiColumnSort: false,
				frozenColumn: 0 };';
				
			echo '$(function () {';			
			for ($x = 0; $x < count($matrices); $x++) {
				echo 'var data_' . $x . ' = [];';
			}
			echo 'var titles_index = [';
			for ($x = 0; $x < count($matrices); $x++) {
				echo 'titles_' . $x;
				if ($x != (count($matrices) - 1)) {
					echo ',';
				}
			}
			echo '];';
			echo 'var matrix_index = [';
			for ($x = 0; $x < count($matrices); $x++) {
				echo 'matrix_' . $x;
				if ($x != (count($matrices) - 1)) {
					echo ',';
				}
			}
			echo '];';
			?>
			
			var data = [];
			for (var s = 0; s < num_matrices; s++) {
				data[s] = [];
				$.each(matrix_index[s], function(i, matrix_item){
					data[s][i] = {};
					data[s][i]['id'] = 'id' + s + '_' + i;
					data[s][i]['vi_' + s] = titles_index[s][1][i];
					$.each(matrix_item, function(j, matrix_row_item){
						data[s][i][s + '_' + j] = matrix_row_item;
					});
				});
			}
			
		<?php
			echo 'dataView = [];';
			for ($x = 0; $x < count($matrices); $x++) {
				echo 'dataView[' . $x . '] = new Slick.Data.DataView();';
				echo 'grid_' . $x . ' = new Slick.Grid("#relationship_grid_' . $x . '", dataView[' . $x . '], columns_' . $x . ', options);';
				echo '	dataView[' . $x . '].onRowCountChanged.subscribe(function (e, args) {
						  grid_' . $x . '.updateRowCount();
						  grid_' . $x . '.render();
						});
						dataView[' . $x . '].onRowsChanged.subscribe(function (e, args) {
						  grid_' . $x . '.invalidateRows(args.rows);
						  grid_' . $x . '.render();
						});';
				echo 'dataView[' . $x . '].setItems(data[' . $x . ']);';
				echo 'grid_' . $x . '.onSort.subscribe(function (e, args) {
					var comparer = function(a, b) {
						return (a[args.sortCol.field] > b[args.sortCol.field]) ? 1 : -1;
					}
					dataView[' . $x . '].sort(comparer, args.sortAsc);
					});';
			}
			echo '});';
		?>
	</script>
</body>

</html>