<?php

// Add the Page meta box
function kt_add_page_metabox() {

	$screens = array('page');
	foreach ($screens as $screen) {
		add_meta_box('kt_page_meta', 'Page Meta Data', 'kt_page_meta', 'page', 'normal', 'default');
	}

}
add_action('add_meta_boxes', 'kt_add_page_metabox');

// TODO: to be completed
// Add the Page meta box
function kt_add_TR_metabox() {

	$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];
	$template_file = get_post_meta($post_id, '_wp_page_template', TRUE);

	// check for a template type
	if ($template_file == 'page-templates/technical_resources.php') {
		// add_meta_box('kt_bod_meta', 'Board Members', 'kt_bod_meta', 'page', 'normal', 'default');
	}

}
// add_action( 'add_meta_boxes', 'kt_add_TR_metabox' );

function kt_add_post_metabox() {

	$screens = array('post');
	foreach ($screens as $screen) {
		add_meta_box('kt_post_meta', 'Post Meta', 'kt_post_meta', 'post', 'side', 'default');
	}
}
add_action('add_meta_boxes', 'kt_add_post_metabox');

function kt_add_bod_metabox() {
	$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];
	$template_file = get_post_meta($post_id, '_wp_page_template', TRUE);

	// check for a template type
	if ($template_file == 'page-templates/board-of-directors.php') {
		add_meta_box('kt_bod_meta', 'Board Members', 'kt_bod_meta', 'page', 'normal', 'default');
	}
}
add_action('add_meta_boxes', 'kt_add_bod_metabox');

// The edit page Meta box
function by_the_numbers() {
	global $post;

	$btns_good = get_post_meta($post->ID, 'btn_good', false);
	$btns_good = $btns_good[0];
	$btns_bad = get_post_meta($post->ID, 'btn_bad', false);
	$btns_bad = $btns_bad[0];

	$btn_meta = '';

	$btn_meta .= '<div class="clear">';

	// THE GOOD
	$btn_meta .= '<ul id="btn_good" class="btn_ul sortable clear" style="margin-right:4%">';
	$btn_meta .= '<li class="btn_ul_title ignore"><h2 style="color:#28a234">' . __('The Good', 'op') . '</h2></li>';
	if (!empty($btns_good)) {
		$i = 0;
		foreach ($btns_good as $btn_good) {

			$btn_meta .= '<li><span class="move_btn">&nbsp;</span><div class="btn_nums">';
			$btn_meta .= '<input type="text" value="' . $btn_good[0] . '" placeholder="' . __('Big Number', 'op') . '" name="btn_good[' . $i . '][]" />';
			$btn_meta .= '<input type="text" value="' . $btn_good[1] . '" placeholder="' . __('Small Text', 'op') . '" name="btn_good[' . $i . '][]" />';
			$btn_meta .= '</div>';
			$btn_meta .= '<textarea name="btn_good[' . $i . '][]" placeholder="' . __('Description', 'op') . '" >' . $btn_good[2] . '</textarea>';
			$btn_meta .= '<span class="remove_btn">×</span></li>';
			$i++;

		}
	} else {
		$btn_meta .= '<li><span class="move_btn">&nbsp;</span>
							<div class="btn_nums"><input type="text" value="" placeholder="' . __('Big Number', 'op') . '" name="btn_good[]" /><input type="text" value="" placeholder="' . __('Small Text', 'op') . '" name="btn_good[]" /></div>
							<textarea name="btn_good[]" placeholder="' . __('Description', 'op') . '" ></textarea><span class="remove_btn">×</span>
						</li>';

	}// is_array
	$btn_meta .= '<li class="ignore"><span class="add_btn">+</span></li>';
	$btn_meta .= '</ul>';
	// THE BAD
	$btn_meta .= '<ul id="btn_bad" class="btn_ul" >';
	$btn_meta .= '<li class="btn_ul_title ignore"><h2 style="color:#990000">' . __('The Bad', 'op') . '</h2></li>';
	if (!empty($btns_bad)) {
		$i = 0;
		foreach ($btns_bad as $btn_bad) {

			$btn_meta .= '<li><span class="move_btn">&nbsp;</span><div class="btn_nums">';
			$btn_meta .= '<input type="text" value="' . $btn_bad[0] . '" placeholder="' . __('Big Number', 'op') . '" name="btn_bad[' . $i . '][]" />';
			$btn_meta .= '<input type="text" value="' . $btn_bad[1] . '" placeholder="' . __('Small Text', 'op') . '" name="btn_bad[' . $i . '][]" />';
			$btn_meta .= '</div>';
			$btn_meta .= '<textarea name="btn_bad[' . $i . '][]" placeholder="' . __('Description', 'op') . '" >' . $btn_bad[2] . '</textarea>';
			$btn_meta .= '<span class="remove_btn">×</span></li>';

			$i++;

		}
	} else {
		$btn_meta .= '<li><span class="move_btn">&nbsp;</span>
				<div class="btn_nums"><input type="text" value="" placeholder="' . __('Big Number', 'op') . '" name="btn_bad[]" /><input type="text" value="" placeholder="' . __('Small Text', 'op') . '" name="btn_bad[]" /></div>
				<textarea name="btn_bad[]" placeholder="' . __('Description', 'op') . '" ></textarea><span class="remove_btn">×</span>
			</li>';

	}// is_array
	$btn_meta .= '<li class="ignore"><span class="add_btn">+</span></li>';
	$btn_meta .= '</ul>';

	$btn_meta .= '</div>';

	echo $btn_meta;

}

