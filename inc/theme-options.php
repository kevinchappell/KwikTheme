<?php
/**
 * OpenPower Theme Options
 * @subpackage OpenPower
 * @since OpenPower 1.0
 */
/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 * @since OpenPower 1.0
 *
 */

define("THEME_PREFIX", "op_");

function op_admin_enqueue_scripts($hook){
    $hooks_array = array(
        "toplevel_page_theme_options",
        "post-new.php",
        "post.php",
        "home_slide_page_slide-settings"
    );
    wp_enqueue_script('media-upload');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('jquery-ui-spinner');	
	wp_enqueue_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3&sensor=true', array(), NULL, true);
	if(function_exists('wp_enqueue_media'))	wp_enqueue_media();
	
	if(in_array($hook, $hooks_array)) {
		wp_enqueue_script('cpicker', get_template_directory_uri() . '/js/cpicker.js');
		wp_enqueue_script('op-theme-options', get_template_directory_uri() . '/inc/theme-options.js', array('googlemaps', 'cpicker','jquery'), '2014-06-10', true);
	}    
    
}
add_action( 'admin_enqueue_scripts', 'op_admin_enqueue_scripts' ,10,1);



function admin_css() {	
	wp_enqueue_style( 'op-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-04-28' );
	wp_enqueue_style('thickbox');
	}
add_action('admin_print_styles', 'admin_css');


add_action('admin_init', 'theme_options_init_fn' );
function theme_options_init_fn(){
	$tabs = op_settings_tabs();
	$options = op_get_theme_options();
	register_setting('theme_options', 'theme_options', 'op_options_validate' );
	foreach($tabs as $key => $value){
		add_settings_section( $key, $value['section_title'], THEME_PREFIX.'sections_callback', __FILE__);		
		foreach($options as $k => $v){			
			add_settings_field($k,$v['title'],'op_field_type_'.$v['type'],__FILE__,$v['section'],$k);
		}
	}	
}

function op_sections_callback( $section ) {
	$tabs = op_settings_tabs();
	echo "<p>".$tabs[$section['id']]['section_desc']."</p>";
}


Class OpUtils{

	public function positions(){
		$positions = array(
				'0 0' => 'Top Left',
				'0 50%' => 'Top Center',
				'0 100%' => 'Top Right',
				'50% 0' => 'Middle Left',
				'50% 50%' => 'Middle Center',
				'50% 100%' => 'Middle Right',
				'100% 0' => 'Bottom Left',
				'100% 50%' => 'Bottom Center',
				'100% 100%' => 'Bottom Right',
			);
		return $positions;
	}

	public function repeat(){
		$repeat = array(
				'no-repeat' => 'No Repeat',
				'repeat' => 'Repeat',
				'repeat-x' => 'Repeat-X',
				'repeat-y' => 'Repeat-Y'
			);
		return $repeat;
	}

	public function bgSize(){
		$bgSize = array(
				'auto' => 'Default',
				'100% 100%' => 'Stretch',
				'cover' => 'Cover',
			);
		return $bgSize;
	}

	public function bgAttachment(){
		$bgAttachment = array(
				'scroll' => 'Scroll',
				'fixed' => 'Fixed'
			);
		return $bgAttachment;
	}

	public function fontWeights(){
		$fontWeights = array(
				'normal' => 'Normal',
				'bold' => 'Bold',
				'bolder' => 'Bolder',
				'lighter' => 'Lighter'
			);
		return $fontWeights;
	}


	public function get_all_post_types(){
		$all_post_types = array();
		$args = array(
		   'public'   => true,
		   '_builtin' => true
		);
		$output = 'objects'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'

		$default_post_types = get_post_types( $args, $output, $operator );

		$i = 0;

		foreach ( $default_post_types  as $k => $v) {
			$all_post_types[$k]['label']  = $v->labels->name;
			$all_post_types[$k]['name']  = $v->name;
		}

		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);

		$custom_post_types = get_post_types( $args, $output, $operator );

		foreach ( $custom_post_types  as $k => $v) {
			$all_post_types[$k]['label']  = $v->labels->name;
			$all_post_types[$k]['name']  = $v->name;
		}

		array_push($all_post_types, array('name' => '404','label' => __('404 Not Found', 'kwik')));

		return $all_post_types;
	}

	public function imgInput($name, $val){
		$thumb = wp_get_attachment_image_src($val, 'thumbnail' );
		$thumb = $thumb['0'];
		$img_input = '<input type="hidden" name="'.$name.'" class="img_id" value="'.$val.'" />';
		$img_input .= '<img src="'.$thumb.'" class="img_prev" width="23" height="23" title="'.get_the_title($val).'"/><span id="site_bg_img_ttl" class="img_title">'.get_the_title($val).(!empty($val) ? '<span title="'.__('Remove Image','kwik').'" class="clear_img tooltip"></span>':'').'</span><input type="button" class="upload_img" id="upload_img" value="+ '.__('IMG', 'kwik').'" />';
		return $img_input;
	}

	public function textInput($name, $val){
		return '<input type="text" name="'.$name.'" class="op_text" value="'.esc_attr($val).'" />';
	}

	public function spinner($name, $val){
		return '<input type="number" name="'.$name.'" class="kf_spinner" max="50" min="1" value="'.esc_attr($val).'" />px';
	}

	public function colorInput($name, $val){
		$color_input = '<input type="text" name="'.$name.'" class="cpicker" value="'. esc_attr($val).'" />';
		if(!empty($val)) $color_input .= '<span class="clear_color tooltip" title="'.__('Remove Color', 'kwik').'"></span>';	
		return $color_input;
	}

	public function selectInput($name, $val, $options){
		$select_input = '<select name="'.$name.'">';
		foreach($options as $k => $v) $select_input .= '<option '.selected( $k, $val, false).' value="'.$k.'">'.$v.'</option>';
		$select_input .= '</select>';
		return $select_input;
	}

	public function fontFamilyInput($name, $cur_val){
		$fonts = get_google_fonts('AIzaSyDTUcM9QmvxwUdg2cAJQArNWPaAjAnP--E');
		$options = array();
		foreach($fonts as $font){
			$options[str_replace(' ', '+', $font->family)] = $font->family;
		}
		return $this->selectInput($name, $cur_val, $options);
	}	


}


