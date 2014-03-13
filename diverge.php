<?php
	// Project Maple
	// Created for UBC CS - Trimentoring
	// @date: Feb 2, 2014
	// @author: Tom Jin
	
	include_once 'config.php';
	include_once 'functions.php';
	
	session_start();
	$curr_page = 1;
	
	$junior_name = 'Junior Data.csv';
	$senior_name = 'Senior Data.csv';
	$mentor_name = 'Mentor Data.csv';
	
	// Moves the files to the CSV folder if not moved already
	if (isset($_POST['mentor_path'])
		&& isset($_POST['junior_path'])
		&& isset($_POST['senior_path'])) {

		$mentor_name = $_POST['mentor_path'];
		$junior_name = $_POST['junior_path'];
		$senior_name = $_POST['senior_path'];
		
		rename('upload/files/' . $mentor_name, 'csv/' . $mentor_name);
		rename('upload/files/' . $junior_name, 'csv/' . $junior_name);
		rename('upload/files/' . $senior_name, 'csv/' . $senior_name);
	}
	
	
	// Dump the CSV files into an array
	$junior_headers = csv_get_headers($junior_name);
	$senior_headers = csv_get_headers($senior_name);
	$mentor_headers = csv_get_headers($mentor_name);
	
	$junior_data = csv_get_array($junior_name);
	$senior_data = csv_get_array($senior_name);
	$mentor_data = csv_get_array($mentor_name);
	
	// Are these CSV files usable? 
	if ($junior_data === FALSE 
		|| $senior_data === FALSE 
		|| $mentor_data === FALSE) {
		header("Location: index.php?err");
	}
	
	$data_headers = array();
	$data_headers[$junior_name] = $junior_headers;
	$data_headers[$senior_name] = $senior_headers;
	$data_headers[$mentor_name] = $mentor_headers;
	
	
	$data = array();
	$data[$junior_name] = $junior_data;
	$data[$senior_name] = $senior_data;
	$data[$mentor_name] = $mentor_data;
	
	$_SESSION['headers'] = $data_headers;
	$_SESSION['data'] = $data;
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
		
			<div style="margin-top:50px;">
				<div class="row">
					<h2>Specify Match Groups</h2>
					<h4>You've uploaded <?php echo count($data); ?> data files. Which match group pairs do you want to generate between them?</h4>
					<br />
				</div>
				<form method="post" action="intersect.php">
					<div class="row">
					<table class="table" style="width:600px;">
					<?php
						for ($i = 0; $i < (count($data) - 1); $i++) {
							echo '
							<tr>
								<td>
									<select class="form-control" name="match_' . $i . '_a">';
									foreach ($data as $key => $value) {
										echo '<option value="' . $key . '">' . $key . '</option>';
									}
							echo '	</select>
								</td>
								<td style="width:200px;text-align:center;vertical-align:middle;">
									matches with
								</td>
								<td>
									<select class="form-control" name="match_' . $i . '_b">';
									foreach ($data as $key => $value) {
										echo '<option value="' . $key . '">' . $key . '</option>';
									}
							echo'	</select>
								</td>
							</tr>';
						}
					?>	
							
						</table>
						<input type="hidden" name="mentor_path" value="<?php echo $mentor_name; ?>">
						<input type="hidden" name="senior_path" value="<?php echo $senior_name; ?>">
						<input type="hidden" name="junior_path" value="<?php echo $junior_name; ?>">
						<hr />
						<p><input type="submit" class="btn btn-primary" value="Continue"></p>
					</div>
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
	
</body>

</html>