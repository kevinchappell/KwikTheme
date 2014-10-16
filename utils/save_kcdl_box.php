<?php 

	define('WP_USE_THEMES', false);
	include($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

	function save_kcdl_box($post){	

		// global $wpdb;
		global $user_ID;

		$kcdl_box = array(
			       'post_title' => htmlentities($post['kcdl_box_ttl']),
				   'post_content' => '',
				   'post_status' => 'publish',
				   'post_author' => $user_ID,
				   'post_type' => 'kcdl_box'
				);

		if($post["kcdl_box_id"] != ""){
			$kcdl_box["ID"] = $post["kcdl_box_id"][0];
		}

		// Insert the post into the database
		$post_id = wp_insert_post( $kcdl_box );

		if($post["kcdl_box_img_id"] != ""){
			set_post_thumbnail( $post_id, $post["kcdl_box_img_id"]);
		}		

		$kcdl_box_meta = array(
			'_subtitle' => $post['kcdl_box_subtitle'],
			'_link' => $post['box_link'],
			'_continue' => $post['kcdl_box_learn_more']
	    );
	    
	    // Add values of $kcdl_box_meta as custom fields 
	    foreach ($kcdl_box_meta as $key => $value) {
	        if( $post->post_type == 'revision' ) return;
	        __update_post_meta( $post_id, $key, $value );
	    }

	    echo $post_id;

	}


	if (current_user_can('edit_post', $_POST["kcdl_box_id"])) {
        save_kcdl_box($_POST);
    } else {echo "no!";}