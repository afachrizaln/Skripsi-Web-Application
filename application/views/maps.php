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
					<li class='active'><a href="<?php echo site_url(); ?>">Map</a></li>
					<li><a href="<?php echo site_url(); ?>home/construct">Construct</a></li>
					<li><a href="<?php echo site_url(); ?>home/debug">Debug</a></li>
					<li><a href="about.html">About</a></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	<div class='container-fluid'>
		<?php if(isset($notif)) : ?>
			<?php echo $notif; ?>
		<?php endif; ?>
		<div class='row'>
			<div class='col-md-4'>
				<?php echo form_open('home/search/'); ?>
				<div class='panel panel-default'>
					<div class="panel-heading">
						<h9><b>SEARCH</b></h7>
						</div>
						<div class="panel-body" style="font-size:14px">
							<p style="padding:0; margin:0;">Query Point Coordinate <small>(<a id='find_me' href='#'>find me</a>)</small></p>
							<p>
								<?php if(isset($query_coords)) : ?>
									<input class='form-control' required name='query_coords' style="height:36px; font-size:14px" placeholder='Enter latitude and longitude, e.g -6.9417 107.5967' type='text' value='<?php echo $query_coords; ?>' />
								<?php else : ?>	
									<input class='form-control' required name='query_coords' style="height:36px; font-size:14px" placeholder='Enter latitude and longitude, e.g -6.9417 107.5967' type='text' />
								<?php endif; ?>
							</p>
							<div class="form-group">
								<p class="col-lg-5" style="padding:0; margin:0;">kth Ambulance Unit</p>
								<div class="col-lg-7">
									<?php if(isset($k_ambulance)) : ?>
										<select class="form-control" style="height:28px;font-size:11px;padding-top:0;padding-bottom:0;" name="k_ambulance">
											<option value=0>k = 0</option>
											<?php foreach($this->webmodel->select_generator_where_id_type(1) AS $key => $row) : ?>
												<?php if($k_ambulance == $key+1) : ?>
													<option value=<?php echo $key+1?> selected>k = <?php echo $key+1 ?></option>
												<?php else : ?>
													<option value=<?php echo $key+1?>>k = <?php echo $key+1 ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										</select>
									<?php else : ?>	
										<select class="form-control" style="height:28px;font-size:11px;padding-top:0;padding-bottom:0;" name="k_ambulance">
											<option value=0 selected>k = 0</option>
											<?php foreach($this->webmodel->select_generator_where_id_type(1) AS $key => $row) : ?>
												<option value=<?php echo $key+1?>>k = <?php echo $key+1 ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group">
								<p class="col-lg-5" style="padding:0; margin:0;">kth Police Unit</p>
								<div class="col-lg-7">
									<?php if(isset($k_police)) : ?>
										<select class="form-control" style="height:28px;font-size:11px;padding-top:0;padding-bottom:0;" name="k_police">
											<option value=0>k = 0</option>
											<?php foreach($this->webmodel->select_generator_where_id_type(2) AS $key => $row) : ?>
												<?php if($k_police == $key+1) : ?>
													<option value=<?php echo $key+1?> selected>k = <?php echo $key+1 ?></option>
												<?php else : ?>
													<option value=<?php echo $key+1?>>k = <?php echo $key+1 ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										</select>
									<?php else : ?>	
										<select class="form-control" style="height:28px;font-size:11px;padding-top:0;padding-bottom:0;" name="k_police">
											<option value=0 selected>k = 0</option>
											<?php foreach($this->webmodel->select_generator_where_id_type(2) AS $key => $row) : ?>
												<option value=<?php echo $key+1?>>k = <?php echo $key+1 ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group">
								<p class="col-lg-5" style="padding:0; margin:0;">kth Fire Brigade Unit</p>
								<div class="col-lg-7">
									<?php if(isset($k_firebrigade)) : ?>
										<select class="form-control" style="height:28px;font-size:11px;padding-top:0;padding-bottom:0;" name="k_firebrigade">
											<option value=0>k = 0</option>
											<?php foreach($this->webmodel->select_generator_where_id_type(3) AS $key => $row) : ?>
												<?php if($k_firebrigade == $key+1) : ?>
													<option value=<?php echo $key+1?> selected>k = <?php echo $key+1 ?></option>
												<?php else : ?>
													<option value=<?php echo $key+1?>>k = <?php echo $key+1 ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										</select>
									<?php else : ?>	
										<select class="form-control" style="height:28px;font-size:11px;padding-top:0;padding-bottom:0;" name="k_firebrigade">
											<option value=0 selected>k = 0</option>
											<?php foreach($this->webmodel->select_generator_where_id_type(3) AS $key => $row) : ?>
												<option value=<?php echo $key+1?>>k = <?php echo $key+1 ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
								</div>
							</div>

							<br><br><br><br><br>


							<button type='submit' class='btn btn-primary btn-block' id='search' style="height:36px; font-size:14px"><i class='glyphicon glyphicon-search'></i> Search</button>
						</div>

					</div>
					<?php echo form_close(); ?>




					<div class="panel panel-default">
						<div class="panel-heading">
							<h9><b>VISUAL OPTION</b></h9>
						</div>
						<div class="panel-body" style="font-size:14px;">
							<p style="padding:0; margin:0;">Point Interest</p>
							<select class="form-control" id="type_point" name="type_point" style="height:36px;font-size:14px;">
								<option value='0' selected>All Unit</option>
								<option value='1'>Hospital</option>
								<option value='2' >Police Station</option>
								<option value='3' >Fire Brigade</option>
							</select>
							
							


							<?php if($index_step == 99) : ?>
