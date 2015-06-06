<?php

/**
 * The template for displaying solution content.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	// Post thumbnail.
	if ( has_post_thumbnail() && ! is_archive() ) {
		the_post_thumbnail('medium');
	}
	?>

	<header class="entry-header">
	<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		endif;
	?>
	</header><!-- .entry-header -->

	<div class="entry-content">
	<style>
		.company-info{
			float:right;
			width:300px;
			background:#efefef;
			padding:10px;
			border:1px solid #dfdfdf;
			margin: 0 0 10px 10px;
		}
	</style>
			<?php
			$post_id = get_the_ID();
			// the_meta();
			$solutions_meta = get_post_meta( $post_id, 'solutions_info_fields', true );
			$company = $solutions_meta['company'];
			if (!empty($solutions_meta['company']['label'])){ ?>
				<div class="company-info">
				<h3><?php echo esc_html($solutions_meta['company']['label']); ?></h3>
					<?php if ($company['id']){
						if ( has_post_thumbnail( $company['id'] ) ) {
							echo get_the_post_thumbnail( $company['id'], 'medium' );
						}
					} ?>
				</div>
			<?php } ?>
	<?php
	the_content( sprintf(
	__( 'Continue reading %s', 'kwik' ),
	the_title( '<span class="screen-reader-text">', '</span>', false )
	) );

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
	<?php

	$term_list = wp_get_post_terms( get_the_ID(), 'solution_categories');
    if($term_list){
    	echo '<div class="solution-categories">';
    	$output = '<h4>Solution Categories</h4>';
	    foreach ($term_list as $term) {
	    	$term_link = get_term_link($term);
			$output .= '<a href="' .	$term_link . '">' .	$term->name . '</a>, ';
	    }
	    echo rtrim($output, ', ');
	    echo '</div>';
    }

	?>
	<?php kt_entry_meta(); ?>
	<?php //edit_post_link( __( 'Edit', 'kwik' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
