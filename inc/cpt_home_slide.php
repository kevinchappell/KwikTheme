<?php
add_action('init', 'home_slide_cpt_init');
function home_slide_cpt_init()
{
    register_post_type('home_slide', array(
        'labels' => array(
            'name' => __('Home Slides', 'op'),
            'singular_name' => __('Slide', 'op'),
            'add_new' => __('Add Slide', 'op'),
            'add_new_item' => __('Add New Home Page Slide', 'op')
        ),
        'menu_icon' => 'dashicons-format-gallery',
        'menu_position' => 3,
        'register_meta_box_cb' => 'add_home_slide_metaboxes',
        'supports' => array(
            'title',
            'editor',
            'author',
            'thumbnail'
        ),
        'public' => true,
        'exclude_from_search' => true,
        'has_archive' => false
    ));
    add_image_size('home_slide', 920, 230, true);
}


add_action('admin_init', 'slider_options_init');
function slider_options_init(){
    register_setting('slider_options', 'slider_options', 'slider_options_validate');
}


function add_home_slide_script($hook) {
     $screen = get_current_screen();

     // make these settings
     $post_types_array = array(
        "home_slide",
        "page",
        "home_slide_page_slide-order"
        );

     $hooks_array = array(
        "post.php",
        "post-new.php"
        );
     
    // Check screen hook and current post type
    if ( in_array($screen->post_type, $post_types_array)){
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script( 'cpt_home_slide',  get_template_directory_uri() . '/inc/cpt_home_slide.js', array('jquery'), NULL, true );      

    }   elseif('edit.php' == $hook && 'home_slide' == $screen->post_type){

    }
}
add_action( 'admin_enqueue_scripts', 'add_home_slide_script');