<!-- 								<p style="padding-top:10px; margin:0;">Algorithm</p>
								<div class="radio" id="type_algo" name="type_algo" style="padding:0;margin:0;">
									<label>
										<input type="radio" name="type_algo" id="1st_algo" value="1st_algo" checked>
										1st Algorithm &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</label>
									<label>
										<input type="radio" name="type_algo" id="2nd_algo" value="2nd_algo">
										2nd Algorithm
									</label>
								</div> -->
							<?php else : ?>
								<!-- <p style="padding-top:10px; margin:0;">Map Legend</p>
								<img src="<?php echo site_url(); ?>/assets/maps/mapicons/hospital-building.png ?>" style="height:30px;width:25px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hospital <br>
								<img src="<?php echo site_url(); ?>/assets/maps/mapicons/police.png ?>" style="height:30px;width:25px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Police Station <br>
								<img src="<?php echo site_url(); ?>/assets/maps/mapicons/firemen.png ?>" style="height:30px;width:25px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fire Departement <br>
						 -->	<?php endif; ?>
						</div>
					</div>
					
				</div>
				<div class='col-md-8'>
					<?php if(isset($notif)) : ?>
						<div id="map-canvas" style="width:100%;height:490px;color:black;"></div>
					<?php else : ?>
						<div id="map-canvas" style="width:100%;height:560px;color:black;"></div>
					<?php endif; ?>
				<!-- <div class="panel panel-default" style="height:110px;width:250px;top:15px;left:625px;right:0px;bottom:0px;position:absolute">
					<div class="panel-heading" style="text-align:center">
						<h9><b>POINT INTEREST</b></h9>
					</div>
					<div class="panel-body" style="font-size:14px;text-align:center">
						<select class="form-control" id="type_point" name="type_point" style="height:36px;font-size:14px;">
							<option value='0' selected>All Unit</option>
							<option value='1'>Hospital</option>
							<option value='2' >Police Station</option>
							<option value='3' >Fire Departement</option>
						</select>
					</div>
				</div> -->
			</div>
		</div>
		<br><br>
		<?php if($index_step == 99) : ?>
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h9><b>INFO</b></h9>
				</div>
				<div class="panel-body" style="font-size:14px;">
						<div>
							<li><b>Basic KNN</b></li>
							<table class="table table-striped table-hover" style="font-size:12px">
								  <thead>
								    <tr>
								      <th>Emergency Unit</th>
								      <th>kth Selected</th>
								      <th>Name</th>
								      <th>Distance (km)</th>
								      <th>Execution Time</th>
								    </tr>
								  </thead>
								  <tbody>
								    <tr>
								      <td>Ambulance</td>
								      <td><?php echo $k_ambulance; ?></td>
								      <td><?php echo $road_h_1st[0]; ?></td>
								      <td><?php echo $road_h_1st[2]; ?> km</td>
								      <td><?php echo $exec_time_h_1st; ?> s</td>
								    </tr>
								    <tr>
								      <td>Police</td>
								      <td><?php echo $k_police; ?></td>
								      <td><?php echo $road_ps_1st[0]; ?></td>
								      <td><?php echo $road_ps_1st[2]; ?> km</td>
								      <td><?php echo $exec_time_ps_1st; ?> s</td>
								    </tr>
								    <tr>
								      <td>Fire Brigade</td>
								      <td><?php echo $k_firebrigade; ?></td>
								      <td><?php echo $road_fs_1st[0]; ?></td>
								      <td><?php echo $road_fs_1st[2]; ?> km</td>
								      <td><?php echo $exec_time_fs_1st; ?> s</td>
								    </tr>
								    <?php if($exec_time_total_1st < $exec_time_total_3rd) : ?>
									    <tr class="info">
									    	<td colspan="4" align="right"><b>Total Execution Time</b></td>
									    	<td><b><?php echo $exec_time_total_1st; ?> s</b></td>
									    </tr>
									<?php elseif($exec_time_total_1st > $exec_time_total_3rd) : ?>
										<tr class="danger">
									    	<td colspan="4" align="right"><b>Total Execution Time</b></td>
									    	<td><b><?php echo $exec_time_total_1st; ?> s</b></td>
									    </tr>
									<?php endif; ?>
								  </tbody>
							</table>

				
							<li><b>Network Voronoi Diagram based KNN</b></li>
							<table class="table table-striped table-hover " style="font-size:12px">
								  <thead>
								    <tr>
								      <th>Emergency Unit</th>
								      <th>kth Selected</th>
								      <th>Name</th>
								      <th>Distance (km)</th>
								      <th>Execution Time</th>
								    </tr>
								  </thead>
								  <tbody>
								    <tr>
								      <td>Ambulance</td>
								      <td><?php echo $k_ambulance; ?></td>
								      <td><?php echo $road_h_3rd[0]; ?></td>
								      <td><?php echo $road_h_3rd[2]; ?> km</td>
								      <td><?php echo $exec_time_h_3rd; ?> s</td>
								    </tr>
								    <tr>
								      <td>Police</td>
								      <td><?php echo $k_police; ?></td>
								      <td><?php echo $road_ps_3rd[0]; ?></td>
								      <td><?php echo $road_ps_3rd[2]; ?> km</td>
								      <td><?php echo $exec_time_ps_3rd; ?> s</td>
								    </tr>
								    <tr>
								      <td>Fire Brigade</td>
								      <td><?php echo $k_firebrigade; ?></td>
								      <td><?php echo $road_fs_3rd[0]; ?></td>
								      <td><?php echo $road_fs_3rd[2]; ?> km</td>
								      <td><?php echo $exec_time_fs_3rd; ?> s</td>
								    </tr>
								    <?php if($exec_time_total_3rd < $exec_time_total_1st) : ?>
									    <tr class="info">
									    	<td colspan="4" align="right"><b>Total Execution Time</b></td>
									    	<td><b><?php echo $exec_time_total_3rd; ?> s</b></td>
									    </tr>
								    <?php elseif($exec_time_total_3rd > $exec_time_total_1st) : ?>
									<tr class="danger">
								    	<td colspan="4" align="right"><b>Total Execution Time</b></td>
								    	<td><b><?php echo $exec_time_total_3rd; ?></b></td>
								    </tr>
									<?php endif; ?>
								  </tbody>
							</table> 
					
				</div>
				<p class='pull-right'>
					<a href='http://facebook.com/afachreezal'>Ashari Fachrizal</a> - <a href='http://telkomuniversity.ac.id'> Telkom University &nbsp;&nbsp;</a>
				</p>
			</div>
		</div>
	<?php endif; ?>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9MDhON5KSnuzHJm4Yl0js0YDEfEmer1A"></script>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="<?php echo site_url(); ?>/assets/maps/Mapster.js"></script>
	<script src="<?php echo site_url(); ?>/assets/maps/map-options.js"></script>


	<script>
		function f1() {
			document.getElementById("step1").href = 'http://localhost/maps_bandung/home/map_step/1/' + document.getElementById("type_point").value;
			document.getElementById("step2").href = 'http://localhost/maps_bandung/home/map_step/2/' + document.getElementById("type_point").value;
			document.getElementById("step3").href = 'http://localhost/maps_bandung/home/map_step/3/' + document.getElementById("type_point").value;

			
		}

	</script>

	<script>

		$(document).ready(function() {
			setTimeout(function(){
				$('body').addClass('loaded');
				$('h1').css('color','#222222');
			}, 1000);

		});

		(function testa(window, mapster){
			window.onload = function(){
				var type_point = document.getElementById("type_point").value;
			}
			var options = mapster.MAP_OPTIONS,
			element = document.getElementById('map-canvas'),
			map = mapster.create(element, options);
			
			

			var markers = new Array();

			

			<?php if(!empty($marker)) : ?>
			<?php foreach($marker AS $row): ?>
			if ("<?php echo $row->is_border ?>" == "0") {
				ic = "<?php echo site_url(); ?>/assets/maps/mapicons/junction.png";
			} else {
				ic = "<?php echo site_url(); ?>/assets/maps/mapicons/symbol_beta.png";
			}
			marker = map.addMarker({
				lat: <?php echo $row->lat ?>, 
				lng: <?php echo $row->lng ?>, 
				icon: ic,
				content: "<?php echo $row->name ?>"
			});
			markers.push(marker);
		<?php endforeach ?>
	<?php endif; ?>




	<?php if($index_step == 99) : ?>
	var type_generator = 0;
	var type_algo = '1st_algo';

	marker = map.addMarker({
		lat: <?php echo $query_point['lat'] ?>, 
		lng: <?php echo $query_point['lng'] ?>,
		label: 'Q',
		content: "Query Point"
	});
	markers.push(marker);


	<?php foreach($generator AS $row): ?>
	if ("<?php echo $row->id_type ?>" == "1") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/hospital-building.png";
	} else if ("<?php echo $row->id_type ?>" == "2") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/police.png";
	} else if ("<?php echo $row->id_type ?>" == "3") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/firemen.png";
	} else if ("<?php echo $row->id_type ?>" == "4") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/airport.png";
	} else if ("<?php echo $row->id_type ?>" == "5") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/university.png";
	} else if ("<?php echo $row->id_type ?>" == "99") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/symbol_beta.png";
	} else {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/junction.png";
	}
	marker = map.addMarker({
		lat: <?php echo $row->lat ?>, 
		lng: <?php echo $row->lng ?>, 
		icon: ic,
		content: "<?php echo $row->gname ?>"
	});
	markers.push(marker);
