<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, KwikTheme already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */
get_header();
$cat = get_query_var('taxonomy');
$obj = get_queried_object();
if(!empty($obj->query_var)){ $techmain = $obj->query_var; }
$cidd = $obj->term_id;
$child_links = wp_list_pages("sort_column=menu_order&title_li=&child_of=137&echo=0&depth=1&exclude=532");
if($cat == 'technical_resources_category' || $techmain == 'technical_resources') { ?>
<ul class="" id="child_links" style="float:left;">
	<?php
		$children .= '<li><ul>';
		$children .= $child_links;
		$children .= '</ul></li>';
		echo  $children;
	?>
	<li class="techli"><a href="<?php echo get_page_link(532); ?>">Technical Resources</a>
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
		'pad_counts'               => false
	);
	$categories = get_categories( $cargs );
	?>
	<ul class="subtags">
		<?php
		foreach($categories as $category) {
		$cid = $category->term_id; ?>
		<?php if($cid == $cidd) { ?>
			<li><a style="color:#000 !important;" href="<?php echo get_term_link( $category, 'technical_resources_category' )?>"><?php echo $category->name; ?></a></li>
		<?php }else{ ?>
			<li><a href="<?php echo get_term_link( $category, 'technical_resources_category' )?>"><?php echo $category->name; ?></a></li>
		<?php } ?>
	<?php } ?>
	</ul></li>
</ul>
<?php } ?>
<div id="primary" class="site-content">
  <div id="content" role="main">
  <?php include_once("forms/filter_posts.php") ?>
<h1 class="entry-title h3entry-title"><?php echo $obj->name; ?></h1>
 <div id="articles_wrap">
          // <?php if ( have_posts() ) : ?>
          <?php /* Start the Loop */ ?>
          <?php while ( have_posts() ) : the_post(); ?>
          <?php get_template_part( 'content', 'archive' ); ?>
          <?php endwhile; ?>
          <?php kt_content_nav( 'nav-below' ); ?>
          <?php else : ?>
          <article id="post-0" class="post no-results not-found">
            <?php if ( current_user_can( 'edit_posts' ) ) :
				// Show a different message to a logged-in user who can add posts.
			?>
            <header class="entry-header">
              <h1 class="entry-title">
                <?php _e( 'No posts to display', 'kwik' ); ?>
              </h1>
            </header>
            <div class="entry-content">
              <p><?php printf( __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'kwik' ), admin_url( 'post-new.php' ) ); ?></p>
            </div>
            <!-- .entry-content -->
            <?php else :
				// Show the default message to everyone else.
			?>
            <header class="entry-header">
              <h1 class="entry-title">
                <?php _e( 'Nothing Found', 'kwik' ); ?>
              </h1>
            </header>
            <div class="entry-content">
              <p>
                <?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'kwik' ); ?>
              </p>
              <?php get_search_form(); ?>
            </div>
            <!-- .entry-content -->
            <?php endif; // end current_user_can() check ?>
          </article>
          <!-- #post-0 -->
          <?php endif; // end have_posts() check ?>
    </div><!-- #articles_wrap -->
  </div><!-- #content -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