class Validate extends OpUtils {

    public function validateFont($val){
    	// var_dump($val);
		$font = array (
				'color' => $this->validateColor($val['color']),
				'weight' => wp_filter_nohtml_kses($val['weight']),
				'size' => wp_filter_nohtml_kses($val['size']),
				'line-height' => wp_filter_nohtml_kses($val['line-height']),
				'font-family' => wp_filter_nohtml_kses($val['font-family'])
			);
		return $font;
	}

    public function validateLinkColor($val){
		$link_color = array (
				'default' => $this->validateColor($val['default']),
				'visited' => $this->validateColor($val['visited']),
				'hover' => $this->validateColor($val['hover']),
				'active' => $this->validateColor($val['active'])
			);

		return $link_color;
	}

	public function validateColor($val){
		$color = ( isset( $val ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $val ) ) ? '#'.strtolower( ltrim( $val, '#' ) ) : '';
		return $color;
	}


    public function validateHeaders($val){
		$headers = array ();

		$post_types = $this->get_all_post_types();		

		foreach ($post_types as $type) {			
			$headers[$type['name']] = array(
						'color' => $this->validateColor($val[$type['name']]['color']),
						'weight' => wp_filter_nohtml_kses($val[$type['name']]['weight']),
						'size' => wp_filter_nohtml_kses($val[$type['name']]['size']),
						'line-height' => wp_filter_nohtml_kses($val[$type['name']]['line-height']),
						'font-family' => wp_filter_nohtml_kses($val[$type['name']]['font-family']),
						'bg_color' => $this->validateColor($val[$type['name']]['bg_color']),
						'img' => wp_filter_nohtml_kses($val[$type['name']]['img']),
						'position' => wp_filter_nohtml_kses($val[$type['name']]['position']),
						'repeat' => wp_filter_nohtml_kses($val[$type['name']]['repeat']),
						'bg_size' => wp_filter_nohtml_kses($val[$type['name']]['bg_size']),
						'attachment' => wp_filter_nohtml_kses($val[$type['name']]['img']),
						'text' => wp_filter_nohtml_kses($val[$type['name']]['text'])
					);
		}

		return $headers;
	}


}


function op_field_type_logo($k) {
	$utils = new OpUtils();
	$options = op_get_theme_options();
	$val = $options[$k]['value'];
	$name = 'theme_options['.$k.'][value]';
	
	$kf_bg = '';
	$kf_bg .= '<div class="preview">';
	$kf_bg .= '</div>';
	$kf_bg .= '<div>'.__('Background Color','kwik').':<br/>'.$utils->colorInput($name.'[color]', $val['color']).'</div>';
	$kf_bg .= '<div>'.__('Image','kwik').':<br/>'.$utils->imgInput($name.'[img]', $val['img']).'</div>';
	$kf_bg .= '<div>'.__('Position','kwik').':<br/>'.$utils->selectInput($name.'[position]', $val['position'], $utils->positions()).'</div>';
	$kf_bg .= '<div>'.__('Repeat','kwik').':<br/>'.$utils->selectInput($name.'[repeat]', $val['repeat'], $utils->repeat()).'</div>';
	$kf_bg .= '<div>'.__('Bg Size','kwik').':<br/>'.$utils->selectInput($name.'[size]', $val['size'], $utils->bgSize()).'</div>';
	$kf_bg .= '<div>'.__('Attachment','kwik').':<br/>'.$utils->selectInput($name.'[attachment]', $val['attachment'], $utils->bgAttachment()).'</div>';

	
	echo $kf_bg;
}

function op_field_type_headers($k) {
	$utils = new OpUtils();
	$options = op_get_theme_options();
	$val = $options[$k]['value'];
	$name = 'theme_options['.$k.'][value]';
	
	$kf_headers = '';

	$post_types = $utils->get_all_post_types();

	foreach ( $post_types as $post_type ) {
	
		if(!isset($post_type)) return;

		$header = $k."-".$post_type['name'];

		echo '<h2 class="button-primary">' . $post_type['label'] . '</h2>';
		echo '<div class="sub_panel clear">';
			echo '<h3 style="">'.__('Title','kwik').'</h3>';
			echo $utils->textInput($name."[".$post_type['name']."][text]", $val[$post_type['name']]['text']);
			echo '<h3 style="">'.__('Font','kwik').'</h3>';
			op_field_type_font($header);
			echo '<h3>'.__('Background','kwik').'</h3>';
			op_field_type_background($header);
		echo '</div>';
		$header = '';

	}
}



function op_field_type_font($k) {
	$utils = new OpUtils();
	$options = op_get_theme_options();

	if(strpos($k,'headers') !== false) {
		$n = explode("-", $k);
		$name = 'theme_options['.$n[0].'][value]['.$n[1].']';
		$val = $options[$n[0]]['value'][$n[1]];
	} else {
		$name = 'theme_options['.$k.'][value]';
		$val = $options[$k]['value'];
	}
	$kf_f = '';
	$kf_f .= '<div class="color sub_option">'.__('Color','kwik').':<br/>'.$utils->colorInput($name.'[color]', $val['color']).'</div>';
	$kf_f .= '<div class="weight sub_option">'.__('Weight','kwik').':<br/>'.$utils->selectInput($name.'[weight]', $val['weight'], $utils->fontWeights()).'</div>';
	$kf_f .= '<div class="size sub_option">'.__('Size','kwik').':<br/>'.$utils->spinner($name.'[size]', $val['size']).'</div>';
	$kf_f .= '<div class="line-height sub_option">'.__('Height','kwik').':<br/>'.$utils->spinner($name.'[line-height]', $val['line-height']).'</div>';
	$kf_f .= '<div class="family sub_option">'.__('Font-Family','kwik').':<br/>'.$utils->fontFamilyInput($name.'[font-family]', $val['font-family']).'</div>';
	
	echo $kf_f;
}


