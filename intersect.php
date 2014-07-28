<?php
	// Project Maple
	// Created for UBC CS - Trimentoring
	// @date: Feb 2, 2014
	// @author: Tom Jin
	
	include_once 'config.php';
	include_once 'functions.php';
	
	session_start();
	$curr_page = 2;
	
	$data_headers = $_SESSION['headers'];
	$data = $_SESSION['data'];
	$relationships = $_SESSION['relationships'];
	
	if (!isset($_SESSION['relationships'])) {
	   	header("Location: index.php");
	   	exit();
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $APP_NAME; ?> - Intersection</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Google Web Font -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!-- Add custom CSS here -->
    <link href="css/landing-page.css" rel="stylesheet">
</head>

<body>

	<?php
		include "page_header.php";
	?>

    <div class="content-section-a">
        <div class="container">
			<div style="margin-top:48px;">
			<div class="page-header">
              	<h1 id="navbar">Matching Parameters</h1>
              	<div style="width:800px;">
              		<blockquote>Similarity between corresponding rows in a dataset is determined by the similarity between certain columns. Indicate how these columns are to be compared with each other below</blockquote>
				</div>
			</div>
				<form method="post" action="previsualize.php">
					<?php
						$j = 0;
						foreach ($relationships as $relationship) {
					?>
					<div class="row pale-box">
						<h3><?php echo $relationship[0]; ?> matched with <?php echo $relationship[1]; ?></h3>
						<table class="table table-hover" id="<?php echo $j; ?>-table">
							<thead>
								<tr>
									<td>Identifier  <a href="help.php" target="_blank" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Check to mark a column in the CSV files as the identifying column (eg. name of student)">(?)</a></td>
									<td><?php echo $relationship[0]; ?>  <a href="help.php" target="_blank" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Data columns from the first dataset.">(?)</a></td>
									<td></td>
									<td><?php echo $relationship[1]; ?> <a href="help.php" target="_blank" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Data columns from the second dataset.">(?)</a></td>
									<td>Importance <a href="help.php" target="_blank" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="The relative importance of this data column match pair compared to other data column match pairs.">(?)</a></td>
									<td>Match Type <a href="help.php" target="_blank" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Exact Match: Two data cells must be identical. Inexact Match: Two data cells are comma separated lists. Conditional Match: Two data cells must match up with third.">(?)</a></td>
									<td>Conditional <a href="help.php" target="_blank" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Phrase used in conditional matches OR number used in range matches.">(?)</a></td>
								</tr>
							</thead>
							<tbody>
								<?php
										$i = 0;
										echo '<input type="hidden" name="' . $j . '_pair_a" value="' . $relationship[0] . '">';
										echo '<input type="hidden" name="' . $j . '_pair_b" value="' . $relationship[1] . '">';

										foreach ($data_headers[$relationship[0]] as $header) {
											echo '<tr>';
											echo '<td><input class="identifier-select identifier-select-' . $j . '" type="checkbox" id="' . $j . '_' . $i . '_identifier" data-pair="' . $j . '" data-item="' . $i . '" name="' . $j . '_identifier[' . $i . ']" value="1" /></td>';
											echo '<td><input type="hidden" name="' . $j . '_' . $i . '_header" value="' . $header . '">
												' . $header . '</td>';
											echo '<td style="color:#CECECE;"> pairs with </td>';
											echo '<td>
												<select class="form-control" name="' . $j . '_' . $i . '_match_with">';
												echo '<option value="">-</option>';
												foreach ($data_headers[$relationship[1]] as $inner_header) {
													echo '<option value="' . $inner_header . '">' . $inner_header . '</option>';
												}
											echo '</select></td>';
											echo '<td>
												<select class="importance-select form-control" data-pair="' . $j . '" data-item="' . $i . '" name="' . $j . '_' . $i . '_importance" id="' . $j . '_' . $i . '_importance">
												  <option value="0">Not Applicable</option>
												  <option value="16">Very Important</option>
												  <option value="8">Important</option>
												  <option value="4">Somewhat Important</option>
												  <option value="1">Least Important</option>
												</select></td>';
											echo '<td>
												<select class="type-select form-control" data-pair="' . $j . '" data-item="' . $i . '" name="' . $j . '_' . $i . '_type" id="' . $j . '_' . $i . '_type">
												  <option value="1">Exact Match</option>
												  <option value="4">Similar Wording</option>
												  <option value="2">Comma Separated Lists</option>
												  <option value="3">Strong Conditional</option>
												  <option value="6">Weak Conditional</option>
												  <option value="5">Match Within Range</option>
												</select><span id="' . $j . '_' . $i . '_type_empty">-</span></td>';
											echo '<td>
													<input type="text" name="' . $j . '_' . $i . '_conditional" id="' . $j . '_' . $i . '_conditional" style="display:none;" data-toggle="tooltip" data-placement="top" data-original-title="A good match occurs when both data fields match one of the terms described in a comma-separated list." />
													<span id="' . $j . '_' . $i . '_conditional_empty">-</span>
												  </td>
												</tr>';
											$i++;
										}
								?>
								<tr id="add-row-<?php echo $j; ?>">
									<td colspan="7" style="text-align:center;">
										<input type="hidden" value="<?php echo $i ?>" name="<?php echo $j; ?>_row_count" id="<?php echo $j; ?>_row_count">
										<button type="button" class="btn-add-row btn btn-info" data-tableno="<?php echo $j ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Another Match Pair</button>
									</td>
								</tr>
							</tbody>
						</table>
						<hr />
					</div>
					<?php
						$j++;
						}
					?>
					<button type="submit" class="btn btn-primary btn-lg btn-block has-spinner">
						<span class="spinner"><i class="fa fa-spin fa-refresh"></i></span>
						&nbsp;&nbsp;Save and Continue
					</button>
				</form>
			</div>
        </div>
        <!-- /.container -->

    </div>

	<?php
		include "page_footer.php";
	?>

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
    	function addRow(table_no, table_rows, new_table_row) {
			var html;
			html += '<tr><td><input class="identifier-select identifier-select-' + table_no + '" type="checkbox" id="' + table_no + '_' + new_table_row + '_identifier" data-pair="' + table_no + '" data-item="' + new_table_row + '" name="' + table_no + '_identifier[' + new_table_row + ']" value="1"></td>';
			html += '<td><select class="form-control" id="' + table_no + '_' + new_table_row + '_header" name="' + table_no + '_' + new_table_row + '_header">';
			<?php
				echo 'html += \'<option value="">-</option>\';';
				foreach ($data_headers[$relationship[0]] as $inner_header) {
					echo 'html += \'<option value="' . $inner_header . '">' . $inner_header . '</option>\';';
				}
			?>
			html += '</select></td>';
			html += '<td style="color:#CECECE;"> pairs with </td><td>';
			html += '<select class="form-control" name="' + table_no + '_' + new_table_row + '_match_with">';
			<?php
				echo 'html += \'<option value="">-</option>\';';
				foreach ($data_headers[$relationship[1]] as $inner_header) {
					echo 'html += \'<option value="' . $inner_header . '">' . $inner_header . '</option>\';';
				}
			?>
			html += '</select></td>';
			html += '<td><select class="importance-select form-control" data-pair="' + table_no + '" data-item="' + new_table_row + '" name="' + table_no + '_' + new_table_row + '_importance" id="' + table_no + '_' + new_table_row + '_importance">';
				html += '<option value="0">Not Applicable</option>';
				html += '<option value="16">Very Important</option>';
				html += '<option value="8">Important</option>';
				html += '<option value="4">Somewhat Important</option>';
				html += '<option value="1">Least Important</option>';
			html += '</select></td>';
			
			html += '<td><select class="type-select form-control" data-pair="' + table_no + '" data-item="' + new_table_row + '" name="' + table_no + '_' + new_table_row + '_type" id="' + table_no + '_' + new_table_row + '_type" style="display: none;">';
				html += '<option value="1">Exact Match</option>';
				html += '<option value="4">Similar Wording</option>';
				html += '<option value="2">Comma Separated Lists</option>';
				html += '<option value="3">Strong Conditional</option>';
				html += '<option value="6">Weak Conditional</option>';
				html += '<option value="5">Match Within Range</option>';
			html += '</select><span id="' + table_no + '_' + new_table_row + '_type_empty">-</span></td>';
			html += '<td>';
				html += '<input type="text" name="' + table_no + '_' + new_table_row + '_conditional" id="' + table_no + '_' + new_table_row + '_conditional" style="display: none;" data-toggle="tooltip" data-placement="top" data-original-title="A good match occurs when both data fields match one of the terms described in a comma-separated list.">';
				html += '<span id="' + table_no + '_' + new_table_row + '_conditional_empty">-</span>';
			html += '</td></tr>';
			
			$('#add-row-' + table_no).before(html);
			$('#' + table_no + '_row_count').val(new_table_row + 1);
    	}
    
    
    	$( document ).ready(function() {
    		$('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
			$('.has-spinner').click(function() {
				$(this).toggleClass('active');
			});
    		
    		$('.identifier-select').change(function() {
    			$pair = $(this).data("pair");
    			$row = $(this).data("item");
    			$('.identifier-select-' + $pair).prop('checked', false);
    			$('#' + $pair + '_' + $row + '_identifier').prop('checked', true);
    		});
    		$('table').on("change", ".importance-select", function() {
    			$pair = $(this).data("pair");
    			$row = $(this).data("item");
    			if ($(this).val() === "0") {
    				$('#' + $pair + '_' + $row + '_conditional').hide();
    				$('#' + $pair + '_' + $row + '_type').hide();
    				$('#' + $pair + '_' + $row + '_conditional_empty').show();
    				$('#' + $pair + '_' + $row + '_type_empty').show();
    			} else {
    				$('#' + $pair + '_' + $row + '_type').show();
    				$('#' + $pair + '_' + $row + '_type_empty').hide();
    			}
    		});
    		$('table').on("change", ".type-select", function() {
    			$pair = $(this).data("pair");
    			$row = $(this).data("item");
    			if ($(this).val() == 3 || $(this).val() == 6) {
    				$('#' + $pair + '_' + $row + '_conditional').show().attr('placeholder', 'Term(s) to match');
    				$('#' + $pair + '_' + $row + '_conditional_empty').hide();
    			} else if ($(this).val() == 5) {
    				$('#' + $pair + '_' + $row + '_conditional').show().attr('placeholder', 'Match occurs within...');
    				$('#' + $pair + '_' + $row + '_conditional_empty').hide();
    			} else {
    				$('#' + $pair + '_' + $row + '_conditional').hide();
    				$('#' + $pair + '_' + $row + '_conditional_empty').show();
    			}
    		});
    		$('.btn-add-row').click(function() {
    			var table_no = $(this).data("tableno");
    			var table_rows = $('#' + table_no + '-table tr').length; 
    			var new_table_row = table_rows - 2;
				addRow(table_no, table_rows, new_table_row);
    		});
    		<?php
    			echo 'var $max_j = ' . count($relationships) . ';';
    			echo 'var $max_i = [];';
    			$j = 0;
    			foreach ($relationships as $relationship) {
    				echo '$max_i[' . $j . '] = ' . count($data_headers[$relationship[0]]) . ';';
    				$j++;
    			}
    		?>
    		for (var j = 0; j < $max_j; j++) {
    			for (var i = 0; i < $max_i[j]; i++) {
    				if ($('#' + j + '_' + i + '_importance').val() === "0") {
						$('#' + j + '_' + i + '_conditional').hide();
						$('#' + j + '_' + i + '_type').hide();
						$('#' + j + '_' + i + '_conditional_empty').show();
						$('#' + j + '_' + i + '_type_empty').show();
					} else {
						$('#' + j + '_' + i + '_type').show();
						$('#' + j + '_' + i + '_type_empty').hide();
						if ($('#' + j + '_' + i + '_type').val() == 3) {
							$('#' + j + '_' + i + '_conditional').show();
							$('#' + j + '_' + i + '_conditional_empty').hide();
						} else if ($('#' + j + '_' + i + '_type').val() == 5) {
							$('#' + j + '_' + i + '_conditional').show();
							$('#' + j + '_' + i + '_conditional_empty').hide();
						} else {
							$('#' + j + '_' + i + '_conditional').hide();
							$('#' + j + '_' + i + '_conditional_empty').show();
						}
					}
    			}
    		}
    		
    		// Restores the original data
    		<?php
				if (isset($_SESSION['relationships_data'])) {
					$relationships_data = $_SESSION['relationships_data'];
					for ($j = 0; $j < count($relationships_data); $j++) {
						$original_row_count = count($data_headers[$relationships[$j][0]]);
						$current_row_count = count($relationships_data[$j]["pair_data"]);
						if ($current_row_count > $original_row_count) {
							for ($a = 0; $a < ($current_row_count - $original_row_count); $a++) {
								echo 'addRow(' . $j . ',' . ($original_row_count + $a + 2) . ',' . ($original_row_count + $a) . ');';
							}
						}
					}
					for ($j = 0; $j < count($relationships_data); $j++) {
						// Go through each possible relationship
						$relationship_data = $relationships_data[$j];
						$relationship_pair_data = $relationship_data["pair_data"];
						$original_row_count = count($data_headers[$relationships[$j][0]]);
						
						for ($i = 0; $i < count($relationship_pair_data); $i++) {
							// Go through each row item
						
							$relationship_row_data = $relationship_pair_data[$i];
							$column_identifier = $relationship_row_data["column_identifier"];
							if ($column_identifier) {
								echo "$('#" . $j . "_" . $i . "_identifier').prop('checked', true);";
							}
							$column_title = $relationship_row_data["column_title"];
							if (($i > ($original_row_count - 1)) && ($column_title !== "")) {
								// Only use if the row we're working with is an added row
								echo "$('select[name=\"" . $j . "_" . $i . "_header\"] option[value=\"" . $column_title . "\"]').attr('selected',true);";
							}
							$column_match_with = $relationship_row_data["column_match_with"];
							if ($column_match_with !== "") {
								echo "$('select[name=\"" . $j . "_" . $i . "_match_with\"] option[value=\"" . $column_match_with . "\"]').attr('selected',true);";
							}
							$column_importance = $relationship_row_data["column_importance"];
							if ($column_importance > 0) {
								echo "$('#" . $j . "_" . $i . "_importance option[value=\"" . $column_importance . "\"]').attr('selected',true);";
								echo "$('#" . $j . "_" . $i . "_type').show();";
								echo "$('#" . $j . "_" . $i . "_type_empty').hide();";
							}
							$column_type = $relationship_row_data["column_type"];
							if ($column_type > 1) {
								echo "$('#" . $j . "_" . $i . "_type option[value=\"" . $column_type . "\"]').attr('selected',true);";
							}
							if ($column_type == 3 || $column_type == 6) {
								echo "$('#" . $j . "_" . $i . "_conditional').show();";
								echo "$('#" . $j . "_" . $i . "_conditional_empty').hide();";
							}
							$column_conditional = $relationship_row_data["column_conditional"];
							if ($column_conditional !== "") {
								echo "$('#" . $j . "_" . $i . "_conditional').val('" . $column_conditional . "');";
							}
						}
					}
				}
    		?>
		});
    </script>
	
</body>

</html>