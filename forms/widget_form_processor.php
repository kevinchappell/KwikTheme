<?php
session_start();
define('WP_USE_THEMES', false);
require $_SERVER['DOCUMENT_ROOT'] . '/dev/wp-load.php';


	

/**
 * send_email function
 * 
 * @access public
 * @param mixed $from, $to, $msg, $subject
 * @return boolean
 */
function send_email($post, $files){

	if (empty($post)){
		return false;
	}

	$subject = 'New Message from '.get_bloginfo('name');
	
	$widget_options = get_option('widget_contact');
	$options = $widget_options[3];

	$to = $options['to_email'];
	$cc = $options['cc_email'];
	
	$the_date = date('r');

	
	//BODY OF THE MESSAGE
	$message = '<html><body>';
	$message .= '<table cellpadding="10" border="0">';
	$message .= '<tr><td valign="bottom" colspan="2"><h2>'.__('New Message from ','kwik').$post['user_name'].'<h2></td></tr>';
	if($post['user_phone'] != '')	$message .= '<tr><td width="33%">Phone:</td><td>'.$post['user_phone'].'</td></tr>';
	$message .= '<tr><td width="33%">Email:</td><td>'.$post['user_email'].'</td></tr>';
	$message .= '<tr><td width="33%">Message:</td><td>'.$post['user_message'].'</td></tr>';
	$message .= '<tr><td colspan="2"></td></tr>';
	$message .= '<tr><td width="33%">Submitter\'s IP:</td><td>'.$post['user_ip'].'</td></tr>';
	$message .= '<tr><td width="33%">Submitted on:</td><td>'.$the_date.'</td></tr>';
	$message .= '<tr><td width="33%">Submitted from:</td><td>'.$post['url_main'].'</td></tr>';
	$message .= '<tr><td colspan="2" align="center"><a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a></td></tr>';
	$message .= "</table>";
	$message .= "</body></html>";
	
	
	$semi_rand = md5(uniqid(time()));
	$mime_boundary="==Multipart_Boundary_x".$semi_rand."x";
	
	$headers = 'From: '. get_bloginfo('name') .' <' . get_option('admin_email') . '>'. "\r\n";
	$headers .= "Reply-To: ". $post['user_name'] ." <" . $post['user_email'] . ">". "\r\n";
	if ($cc != "")	$headers .= "Cc: ".$cc."\r\n";	


	$headers .= "MIME-Version: 1.0\r\n" .
	$headers .= "Content-Type: text/html; \r\n charset=UTF-8 \r\n";		

 
	if (@mail($to, $subject, $message, $headers)) {
		return true; // Message sent
	} 
	return false; // Message failed
}

/**
 * stripHtml function, strip the value from html or php, prevent html injections
 * 
 * @access public
 * @param mixed $value
 * @return boolean
 */
function stripHtml($value){
	$count_before=strlen($value);
	$strip=strip_tags($value);
	$count_after=strlen($strip);
	return ( $count_before == $count_after ) ? false : true;
}

/**
 * validate_form function
 * 
 * @access public
 * @param mixed $session, $post, $options
 * @return string
 */
function validate_form( $session, $post, $files){

	$validation=array();
	//validate user email
	if ( $post['user_email'] == "" or !is_email($post['user_email']) or stripHtml($post['user_email']) ){
		$validation[]=array("field"=>"user_email", "msg"=>"No valid email entered!");
	}
	//validate name
	if ( $post['user_name'] == "" or stripHtml($post['user_name'] ) ){
		$validation[]=array("field"=>"user_name", "msg"=>"No valid name entered!");
	}
	//validate phone
/*	
	if ( $post['user_phone'] == "" or stripHtml($post['user_phone'] ) ){
		$validation[]=array("field"=>"user_phone", "msg"=>"No valid phone number entered");
	}
*/
	//validate message
	if ( $post['user_message'] == ""){
		$validation[]=array("field"=>"user_message", "msg"=>"Please enter a valid Message!");
	}
	//verifies the captcha
/*	if ( !($session['ask_cap'] == $post['ask_cap'] ) && ( ! empty($post['ask_cap']) ) && ( ! stripHtml($post['ask_cap']) ) ) {
		$validation[]=array("field"=>"ask_cap", "msg"=>"Captcha is incorrect!");
	}*/

	//if validation pass will try to send the email, if not will not try to send.
	if(count($validation) == 0){
		
		//SEND EMAIL
			if ( !send_email($post, $files) ){
				$validation[]=array("field"=>"mail_result", "msg"=>"Mail failed to send, please try again later.");		
		}
	}
	$result=array();
	$result["status"]=(count($validation) == 0)?true:false;
	$result["errors"]=$validation;
	return json_encode($result);
}

//CALLS THE FUNCTION TO VALIDATE DATA AND SEND THE EMAIL FRO USER FRIENDS
print(validate_form( $_SESSION, $_POST, $_FILES));
?>