function get_google_fonts($api_key) {

    $feed = "https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&fields=items(category%2Cfamily%2Cvariants)&key=" . $api_key;

    $cache_file = dirname(__FILE__).'/cache/font-cache';

    $last = file_exists($cache_file) ? filemtime($cache_file) : false;
    $now = time();
    $interval = 1200; // twenty minutes
	
	// check the cache file
    if ( !$last || (( $now - $last ) || !file_exists($cache_file) > $interval) ) {		
		
		$cache_rss = curl_get_result($feed);

        if (!$cache_rss) {
			// feed returned nothing, use the font file
        } else {
            $cache_static = fopen($cache_file, 'wb');
            fwrite($cache_static, $cache_rss);
            fclose($cache_static);
        }
        // read from the cache file
        $rss = file_get_contents($cache_file);		
    }
    else {
        $rss = file_get_contents($cache_file);		
    }	

	$fonts = json_decode($rss);

    if ($fonts) { // are there any results?
		return $fonts->items;
    } else { // There are no fonts... somehow
		return false; 
    }
}



function op_field_type_background($k) {
	$utils = new OpUtils();
	$options = op_get_theme_options();
	
	if(strpos($k,'headers') !== false) {
		$n = explode("-", $k);
		$name = 'theme_options['.$n[0].'][value]['.$n[1].']';
		$val = $options[$n[0]]['value'][$n[1]];
	} else {
		$name = 'theme_options['.$k.'][value]';
		$val = $options[$k]['value'];
	}
	
	$kf_bg = '';
	$kf_bg .= '<div>'.__('Background Color','kwik').':<br/>'.$utils->colorInput($name.'[bg_color]', $val['bg_color']).'</div>';
	$kf_bg .= '<div>'.__('Image','kwik').':<br/>'.$utils->imgInput($name.'[img]', $val['img']).'</div>';
	$kf_bg .= '<div>'.__('Position','kwik').':<br/>'.$utils->selectInput($name.'[position]', $val['position'], $utils->positions()).'</div>';
	$kf_bg .= '<div>'.__('Repeat','kwik').':<br/>'.$utils->selectInput($name.'[repeat]', $val['repeat'], $utils->repeat()).'</div>';
	$kf_bg .= '<div>'.__('Bg Size','kwik').':<br/>'.$utils->selectInput($name.'[bg_size]', $val['bg_size'], $utils->bgSize()).'</div>';
	$kf_bg .= '<div>'.__('Attachment','kwik').':<br/>'.$utils->selectInput($name.'[attachment]', $val['attachment'], $utils->bgAttachment()).'</div>';
	$kf_bg .= '<div class="preview">';
	$kf_bg .= '</div>';
	
	echo $kf_bg;
}

function op_field_type_link_color($k) {
	$utils = new OpUtils();
	$options = op_get_theme_options();
	$val = $options[$k]['value'];
	$name = 'theme_options['.$k.'][value]';	

	$kf_color = '';
	$kf_color .= '<div class="op_color">'.__('Default','kwik').':<br/>'.$utils->colorInput($name.'[default]', $val['default']).'</div>';
	$kf_color .= '<div class="op_color">'.__('Visited','kwik').':<br/>'.$utils->colorInput($name.'[visited]', $val['visited']).'</div>';
	$kf_color .= '<div class="op_color">'.__('Hover','kwik').':<br/>'.$utils->colorInput($name.'[hover]', $val['hover']).'</div>';
	$kf_color .= '<div class="op_color">'.__('Active','kwik').':<br/>'.$utils->colorInput($name.'[active]', $val['active']).'</div>';
	
	echo $kf_color;
}

function op_field_type_color($k) {
	$utils = new OpUtils();
	$options = op_get_theme_options();
	$val = $options[$k]['value'];
	$name = 'theme_options['.$k.'][value]';
	echo $utils->colorInput($name, $val);
}


function op_field_type_social($k) {
	$utils = new OpUtils();
	$options = op_get_theme_options();
	$val = $options[$k]['value'];
	$name = 'theme_options['.$k.'][value]';
	$kf_sn = '';
	
	echo $kf_sn;
}




function op_header_color() {
	$options = op_get_theme_options();
	?>
	<input type="text" name="theme_options[header_color]" class="cpicker" id="header-bg" value="<?php echo esc_attr( $options['header_color'] ); ?>" />
	<br />
    <span><?php printf( __( 'Default: %s', 'op' ), '<span id="default-color">' . op_get_default_color( 'site_header', $options['color_scheme'] ) . '</span>' ); ?></span>
	<?php
}

function op_content_bg_color() {
	$options = op_get_theme_options();
	?>
	<input type="text" name="theme_options[content_bg_color]" class="cpicker" id="content-bg" value="<?php echo esc_attr( $options['content_bg_color'] ); ?>" />
	<br />
    <span><?php printf( __( 'Default: %s', 'op' ), '<span id="default-color">' . op_get_default_color( 'content_bg', $options['color_scheme'] ) . '</span>' ); ?></span>
	<?php
}

function op_link_color() {
	$options = op_get_theme_options();
	?>
	<input type="text" name="theme_options[link_color]" class="cpicker" id="link-color" value="<?php echo esc_attr( $options['link_color'] ); ?>" />
	<br />
    <span><?php printf( __( 'Default: %s', 'op' ), '<span id="default-color">' . op_get_default_color( 'link_color', $options['color_scheme'] ) . '</span>' ); ?></span>
	<?php
}



