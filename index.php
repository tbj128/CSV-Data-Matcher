<?php
	// Project Maple
	// Created for UBC CS - Trimentoring
	// @date: Feb 2, 2014
	// @author: Tom Jin
	
	include_once 'config.php';
	$curr_page = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $APP_NAME; ?></title>

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
	<!--
	<div id="upload-msg" class="alert alert-success alert-dismissable" style="display:none;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>Success</strong> File successfully uploaded.
	</div>
	
	<div id="upload-status" class="well" style="display:none;">
		<h3>Uploading File</h3>
		<h4><small>Please wait, this may take a few minutes.</small></h4>
		<div id="progress" class="progress progress-success progress-striped">
			<div class="bar"></div>
		</div>
		<button type="reset" class="btn btn-warning cancel">
			<i class="icon-ban-circle icon-white"></i>
			<span>Cancel upload</span>
		</button>
	</div>-->


	<?php
		include "page_header.php";
	?>

    <div class="intro-header">

        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <h1>Tri-Mentoring Matchup</h1>
                        <h3>Begin by uploading the documents below</h3>
                        <hr class="intro-divider">
						<div class="well" style="opacity:0.9;width:260px;margin:10px auto;color:#222;">
							<form method="post" action="intersect.php">
								<div class="form-group">
									<label>Student (Junior) Data</label><br />
									<input class="form-control" type="hidden" name="junior_path" id="junior_path" value="">
									<input class="form-control" type="text" name="junior_path_desc" id="junior_path_desc" value="" style="display:none;" disabled>
									<span class="btn btn-success fileinput-button">
										<i class="fa fa-upload"></i>
										<span>&nbsp;&nbsp;Select CSV...</span>
										<!-- The file input field used as target for the file upload widget -->
										<input id="fileupload_junior" type="file" name="files[]">
									</span>
								</div>
								<hr />
								<div class="form-group">
									<label>Student (Senior) Data</label><br />
									<input class="form-control" type="hidden" name="senior_path" id="senior_path" value="">
									<input class="form-control" type="text" name="senior_path_desc" id="senior_path_desc" value="" style="display:none;" disabled>
									<span class="btn btn-success fileinput-button">
										<i class="fa fa-upload"></i>
										<span>&nbsp;&nbsp;Select CSV...</span>
										<!-- The file input field used as target for the file upload widget -->
										<input id="fileupload_senior" type="file" name="files[]">
									</span>
								</div>
								<hr />
								<div class="form-group">
									<label>Mentor Data</label><br />
									<input class="form-control" type="hidden" name="mentor_path" id="mentor_path" value="">
									<input class="form-control" type="text" name="mentor_path_desc" id="mentor_path_desc" value="" style="display:none;" disabled>
									<span class="btn btn-success fileinput-button">
										<i class="fa fa-upload"></i>
										<span>&nbsp;&nbsp;Select CSV...</span>
										<!-- The file input field used as target for the file upload widget -->
										<input id="fileupload_mentor" type="file" name="files[]">
									</span>
								</div>
								<hr />
								<p><input type="submit" class="btn btn-primary" value="Continue"></p>
							</form>
						</div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.intro-header -->


	<?php
		include "page_footer.php";
	?>


    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
	
	<script src="js/fileuploader/jquery.ui.widget.js"></script>
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<script src="js/fileuploader/jquery.iframe-transport.js"></script>
	<!-- The basic File Upload plugin -->
	<script src="js/fileuploader/jquery.fileupload.js"></script>
	<script>
		$(document).ready(function() {
			// File Upload Plugin
			var url = 'upload/';
			$('#fileupload_junior').fileupload({
				url: url,
				dataType: 'json',
				add: function (e, data) {
					$('#junior_path').val(data.files[0].name);
					$('#junior_path_desc').val(data.files[0].name);
					console.log(data.files[0].name);
					data.submit();
				},
				complete: function (e, data) {
					console.log(e);
					console.log(data);
					$('#fileupload_junior').hide();
					$("#junior_path_desc").show();
				},
				progressall: function (e, data) {
					// var progress = parseInt(data.loaded / data.total * 100, 10);
					// $('#progress .bar').css(
						// 'width',
						// progress + '%'
					// );
				}
			});
			$('#fileupload_senior').fileupload({
				url: url,
				dataType: 'json',
				add: function (e, data) {
					$('#senior_path').val(data.files[0].name);
					$('#senior_path_desc').val(data.files[0].name);
					console.log(data.files[0].name);
					data.submit();
				},
				complete: function (e, data) {
					console.log(e);
					console.log(data);
					$('.fileupload_senior').hide();
					$("#senior_path_desc").show();
				},
				progressall: function (e, data) {
					// var progress = parseInt(data.loaded / data.total * 100, 10);
					// $('#progress .bar').css(
						// 'width',
						// progress + '%'
					// );
				}
			});
			$('#fileupload_mentor').fileupload({
				url: url,
				dataType: 'json',
				add: function (e, data) {
					$('#mentor_path').val(data.files[0].name);
					$('#mentor_path_desc').val(data.files[0].name);
					console.log(data.files[0].name);
					data.submit();
				},
				complete: function (e, data) {
					console.log(e);
					console.log(data);
					$('#fileupload_mentor').hide();
					$("#mentor_path_desc").show();
				},
				progressall: function (e, data) {
					// var progress = parseInt(data.loaded / data.total * 100, 10);
					// $('#progress .bar').css(
						// 'width',
						// progress + '%'
					// );
				}
			});
			
			
			
			$('#fileupload_junior').bind('fileuploadstart', function (e) {
				//$('#upload-status').show();
			});
			$('#fileupload_senior').bind('fileuploadstart', function (e) {
				//$('#upload-status').show();
			});
			$('#fileupload_mentor').bind('fileuploadstart', function (e) {
				//$('#upload-status').show();
			});
			
			$('button.cancel').click(function () {
				//$('#upload-status').hide();
			});
		
		
		});

	</script>

</body>

</html>
