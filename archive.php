<?php

/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, OpenPower already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
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

    <form id="grid_filter" action="javascript:void(0)">



      <?php $order = (isset($_GET['order']) ? $_GET['order'] : 'ASC'); ?>

      <?php $orderby = (isset($_GET['orderby']) ? $_GET['orderby'] : 'date'); ?>

      <?php $category = (isset($_GET['category']) ? $_GET['category'] : 'all'); ?>

      <input type="radio" value="ASC" id="order_asc" <?php echo ($order == 'ASC' ? 'checked="checked"': ''); ?> name="grid_order" />

      <input type="radio" value="DESC" id="order_desc" <?php echo ($order == 'DESC' ? 'checked="checked"': ''); ?> name="grid_order" />

      

      <input type="radio" value="title" id="grid_orderby_title" <?php echo ($order == 'title' ? 'checked="checked"': ''); ?> name="grid_orderby" />

      <input type="radio" value="date" id="grid_orderby_date" <?php echo ($order == 'date' ? 'checked="checked"': ''); ?> name="grid_orderby" />

       

      <input type="hidden" value="<?php echo $orderby; ?>" name="orderby" />

      <input type="hidden" value="<?php echo $category; ?>" name="category" />

      

      <label for="order_asc" class="order_btns order_asc">ASC</label><label for="order_desc" class="order_btns order_desc">DESC</label>



		<div id="grid_sort" style="float:right;" class="custom_dropdown">Order by

            <ul class="dropdown">

              <li class="category-option" title="title"><label for="grid_orderby_title"><?php _e('Title','op')?></label></li>

              <li class="category-option" title="date"><label for="grid_orderby_date"><?php _e('Date','op')?></label></li>

            </ul>

		</div>

    

      <input type="text" placeholder="<?php _e('Search','op'); ?>" name="s_term" id="s_term" class="input">



        

        <?php $categories=get_categories('hide_empty=1'); ?>

		<div id="grid_category" class="custom_dropdown" tabindex="1">Category

            <ul class="dropdown">

              <?php 

			  

			  echo '<li class="category-option"><label for="grid_category-all">' . __('All','op') . '<input type="radio" value="all" id="grid_category-all" '.($category == 'all' ? 'checked="checked"': '').' name="grid_category" /></label></li>';



              foreach($categories as $cat){

              echo '<li class="category-option"><label for="grid_category-'.$cat->slug.'">' . $cat->name . '<input type="radio" value="'.$cat->slug.'" id="grid_category-'.$cat->slug.'" '.($category == $cat->slug ? 'checked="checked"': '').' name="grid_category" /></label></li>';

              } 

              ?>

            </ul>

		</div>

        



        

                    

      <input type="checkbox" name="category" style="display:none;">

      

    </form>

 <div id="articles_wrap"><!--   

      <div class="scrollbar">

        <div class="track">

          <div class="thumb">

            <div class="end"></div>

          </div>

        </div>

      </div>

      <div class="viewport">

        <div class="overview">-->

          <?php if ( have_posts() ) : ?>

          <?php /* Start the Loop */ ?>

          <?php while ( have_posts() ) : the_post(); ?>

          <?php get_template_part( 'content', 'archive' ); ?>

          <?php endwhile; ?>

          <?php op_content_nav( 'nav-below' ); ?>

          <?php else : ?>

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

      <!--  </div> .overview --> 

     <!--  </div>.viewport --> 

    </div>

    <!-- #articles_wrap --> 

  </div>

  <!-- #content --> 

</div>

<!-- #primary -->

<?php //get_sidebar(); ?>

<?php get_footer(); ?>