function op_ambifade() {
	$options = op_get_theme_options();

	echo '<input type="checkbox" name="theme_options[ambifade]" id="ambifade" value="1" '. checked( 1, $options['ambifade'], false ) . ' />';

}



function op_color_scheme() {
	$options = op_get_theme_options();

	foreach ( op_color_schemes() as $scheme ) {
	?>
	<div class="layout image-radio-option color-scheme">
	<label class="description">
		<input type="radio" name="theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
		<input type="hidden" id="default-color-<?php echo esc_attr( $scheme['value'] ); ?>" value="<?php echo esc_attr( $scheme['link_color'] ); ?>" />
		<span>
			<img src="<?php echo esc_url( $scheme['thumbnail'] ); ?>" width="136" height="122" alt="" />
			<?php echo $scheme['label']; ?>
		</span>
	</label>
	</div>
	<?php
	}
}






// General Settings text
function op_header_text() {
	echo '<p>Set the main section headers for the site here. Page headers are set on pages themselves.</p>';
}



// INPUT - Name: theme_options[op_thought_leadership]
function op_work_section() {
	$options = get_option('theme_options');	
	$thumb = wp_get_attachment_image_src($options['work_section_img'], 'thumbnail' );
	$thumb = $thumb['0'];
	$op_thought_leadership = '<input type="hidden" name="theme_options[work_section_img]" class="img_id" value="'.$options['work_section_img'].'" />';
	$op_thought_leadership .= '<img src="'.$thumb.'" class="img_prev" width="23" height="23" title="'.get_the_title($options['work_section_img']).'"/><span id="site_bg_img_ttl" class="img_title">'.get_the_title($options['work_section_img']).(!empty($options['work_section_img']) ? '<span title="'.__('Remove Image','op').'" class="clear_img tooltip"></span>':'').'</span>';
    $op_thought_leadership .= '<input type="button" class="upload_img" id="upload_img" value="+ IMG" />';
	echo $op_thought_leadership;
}


// INPUT - Name: theme_options[op_thought_leadership]
function op_thought_leadership() {
	$options = get_option('theme_options');	
	$thumb = wp_get_attachment_image_src($options['thought_leadership_img'], 'thumbnail' );
	$thumb = $thumb['0'];
	$op_thought_leadership = '<input type="hidden" name="theme_options[thought_leadership_img]" class="img_id" value="'.$options['thought_leadership_img'].'" />';
	$op_thought_leadership .= '<img src="'.$thumb.'" class="img_prev" width="23" height="23" title="'.get_the_title($options['thought_leadership_img']).'"/><span id="site_bg_img_ttl" class="img_title">'.get_the_title($options['thought_leadership_img']).(!empty($options['thought_leadership_img']) ? '<span title="'.__('Remove Image','op').'" class="clear_img tooltip"></span>':'').'</span>';
    $op_thought_leadership .= '<input type="button" class="upload_img" id="upload_img" value="+ IMG" />';
	echo $op_thought_leadership;
}



// INPUT - Name: theme_options[social_networks]
function op_social_networks() {
	$options = get_option('theme_options');
	//facebook
	$op_social_networks = '<label>Facebook ID: </label><input type="text" name="theme_options[social_networks][]" value="'.$options['social_networks'][0].'" /><br/>';
	//twitter
	$op_social_networks .= '<label>Twitter ID: </label><input type="text" name="theme_options[social_networks][]" value="'.$options['social_networks'][1].'" /><br/>';
	// linkedin
	$op_social_networks .= '<label>LinkedIn ID: </label><input type="text" name="theme_options[social_networks][]" value="'.$options['social_networks'][2].'" /><br/>';
	// youtube
	$op_social_networks .= '<label>Youtube: </label><input type="text" name="theme_options[social_networks][]" value="'.$options['social_networks'][3].'" /><br/>';
	
	echo $op_social_networks;
}



// INPUT - Name: theme_options[social_networks]
function op_bitly() {
	$options = get_option('theme_options');	
	$op_bitly = '<label>Username: </label><input type="text" name="theme_options[bitly][]" value="'.$options['bitly'][0].'" /><br/>';
	$op_bitly .= '<label>API Key: </label><input type="text" name="theme_options[bitly][]" value="'.$options['bitly'][1].'" /> - <a href="https://bitly.com/a/your_api_key" title="Generate your API key here." target="_blank">get key</a><br/>';	
	$op_bitly .= '<label>Secure: </label><input type="checkbox" name="theme_options[bitly][]" value="1" '. checked( 1, $options['bitly'][2], false ) . '/>';
	echo $op_bitly;
}



