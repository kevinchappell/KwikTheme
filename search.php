<?php

/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">

		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">

			</header>

			<?php kt_content_nav( 'nav-above' ); ?>

			<?php /* Start the Loop */ ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'archive' ); ?>

			<?php endwhile; ?>

			<?php kt_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<article id="post-0" class="post no-results not-found">

				<header class="entry-header">

					<h1 class="entry-title"><?php _e( 'Nothing Found', 'kwik' ); ?></h1>

				</header>

				<div class="entry-content">

					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'kwik' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .entry-content -->

			</article><!-- #post-0 -->

		<?php endif; ?>

		</div><!-- #main -->

	</section><!-- #primary -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
