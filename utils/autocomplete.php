<?php 

	define('WP_USE_THEMES', false);
	include($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

	function autocompleter($term, $type){	

		global $wpdb;
		$input = $term;
		$data = array();
		$table_name = $wpdb->prefix."posts";

		$query = "
		SELECT concat( post_title ) name, 1 cnt, ID as the_id FROM ".$table_name." t
		WHERE post_status='publish'
		AND post_type = '".$type."'
		AND post_date < NOW()
		AND post_title LIKE '%$input%' 
		ORDER BY post_title";

		$query_results = mysql_query($query);

		while ($row = mysql_fetch_array($query_results)) {
			$json = array();
			$json['label'] = $row['name'];
			$json['id'] = $row['the_id'];
			$data[] = $json;
		}

		header("Content-type: application/json");
		echo json_encode($data);
	}

	
	if($_GET['term']){
		$term = $_GET['term'];
		$type = isset($_GET['type']) ? $_GET['type'] : 'post';
		autocompleter($term, $type);
	}