<?php endforeach ?>	

var flightPaths = [];
<?php foreach ($neighbor AS $row_neighbor) : ?>
	var flightPath = map.addRoad({
		coordinates: [
		<?php foreach ($this->webmodel->select_all_path_where_id_marker1_marker2($row_neighbor->id_marker1, $row_neighbor->id_marker2, "ASC") AS $row) : ?>
		{lat: <?php echo $row->lat ?>, lng: <?php echo $row->lng ?>},
	<?php endforeach; ?>
	],
	strokeOpacity: 1.0,
	strokeWeight: 1,
	idGenerator: 99
});
<?php endforeach; ?>
flightPaths.push(flightPath);

<?php if($road_h_1st[1] != null) : ?>
	var flightPath = map.addRoad({
		coordinates: [
			<?php foreach ($road_h_1st[1] AS $row) : ?>
			{lat: <?php echo $row['lat'] ?>, lng: <?php echo $row['lng'] ?>},
			<?php endforeach; ?>
	],
	strokeOpacity: 0.75,
	strokeWeight: 5,
	idGenerator: 1
	});
	flightPaths.push(flightPath);
<?php endif; ?>

<?php if($road_ps_1st[1] != null) : ?>
var flightPath = map.addRoad({
	coordinates: [
		<?php foreach ($road_ps_1st[1] AS $row) : ?>
		{lat: <?php echo $row['lat'] ?>, lng: <?php echo $row['lng'] ?>},
		<?php endforeach; ?>
],
strokeOpacity: 0.5,
strokeWeight: 5,
idGenerator: 2
});
flightPaths.push(flightPath);
<?php endif; ?>

