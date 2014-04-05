<?php

/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage OpenPower
 * @since OpenPower 1.0
 */

get_header(); ?>



<div id="primary" class="site-content">
  <div id="content" role="main">

  <header class="entry-header"><h1 class="entry-title"><?php echo (get_option( 'show_on_front' ) == 'page') ? get_the_title(get_option('page_for_posts' )) : "News" ; ?></h1></header>
    <div id="articles_wrap">
          <?php if ( have_posts() ) : ?>
          <?php /* Start the Loop */ ?>

          <?php while ( have_posts() ) : the_post(); 
            get_template_part( 'content', 'archive' ); 
          endwhile; 
          else : ?>
          <article id="post-0" class="post no-results not-found">

            <?php if ( current_user_can( 'edit_posts' ) ) :
				// Show a different message to a logged-in user who can add posts.

			?>
            <header class="entry-header">
              <h1 class="entry-title">
                <?php _e( 'No posts to display', 'op' ); ?>
              </h1>
            </header>

            <div class="entry-content">
              <p><?php printf( __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'op' ), admin_url( 'post-new.php' ) ); ?></p>
            </div>
            <!-- .entry-content -->
            <?php else :
				// Show the default message to everyone else.
			?>
            <header class="entry-header">
              <h1 class="entry-title">
                <?php _e( 'Nothing Found', 'op' ); ?>
              </h1>
            </header>
            <div class="entry-content">
              <p>
                <?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'op' ); ?>
              </p>
              <?php get_search_form(); ?>
            </div>
            <!-- .entry-content -->
            <?php endif; // end current_user_can() check ?>
          </article>
          <!-- #post-0 -->
          <?php endif; // end have_posts() check ?>
    </div><!-- #articles_wrap --> 
    <?php op_paginate(); ?>
  </div>
  <!-- #content --> 
</div>
<!-- #primary -->


<?php get_footer(); ?>

