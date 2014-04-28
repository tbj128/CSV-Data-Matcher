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
	$curr_page = 4;


	// Data Variables
	$data_headers = $_SESSION['headers'];
	$data = $_SESSION['data'];
	$relationships = $_SESSION['relationships'];
	$matrices = $_SESSION['matrices'];
	$relationships_data = $_SESSION['relationships_data'];
	$identifier_data = $_SESSION['identifier_data'];
	$output_items = array();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $APP_NAME; ?> - Match</title>

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
					<h1 id="navbar">Download Matched Data</h1>
					<div style="width:800px;">
						<blockquote>The following matches were calculated based off of the input criteria in previous steps. Click back to go edit that criteria. 
						</blockquote>
					</div>
				</div>
				<?php
					$a = 0;
					foreach ($relationships_data as $relationship_data) {
						$out = array();
				?>
				<h3><?php echo $relationship_data["pair_b"]; ?> matched with <?php echo $relationship_data["pair_a"]; ?></h3>
				<a href="download.php?r=<?php echo $a; ?>" class="btn btn-primary">
					<i class="fa fa-download"></i>&nbsp;&nbsp;
					Download
				</a>
				<table class="table table-hover">
					<thead>
						<tr>
							<td><?php echo $relationship_data["pair_b"]; ?></td>
							<td><?php echo $relationship_data["pair_a"]; ?></td>
							<td>Match Score</td>
						</tr>
					</thead>
					<tbody>
				<?php
						$dataset_name_a = $relationship_data["pair_a"];
						$dataset_name_b = $relationship_data["pair_b"];
			
						// Prepare the output titles (indicates the dataset)
						$out_row_headers = array();
						$out_row_headers[] = $dataset_name_a;
						for ($i = 0; $i < (count($data_headers[$dataset_name_a]) - 1); $i++) {
							$out_row_headers[] = "";
						}
						$out_row_headers[] = "";
						$out_row_headers[] = $dataset_name_b;
						for ($i = 0; $i < (count($data_headers[$dataset_name_b]) - 1); $i++) {
							$out_row_headers[] = "";
						}
						$out[] = $out_row_headers;
			
						// Prepare the output headers
						$out_row_headers = array();
						foreach ($data_headers[$dataset_name_a] as $header) {
							$out_row_headers[] = $header;
						}
						$out_row_headers[] = "";
						foreach ($data_headers[$dataset_name_b] as $header) {
							$out_row_headers[] = $header;
						}
						$out[] = $out_row_headers;
				
				
						$m = new Munkres();
						$inverted_matrix = make_cost_matrix($matrices[$a]);
						$indexes = $m->compute($inverted_matrix);
						
						// Initializes arrays that will indicate the unmatched items
						$unmatched_x = array_fill(0, count($data[$dataset_name_b]), 0);
						$unmatched_y = array_fill(0, count($data[$dataset_name_a]), 0);

						foreach ($indexes as $rc) {
							$x = $rc[0];
							$y = $rc[1];
							$cost = $matrices[$a][$x][$y];
							// Marks the item as having been processed
							$unmatched_x[$x] = 1;
							$unmatched_y[$y] = 1;
							
							// Where $identifier_data[$dataset_name_b] is the column indicated as the identifier column
							$item_1 = reset($data[$dataset_name_b][$x]); 
							$item_2 = reset($data[$dataset_name_a][$y]);
							if (in_array($dataset_name_b, $identifier_data)) {
								$item_1 = $data[$dataset_name_b][$x][$identifier_data[$dataset_name_b]];
							}
							if (in_array($dataset_name_a, $identifier_data)) {
								$item_2 = $data[$dataset_name_a][$y][$identifier_data[$dataset_name_a]];
							}
							
							echo '<tr>';
							echo '<td>' . $item_1 . '</td><td>' . $item_2 . '</td><td>' . $cost . '</td>';
							echo '</tr>';
							
							// Preps the full CSV file content
							$out_row_item = array();
							foreach ($data[$dataset_name_a][$y] as $field_name => $field_value) {
								$out_row_item[] = $field_value;
							}
							$out_row_item[] = "";
							foreach ($data[$dataset_name_b][$x] as $field_name => $field_value) {
								$out_row_item[] = $field_value;
							}
							$out[] = $out_row_item;
						}
						
						for ($i = 0; $i < count($unmatched_x); $i++) {
							if ($unmatched_x[$i] == 0) {
								$item = reset($data[$dataset_name_b][$i]);
								if (in_array($dataset_name_b, $identifier_data)) {
									$item = $data[$dataset_name_b][$i][$identifier_data[$dataset_name_b]];
								}
								echo '<tr>';
								echo '<td>' . $item . '</td><td>-</td><td>Not Matched</td>';
								echo '</tr>';
							
								// Lonely item that we need to remember to add to our list
								$out_row_item = array();
								foreach ($data[$dataset_name_a][0] as $field_name => $field_value) {
									$out_row_item[] = "";
								}
								$out_row_item[] = "";
								foreach ($data[$dataset_name_b][$i] as $field_name => $field_value) {
									$out_row_item[] = $field_value;
								}
								$out[] = $out_row_item;
							}
						}
						for ($i = 0; $i < count($unmatched_y); $i++) {
							if ($unmatched_y[$i] == 0) {
								$item = reset($data[$dataset_name_a][$i]);
								if (in_array($dataset_name_a, $identifier_data)) {
									$item = $data[$dataset_name_a][$i][$identifier_data[$dataset_name_a]];
								}
								echo '<tr>';
								echo '<td>-</td><td>' . $item . '</td><td>Not Matched</td>';
								echo '</tr>';
							
								// Lonely item that we need to remember to add to our list
								$out_row_item = array();
								foreach ($data[$dataset_name_a][$i] as $field_name => $field_value) {
									$out_row_item[] = $field_value;
								}
								$out_row_item[] = "";
								foreach ($data[$dataset_name_b][0] as $field_name => $field_value) {
									$out_row_item[] = "";
								}
								$out[] = $out_row_item;
							}
						}
					?>
				
					</tbody>
				</table>
				<br />
				<?php	
						$output_items[] = $out;
						$a++;
					}
					
					// Saves it to the session
					$_SESSION['csv_output'] = $output_items;
				?>
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
    	});
    </script>

	</body>
</html>