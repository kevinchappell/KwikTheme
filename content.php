<?php

/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<div class="featured-post">
				<?php _e( 'Featured post', 'op' ); ?>
			</div>
		<?php endif; ?>

		<header class="entry-header">
			<?php if ( is_single() ) : ?>
			<h1 class="entry-title"><?php the_title(); ?><?php edit_post_link( __( 'Edit', 'op' )); ?></h1>
            <?php //kt_author_date(); ?>
			<?php else : ?>
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'op' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a><?php edit_post_link( __( 'Edit', 'op' ), '<span class="edit-link">', '</span>' ); ?>
			</h1>
			        <?php $source = get_post_meta($post->ID, '_source', true);
        $source_link = get_post_meta($post->ID, '_source_link', true); 
		if($source){ ?>
        <div class="entry-source"><strong><?php _e('Source:', 'kwik');?></strong> <?php echo $source_link ? '<a href="'.$source_link.'" target="_blank">' : ''; ?><?php echo $source; ?><?php echo $source_link ? '</a>' : ''; ?></div>
        <?php } ?>
			<?php endif; // is_single() ?>
		</header><!-- .entry-header -->
		<?php if ( is_search()) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
        <?php elseif ( is_home() ) : ?>        

		<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'op' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'op' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

		<?php endif; ?>

		<footer class="entry-meta clear" style="display:none;">

                <?php if ( is_singular()){ ?>

                <div style="float:left; width:48%;">

					<?php kt_entry_meta(); ?>

                    <?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>

                        <div class="author-info">

                            <div class="author-avatar">

                                <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'kt_author_bio_avatar_size', 68 ) ); ?>

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

                </div>

                <div style="float:right; width:48%;"><?php comments_template( '', true ); ?></div>

				

                 <?php } ?>

                

		</footer><!-- .entry-meta -->

	</article><!-- #post -->