function op_map() {
	$options = op_get_theme_options();
	$op_map = $options['op_map'];
	
	echo '<div id="op_map_canvas" style="width:50%; float:left; height:205px;display:inline-block;"></div>
	<div id="op_map_settings">
	<span>Latitude:</span> <input id="op_maps_lat" type="text" size="10" name="theme_options[op_map][]" value="'.$op_map[0].'" />
	<span>&nbsp;&nbsp;Longitude:</span> <input id="op_maps_long" type="text" size="10" name="theme_options[op_map][]" value="'.$op_map[1].'" />
	<br/>
	<br/>
	<span>Map Type:</span>
	<select id="op_maps_type" name="theme_options[op_map][]" />
	<option '.($op_map[2] == 'ROADMAP' ? 'selected="selected"' : '').' value="ROADMAP">Roadmap</option>
	<option '.($op_map[2] == 'SATELLITE' ? 'selected="selected"' : '').' value="SATELLITE">Satellite</option>
	<option '.($op_map[2] == 'HYBRID' ? 'selected="selected"' : '').' value="HYBRID">Hybrid</option>
	<option '.($op_map[2] == 'TERRAIN' ? 'selected="selected"' : '').' value="TERRAIN">Terrain</option>
	</select>
	<span>&nbsp;&nbsp;Zoom:</span>
	<select id="op_maps_zoom" name="theme_options[op_map][]" />
	<option '.($op_map[3] == 12 ? 'selected="selected"' : '').' value="12">12</option>
	<option '.($op_map[3] == 13 ? 'selected="selected"' : '').' value="13">13</option>
	<option '.($op_map[3] == 14 ? 'selected="selected"' : '').' value="14">14</option>
	<option '.($op_map[3] == 15 ? 'selected="selected"' : '').' value="15">15</option>
	<option '.($op_map[3] == 16 ? 'selected="selected"' : '').' value="16">16</option>
	<option '.($op_map[3] == 17 ? 'selected="selected"' : '').' value="17">17</option>
	<option '.($op_map[3] == 18 ? 'selected="selected"' : '').' value="18">18</option>
	</select>
	<br/>
	<br/>
	<span>Address:</span> <textarea name="theme_options[op_map][]" rows="3" id="op_maps_address">'.$op_map[4].'</textarea>
	<br/>
	<span>Phone:</span> <input id="op_maps_phone" type="text" size="10" name="theme_options[op_map][]" value="'.$op_map[5].'" />
	<span>&nbsp;&nbsp;Fax:</span> <input id="op_maps_fax" type="text" size="10" name="theme_options[op_map][]" value="'.$op_map[6].'" />
	</div>';   
}



function op_get_default_color( $index, $color_scheme = null ) {
	if ( null === $color_scheme ) {
		$options = op_get_theme_options();
		$color_scheme = $options['color_scheme']['value'];
	}
	$color_schemes = op_color_schemes();
	if ( ! isset( $color_schemes[ $color_scheme ] ) ) return false;
	return $color_schemes[ $color_scheme ][$index];
}



