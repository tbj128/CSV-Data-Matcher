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

    <div class="content-section-a">
        <div class="container">
			<div style="margin-top:58px;">
				<div class="page-header">
					<h1 id="navbar">Pair Up.</h1>
					<h3>Upload, visualize, and match CSV datasets.</h3>
				</div>
				<form id="uploadForm" method="post" action="diverge.php">
				<input type="submit" id="uploadSubmit" style="display:none;">
				<input type="hidden" name="upload_items" id="uploadItems" value="">
				<div class="row">
					<div id="uploadButton" class="col-lg-3 upload-card img-rounded">
						<div style="display:table-cell;vertical-align: middle;">
							<div>
								<img src="img/ic-upload.png">
								<span style="color:#FFF;font-size:22px;margin:5px 0 0 10px;vertical-align:middle;">
									Select CSV Data
								</span>
							</div>
						</div>
					</div>
					
					<div class="col-lg-8 upload-files img-rounded">
						<div id="uploadWrap" style="display: table-cell; vertical-align: middle;">
							<div id="uploadBox">
								<p id="noItemsMsg" style="text-align:center;font-size:14px;">No files uploaded yet.</p>
							</div>
						</div>
					</div>
				</div><!-- /.row -->

				<div id="continueButton" class="row" style="display:none;margin-top:32px;">
					<button type="submit" class="btn btn-primary btn-lg btn-block has-spinner">
						<i class="fa fa-check"></i>
						&nbsp;&nbsp;Continue
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
    <script src="js/SimpleAjaxUploader.js"></script>
    
    
    <script>
    	$( document ).ready(function() {
    		var uploader = new ss.SimpleUpload({
				button: 'uploadButton',
				url: 'uploadHandler.php', // server side handler
				progressUrl: 'uploader/uploadProgress.php', // enables cross-browser progress support (more info below)
				responseType: 'json',
				name: 'uploadfile',
				multiple: true,
				allowedExtensions: ['csv'],
				hoverClass: 'ui-state-hover',
				focusClass: 'ui-state-focus',
				disabledClass: 'ui-state-disabled', 
				onSubmit: function(filename, ext) {            
				   var prog = document.createElement('div'),
					   outer = document.createElement('div'),
					   bar = document.createElement('div'),
					   size = document.createElement('div'),
					   wrap = document.getElementById('uploadBox'),
					   errBox = document.getElementById('noItemsMsg');
   
					prog.className = 'prog';
					size.className = 'size';
					outer.className = 'progress progress-striped active';
					bar.className = 'progress-bar progress-bar-success';

					outer.appendChild(bar);
					prog.innerHTML = '<span style="vertical-align:middle;">'+filename+' - </span>';
					prog.appendChild(size);
					prog.appendChild(outer);
					wrap.appendChild(prog); // 'wrap' is an element on the page

					this.setProgressBar(bar);
					this.setProgressContainer(prog);
					this.setFileSizeBox(size);      

					errBox.innerHTML = '';
				  },	
				   // Do something after finishing the upload
				   // Note that the progress bar will be automatically removed upon completion because everything 
				   // is encased in the "wrapper", which was designated to be removed with setProgressContainer() 
				  onComplete:   function(filename, response) {
						if (!response) {
							alert(filename + ' upload failed');
							return false;
						} else {
							var uploadedItems = $('#uploadItems').val();
							if (uploadedItems != '') {
								uploadedItems = uploadedItems + ',' + response.file;
							} else {
								uploadedItems = response.file;
							}
							$('#uploadItems').val(uploadedItems);
							$('#uploadBox').append('<div class="upload-card-small img-rounded">' + response.file + '</div>');
							
							if (uploadedItems.split(',').length >= 2) {
								$('#noItemsMsg').hide();
								$('#continueButton').show();
							} else if (uploadedItems.split(',').length == 1) {
								$('#noItemsMsg').html('Upload at least one more item');
							}
						}
					}
			});
			
			$('#continueButton').click(function() {
				$("#uploadSubmit").click();
			});
    	});
    </script>
</body>
</html>