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
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */
get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

    <?php if ( have_posts() ) : ?>

      <?php if ( is_home() && ! is_front_page() ) : ?>
        <header>
          <h1 class="entry-title"><?php single_post_title(); ?></h1>
        </header>
      <?php endif; ?>

      <?php
      // Start the loop.
      while ( have_posts() ) : the_post();
        get_template_part( 'content', get_post_format() );
      endwhile;

      // Previous/next page navigation.
      the_posts_pagination( array(
        'prev_text'          => __( 'Previous page', 'kwik' ),
        'next_text'          => __( 'Next page', 'kwik' ),
        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'kwik' ) . ' </span>',
      ) );

    // If no content, include the "No posts found" template.
    else :
      get_template_part( 'content', 'none' );

    endif;
    ?>

    </main><!-- .site-main -->
  </div><!-- .content-area -->

<?php get_footer(); ?>
