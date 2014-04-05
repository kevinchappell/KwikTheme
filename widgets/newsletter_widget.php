<?php 
/**
 * Widget Name: BOL Newsletter Widget
 * Description: Signup for the OpenPower Launcht newsletter
 * Version: 0.1
 *
 */

add_action( 'widgets_init', 'op_newsletter_load_widgets' );

function op_newsletter_load_widgets() {
	register_widget( 'BOL_Newsletter_Widget' );
}



class BOL_Newsletter_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'newsletter_widget', 'description' => __('Mini Newsletter Form'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('newsletter', __('Newsletter'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		
		echo $before_widget;
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		if(!empty($title)) echo $before_title . $title . $after_title;
				
		$form = '';
		$form .='<script language="javascript" type="text/javascript">
					function CheckEmail(email) {
					var x=email;
					var atpos=x.indexOf("@");
					var dotpos=x.lastIndexOf(".");
					if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
					{
					return false;
					}	else{
					return true;
					}
					}
					function validateForm()
					{
					var email=document.forms["newsletter"]["email"].value;
					var first=document.forms["newsletter"]["first_name"].value;
					var last=document.forms["newsletter"]["last_name"].value;
					var validemail=CheckEmail(email);
/*					if (email=="Enter Email" || email=="" || first=="Enter First Name" || first=="" || last=="Enter Last Name" || last=="" ){
					alert("All Fields Must Be Filled Out");
					return false;
					}*/
					if (email=="Enter Email" || email==""){
					alert("All Fields Must Be Filled Out");
					return false;
					}
					if(validemail==false){
					alert("You must enter a valid email address");
					return false;
					}
					}
										
					</script>';
		$form .= '<form target="_blank" onsubmit="return validateForm()" method="POST" action="http://funding.beatsol.org/pages/signup" name="newsletter" id="op_newsletter_widget">';	
		$form .= '<small>'.$text.'</small><br/><br/>';	
		$form .= '<input type="hidden" value="00DC0000000QlKn" name="oid"><input type="hidden" value="http://www.launcht.com/newsthanks.php" name="retURL"><input type="hidden" value="Web - Newsletter Request" name="lead_source">';
		$form .= '<input type="text" class="text_field" name="email" placeholder="'.__('Email','op').'" id="email" />';
		//$form .= '<input type="text" class="text_field" name="first_name" placeholder="'.__('First Name','op').'" id="first_name" />';
		//$form .= '<input type="text" class="text_field" name="last_name" placeholder="'.__('Last Name','op').'" id="last_name" />';
		
		$form .= '<div class="inner submit_wrap"><span class="arrow"></span><input type="submit" name="user_submit" id="user_submit" value="'.__('Submit','op').'"></div>';
		$form .= '</form>';
		
		echo $form;
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] =  $new_instance['text'];
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = $instance['title'];
		$text =  $instance['text'];
?>
		<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        
        <p>
        <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:'); ?></label>
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        </p>

<?php
	}
}