<?php if($road_fs_1st[1] != null) : ?>
	var flightPath = map.addRoad({
		coordinates: [
			<?php foreach ($road_fs_1st[1] AS $row) : ?>
			{lat: <?php echo $row['lat'] ?>, lng: <?php echo $row['lng'] ?>},
			<?php endforeach; ?>
	],
	strokeOpacity: 0.25,
	strokeWeight: 5,
	idGenerator: 3
	});
	flightPaths.push(flightPath);
<?php endif; ?>

$('input[type=radio][name=type_algo]').change(function() {
	switch($(this).val()) {
		case '1st_algo' :
		type_algo = '1st_algo';
		visualize(type_generator, type_algo);
		break;
		case '2nd_algo' :
		type_algo = '2nd_algo';
		visualize(type_generator, type_algo);
		break;
	}
});

$( "select" ).change(function() {
	type_generator = document.getElementById("type_point").value;
	visualize(type_generator, type_algo);
});

function visualize(type_generator, type_algo){


	for (i=0; i<markers.length; i++) 
	{                           
				markers[i].setMap(null); //or line[i].setVisible(false);
			}
			for (i=0; i<flightPaths.length; i++) 
			{                           
				flightPaths[i].setMap(null); //or line[i].setVisible(false);
			}

			marker = map.addMarker({
				lat: <?php echo $query_point['lat'] ?>, 
				lng: <?php echo $query_point['lng'] ?>,
				label: 'Q',
				content: "Query Point"
			});
			markers.push(marker);

			if(type_generator == 1){
				<?php foreach($generator AS $row): ?>
				if ("<?php echo $row->id_type ?>" == 1) {
					ic = "<?php echo site_url(); ?>/assets/maps/mapicons/hospital-building.png";
					marker = map.addMarker({
						lat: <?php echo $row->lat ?>, 
						lng: <?php echo $row->lng ?>, 
						icon: ic,
						content: "<?php echo $row->gname ?>"
					});
					markers.push(marker);
				}
			<?php endforeach ?>

			if(type_algo == '1st_algo'){

				<?php if($road_h_1st[1] != null) : ?>
					var flightPath = map.addRoad({
						coordinates: [
						<?php foreach ($road_h_1st[1] AS $row) : ?>
						{lat: <?php echo $row['lat'] ?>, lng: <?php echo $row['lng'] ?>},
					<?php endforeach; ?>
					],
					strokeOpacity: 1,
					strokeWeight: 5,
					idGenerator: 1
				});
				flightPaths.push(flightPath);
				<?php endif; ?>
			}
		} else if(type_generator == 2){
			<?php foreach($generator AS $row): ?>
			if ("<?php echo $row->id_type ?>" == 2) {
				ic = "<?php echo site_url(); ?>/assets/maps/mapicons/police.png";
				marker = map.addMarker({
					lat: <?php echo $row->lat ?>, 
					lng: <?php echo $row->lng ?>, 
					icon: ic,
					content: "<?php echo $row->gname ?>"
				});
				markers.push(marker);
			}
		<?php endforeach ?>

		if(type_algo == '1st_algo'){
			<?php if($road_ps_1st[1] != null) : ?>
				var flightPath = map.addRoad({
					coordinates: [
					<?php foreach ($road_ps_1st[1] AS $row) : ?>
					{lat: <?php echo $row['lat'] ?>, lng: <?php echo $row['lng'] ?>},
				<?php endforeach; ?>
				],
				strokeOpacity: 1,
				strokeWeight: 5,
				idGenerator: 2
				});
				flightPaths.push(flightPath);
			<?php endif;?>
		}
	} else if(type_generator == 3){
		<?php foreach($generator AS $row): ?>
		if ("<?php echo $row->id_type ?>" == 3) {
			ic = "<?php echo site_url(); ?>/assets/maps/mapicons/firemen.png";
			marker = map.addMarker({
				lat: <?php echo $row->lat ?>, 
				lng: <?php echo $row->lng ?>, 
				icon: ic,
				content: "<?php echo $row->gname ?>"
			});
			markers.push(marker);
		}
	<?php endforeach ?>

	if(type_algo == '1st_algo'){
		<?php if($road_fs_1st[1] != null) : ?>
			var flightPath = map.addRoad({
				coordinates: [
				<?php foreach ($road_fs_1st[1] AS $row) : ?>
				{lat: <?php echo $row['lat'] ?>, lng: <?php echo $row['lng'] ?>},
			<?php endforeach; ?>
			],
			strokeOpacity: 1,
			strokeWeight: 5,
			idGenerator: 3
			});
			flightPaths.push(flightPath);
		<?php endif; ?>
	}
} else {

	<?php foreach($generator AS $row): ?>
	if ("<?php echo $row->id_type ?>" == "1") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/hospital-building.png";
	} else if ("<?php echo $row->id_type ?>" == "2") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/police.png";
	} else if ("<?php echo $row->id_type ?>" == "3") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/firemen.png";
	} else if ("<?php echo $row->id_type ?>" == "99") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/symbol_beta.png";
	} else {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/junction.png";
	}
	marker = map.addMarker({
		lat: <?php echo $row->lat ?>, 
		lng: <?php echo $row->lng ?>, 
		icon: ic,
		content: "<?php echo $row->gname ?>"
	});
	markers.push(marker);
