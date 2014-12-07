    <?php $options = kt_get_slider_options(); ?>


    <div id="featured_slide_wrap">

      <div class="cycle-slideshow" 
      data-cycle-fx="<?php echo $options['home_slider']['fx']?>"
      data-cycle-speed=<?php echo $options['home_slider']['speed']?>
      data-cycle-timeout=<?php echo $options['home_slider']['delay']?>
      data-cycle-auto-height=container
      data-cycle-swipe=true
      data-cycle-slides="div.slide"
      >
      <div class="cycle-pager"></div>
        <?php
            $feat_query = new WP_Query('post_status=publish&post_type=home_slide&orderby=menu_order&order=ASC');
            $i = 1;
			$total = $feat_query->post_count;
			if ($feat_query->have_posts()):
            while ($feat_query->have_posts()) : $feat_query->the_post();
            global $more;
            $more = 0;
			
			$home_slide_id = get_the_ID();			
			$subtitle = get_post_meta( $post->ID, 'kt_subtitle', true );
			$slide_link = get_post_meta($post->ID, 'kt_home_slide_link', true);
			$link_target = get_post_meta($post->ID, 'kt_home_slide_link_target', true);
			$learn_more = get_post_meta($post->ID, 'kt_learn_more', true);
			
			?>


<!--         <div class="slide_wrap" id="slide_wrap_<?php echo $i; ?>"> -->

        <div class="slide slide_<?php echo $i; ?>">
<?php edit_post_link( __( 'Edit', 'trb' )); ?>
<?php if($slide_link) echo '<a href="'. $slide_link .'" target="'.$link_target.'" style="display:block;z-index:0;height:100%;width:100%;" title="'.get_the_title().'"></a>'; ?> 
              <div class="slide_info">
                <h2>
                  <?php if($slide_link) echo '<a href="'. $slide_link .'" target="'.$link_target.'" title="'.get_the_title().'">'; ?>     
                  <?php the_title(); ?>
                  <?php if($slide_link) echo '</a>'; ?>
                </h2>
                <?php the_content();  ?>
                <?php if(!empty($learn_more))echo '<a href="'.$slide_link.'" class="btn" title="'.get_the_title().'" target="'.$link_target.'">'.$learn_more.'</a>'; ?>
                </div>
                <?php if($slide_link) echo '<a href="'. $slide_link .'" target="'.$link_target.'" style="display:block;z-index:0;height:100%;width:100%;" title="'.get_the_title().'">'; ?> 
                <?php the_post_thumbnail('home_slide'); ?>
                <?php if($slide_link) echo '</a>'; ?> 
                
            </div><!--  /.slide  -->
        <!-- </div> --><!--  /.slide_wrap  -->
        <?php $i++;  endwhile; endif; wp_reset_postdata(); ?>
      </div>
    </div>