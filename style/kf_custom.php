<?php

header("Content-type: text/css; charset: UTF-8");
define('WP_USE_THEMES', false);
include ($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

$options = KwikThemeOptions::kt_get_options();

/*	$link_color = $options['link_color'];
$site_bg = $options['site_bg'];

$bg_image = wp_get_attachment_image_src($options['site_bg'][1], array(1280,800) );
$bg_image = $bg_image['0'];

$social_links_btm_margin = (((count(array_filter($options['social_networks']))+2)*65)+20)/2;
$social_links_right_margin = (((count(array_filter($options['social_networks']))+2)*49)+1)/2;
 */

if ($options['body_font']['value']['font-family']) {
	echo '@import url(http://fonts.googleapis.com/css?family=' . $options['body_font']['value']['font-family'] . ');';
}
?>

body {
    font-family: "<?php echo str_replace('+', ' ', $options['body_font']['value']['font-family']);?>" Arial, Helvetica, sans-serif;
    font-size:<?php echo $options['body_font']['value']['size'];?>px;
    line-height:<?php echo $options['body_font']['value']['line-height'];?>px;
    font-weight:<?php echo $options['body_font']['value']['weight'];?>;
    color: <?php echo $options['body_font']['value']['color'];?>;
}

/* Link color */
a, a strong{
	color: <?php echo $options['link_color']['value']['default'];?>;
}

a:hover, a:hover strong {
	color: <?php echo $options['link_color']['value']['hover'];?>;
}
/* Nav Link color */
.main-navigation li a{
	color: <?php echo $options['nav_link_color']['value']['default'];?>;
}
.main-navigation li a:hover{
	color: <?php echo $options['nav_link_color']['value']['hover'];?>;
}
.main-navigation .current-menu-item > a,
.main-navigation .current-menu-ancestor > a,
.main-navigation .current_page_item > a,
.main-navigation .current_page_ancestor > a{
    color: <?php echo $options['nav_current_link']['value']['default'];?>;
}

.main-navigation li ul a, .main-navigation li.current-menu-item ul a {
    /*background-color: rgba(255,255,255,.9);*/
    background-color: #fff;
    color: <?php echo $options['nav_link_color']['value']['default'];?>;
}

.main-navigation li ul li a:hover {
    color: <?php echo $options['nav_link_color']['value']['hover'];?>;
}

.main-navigation .current-menu-item > ul a,
.main-navigation .current-menu-ancestor > ul a,
.main-navigation .current_page_item > ul a,
.main-navigation .current_page_ancestor > ul a {

}


footer#footer .menu li li a {
    color: <?php echo $options['footer_nav_link_color']['value']['default'];?>;
    font-weight:normal
}
footer#footer .menu li li a:hover {
    color: <?php echo $options['footer_nav_link_color']['value']['hover'];?>;
}

.template-front-page .front-widgets h3 {
  font: normal 18px "Helvetica Neue", Arial, Helvetica, sans-serif;
  color: #000;
}
