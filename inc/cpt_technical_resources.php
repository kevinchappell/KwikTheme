<?php


function technical_resources_cpt() {

  technical_resources_taxonomies();

  $labels = array(
    'name'               => _x( 'Technical Resources', 'post type general name' ),
    'singular_name'      => _x( 'Technical Resource', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'book' ),
    'add_new_item'       => __( 'Add New Resource' ),
    'edit_item'          => __( 'Edit Resource' ),
    'new_item'           => __( 'New Resource' ),
    'all_items'          => __( 'All Technical Resources' ),
    'view_item'          => __( 'View Technical Resources' ),
    'search_items'       => __( 'Search Technical Resources' ),
    'not_found'          => __( 'No Technical Resources found' ),
    'not_found_in_trash' => __( 'No Technical Resources found in the Trash' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Technical Resources'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our technical resources and resource specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,
    'register_meta_box_cb' => 'add_tr_metabox'

  );
  register_post_type( 'technical_resources', $args );
  flush_rewrite_rules();
}
add_action( 'init', 'technical_resources_cpt' );

function technical_resources_taxonomies() {
  $labels = array(
    'name'              => _x( 'Technical Resource Categories', 'taxonomy general name' ),
    'singular_name'     => _x( 'Technical Resource Category', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Technical Resource Categories' ),
    'all_items'         => __( 'All Technical Resource Categories' ),
    'parent_item'       => __( 'Parent Technical Resource Category' ),
    'parent_item_colon' => __( 'Parent Technical Resource Category:' ),
    'edit_item'         => __( 'Edit Technical Resource Category' ),
    'update_item'       => __( 'Update Technical Resource Category' ),
    'add_new_item'      => __( 'Add New Technical Resource Category' ),
    'new_item_name'     => __( 'New Technical Resource Category' ),
    'menu_name'         => __( 'Technical Resource Categories' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'technical_resources_category', 'technical_resources', $args );
}


function technical_resources_activation() {
    technical_resources_taxonomies();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'technical_resources_activation' );

function technical_resources_deactivation() {
    flush_rewrite_rules();
}
add_action( 'switch_theme', 'technical_resources_deactivation' );




// ----------------------
// META
// ----------------------


// Add the meta box
function add_tr_metabox(){
  add_meta_box('tr_meta', 'Link Technical Resource to Client', 'tr_meta', 'technical_resources', 'normal', 'default');
}

// The Technical Resuorce edit page Meta box
function tr_meta(){
  global $post;

  $client_link = get_post_meta($post->ID, '_client_link', true);

  $tr_meta = '';
    // Noncename for security check on data origin
  $tr_meta .= '<input type="hidden" name="tr_meta_noncename" id="tr_meta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
  $tr_meta .= '<div class="meta_wrap client_link">';
    $tr_meta .= '<ul>';
      $tr_meta .= '<li>';
        if($client_link !== ""){
          $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($client_link), 'client_logo' );
          $tr_meta .= '<label class="client_thumb"><img src="'.$thumb[0].'"/></label>';
        } else {
          $tr_meta .= '<label class="client_thumb">'.__('Client Link','KwikTheme').'</label>';
        }
          $tr_meta .= '<input type="text" name="_client_link_title" id="client_link_title"" value="'.($client_link != "" ? get_the_title($client_link) : "").'" />';
          $tr_meta .= '<input type="hidden" id="client_link_id" name="_client_link" value="'.$client_link.'" />';
          $tr_meta .= '<label>&nbsp;</label>';
          $tr_meta .= '<span class="remove_client_link">remove</span>';
          // $tr_meta .= '<small class="">&nbsp;</small>';
        $tr_meta .= '</li>';
    $tr_meta .= '</ul>';
  $tr_meta .= '</div>';

  $tr_meta .= '<br class="clear"/>';

  echo  $tr_meta;

}


// Save the Metabox Data
function save_tr_meta($post_id, $post){


  if($post->post_type!='technical_resources') return $post->ID;
    // make sure there is no conflict with other post save function and verify the noncename
    if (!wp_verify_nonce($_POST['tr_meta_noncename'], plugin_basename(__FILE__))) {
        return $post->ID;
    }

    // Is the user allowed to edit the post or page?
    if (!current_user_can('edit_post', $post->ID)) return $post->ID;

    $tr_meta = array(
      '_client_link' => $_POST['_client_link'],
    );

    // Add values of $tr_meta as custom fields
    foreach ($tr_meta as $key => $value) {
        if( $post->post_type == 'revision' ) return;
        __update_post_meta( $post->ID, $key, $value );
    }

}
add_action('save_post', 'save_tr_meta', 1, 2);





function add_technical_resources_script($hook) {
  wp_enqueue_script('jquery-ui-autocomplete');
  // Check screen hook and current post type
  if ( 'technical_resources_page_technical_resources-settings' == $hook || ('post.php' == $hook && get_post_type() == "technical_resources") ){
    wp_enqueue_media();
    wp_enqueue_script( 'cpt_technical_resources',  get_template_directory_uri() . '/inc/cpt_technical_resources.js', array('jquery-ui-autocomplete'), NULL, true );
  }
}
add_action( 'admin_enqueue_scripts', 'add_technical_resources_script');


function content_client_link($post_id){
  $client_link = get_post_meta($post_id, '_client_link', true);

  if($client_link !== ""){
    $output = '';
    $output .= '<div class="client_link">';
    $output .= '<a href="'.get_the_permalink($client_link).'">';
    $output .= get_the_post_thumbnail( $client_link, 'client_logo' );
    $output .= '</a>';
    // $output .= get_the_title($client_link);
    $output .= '</div>';

    echo $output;
  }

}

function has_client_link($post_id){
  $client_link = get_post_meta($post_id, '_client_link', true);
  echo $client_link !== "" ? ' has_client_link' : '';
}