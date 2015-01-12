<?php

/**
 * Template Name: Contact Page
 *
 * Description: Contact page template. Use this until KwikForms is finalized.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php comments_template( '', true ); ?>
        <?php include TEMPLATEPATH.'/forms/contact_form.php'; ?>
			<?php endwhile; // end of the loop. ?>
		</div><!-- #main -->
	</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