function op_color_schemes() {
	$utils = new OpUtils();
	$color_scheme_options = array(
		'light' => array(
			'value' => 'light',
			'label' => __( 'Light', 'op' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/light.png',
			'site_bg_color' => '#efefef',
			'link_color' => array(
				'default' => '#11b2d6',
				'visited' => '#11b2d6',
				'hover' => '#1b8be0',
				'active' => '#1b8be0'
			),
			'nav_link_color' => array(
				'default' => '#2dbbda',
				'visited' => '#15b4de',
				'hover' => '#31cbed',
				'active' => '#15b4de'
			),
			'nav_current_link' => array(
				'default' => '#000',
				'visited' => '#000',
				'hover' => '#000',
				'active' => '#000'
			),
			'footer_nav_link_color' => array(
				'default' => '#b7b7b7',
				'visited' => '#b7b7b7',
				'hover' => '#d7d7d7',
				'active' => '#d7d7d7'
			),
			'footer_nav_current_link_color' => array(
				'default' => '#000',
				'visited' => '#000',
				'hover' => '#000',
				'active' => '#000'
			),
			'body_font' => array(
				'color' => '#757575'
			),
			'headers' => array(),
			'content_bg' => '#fff'
		),
		'dark' => array(
			'value' => 'dark',
			'label' => __( 'Dark', 'op' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/dark.png',
			'site_bg_color' => invert_color('#efefef'),
			'link_color' => '#e4741f',

			'site_header' => '#333',
			'content_bg' => invert_color('#fff')
		),
	);

$post_types = $utils->get_all_post_types();

	foreach ($post_types as $type) {

		$color_scheme_options['light']['headers'][$type['name']] = array(
				'color' => '#000',
				'bg_color' => '#fff'
			);
		$color_scheme_options['dark']['headers'][$type['name']] = array(
				'color' => '#fff',
				'bg_color' => '#333'
			);

	}
	// var_dump($color_scheme_options);
	return apply_filters( 'op_color_schemes', $color_scheme_options );
}



function op_layouts() {
	$layout_options = array(
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Content on left', 'op' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png',
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Content on right', 'op' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png',
		),
		'content' => array(
			'value' => 'content',
			'label' => __( 'One-column, no sidebar', 'op' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content.png',
		),
	);
	return apply_filters( 'op_layouts', $layout_options );
}



function op_get_theme_options(){
    return get_option('theme_options', op_get_default_theme_options());
}


function op_settings_tabs(){
	$tabs = array(
		THEME_PREFIX.'general' => array('section_title' => __( 'General', 'op' ),'section_desc' => 'Set the main options for the OpenPower website here.'),
		THEME_PREFIX.'headers' => array('section_title' => __( 'Headers', 'op' ),'section_desc' => 'Set the default header images for the different sections. Page headers can be overridden on individual pages.'),
		THEME_PREFIX.'typography' => array('section_title' => __( 'Typography', 'op' ),'section_desc' => 'Set link color and type-face for your website'),
		THEME_PREFIX.'contact' => array('section_title' => __( 'Contact', 'op' ),'section_desc' => 'Set the contact options for the contact form and social networks here.')
	);
	return $tabs;
}



function op_theme_layout() {
	$options = op_get_theme_options();
	foreach ( op_layouts() as $layout ) {
		?>
		<div class="layout image-radio-option theme-layout">
		<label class="description">
			<input type="radio" name="theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
			<span>
				<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" />
				<?php echo $layout['label']; ?>
			</span>
		</label>
		</div>
		<?php
	}
}



function op_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$options  = op_get_theme_options();
	$defaults = op_get_default_theme_options();
	$wp_customize->add_setting( 'theme_options[color_scheme]', array(
		'default'    => $defaults['color_scheme'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );
	$schemes = op_color_schemes();
	$choices = array();
	foreach ( $schemes as $scheme ) {
		$choices[ $scheme['value'] ] = $scheme['label'];
	}
	$wp_customize->add_control( 'op_color_scheme', array(
		'label'    => __( 'Color Scheme', 'op' ),
		'section'  => 'colors',
		'settings' => 'theme_options[color_scheme]',
		'type'     => 'radio',
		'choices'  => $choices,
		'priority' => 5,
	) );
	// Link Color (added to Color Scheme section in Theme Customizer)
	$wp_customize->add_setting( 'theme_options[link_color]', array(
		'default'           => op_get_default_color( 'link_color', $options['color_scheme'] ),
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_hex_color',
		'capability'        => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'    => __( 'Link Color', 'op' ),
		'section'  => 'colors',
		'settings' => 'theme_options[link_color]',
	) ) );
	// Default Layout
	$wp_customize->add_section( 'op_layout', array(
		'title'    => __( 'Layout', 'op' ),
		'priority' => 50,
	) );
	$wp_customize->add_setting( 'theme_options[theme_layout]', array(
		'type'              => 'option',
		'default'           => $defaults['theme_layout'],
		'sanitize_callback' => 'sanitize_key',
	) );
	$layouts = op_layouts();
	$choices = array();
	foreach ( $layouts as $layout ) {
		$choices[$layout['value']] = $layout['label'];
	}
	$wp_customize->add_control( 'theme_options[theme_layout]', array(
		'section'    => 'op_layout',
		'type'       => 'radio',
		'choices'    => $choices,
	) );
}
add_action( 'customize_register', 'op_customize_register' );



function op_customize_preview_js() {
	wp_enqueue_script( 'op-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20120523', true );
}
add_action( 'customize_preview_init', 'op_customize_preview_js' );




function op_layout_classes( $existing_classes ) {
	$options = op_get_theme_options();
	$current_layout = $options['theme_layout'];
	if ( in_array( $current_layout, array( 'content-sidebar', 'sidebar-content' ) ) )
		$classes = array( 'two-column' );
	else
		$classes = array( 'one-column' );
		
	if ( 'content-sidebar' == $current_layout )
		$classes[] = 'right-sidebar';
	elseif ( 'sidebar-content' == $current_layout )
		$classes[] = 'left-sidebar';
	else
		$classes[] = $current_layout;
	$classes = apply_filters( 'op_layout_classes', $classes, $current_layout );
	return array_merge( $existing_classes, $classes );
}
// add_filter( 'body_class', 'op_layout_classes' );
add_action('admin_menu', 'op_add_options_page');



function op_add_options_page() {	
	
	$theme_page = add_menu_page(__('OpenPower Settings', 'op'), // Name of page
	__('Theme Options', 'op'), // Label in menu
	'edit_theme_options', // Capability required
	'theme_options', // Menu slug, used to uniquely identify the page
	'op_theme_options_render_page', // Function that renders the options page
	//get_template_directory_uri() . '/images/favicon.png'
	''
	, 0);
		
	if (!$theme_page) return;
    
    
    add_action( "load-$theme_page", 'op_theme_options_help' );
}

function op_theme_options_help() {

	$general_help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, OpenPower, provides the following Theme Options:', 'op' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Color Scheme</strong>: You can choose a color palette of "Light" (light background with dark text) or "Dark" (dark background with light text) for your site.', 'op' ) . '</li>' .
				'<li>' . __( '<strong>Link Color</strong>: You can choose the color used for text links on your site. You can enter the HTML color or hex code, or you can choose visually by clicking the "Select a Color" button to pick from a color wheel.', 'op' ) . '</li>' .
				'<li>' . __( '<strong>Default Layout</strong>: You can choose if you want your site&#8217;s default layout to have a sidebar on the left, the right, or not at all.', 'op' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'op' ) . '</p>';
	
	$headers_help = '<p>' . __( 'Specific Header Images can be applied to the various sections of the website. For example, on the Portfolio page you may want to a specific work or a map on the Contact Page.', 'op' ) . '</p>' .

			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'op' ) . '</p>';

	$sidebar = '<p><strong>' . __( 'For more information:', 'op' ) . '</strong></p>' .
		'<p>' . __( '<a href="http://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">Documentation on Theme Options</a>', 'op' ) . '</p>' .
		'<p>' . __( '<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'op' ) . '</p>';

	$screen = get_current_screen();

	if ( method_exists( $screen, 'add_help_tab' ) ) {
		// WordPress 3.3
		$screen->add_help_tab( array(
			'title' => __( 'General', 'op' ),
			'id' => 'general-options-help',
			'content' => $general_help,
			)
		);
		$screen->add_help_tab( array(
			'title' => __( 'Header', 'op' ),
			'id' => 'header-options-help',
			'content' => $headers_help,
			)
		);

		$screen->set_help_sidebar( $sidebar );
	} else {
		// WordPress 3.2
		add_contextual_help( $screen, $help . $sidebar );
	}
}



function op_settings_sections($page) {
    global $wp_settings_sections, $wp_settings_fields;

    if ( !isset($wp_settings_sections) || !isset($wp_settings_sections[$page]) ) return;
	  	
	echo '<div id="op_settings">';

		echo '<ul id="op_settings_index">';
			foreach ( (array) $wp_settings_sections[$page] as $section ) echo "<li><a href='#{$section['id']}'>{$section['title']}</a></li>";
		echo '</ul>';

        foreach ( (array) $wp_settings_sections[$page] as $section ) {
			if(isset($wp_settings_fields[$page][$section['id']])) echo '<div id="'.$section['id'].'" class="op_options_panel">';
	            echo !empty($section['title']) ? "<h3>{$section['title']}</h3>\n" : "";
	            call_user_func($section['callback'], $section);
	            if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']]) ) continue;
	            echo '<table class="form-table">';
	            op_settings_fields($page, $section['id']);
	            echo '</table>';
			if(isset($wp_settings_fields[$page][$section['id']])) echo "</div>\n";
        }

	echo '</div>';
}



function op_settings_fields($page, $section) {
	global $wp_settings_fields;
	$options = get_option('theme_options');
	
	if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]) ) return;

		foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {		
			echo '<tr valign="top">';
			if ( !empty($field['args']['label_for']) ) echo '<th scope="row"><label for="' . $field['args']['label_for'] . '">' . $field['title'] . '</label></th>';
			else echo '<th scope="row">' . $field['title'] . '</th>';
			echo '<td class="'.$field['id'].' '.$field['callback'].'">';
			call_user_func($field['callback'], $field['args']);
			echo '</td>';
			echo '</tr>';
		}
}



