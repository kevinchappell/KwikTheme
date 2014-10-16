<?php
/**
 * Template Name: Tech Resources Template
 *
 */
?>
<?php get_header(); ?>
	<div id="primary" class="site-content">
		<div id="content" role="main">

		<article class="post-532 page type-page status-publish hentry" id="post-532">

		<header class="entry-header">

			<h1 class="entry-title">Technical Resources</h1>
		</header>
		<div class="entry-content">
			<h3>The vision of KwikTheme Foundation includes key tools and resources to build upon the POWER architecture. In addition to the Foundation’s deliverables, members of the community are already developing KwikTheme solutions that support our vision. Please read below, to learn more about our tools and resources in:</h3>
			<ul>
				<?php
				$cargs = array(
					'type'                     => 'technical_resources',
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'taxonomy'                 => 'technical_resources_category',
					'pad_counts'               => false );
				$categories = get_categories( $cargs );
				foreach($categories as $category) { $catid = $category->term_id; //$content = wp_strip_all_tags($post->post_content);?>
					<li><h3><a href="<?php echo get_term_link( $category, 'technical_resources_category' ); ?>"><?php echo $category->name; ?></a></h3>
					<ul>
						<?php
							$args = array( 'post_type' => 'technical_resources',
								   'tax_query' => array(
										array(
											'taxonomy' => 'technical_resources_category',
											'field' => 'id',
											'terms' => $catid
									  )),
									 'post_status' => 'publish'
							  );
							$postarray = query_posts($args);
							foreach($postarray as $post){
						 ?>
						 <li><?php echo $post->post_title; ?></li>
						<?php } ?>
					</ul>
					</li>
				<?php } ?>
			</ul>
		</div><!-- .entry-content -->

	</article>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
