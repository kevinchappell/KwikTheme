<?php
/**
 * Registers our sidebars
 *
 * @since KwikTheme 1.0
 */
function kt_widgets_init() {

  register_sidebar(array(
    'name' => __('Main Sidebar', 'kwik'),
    'id' => 'sidebar-1',
    'description' => __('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'kwik'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));

  register_sidebar(array(
    'name' => __('Newsroom Sidebar', 'kwik'),
    'id' => 'sidebar-newsroom',
    'description' => __('Appears in Newsroom', 'kwik'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));

  register_sidebar(array(
    'name' => __('Horizontal Widget Area 1', 'kwik'),
    'id' => 'sidebar-horz-1',
    'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'kwik'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title"><span>',
    'after_title' => '</span></h3>',
  ));
  register_sidebar(array(
    'name' => __('Horizontal Widget Area 2', 'kwik'),
    'id' => 'sidebar-horz-2',
    'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'kwik'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title"><span>',
    'after_title' => '</span></h3>',
  ));

  register_sidebar(array(
    'name' => __('Top Bar', 'kwik'),
    'id' => 'sidebar-top-bar',
    'description' => __('Widgets for the very top of the site.', 'kwik'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title"><span>',
    'after_title' => '</span></h3>',
  ));

  register_sidebar(array(
    'name' => __('Footer Widgets', 'kwik'),
    'id' => 'footer_widgets',
    'description' => __('Appears in the footer', 'kwik'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));

}
add_action('widgets_init', 'kt_widgets_init');

/* Additional css classes for widgets */
add_filter( 'dynamic_sidebar_params', 'kwik_widget_classes' );

/**
 * add widget classes to deal with styling issues in older (IE) browsers
 * @param  array $params widget params
 * @return array        modified widget params
 */
function kwik_widget_classes( $params ) {

    global $genbu_widget_num; // Global a counter array
    $this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
    $arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets

    if ( !$genbu_widget_num ) {// If the counter array doesn't exist, create it
        $genbu_widget_num = array();
    }

    if ( !isset( $arr_registered_widgets[$this_id] ) || !is_array( $arr_registered_widgets[$this_id] ) ) { // Check if the current sidebar has no widgets
        return $params; // No widgets in this sidebar... bail early.
    }

    if ( isset($genbu_widget_num[$this_id] ) ) { // See if the counter array has an entry for this sidebar
        $genbu_widget_num[$this_id] ++;
    } else { // If not, create it starting with 1
        $genbu_widget_num[$this_id] = 1;
    }

    $class = 'class="widget widget-' . $genbu_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options

    if ( $genbu_widget_num[$this_id] == 1 ) { // If this is the first widget
        $class .= 'widget widget-first ';
    } elseif( $genbu_widget_num[$this_id] == count( $arr_registered_widgets[$this_id] ) ) { // If this is the last widget
        $class .= 'widget widget-last ';
    }

    $params[0]['before_widget'] = str_replace( 'class="widget ', $class, $params[0]['before_widget'] ); // Insert our new classes into "before widget"

    return $params;

}
