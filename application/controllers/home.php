<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('webmodel');
		$this->load->library('fibonacciheap');
		$this->load->library('fibonacciheapelement');
		//$this->load->library('r_tree');
		$this->load->library('node');
		$this->load->library('graph');
	}

	public function index()
	{
		// $rt = new r_tree();
		// $rt->RTree2(5, 2, 2);
		// $numEntries = $rt->getMaxEntries() * 4;

		// $entries1 = (object) array('id' => 1);
		// $coords1 = array(-6.969879, 107.583679);
		// $dims1 = array(-6.933779 -(-6.969879), 107.619781 - 107.583679);
		// $rt->insert($coords1, $dims1, $entries1);

		// // $entries2 = (object) array('id' => 2);
		// // $coords2 = array(1.0, 0.0);
		// // $dims2 = array(0.5, 1.5);
		// // $rt->insert($coords2, $dims2, $entries2);

		// // $entries3 = (object) array('id' => 3);
		// // $coords3 = array(0.0, 1.5);
		// // $dims3 = array(1.5, 0.5);
		// // $rt->insert($coords3, $dims3, $entries3);

		// // $entries4 = (object) array('id' => 4);
		// // $coords4 = array(1.0, 1.5);
		// // $dims4 = array(1.5, 0.5);
		// // $rt->insert($coords4, $dims4, $entries4);

		// // $entries5 = (object) array('id' => 5);
		// // $coords5 = array(0.0, 1.5);
		// // $dims5 = array(1.5, 0.5);
		// // $rt->insert($coords5, $dims5, $entries5);

		// $results = $rt->search(array(-6.9640004355898535, 107.59132146835327), array(0.0, 0.0));

		// echo "<pre>";
		// print_r("=================================<br>");
		// print_r($results);
		// echo "</pre>";
		// die();

		//$this->search_path(1, 1);

		$data['index_step'] = 0;

		// echo "<pre>";
		// print_r($this->border_comparison(1));
		// echo "</pre>";

		// echo $this->count_distance(324, 325) + $this->count_distance(325, 326) + $this->count_distance(326, 413) ;
		// echo "<br>";
		// echo $this->count_distance(328, 327) + $this->count_distance(327, 413);



		// echo "OY OM<br>";
		// echo $this->count_distance(334, 335) + $this->count_distance(335, 401) + $this->count_distance(401, 164) + $this->count_distance(164, 165) + $this->count_distance(165, 431);
		// echo "<br>";
		// echo $this->count_distance(336, 337) + $this->count_distance(337, 237)  + $this->count_distance(237, 238) + $this->count_distance(238, 236) + $this->count_distance(236, 235) + $this->count_distance(235, 241) + $this->count_distance(241, 431);
		
		// $data['marker'] = $this->webmodel->select_marker_where_is_border(0);

		$data['neighbor'] = $this->webmodel->select_all_neighbor();
		$data['generator'] = $this->webmodel->select_all_generator();
		

		// $data['border_h'] = $this->webmodel->select_marker_where_is_border(1);
		$data['distinct_h'] = $this->webmodel->select_distinct_id_generator_neighbor_h();

		// $data['border_ps'] = $this->webmodel->select_marker_where_is_border(2);
		$data['distinct_ps'] = $this->webmodel->select_distinct_id_generator_neighbor_ps();

		// $data['border_fs'] = $this->webmodel->select_marker_where_is_border(3);
		$data['distinct_fs'] = $this->webmodel->select_distinct_id_generator_neighbor_fs();

		$this->load->view('maps', $data);

	}

	

		public function generate_border($id_type){
		@set_time_limit(-1);
		foreach($this->webmodel->select_generator_where_id_type($id_type) as $row_start) {
			$answer = array();
			$neighbour = array();

			foreach ($this->webmodel->select_all_path_group_neighbor() AS $row) {
				$polyline_length = $this->count_distance($row->id_marker1, $row->id_marker2);
				$neighbour[$row->id_marker1][$row->id_marker2] = $polyline_length;
				$neighbour[$row->id_marker2][$row->id_marker1] = $polyline_length;
			}


			$visited = array();
			for ($i = 1; $i <= count($neighbour); $i++) {
	            $visited[$i] = 0;
	        }
	        foreach($this->webmodel->select_generator_where_id_type($id_type) as $row) {
				$visited[$row->id] = 99;
			}

			foreach($this->webmodel->select_marker_where_is_border($id_type) as $row) {
				$visited[$row->id] = 50;
			}



			$path = array();
			for ($i = 1; $i <= count($neighbour); $i++) {
	            $path[$i] = array();
	        }

	        array_push($path[$row_start->id], $row_start->id);


	        $is_loop = true;
	        foreach ($neighbour[$row_start->id] as $row => $value) {
	        	if($visited[$row] == 99){
	        		$temp = array(0 => $row);
	        		$path[$row] = array_merge($path[$row_start->id], $temp);
	        		array_push($answer, array(0 => $value, 1 => $path[$row]));
	        		$border = $this->find_mid_point($path[$row], $value);

					$this->webmodel->insert_border($id_type, $border->lat, $border->lng, $border->point1, $border->point2, $border->path_point1, $border->path_point2);
					
	        	} elseif($visited[$row] == 50){
	        		$is_loop == false;
	        	} else {
	        		$queue[$row] = $value;
	        		$temp = array(0 => $row);
	        		$path[$row] = array_merge($path[$row_start->id], $temp);
	        	}
	        }

	        while($is_loop){
				foreach ($this->webmodel->select_all_path_group_neighbor() AS $row) {
					$polyline_length = $this->count_distance($row->id_marker1, $row->id_marker2);
					$neighbour[$row->id_marker1][$row->id_marker2] = $polyline_length;
					$neighbour[$row->id_marker2][$row->id_marker1] = $polyline_length;
				}

				$visited = array();
				for ($i = 1; $i <= count($neighbour); $i++) {
		            $visited[$i] = 0;
		        }
		        foreach($this->webmodel->select_generator_where_id_type($id_type) as $row) {
					$visited[$row->id] = 99;
				}

				foreach($this->webmodel->select_marker_where_is_border($id_type) as $row) {
					$visited[$row->id] = 50;
				}

	        	while(!empty($queue)){
					$selected = array_search(min($queue), $queue);


					if($visited[$selected] == 0){

						$bool = false;
						$temp_dist = $queue[$selected];
						
						foreach($this->webmodel->select_generator_where_id_type($id_type) as $row) {
							if(array_key_exists($row->id, $neighbour[$selected]) && $row->id != $row_start->id && $bool == false){
								
								$temp = array(0 => $row->id);
								$path[$row->id] = array_merge($path[$selected], $temp);
			
								array_push($answer, array(0 => $neighbour[$selected][$row->id] + $temp_dist, 1 => $path[$row->id]));

								$border = $this->find_mid_point($path[$row->id], $neighbour[$selected][$row->id] + $temp_dist);

								$this->webmodel->insert_border($id_type, $border->lat, $border->lng, $border->point1, $border->point2, $border->path_point1, $border->path_point2);
									
								$bool = true;
								unset($neighbour[$border->point1][$border->point2]);
								unset($neighbour[$border->point2][$border->point1]);

								foreach ($neighbour[$selected] as $row => $value) {
									$visited[$row] = 1;
								}

								$queue = array();
								foreach ($neighbour[$row_start->id] as $row => $value) {
						        	if($visited[$row] == 99){
						        		$temp = array(0 => $row);
						        		$path[$row] = array_merge($path[$row_start->id], $temp);
						        		
						        		$border = $this->find_mid_point($path[$row], $value);
						        		
										$this->webmodel->insert_border($id_type, $border->lat, $border->lng, $border->point1, $border->point2, $border->path_point1, $border->path_point2);
						        	} elseif($visited[$row] == 50){
						        		break;
						        	} else {
					        			$queue[$row] = $value;
						        		$temp = array(0 => $row);
						        		$path[$row] = array_merge($path[$row_start->id], $temp);
						        	}
						        }
								break 2;
							}
						}

						
						
						if($bool == false){
							foreach ($neighbour[$selected] as $row => $value) {
								if($visited[$row] == 0){
									if(isset($queue[$row])){
										if($queue[$row] < $value + $temp_dist){
										}else{
								        	$queue[$row] = $value + $temp_dist;
											$temp = array(0 => $row);
											$path[$row] =  array_merge($path[$selected], $temp);
										}
									} else {
										$queue[$row] = $value + $temp_dist;
										$temp = array(0 => $row);
										$path[$row] =  array_merge($path[$selected], $temp);
									}
								} else {

								}
							}
						} else {
						}
						$visited[$selected] = 1;
					} else if($visited[$selected] == 50){

					}
					unset($queue[$selected]);
					if(empty($queue)){
						$is_loop = false;
					}
					
				}
			
			// die();
			// foreach ($answer as $key) {
			// 	$border = $this->find_mid_point($key[1], $key[0]);


			// 	$this->webmodel->insert_border($border->lat, $border->lng, $border->point1, $border->point2, $border->path_point1, $border->path_point2);
			// }
			}
			
			
		}
	}

	

	public function generate_adjacency_nvd(){
		$this->webmodel->empty_adjacency();
		$answer_h = array();
		$answer_ps = array();
		$answer_fs = array();

		foreach ($this->webmodel->select_marker_where_is_border(1) as $row) {
			foreach ($this->webmodel->select_neighbor_where_id_marker_adjacency($row->id,1) as $row_n) {
				$answer_h[$row->id][$row_n->id_generator_h] = $row->id;
			}
		}


		foreach ($this->webmodel->select_marker_where_is_border(2) as $row ) {
			foreach ($this->webmodel->select_neighbor_where_id_marker_adjacency($row->id,2) as $row_n) {
				$answer_ps[$row->id][$row_n->id_generator_ps] = $row->id;
			}
		}

		foreach ($this->webmodel->select_marker_where_is_border(3) as $row ) {
			foreach ($this->webmodel->select_neighbor_where_id_marker_adjacency($row->id,3) as $row_n) {
				$answer_fs[$row->id][$row_n->id_generator_fs] = $row->id;
			}
		}

		// echo "<pre>Obrolan nyata";
		// 	print_r($answer_h);
		// 	echo"</pre>";

		$k_h = 0; $k_ps = 0; $k_fs = 0;
		$prev1_h = null; $prev2_h = null; $prev1_ps = null; $prev2_ps = null; $prev1_fs = null; $prev2_fs = null;
		foreach ($answer_h as $key => $row){
			$temp_h = array_keys($row);
			if($prev1_h != $temp_h[0] || $prev2_h != $temp_h[1]){
				// echo "<pre>";
				// print_r($row);
				// echo "adaw " . $key;
				// echo"</pre>";
				$k_h++;
			}
			$this->webmodel->insert_adjacency($key, $temp_h[0], $temp_h[1], 1, $k_h);	
			$prev1_h = $temp_h[0]; $prev2_h = $temp_h[1];
		}
		foreach ($answer_ps as $key => $row){
			$temp_ps = array_keys($row);
			if($prev1_ps != $temp_ps[0] || $prev2_ps != $temp_ps[1])
				$k_ps++;
			$this->webmodel->insert_adjacency($key, $temp_ps[0], $temp_ps[1], 2, $k_ps);
			$prev1_ps = $temp_ps[0]; $prev2_ps = $temp_ps[1];
		}
		foreach ($answer_fs as $key => $row){
			$temp_fs = array_keys($row);
			if($prev1_fs != $temp_fs[0] || $prev2_fs != $temp_fs[1])
				$k_fs++;
			$this->webmodel->insert_adjacency($key, $temp_fs[0], $temp_fs[1], 3, $k_fs);
			$prev1_fs = $temp_fs[0]; $prev2_fs = $temp_fs[1];
		}
	}

	public function update_border_group(){

	}

	public function border_comparison($id_type){
		@set_time_limit(-1);
		$data = array();
		foreach($this->webmodel->select_adjacency_where_id_type($id_type) as $row){
			$dist_generator1 = $this->djikstra($row->id, $row->id_generator1, 3);
			$dist_generator2 = $this->djikstra($row->id, $row->id_generator2, 3);
			if($dist_generator1 != $dist_generator2)
				$dist_generator2 = $dist_generator1;
			array_push($data, array('id_border' => $row->id, 'id_generator1' => $row->id_generator1, 'dist_generator1' => $dist_generator1, 'id_generator2' => $row->id_generator2, 'dist_generator2' => $dist_generator2));
		}
		return $data;
	}

	public function map_step($index_step, $id_type){
		@set_time_limit(-1);
		$data['index_step'] = $index_step;
		$data['query_coords'] = null;
		$data['selected_type_point'] = $id_type;
		if($index_step == 1){
			$this->webmodel->empty_border();
			$this->webmodel->update_construct_neighbor();
			$data['marker'] = $this->webmodel->select_marker_where_is_border(0);
			$data['neighbor'] = $this->webmodel->select_all_neighbor();
			$data['generator'] = $this->webmodel->select_all_generator();

			$data['distinct_h'] = $this->webmodel->select_distinct_id_generator_neighbor_h();
			$data['distinct_ps'] = $this->webmodel->select_distinct_id_generator_neighbor_ps();
			$data['distinct_fs'] = $this->webmodel->select_distinct_id_generator_neighbor_fs();
			// $data['borders'] = $this->generate_border();
			// $this->generate_distance();
			// $this->parallel_djikstra();
			// $data['nvd'] = $this->webmodel->select_all_neighbor();
			$flash_data = 
					"<div class='alert alert-info' style='height:30'>
					<span class='fa fa-info-circle' aria-hidden='true'></span>
					<strong>Construct table</strong><br>
					remove_border() - ungrouping_link() - count_distance()
					</div>";

			$this->session->set_flashdata('notif',$flash_data);
			$this->load->view('maps', $data);
		} else if($index_step == 2){

			$this->generate_border(1);
			$this->generate_border(2);
			$this->generate_border(3);
			//$this->generate_distance();
			// $this->parallel_djikstra($id_type);
			$data['marker'] = $this->webmodel->select_marker_where_is_border(1);
			$data['generator'] = $this->webmodel->select_all_generator();
			$data['neighbor'] = $this->webmodel->select_all_neighbor();
			$flash_data = 
				"<div class='alert alert-success' style='height:30'>
				<span class='fa fa-check-circle' aria-hidden='true'></span>
				<strong>Success Creating Border</strong><br>
				NVD = compute_border() - grouping_link()
				</div>";

			$this->session->set_flashdata('notif',$flash_data);
			$this->load->view('maps', $data);
		} else if($index_step == 3){
			$this->parallel_djikstra(1);
			$this->parallel_djikstra(2);
			$this->parallel_djikstra(3);
			$data['marker'] = null;
			$data['generator'] = $this->webmodel->select_all_generator();;
			$data['neighbor'] = $this->webmodel->select_all_neighbor();
			$data['distinct_h'] = $this->webmodel->select_distinct_id_generator_neighbor_h();
			$data['distinct_ps'] = $this->webmodel->select_distinct_id_generator_neighbor_ps();
			$data['distinct_fs'] = $this->webmodel->select_distinct_id_generator_neighbor_fs();
			$flash_data = 
				"<div class='alert alert-success' style='height:30'>
				<span class='fa fa-check-circle' aria-hidden='true'></span>
				<strong>Success Generating NVD</strong><br>
				NVD = compute_border() - grouping_link()
				</div>";

			$this->session->set_flashdata('notif',$flash_data);
			$this->load->view('maps', $data);
		} else if($index_step == 4){
			$data['marker'] = $this->webmodel->select_all_marker_not_generator();
			$data['generator'] = $this->webmodel->select_generator_where_id_type($id_type);
			$data['neighbor'] = $this->webmodel->select_all_neighbor();
			$data['poly'] = $this->create_mbr($id_type);
			// $data['poly'] = $this->create_convex_hull($id_type);
			$flash_data = 
				"<div class='alert alert-success' style='height:30'>
				<span class='fa fa-check-circle' aria-hidden='true'></span>
				<strong>Success Generating NVP</strong><br>
				NVP = compute_convex_hull()
				</div>";

			$this->session->set_flashdata('notif',$flash_data);
			$this->load->view('maps', $data);
		} else {
			$this->index();
		}
	}

	public function construct(){
		@set_time_limit(-1);

		// $this->generate_distance();
		// $this->generate_border(1);
		// $this->generate_distance_border(1);
		// $this->generate_border(2);
		// $this->generate_distance_border(2);
		// $this->generate_border(3);
		// $this->generate_distance_border(3);

		// $this->parallel_djikstra(1);
		// $this->parallel_djikstra(2);
		// $this->parallel_djikstra(3);

		$this->webmodel->empty_adjacency();
		$this->generate_adjacency_nvd();

		// $loop = $this->djikstra(411, 330);
		// print_r($loop);
		// foreach ($loop AS $row) {
		// 	if(!isset($polyline_length)){
		// 		$polyline_length = 0;
		// 		$prev = $row;
		// 		echo "a";
		// 	}
		// 	else{
		// 		echo $prev . " | " . $row;
		// 		$polyline_length += $this->count_distance($prev, $row);
		// 		$prev = $row;
		// 	}
		// 	echo $polyline_length . "<br>";
		// }
		// print_r($polyline_length);

		$this->index();

	}

	public function debug(){
		@set_time_limit(-1);
		$data['border_comparison_ambulance'] = $this->border_comparison(1);
		$data['border_comparison_police'] = $this->border_comparison(2);
		$data['border_comparison_fire_brigade'] = $this->border_comparison(3);

		$this->load->view('maps-construct', $data);
	}

	public function remove(){
		$this->webmodel->empty_border();
		$this->webmodel->update_construct_neighbor();
		$this->webmodel->empty_adjacency();

		// // $this->index();
		// echo "<pre>";
		// print_r($this->djikstra(328, 338));
		// echo "</pre>";
		$this->index();
	}

	public function search(){
		$query_coords = $this->input->post('query_coords');

		$k_ambulance = $this->input->post('k_ambulance');
		$k_police = $this->input->post('k_police');
		$k_firebrigade = $this->input->post('k_firebrigade');



		$data['query_coords'] = $query_coords;
		$data['k_ambulance'] = $k_ambulance;
		$data['k_police'] = $k_police;
		$data['k_firebrigade'] = $k_firebrigade;

		$coords = explode(" ", $query_coords);
		$data['query_point'] = array('lat' => $coords[0], 'lng' => $coords[1]);

		$data['query_coords'] = $query_coords;

		$nearestPath1 = INF;
		$nearestPath2 = INF;
		$marker1 = null;
		$marker2 = null;
		foreach($this->webmodel->select_all_path_join_neighbor() AS $row){
			$distance =  sqrt(pow(($coords[0] - ($row->lat)) , 2) + pow(($coords[1] - $row->lng) , 2))*111.32;
			if($distance < $nearestPath2){
				$nearestPath2 = $distance;
				$marker2 = $row;
			}
		}
		// $time_start_nvd = microtime(true);
		// $data['test'] = $this->search_1st_algorithm($coords, 1, $k);
		// $data['test'] = $this->search_1st_algorithm($coords, 2, $k);
		// //$data['test'] = $this->search_1st_algorithm($coords, 3, $k);
		// $time_end_nvd = microtime(true);
		// echo $time_end_nvd - $time_start_nvd . "<br><br>";
		// echo "==================================";

		// $time_start_nvd = microtime(true);
		// $this->compute_knn($coords, 1, $k);
		// $time_end_nvd = microtime(true);
	 //    echo $time_end_nvd - $time_start_nvd . " s <br> oyoy";
		// die();

		$time_h_1st= 0;	
		$time_ps_1st= 0;
		$time_fs_1st= 0;

		$time_h_3rd= 0;
		$time_ps_3rd= 0;
		$time_fs_3rd= 0;

		if($k_ambulance != 0){
			$time_start_h_1st = microtime(true);
			$data['road_h_1st'] = $this->search_1st_algorithm($coords, 1, $k_ambulance);
			$time_end_h_1st = microtime(true);
		    $time_h_1st = $time_end_h_1st - $time_start_h_1st;

    	    $time_start_h_3rd = microtime(true);
			$data['road_h_3rd'] = $this->search_3rd_algorithm($coords, $marker2, 1, $k_ambulance);
			$time_end_h_3rd = microtime(true);
		    $time_h_3rd = $time_end_h_3rd - $time_start_h_3rd;
		}else{
			$data['road_h_1st'] = array('-', null, 0);
			$data['road_h_3rd'] = array('-', null, 0);
		}

		if($k_police != 0){
		    $time_start_ps_1st = microtime(true);
			$data['road_ps_1st'] = $this->search_1st_algorithm($coords, 2, $k_police);
			$time_end_ps_1st = microtime(true);
		    $time_ps_1st = $time_end_ps_1st - $time_start_ps_1st;

    	    $time_start_ps_3rd = microtime(true);
			$data['road_ps_3rd'] = $this->search_3rd_algorithm($coords, $marker2, 2, $k_police);
			$time_end_ps_3rd = microtime(true);
		    $time_ps_3rd = $time_end_ps_3rd - $time_start_ps_3rd;
		}else{
			$data['road_ps_1st'] = array('-', null, 0);
			$data['road_ps_3rd'] = array('-', null, 0);
		}

		if($k_firebrigade != 0){
		    $time_start_fs_1st = microtime(true);
			$data['road_fs_1st'] = $this->search_1st_algorithm($coords, 3, $k_firebrigade);
			$time_end_fs_1st = microtime(true);
		    $time_fs_1st = $time_end_fs_1st - $time_start_fs_1st;

    	    $time_start_fs_3rd = microtime(true);
			$data['road_fs_3rd'] = $this->search_3rd_algorithm($coords, $marker2, 3, $k_firebrigade);
			$time_end_fs_3rd = microtime(true);
		    $time_fs_3rd = $time_end_fs_3rd - $time_start_fs_3rd;			
		}else{
			$data['road_fs_1st'] = array('-', null, 0);
			$data['road_fs_3rd'] = array('-', null, 0);
		}


	    $data['exec_time_total_1st'] = $time_h_1st + $time_ps_1st + $time_fs_1st;

	    $data['exec_time_h_1st'] = $time_h_1st;
	    $data['exec_time_ps_1st'] = $time_ps_1st;
	    $data['exec_time_fs_1st'] = $time_fs_1st;

	    $data['exec_time_total_3rd'] = $time_h_3rd + $time_ps_3rd + $time_fs_3rd;

	    $data['exec_time_h_3rd'] = $time_h_3rd;
	    $data['exec_time_ps_3rd'] = $time_ps_3rd;
	    $data['exec_time_fs_3rd'] = $time_fs_3rd;


	    //==================================================================

	 //    $time_start_h_2nd = microtime(true);
		// $data['road_h_2nd'] = $this->compute_knn($coords, 1, $k_ambulance);
		// $time_end_h_2nd = microtime(true);
	 //    $time_h_2nd = $time_end_h_2nd - $time_start_h_2nd;
	 //    $data['exec_time_h_2nd'] = $time_h_2nd;

	 //    $time_start_ps_2nd = microtime(true);
		// $data['road_ps_2nd'] = $this->compute_knn($coords, 2, $k_police);
		// $time_end_ps_2nd = microtime(true);
	 //    $time_ps_2nd = $time_end_ps_2nd - $time_start_ps_2nd;
	 //    $data['exec_time_ps_2nd'] = $time_ps_2nd;

	 //    $time_start_fs_2nd = microtime(true);
		// $data['road_fs_2nd'] = $this->compute_knn($coords, 3, $k_firebrigade);
		// $time_end_fs_2nd = microtime(true);
	 //    $time_fs_2nd = $time_end_fs_2nd - $time_start_fs_2nd;
	 //    $data['exec_time_fs_2nd'] = $time_fs_2nd;

	 //    $data['exec_time_total_2nd'] = $time_h_2nd + $time_ps_2nd + $time_fs_2nd;

		//===================================================================





		//===================================================================

		$data['index_step'] = 99;
		//$data['marker'] = $this->webmodel->select_all_marker();
		$data['generator'] = $this->webmodel->select_all_generator();
		$data['neighbor'] = $this->webmodel->select_all_neighbor();


		$this->load->view('maps', $data);

	}

	public function search_1st_algorithm($coords, $id_type, $k){
		$data = array();

		foreach ($this->webmodel->select_generator_where_id_type($id_type) AS $row_g){
			array_push($data, $this->search_djikstra($coords, $row_g->id_marker));
		}

		usort($data, function ($item1, $item2) {
		    return $item1[2] >= $item2[2];
		});

		return $data[$k-1];
	}

	public function search_3rd_algorithm($coords, $marker2, $id_type, $k){
		
	    
		if($k == 1){
		    if($id_type == 1)
				$data = $this->search_djikstra($coords, $marker2->id_generator_h);
			elseif($id_type == 2)
				$data = $this->search_djikstra($coords, $marker2->id_generator_ps);
			else
				$data = $this->search_djikstra($coords, $marker2->id_generator_fs);
		} else {
			$data = array();
			$queue = array();
			$count_k = 1;

		    if($id_type == 1){
				$queue[$marker2->id_generator_h] = 0;
				$min = $marker2->id_generator_h;
		    }
			elseif($id_type == 2){
				$queue[$marker2->id_generator_ps] = 0;
				$min = $marker2->id_generator_ps;
			}
			else{
				$queue[$marker2->id_generator_fs] = 0;
				$min = $marker2->id_generator_fs;
			}

			while($count_k < $k){
				$count_k++;
				$min = array_search(min($queue), $queue);

				$temp[$min] = "Y";

				unset($queue[$min]);
				foreach ($this->webmodel->select_adjacency_where_id_generator($min) as $row) {
					if($row->id_generator1 == $min){
						if(!isset($queue[$row->id_generator2]) && !isset($temp[$row->id_generator2])){
							$data[$row->id_generator2] = $this->search_djikstra($coords, $row->id_generator2);
							$queue[$row->id_generator2] = $data[$row->id_generator2][2];
						} else {
						}
					}elseif($row->id_generator2 == $min){
						if(!isset($queue[$row->id_generator1]) && !isset($temp[$row->id_generator1])){
							$data[$row->id_generator1] = $this->search_djikstra($coords, $row->id_generator1);
							$queue[$row->id_generator1] = $data[$row->id_generator1][2];
						} else {
						}
					}
				}
			}
			$min = array_search(min($queue), $queue);
			$data = $data[$min];
		}


		return $data;
	}

	public function search_a_star($coords, $id_type){		
		$nearestPath1 = INF;
		$nearestPath2 = INF;
		$marker1 = null;
		$marker2 = null;
		foreach($this->webmodel->select_all_path_join_neighbor() AS $row){
			$distance =  sqrt(pow(($coords[0] - ($row->lat)) , 2) + pow(($coords[1] - $row->lng) , 2))*111.32;
			if($distance < $nearestPath2){
				$nearestPath2 = $distance;
				$marker2 = $row;
			}
			

		}


		$neighbor = $this->webmodel->select_neighbor_where_path($marker2->id);


		$prevP = null;
		foreach ($this->webmodel->select_all_path_where_id_neighbor($neighbor->id) as $row) {
			if($prevP != null){
				if($marker2->id == $row->id){
					$marker1 = $prevP;
					break;
				}
				elseif($prevP->id == $marker2->id){
					$marker1 = $row;
					break;
				}
			}
			$prevP = $row;
		}
		$nearestPath1 = sqrt(pow(($coords[0] - ($marker1->lat)) , 2) + pow(($coords[1] - $marker1->lng) , 2))*111.32;

		


		// 1 = 26 = -6.947966 107.633865, 2 27 = -6.947764 107.634544, 3 = QP = -6.948145450241897 107.6342122326605
		$x1=$marker1->lat; $y1=$marker1->lng;
		$x2=$marker2->lat; $y2=$marker2->lng; 
		$x3=$coords[0]; $y3=$coords[1];
	    $px = $x2-$x1; $py = $y2-$y1; $dAB = $px*$px + $py*$py;
	    $u = (($x3 - $x1) * $px + ($y3 - $y1) * $py) / $dAB;
	    $x = $x1 + $u * $px; $y = $y1 + $u * $py;
	    
		

		//xy = -6.947885689446784  107.63413495478036

	    

		$_distArr = array();
		foreach ($this->webmodel->select_all_path_group_neighbor_gb($id_type) AS $row) {
			$polyline_length = $row->distance_neighbor;
			$_distArr[$row->id_marker1][$row->id_marker2] = $polyline_length;
			$_distArr[$row->id_marker2][$row->id_marker1] = $polyline_length;
		}
		$_distArr['QP'][$neighbor->id_marker1] = $nearestPath1;
		$_distArr[$neighbor->id_marker1]['QP'] = $nearestPath1;
		$_distArr['QP'][$neighbor->id_marker2] = $nearestPath2;
		$_distArr[$neighbor->id_marker2]['QP'] = $nearestPath2;




		//the start and the end
		$a = 'QP';
		if($id_type == 1)
			$b = $neighbor->id_generator_h;
		elseif($id_type == 2)
			$b = $neighbor->id_generator_ps;
		else
			$b = $neighbor->id_generator_fs;

		//heuristic
		foreach ($this->webmodel->select_all_marker() AS $row) {
			$_heuArr[$row->id] = $this->count_heuristic($b, $row->id);
		}
		$_heuArr['QP'] = $this->count_heuristic_QP($coords[0], $coords[1], $row->id);



		//initialize the array for storing
		$S = array();//the nearest path with its parent and weight
		$Q = array();//the left nodes without the nearest path
		foreach(array_keys($_distArr) as $val) $Q[$val] = 99999;
		$Q[$a] = 0;
		
		while(!empty($Q)){
			$min = array_search(min($Q), $Q);
			if($min == $b) break;
			foreach($_distArr[$min] as $key=>$val){
				if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
					$Q[$key] = $Q[$min] + $val + $_heuArr[$min];
					$S[$key] = array($min, $Q[$key]);
				}
			}
			unset($Q[$min]);		
		}


		


		if (!array_key_exists($b, $S)) {
			return;
		}
				

		$path = array();
		$pos = $b;
		while($pos != $a){
			$path[] = $pos;
			$pos = $S[$pos][0];

		}


		$path[] = $a;


		//die();

		$path = array_reverse($path);

		$road = array();

		array_push($road, array('lat' => $coords[0], 'lng' => $coords[1]));
		array_push($road, array('lat' => $x, 'lng' => $y));

		$temp = false;
		if($path[1] == $neighbor->id_marker1){
			foreach ($this->webmodel->select_all_path_where_id_neighbor_DESC($neighbor->id) as $row) {
				if($temp == true)
					array_push($road, array('lat' => $row->lat, 'lng' => $row->lng));
				if($row->id == $marker2->id)
					$temp = true;
			}
		} else {
			foreach ($this->webmodel->select_all_path_where_id_neighbor($neighbor->id) as $row) {
				if($temp == true)
					array_push($road, array('id' => $row->id, 'lat' => $row->lat, 'lng' => $row->lng));
				if($row->id == $marker2->id)
					$temp = true;
				
			}
		}
		


		unset($path[0]);
		$prev = null;
		foreach ($path as $row) {
			if($prev != null){
				if(!empty($this->webmodel->check_traverse($prev, $row))){
					foreach($this->webmodel->select_all_path_where_id_marker1_marker2($prev, $row, "ASC") AS $value){
						array_push($road, array('id' => $value->id_path, 'lat' => $value->lat, 'lng' => $value->lng));
					}
				} else {
					foreach($this->webmodel->select_all_path_where_id_marker1_marker2_DESC($prev, $row) AS $value){
						array_push($road, array('id' => $value->id_path, 'lat' => $value->lat, 'lng' => $value->lng));
					}
				}
			}
			$prev = $row;
		}

		$qp = array('lat' => $coords[0], 'lng' => $coords[1]);

		$dist = 0;
		$temp = null;
		foreach ($path as $key) {
			if($temp != null)
				$dist += $this->count_distance($temp, $key);
			$temp = $key;
		}

		return array($road , $dist);
		// $data['query_point'] = $qp;
		// $data['query_coords'] = $query_coords;
		// $data['index_step'] = 99;
		// //$data['marker'] = $this->webmodel->select_all_marker();
		// $data['generator'] = $this->webmodel->select_generator_where_id_type($type_point);

		// $data['neighbor'] = $this->webmodel->select_all_neighbor();
		// $data['path_selected'] = $road;
		// $this->load->view('maps', $data);
	}

	public function search_generator_test($coords, $id_type){		
		$nearestPath1 = INF;
		$nearestPath2 = INF;
		$marker1 = null;
		$marker2 = null;
		foreach($this->webmodel->select_all_path_join_neighbor() AS $row){
			$distance =  sqrt(pow(($coords[0] - ($row->lat)) , 2) + pow(($coords[1] - $row->lng) , 2))*111.32;
			if($distance < $nearestPath2){
				$nearestPath2 = $distance;
				$marker2 = $row;
			}
			

		}

		$neighbor = $this->webmodel->select_neighbor_where_path($marker2->id);


		$prevP = null;
		foreach ($this->webmodel->select_all_path_where_id_neighbor($neighbor->id) as $row) {
			if($prevP != null){
				if($marker2->id == $row->id){
					$marker1 = $prevP;
					break;
				}
				elseif($prevP->id == $marker2->id){
					$marker1 = $row;
					break;
				}
			}
			$prevP = $row;
		}
		$nearestPath1 = sqrt(pow(($coords[0] - ($marker1->lat)) , 2) + pow(($coords[1] - $marker1->lng) , 2))*111.32;

		


		// 1 = 26 = -6.947966 107.633865, 2 27 = -6.947764 107.634544, 3 = QP = -6.948145450241897 107.6342122326605
		$x1=$marker1->lat; $y1=$marker1->lng;
		$x2=$marker2->lat; $y2=$marker2->lng; 
		$x3=$coords[0]; $y3=$coords[1];
	    $px = $x2-$x1; $py = $y2-$y1; $dAB = $px*$px + $py*$py;
	    $u = (($x3 - $x1) * $px + ($y3 - $y1) * $py) / $dAB;
	    $x = $x1 + $u * $px; $y = $y1 + $u * $py;
	    
		

		//xy = -6.947885689446784  107.63413495478036

	    

		$_distArr = array();
		foreach ($this->webmodel->select_all_path_group_neighbor_gb($id_type) AS $row) {
			$polyline_length = $row->distance_neighbor;
			$_distArr[$row->id_marker1][$row->id_marker2] = $polyline_length;
			$_distArr[$row->id_marker2][$row->id_marker1] = $polyline_length;
		}
		$_distArr['QP'][$neighbor->id_marker1] = $nearestPath1;
		$_distArr[$neighbor->id_marker1]['QP'] = $nearestPath1;
		$_distArr['QP'][$neighbor->id_marker2] = $nearestPath2;
		$_distArr[$neighbor->id_marker2]['QP'] = $nearestPath2;


		//the start and the end
		$a = 'QP';
		if($id_type == 1)
			$b = $neighbor->id_generator_h;
		elseif($id_type == 2)
			$b = $neighbor->id_generator_ps;
		else
			$b = $neighbor->id_generator_fs;

		//initialize the array for storing
		$S = array();//the nearest path with its parent and weight
		$Q = array();//the left nodes without the nearest path
		foreach(array_keys($_distArr) as $val) $Q[$val] = INF;

		$Q[$a] = 0;

		while(!empty($Q)){
			$min = array_search(min($Q), $Q);
			if($min == $b) break;
			foreach($_distArr[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
					$Q[$key] = $Q[$min] + $val;
					$S[$key] = array($min, $Q[$key]);
			}
			unset($Q[$min]);		
		}

		if (!array_key_exists($b, $S)) {
			return;
		}
				

		$path = array();
		$pos = $b;
		while($pos != $a){
			$path[] = $pos;
			$pos = $S[$pos][0];

		}


		$path[] = $a;


		//die();

		$path = array_reverse($path);

		$road = array();

		array_push($road, array('lat' => $coords[0], 'lng' => $coords[1]));
		array_push($road, array('lat' => $x, 'lng' => $y));

		$temp = false;
		if($path[1] == $neighbor->id_marker1){
			foreach ($this->webmodel->select_all_path_where_id_neighbor_DESC($neighbor->id) as $row) {
				if($temp == true)
					array_push($road, array('lat' => $row->lat, 'lng' => $row->lng));
				if($row->id == $marker2->id)
					$temp = true;
			}
		} else {
			foreach ($this->webmodel->select_all_path_where_id_neighbor($neighbor->id) as $row) {
				if($temp == true)
					array_push($road, array('id' => $row->id, 'lat' => $row->lat, 'lng' => $row->lng));
				if($row->id == $marker2->id)
					$temp = true;
				
			}
		}
		


		unset($path[0]);
		$prev = null;
		foreach ($path as $row) {
			if($prev != null){
				if(!empty($this->webmodel->check_traverse($prev, $row))){
					foreach($this->webmodel->select_all_path_where_id_marker1_marker2($prev, $row, "ASC") AS $value){
						array_push($road, array('id' => $value->id_path, 'lat' => $value->lat, 'lng' => $value->lng));
					}
				} else {
					foreach($this->webmodel->select_all_path_where_id_marker1_marker2_DESC($prev, $row) AS $value){
						array_push($road, array('id' => $value->id_path, 'lat' => $value->lat, 'lng' => $value->lng));
					}
				}
			}
			$prev = $row;
		}

		$qp = array('lat' => $coords[0], 'lng' => $coords[1]);

		$dist = 0;
		$temp = null;
		foreach ($path as $key) {
			if($temp != null)
				$dist += $this->count_distance($temp, $key);
			$temp = $key;
		}

		return array($road , $dist);
		// $data['query_point'] = $qp;
		// $data['query_coords'] = $query_coords;
		// $data['index_step'] = 99;
		// //$data['marker'] = $this->webmodel->select_all_marker();
		// $data['generator'] = $this->webmodel->select_generator_where_id_type($type_point);

		// $data['neighbor'] = $this->webmodel->select_all_neighbor();
		// $data['path_selected'] = $road;
		// $this->load->view('maps', $data);
	}

	public function search_generator(){
		$type_point = $this->input->post('type_point');
		$query_coords = $this->input->post('query_coords');
		$coords = explode(" ", $query_coords);
		$poly = $this->create_mbr($type_point);
		$temp_arr = array();
		foreach($poly AS $points){
			array_push($temp_arr, $this->webmodel->check_contains($points, $query_coords));
		}

		$temp = null;
		foreach ($temp_arr as $row) {
			if($row->contain == 1)
				$temp = $temp . "| NVP(" . $row->gid . ") | ";
		}
		if($temp == null)
			$fh = "No NVP contains Q with lat: " . round($coords[0] , 6, PHP_ROUND_HALF_DOWN). " lng: " . round($coords[1] , 6, PHP_ROUND_HALF_DOWN);
		else
			$fh = $temp;

		$flash_data = 
		 "<div class='alert alert-success' style='height:30'>
				<span class='fa fa-info-circle' aria-hidden='true'></span>
				<strong>NVP Contains Query Point</strong><br>
				" . $fh ."
				</div>";
		$this->session->set_flashdata('notif',$flash_data);
		$data['query_coords'] = $query_coords;
		$data['selected_type_point'] = $this->input->post('type_point');;
		$data['index_step'] = 3;
		$data['marker'] = $this->webmodel->select_all_marker_not_generator();
		$data['generator'] = $this->webmodel->select_generator_where_id_type($type_point);
		$data['neighbor'] = $this->webmodel->select_all_neighbor();
		$data['poly'] = $this->create_mbr($type_point);
		$this->load->view('maps', $data);
	}



	public function generate_distance(){
		@set_time_limit(-1);
		foreach($this->webmodel->select_all_neighbor() as $row){
			$temp = $this->count_distance_path($row->id_marker1, $row->id_marker2);
			$this->webmodel->update_distance($temp, $row->id);
		}

		// foreach($this->webmodel->select_all_neighbor() as $row){
		// 	$temp1 = $this->count_distance($row->id_marker1, $row->id_marker2);
		// 	$this->webmodel->update_distance($temp1, $row->id);
		// }
	}


	public function generate_distance_border($id_type){
		foreach($this->webmodel->select_all_neighbor_border_where_type($id_type) as $row){
			$temp = $this->count_distance_path($row->id_marker1, $row->id_marker2);
			$this->webmodel->update_distance($temp, $row->id);
		}
	}

	public function count_heuristic($id_marker1, $id_marker2){
		$marker1 = $this->webmodel->select_marker_where_id($id_marker1);
		$marker2 = $this->webmodel->select_marker_where_id($id_marker2);
		$heuristic_length = sqrt(pow(($marker1->lat-($marker2->lat)) , 2) + pow(($marker1->lng - $marker2->lng) , 2))*111.32;

		return $heuristic_length;
	}

	public function count_heuristic_QP($qp_lat, $qp_lng, $id_marker1){
		$marker1 = $this->webmodel->select_marker_where_id($id_marker1);
		$heuristic_length = sqrt(pow(($qp_lat-($marker1->lat)) , 2) + pow(($qp_lng - $marker1->lng) , 2))*111.32;

		return $heuristic_length;
	}

 	public function count_distance($id_marker1, $id_marker2){
		$polyline_length = 0; $previousValue = null;
		foreach ($this->webmodel->select_all_path_where_id_marker1_marker2($id_marker1, $id_marker2, "ASC") AS $row) {
			if($previousValue != null){
				// $temp = sqrt(pow(($previousValue->lat-($row->lat)) , 2) + pow(($previousValue->lng - $row->lng) , 2))*111.32;
				// $polyline_length += $temp;
				$polyline_length += $row->distance_path;
			}
			$previousValue = $row;
		}

		return $polyline_length;
	}

	public function count_distance_path($id_marker1, $id_marker2){
		$polyline_length = 0; $previousValue = null;
		foreach ($this->webmodel->select_all_path_where_id_marker1_marker2($id_marker1, $id_marker2, "ASC") AS $row) {
			if($previousValue != null){
				$temp = sqrt(pow(($previousValue->lat-($row->lat)) , 2) + pow(($previousValue->lng - $row->lng) , 2))*111.32;
				$polyline_length += $temp;
				// echo $row->id_path;
				$this->webmodel->update_distance_path($temp, $row->id_path);
			}
			$previousValue = $row;
		}

		return $polyline_length;
	}

	public function compute_knn($coords, $id_type, $k){
		@set_time_limit(-1);

		$list_temporary = array();
		$list_permanent = array();

		$temp = array();
		$temp_w = array();
		$nvd = array();

		$nearestPath1 = INF;
		$nearestPath2 = INF;
		$marker1 = null;
		$marker2 = null;
		foreach($this->webmodel->select_all_path_join_neighbor() AS $row){
			$distance =  sqrt(pow(($coords[0] - ($row->lat)) , 2) + pow(($coords[1] - $row->lng) , 2))*111.32;
			if($distance < $nearestPath2){
				$nearestPath2 = $distance;
				$marker2 = $row;
			}
		}

		$neighbor = $this->webmodel->select_neighbor_where_path($marker2->id);

		$prevP = null;
		foreach ($this->webmodel->select_all_path_where_id_neighbor($neighbor->id) as $row) {
			if($prevP != null){
				if($marker2->id == $row->id){
					$marker1 = $prevP;
					break;
				}
				elseif($prevP->id == $marker2->id){
					$marker1 = $row;
					break;
				}
			}
			$prevP = $row;
		}
		$nearestPath1 = sqrt(pow(($coords[0] - ($marker1->lat)) , 2) + pow(($coords[1] - $marker1->lng) , 2))*111.32;

		foreach ($this->webmodel->select_generator_where_id_type($id_type) AS $row_g){
			$temp['QP'][$row_g->id_marker] = "N";
		}

		foreach ($this->webmodel->select_all_marker() AS $row) {
			foreach ($this->webmodel->select_generator_where_id_type($id_type) AS $row_g){
				$temp[$row->id][$row_g->id_marker] = "N";
			}
		}


		foreach ($this->webmodel->select_generator_where_id_type($id_type) AS $row){
			$id = $row->id_marker;
			$data = $row->id_marker;
			$list_temporary[$id][$data] = 0;
			$w = $this->fibonacciheap->insert(0, $id, $data);
			$temp_w[$id][$data] = $w;
			$temp[$row->id_marker][$row->id_marker] = "N";
		}


		while($this->fibonacciheap->size() != 0){

			$v = $this->fibonacciheap->extractMin();
			$temp[$v->id][$v->data] = "Y";
			$list_permanent[$v->id][$v->data] = $v->key;
			if($v->id == "QP"){
				// echo "<pre>";
				// echo "===========================================";
				// print_r($list_permanent["QP"]);
				// echo "===========================================";
				// echo "</pre>";
			}
			unset($list_temporary[$v->id][$v->data]);


			if($v->id == $neighbor->id_marker1)
				$dist_QP = $nearestPath1;
			elseif($v->id == $neighbor->id_marker2)
				$dist_QP = $nearestPath2;

			if($v->id == $neighbor->id_marker1 || $v->id == $neighbor->id_marker2){

				$idw = 'QP';

				if($temp[$idw][$v->data] == "N"){
					// echo "<pre>";
					// print_r(array("vid" => $v->id, "vdata" => $v->data));
					$total = $v->key + $dist_QP;
					// print_r("total = " . $total . ", v->key = " . $v->key . "<br>");
					if(isset($list_temporary[$idw][$v->data]) == true && $list_temporary[$idw][$v->data] > $total){
						// echo "1";
						$list_temporary[$idw][$v->data] = $total;
						print_r($list_temporary[$idw]);
						$this->fibonacciheap->decreaseKey($temp_w[$idw][$v->data], $total);
					}

					if(isset($list_temporary[$idw][$v->data]) == false){
						// echo "2";
						if(isset($list_temporary[$idw]) == false)
							$c_list_temp = 0;
						else
							$c_list_temp = count($list_temporary[$idw]);

						if(isset($list_permanent[$idw]) == false)
							$c_list_perm = 0;
						else
							$c_list_perm = count($list_permanent[$idw]);

						if($c_list_temp + $c_list_perm < $k){

							$list_temporary[$idw][$v->data] = $total;
							// print_r($list_temporary[$idw]);
							$w = $this->fibonacciheap->insert($total, $idw, $v->data);
							$temp_w[$idw][$v->data] = $w;
						}
					}

					// echo "</pre>";
					// echo "<pre>";
					// print_r(count($list_temporary) + count($list_permanent));
					// echo "</pre>";
					// die();

				}
			}

			foreach($this->webmodel->select_neighbor_where_id_marker($v->id) AS $row){
				if($v->id != $row->id_marker1){
					$idw = $row->id_marker1;
				} else {
					$idw = $row->id_marker2;
				}

				if($temp[$idw][$v->data] == "N"){

					$total = $v->key + $row->distance;
					if(isset($list_temporary[$idw][$v->data]) == true && $list_temporary[$idw][$v->data] > $total){
						$list_temporary[$idw][$v->data] = $total;
						$this->fibonacciheap->decreaseKey($temp_w[$idw][$v->data], $total);
					}

					if(isset($list_temporary[$idw][$v->data]) == false){
						if(isset($list_temporary[$idw]) == false)
							$c_list_temp = 0;
						else
							$c_list_temp = count($list_temporary[$idw]);

						if(isset($list_permanent[$idw]) == false)
							$c_list_perm = 0;
						else
							$c_list_perm = count($list_permanent[$idw]);

						if($c_list_temp + $c_list_perm < $k){
							$list_temporary[$idw][$v->data] = $total;
							$w = $this->fibonacciheap->insert($total, $idw, $v->data);
							$temp_w[$idw][$v->data] = $w;
						}
					}

					
					// echo "<pre>";
					// print_r(count($list_temporary) + count($list_permanent));
					// echo "</pre>";
					// die();

				}
			}
		}
		// echo "hay" . $list_permanent['QP'][array_keys($list_permanent['QP'])[$k-1]];
		$data = $this->search_djikstra($coords, array_keys($list_permanent['QP'])[$k-1], $list_permanent['QP'][array_keys($list_permanent['QP'])[$k-1]]);
		// echo "<pre>boys";
		// print_r($list_permanent['QP']);
		// echo "</pre>";
		return $data;
	}

	public function parallel_djikstra($id_type){
		@set_time_limit(-1);
		$temp = array();
		$temp_w = array();
		$nvd = array();

		foreach ($this->webmodel->select_all_marker() AS $row) {
			$temp[$row->id] = array("key" => 99999, "id" => $row->id, "data" => null, "marker" => "N");
		}

		foreach ($this->webmodel->select_generator_where_id_type($id_type) AS $row) {
			$id = $row->id_marker;
			$data = $row->id_marker;
			$this->fibonacciheap->insert(0, $id, $data);
			$temp[$row->id_marker] = array("key" => 0, "id" => $row->id_marker, "data" => $row->id_marker, "marker" => "N");	
		}

		while($this->fibonacciheap->size() != 0){
			$v = $this->fibonacciheap->extractMin();

			$temp[$v->id]['marker'] = "Y";
			if($v->id == 48){
				echo "<pre><b>";
				print_r("STOP!!!");
				echo "</b></pre>";
			}
				
			foreach($this->webmodel->select_neighbor_where_id_marker($v->id) As $row){
				if($v->id != $row->id_marker1){
					$idw = $row->id_marker1;
				} else {
					$idw = $row->id_marker2;
				}

				if($idw == 48){
					if($temp[$idw]['marker'] == "N"){

						$total = $v->key + $row->distance;
						if($temp[$idw]['key'] == 99999){
							$temp[$idw]['data'] = $v->data;
							$temp[$idw]['key'] = $total;
							$w = $this->fibonacciheap->insert($temp[$idw]['key'], $temp[$idw]['id'], $temp[$idw]['data']);
							$temp_w[$idw] = array("w" => $w);
							echo "<pre><b>";
							print_r('=====' . 'Generator = ' . $v->id . ', data = ' . $v->data . ', vdistance = ' . $v->key . ' idw = ' . $idw . ' distance ' . $row->distance);
							echo "</b></pre>";

							echo "<pre><b>";
							print_r($total);
							echo "</b></pre>";

						}
						if($temp[$idw]['key'] > $total){
							echo "<pre><b>";

							echo "MASUK KE ELSE";
							print_r('=====' . 'Generator = ' . $v->id . ', data = ' . $v->data . ', Neighbour = ' . $row->id . ' idw = ' . $idw . ' distance ' . $row->distance);
							echo "</b></pre>";

							echo "<pre><b>";
							print_r($total);
							echo "TUTUP ELSE";
							echo "</b></pre>";
							$temp[$idw]['data'] = $v->data;
							$temp[$idw]['key'] = $total;
							$this->fibonacciheap->decreaseKey($temp_w[$idw]['w'], $total);
						}

						array_push($nvd, array("generator" => $v->data, "id_marker1" => $idw, "id_marker2" => $v->id));
						$this->webmodel->update_generator($id_type, $v->data, $idw, $v->id);
					}
				} else {
					if($temp[$idw]['marker'] == "N"){

						$total = $v->key + $row->distance;
						if($temp[$idw]['key'] == 99999){
							$temp[$idw]['data'] = $v->data;
							$temp[$idw]['key'] = $total;
							$w = $this->fibonacciheap->insert($temp[$idw]['key'], $temp[$idw]['id'], $temp[$idw]['data']);
							$temp_w[$idw] = array("w" => $w);
							// echo "<pre><b>";
							// print_r('=====' . 'Generator = ' . $v->id . ', Neighbour = ' . $row->id . ' idw = ' . $idw . ' distance ' . $row->distance);
							// echo "</b></pre>";

							// echo "<pre><b>";
							// print_r($temp_w);
							// echo "</b></pre>";

						}
						if($temp[$idw]['key'] > $total){
							$temp[$idw]['data'] = $v->data;
							$temp[$idw]['key'] = $total;
							$this->fibonacciheap->decreaseKey($temp_w[$idw]['w'], $total);
						}

						array_push($nvd, array("generator" => $v->data, "id_marker1" => $idw, "id_marker2" => $v->id));
						$this->webmodel->update_generator($id_type, $v->data, $idw, $v->id);
					}
				}


			}

		}

	}



	public function create_convex_hull($id_type)
	{
		$ch = array();
		foreach($this->webmodel->select_generator_where_id_type($id_type) AS $row_generator){
			$points = array();
			/* Ensure point doesn't rotate the incorrect direction as we process the hull halves */
			foreach($this->webmodel->select_neighbor_where_id_generator($row_generator->id) AS $row){
					array_push($points, array(
						"id_generator" => $row->id_generator,
						"0" => $row->lat,
						"1" => $row->lng,
						"id" => $row->name
					));

			};

			$cross = function($o, $a, $b) {
				return ($a[0] - $o[0]) * ($b[1] - $o[1]) - ($a[1] - $o[1]) * ($b[0] - $o[0]);
			};
			$pointCount = count($points);
			sort($points);
			if ($pointCount > 1) {

				$n = $pointCount;
				$k = 0;
				$h = array();
	 
				/* Build lower portion of hull */
				for ($i = 0; $i < $n; ++$i) {
					while ($k >= 2 && $cross($h[$k - 2], $h[$k - 1], $points[$i]) <= 0)
						$k--;
					$h[$k++] = $points[$i];

				}


	 
				/* Build upper portion of hull */
				for ($i = $n - 2, $t = $k + 1; $i >= 0; $i--) {
					while ($k >= $t && $cross($h[$k - 2], $h[$k - 1], $points[$i]) <= 0)
						$k--;
					$h[$k++] = $points[$i];

				}

				/* Remove all vertices after k as they are inside of the hull */
				if ($k > 1) {
					/* If you don't require a self closing polygon, change $k below to $k-1 */
					$h = array_splice($h, 0, $k); 
				}

				array_push($ch, $h);

			}
			else if ($pointCount <= 1)
			{
				array_push($ch, $points);
			}
			else
			{
				array_push($ch, null);
			}
		}

		// echo "<pre>";
		// print_r($ch);
		// echo "</pre>";
		return $ch;
		
	}

	public function create_mbr($id_type){
		$mbr = array();
		foreach ($this->webmodel->select_generator_where_id_type($id_type) AS $key => $row_generator) {
			$minX = INF;
			$minY = INF;
			$maxX = -INF;
			$maxY = -INF;
			foreach($this->webmodel->select_neighbor_where_id_generator($row_generator->id) AS $row){


				if($minX > $row->lat)
					$minX = $row->lat;
				if($maxX < $row->lat)
					$maxX = $row->lat;
				if($minY > $row->lng)
					$minY = $row->lng;
				if($maxY < $row->lng)
					$maxY = $row->lng;
			}
			// array_push($mbr, array(
			// 	"id_generator" => $row_neighbor->id,
			// 	"minXminY" => $minX . " " . $minY,
			// 	"minXmaxY" => $minX . " " . $maxY,
			// 	"maxXmaxY" => $maxX . " " . $maxY,
			// 	"maxXminY" => $maxX . " " . $minY,
			// 	"minXminY" => $minX . " " . $minY
			// ));
			$mbr[$key][0] = array("id_generator" => $row_generator->id, "0" => $minX, "1" => $minY);
			$mbr[$key][1] = array("id_generator" => $row_generator->id, "0" => $minX, "1" => $maxY);
			$mbr[$key][2] = array("id_generator" => $row_generator->id, "0" => $maxX, "1" => $maxY);
			$mbr[$key][3] = array("id_generator" => $row_generator->id, "0" => $maxX, "1" => $minY);
			//$mbr[$key][4] = array("id_generator" => $row_generator->id, "0" => $minX, "1" => $minY);

		}
		// echo "<pre>";
		// print_r($mbr);
		// echo "</pre>";
		//die();
		return $mbr;
	}

	public function search_djikstra($coords, $endpoint, $total_distance_counted = INF){
		$_distArr = array();
		$nearestPath1 = INF;
		$nearestPath2 = INF;
		$marker1 = null;
		$marker2 = null;
		foreach($this->webmodel->select_all_path_join_neighbor() AS $row){
			$distance =  sqrt(pow(($coords[0] - ($row->lat)) , 2) + pow(($coords[1] - $row->lng) , 2))*111.32;
			if($distance < $nearestPath2){
				$nearestPath2 = $distance;
				$marker2 = $row;
			}
			

		}

		$neighbor = $this->webmodel->select_neighbor_where_path($marker2->id);


		$prevP = null;
		foreach ($this->webmodel->select_all_path_where_id_neighbor($neighbor->id) as $row) {
			if($prevP != null){
				if($marker2->id == $row->id){
					$marker1 = $prevP;
					break;
				}
				elseif($prevP->id == $marker2->id){
					$marker1 = $row;
					break;
				}
			}
			$prevP = $row;
		}
		$nearestPath1 = sqrt(pow(($coords[0] - ($marker1->lat)) , 2) + pow(($coords[1] - $marker1->lng) , 2))*111.32;

		


		// 1 = 26 = -6.947966 107.633865, 2 27 = -6.947764 107.634544, 3 = QP = -6.948145450241897 107.6342122326605
		$x1=$marker1->lat; $y1=$marker1->lng;
		$x2=$marker2->lat; $y2=$marker2->lng; 
		$x3=$coords[0]; $y3=$coords[1];
	    $px = $x2-$x1; $py = $y2-$y1; $dAB = $px*$px + $py*$py;
	    $u = (($x3 - $x1) * $px + ($y3 - $y1) * $py) / $dAB;
	    $x = $x1 + $u * $px; $y = $y1 + $u * $py;

	    $_distArr = array();
		foreach ($this->webmodel->select_all_path_group_neighbor() AS $row) {
			$polyline_length = $row->distance_neighbor;
			$_distArr[$row->id_marker1][$row->id_marker2] = $polyline_length;
			$_distArr[$row->id_marker2][$row->id_marker1] = $polyline_length;
		}
		$_distArr['QP'][$neighbor->id_marker1] = $nearestPath1;
		$_distArr[$neighbor->id_marker1]['QP'] = $nearestPath1;
		$_distArr['QP'][$neighbor->id_marker2] = $nearestPath2;
		$_distArr[$neighbor->id_marker2]['QP'] = $nearestPath2;





		//the start and the end
		$a = 'QP';
		$b = $endpoint;
		$endpoint_name = $this->webmodel->select_generator_where_id_marker($endpoint)->gname;

		//initialize the array for storing
		$S = array();//the nearest path with its parent and weight
		$Q = array();//the left nodes without the nearest path
		foreach(array_keys($_distArr) as $val) $Q[$val] = 99999;
		$Q[$a] = 0;

		while(!empty($Q)){
			$min = array_search(min($Q), $Q);
			if($min == $b) break;
				 foreach($_distArr[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
					$Q[$key] = $Q[$min] + $val;
					$S[$key] = array($min, $Q[$key]);
			}
			unset($Q[$min]);		
		}
		


		if (!array_key_exists($b, $S)) {
			return;
		}
				

		$path = array();
		$pos = $b;
		while($pos != $a){
			$path[] = $pos;
			$pos = $S[$pos][0];

		}


		$path[] = $a;


		//die();

		$path = array_reverse($path);

		$road = array();

		array_push($road, array('lat' => $coords[0], 'lng' => $coords[1]));
		array_push($road, array('lat' => $x, 'lng' => $y));

		$temp = false;
		if($path[1] == $neighbor->id_marker1){
			foreach ($this->webmodel->select_all_path_where_id_neighbor_DESC($neighbor->id) as $row) {
				if($temp == true)
					array_push($road, array('lat' => $row->lat, 'lng' => $row->lng));
				if($row->id == $marker2->id)
					$temp = true;
			}
		} else {
			foreach ($this->webmodel->select_all_path_where_id_neighbor($neighbor->id) as $row) {
				if($temp == true)
					array_push($road, array('id' => $row->id, 'lat' => $row->lat, 'lng' => $row->lng));
				if($row->id == $marker2->id)
					$temp = true;
				
			}
		}
		


		unset($path[0]);
		$prev = null;
		foreach ($path as $row) {
			if($prev != null){
				if(!empty($this->webmodel->check_traverse($prev, $row))){
					foreach($this->webmodel->select_all_path_where_id_marker1_marker2($prev, $row, "ASC") AS $value){
						array_push($road, array('id' => $value->id_path, 'lat' => $value->lat, 'lng' => $value->lng));
					}
				} else {
					foreach($this->webmodel->select_all_path_where_id_marker1_marker2_DESC($prev, $row) AS $value){
						array_push($road, array('id' => $value->id_path, 'lat' => $value->lat, 'lng' => $value->lng));
					}
				}
			}
			$prev = $row;
		}

		$qp = array('lat' => $coords[0], 'lng' => $coords[1]);

		$dist = 0;
		$temp = null;
		if($total_distance_counted == INF){
			foreach ($path as $key) {
				if($temp != null)
					$dist += $this->count_distance($temp, $key);
				$temp = $key;
			}
			if($path[1] == $neighbor->id_marker1)
				$dist += $nearestPath1;
			elseif($path[1] == $neighbor->id_marker2)
				$dist += $nearestPath2;
		} else {
			$dist = $total_distance_counted;
			
		}
		return array($endpoint_name, $road, $dist);

	}

	public function djikstra($startingpoint, $endpoint){
		@set_time_limit(-1);
		$_distArr = array();
		foreach ($this->webmodel->select_all_path_group_neighbor() AS $row) {
			$polyline_length = $this->count_distance($row->id_marker1, $row->id_marker2);
			$_distArr[$row->id_marker1][$row->id_marker2] = $polyline_length;
			$_distArr[$row->id_marker2][$row->id_marker1] = $polyline_length;
		}


		//the start and the end
		$a = $startingpoint;
		$b = $endpoint;

		//initialize the array for storing
		$S = array();//the nearest path with its parent and weight
		$Q = array();//the left nodes without the nearest path
		foreach(array_keys($_distArr) as $val) $Q[$val] = 99999;
		$Q[$a] = 0;

		while(!empty($Q)){
			$min = array_search(min($Q), $Q);
			if($min == $b) break;
				 foreach($_distArr[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
					$Q[$key] = $Q[$min] + $val;
					$S[$key] = array($min, $Q[$key]);
			}
			unset($Q[$min]);		
		}
		


		if (!array_key_exists($b, $S)) {
			return;
		}
				

		$path = array();
		$pos = $b;
		while($pos != $a){
			$path[] = $pos;
			$pos = $S[$pos][0];

		}


		$path[] = $a;


		//die();

		$path = array_reverse($path);
		return $S[$b][1];;

	}


	public function find_mid_point($points, $totalDistance){
		$midDistance = $totalDistance/2;
		$distance = 0;   
		$start_distance=0;
		$previousValue = null;
		for($i=0;$i<count($points)-1;$i++)   
		{	
			
			if($previousValue != null){
				$id_neighbor = 'NP' . $previousValue . 'NP' . $points[$i];
				
				$start_distance = $this->count_distance($previousValue, $points[$i]);
				if(($start_distance + $distance) < $midDistance)           
					$distance += $start_distance;       
				else
					break;
			}
			else{
				$id_neighbor = 'NP' . $points[0] . 'NP' . $points[1];
			}
			$previousValue = $points[$i];
		}

		$subDistance = $midDistance - $distance;

		$rowPoint1 = $this->webmodel->select_marker_where_id($previousValue);
		$rowPoint2 = $this->webmodel->select_marker_where_id($points[$i]);
		$rowTotalPoint1Point2 = $this->webmodel->select_neighbor_where_id_marker1_marker2($rowPoint1->id, $rowPoint2->id);

		$div = $subDistance/$rowTotalPoint1Point2->distance;

		$temp = $distance;
		$path_prev_value = null;
		if(!empty($this->webmodel->check_traverse($rowPoint1->id, $rowPoint2->id))){
			foreach ($this->webmodel->select_all_path_where_id_marker1_marker2($rowPoint1->id, $rowPoint2->id, "ASC") as $key) {
				if($path_prev_value != null){
					if($temp + $key->distance_path < $midDistance){
						$temp += $key->distance_path;
					}
					else
						break;
				}
				$path_prev_value = $key;
			}
			$path_point1 = $this->webmodel->select_path_where_id($path_prev_value->id_path);
			$path_point2 = $this->webmodel->select_path_where_id($key->id_path);
		} else {
			foreach ($this->webmodel->select_all_path_where_id_marker1_marker2_DESC($rowPoint1->id, $rowPoint2->id, "DESC") as $key) {
				if($path_prev_value != null){
					if($temp + $path_prev_value->distance_path < $midDistance){
						$temp += $path_prev_value->distance_path;
					}
					else
						break;
				} else {
					$temp += 0;
				}

				$path_prev_value = $key;
			}
			$path_point1 = $this->webmodel->select_path_where_id($key->id_path);
			$path_point2 = $this->webmodel->select_path_where_id($path_prev_value->id_path);
		}
		

		

		$subDistance = $midDistance - $temp;
		$div = $subDistance/$path_point2->distance;
		if(!empty($this->webmodel->check_traverse($rowPoint1->id, $rowPoint2->id))){
			$midLat = ($path_point1->lat*(1-$div))+($path_point2->lat*$div);
			$midLng = ($path_point1->lng*(1-$div))+($path_point2->lng*$div);
		} else {
			$midLat = ($path_point2->lat*(1-$div))+($path_point1->lat*$div);
			$midLng = ($path_point2->lng*(1-$div))+($path_point1->lng*$div);
		}

		
		$midData = array(
						'g1'=>$points[0],
						'g2'=>$points[count($points)-1],
						'point1' => $previousValue,
						'point2' => $points[$i],
						'path_point1' =>$path_prev_value->id_path,
						'path_point2' => $key->id_path,
						'lat'=>$midLat,
						'lng'=>$midLng,
						'total_distance' => $totalDistance
						);
		return (object) $midData;
	}


}