// The edit page Meta box
function kt_page_meta() {
	global $post;

	$show_background = get_post_meta($post->ID, 'show_background', true);
	$header_img = get_post_meta($post->ID, 'header_img', true);
	$header_text = get_post_meta($post->ID, 'header_text', true);

	$header_img_prev = wp_get_attachment_image_src($header_img, 'header_img');
	$header_img_prev = $header_img_prev['0'];

	$page_meta = '';
	// Noncename for security check on data origin
	$page_meta .= '<input type="hidden" name="kt_meta_noncename" id="kt_meta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
	$page_meta .= '<div class="page_meta meta_wrap">';
	$page_meta .= '<ul>';
	$page_meta .= '<li><strong>' . __('Page Header', 'op') . ':</strong></li>';
	$page_meta .= '<li><img src="' . $header_img_prev . '" class="img_prev transparent" width="60" height="24" title="' . get_the_title($header_img) . '"><label>' . __('Image', 'op') . '</label><input type="hidden" name="header_img" class="img_id" value="' . $header_img . '" /><span id="site_bg_img_ttl" class="img_title">' . get_the_title($header_img) . (!empty($header_img) ? '<span title="' . __('Remove Image', 'op') . '" class="clear_img tooltip"></span>' : '') . '</span><input type="button" class="upload_img" value="Upload" /></li>';
	$page_meta .= '<li><label>' . __('Text', 'op') . '</label><input type="text" name="header_text" value="' . $header_text . '" /></li>';
	$page_meta .= '</ul>';
	$page_meta .= '</div>';

	echo $page_meta;
}

//
function kt_post_meta() {
	global $post;

	$source = get_post_meta($post->ID, '_source', true);
	$source_link = get_post_meta($post->ID, '_source_link', true);

	$post_meta = '';
	// Noncename for security check on data origin
	$post_meta .= '<input type="hidden" name="kt_post_meta_noncename" id="kt_post_meta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
	$post_meta .= '<div class="post_meta meta_wrap">';
	$post_meta .= '<label><strong>' . __('Source', 'op') . ':</strong></label>';
	$post_meta .= '<input type="text" name="post_source" class="kt_text" value="' . $source . '" />';
	$post_meta .= '<label><strong>' . __('Link', 'op') . ':</strong></label>';
	$post_meta .= '<input type="text" name="post_source_link" class="kt_text" value="' . $source_link . '" />';
	$post_meta .= '</div>';

	echo $post_meta;
}

