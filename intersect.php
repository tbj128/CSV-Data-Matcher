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
	   	header("Location: " . $_SERVER['REQUEST_URI']);
	   	exit();
	} else {
		$relationships = $_SESSION['relationships'];
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
    <style>
		.table td {
			vertical-align:middle !important;
		}
	</style>
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
              		<blockquote>Similarity between corresponding rows in a dataset is determined by the similarity between certain columns. Indicate what and how columns are to be compared with each other below</blockquote>
				</div>
			</div>
				<form method="post" action="visualize.php">
					<?php
						$j = 0;
						foreach ($relationships as $relationship) {
					?>
					<div class="row pale-box">
						<h3><?php echo $relationship[0]; ?> matched with <?php echo $relationship[1]; ?></h3>
						<table class="table table-hover">
							<thead>
								<tr>
									<td><?php echo $relationship[0]; ?>  <a style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Data columns from the first dataset.">(?)</a></td>
									<td></td>
									<td><?php echo $relationship[1]; ?> <a style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Data columns from the second dataset.">(?)</a></td>
									<td>Importance <a style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="The relative importance of this data column match pair compared to other data column match pairs.">(?)</a></td>
									<td>Type <a style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Exact Match: Two data cells must be identical. Inexact Match: Two data cells are comma separated lists. Conditional Match: Two data cells must match up with third.">(?)</a></td>
									<td>Conditional <a style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Phrase used in conditional matches OR number used in range matches.">(?)</a></td>
								</tr>
							</thead>
							<tbody>
								<?php
										$i = 0;
										echo '<input type="hidden" name="' . $j . '_pair_a" value="' . $relationship[0] . '">';
										echo '<input type="hidden" name="' . $j . '_pair_b" value="' . $relationship[1] . '">';
										foreach ($data_headers[$relationship[0]] as $header) {
											echo '<tr><td><input type="hidden" name="' . $j . '_' . $i . '_header" value="' . $header . '">
												' . $header . '</td>';
											echo '<td style="color:#CECECE;"> pairs with </td>';
											echo '<td>
												<select class="form-control" name="' . $j . '_' . $i . '_match_with">';
												foreach ($data_headers[$relationship[1]] as $inner_header) {
													echo '<option value="' . $inner_header . '">' . $inner_header . '</option>';
												}
											echo '</select></td>';
											echo '<td>
												<select class="importance-select form-control" data-pair="' . $j . '" data-item="' . $i . '" name="' . $j . '_' . $i . '_importance" id="' . $j . '_' . $i . '_importance">
												  <option value="0">Don\'t Use to Match</option>
												  <option value="3">Important</option>
												  <option value="2">Somewhat Important</option>
												  <option value="1">Least Important</option>
												</select></td>';
											echo '<td>
												<select class="type-select form-control" data-pair="' . $j . '" data-item="' . $i . '" name="' . $j . '_' . $i . '_type" id="' . $j . '_' . $i . '_type" style="display:none;">
												  <option value="1">Exact Match</option>
												  <option value="4">Similar Text</option>
												  <option value="2">Comma Separated Lists</option>
												  <option value="3">Conditional Match</option>
												  <option value="5">Match Within Range</option>
												</select><span id="' . $j . '_' . $i . '_type_empty">-</span></td>';
											echo '<td><input type="text" name="' . $j . '_' . $i . '_conditional" id="' . $j . '_' . $i . '_conditional" style="display:none;"><span id="' . $j . '_' . $i . '_conditional_empty">-</span></td></tr>';
											$i++;
										}
								//	}
								?>
							</tbody>
						</table>
						<hr />
					</div>
					<?php
						$j++;
						}
					?>
					<button type="submit" class="btn btn-primary btn-lg btn-block">Save and Continue</button>
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
    	$( document ).ready(function() {
    		$('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
    		
    		$('.importance-select').change(function() {
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
    		$('.type-select').change(function() {
    			$pair = $(this).data("pair");
    			$row = $(this).data("item");
    			if ($(this).val() == 3) {
    				$('#' + $pair + '_' + $row + '_conditional').show().attr('placeholder', 'Term to match');
    				$('#' + $pair + '_' + $row + '_conditional_empty').hide();
    			} else if ($(this).val() == 5) {
    				$('#' + $pair + '_' + $row + '_conditional').show().attr('placeholder', 'Match occurs within...');
    				$('#' + $pair + '_' + $row + '_conditional_empty').hide();
    			} else {
    				$('#' + $pair + '_' + $row + '_conditional').hide();
    				$('#' + $pair + '_' + $row + '_conditional_empty').show();
    			}
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
		});
    </script>
	
</body>

</html>