// INPUT - Name: slider_options[slider_speed]
function op_home_slider()
{
    $options          = op_get_slider_options();
    $effects          = array(
        'blindX' => __('Blind X', 'op'),
        'blindY' => __('Blind Y', 'op'),
        'blindZ' => __('Blind Z', 'op'),
        'cover' => __('Cover', 'op'),
        'curtainX' => __('Curtain X', 'op'),
        'curtainY' => __('Curtain Y', 'op'),
        'fade' => __('Fade', 'op'),
        'fadeZoom' => __('Fade Zoom', 'op'),
        'growX' => __('Grow X', 'op'),
        'growY' => __('Grow Y', 'op'),
        'none' => __('None', 'op'),
        'scrollUp' => __('Scroll Up', 'op'),
        'scrollDown' => __('Scroll Down', 'op'),
        'scrollLeft' => __('Scroll Left', 'op'),
        'scrollRight' => __('Scroll Right', 'op'),
        'scrollHorz' => __('Scroll Horizontal', 'op'),
        'scrollVert' => __('Scroll Vertical', 'op'),
        'shuffle' => __('Shuffle', 'op'),
        'slideX' => __('Slide X', 'op'),
        'slideY' => __('Slide Y', 'op'),
        'tiles' => __('Tiles', 'op'),
        'toss' => __('Toss', 'op'),
        'turnUp' => __('Turn Up', 'op'),
        'turnDown' => __('Turn Down', 'op'),
        'turnLeft' => __('Turn Left', 'op'),
        'turnRight' => __('Turn Right', 'op'),
        'uncover' => __('Uncover', 'op'),
        'wipe' => __('Wipe', 'op'),
        'zoom' => __('Zoom', 'op')
    );
    $op_home_slider = '<p><label>' . __('Transition Speed', 'op') . ':</label><input type="text" name="slider_options[home_slider][speed]" value="' . $options['home_slider']['speed'] . '" /></p>';
    $op_home_slider .= '<p><label>' . __('Transition Effect', 'op') . ':</label>';
    $op_home_slider .= '<select name="slider_options[home_slider][fx]" value="' . $options['home_slider']['fx'] . '" /></p>';
    foreach ($effects as $k => $v) {
        $op_home_slider .= '<option value="' . $k . '" ' . ($options['home_slider']['fx'] == $k ? 'selected="selected"' : '') . '>' . $v . '</option>';
    }
    $op_home_slider .= '</select></p>';
    $op_home_slider .= '<p><label>' . __('Timeout', 'op') . ':</label><input type="text" name="slider_options[home_slider][delay]" value="' . $options['home_slider']['delay'] . '" /></p>';
    $op_home_slider .= '<p><label>' . __('Background Color', 'op') . ':</label><input type="text" name="slider_options[home_slider_bg]" class="cpicker" id="slider-bg-color" value="' . esc_attr($options['home_slider_bg']) . '" />' . (!empty($options['home_slider_bg']) ? '<span class="clear_color tooltip" title="Clearing the color will make it transparent"></span>' : '');
 
    //$op_home_slider .= '<p><label>'.__('<span class="tooltip" title="Ambifade is a KC Design lab original effect for the home slider and page header, check the documentation for details">Ambifade</span>','op').':</label><input type="checkbox" name="slider_options[ambifade][]" id="ambifade" value="1" '. checked( 1, $options['ambifade'][0], false ) . ' /></p>';
    //$op_home_slider .= '<p><label>'.__('Ambifade to...','op').'</label><select name="slider_options[ambifade][]"><option value="background" '.($options['ambifade'][1] == 'background' ? 'selected="selected"': '').'>Background</option><option value="opaque" '.($options['ambifade'][1] == 'opaque' ? 'selected="selected"': '').'>Opaque</option><option value="transparent" '.($options['ambifade'][1] == 'transparent' ? 'selected="selected"': '').'>Transparent</option></select>';
    echo $op_home_slider;
}
function op_get_slider_options(){
    return get_option('slider_options', default_slider_options());
}
function default_slider_options()
{
    $default_slider_options = array(
        'home_slider' => array(
            'speed' => 750,
            'fx' => 'fade',
            'delay' => 4000
        ),
        'home_slider_bg' => '#336699',
        /*		'ambifade' => array(
        
        true,
        
        'background'
        
        )*/
    );
    return apply_filters('default_slider_options', $default_slider_options);
}
// Validate user data for some/all of your input fields
function slider_options_validate($input)
{
    $output = $defaults = default_slider_options();
    if (isset($input['home_slider_bg']) && preg_match('/^#?([a-f0-9]{3}){1,2}$/i', $input['home_slider_bg']))
        $input['home_slider_bg'] = '#' . strtolower(ltrim($input['home_slider_bg'], '#'));
    $output = array(
        'home_slider' => array(
            'speed' => intval($input['home_slider']['speed']),
            'fx' => wp_filter_nohtml_kses($input['home_slider']['fx']),
            'delay' => intval($input['home_slider']['delay'])
        ),
        'home_slider_bg' => $input['home_slider_bg']
        /*		'ambifade' => array(
        
        (isset( $input['ambifade'][0] ) && true == $input['ambifade'][0] ? true : false ),
        
        wp_filter_nohtml_kses($input['ambifade'][1]),
        
        )*/
    );
    return apply_filters('slider_options_validate', $output, $input, $defaults);
}
// Add the home slide meta box
function add_home_slide_metaboxes()
{
    add_meta_box('op_home_slide_meta', 'Slide Details', 'op_home_slide_meta', 'home_slide', 'side', 'default');
}
// The Home Slide meta box
function op_home_slide_meta()
{
    global $post;
    $home_slide_meta = '';
    // Noncename for security check on data origin
    $home_slide_meta .= '<input type="hidden" name="home_slide_noncename" id="home_slide_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
    // Get the current data	
    $op_home_slide_link        = get_post_meta($post->ID, 'op_home_slide_link', true);
    $op_home_slide_link_target = get_post_meta($post->ID, 'op_home_slide_link_target', true);
    $op_learn_more             = get_post_meta($post->ID, 'op_learn_more', true);
    $home_slide_meta .= '<label>Link:</label>';
    $home_slide_meta .= '<input type="text" name="op_home_slide_link" value="' . $op_home_slide_link . '" class="widefat" />';
    $home_slide_meta .= '<br/>';
    $home_slide_meta .= '<label>Target:</label>';
    $home_slide_meta .= '<select name="op_home_slide_link_target" class="widefat" >';
    $home_slide_meta .= '<option value="_blank" ' . ($op_home_slide_link_target == '_blank' ? 'selected="selected"' : '') . '>New Window/Tab</option>';
    $home_slide_meta .= '<option value="_self" ' . ($op_home_slide_link_target == '_self' ? 'selected="selected"' : '') . '>Same Page</option>';
    $home_slide_meta .= '</select>';
    $home_slide_meta .= '<br/>';
    $home_slide_meta .= '<label>Learn More Text:</label>';
    $home_slide_meta .= '<input type="text" name="op_learn_more" value="' . $op_learn_more . '" class="widefat" />';
    echo $home_slide_meta;
}
// Save the Metabox Data 
function save_home_slide_meta($post_id, $post)
{
    if ($post->post_type == 'home_slide') {
        $slider_options = get_option('slider_options');
        // make sure there is no conflict with other post save function and verify the noncename
        if (isset($_POST['home_slide_noncename']) && !wp_verify_nonce($_POST['home_slide_noncename'], plugin_basename(__FILE__))) {
            return $post->ID;
        }
        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post->ID))
            return $post->ID;
        if ($post->post_status != 'auto-draft') {
            $home_slide_meta = array(
                'op_home_slide_link' => wp_filter_nohtml_kses($_POST['op_home_slide_link']),
                'op_home_slide_link_target' => wp_filter_nohtml_kses($_POST['op_home_slide_link_target']),
                'op_learn_more' => wp_filter_nohtml_kses($_POST['op_learn_more'])
            );
            // Add values of $home_slide_meta as custom fields 
            foreach ($home_slide_meta as $key => $value) {
                if ($post->post_type == 'revision')
                    return;
                __update_post_meta($post->ID, $key, $value);
            }
        } else {
            return;
        }
    } else {
        return;
    }
}
add_action('save_post', 'save_home_slide_meta', 1, 2);
add_action('admin_menu', 'register_home_slide_menu');
function register_home_slide_menu(){
    add_submenu_page('edit.php?post_type=home_slide', 'Order Slides', 'Order', 'edit_pages', 'slide-order', 'home_slide_order_page');
    add_submenu_page('edit.php?post_type=home_slide', 'Slider Settings', 'Settings', 'edit_pages', 'slide-settings', 'home_slide_settings_page');
}
function home_slide_settings_page(){
?>

	<div class="wrap">

		<h2>Slider Settings</h2>

		<p>Change the transition effect, duration and speed here.</p>

        <form action="options.php" method="post">

            <?php
    settings_fields('slider_options');
    op_home_slider();
    submit_button();
?>

        </form>	

    </div>

<?php
}


