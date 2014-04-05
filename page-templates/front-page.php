<?php

/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in OpenPower consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage OpenPower
 * @since OpenPower 1.0
 */

get_header(); ?>
<div id="primary" class="site-content">
	<div id="content" role="main">
		<div id="home_top" class="clear">
			<?php include(TEMPLATEPATH.'/home_slider.php'); ?>
		</div>
		<?php get_sidebar( 'front' ); ?>
		<?php client_logos('platinum'); ?>
		<?php client_logos('gold'); ?>
	</div><!-- #content -->
</div><!-- #primary -->
<?php get_footer(); ?>