<?php

/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

$options = KwikThemeOptions::kt_get_options();

?>
<!DOCTYPE html>

<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>

<!--<![endif]-->

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<meta name="description" content="<?php echo get_meta_description();  ?>" />
<meta name="keywords" content="<?php echo get_SEO_tags(); ?>" />
<title>
<?php wp_title( '|', true, 'right' ); ?>
</title>
<meta property="og:title" content="<?php bloginfo('name'); ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php bloginfo('url'); ?>" />
<meta property="og:image" content="<?php bloginfo('template_directory'); ?>/images/logo.png" />
<meta property="og:description" content="<?php echo get_meta_description();  ?>" />
<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
<meta property="fb:admins" content="1511210009" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" >
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link href="<?php bloginfo('template_directory') ?>/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>

<!--[if lt IE 9]>

<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>

<![endif]-->

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
  <?php include_once("analyticstracking.php") ?>
<div id="page" class="hfeed site">
<div id="tkt_bar">
  <div class="inner clear">
    <?php dynamic_sidebar( 'sidebar-top-bar' ); ?>
  </div>
</div>
<header id="masthead" role="banner">
  <div class="inner site-header">
    <h1 class="site-title logo_wrap">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
        <?php bloginfo( 'name' ); ?>
      </a>
    </h1>
    <h2 class="site-description"><?php echo str_replace(".", "<span>.</span>", get_bloginfo( 'description' )); ?></h2>
  <nav id="site-navigation" class="main-navigation" role="navigation">
    <h3 class="menu-toggle">
      <?php _e( 'Menu', 'kwik' ); ?>
    </h3>
    <a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to content', 'kwik' ); ?>">
    <?php _e( 'Skip to content', 'kwik' ); ?>
    </a>
    <?php wp_nav_menu( array( 'theme_location' => 'main', 'menu_class' => 'nav-menu' ) ); ?>
  </nav>
  <!-- #site-navigation -->
  </div>
</header>
<!-- #masthead -->

<div id="main" class="wrapper">
  <div class="inner">

<?php echo kt_content_header($wp_query);?>

<?php

if(function_exists('bcn_display') && !is_page(2)){
	echo '<div class="breadcrumbs">';
	// if (function_exists('brand_breadcrumbs')) echo brand_breadcrumbs(false);
	bcn_display();
	echo '</div>';
}


echo kt_child_links();