<?php endforeach ?>

if(type_algo == '1st_algo'){

	<?php if($road_h_1st[1] != null) : ?>
		var flightPath = map.addRoad({
			coordinates: [
			<?php foreach ($road_h_1st[1] AS $row) : ?>
			{lat: <?php echo $row['lat'] ?>, lng: <?php echo $row['lng'] ?>},
		<?php endforeach; ?>
		],
		strokeOpacity: 0.5,
		strokeWeight: 5,
		idGenerator: 1
		});
		flightPaths.push(flightPath);
	<?php endif;?>


	<?php if($road_ps_1st[1] != null) : ?>
		var flightPath = map.addRoad({
			coordinates: [
			<?php foreach ($road_ps_1st[1] AS $row) : ?>
			{lat: <?php echo $row['lat'] ?>, lng: <?php echo $row['lng'] ?>},
		<?php endforeach; ?>
		],
		strokeOpacity: 0.5,
		strokeWeight: 5,
		idGenerator: 2
		});
		flightPaths.push(flightPath);
	<?php endif; ?>


	<?php if($road_fs_1st[1] != null) : ?>
		var flightPath = map.addRoad({
			coordinates: [
			<?php foreach ($road_fs_1st[1] AS $row) : ?>
			{lat: <?php echo $row['lat'] ?>, lng: <?php echo $row['lng'] ?>},
		<?php endforeach; ?>
		],
		strokeOpacity: 0.5,
		strokeWeight: 5,
		idGenerator: 3
		});
		flightPaths.push(flightPath);
	<?php endif; ?>
}

}
}