function op_theme_options_render_page() {
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br></div>
	<?php echo "<h2><span class='dashicon'></span>" . sprintf(__('%s Theme Options', 'op'), wp_get_theme()). "</h2>";?>
	<form action="options.php" method="post">
		<?php settings_fields('theme_options'); ?>   
		<?php op_settings_sections(__FILE__); ?>      
		<p class="submit clear"><input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
	</form>
</div>
<?php
}



// Validate user data for some/all of your input fields
function op_options_validate($input) {	
	$utils = new Validate();
	$output = $defaults = op_get_default_theme_options();	
	
	// // Color scheme must be in our array of color scheme options
	// if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], op_color_schemes() ) )
	// 	$color_scheme = $input['color_scheme'];
	// // Default colors may have changed if color scheme changed
	// $output['link_color'] = $defaults['link_color'] = op_get_default_color( 'link_color', $output['color_scheme'] );

	// // Link color must be 3 or 6 hexadecimal characters
	// 
	// 	;
	// if ( isset( $input['header_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['header_color'] ) )
	// 	$input['header_color'] = '#' . strtolower( ltrim( $input['header_color'], '#' ) );
	// if ( isset( $input['link_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['link_color'] ) )
	// 	$input['link_color'] = '#' . strtolower( ltrim( $input['link_color'], '#' ) );	

	// if ( isset( $input['content_bg_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['content_bg_color'] ) )
	// 	$input['content_bg_color'] = '#' . strtolower( ltrim( $input['content_bg_color'], '#' ) );


	$output = array(
		'site_bg' => array(
			'type' => 'background',
			'title' => $output['site_bg']['title'],
			'section' => $output['site_bg']['section'],
			'value' => array (
				'bg_color' => $utils->validateColor($input['site_bg']['value']['bg_color']),
				'img' => intval( $input['site_bg']['value']['img']),
				'position' => wp_filter_nohtml_kses($input['site_bg']['value']['position']),
				'repeat' => wp_filter_nohtml_kses($input['site_bg']['value']['repeat']),
				'bg_size' => wp_filter_nohtml_kses($input['site_bg']['value']['bg_size']),
				'attachment' => wp_filter_nohtml_kses($input['site_bg']['value']['attachment'])	
				)
		),
		'link_color' => array(
			'type' => 'link_color',
			'title' => $output['link_color']['title'],
			'section' => $output['link_color']['section'],
			'desc' => $output['link_color']['desc'],
			'value' => $utils->validateLinkColor($input['link_color']['value'])			
		),
		'nav_link_color' => array(
			'type' => 'link_color',
			'title' => $output['nav_link_color']['title'],
			'section' => $output['nav_link_color']['section'],
			'desc' => $output['nav_link_color']['desc'],
			'value' => $utils->validateLinkColor($input['nav_link_color']['value'])	
		),
		'nav_current_link' => array(
			'type' => 'link_color',
			'title' => $output['nav_current_link']['title'],
			'section' => $output['nav_current_link']['section'],
			'desc' => $output['nav_current_link']['desc'],
			'value' => $utils->validateLinkColor($input['nav_current_link']['value'])
		),
		'footer_nav_link_color' => array(
			'type' => 'link_color',
			'title' => $output['footer_nav_link_color']['title'],
			'section' => $output['footer_nav_link_color']['section'],
			'desc' => $output['footer_nav_link_color']['desc'],
			'value' => $utils->validateLinkColor($input['footer_nav_link_color']['value'])
		),
		'footer_nav_current_link_color' => array(
			'type' => 'link_color',
			'title' => $output['footer_nav_current_link_color']['title'],
			'section' => $output['footer_nav_current_link_color']['section'],
			'desc' => $output['footer_nav_current_link_color']['desc'],
			'value' => $utils->validateLinkColor($input['footer_nav_current_link_color']['value'])
		),
		'body_font' => array (
			'type' => 'font',
			'title' => $output['body_font']['title'],
			'section' => $output['body_font']['section'],
			'desc' => $output['body_font']['desc'],
			'value' => $utils->validateFont($input['body_font']['value'])
		),
		'headers' => array (
			'type' => 'headers',
			'title' => $output['headers']['title'],
			'section' => $output['headers']['section'],
			'desc' => $output['headers']['desc'],
			'value' => $utils->validateHeaders($input['headers']['value'])
		)


		
	// 	'header_color' => $input['header_color'],
	// 	'content_bg_color' => $input['content_bg_color'],
	// 	'link_color' => $input['link_color'],
	// 	'color_scheme' => $color_scheme,
	// 	'theme_layout' => $input['theme_layout'],

	// 	'bitly' => array(
	// 				  wp_filter_nohtml_kses($input['bitly'][0]),
	// 				  wp_filter_nohtml_kses($input['bitly'][1]),
	// 				  (isset( $input['bitly'][2] ) && true == $input['bitly'][2] ? true : false )
	// 	),
	// 	'social_networks' => array(
	// 		$input['social_networks'][0],
	// 		$input['social_networks'][1],
	// 		$input['social_networks'][2],
	// 		$input['social_networks'][3]		
	// 	),
	// 	'op_map' => array(
	// 					  wp_filter_nohtml_kses($input['op_map'][0]),
	// 					  wp_filter_nohtml_kses($input['op_map'][1]),
	// 					  wp_filter_nohtml_kses($input['op_map'][2]),
	// 					  $input['op_map'][3] == 1 ? 1 : 0,
	// 					  wp_filter_nohtml_kses($input['op_map'][4]),
	// 					  wp_filter_nohtml_kses($input['op_map'][5]),
	// 					  wp_filter_nohtml_kses($input['op_map'][6])
	// 	)
	);

	

	return apply_filters( 'op_options_validate', $output, $input, $defaults );
}



