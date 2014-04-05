<?php 

				
	function page_header_bg($percent, $left_color, $right_color, $default)
	{	
		$begin = $percent;
		$end = 100-$percent;
		
		$output = '<style>';
		$output .= '#page_header {';
		$output .= '
		background: url(data:image/svg+xml;base64,'.base64_encode(generate_svg($percent, $left_color, $right_color, $default)).');';		
		$output .='
		background: -moz-linear-gradient(left,  '.$default.' 0%, #'. $left_color .' '.$begin.'%, #'. $right_color .' '.$end.'%, '.$default.' 100%);'; /* FF3.6+ */
		$output .='
		background: -webkit-gradient(linear, left top, right top, color-stop(0%,'.$default.'), color-stop('.$begin.'%,#'.$left_color.'), color-stop('.$end.'%,#'.$right_color.'), color-stop(100%,'.$default.'));'; /* Chrome,Safari4+ */
		$output .='
		background: -webkit-linear-gradient(left,  '.$default.' 0%,#'.$left_color.' '.$begin.'%,#'.$right_color.' '.$end.'%,'.$default.' 100%);'; /* Chrome10+,Safari5.1+ */
		$output .='
		background: -o-linear-gradient(left,  '.$default.' 0%,#'.$left_color.' '.$begin.'%,#'.$right_color.' '.$end.'%,'.$default.' 100%);'; /* Opera 11.10+ */
		$output .='
		background: -ms-linear-gradient(left,  '.$default.' 0%,#'.$left_color.' '.$begin.'%,#'.$right_color.' '.$end.'%,'.$default.' 100%);'; /* IE10+ */
		$output .='
		background: linear-gradient(to right,  '.$default.' 0%,#'.$left_color.' '.$begin.'%,#'.$right_color.' '.$end.'%,'.$default.' 100%);'; /* W3C */
		$output .='
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'.$default.'", endColorstr="'.$default.'",GradientType=1 );'; /* IE6-9 */
		
		$output .='
		}';
		$output .= '</style>';
		$output .='<!--[if gte IE 9]>
					  <style type="text/css">
						#page_header {
						   filter: none;
						}
					  </style>
		<![endif]-->';
		
		echo $output;		
		
	}
	
	
	function generate_svg($percent, $left_color, $right_color, $default){
		
		$begin = $percent;
		$end = 100-$percent;
		
		$output = '<svg preserveAspectRatio="none" viewBox="0 0 1 1" height="100%" width="100%" xmlns="http://www.w3.org/2000/svg">';
		$output .= '<linearGradient y2="0%" x2="100%" y1="0%" x1="0%" gradientUnits="userSpaceOnUse" id="grad-ucgg-generated">';
		if(empty($default)){
		$output .= '<stop stop-opacity="0" stop-color="#ffffff" offset="0%"/>';
		} else {
		$output .= '<stop stop-opacity="1" stop-color="'.$default.'" offset="0%"/>';	
		}
		$output .= '<stop stop-opacity="1" stop-color="#'.$left_color.'" offset="'.$begin.'%"/>';
		$output .= '<stop stop-opacity="1" stop-color="#'.$right_color.'" offset="'.$end.'%"/>';
		if(empty($default)){
		$output .= '<stop stop-opacity="0" stop-color="#ffffff" offset="100%"/>';
		} else {
		$output .= '<stop stop-opacity="1" stop-color="'.$default.'" offset="100%"/>';	
		}
		$output .= '</linearGradient>';
		$output .= '<rect fill="url(#grad-ucgg-generated)" height="1" width="1" y="0" x="0"/>';
		$output .= '</svg>';
		
		return $output;
	}

	
	if($_POST['percent']){

		$percent = $_POST['percent'];
		$left_color = $_POST['left_color'];
		$right_color = $_POST['right_color'];
		$default = $_POST['default'];
		page_header_bg($percent, $left_color, $right_color, $default);
	}

