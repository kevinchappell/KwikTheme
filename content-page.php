<?php

/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php if ( ! is_null( kwik_title() ) ) : ?>
  <header class="entry-header">
    <h1 class="entry-title"><?php echo kwik_title(); ?></h1>
  </header><!-- .entry-header -->
<?php endif; ?>

  <div class="entry-content">
    <?php the_content(); ?>
    <?php
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'kwik' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'kwik' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
		?>
		</div><!-- .entry-content -->
<footer class="entry-footer">

</footer><!-- .entry-footer -->

</article><!-- #post-## -->
