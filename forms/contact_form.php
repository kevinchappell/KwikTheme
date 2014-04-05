<!--   BEGIN CONTACT FORM     -->

<div id="contact_wrap">
  <?php


$email_addy_error_msg = get_post_meta(6, 'email_addy_error_msg', true);
$captcha_error_msg = get_post_meta(6, 'captcha_error_msg', true);
$message_error_msg = get_post_meta(6, 'message_error_msg', true);
$name_error_msg = get_post_meta(6, 'name_error_msg', true);
$server_success_msg = get_post_meta(6, 'server_success_msg', true);
$server_fail_msg = get_post_meta(6, 'server_fail_msg', true);
$upload_format_msg = get_post_meta(6, 'upload_format_msg', true);
$upload_size_msg = get_post_meta(6, 'upload_size_msg', true);


?>
  <form id="contactform" name="contactform" method="post" class="clear" enctype="multipart/form-data" action="<?php bloginfo('template_directory') ?>/forms/contact_form_processor.php" >
    <div class="form_content_wrap">
      <input type="hidden" name="url_main" value="<?php echo curPageURL(); ?>" />
      <input type="hidden" name="user_ip" value="<?php echo getRealIp(); ?>" />
      <div class="nodisplay error" id="upload_format_msg"><?php echo $upload_format_msg; ?></div>
      <div class="nodisplay error" id="upload_size_msg"><?php echo $upload_size_msg; ?></div>
      <div class="clear">
      <div class="input_wrap name_wrap" style="float:left">
        <label for="y_name">
          <?php _e( 'Name', 'op' ); ?>
          <span class="required">*</span>:</label>
        <div class="nodisplay error" id="name_error_msg"><?php echo $name_error_msg; ?></div>
        <span class="text_input"><input name="y_fname" id="y_fname" type="text" placeholder="<?php _e('Your Name', 'kwik') ?>" class="required contact_text fname" value="" /></span>
        <!-- <span class="text_input"><input name="y_lname" id="y_lname" type="text" placeholder="Last Name" class="required contact_text lname" value="" /></span> -->
      </div>
      <div class="input_wrap name_wrap" style="float:left;margin-left: 15px;">
        <label for="y_email">
          <?php _e( 'Email', 'op' ); ?>
          <span class="required">*</span>:</label>
        <div class="nodisplay error" id="email_addy_error_msg"><?php echo $email_addy_error_msg; ?></div>
        <span class="text_input"><input name="y_email" id="y_email" type="text" placeholder="<?php _e('youremail@domain.com', 'kwik') ?>" class="required email contact_text" value="" /></span>
      </div>
      <div class="input_wrap nodisplay">
        <label for="y_phone">
          <?php _e( 'Phone', 'op' ); ?>
          :</label>
        <span class="text_input"><input name="y_phone" id="y_phone" type="text" placeholder="(555) - 555 - 1234" class="contact_text y_phone" value="" /></span>
      </div>
      <div class="input_wrap nodisplay">
        <label for="y_subject">
          <?php _e( 'Subject', 'op' ); ?>
          :</label>
        <input name="y_subject" id="y_subject" type="text" placeholder="Subject" class="contact_text y_subject" value="" />
      </div>
      <div class="input_wrap nodisplay">
        <label for="y_file">
          <?php _e( 'Attach File:', 'op' ); ?>
        </label>
        <input name="y_file" id="y_file" type="file" class="contact_text" size="49" value="" />
        <progress value="0" max="100" id="upload_progress" style="display:none;margin-left:20px;"></progress>
      </div>
      </div>
      <div class="textarea_wrap input_wrap">
        <label for="y_message">
          <?php _e( 'Message', 'op' ); ?>
          <span class="required">*</span>:</label>
        <div class="nodisplay error" id="message_error_msg"><?php echo $message_error_msg; ?></div>
        <textarea name="y_message" cols="15" id="y_message" class="contact_textarea" placeholder="<?php _e('Enter your message&hellip;', 'kwik') ?>" rows="2"></textarea>
      </div>
      <div class="input_wrap clear">
        <label for="ask_cap">
          <?php _e( 'Security', 'op' ); ?>
          <span class="required">*</span>:</label>        
        <div class="nodisplay error" id="captcha_error_msg"><?php echo $captcha_error_msg; ?></div>
        <span class="text_input"><input name="ask_cap" id="ask_cap" placeholder="<?php _e('Enter security code', 'kwik') ?>" type="text" value="" /></span>   <img id="y_cap_img" alt="enter the captcha code please" src="<?php bloginfo('template_directory'); ?>/forms/captcha.php"/>
      </div>

      <input name="Send" type="submit" value="<?php _e( 'Submit', 'op' ); ?>" id="y_send" class="" />
    </div>
  </form>
  <div class="nodisplay" id="server_success_msg"><?php echo $server_success_msg; ?></div>
  <div class="nodisplay" id="server_fail_msg"><?php echo $server_fail_msg; ?></div>
</div>

<!---     END CONTACT FORM     -->