function op_get_default_theme_options(){
	$utils = new OpUtils();

	// Light colors
	$link_light = op_get_default_color( 'link_color', 'light' );
	$nav_link_color = op_get_default_color( 'nav_link_color', 'light' );
	$nav_current_link = op_get_default_color( 'nav_current_link', 'light' );
	$footer_nav_link_color = op_get_default_color( 'footer_nav_link_color', 'light' );
	$footer_nav_current_link_color = op_get_default_color( 'footer_nav_current_link_color', 'light' );
	$body_font = op_get_default_color( 'body_font', 'light' );


	$default_theme_options = array(

		'site_bg'   => array(
			'type' => 'background',
			'title' => __('Site Background:','kwik'),
			'section' => THEME_PREFIX.'general',
			'value' => array(
				'bg_color' => '#ffffff',
				'img' => '',
				'position' => '0 0',
				'repeat' => 'repeat',
				'bg_size' => '',
				'attachment' => ''
				)
		),

		'link_color'   => array(
			'type' => 'link_color',
			'title' => __('Link Color:','kwik'),
			'section' => THEME_PREFIX.'typography',
			'desc' => __('Set the colors for hyperlinks on your site.','kwik'),
			'value' => array(
				'default' => $link_light['default'],
				'visited' => $link_light['visited'],
				'hover' => $link_light['hover'],
				'active' => $link_light['active'],
				)
		),

		'nav_link_color'   => array(
			'type' => 'link_color',
			'title' => __('Nav Link Color:','kwik'),
			'section' => THEME_PREFIX.'typography',
			'desc' => __('Set the colors for primary navigation of your website','kwik'),
			'value' => array(
				'default' => $nav_link_color['default'],
				'visited' => $nav_link_color['visited'],
				'hover' => $nav_link_color['hover'],
				'active' => $nav_link_color['active'],
				)
		),
		'nav_current_link'   => array(
			'type' => 'link_color',
			'title' => __('Current Nav Link Color:','kwik'),
			'section' => THEME_PREFIX.'typography',
			'desc' => __('What color should current page links look be?','kwik'),
			'value' => array(
				'default' => $nav_current_link['default'],
				'visited' => $nav_current_link['visited'],
				'hover' => $nav_current_link['hover'],
				'active' => $nav_current_link['active'],
				)
		),
		'footer_nav_link_color'   => array(
			'type' => 'link_color',
			'title' => __('Footer Nav Link Color:','kwik'),
			'section' => THEME_PREFIX.'typography',
			'desc' => __('What color should current page links look be?','kwik'),
			'value' => array(
				'default' => $footer_nav_link_color['default'],
				'visited' => $footer_nav_link_color['visited'],
				'hover' => $footer_nav_link_color['hover'],
				'active' => $footer_nav_link_color['active'],
				)
		),
		'footer_nav_current_link_color'   => array(
			'type' => 'link_color',
			'title' => __('Footer Current Nav Link Color:','kwik'),
			'section' => THEME_PREFIX.'typography',
			'desc' => __('What color should current page links in footer look like?','kwik'),
			'value' => array(
				'default' => $footer_nav_current_link_color['default'],
				'visited' => $footer_nav_current_link_color['visited'],
				'hover' => $footer_nav_current_link_color['hover'],
				'active' => $footer_nav_current_link_color['active'],
				)
		),
		'body_font'   => array(
			'type' => 'font',
			'title' => __('Body Type-Face:','kwik'),
			'section' => THEME_PREFIX.'typography',
			'desc' => __('Set the type-face for the body element.','kwik'),
			'value' => array(
				'color' => $body_font['color'],
				'weight' => 'normal',
				'size' => '13',
				'line-height' => '17',
				'font-family' => 'Open+Sans'
				)
		),

		'headers'   => array(
			'type' => 'headers',
			'title' => __('Site Headers:','kwik'),
			'section' => THEME_PREFIX.'headers',
			'desc' => __('Set the header image for major sections of the site. (post, custom post types, and default page and 404 headers)','kwik'),
			'value' => array()
		),




		// 'theme_layout'  => array(
		// 	'type' => 'color',
		// 	'name' => 'theme_layout',
		// 	'title' => __('Theme Layout:','kwik'),
		// 	'value' => 'content_sidebar'
		// )

		'social_networks'  => array(
			'type' => 'social',
			'title' => __('Social Networks:','kwik'),
			'section' => THEME_PREFIX.'contact',
			'desc' => __('Connect with your audience by setting your social networks here','kwik'),
			'value' => array(
				array(
					'title' => 'Facebook',
					'img' => 0,
					'color' => '#3B5998',
					'url' => '#'
					),
				array(
					'title' => 'Twitter',
					'img' => 0,
					'color' => '#4099FF',
					'url' => 'https://twitter.com/kevinchappell'
				)				
			)
		)
	);


	$post_types = $utils->get_all_post_types();

	foreach ($post_types as $type) {

		$headers = op_get_default_color( 'headers', 'light' );

		$default_theme_options['headers']['value'][$type['name']] = array(
					'color' => $headers[$type['name']]['color'],
					'weight' => 'normal',
					'size' => '16',
					'line-height' => '17',
					'font-family' => 'Open+Sans',
					'bg_color' => '#ffffff',
					'img' => '',
					'position' => '0 0',
					'repeat' => 'repeat',
					'bg_size' => '',
					'attachment' => '',
					'text' => ''
				);
	}


	if ( is_rtl() ) $default_theme_options['theme_layout'] = 'sidebar-content';
	
	return apply_filters( 'op_default_options', $default_theme_options );
}