// board of directors meta
function kt_bod_meta() {
	global $post;

	$bod = get_post_meta($post->ID, '_board_members', false);

	$no_avatar = get_bloginfo('template_url') . '/inc/images/no_avatar.jpg';
	$bod = $bod ? $bod[0] : "";

	$bod_meta = '';
	// Noncename for security check on data origin
	$bod_meta .= '<input type="hidden" name="kt_bod_meta_noncename" id="kt_bod_meta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
	$bod_meta .= '<div class="post_meta meta_wrap">';
	$bod_meta .= '<ul id="bod" class="sortable">';
	$i = 0;
	if (!empty($bod)) {
		foreach ($bod as $b) {
			$mem_img = wp_get_attachment_image_src($b['img'], 'medium');
			$mem_img = $mem_img['0'];
			$img_src = $mem_img ? $mem_img : $no_avatar;

			$bod_meta .= '<li class="bod_mem clear">';
			$bod_meta .= '<span class="remove_btn">Remove</span>';
			$bod_meta .= '<img src="' . $img_src . '" class="img_prev" width="150">';
			$bod_meta .= '<input type="hidden" name="bod[' . $i . '][img]" class="img_id" value="' . $b['img'] . '">';
			$bod_meta .= '<input type="text" name="bod[' . $i . '][name]" placeholder="' . __('Director Name, Position', 'kwik') . '" class="kt_text" value="' . $b['name'] . '" /><br/>';
			$bod_meta .= '<input type="text" placeholder="' . __('Company', 'kwik') . '" class="kt_text kwik_ac-clients ignore" name="bod[' . $i . '][company_name]" value="' . $b['company_name'] . '" /><input type="hidden" class="kwik_ac_val" name="bod[' . $i . '][company]" value="' . $b['company'] . '"><br/>';
			$bod_meta .= '<textarea name="bod[' . $i . '][bio]" class="bod_bio" placeholder="' . __('Director Name, Position', 'kwik') . '">' . $b['bio'] . '</textarea>';
			$bod_meta .= '</li>';
			$i++;
		}
	} else {
		$bod_meta .= '<li class="bod_mem clear">';
		$bod_meta .= '<span class="remove_btn">Remove</span>';
		$bod_meta .= '<img src="' . $no_avatar . '" class="img_prev" width="150">';
		$bod_meta .= '<input type="hidden" name="bod[0][img]" class="img_id" value="">';
		$bod_meta .= '<input type="text" name="bod[0][name]" placeholder="' . __('Director Name, Position', 'kwik') . '" class="kt_text" value="" /><br/>';
		$bod_meta .= '<input type="text" placeholder="' . __('Company', 'kwik') . '" class="kt_text kwik_ac-clients ignore" name="bod[0][company_name]" value="" /><input type="hidden" class="kwik_acval" name="bod[0][company]" value=""><br/>';
		$bod_meta .= '<textarea name="bod[0][bio]" class="bod_bio" placeholder="' . __('Director Name, Position', 'kwik') . '"></textarea>';
		$bod_meta .= '</li>';
	}
	$bod_meta .= '<li class="ignore clear"><span class="add_btn">' . __('Add Member', 'kwik') . '</span></li>';
	$bod_meta .= '</ul>';
	$bod_meta .= '</div>';

	echo $bod_meta;
}

// Save the Metabox Data
function kt_save_page_meta($post_id, $post) {

	if ($post->post_status == 'auto-draft') {return;
	}

	if ($post->post_type != 'page') {return $post->ID;
	}

	// make sure there is no conflict with other post save function and verify the noncename
	if (!wp_verify_nonce($_POST['kt_meta_noncename'], plugin_basename(__FILE__))) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if (!current_user_can('edit_post', $post->ID)) {return $post->ID;
	}

	$page_meta = array(
		'_board_members' => $_POST['bod'],
		'btn_good' => $_POST['btn_good'],
		'btn_bad' => $_POST['btn_bad'],
		'show_background' => strip_tags($_POST['show_background']),
		'header_img' => strip_tags($_POST['header_img']),
		'header_text' => strip_tags($_POST['header_text'])
	);

	// Add values of $belt_meta as custom fields
	foreach ($page_meta as $key => $value) {
		if ($post->post_type == 'revision') {return;
		}

		__update_post_meta($post->ID, $key, $value);
	}

}
add_action('save_post', 'kt_save_page_meta', 1, 2);

// Save the Metabox Data
function kt_save_post_meta($post_id, $post) {

	if ($post->post_status == 'auto-draft') {return;
	}

	if ($post->post_type != 'post') {return $post->ID;
	}

	// make sure there is no conflict with other post save function and verify the noncename
	if (!wp_verify_nonce($_POST['kt_post_meta_noncename'], plugin_basename(__FILE__))) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if (!current_user_can('edit_post', $post->ID)) {
    return $post->ID;
	}

	$page_meta = array(
		'_source' => $_POST['post_source'],
		'_source_link' => $_POST['post_source_link'],
	);

	// Add values of $belt_meta as custom fields
	foreach ($page_meta as $key => $value) {
		if ($post->post_type == 'revision') {return;
		}

		__update_post_meta($post->ID, $key, $value);
	}

}
add_action('save_post', 'kt_save_post_meta', 1, 2);

// TAXONOMY META FIELDS