function home_slide_order_page(){
?>

	<div class="wrap">

		<h2>Sort Home Page Slides</h2>

		<p>Simply drag the slide up or down and they will be saved in that order.</p>

	<?php
    $slides = new WP_Query(array(
        'post_type' => 'home_slide',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'menu_order'
    ));
?>

	<?php
    if ($slides->have_posts()):
?>
		<table class="wp-list-table widefat fixed posts" id="sortable-table">
			<thead>
				<tr>
					<th class="column-order">Order</th>
					<th class="column-thumbnail">Thumbnail</th>
					<th class="column-title">Title</th>
				</tr>
			</thead>
			<tbody data-post-type="slide">
			<?php
            while ($slides->have_posts()): $slides->the_post();
            ?>

				<tr id="post-<?php the_ID(); ?>">

					<td class="column-order"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/move.png'; ?>" title="" alt="Move Slide" width="30" height="30" class="" /></td>
					<td class="column-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></td>
					<td class="column-title">
                        <strong><?php the_title();?></strong>
                        <div class="excerpt"><?php the_excerpt(); ?></div>
                    </td>

				</tr>

			<?php endwhile; ?>

			</tbody>
			<tfoot>
				<tr>
					<th class="column-order">Order</th>
					<th class="column-thumbnail">Thumbnail</th>
					<th class="column-title">Title</th>
				</tr>
			</tfoot>
		</table>
	<?php else: ?>
		<p>No slides found, why not <a href="post-new.php?post_type=home_slide">create one?</a></p>
	<?php endif; ?>

	<?php wp_reset_postdata(); // Don't forget to reset again! ?>



	<style>

		/* Dodgy CSS ^_^ */

		#sortable-table td { background: white; }

		#sortable-table .column-order { padding: 3px 10px; width: 50px; }

			#sortable-table .column-order img { cursor: move; }

		#sortable-table td.column-order { vertical-align: middle; text-align: center; }

		#sortable-table .column-thumbnail { width: 160px; }

	</style>



	</div><!-- .wrap -->



<?php
}
add_action('wp_ajax_home_slide_update_post_order', 'home_slide_update_post_order');
function home_slide_update_post_order()
{
    global $wpdb;
    $post_type = $_POST['postType'];
    $order     = $_POST['order'];
    /**
     *    Expect: $sorted = array(
     *                menu_order => post-XX
     *            );
     */
    foreach ($order as $menu_order => $post_id) {
        $post_id    = intval(str_ireplace('post-', '', $post_id));
        $menu_order = intval($menu_order);
        wp_update_post(array(
            'ID' => $post_id,
            'menu_order' => $menu_order
        ));
    }
    die('1');
}
?>
