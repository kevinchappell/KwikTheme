<?php 
/**
 * Widget Name: Home Boxes
 * Description: A configurable widget to display featured posts with a fallback for display more.
 * Version: 0.1
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'home_boxes_load_widgets' );
add_action('wp_ajax_nopriv_do_ajax', 'news_scroll_ajax');
add_action('wp_ajax_do_ajax', 'news_scroll_ajax');

/**
 * Register our widget.
 * 'Home_Boxes' is the widget class used below.
 *
 * @since 0.1
 */
function home_boxes_load_widgets() {
	register_widget( 'Home_Boxes' );
}


function hb_neat_trim($str, $n, $delim='&hellip;', $neat=true) {	
   $len = strlen($str);
   if ($len > $n) {
	   if($neat){
		   preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
		   return rtrim($matches[1]) . $delim;
	   } else {
		   return substr($str, 0, $n) . $delim;	
   
		}
   }
   else {
       return $str;
   }   
}

function news_scroll_ajax(){
	 
     switch($_REQUEST['fn']){
		  case 'more_news_scroll':
               $output = op_more_news_scroll($_REQUEST['offset']);
          break;
     }
 
         $output = json_encode($output);
         if(is_array($output)){
        	print_r($output);
         }
         else{
        	echo $output;
         }
         die;
}


