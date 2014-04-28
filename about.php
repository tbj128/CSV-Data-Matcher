<?php
	// Project Maple
	// Created for UBC CS - Trimentoring
	// @date: April 21, 2014
	// @author: Tom Jin
	
	include_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $APP_NAME; ?> - About</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Google Web Font -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!-- Add custom CSS here -->
    <link href="css/landing-page.css" rel="stylesheet">
	<link rel="stylesheet" href="css/jquery.fileupload-ui.css">

</head>

<body>

    <div class="content-section-a">
        <div class="container">
			<div>
				<div class="page-header">
					<h1 id="navbar">CSV Data Matching.</h1>
					<blockquote>
					  <p>UBC CS Tri-Mentoring Program <br />
					  Built in beautiful Vancouver, Canada.</p>
					</blockquote>
				</div>
				<div class="well">
					<a href="help.php"><h4>Help Contents</h4></a>
				</div>
        	</div>
        </div>
        <!-- /.container -->

    </div>

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>