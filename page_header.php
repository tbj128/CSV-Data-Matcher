<?php
	$t_0 = "";
	$t_1 = "";
	$t_2 = "";
	$t_3 = "";
	$t_4 = "";
	
	$l_0 = "";
	$l_1 = "";
	$l_2 = "";
	$l_3 = "";
	$l_4 = "";
	if ($curr_page == 0) {
		$t_0 .= " class='active'";	
		$t_1 = " class='disabled'";
		$t_2 = " class='disabled'";
		$t_3 = " class='disabled'";
		$t_4 = " class='disabled'";
		$l_0 = "index.php";
		$l_1 = "javascript:void(0);";
		$l_2 = "javascript:void(0);";
		$l_3 = "javascript:void(0);";
		$l_4 = "javascript:void(0);";
	} else if ($curr_page == 1) {
		$t_0 .= " class='disabled'";	
		$t_1 = " class='active'";
		$t_2 = " class='disabled'";
		$t_3 = " class='disabled'";
		$t_4 = " class='disabled'";
		$l_0 = "javascript:void(0);";
		$l_1 = "diverge.php";
		$l_2 = "javascript:void(0);";
		$l_3 = "javascript:void(0);";
		$l_4 = "javascript:void(0);";
	} else if ($curr_page == 2) {
		$t_0 .= " class='disabled'";	
		$t_1 = " class='disabled'";
		$t_2 = " class='active'";
		$t_3 = " class='disabled'";
		$t_4 = " class='disabled'";
		$l_0 = "javascript:void(0);";
		$l_1 = "javascript:void(0);";
		$l_2 = "intersect.php";
		$l_3 = "javascript:void(0);";
		$l_4 = "javascript:void(0);";
	} else if ($curr_page == 3) {
		$t_0 .= " class='disabled'";	
		$t_1 = " class='disabled'";
		$t_2 = " class='disabled'";
		$t_3 = " class='active'";
		$t_4 = " class='disabled'";
		$l_0 = "javascript:void(0);";
		$l_1 = "javascript:void(0);";
		$l_2 = "javascript:void(0);";
		$l_3 = "visualize.php";
		$l_4 = "javascript:void(0);";
	} else if ($curr_page == 4) {
		$t_0 .= " class='disabled'";	
		$t_1 = " class='disabled'";
		$t_2 = " class='disabled'";
		$t_3 = " class='disabled'";
		$t_4 = " class='active'";
		$l_0 = "javascript:void(0);";
		$l_1 = "javascript:void(0);";
		$l_2 = "javascript:void(0);";
		$l_3 = "javascript:void(0);";
		$l_4 = "match.php";
	}
	
?>
			
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="row nav-progress">
			<ul class="nav nav-pills">
				<li <?php echo $t_0; ?>><a href="<?php echo $l_0; ?>">Upload Data</a></li>
				<li <?php echo $t_1; ?>><a href="<?php echo $l_1; ?>">Define Relationship</a></li>
				<li <?php echo $t_2; ?>><a href="<?php echo $l_2; ?>">Define Matching</a></li>
				<li <?php echo $t_3; ?>><a href="<?php echo $l_3; ?>">Visualize</a></li>
				<li <?php echo $t_4; ?>><a href="<?php echo $l_4; ?>">Match</a></li>
			</ul>
		</div>
	</div>
	<!-- /.container -->
</nav>