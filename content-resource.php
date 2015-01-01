<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

$resource_content = (get_the_excerpt() != "") ? '<p>'.get_the_excerpt().'</p>' : '<p>'.get_the_content('Read more').'</p>';
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('clear'); ?>>
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<div class="featured-post">
			<?php _e( 'Featured post', 'kwik' ); ?>
		</div>
		<?php endif; ?>
        <a href="<?php the_permalink(); ?>" style="float:left;" title="<?php the_title(); ?>"><?php resource_feature_image($post->ID, array(128,128), true); ?></a>
        <div class="text_wrap">
		<header class="entry-header">
        <h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'kwik' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a><?php edit_post_link( __( 'Edit', 'kwik' ), '<span class="edit-link">', '</span>' ); ?></h3>
		</header><!-- .entry-header -->
		<footer class="entry-meta">
        <?php //echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago<br/>'; ?>
        <span class="entry-date"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago<br/>'; ?>   </span>
		<div class="entry-summary">
			<?php echo $resource_content; ?>
			<?php echo download_link(get_the_ID()); ?>
		</div><!-- .entry-summary -->
        
	</article><!-- #post -->
