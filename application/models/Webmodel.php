<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Webmodel extends CI_Model {

		public function __construct()
		{
			parent::__construct();
			$this->load->database();
		}

		function test()
		{
			return 'Data untuk test model';
		}


		public function select_all_generator(){
			$query = $this->db->query('SELECT * FROM generator
										INNER JOIN marker ON generator.id_marker = marker.id;');
			return $query->result();
		}

		public function select_all_generator_group_by_where_type($id_type){
			$query = $this->db->query('SELECT * FROM generator WHERE id_type = "'.$id_type.'" GROUP BY id_type;');
			return $query->row();
		}

		public function select_generator_where_id_marker($id_marker){
			$query = $this->db->query('	SELECT 
											*
										FROM generator
										WHERE id_marker = "'.$id_marker.'";');
			return $query->row();
		}

		public function select_generator_where_id_type($id_type){
			$query = $this->db->query('	SELECT 
											*
										FROM generator
										INNER JOIN marker ON generator.id_marker = marker.id
										WHERE id_type = "'.$id_type.'";');
			return $query->result();
		}

		public function select_all_adjacency_where_id_type($id_type){
			$query = $this->db->query('	SELECT 
											*
										FROM adjacency
										WHERE id_type = "'.$id_type.'";');
			return $query->result();
		}

		public function select_all_patha(){
			$query = $this->db->get('path');
			return $query;
		}

		public function select_all_path(){
			$query = $this->db->query('SELECT * FROM path;');
			return $query->result();
		}

		public function select_all_path_join_neighbor(){
			$query = $this->db->query('SELECT 
											neighbor.id_marker1,
											neighbor.id_marker2,
											neighbor.distance,
											neighbor.id_generator_h,
											neighbor.id_generator_ps,
											neighbor.id_generator_fs,
											path.id,
											path.lat,
											path.lng
									 FROM path JOIN neighbor on neighbor.id = path.id_neighbor WHERE neighbor.is_visible = 1;');
			return $query->result();
		}

		public function select_all_neighbor(){
			$query = $this->db->query('SELECT * FROM neighbor WHERE is_visible = 1;');
			return $query->result();
		}

		public function select_all_neighbor_border(){
			$query = $this->db->query('	SELECT 
											neighbor.id,
											neighbor.id_marker1,
											neighbor.id_marker2
										FROM 
											neighbor
										JOIN 
												marker ON neighbor.id_marker1 = marker.id 
										WHERE 
												is_visible = 1 AND marker.is_border != 0;');
			return $query->result();
		}

		public function select_all_neighbor_border_where_type($id_type){
			$query = $this->db->query('	SELECT 
											neighbor.id,
											neighbor.id_marker1,
											neighbor.id_marker2
										FROM 
											neighbor
										JOIN 
												marker ON neighbor.id_marker1 = marker.id 
										WHERE 
												is_visible = 1 AND marker.is_border = "'.$id_type.'";');
			return $query->result();
		}

		public function select_neighbor_where_id_marker1_marker2($id_marker1, $id_marker2){
			$query = $this->db->query('	SELECT 
											* 
										FROM neighbor 
										WHERE
											neighbor.id_marker1 = "'.$id_marker1.'" AND neighbor.id_marker2 = "'.$id_marker2.'" 
										OR
											neighbor.id_marker2 = "'.$id_marker1.'" AND neighbor.id_marker1 = "'.$id_marker2.'";');
			return $query->row();
		}

		public function select_neighbor_where_id_marker($id_marker){
			$query = $this->db->query('	SELECT 
											* 
										FROM neighbor 
										WHERE
											(neighbor.id_marker1 = "'.$id_marker.'" 
										OR
											neighbor.id_marker2 = "'.$id_marker.'")
										AND
											is_visible = 1;');
			return $query->result();
		}

		public function select_neighbor_where_id_marker_adjacency($id_marker, $id_type){
			$query = $this->db->query('	SELECT 
											* 
										FROM neighbor 
										WHERE
											neighbor.is_visible = 1 AND (neighbor.id_marker1="'.$id_marker.'" OR neighbor.id_marker2="'.$id_marker.'");');
			return $query->result();
		}

		public function select_adjacency_where_id_generator($id_generator){
			$query = $this->db->query('	SELECT 
											* 
										FROM adjacency 
										WHERE
											adjacency.id_generator1 = "'.$id_generator.'"
										OR
											adjacency.id_generator2 = "'.$id_generator.'"
										GROUP BY id_group;');
			return $query->result();
		}

		public function select_adjacency_where_id_type($id_type){
			$query = $this->db->query('	SELECT 
											* 
										FROM adjacency 
										WHERE
											adjacency.id_type = "'.$id_type.'";');
			return $query->result();
		}



		public function select_neighbor_where_path($id_path){
			$query = $this->db->query('	SELECT 
											neighbor.id,
											neighbor.id_marker1,
											neighbor.id_marker2,
											neighbor.distance,
											neighbor.id_generator_h,
											neighbor.id_generator_ps,
											neighbor.id_generator_fs,
											path.id AS id_path,
											path.lat,
											path.lng
										FROM 
											neighbor
										INNER JOIN path ON path.id_neighbor = neighbor.id
										WHERE 
											path.id = "' . $id_path . '" 
										AND
											is_visible = 1;');
			return $query->row();
		}

		public function select_all_path_where_id_neighbor($id_neighbor){
			$query = $this->db->query('SELECT * FROM path WHERE id_neighbor = "'.$id_neighbor.'";');
			return $query->result();
		}

		public function select_all_path_where_id_neighbor_DESC($id_neighbor){
			$query = $this->db->query('SELECT * FROM path WHERE id_neighbor = "'.$id_neighbor.'" ORDER BY id DESC;');
			return $query->result();
		}


		public function select_all_path_where_id_marker1_marker2($id_marker1, $id_marker2, $sort){
			$query = $this->db->query('	SELECT
											path.id AS id_path,
											path.distance AS distance_path,
											neighbor.id,
											path.lat,
											path.lng,
											neighbor.distance AS distance_neighbor,
											neighbor.id_marker1,
											neighbor.id_marker2
										FROM path
										INNER JOIN neighbor ON path.id_neighbor = neighbor.id
										WHERE 
											neighbor.id_marker1 = "'.$id_marker1.'" AND neighbor.id_marker2 = "'.$id_marker2.'" 
										OR
											neighbor.id_marker2 = "'.$id_marker1.'" AND neighbor.id_marker1 = "'.$id_marker2.'"
										ORDER BY id_marker1 '.$sort.';');
			return $query->result();
		}

		public function select_all_path_where_id_marker1_marker2_DESC($id_marker1, $id_marker2){
			$query = $this->db->query('	SELECT
											path.id AS id_path,
											path.distance AS distance_path,
											neighbor.id,
											path.lat,
											path.lng,
											neighbor.distance AS distance_neighbor,
											neighbor.id_marker1,
											neighbor.id_marker2
										FROM path
										INNER JOIN neighbor ON path.id_neighbor = neighbor.id
										WHERE 
											neighbor.id_marker1 = "'.$id_marker1.'" AND neighbor.id_marker2 = "'.$id_marker2.'" 
										OR
											neighbor.id_marker2 = "'.$id_marker1.'" AND neighbor.id_marker1 = "'.$id_marker2.'"
										ORDER BY id_path DESC;');
			return $query->result();
		}

		public function check_traverse($id_marker1, $id_marker2){
			$query = $this->db->query('	SELECT
											path.id AS id_path,
											path.distance AS distance_path,
											neighbor.id,
											path.lat,
											path.lng,
											neighbor.distance AS distance_neighbor,
											neighbor.id_marker1,
											neighbor.id_marker2
										FROM path
										INNER JOIN neighbor ON path.id_neighbor = neighbor.id
										WHERE 
											neighbor.id_marker1 = "'.$id_marker1.'" AND neighbor.id_marker2 = "'.$id_marker2.'";');
			return $query->row();
		}

		public function select_all_path_group_neighbor(){
			$query = $this->db->query('	SELECT 
											path.id AS id_path,
											path.distance AS distance_path,
											neighbor.id,
											path.lat,
											path.lng,
											neighbor.distance AS distance_neighbor,
											neighbor.id_marker1,
											neighbor.id_marker2,
											neighbor.is_visible
										FROM path 
										INNER JOIN neighbor ON path.id_neighbor = neighbor.id
										WHERE
											is_visible = 1
										GROUP BY path.id_neighbor
										ORDER BY id_marker1 ASC;');
			return $query->result();
		}

		public function select_all_path_group_neighbor_gb($id_type){
			$query = $this->db->query('	SELECT
				path.id AS id_path,
											path.distance AS distance_path,
											neighbor.id,
											path.lat,
											path.lng,
											neighbor.id,
											neighbor.distance AS distance_neighbor,
											neighbor.id_marker1,
											neighbor.id_marker2,
											neighbor.is_visible
										FROM
											path
										INNER JOIN neighbor ON path.id_neighbor = neighbor.id
										INNER JOIN marker ON marker.id = neighbor.id_marker1
										WHERE
											is_visible = 1 AND (marker.is_border = 0 OR marker.is_border = "'.$id_type.'")
										GROUP BY path.id_neighbor
										ORDER BY id_marker1 ASC');
			return $query->result();
		}

		public function select_all_path1($int1, $int2){
			$query = $this->db->query('	SELECT * FROM path 
										INNER JOIN neighbor ON path.id_neighbor = neighbor.id
										WHERE id_int1 = "'.$int1.'" AND id_int2= "'.$int2.'";');
			return $query->result();
		}

		public function select_path_where_id($id){
			$query = $this->db->query('SELECT * FROM path WHERE id = "'.$id.'";');
			return $query->row();
		}

		public function select_marker_where_id($id){
			$query = $this->db->query('SELECT * FROM marker WHERE id = "'.$id.'";');
			return $query->row();
		}

		public function select_marker_where_name($name){
			$query = $this->db->query('SELECT * FROM marker WHERE name = "'.$name.'";');
			return $query->row();
		}

		public function select_all_marker(){
			$query = $this->db->query('SELECT * FROM marker;');
			return $query->result();
		}

		public function select_all_marker_not_generator(){
			$query = $this->db->query('SELECT * FROM marker LEFT JOIN generator ON marker.id = generator.id_marker WHERE generator.id IS NULL;');
			return $query->result();
		}

		public function select_max_marker(){
			$query = $this->db->query('SELECT MAX(id) AS id FROM marker;');
			return $query->row()->id;
		}


		public function select_marker_where_is_border($is_border){
			$query = $this->db->query('	SELECT 
											marker.id,
											marker.name,
											marker.lat,
											marker.lng,
											marker.is_border
										FROM marker
										LEFT JOIN generator ON marker.id = generator.id_marker
										WHERE 
											 generator.id IS NULL AND is_border = "'.$is_border.'";');
			return $query->result();
		}


		public function select_distinct_id_generator_neighbor_h(){
			$query = $this->db->query('	SELECT DISTINCT id_generator_h AS dist FROM neighbor');
			return $query->result();
		}
		public function select_distinct_id_generator_neighbor_ps(){
			$query = $this->db->query('	SELECT DISTINCT id_generator_ps AS dist FROM neighbor');
			return $query->result();
		}
		public function select_distinct_id_generator_neighbor_fs(){
			$query = $this->db->query('	SELECT DISTINCT id_generator_fs AS dist FROM neighbor');
			return $query->result();
		}

		public function select_typepoint_where_type($type){
			$query = $this->db->query('SELECT * FROM typepoint WHERE typepoint.type ="'.$type.'";');
			return $query->row();
		}

		public function select_adjacency_where_id_generator1_generator2($id_generator1, $id_generator2){
			$query = $this->db->query('	SELECT 
											* 
										FROM adjacency 
										WHERE
											adjacency.id_generator1 = "'.$id_generator1.'" AND adjacency.id_generator2 = "'.$id_generator2.'" 
										OR
											adjacency.id_generator2 = "'.$id_generator1.'" AND adjacency.id_generator1 = "'.$id_generator2.'"');
			return $query->row();
		}

		public function empty_border(){
			$this->db->where('is_border', 1);
			$this->db->or_where('is_border', 2);
			$this->db->or_where('is_border', 3);
			$this->db->delete('marker');
		}

		public function insert_adjacency($id_border, $id_generator1, $id_generator2, $id_type, $k){
			$temp = $this->select_adjacency_where_id_generator1_generator2($id_generator1, $id_generator2);
			if($temp != null)
				$k = $temp->id_group;
			$data_adjacency = array (
					'id' => $id_border,
					'id_generator1' => $id_generator1,
			        'id_generator2' => $id_generator2,
			        'id_group' => $k,
			        'id_type' => $id_type
		        ); 	
	        $this->db->insert('adjacency', $data_adjacency);
		}

		public function empty_adjacency(){
			$this->db->where('id_type', 1);
			$this->db->or_where('id_type', 2);
			$this->db->or_where('id_type', 3);
			$this->db->delete('adjacency');
		}

		public function insert_border($id_type, $lat, $lng, $point1, $point2, $path_point1, $path_point2){
			$id = $this->select_max_marker() + 1;
			$data_border = array (
		        'id' => $id,
		        'name' => 'B' . $id,
		        'lat' => $lat,
		        'lng' => $lng,
		        'is_border' => $id_type
	        ); 	
	        $this->db->insert('marker', $data_border);

	        $data_visible = array (
		        'is_visible' => 0
	        );
	        $this->db->where('id', 'NP' . $point1 . 'NP' . $point2);
	        $this->db->or_where('id', 'NP' . $point2 . 'NP' . $point1);
			$this->db->update('neighbor', $data_visible);

			//===============================================================

			$id_neighbor1 = 'NP' . $id . 'NP' . $point1;
			$data_neighbor1 = array (
		        'id' => $id_neighbor1,
		        'id_marker1' => $id,
		        'id_marker2' => $point1,
		        'distance' => 0,
		        'id_generator_h' => 330,
		        'id_generator_ps' => 330,
		        'id_generator_fs' => 330,
		        'from_border' => $id_type,
		        'is_visible' => 1
	        ); 	
	        $this->db->insert('neighbor', $data_neighbor1);

	       

	        //==============================================================

	        $id_neighbor2 = 'NP' . $id . 'NP' . $point2;
	        $data_neighbor2 = array (
		        'id' => $id_neighbor2,
		        'id_marker1' => $id,
		        'id_marker2' => $point2,
		        'distance' => 0,
		        'id_generator_h' => 330,
		        'id_generator_ps' => 330,
		        'id_generator_fs' => 330,
		        'from_border' => $id_type,
		        'is_visible' =>1
	        ); 	
	        $this->db->insert('neighbor', $data_neighbor2);

	       //===============================================================

	       if(!empty($this->webmodel->check_traverse($point1, $point2))){
				$inc = 1;
				if($inc < 10){
	        		$inc = str_pad($inc, 2, "0", STR_PAD_LEFT);
	        	}

				$data_path_border_neighbor1 = array (
					'id' => $id_neighbor1 . '-' . $inc,
					'lat' => $lat,
					'lng' => $lng,
					'distance' => 0,
					'id_neighbor' => $id_neighbor1
				);
				$this->db->insert('path', $data_path_border_neighbor1);

				$temp = false;
	        	foreach($this->select_all_path_where_id_marker1_marker2_DESC($point1, $point2) AS $row){
	        		if($row->id_path == $path_point1){
	        			$temp = true;
	        		}

	        		if($temp == true){
	        			if(++$inc < 10){
			        		$inc = str_pad($inc, 2, "0", STR_PAD_LEFT);
			        	}

			        	$data_path_neighbor1 = array (
				        	'id' => $id_neighbor1 . '-' . $inc,
				        	'lat' => $row->lat,
				        	'lng' => $row->lng,
				        	'distance' => $row->distance_path,
				        	'id_neighbor' => $id_neighbor1
				        );
				        $this->db->insert('path', $data_path_neighbor1);
	        		}
	        	}

	        	$inc = 1;
	        	if($inc < 10){
	        		$inc = str_pad($inc, 2, "0", STR_PAD_LEFT);
	        	}
	        	$data_path_neighbor2 = array (
		        	'id' => $id_neighbor2 . '-' . $inc,
		        	'lat' => $lat,
		        	'lng' => $lng,
		        	'distance' => 0,
		        	'id_neighbor' => $id_neighbor2
		        );
		        $this->db->insert('path', $data_path_neighbor2);

		        $temp = false;
		        foreach($this->select_all_path_where_id_marker1_marker2($point1, $point2, "ASC") AS $row){
	        		if($row->id_path == $path_point2){
	        			$temp = true;
	        		} 

	        		if($temp == true){
	        			if(++$inc < 10){
			        		$inc = str_pad($inc, 2, "0", STR_PAD_LEFT);
			        	}


			        	$data_path_neighbor2 = array (
				        	'id' => $id_neighbor2 . '-' . $inc,
				        	'lat' => $row->lat,
				        	'lng' => $row->lng,
				        	'distance' => $row->distance_path,
				        	'id_neighbor' => $id_neighbor2
				        );
				        $this->db->insert('path', $data_path_neighbor2);
	        		}
	        		
	        	}
        	} else {
        		$inc = 1;
				if($inc < 10){
	        		$inc = str_pad($inc, 2, "0", STR_PAD_LEFT);
	        	}

				$data_path_border_neighbor1 = array (
					'id' => $id_neighbor1 . '-' . $inc,
					'lat' => $lat,
					'lng' => $lng,
					'distance' => 0,
					'id_neighbor' => $id_neighbor1
				);
				$this->db->insert('path', $data_path_border_neighbor1);

				$temp = false;
	        	foreach($this->select_all_path_where_id_marker1_marker2($point1, $point2, "ASC") AS $row){
	        		if($row->id_path == $path_point1){
	        			$temp = true;
	        		}

	        		if($temp == true){
	        			if(++$inc < 10){
			        		$inc = str_pad($inc, 2, "0", STR_PAD_LEFT);
			        	}

			        	$data_path_neighbor1 = array (
				        	'id' => $id_neighbor1 . '-' . $inc,
				        	'lat' => $row->lat,
				        	'lng' => $row->lng,
				        	'distance' => $row->distance_path,
				        	'id_neighbor' => $id_neighbor1
				        );
				        $this->db->insert('path', $data_path_neighbor1);
	        		}
	        	}

	        	$inc = 1;
	        	if($inc < 10){
	        		$inc = str_pad($inc, 2, "0", STR_PAD_LEFT);
	        	}
	        	$data_path_neighbor2 = array (
		        	'id' => $id_neighbor2 . '-' . $inc,
		        	'lat' => $lat,
		        	'lng' => $lng,
		        	'distance' => 0,
		        	'id_neighbor' => $id_neighbor2
		        );
		        $this->db->insert('path', $data_path_neighbor2);

		        $temp = false;
		        foreach($this->select_all_path_where_id_marker1_marker2_DESC($point1, $point2) AS $row){
	        		if($row->id_path == $path_point2){
	        			$temp = true;
	        		} 

	        		if($temp == true){
	        			if(++$inc < 10){
			        		$inc = str_pad($inc, 2, "0", STR_PAD_LEFT);
			        	}


			        	$data_path_neighbor2 = array (
				        	'id' => $id_neighbor2 . '-' . $inc,
				        	'lat' => $row->lat,
				        	'lng' => $row->lng,
				        	'distance' => $row->distance_path,
				        	'id_neighbor' => $id_neighbor2
				        );
				        $this->db->insert('path', $data_path_neighbor2);
	        		}
	        		
	        	}
        	}

	        
		}

		public function update_distance($distance, $id_neighbor){
			$data_distance = array (
		        'distance' => $distance
	        );
	        $this->db->where('id', $id_neighbor);
	        $this->db->update('neighbor',$data_distance);
		}

		public function update_distance_path($distance, $id_path){
			$data_distance = array (
		        'distance' => $distance
	        );
	        $this->db->where('id', $id_path);
	        $this->db->update('path',$data_distance);
		}


		public function update_generator($type, $id_generator, $id_marker1, $id_marker2){
			if($type == 1){
				$data_generator = array (
			        'id_generator_h' => $id_generator
		        );
			} elseif($type == 2){
				$data_generator = array (
			        'id_generator_ps' => $id_generator
		        );
			} else {
				$data_generator = array (
			        'id_generator_fs' => $id_generator
		        );
			}


	        $this->db->where('id', 'NP'. $id_marker1 . 'NP' . $id_marker2);
	        $this->db->or_where('id', 'NP' . $id_marker2 . 'NP' . $id_marker1);
	        $this->db->update('neighbor',$data_generator);
			
		}

		public function update_construct_neighbor(){
			$data_generator = array (
		        'id_generator_h' => $this->select_all_generator_group_by_where_type(1)->id,
		        'id_generator_ps' => $this->select_all_generator_group_by_where_type(2)->id,
		        'id_generator_fs' => $this->select_all_generator_group_by_where_type(3)->id,
		        'from_border' => 0,
		        'is_visible' => 1
	        );
	        $this->db->update('neighbor',$data_generator);
		}

		public function check_contains($points, $query_coords){
			$coords = explode(" ", $query_coords);

			$temp = "";
			foreach($points AS $row){
				$gid = $row['id_generator'];
				$temp = $temp . $row[0] . " " . $row[1] .", ";
			}
			$temp = $temp . $points[0][0] . " " . $points[0][1];
			// die();
			$new_query = $this->db->query('	
						SET @gid = ' . $gid . ';
			');
			$query = $this->db->query('	
						SELECT @gid AS gid, MBRContains(
					    PolygonFromText("Polygon(('.$temp.'))"),
					    PolygonFromText("Point(' . $coords[0] . ' ' . $coords[1] . ')")) AS contain;
			');
			return $query->row();
		}


	}
?>