// Add term page
function client_levels_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="term_meta[fee]"><?php _e('Annual Fee', 'kwik');?></label>
		<input type="text" name="term_meta[fee][]" id="term_meta[fee]" value="">
		<input type="text" name="term_meta[fee][]" id="term_meta[fee][1]" value="">
		<p class="description"><?php _e('What is the Annual fee for this Membership Level?', 'kwik');?></p>
	</div>
	<div class="form-field">
		<label for="term_meta[fte]"><?php _e('FTEs', 'kwik');?></label>
		<input type="text" name="term_meta[fte]" id="term_meta[fte]" value="">
		<p class="description"><?php _e('How many FTEs?', 'kwik');?></p>
	</div>
	<div class="form-field">
		<label for="term_meta[ipc]"><?php _e('IP Contribution', 'kwik');?></label>
		<input type="text" name="term_meta[ipc]" id="term_meta[ipc]" value="">
		<p class="description"><?php _e('How much?', 'kwik');?></p>
	</div>
	<div class="form-field">
		<label for="term_meta[tsc]"><?php _e('Technical Steering Commitee', 'kwik');?></label>
		<input type="text" name="term_meta[tsc]" id="term_meta[tsc]" value="">
		<p class="description"><?php _e('', 'kwik');?></p>
	</div>
	<div class="form-field">
		<label for="term_meta[position]"><?php _e('Board/Voting Position', 'kwik');?></label>
		<input type="text" name="term_meta[position]" id="term_meta[position]" value="">
		<p class="description"><?php _e('', 'kwik');?></p>
	</div>
<?php
}
add_action('client_levels_add_form_fields', 'client_levels_add_new_meta_field', 10, 2);

// Edit term page
function client_levels_edit_meta_field($term) {

	// put the term ID into a variable
	$t_id = $term->term_id;

	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option("taxonomy_$t_id");?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[fee]"><?php _e('Annual Fee', 'kwik');?></label></th>
		<td>
			<input type="text" name="term_meta[fee][]" id="term_meta[fee]" value="<?php echo esc_attr($term_meta['fee'][0]) ? esc_attr($term_meta['fee'][0]) : '';?>">
			<input type="text" name="term_meta[fee][]" id="term_meta[fee][1]" value="<?php echo esc_attr($term_meta['fee'][1]) ? esc_attr($term_meta['fee'][1]) : '';?>">
			<p class="description"><?php _e('What is the Annual fee for this Membership Level?', 'kwik');?></p>
		</td>
	</tr>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[fte]"><?php _e('FTEs', 'kwik');?></label></th>
		<td>
			<input type="text" name="term_meta[fte]" id="term_meta[fte]" value="<?php echo esc_attr($term_meta['fte']) ? esc_attr($term_meta['fte']) : '';?>">
			<p class="description"><?php _e('Enter the number of FTEs', 'kwik');?></p>
		</td>
	</tr>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[ipc]"><?php _e('IP Contribution', 'kwik');?></label></th>
		<td>
			<input type="text" name="term_meta[ipc]" id="term_meta[ipc]" value="<?php echo esc_attr($term_meta['ipc']) ? esc_attr($term_meta['ipc']) : '';?>">
			<p class="description"><?php _e('How much for IPC?', 'kwik');?></p>
		</td>
	</tr>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[tsc]"><?php _e('Technical Steering Commitee', 'kwik');?></label></th>
		<td>
			<input type="text" name="term_meta[tsc]" id="term_meta[tsc]" value="<?php echo esc_attr($term_meta['tsc']) ? esc_attr($term_meta['tsc']) : '';?>">
			<p class="description"><?php _e('', 'kwik');?></p>
		</td>
	</tr>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[position]"><?php _e('Board/Voting Position', 'kwik');?></label></th>
		<td>
			<input type="text" name="term_meta[position]" id="term_meta[position]" value="<?php echo esc_attr($term_meta['position']) ? esc_attr($term_meta['position']) : '';?>">
			<p class="description"><?php _e('', 'kwik');?></p>
		</td>
	</tr>
<?php
}
add_action('client_levels_edit_form_fields', 'client_levels_edit_meta_field', 10, 2);

// Save extra taxonomy fields callback function.
function save_client_levels_custom_meta($term_id) {
	if (isset($_POST['term_meta'])) {
		$t_id = $term_id;
		$term_meta = get_option("taxonomy_$t_id");
		$keys = array_keys($_POST['term_meta']);
		foreach ($keys as $key) {
			if (isset($_POST['term_meta'][$key])) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option("taxonomy_$t_id", $term_meta);
	}
}
add_action('edited_client_levels', 'save_client_levels_custom_meta', 10, 2);
add_action('create_client_levels', 'save_client_levels_custom_meta', 10, 2);