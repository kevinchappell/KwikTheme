<?php

/**
 * The template for displaying a "No posts found" message.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

?>
<section class="no-results not-found">
	<header class="page-header">
		<h1 class="entry-title"><?php _e( 'Nothing Found', 'kwik' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'kwik' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
		<?php elseif ( is_search() ) : ?>
			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'kwik' ); ?></p>
		<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'kwik' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