<?php else : ?>
	<?php foreach($generator AS $row): ?>
	if ("<?php echo $row->id_type ?>" == "1") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/hospital-building.png";
	} else if ("<?php echo $row->id_type ?>" == "2") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/police.png";
	} else if ("<?php echo $row->id_type ?>" == "3") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/firemen.png";
	} else if ("<?php echo $row->id_type ?>" == "4") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/airport.png";
	} else if ("<?php echo $row->id_type ?>" == "5") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/university.png";
	} else if ("<?php echo $row->id_type ?>" == "99") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/symbol_beta.png";
	} else {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/junction.png";
	}
	marker = map.addMarker({
		lat: <?php echo $row->lat ?>, 
		lng: <?php echo $row->lng ?>, 
		icon: ic,
		content: "<?php echo $row->gname ?>"
	});
	markers.push(marker);
<?php endforeach ?>


<?php if(!empty($distinct_h)) : ?>
	<?php $inc = 1; ?>
	<?php foreach($distinct_h AS $row): ?>
	<?php $temp_h[$row->dist] = $inc; $inc++; ?>
<?php endforeach ?>
<?php endif; ?>

<?php if(!empty($distinct_ps)) : ?>
	<?php $inc = 1; ?>
	<?php foreach($distinct_ps AS $row): ?>
	<?php $temp_ps[$row->dist] = $inc; $inc++; ?>
