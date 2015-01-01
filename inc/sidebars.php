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