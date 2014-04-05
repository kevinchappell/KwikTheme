<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage OpenPower
 * @since OpenPower 1.0
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('clear'); ?>>
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<div class="featured-post">
			<?php _e( 'Featured post', 'op' ); ?>
		</div>
		<?php endif; ?>

        <div class="text_wrap">
		<header class="entry-header">
        <h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'op' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a><?php edit_post_link( __( 'Edit', 'op' ), '<span class="edit-link">', '</span>' ); ?></h3>
		</header><!-- .entry-header -->
		<footer class="entry-meta">
		<?php 
			$source = get_post_meta($post->ID, '_source', true);
			$source_link = get_post_meta($post->ID, '_source_link', true); 
			$category = get_the_category();
			$cat_info = '<a class="filter_link" href="#category-'.$category[0]->category_nicename.'">';
			$cat_info .= $category[0]->cat_name;
			$cat_info .= '</a>';
        ?>
		<span class="entry-date"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ).' ago'; ?>&nbsp; &#183; &nbsp;</span><?php echo $cat_info; ?><?php if($source){ ?>&nbsp; &#183; &nbsp;<span class="entry-source"><?php _e('Source:', 'kwik');?> <?php echo $source_link ? '<a href="'.$source_link.'" target="_blank">' : ''; ?><?php echo $source; ?><?php echo $source_link ? '</a>' : ''; ?></span><?php } ?>
		<div class="entry-summary"><?php the_excerpt(); ?></div><!-- .entry-summary -->
        <div class="hidden_meta">
			<div class="archive_tags">
            <?php the_tags(); ?>
            </div>
            <div class="archive_cats">
           <?php _e('Categories','op'); ?>:  <?php the_category(', '); ?>
            </div><br />
             <a href="<?php the_permalink(); ?>" class="btn" title="<?php the_title(); ?>">
			<?php _e('Read','op'); ?>
            </a>
            </div>
			
			<?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
				<div class="author-info">
					<div class="author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'op_author_bio_avatar_size', 68 ) ); ?>
					</div><!-- .author-avatar -->
					<div class="author-description">
						<h2><?php printf( __( 'About %s', 'op' ), get_the_author() ); ?></h2>
						<p><?php the_author_meta( 'description' ); ?></p>
						<div class="author-link">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
								<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'op' ), get_the_author() ); ?>
							</a>
						</div><!-- .author-link	-->
					</div><!-- .author-description -->
				</div><!-- .author-info -->
			<?php endif; ?>
		</footer><!-- .entry-meta -->
		</div>
	</article><!-- #post -->