<?php endforeach ?>
<?php endif; ?>
<?php if(!empty($distinct_fs)) : ?>
	<?php $inc = 1; ?>
	<?php foreach($distinct_fs AS $row): ?>
	<?php $temp_fs[$row->dist] = $inc; $inc++; ?>
<?php endforeach ?>
<?php endif; ?>


var  flightPaths = [];
<?php foreach ($neighbor AS $row_neighbor) : ?>
	var flightPath = map.addRoad({
		coordinates: [
		<?php foreach ($this->webmodel->select_all_path_where_id_marker1_marker2($row_neighbor->id_marker1, $row_neighbor->id_marker2, "ASC") AS $row) : ?>
		{lat: <?php echo $row->lat ?>, lng: <?php echo $row->lng ?>},
	<?php endforeach; ?>
	],
	strokeOpacity: 1,
	strokeWeight: 5,
	idGenerator: 0
});
	flightPaths.push(flightPath);
<?php endforeach; ?>

$( "select" ).change(function() {
	var type_point = document.getElementById("type_point").value;

	for (i=0; i<markers.length; i++) 
	{                           
				markers[i].setMap(null); //or line[i].setVisible(false);
			}
			for (i=0; i<flightPaths.length; i++) 
			{                           
				flightPaths[i].setMap(null); //or line[i].setVisible(false);
			}

			<?php if(!empty($marker)) : ?>
			<?php foreach($marker AS $row): ?>
				ic = "<?php echo site_url(); ?>/assets/maps/mapicons/symbol_beta.png";
				markers.push(marker);
				<?php endforeach ?>
			<?php endif; ?>


	if(type_point == 1){
		marker = map.addMarker({
			lat: -6.949942641719169, 
			lng: 107.6047620177269,
			label: 'Q',
			content: "Query Point"
		});
		markers.push(marker);
		<?php foreach($generator AS $row): ?>
		if ("<?php echo $row->id_type ?>" == 1) {
			ic = "<?php echo site_url(); ?>/assets/maps/mapicons/hospital-building.png";
			marker = map.addMarker({
				lat: <?php echo $row->lat ?>, 
				lng: <?php echo $row->lng ?>, 
				icon: ic,
				content: "<?php echo $row->gname ?>"
			});
			markers.push(marker);
		}
	<?php endforeach ?>

	<?php if(!empty($border_h)) : ?>
	<?php foreach($border_h AS $row): ?>
	ic = "<?php echo site_url(); ?>/assets/maps/mapicons/symbol_beta.png";
	marker = map.addMarker({
		lat: <?php echo $row->lat ?>, 
		lng: <?php echo $row->lng ?>, 
		icon: ic,
		content: "<?php echo $row->name ?>"
	});
	markers.push(marker);
<?php endforeach ?>
<?php endif; ?>

<?php foreach ($neighbor AS $row_neighbor) : ?>
	var flightPath = map.addRoad({
		coordinates: [
		<?php foreach ($this->webmodel->select_all_path_where_id_marker1_marker2($row_neighbor->id_marker1, $row_neighbor->id_marker2, "ASC") AS $row) : ?>
		{lat: <?php echo $row->lat ?>, lng: <?php echo $row->lng ?>},
	<?php endforeach; ?>
	],
	strokeOpacity: 1,
	strokeWeight: 5,
	idGenerator: <?php echo $temp_h[$row_neighbor->id_generator_h] ?>
});
	flightPaths.push(flightPath);
<?php endforeach; ?>
} else if(type_point == 2){
	<?php foreach($generator AS $row): ?>
	if ("<?php echo $row->id_type ?>" == 2) {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/police.png";
		marker = map.addMarker({
			lat: <?php echo $row->lat ?>, 
			lng: <?php echo $row->lng ?>, 
			icon: ic,
			content: "<?php echo $row->name ?>"
		});
		markers.push(marker);
	}
<?php endforeach ?>

<?php if(!empty($border_ps)) : ?>
	<?php foreach($border_ps AS $row): ?>
	ic = "<?php echo site_url(); ?>/assets/maps/mapicons/symbol_beta.png";
	marker = map.addMarker({
		lat: <?php echo $row->lat ?>, 
		lng: <?php echo $row->lng ?>, 
		icon: ic,
		content: "<?php echo $row->name ?>"
	});
	markers.push(marker);
<?php endforeach ?>
<?php endif; ?>

<?php foreach ($neighbor AS $row_neighbor) : ?>
	var flightPath = map.addRoad({
		coordinates: [
		<?php foreach ($this->webmodel->select_all_path_where_id_marker1_marker2($row_neighbor->id_marker1, $row_neighbor->id_marker2, "ASC") AS $row) : ?>
		{lat: <?php echo $row->lat ?>, lng: <?php echo $row->lng ?>},
	<?php endforeach; ?>
	],
	strokeOpacity: 1,
	strokeWeight: 5,
	idGenerator: <?php echo $temp_ps[$row_neighbor->id_generator_ps] ?>
});
	flightPaths.push(flightPath);
<?php endforeach; ?>
} else if(type_point == 3){
	<?php foreach($generator AS $row): ?>
	if ("<?php echo $row->id_type ?>" == 3) {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/firemen.png";
		marker = map.addMarker({
			lat: <?php echo $row->lat ?>, 
			lng: <?php echo $row->lng ?>, 
			icon: ic,
			content: "<?php echo $row->name ?>"
		});
		markers.push(marker);
	}
<?php endforeach ?>

<?php if(!empty($border_fs)) : ?>
	<?php foreach($border_fs AS $row): ?>
	ic = "<?php echo site_url(); ?>/assets/maps/mapicons/symbol_beta.png";
	marker = map.addMarker({
		lat: <?php echo $row->lat ?>, 
		lng: <?php echo $row->lng ?>, 
		icon: ic,
		content: "<?php echo $row->name ?>"
	});
	markers.push(marker);
<?php endforeach ?>
<?php endif; ?>

<?php foreach ($neighbor AS $row_neighbor) : ?>
	var flightPath = map.addRoad({
		coordinates: [
		<?php foreach ($this->webmodel->select_all_path_where_id_marker1_marker2($row_neighbor->id_marker1, $row_neighbor->id_marker2, "ASC") AS $row) : ?>
		{lat: <?php echo $row->lat ?>, lng: <?php echo $row->lng ?>},
	<?php endforeach; ?>
	],
	strokeOpacity: 1,
	strokeWeight: 5,
	idGenerator: <?php echo $temp_fs[$row_neighbor->id_generator_fs] ?>
});
	flightPaths.push(flightPath);
<?php endforeach; ?>
} else {
	<?php foreach($generator AS $row): ?>
	if ("<?php echo $row->id_type ?>" == "1") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/hospital-building.png";
	} else if ("<?php echo $row->id_type ?>" == "2") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/police.png";
	} else if ("<?php echo $row->id_type ?>" == "3") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/firemen.png";
	} else if ("<?php echo $row->id_type ?>" == "99") {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/symbol_beta.png";
	} else {
		ic = "<?php echo site_url(); ?>/assets/maps/mapicons/junction.png";
	}
	marker = map.addMarker({
		lat: <?php echo $row->lat ?>, 
		lng: <?php echo $row->lng ?>, 
		icon: ic,
		content: "<?php echo $row->name ?>"
	});
	markers.push(marker);
<?php endforeach ?>


<?php foreach ($neighbor AS $row_neighbor) : ?>
	var flightPath = map.addRoad({
		coordinates: [
		<?php foreach ($this->webmodel->select_all_path_where_id_marker1_marker2($row_neighbor->id_marker1, $row_neighbor->id_marker2, "ASC") AS $row) : ?>
		{lat: <?php echo $row->lat ?>, lng: <?php echo $row->lng ?>},
	<?php endforeach; ?>
	],
	strokeOpacity: 1,
	strokeWeight: 5,
	idGenerator: 0
});
	flightPaths.push(flightPath);
<?php endforeach; ?>
}
});


<?php endif; ?>





}(window, window.Mapster) || (window.Mapster = {}));
</script>
</body>
</html>