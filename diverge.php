<?php
	// Project Maple
	// Created for UBC CS - Trimentoring
	// @date: Feb 2, 2014
	// @author: Tom Jin
	
	include_once 'config.php';
	include_once 'functions.php';
	
	session_start();
	$curr_page = 1;
	
	$csv_files = array();
	if (isset($_POST['upload_items'])) {
		$uploaded_items = explode(",", $_POST['upload_items']);
		foreach ($uploaded_items as $uploaded_item) {
			$csv_files[] = $uploaded_item;
		}
	}
	
	$data_headers = array();
	$data = array();
	foreach ($csv_files as $csv_file) {
		$data_headers[$csv_file] = csv_get_headers($csv_file);
		$data[$csv_file] = csv_get_array($csv_file);
	}
	
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
					<div>
						<blockquote>You've uploaded <?php echo count($data); ?> data files. Select the matching relationship you want to generate below.</blockquote>
					</div>
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
						
						<button type="submit" class="btn btn-primary btn-lg btn-block has-spinner">
							<span class="spinner"><i class="fa fa-spin fa-refresh"></i></span>
							&nbsp;&nbsp;Save and Continue
						</button>
						
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