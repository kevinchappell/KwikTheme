<?php 

	define('WP_USE_THEMES', false);
	include($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

	function autocompleter($term){	

		global $wpdb;

		$input = $term;
		$data = array();
		$table_name = $wpdb->prefix."posts";
		$query = "
		SELECT concat( post_title ) name, 1 cnt, ID as the_id FROM ".$table_name." t
		WHERE post_type='kcdl_box'
		AND post_title LIKE '%$input%'
		AND post_status='publish'
		ORDER BY post_title";
		
		$query_results = mysql_query($query);

		while ($row = mysql_fetch_array($query_results)) {

			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($row['the_id']), 'thumbnail' );

			$json = array();
			$json['label'] = $row['name'];
			$json['id'] = $row['the_id'];
			$json['subtitle'] = get_post_meta($row['the_id'], "_subtitle", true);
			$json['learn_more'] = get_post_meta($row['the_id'], "_continue", true);
			$json['link'] = get_post_meta($row['the_id'], "_link", true);
			$json['thumbnail_id'] = get_post_thumbnail_id( $row['the_id'] );
			$json['thumbnail'] = $thumb[0];
			
			$data[] = $json;
		}
		//echo $table_name;
		header("Content-type: application/json");
		echo json_encode($data);
	}

	
	if($_GET['term']){
		autocompleter($_GET['term']);
	}
	