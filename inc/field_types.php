<?php
function op_background($option) {
	$option_name = $option['name'];
		$thumb = wp_get_attachment_image_src($options[$option_name][1], 'thumbnail' );
		$thumb = $thumb['0'];
		$kf_bg = '';
		$kf_bg .= '<div>'.__('Color','kwik').':<br/><input type="text" name="theme_options['.$option_name.'][]" class="cpicker" value="'. esc_attr( $options[$option_name][0] ).'" /></div>';
		$kf_bg .= '<div>'.__('Image','kwik').':<br/>
		<input type="hidden" name="theme_options['.$option_name.'][]" class="img_id" value="'.$options[$option_name][1].'" />
		<img src="'.$thumb.'" class="img_prev" width="23" height="23" title="'.get_the_title($options[$option_name][1]).'"/><span id="site_bg_img_ttl" class="img_title">'.get_the_title($options[$option_name][1]).(!empty($options[$option_name][1]) ? '<span title="'.__('Remove Image','kwik').'" class="clear_img tooltip"></span>':'').'</span>';
	    $kf_bg .= '<input type="button" class="upload_img" id="upload_img" value="+ IMG" /></div>';
		 $kf_bg .= '<div>'.__('Position','kwik').':<br/><select name="theme_options['.$option_name.'][]">';
		 $kf_bg .= '<option '.($options[$option_name][2] == '0 0' ? 'selected="selected"' : '').' value="0 0">Top Left</option>';
		 $kf_bg .= '<option '.($options[$option_name][2] == '0 50%' ? 'selected="selected"' : '').' value="0 50%">Top Center</option>';
		 $kf_bg .= '<option '.($options[$option_name][2] == '0 100%' ? 'selected="selected"' : '').' value="0 100%">Top Right</option>';
		 $kf_bg .= '<option '.($options[$option_name][2] == '50% 0' ? 'selected="selected"' : '').' value="0 100%">Middle Left</option>';
		 $kf_bg .= '<option '.($options[$option_name][2] == '50% 50%' ? 'selected="selected"' : '').' value="50% 50%">Middle Center</option>';
		 $kf_bg .= '<option '.($options[$option_name][2] == '50% 100%' ? 'selected="selected"' : '').' value="0 100%">Middle Right</option>';
		 $kf_bg .= '<option '.($options[$option_name][2] == '100% 0' ? 'selected="selected"' : '').' value="100% 0">Bottom Left</option>';
		 $kf_bg .= '<option '.($options[$option_name][2] == '100% 50%' ? 'selected="selected"' : '').' value="100% 50%">Bottom Center</option>';
		 $kf_bg .= '<option '.($options[$option_name][2] == '100% 0' ? 'selected="selected"' : '').' value="100% 0">Bottom Left</option>';
		 $kf_bg .= '</select></div>';
		 $kf_bg .= '<div>'.__('Repeat','kwik').':<br/><select name="theme_options['.$option_name.'][]">';
		 $kf_bg .= '<option '.($options[$option_name][3] == 'no-repeat' ? 'selected="selected"' : '').' value="no-repeat">No Repeat</option>';
		 $kf_bg .= '<option '.($options[$option_name][3] == 'repeat' ? 'selected="selected"' : '').' value="repeat">Repeat</option>';
		 $kf_bg .= '<option '.($options[$option_name][3] == 'repeat-x' ? 'selected="selected"' : '').' value="repeat-x">Repeat-X</option>';
		 $kf_bg .= '<option '.($options[$option_name][3] == 'repeat-y' ? 'selected="selected"' : '').' value="repeat-y">Repeat-Y</option>';
		 $kf_bg .= '</select></div>';
		 $kf_bg .= '<div>'.__('Bg Size','kwik').':<br/><select name="theme_options['.$option_name.'][]">';
		 $kf_bg .= '<option '.($options[$option_name][4] == 'auto' ? 'selected="selected"' : '').' value="auto">Default</option>';
		 $kf_bg .= '<option '.($options[$option_name][4] == '100% 100%' ? 'selected="selected"' : '').' value="100% 100%">Stretch</option>';
		 $kf_bg .= '<option '.($options[$option_name][4] == 'cover' ? 'selected="selected"' : '').' value="cover">Cover</option>';
		 $kf_bg .= '</select></div>';
		 $kf_bg .= '<div>'.__('Attachment','kwik').':<br/><select name="theme_options['.$option_name.'][]">';
		 $kf_bg .= '<option '.($options[$option_name][5] == 'scroll' ? 'selected="selected"' : '').' value="scroll">Scroll</option>';
		 $kf_bg .= '<option '.($options[$option_name][5] == 'fixed' ? 'selected="selected"' : '').' value="fixed">Fixed</option>';
		 $kf_bg .= '</select></div>';
		 $kf_bg .= '';

		 $kf_bg .= '<div class="preview_wrap">';
			 $kf_bg .= '<h2>'.__('Preview','kwik').'</h2>';
			 $kf_bg .= '<div class="preview">';
			 $kf_bg .= '</div>';
		 $kf_bg .= '</div>';
	    
		$kf_bg .= '';
		
	echo $kf_bg;
    }