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

    <title><?php echo $APP_NAME; ?> - Help</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Google Web Font -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!-- Add custom CSS here -->
    <link href="css/landing-page.css" rel="stylesheet">
	<link rel="stylesheet" href="css/jquery.fileupload-ui.css">

</head>

<body style="background:#FFF;">

    <div class="content-section-a" style="background:#FFF;">
        <div class="container" style="font-size:18px;">
			<div>
				<h1>Help</h1>
				<ul>
					<li><strong>Upload Data</strong>
					<ul>
						<li>This system accepts .CSV (comma separated data files)&nbsp;
						<ul>
							<li>The first row must contain the column titles</li>
							<li>Each subsequent row must represent a different item (eg. different people)</li>
						</ul>
						</li>
						<li>You can upload multiple files at a time but you must upload more than two data files in order to find the optimal matches between them</li>
					</ul>
					</li>
					<li><strong>Define Relationship</strong>
					<ul>
						<li>To find optimal matches, you have to specify which datasets you want to match together.&nbsp;</li>
						<li>For example, say you&#39;ve uploaded X, Y, and Z
						<ul>
							<li>You can create up to two match groups from the three different combinations possible: X with Y, X with Z, or Y with Z</li>
						</ul>
						</li>
					</ul>
					</li>
					<li><strong>Define Matching</strong>
					<ul>
						<li>Here you will define which columns in each dataset will be used to determine the best match</li>
						<li>For each match group, you&#39;ll see a table that&#39;ll have several columns:
						<ul>
							<li>Identifier: You can only mark one column title in each match group as the identifier. The identifier column will then be used in the subsequent pages to identify items in each match group
							<ul>
								<li>For example, say you had a columns in your CSV file called &#39;Name&#39; and &#39;Age&#39;. You would mark &#39;Name&#39; as the identifier so that later on, the system can tell you that Agnes was matched with Margo.</li>
							</ul>
							</li>
							<li>&lt;dataset A&gt;.csv: These are the column titles associated with dataset A</li>
							<li>&lt;dataset B&gt;.csv: These are the column titles associated with dataset B
							<ul>
								<li>For each column title under dataset A, find their corresponding column title under dataset B.</li>
								<li>For example, if one column in dataset A is &#39;Name&#39;, this column would be logically matched with the column &#39;Name&#39; in dataset B.&nbsp;</li>
							</ul>
							</li>
							<li>Importance: For each matched column title pair, how important is it in the matching process?
							<ul>
								<li>For example, if you have &#39;Name&#39; of dataset A matched with &#39;Name&#39; of dataset B, you would mark this pair as &#39;Do Not Use&#39; as you would generally not want to match two people by the similarity of their names</li>
								<li>The more important you weight a column title pair, the higher similarity score they will receive when a set of matching criteria is reached&nbsp;</li>
							</ul>
							</li>
							<li>Match Type: Once you&#39;ve set the importance of a column title pair, you will get to choose the match type.&nbsp;
							<ul>
								<li>Exact Match: A high similarity score will be given to two data column cells which are identical, a low score otherwise.
								<ul>
									<li>For example, &quot;Anna&quot; and &quot;Anna&quot; will have a score of 100, &quot;Anna&quot; and &quot;Elsa&quot; will have a score of 0.</li>
								</ul>
								</li>
								<li>Similar Wording: The more similar the text are in two data columns cells, the higher the similarity score.
								<ul>
									<li>For example, &quot;Ice Queen&quot; and &quot;Icy Queen&quot; will have a higher similarity score than &quot;Ice Cream&quot;</li>
								</ul>
								</li>
								<li>Comma Separated Lists: Using the comma as the separator, the system will treat the text in the two data column cells as a list of items. The more words in common between the two lists, the higher the similarity score.
								<ul>
									<li>eg. &quot;snow, ice, castle&quot; and &quot;castle, snow, ice&quot; will have a high similarity score</li>
								</ul>
								</li>
								<li>Conditional Match: A high similarity score will only be assigned if the text in both data column cell matches with one of the terms in a comma-separated list you describe under the &#39;Conditional&#39; column
								<ul>
									<li>eg. If you wanted to only match people based on which days they could come to a dance, you could have a dataset with columns that indicate whether a given person can come on a day or not. A high similarity score would only be useful if two people could come on the same day (eg. both people indicated that yes, they can come on a Friday)</li>
								</ul>
								</li>
								<li>Match within Range: A high similarity score will be given if the two numbers in the two data column cells are within a given range described under the &#39;Conditional&#39; column
								<ul>
									<li>eg. If you only wanted to match people at a dance if they were within three years of each other, you could set the Match Type to be &#39;Match within Range&#39; on the &#39;Age&#39; data columns.</li>
								</ul>
								</li>
							</ul>
							</li>
							<li>Conditional: Only used when &#39;Match Type&#39; is set to &#39;Conditional Match&#39; or &#39;Match within Range&#39;</li>
						</ul>
						</li>
					</ul>
					</li>
					<li><strong>Visualize</strong>
					<ul>
						<li>A grid with the similarity scores between each row in each dataset will be generated. The higher the score, the more closely matched the data pair are (and the more green data pair becomes).</li>
						<li>If you&#39;re not satisfied with the similarity scores generated, click the back button to tweak the matching criteria (eg. assign a greater importance to two data columns)</li>
					</ul>
					</li>
					<li><strong>Download</strong> 
					<ul>
						<li>Generate the best matches based on the similarity scores of each data pair. Download a complete matched dataset in CSV format.</li>
					</ul>
					</li>	
				</ul>
        	</div>
        </div>
        <!-- /.container -->

    </div>

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>