function op_more_news_scroll($offset){
	
	$widget_options = get_option('widget_most_viewed');	
	$fallback = $widget_options['fallback'];	
	global $post;

 
 $news_scroll_args = array( 
	'post_status' => 'publish' , 
	'post_type' => $fallback,
	'posts_per_page' => 1, 
	'orderby' => 'date', 
	'order' => 'DESC', 
	'offset' => $offset
		
);

if(!empty($widget_options['home_box_content'])) $news_scroll_args['post__not_in'] = $widget_options['home_box_content'];

$posts = query_posts($news_scroll_args);	
$json = array(); 
if ($posts) {
    foreach ($posts as $post) {
        $data = array();
        the_post();
		
		$short_title = hb_neat_trim($post->post_title, 45);
		
		$target = get_post_meta( $post->ID, '_links_to_target', true );
		
		

		$category = get_the_category();
		$data['cat'] = $category[0]->name;
        $data['id'] = $post->ID;
		
		$post_classes = get_post_class();
		
		$data['classes'] = '';
		
		foreach($post_classes as $post_class){		
			$data['classes'] .= $post_class.' ';
		}	 

		$data['permalink'] = get_permalink();	
		
		$data['title_link'] = '<h3 class="entry-title"><a rel="bookmark" title="Permalink to ' .$post->post_title. '" target="'.(isset($target) ? $target : '').'" href="' .get_permalink(). '">' .$short_title. '</a></h3>';	

		$data['image'] = '<div class="entry_thumb"><span class="btm_arrow"></span><a href="'.get_permalink($post->ID).'" title="'.sprintf( esc_attr__( 'Read: %s', 'op' ), the_title_attribute( 'echo=0' ) ).'" target="'.(isset($target) ? $target : '').'" rel="bookmark"> '.get_the_post_thumbnail($post->ID, 'thumbnail').'</a>';
		$data['date'] = '';
		$data['learn_more'] = '<a class="learn_more_btn" target="'.(isset($target) ? $target : '').'" href="' .get_permalink($post->ID). '">' .$post->post_title. '</a>';
			
		$data['posted_on'] = get_op_posted_on($post->ID);
		
				
		$data['title'] = $short_title;
		$data['excerpt'] = hb_neat_trim(get_the_excerpt(), 120);
        $json['posts'][] = $data;
    }
}
 
header('Content-type: application/json;');
return json_encode($json);
}
	
	
function home_boxes_js(){


$js = "<script>";

$js .= "
jQuery(document).ready(function ($) {
jQuery.loadScript = function (url, arg1, arg2) {
  var cache = false, callback = null;
  //arg1 and arg2 can be interchangable
  if ($.isFunction(arg1)){
    callback = arg1;
    cache = arg2 || cache;
  } else {
    cache = arg1 || cache;
    callback = arg2 || callback;
  }
               
  var load = true;
  //check all existing script tags in the page for the url
  jQuery('script[type=\"text/javascript\"]')
    .each(function () { 
      return load = (url != $(this).attr('src')); 
    });
  if (load){
    //didn't find it in the page, so load it
    jQuery.ajax({
      type: 'GET',
      url: url,
      success: callback,
      dataType: 'script',
      cache: cache
    });
  } else {
    //already loaded so just call the callback
    if (jQuery.isFunction(callback)) {
      callback.call(this);
    };
  };
};";

$js .= "$.loadScript('//cdnjs.cloudflare.com/ajax/libs/tinyscrollbar/1.81/jquery.tinyscrollbar.min.js', true, function() {";
	
$js .= "function set_container_width() { var totalContent = 0; $('#news_scroll .hentry').each(function () { pixel_width = parseInt($(this).innerWidth()); pixel_margin = parseInt($(this).css('margin-right').replace('px', '')); pixel_total = pixel_width + pixel_margin; $(this).width(pixel_width).css('margin-right', pixel_margin + 'px'); totalContent += pixel_total; }); $('#content #news_scroll .overview').css('width', totalContent);}";
	
$js .= "function set_container_height() {    $('#content #news_scroll .viewport').css('height', $('#news_scroll .hentry').height());}set_container_width();set_container_height();var offset = parseInt($('#news_scroll_offset').text());var clicks = 0;";

$js .= "function get_more_home_boxes() {
    pixel_width = parseInt($('#news_scroll .hentry').innerWidth());
    pixel_margin = parseInt($('#news_scroll .hentry').css('margin-right').replace('px', ''));
    pixel_total = pixel_width + pixel_margin;
    var totalContent = $('#news_scroll .overview').width();
    jQuery.ajax({
        url: $('h1.site-title a').attr('href') + 'wp-admin/admin-ajax.php',
        data: {
            'action': 'do_ajax',
            'fn': 'more_news_scroll',
            'offset': offset
        },
        dataType: 'JSON',
        success: function (data) {
            data = $.parseJSON(data);
            if (jQuery.isEmptyObject(data)) {
                var placeholder_int = pixel_total * clicks;
                $('#news_scroll .overview').animate({
                    'left': '-' + placeholder_int + 'px'
                }, 500, function () {
                    $('#news_scroll').tinyscrollbar_update(placeholder_int);
                });
            } else {
                $('#news_scroll .hentry:last').css('margin-right', pixel_margin + 'px');
                $.each(data.posts, function (i, post) {
                    totalContent += pixel_total;
                    $('#news_scroll .overview').css('width', totalContent);
                    var elem = '<article itemscope id=\"post-' + post.id + '\" style=\"width:' + pixel_width + 'px;\" class=\"' + post.classes + '\">';
                    elem += post.image;
                    elem += '<span itemprop=\"category\" class=\"category\">' + post.cat + '</span></div><header class=\"entry-header\">';
                    //elem += '<h3 class=\"entry-title\"><a rel=\"bookmark\" title=\"Permalink to ' + post.cat + '\" href=\"' + post.permalink + '\">' + post.title + '</a></h3>';
					elem += post.title_link;
                    elem += '&mdash;<br>';
                    elem += '<div class=\"entry-meta\">' + post.posted_on + '</div></header>';
                    elem += '<div class=\"entry-summary\">' + post.excerpt + '<br>';
                    elem += post.learn_more;
                    elem += '</div></article>';
                    offset++;
                    clicks++;
                    $('#news_scroll .overview').append(elem);
                });
                var placeholder_int = pixel_total * clicks;
				$('#news_scroll .hentry:last').css('margin-right',0);
                $('#news_scroll').tinyscrollbar_update(placeholder_int - pixel_total);
                $('#news_scroll .overview').animate({
                    'left': '-' + placeholder_int + 'px'
                }, 500);
            }
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    }).done(function (msg) {});
}";		


$js .= "$('#news_scroll').tinyscrollbar({axis: 'x'}, 'bottom');$('#news_scroll_right').click(function (e) {get_more_home_boxes();});$('#news_scroll_left').click(function (e) {pixel_width = parseInt($('#news_scroll .hentry').first().innerWidth());pixel_margin = parseInt($('#news_scroll .hentry').first().css('margin-right').replace('px', ''));pixel_total = pixel_width + pixel_margin;cur_left = $('#news_scroll .overview').offset().left;if (cur_left < 439.5) { $('#news_scroll .overview').animate({ 'left': '+=' + pixel_total + 'px'}, 500);}});});});";

$js .= "</script>";


return $js;
	
}



//ini_set('display_errors', true);
//ini_set('error_reporting', E_ALL);



/**
 * Home Boxes Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Home_Boxes extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Home_Boxes() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'home_boxes', 'description' => esc_html__('Site Feed Boxes', 'op') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 325, 'height' => 1050);

		/* Create the widget. */
		$this->WP_Widget( 'home_boxes-widget', esc_html__('Home Boxes', 'op'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		echo home_boxes_js();
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$number = $instance['number'];
		$home_boxes = $instance['home_box_content'];

		$fallback = $instance['fallback'];


		/* Before widget (defined by themes). */
		echo $before_widget;
		
		if ( $title ) {
			
			$title = $title[0].'<strong>'.$title[1].'</strong>'.substr($title, 2, strlen($title));
			echo $before_title . $title . $after_title;
			
		}	

	$k_news_scroll_html = '';
	//$k_news_scroll_html .= '<div id="news_scroll_wrap">';
	$k_news_scroll_html .= '<span style="display:none;" id="news_scroll_offset">'.((empty($home_boxes) || empty($home_boxes[0])) ? $number : 0).'</span>';
	$posts_count = wp_count_posts( 'posts' );
	//if($posts_count->publish > 4){
		$k_news_scroll_html .= '<div id="news_scroll_left"></div>';
		$k_news_scroll_html .= '<div id="news_scroll_right"></div>';
	//}
	$k_news_scroll_html .= '<div id="news_scroll" class="'.number_to_class($number, false).' clear">';
	$k_news_scroll_html .= '<div class="viewport"><div class="overview">';
	
	$categories = get_categories();
	$cat_slugs = array();
	foreach ($categories as $cat){	
		$cat_slugs[] = $cat->slug;
	}	
	
	$news_scroll_args = array(		
		'post_status' => 'publish' , 
		'post_type' => $fallback,
		'tax_query' => array(
		'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => $cat_slugs
			)
		),
		'orderby' => 'date', 
		'order' => 'DESC', 
		'paged' => get_query_var('paged'),
		'posts_per_page' => $number
	);
	
	if(!empty($home_boxes[0]) || !empty($home_boxes[0])) $news_scroll_args['post__in'] = $instance['home_box_content'];			

	
	$home_boxes = new WP_Query($news_scroll_args);	
	
	while ($home_boxes->have_posts()) : $home_boxes->the_post();

	$post_classes = get_post_class();
	$target = get_post_meta( $home_boxes->post->ID, '_links_to_target', true );
	
		$k_news_scroll_html .= '<article itemscope id="post-'.$home_boxes->post->ID.'" class="';
		
		foreach($post_classes as $post_class){		
			$k_news_scroll_html .= $post_class.' ';
			}		
			
		$k_news_scroll_html .= '">';
		 $category = get_the_category($home_boxes->post->ID);

			$k_news_scroll_html .= '<div class="entry_thumb"><span class="btm_arrow"></span><a href="'.get_permalink($home_boxes->post->ID).'" target="'.(isset($target) ? $target : '').'" title="'.sprintf( esc_attr__( 'Read: %s', 'op' ), the_title_attribute( 'echo=0' ) ).'" rel="bookmark"> '.get_the_post_thumbnail($home_boxes->post->ID, 'thumbnail').'</a>';
		
					$cat_info = '<span class="category" itemprop="category">';
						$cat_info .= $category[0]->cat_name; 
					$cat_info .= '</span>';
					$k_news_scroll_html .= $cat_info;
	$k_news_scroll_html .= '</div>';	
			$k_news_scroll_html .= '<header class="entry-header">';	
				$k_news_scroll_html .= '<h3 class="entry-title"><a href="'.get_permalink($home_boxes->post->ID).'" target="'.(isset($target) ? $target : '').'" title="'. sprintf( esc_attr__( 'Permalink to %s', 'op' ), the_title_attribute( 'echo=0' ) ).'" rel="bookmark">'.neat_trim(get_the_title($home_boxes->post->ID), 65).'</a></h3>
	&mdash;<br />';
				$k_news_scroll_html .= '<div class="entry-meta">'.get_op_posted_on($home_boxes->post->ID).'</div>';
			$k_news_scroll_html .= '</header>';
	
			$k_news_scroll_html .= '<div class="entry-summary">'.get_the_excerpt(); 
			$k_news_scroll_html .= '<br/><a href="'.get_permalink($home_boxes->post->ID).'" target="'.(isset($target) ? $target : '').'" class="learn_more_btn">'.get_the_title($home_boxes->post->ID).'</a>';
			$k_news_scroll_html .= '</div>';
	
		$k_news_scroll_html .= '</article>';
	
	endwhile;

	$k_news_scroll_html .= '</div>';
	$k_news_scroll_html .= '</div>';
	$k_news_scroll_html .= '<div class="scrollbar"><div class="track"><div class="thumb"></div></div></div>';		

	
	$k_scroll_script = '
	<script type="text/javascript">
	//var news_scroll_bar = jQuery(\'#news_scroll\');

	 
		function set_container_width(){			
			var news_scroll_item_width = jQuery(\'#news_scroll .hentry\').width();			
			jQuery(\'#news_scroll .hentry\').css({"width": news_scroll_item_width});		
			var totalContent = 0;
			jQuery(\'#news_scroll .hentry\').each(function () {
				var $this = jQuery(this);
				totalContent += news_scroll_item_width;
				jQuery(\'#news_scroll .overview\').css("width", totalContent);		
						
			});
		}
		
		jQuery(\'#news_scroll\').tinyscrollbar({ axis: \'x\'});
	var post_width_old;	// We have this for when the user has a query with no results. This little guy will store the width of the articles in the last query.	

		
	//set_container_width();
	});
	</script>';
	
	echo $k_news_scroll_html;



		echo $after_widget;
		
		//echo $k_scroll_script;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['fallback'] = $new_instance['fallback'];
		$instance['home_box_content'] = $new_instance['home_box_content'];


		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 		
			'title' => esc_html__('Home Boxes', 'op'),
			'number' => 4,
			'fallback' => array('post'),
			'home_box_content' => ''
		);
		
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance['title'];
		$number = $instance['number'];
		
		
		$fallback_val = $instance['fallback'];

		
		 ?>
        
        <?php //var_dump(get_post_types(array('_builtin'=> false)));
		
		$kc_post_types = get_post_types(array('_builtin'=> false));
		$kc_post_types[] = 'post';

		//$fallback = '<select id="'. $this->get_field_id('fallback').'" name="'. $this->get_field_name('fallback').'">';
		$fallback = '';
		foreach($kc_post_types as $kc_post_type){
				$cur_post_type = get_post_type_object($kc_post_type);
				$fallback .= '<br />
<label><input type="checkbox" '.(in_array($kc_post_type, $fallback_val) ? 'checked="checked"' : '').' name="'. $this->get_field_name('fallback').'[]" value="'.$kc_post_type.'" >'.$cur_post_type->labels->name.'</label>';			
			}
			
		//$fallback .= '</select>';

		 ?>

		<!-- Widget Title: Text Input -->
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id('fallback'); ?>"><?php _e('Fallback:'); ?></label>
        <?php echo $fallback; ?>
        </p>
        
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
        <small>How many should be shown on first load?</small>
        </p>

        
        <label><?php _e('Overrides:'); ?></label>
        <div id="home_boxes_wrap">
                <ul id="home_boxes">
                <?php
		
                
                if(empty($instance['home_box_content']) || !is_array($instance['home_box_content'])) {
                    echo '<li class="home_box">
					<label class="box_label">Box 1</label>
					<input type="text" value="'.get_the_title($instance['home_box_content']).'" class="home_box_title" name="" />
					<input type="hidden" value="'.$instance['home_box_content'].'" class="home_box_value" name="'. $this->get_field_name('home_box_content').'[]" />					
					<span class="move_box"></span>
					</li>';
                    }
                else {
                    $i = 1;
                    foreach($instance['home_box_content'] as $home_box){		
                        //var_dump($home_box);
                    echo '<li class="home_box">
					<label class="box_label">Box '.$i.'</label>
					<input type="text" value="'.get_the_title($home_box).'" class="home_box_title" name="" />
					<input type="hidden" value="'.$home_box.'" class="home_box_value" name="'. $this->get_field_name('home_box_content').'[]" />
					<span class="move_box"></span>
					</li>';                        
                        $i++;		
                        }
                    }
                 ?>
                </ul>
                <div id="add_remove_wrap"><span id="add_box" class="button-secondary" title="Add Box">+</span><?php if(count($instance['home_box_content']) > 1) echo '<span id="remove_box" class="button-secondary" title="Remove Box">-</span>'; ?></div>
              </div>
              <small>Overrides will  be loaded first, then the fallback post type until the total number of first load posts has been filled.</small>

<?php
	}
}

