<!DOCTYPE html>
<html>
<head>
	<title>Maps Bandung</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">
	<!-- Style -->
	<link href="<?php echo site_url(); ?>/assets/maps/style.css" rel="stylesheet">
	<!-- Bootstrap -->
	<link href="<?php echo site_url(); ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- pure -->
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css">
	<link href="<?php echo site_url(); ?>/assets/purelayout/css/layouts/blog.css" rel="stylesheet">
	<!-- font awesome -->
	<link rel="stylesheet" href="<?php echo site_url(); ?>/assets/font-awesome/css/font-awesome.min.css">
</head>
<body>
	<div class='navbar navbar-default navbar-static-top'>
		<div class='container-fluid'>
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class='navbar-brand' href='index.html'>BANDUNG EMERGENCY SYSTEM</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?php echo site_url(); ?>">Map</a></li>
					<li><a href="<?php echo site_url(); ?>home/construct">Construct</a></li>
					<li class='active'><a href="<?php echo site_url(); ?>home/debug">Debug</a></li>
					<li><a href="about.html">About</a></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	<div class='container-fluid'>
		<div class="row">
			<div class='col-md-11 center-block' style="float:none">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h9><b>Ambulance Border Point</b></h9>
					</div>
					<div class="panel-body" style="font-size:14px;">
							<div>
								<table class="table table-striped table-hover" style="font-size:12px">
									  <thead>
									    <tr class="default">
									      <th>Border Id</th>
									      <th>Generator1 Id</th>
									      <th>Distance G1-B (km)</th>
									      <th>Generator2 Id</th>
									      <th>Distance G2-B (km)</th>
									    </tr>
									  </thead>
									  <tbody>
									  	<?php foreach($border_comparison_ambulance As $row) : ?>
										    <tr>
										      <td><?php echo $row['id_border']; ?></td>
										      <td><?php echo $row['id_generator1']; ?></td>
										      <td><?php echo $row['dist_generator1']; ?></td>
										      <td><?php echo $row['id_generator2']; ?></td>
										      <td><?php echo $row['dist_generator2']; ?></td>
										    </tr>
										<?php endforeach; ?>
									  </tbody>
								</table>
							</div>
						
					</div>
				</div>

				<div class="panel panel-info">
					<div class="panel-heading">
						<h9><b>Police Border Point</b></h9>
					</div>
					<div class="panel-body" style="font-size:14px;">
							<div>
								
								<table class="table table-striped table-hover" style="font-size:12px">
									  <thead>
									    <tr class="default">
									      <th>Border Id</th>
									      <th>Generator1 Id</th>
									      <th>Distance(G1, B)</th>
									      <th>Generator2 Id</th>
									      <th>Distance(G2, B)</th>
									    </tr>
									  </thead>
									  <tbody>
									  	<?php foreach($border_comparison_police As $row) : ?>
										    <tr>
										      <td><?php echo $row['id_border']; ?></td>
										      <td><?php echo $row['id_generator1']; ?></td>
										      <td><?php echo $row['dist_generator1']; ?></td>
										      <td><?php echo $row['id_generator2']; ?></td>
										      <td><?php echo $row['dist_generator2']; ?></td>
										    </tr>
										<?php endforeach; ?>
									  </tbody>
								</table>
							</div>
						
					</div>
				</div>

				<div class="panel panel-warning">
					<div class="panel-heading">
						<h9><b>Fire Brigade Border Point</b></h9>
					</div>
					<div class="panel-body" style="font-size:14px;">
							<div>
								<table class="table table-striped table-hover" style="font-size:12px">
									  <thead>
									    <tr class="default">
									      <th>Border Id</th>
									      <th>Generator1 Id</th>
									      <th>Distance(G1, B)</th>
									      <th>Generator2 Id</th>
									      <th>Distance(G2, B)</th>
									    </tr>
									  </thead>
									  <tbody>
									  	<?php foreach($border_comparison_fire_brigade As $row) : ?>
										    <tr>
										      <td><?php echo $row['id_border']; ?></td>
										      <td><?php echo $row['id_generator1']; ?></td>
										      <td><?php echo $row['dist_generator1']; ?></td>
										      <td><?php echo $row['id_generator2']; ?></td>
										      <td><?php echo $row['dist_generator2']; ?></td>
										    </tr>
										<?php endforeach; ?>
									  </tbody>
								</table>
							</div>
						
					</div>
				</div>
			</div>
		</div>

		<p class='pull-right'>
			<a href='http://facebook.com/afachreezal'>Ashari Fachrizal</a> - <a href='http://telkomuniversity.ac.id'> Telkom University &nbsp;&nbsp;</a>
		</p>
	</div>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9MDhON5KSnuzHJm4Yl0js0YDEfEmer1A"></script>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="<?php echo site_url(); ?>/assets/maps/Mapster.js"></script>
	<script src="<?php echo site_url(); ?>/assets/maps/map-options.js"></script>
</body>
</html>