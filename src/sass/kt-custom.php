<?php

header("Content-type: text/css; charset: UTF-8");
define('WP_USE_THEMES', false);
include $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

$options = KwikThemeOptions::kt_get_options();

/*
$link_color = $options['link_color'];
$site_bg = $options['site_bg'];

$bg_image = wp_get_attachment_image_src($options['site_bg'][1], array(1280,800) );
$bg_image = $bg_image['0'];

$social_links_btm_margin = (((count(array_filter($options['social_networks']))+2)*65)+20)/2;
$social_links_right_margin = (((count(array_filter($options['social_networks']))+2)*49)+1)/2;
 */

if ($options['body_font']['font-family']) {
    echo '@import url(http://fonts.googleapis.com/css?family=' . $options['body_font']['font-family'] . ');';
}
?>


body {
<?php echo KwikUtils::font_css($options['body_font']);?>
}

/* Link color */
a {
    <?php echo KwikUtils::text_style($options['link']);?>
}

a:hover, a:hover strong {
    <?php echo KwikUtils::text_style($options['link_hover']);?>
}
a:active {
  outline: 1px solid <?php echo $options['link']['color'];?>;
}
a:focus {
  outline: 1px dotted <?php echo $options['link']['color'];?>;
}


/* Nav Link color */
.main-navigation li a{
<?php echo KwikUtils::font_css($options['menu_link']);?>
}
.main-navigation li a:hover{
    <?php echo KwikUtils::text_style($options['menu_link_hover']);?>
}

.menu-toggle:hover,
button:hover,
input[type="button"]:hover,
input[type="reset"]:hover,
article.post-password-required input[type=submit]:hover{
	color: #ffffff;
	background-color: <?php echo $options['menu_link_hover']['color'] ?>;
}


.main-navigation li ul{
    <?php echo KwikUtils::text_style($options['menu_link_hover']);?>
}

<?php if ( isset( $options['h1'] ) ) { ?>
	.entry-header h1.entry-title, h1.entry-title, h1 {
		<?php echo KwikUtils::font_css( $options['h1'] );?>
	}
<?php } ?>
<?php if ( isset( $options['h2'] ) ) { ?>
	h2 {
		<?php echo KwikUtils::font_css( $options['h2'] );?>
	}
<?php } ?>

<?php if ( isset( $options['h3'] ) ) { ?>
	h3, .widget-area .widget h3, h3.widget-title {
		<?php echo KwikUtils::font_css( $options['h3'] );?>
	}
<?php } ?>

<?php if ( isset( $options['show_site_desc'] ) ) {?>
	.site-header h2 {
		display:block;
		<?php echo KwikUtils::font_css( $options['desc_style'] );?>
	}
<?php } ?>
<?php if ( isset($options['show_site_name'] ) ) {?>
	.site-header h1 {
		display:block;
	}
	.site-header h1 a{
		<?php echo KwikUtils::font_css( $options['name_style'] );?>
	}
<?php }?>



