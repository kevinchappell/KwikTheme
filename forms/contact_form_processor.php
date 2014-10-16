<?php

define('WP_USE_THEMES', false);
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';


	

/**
 * send_email function
 * 
 * @access public
 * @param mixed $from, $to, $msg, $subject
 * @return boolean
 */
function send_email($post, $files){
	
	$options = op_get_theme_options();

	if (empty($post)){
		return false;
	}
	if (empty($post['y_subject'])){
		$subject = 'New Message from '.get_bloginfo('name');
	} else {		
		$subject = $post['y_subject'];		
		}

	

	$to = get_option('admin_email');
	$from = $to;
	$cc = 'membership@open-power.org,info@open-power.org';

	
	$the_date = date('r');

	
	//BODY OF THE MESSAGE
	$message = '<html><body>';
	$message .= '<table cellpadding="10" border="0">';
	$message .= '<tr><td colspan="2" valign="bottom"><h2>'.__('New Message from ','op').$post['y_fname'].$post['y_lname'].'<h2></td></tr>';
	if($post['y_phone'] != '')	$message .= '<tr><td width="33%">Phone:</td><td>'.$post['y_phone'].'</td></tr>';
	// $message .= '<tr><td width="33%">Contact Type:</td><td>'.$post['c_type'].'</td></tr>';	
	$message .= '<tr><td width="33%">Email:</td><td>'.$post['y_email'].'</td></tr>';
	$message .= '<tr><td width="33%">Message:</td><td>'.$post['y_message'].'</td></tr>';
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
	$headers .= "Reply-To: ". $post['y_fname']. $post['y_lname'] ." <" . $post['y_email'] . ">". "\r\n";
	if ($cc != "")	$headers .= "Cc: ".$cc."\r\n";	

	//HEADERS
	if (!empty($files['y_file']['name'])){
		
	  $wp_upload_dir = wp_upload_dir();
	  $upload_path = $wp_upload_dir['basedir'].'/contact_form/';
	  // Check if we can upload to the specified path, if not DIE and inform the user.
	  if(!is_writable($upload_path)) $validation[]=array("field"=>"mail_result", "msg"=>"You cannot upload to the specified directory, please CHMOD it to 777.");
	  
	  // Upload the file to your specified path.
	  if(!move_uploaded_file($files['y_file']['tmp_name'],$upload_path . $files['y_file']['name'])){
				//$validation[]=array("field"=>"mail_result", "msg"=>'Your file upload was successful, view the file <a href="' . $upload_path . $files['y_file']['name'] . '" title="Your File">here</a>');
				$validation[]=array("field"=>"mail_result", "msg"=>'Your file upload was not successful.');
		  $result=array();
		  $result["status"] = (count($validation) == 0)?true:false;
		  $result["errors"] = $validation;
		  return json_encode($result);
	  } else {		 
		  
		  // Obtain file upload vars
		  $fileatt      = $upload_path . $files['y_file']['name'];
		  
		  $fileatt_type = $files['y_file']['type'];
		  $fileatt_name = $files['y_file']['name'];   
		  $file = fopen($fileatt,'rb');
		  $data = fread($file,filesize($fileatt));
		  fclose($file);
		  
		  // Add the headers for a file attachment
		  $headers .= "MIME-Version: 1.0\r\n" .
			"Content-Type: multipart/mixed;\r\n" .
			" boundary=\"{$mime_boundary}\"";
		  
		  
		  // Add a multipart boundary above the html message
		  $message = "--{$mime_boundary}\r\n" . "Content-Type: text/html; charset=\"utf-8\"\n" .
		  "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
							  
		  
		  // Base64 encode the file data
		  $data = chunk_split(base64_encode($data));
		  
		  //We now have everything we need to write the portion of the message that contains the file attachment. Here's the code:
		  
		  // Add file attachment to the message
		  $message .= "--{$mime_boundary}\n" .
				   "Content-Type: {$fileatt_type};\n" .
				   " name=\"{$fileatt_name}\"\n" .
				   "Content-Disposition: attachment;\n" .
				   " filename=\"{$fileatt_name}\"\n" .
				   "Content-Transfer-Encoding: base64\n\n" .
				   $data . "\n\n" .
				   "--{$mime_boundary}--\n";

	 }

	} else {
		// no files attached
		$headers .= "MIME-Version: 1.0\r\n" .
		$headers .= "Content-Type: text/html; \r\n charset=UTF-8 \r\n";		
		
		}
	
 
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
function validate_form( $session, $post, $files, $options ){
	$validation=array();
	//verifies the captcha
	// if ( !($session['ask_cap'] == $post['ask_cap'] )) {
	// 	$validation[]=array("field"=>"ask_cap", "msg"=>"Captcha is incorrect! Hint:".$session['ask_cap']);
	// }
	//validate user email
	if ( $post['y_email'] == "" or !is_email($post['y_email']) or stripHtml($post['y_email']) ){
		$validation[]=array("field"=>"y_email", "msg"=>"No valid email entered!");
	}
	//validate name
	if ( $post['y_fname'] == "" or stripHtml($post['y_fname'] ) ){
		$validation[]=array("field"=>"y_fname", "msg"=>"No valid first name entered!");
	}
	// if ( $post['y_lname'] == "" or stripHtml($post['y_lname'] ) ){
	// 	$validation[]=array("field"=>"y_lname", "msg"=>"No valid last name entered!");
	// }
	//validate phone
/*	
	if ( $post['y_phone'] == "" or stripHtml($post['y_phone'] ) ){
		$validation[]=array("field"=>"y_phone", "msg"=>"No valid phone number entered");
	}
*/
	//validate message
	if ( $post['y_message'] == ""){
		$validation[]=array("field"=>"y_message", "msg"=>"Please enter a valid Message!");
	}

	/*
	if ( $post['c_type'] == ""){
		$validation[]=array("field"=>"c_type", "msg"=>"Please select a type of contact.");
	}
	*/
	//if(empty($files)) $validation[]=array("field"=>"y_file", "msg"=>"No File was selected");


if (!empty($files['y_file']['name'])){			

  // Configuration - Your Options
  $allowed_filetypes = array('.pdf','.txt','.docx','.rtf', '.png', '.jpg', '.gif', '.jpeg'); // These will be the types of file that will pass the validation.
  $max_filesize = 3242880; // Maximum filesize in BYTES (currently 3MB).
  
   $filename = $files['y_file']['name']; // Get the name of the file (including file extension).
   $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
 
   // Check if the filetype is allowed, if not DIE and inform the user.
   if(!in_array($ext,$allowed_filetypes))
   $validation[]=array("field"=>"mail_result", "msg"=>"The file you attempted to upload is not allowed. Only PDF, RTF, DOCX, TXT, PNG, JPG, or GIF please.");
 
   // Now check the filesize, if it is too large then DIE and inform the user.
   if(filesize($files['y_file']['tmp_name']) > $max_filesize)
   $validation[]=array("field"=>"mail_result", "msg"=>"Filesize too large. Maximum 3mb allowed.");
 
}	

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
session_start();
//CALLS THE FUNCTION TO VALIDATE DATA AND SEND THE EMAIL FRO USER FRIENDS
print(validate_form( $_SESSION, $_POST, $_FILES, $options ));
?>