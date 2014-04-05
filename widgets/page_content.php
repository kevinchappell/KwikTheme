<?php 
/**
 * Widget Name: BOL Page Content
 * Description: Grab content from another page using its id
 * Version: 0.1
 *
 */

add_action( 'widgets_init', 'op_load_page_content_widget' );

function op_load_page_content_widget() {
	register_widget( 'page_content' );
}



class page_content extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_page_content', 'description' => __('Enter page or post id'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('content_post_id', __('Page Content'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$widget_content = get_post($instance['content_post_id']);
		$post_id = $instance['content_post_id'];

		$file_url = get_post_meta($post_id, "kd_source",false);
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? $widget_content->post_title : $instance['title'], $instance, $this->id_base );
		$content_post_id = $widget_content->post_content;
		$attachment_ids = get_post_meta($post_id, "kd_id", false);

		$title = $title;
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
			<div class="page_content_widget clear">
				<?php

				$resource_content = (get_the_excerpt($post_id) != "") ? '<p>'.$widget_content->post_excerpt.'</p>' : '<p>'.$widget_content->post_content.'</p>';
?>
	<article id="post-<?php echo $post_id; ?>">
        <a href="<?php echo $file_url[0][0]; ?>" style="float:left;" title="<?php echo $widget_content->post_title; ?>"><?php resource_feature_image($post_id, array(256,256), true); ?></a>
        <div class="text_wrap">
		<header class="entry-header">
        <h3 class="entry-title"><a href="<?php echo $file_url[0][0]; ?>" class="download" data-file_id="<?php echo $attachment_ids[0][0]; ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'op' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php echo get_the_title($post_id); ?></a><?php edit_post_link( __( 'Edit', 'op' ), '<span class="edit-link">', '</span>' ); ?></h3>
		</header><!-- .entry-header -->
		<footer class="entry-meta">
        <?php //echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago<br/>'; ?>
		<div class="entry-summary">
			<?php echo $resource_content; ?>
			<?php echo download_link($widget_content->ID);?>
		</div><!-- .entry-summary -->
        
	</article><!-- #post -->
			</div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['content_post_id'] = strip_tags($new_instance['content_post_id']);

		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'content_post_id' => '' ) );	
		$title = $instance['title'];	
		$content_post_id = esc_textarea($instance['content_post_id']);
?> <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <input class="widefat" id="<?php echo $this->get_field_id('content_post_id'); ?>" name="<?php echo $this->get_field_name('content_post_id'); ?>" type="text" value="<?php echo esc_attr($content_post_id); ?>" />

<?php
	}
}

