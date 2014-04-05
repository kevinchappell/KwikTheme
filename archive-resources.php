<?php

/**
 * The template for displaying Resource Archive
 *
 * @package WordPress
 * @subpackage OpenPower
 * @since OpenPower 1.0
 */


get_header(); ?>

  <div id="primary" class="site-content">

    <div id="content" role="main">

    <header class="entry-header"><h1 class="entry-title">Resources</h1></header>
      <?php if ( ! dynamic_sidebar( 'sidebar-resources-topbar' ) ) : ?>


      <?php endif; // end sidebar widget area ?>

<script id="download_form" type="text/x-jsrender">
{{for data}}
  <ul id="errors" class="visible" style="display: none;">
    <li id="info">There were some problems with your  submission:</li>
    <li>You have not entered a name</li>
    <li>You have not entered an email address</li>
    <li>You have not entered a company name</li>
  </ul>
<form method="post" id="download-form" action="<?php bloginfo('url'); ?>/wp-content/plugins/kwik-downloads/lib/utils/form_processor.php" style="{{:form_display}}">
<h2>Download</h2>
  <p style="font-size:13px; margin-bottom:10px;">Please enter your information to download documents from our resource library.</p>
  <label for="name">Name: <span class="required">*</span></label>
  <input type="text" id="name" name="name" value="" placeholder="Enter Full Name">
  <input type="hidden" id="f_id" name="f_id" value="{{:id}}">
  <input type="hidden" name="user_ip" value="{{:user_ip}}" />

  <label for="email">Email Address: <span class="required">*</span></label>
  <input type="text" id="email" name="email" value="" placeholder="name@example.com">

  <label for="company">Company: <span class="required">*</span></label>
  <input type="text" id="company" name="company" value="" placeholder="Company Name">

  <p id="req-field-desc"><span class="required">*</span> indicates a required field</p>

  <span id="loading" style="display: none;"></span>
  <input type="submit" value="SUBMIT" id="submit-button">
</form>
<div class="kd_success" id="success" style="{{:success_display}}">
<h2>Thank you</h2>
<img src="<?php bloginfo('url'); ?>/wp-content/plugins/kwik-downloads/lib/images/icons/{{:file_ext}}/{{:file_ext}}-128_32.png" class="alignleft" />
<strong>{{:name}}</strong>
<br class="clear"/>
<br class="clear"/>
Size: {{:file_size}}
<br class="clear"/>
<br class="clear" style="margin-bottom:10px"/>
<a href="{{:file_url}}" class="arrow_btn">Download</a>
</div>
{{/for}}
</script>


    <div id="resources_slider_wrap" class="clear">

      <ul id="resources_pager" class="clear"></ul>
      <div id="resources_slider" class="cycle-slideshow" data-cycle-fx="scrollVert" data-cycle-timeout=0 data-cycle-auto-height=container data-cycle-update-view=-1 data-cycle-slides="> div" data-cycle-pager="#resources_pager" data-cycle-speed="333">
      <?php $categories=get_categories('hide_empty=1&taxonomy=resource_cats&orderby=term_order'); 
              foreach($categories as $cat){
              // var_dump($cat);

              echo '<div id="#'.$cat->slug.'" class="resource_cat slide" data-cycle-pager-template="<li><a href=#>'.$cat->name.'</a></li>" data-cycle-hash="'.$cat->slug.'">';

              echo '<ul class="resource_list">';

              // var_dump($cat);

              $resources = new WP_Query('post_status=publish&post_type=resources&posts_per_page=4&resource_cats='.$cat->slug.'&orderby=date&order=ASC');
              global $more;
              $i = 1;
              if ($resources->have_posts()):
              while ($resources->have_posts()) : $resources->the_post();
  
            $resource_content = (get_the_excerpt() != "") ? '<p>'.get_the_excerpt().'</p>' : '<p>'.get_the_content('Read more').'</p>';

            echo '<li class="clear">';               
              $attachments = get_post_meta(get_the_ID(), "kd_source", false);
              $attachment_ids = get_post_meta(get_the_ID(), "kd_id", false);
              // $attachments = $attachments[0];
              // var_dump($attachments[0]);

              if ($attachments[0][0]) {
                echo '<div>';
                resource_feature_image(get_the_ID(), array(96, 96), true, "alignright");
                // echo fileTypeIcon($attachments[0][0], "48", "alignright");
                echo '<h3><a class="download" data-file_id="'.$attachment_ids[0][0].'" href="'.$attachments[0][0].'">'.get_the_title().'</a></h3>';
                echo $resource_content;
                echo download_link(get_the_ID());
                echo '<div id="dl_form-'.$attachment_ids[0][0].'"></div>';
                echo '<br/>'; 
                echo '</div>';
              } else {
                echo '<div>';
                echo '<h3><a data-file_id="'.$attachment_ids[0][0].'" href="'.get_permalink().'">'.get_the_title().'</a></h3>';
                echo $resource_content; 
                echo '<br/>'; 
                echo '</div>';
              }
            echo '</li>';

              endwhile; endif;  wp_reset_postdata();


              echo '</div>';
              } 


        ?>



      </div>
    </div>


<div id="articles_wrap" style="display:none">
<?php 
$categories=get_categories('hide_empty=1&taxonomy=resource_cats&orderby=term_order'); 
              foreach($categories as $cat){
                echo '<div class="resource_cat">';
                echo "<h2>".$cat->name."</h2>";
$resources = new WP_Query('post_status=publish&post_type=resources&posts_per_page=4&resource_cats='.$cat->slug.'&orderby=date&order=ASC');
              global $more;
              $i = 1;
              if ($resources->have_posts()):
              while ($resources->have_posts()) : $resources->the_post();


 ?>
      <?php get_template_part( 'content', 'resource' ); ?>
    <?php endwhile; ?>
  <?php endif; 
  echo "</div>";

  }
  ?>
</div>

    </div><!-- #content -->

  </div><!-- #primary -->


<?php get_sidebar('resources'); ?>
<?php //echo kcdl_boxes(8); ?>
<?php get_footer(); ?>