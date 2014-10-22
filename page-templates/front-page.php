<?php

/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in KwikTheme consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

get_header(); ?>
<div id="primary" class="site-content">
	<div id="content" role="main">
		<?php get_sidebar( 'horz' ); ?>
	</div><!-- #content -->
</div><!-- #primary -->
<?php get_footer(); ?>