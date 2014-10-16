<?php
/**
 * The template for displaying all pages.
 * Template Name: Board of Directors
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */
get_header(); ?>
	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php 

	$bod = get_post_meta($post->ID, '_board_members', false);
	$no_avatar = get_bloginfo('template_url').'/inc/images/no_avatar.jpg';	

	if(!empty($bod)){
		$bod = $bod[0];
		$bod_meta = '<ul id="bod">';
		$i = 0;
		foreach ($bod as $b){
			$mem_img = wp_get_attachment_image_src($b['img'], 'medium' );
			$mem_img = $mem_img['0'];
			$img_src = $mem_img ? $mem_img : $no_avatar;

			$bod_meta .= '<li class="bod_mem clear">';
				$bod_meta .= '<img src="'.$img_src.'" class="bod_mem_img" width="163">';
				$bod_meta .= '<div class="mem_details">';
				$bod_meta .= '<h3>'.$b['name'].'</h3>';
				if($b['company'] != '') $bod_meta .= '<h4>'.get_the_title($b['company']).'</h4>';
				// if($b['company'] != '') $bod_meta .= '<h4><a href="'.get_permalink($b['company']).'" title="View the '.get_the_title($b['company']).' page">'.get_the_title($b['company']).'</a></h4>';
				$bod_meta .= '<p>'.$b['bio'].'</p>';
				$bod_meta .= '</div>';
			$bod_meta .= '</li>';	
			$i++;
		}
		$bod_meta .= '</ul>';
		echo $bod_meta;
	}
	


				?>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>