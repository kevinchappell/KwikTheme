<?php 

	define('WP_USE_THEMES', false);

	include($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

	function autocompleter($term, $type){


		global $wpdb;
		$input = $term;
		$data = array();
		$table_name = $wpdb->prefix."posts";

		$query = "SELECT concat( post_title ) name, 1 cnt, ID as the_id FROM ".$table_name." t ";
		$query .= "WHERE post_status='publish' ";
		$query .= "AND post_date < NOW() ";
		if($type) $query .= "AND post_type='".$type."' ";
		$query .= "AND post_title LIKE '%$input%' "; 
		$query .= "ORDER BY post_title";		

		$query_results = mysql_query($query);

		while ($row = mysql_fetch_array($query_results)) {
			
			$json = array();
			$json['label'] = $row['name'];
			$json['id'] = $row['the_id'];
			$json['thumbnail_id'] = get_post_thumbnail_id( $row['the_id'] );
			$thumb = wp_get_attachment_image_src( $json['thumbnail_id'], 'thumbnail' );
			$json['thumbnail'] = [
				$thumb[0],
				$thumb[1],
				$thumb[2]
			];

			$data[] = $json;
		}

		header("Content-type: application/json");
		echo json_encode($data);
	}

	
	if($_GET['term']){
		$term = $_GET['term'];
		$type = $_GET['type'] ? $_GET['type'] : undefined;
		autocompleter($term, $type);
	}