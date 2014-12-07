<?php
/**
 * KwikTheme functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */


define('KT_BASENAME', basename(dirname( __FILE__ )));
define('KT_SETTINGS', preg_replace('/-/', '_', KT_BASENAME).'_settings');
define("THEME_PREFIX", "kt_");

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */

if (!isset($content_width)) {
	$content_width = 625;
}

/**
 * Sets up theme defaults and registers the various WordPress features that
 * KwikTheme supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since KwikTheme 1.0
 */
function kt_setup() {
	/*
	 * Makes KwikTheme available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on KwikTheme, use a find and replace
	 * to change 'op' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('op', get_template_directory() . '/languages');
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support('automatic-feed-links');
	// This theme supports a variety of post formats.
	add_theme_support('post-formats', array('aside', 'image', 'link', 'quote', 'status'));
	register_nav_menu('primary', __('Primary Menu', 'op'));
	register_nav_menu('top', __('Top Menu Menu', 'op'));
	register_nav_menu('secondary', __('Footer Menu', 'op'));
	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support('post-thumbnails');

	add_image_size("header_img", 1190, 220, true);

	set_post_thumbnail_size(356, 242);// Unlimited height, soft crop
}
add_action('after_setup_theme', 'kt_setup');

foreach (glob(TEMPLATEPATH . "/inc/*.php") as $inc_filename) {
	include $inc_filename;
}
foreach (glob(TEMPLATEPATH . "/widgets/*.php") as $widget_filename) {
	include $widget_filename;
}

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since KwikTheme 1.0
 */
function kt_scripts_styles() {
	global $wp_styles;
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	/*
	 * Adds JavaScript for handling the navigation menu hide-and-show behavior.
	 */
	wp_enqueue_script('colorbox', get_template_directory_uri() . '/js/jquery.colorbox-min.js', array('jquery'));
	wp_enqueue_script('site', get_template_directory_uri() . '/js/site.js', array('jquery', 'colorbox'));

	wp_enqueue_script('jquery-cycle', 'http://malsup.github.io/min/jquery.cycle2.min.js', array('jquery'));

	wp_enqueue_style('op-style', get_stylesheet_uri());
	wp_enqueue_style('kf-custom', get_template_directory_uri() . '/css/kf_custom.php', array('op-style'), '11032014');

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	// wp_enqueue_style( 'op-ie', get_template_directory_uri() . '/css/ie.css', array( 'op-style' ), '20121010' );
	// $wp_styles->add_data( 'op-ie', 'conditional', 'lte IE 9' );
	/*
global $is_IE;
if($is_IE){
wp_register_script( 'site-ie', get_template_directory_uri().'/js/site-ie.js');
wp_enqueue_script('site-ie' );
}
 */
}
add_action('wp_enqueue_scripts', 'kt_scripts_styles');

function kt_admin_fonts($hook_suffix) {
	if ('off' !== _x('on', 'Open Sans font: on or off', 'op')) {
		$subsets = 'latin,latin-ext';
		/* translators: To add an additional Open Sans character subset specific to your language, translate
		this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x('no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'op');
		if ('cyrillic' == $subset) {
			$subsets .= ',cyrillic,cyrillic-ext';
		} elseif ('greek' == $subset) {
			$subsets .= ',greek,greek-ext';
		} elseif ('vietnamese' == $subset) {
			$subsets .= ',vietnamese';
		}

		$protocol = is_ssl() ? 'https' : 'http';

	}
}
add_action('admin_enqueue_scripts', 'kt_admin_fonts', 10, 1);

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since KwikTheme 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function kt_wp_title($title, $sep) {
	global $paged, $page;
	if (is_feed()) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo('name');
	// Add the site description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && (is_home() || is_front_page())) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ($paged >= 2 || $page >= 2) {
		$title = "$title $sep " . sprintf(__('Page %s', 'op'), max($paged, $page));
	}

	return $title;
}
add_filter('wp_title', 'kt_wp_title', 10, 2);

function get_SEO_tags() {
	global $post;
	$tags = get_tags(array(
		'orderby' => 'count',
		'order' => 'DESC',
	));
	$xt = 1;
	$the_tags = '';
	foreach ($tags as $tag) {
		if ($xt <= 9) {
			$the_tags .= $tag->name . ", ";
		}
		$xt++;
	}
	return $the_tags;
}

function get_meta_description() {

	$description = '';

	if (have_posts() && is_single() || is_page()):
		while (have_posts()):
			the_post();
			$out_excerpt = str_replace(array(
				"\r\n",
				"\r",
				"\n",
			), "", get_the_excerpt());
			$out_excerpt = substr($out_excerpt, 0, 150);
			$description .= apply_filters('the_excerpt_rss', $out_excerpt);
		endwhile;
	elseif (is_category() || is_tag()):
		if (is_category()):
			$description .= "Posts in to Category:" . ucfirst(single_cat_title("", FALSE));
		elseif (is_tag()):
			$description .= "Posts with Tag:" . ucfirst(single_tag_title("", FALSE));
		endif;
	else:
		$description .= get_bloginfo('description');
	endif;

	return $description;

}

function kt_excerpt_length($length) {

	if (is_tag() || is_archive() || is_home()) {
		return 24;
	}if (is_page('home')) {

		return 18;

	} else {

		return 50;

	}
}
add_filter('excerpt_length', 'kt_excerpt_length');

// Replaces the excerpt "more" text by a link
function new_excerpt_more($more) {
	global $post;
	return '&hellip; <a class="moretag" href="' . get_permalink($post->ID) . '">Read more</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since KwikTheme 1.0
 */
function kt_page_menu_args($args) {
	if (!isset($args['show_home'])) {
		$args['show_home'] = true;
	}

	return $args;
}
add_filter('wp_page_menu_args', 'kt_page_menu_args');
/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since KwikTheme 1.0
 */
function kt_widgets_init() {

	register_sidebar(array(
		'name' => __('Main Sidebar', 'op'),
		'id' => 'sidebar-1',
		'description' => __('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'op'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => __('Newsroom Sidebar', 'op'),
		'id' => 'sidebar-newsroom',
		'description' => __('Appears in Newsroom', 'op'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => __('Horizontal Widget Area 1', 'op'),
		'id' => 'sidebar-horz-1',
		'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'op'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	));
	register_sidebar(array(
		'name' => __('Horizontal Widget Area 2', 'op'),
		'id' => 'sidebar-horz-2',
		'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'op'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	));

	register_sidebar(array(
		'name' => __('Footer Widgets', 'op'),
		'id' => 'footer_widgets',
		'description' => __('Appears in the footer', 'op'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

}
add_action('widgets_init', 'kt_widgets_init');
if (!function_exists('kt_content_nav')):
	/**
	 * Displays navigation to next/previous pages when applicable.
	 *
	 * @since KwikTheme 1.0
	 */
	function kt_content_nav($html_id) {
		global $wp_query;
		$html_id = esc_attr($html_id);
		if ($wp_query->max_num_pages > 1):?>
					<nav id="<?php echo $html_id;?>" class="navigation" role="navigation">
						<h3 class="assistive-text"><?php _e('Post navigation', 'op');?></h3>
						<div class="nav-previous alignleft"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'op'));?></div>
						<div class="nav-next alignright"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'op'));?></div>
					</nav><!-- #<?php echo $html_id;?>.navigation -->
	<?php endif;
}
endif;
if (!function_exists('kt_comment')):
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own kt_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since KwikTheme 1.0
	 */
	function kt_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		switch ($comment->comment_type):
		case 'pingback':
		case 'trackback':
			// Display trackbacks differently than normal comments.
			?>
							<li <?php comment_class();?> id="comment-<?php comment_ID();?>">
					<p>			<?php _e('Pingback:', 'op');?> <?php comment_author_link();?> <?php edit_comment_link(__('(Edit)', 'op'), '<span class="edit-link">', '</span>');?></p>
		<?php
		break;
		default:
			// Proceed with normal comments.
			global $post;
			?>
							<li <?php comment_class();?> id="li-comment-<?php comment_ID();?>">
					<article id="comment-			<?php comment_ID();?>" class="comment">
						<header class="comment-meta comment-author vcard">
		<?php
		echo get_avatar($comment, 44);
			printf('<cite class="fn">%1$s %2$s</cite>',
				get_comment_author_link(),
				// If current post author is also comment author, make it known visually.
				($comment->user_id === $post->post_author) ? '<span> ' . __('Post author', 'op') . '</span>' : ''
			);
			printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
				esc_url(get_comment_link($comment->comment_ID)),
				get_comment_time('c'),
				/* translators: 1: date, 2: time */
				sprintf(__('%1$s at %2$s', 'op'), get_comment_date(), get_comment_time())
			);
			?>
		</header><!-- .comment-meta -->
		<?php if ('0' == $comment->comment_approved):?>
										<p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'op');?></p>
		<?php endif;?>
	<section class="comment-content comment">
	<?php comment_text();?>
							<?php edit_comment_link(__('Edit', 'op'), '<p class="edit-link">', '</p>');?>
	</section><!-- .comment-content -->
			<div class="reply">
	<?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'op'), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'])));?>
	</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
	break;
		endswitch;// end comment_type check
	}
	endif;

	if (!function_exists('kt_entry_meta')):
		/**
	 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
	 *
	 * Create your own kt_entry_meta() to override in a child theme.
	 *
	 * @since KwikTheme 1.0
	 */
		function kt_entry_meta() {
			// Translators: used between list items, there is a space after the comma.
			$categories_list = get_the_category_list(__(', ', 'op'));
			// Translators: used between list items, there is a space after the comma.
			$tag_list = get_the_tag_list('', __(', ', 'op'));

			// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
			if ($tag_list) {$utility_text = __('<strong>Tags:</strong> %2$s', 'op');
		}

		if ($categories_list) {$utility_text .= __('<br/><strong>Category(s):</strong> %1$s', 'op');
		}

		if (!is_home()) {
			printf(
				$utility_text,
				$categories_list,
				$tag_list
			);

		}
	}

endif;

if (!function_exists('kt_author_date')):
	/**
	 * Prints HTML with meta information for current post author and date.
	 *
	 * @since KwikTheme 1.0
	 */
	function kt_author_date() {

		if (function_exists('coauthors_posts_links')) {
			$author = coauthors_posts_links(null, null, __('By: ', 'op'), null, false);
		} else {
		$author = sprintf('<span class="author vcard">By: <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			esc_attr(sprintf(__('View all posts by %s', 'op'), get_the_author())),
			get_the_author()
		);
	}

	$date = sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url(get_permalink()),
		esc_attr(get_the_time()),
		esc_attr(get_the_date('c')),
		esc_html(get_the_date())
	);

	echo $author . '&nbsp; - &nbsp;' . $date;

}
endif;

/**
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since KwikTheme 1.0
 *
 * @param array Existing class values.
 * @return array Filtered class values.
 */
function kt_body_class($classes) {

	if (kt_child_links()) {$classes[] = 'has_children';
	}

	if (!is_active_sidebar('sidebar-1') || is_page_template('page-templates/full-width.php')) {
		$classes[] = 'full-width';
	}

	if (is_page_template('page-templates/front-page.php')) {
		$classes[] = 'template-front-page';
		if (has_post_thumbnail()) {
			$classes[] = 'has-post-thumbnail';
		}

		if (is_active_sidebar('sidebar-horz-1') && is_active_sidebar('sidebar-horz-2')) {
			$classes[] = 'two-sidebars';
		}
	}

	// Enable custom font class only if the font CSS is queued to load.
	if (wp_style_is('op-fonts', 'queue')) {
		$classes[] = 'custom-font-enabled';
	}

	if (!is_multi_author()) {
		$classes[] = 'single-author';
	}

	return $classes;
}
add_filter('body_class', 'kt_body_class');

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since KwikTheme 1.0
 */
function kt_content_width() {
	if (is_page_template('page-templates/full-width.php') || is_attachment() || !is_active_sidebar('sidebar-1')) {
		global $content_width;
		$content_width = 960;
	}
}
add_action('template_redirect', 'kt_content_width');

function __update_post_meta($post_id, $field_name, $value = '') {
	if (empty($value) OR !$value) {
		delete_post_meta($post_id, $field_name);
	} elseif (!get_post_meta($post_id, $field_name)) {
		add_post_meta($post_id, $field_name, $value);
	} else {
		update_post_meta($post_id, $field_name, $value);
	}
}

// Auto-set the featured image
function kt_autoset_featured() {
	global $post;
	$already_has_thumb = has_post_thumbnail($post->ID);
	if (!$already_has_thumb) {
		$attached_image = get_children("post_parent=" . $post->ID . "&post_type=attachment&post_mime_type=image&numberposts=1");
		if ($attached_image) {
			foreach ($attached_image as $attachment_id => $attachment) {
				set_post_thumbnail($post->ID, $attachment_id);
			}
		}
	}
}//end function
//add_action('the_post', 'kt_autoset_featured');
add_action('publish_post', 'kt_autoset_featured');
/*add_action('draft_to_publish', 'kt_autoset_featured');
add_action('new_to_publish', 'kt_autoset_featured');
add_action('pending_to_publish', 'kt_autoset_featured');
add_action('future_to_publish', 'kt_autoset_featured');*/



//Function which returns content in place of shortcode
function addservicebox($atts, $content = null) {
	return "\t\n" . '<div class="columnThird">' . do_shortcode($content) . '</div>';
}
function addpullquote($atts, $content = null) {
	return "\t\n" . '<div class="columnThird">pullquote!' . do_shortcode($content) . '</div>';
}

//Call above function, which replaces the content.
add_shortcode("addservicebox", "addservicebox");//First parameter is the name of the shortcode (represents [columnThird] in this case), second is the function to call when this is found.
add_shortcode("addpullquote", "addpullquote");

//Creating TinyMCE buttons
//********************************************************************
//check user has correct permissions and hook some functions into the tiny MCE architecture.
function add_editor_button() {
	//Check if user has correct level of privileges + hook into Tiny MC methods.
	if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
		//Check if Editor is in Visual, or rich text, edior mode.
		if (get_user_option('rich_editing')) {
			//Called when tiny MCE loads plugins - 'add_custom' is defined below.
			add_filter('mce_external_plugins', 'add_custom');
			//Called when buttons are loading. -'register_button' is defined below.
			add_filter('mce_buttons', 'register_button');
		}
	}
}

//add action is a wordpress function, it adds a function to a specific action... in this case the function is added to the 'init' action. Init action runs after wordpress is finished loading!
// add_action('init', 'add_editor_button');

function hex_to_RGB($hex_color) {

	$rgb = array();
	$rgb['red'] = hexdec(substr($hex_color, 1, 2));
	$rgb['green'] = hexdec(substr($hex_color, 3, 2));
	$rgb['blue'] = hexdec(substr($hex_color, 5, 2));

	return $rgb;

}

function ImageColorAllocateFromHex($img, $hexstr) {
	$int = hexdec($hexstr);

	return ImageColorAllocate($img,
		0xFF&($int >> 0x10),
		0xFF&($int >> 0x8),
		0xFF&$int);
}

function invert_color($start_color) {

	$color_red = hexdec(substr($start_color, 1, 2));
	$color_green = hexdec(substr($start_color, 3, 2));
	$color_blue = hexdec(substr($start_color, 5, 2));

	$new_red = dechex(255 - $color_red);
	$new_green = dechex(255 - $color_green);
	$new_blue = dechex(255 - $color_blue);

	if (strlen($new_red) == 1) {$new_red .= '0';}
	if (strlen($new_green) == 1) {$new_green .= '0';}
	if (strlen($new_blue) == 1) {$new_blue .= '0';}

	$new_color = '#' . $new_red . $new_green . $new_blue;

	return $new_color;

}

function get_rid_of_editor_styles() {
	global $editor_styles;
	if (is_array($editor_styles)) {
		foreach ($editor_styles as $e) {
			$e = 'editor-style.css' == $e ? '' : $e;
		}
	}
}

add_action('init', 'get_rid_of_editor_styles');

function add_my_own_editor_styles() {
	add_editor_style('css/editor-style.css');
}
add_action('after_setup_theme', 'add_my_own_editor_styles');

//Add button to the button array.
function register_button($buttons) {
	//Use PHP 'array_push' function to add the columnThird button to the $buttons array
	array_push($buttons, "addservicebox");
	array_push($buttons, "addpullquote");
	//Return buttons array to TinyMCE
	return $buttons;
}

//Add custom plugin to TinyMCE - returns associative array which contains link to JS file. The JS file will contain your plugin when created in the following step.
function add_custom($plugin_array) {
	$plugin_array['addservicebox'] = get_bloginfo('template_url') . '/js/editor_plugin.js';
	$plugin_array['addpullquote'] = get_bloginfo('template_url') . '/js/editor_plugin_pullquote.js';
	return $plugin_array;
}

add_action('wp_ajax_add_service_box_function_callback', 'add_service_box_function_callback');
function add_service_box_function_callback() {?>
<!DOCTYPE html>
<head>
    <title>Create a Service Box</title>
<script type="text/javascript" src="<?php bloginfo('url')?>/wp-includes/js/tinymce/tiny_mce_popup.js?ver=358-20121205"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory')?>/js/service_box_dialog.js"></script>
</head>
<body>
<form onsubmit="ServiceBox.update();return false;" action="#">
	<table border="0" cellpadding="4" cellspacing="0" role="presentation">
		<tr>
			<td colspan="2" class="title" id="app_title">Add Service Box</td>
		</tr>
		<tr>
			<td class="nowrap"><label for="anchorName">Title:</label></td>
			<td><input name="anchorName" type="text" class="mceFocus" id="anchorName" value="" style="width: 200px" aria-required="true" /></td>
		</tr>
		<tr>
			<td class="nowrap"><label for="anchorName">Content:</label></td>
			<td><textarea name="service_box_content" class="mceFocus" cols="37" id="service_box_content"></textarea></td>
		</tr>
	</table>

	<div class="mceActionPanel">
		<input type="submit" id="insert" name="insert" value="{#update}" />
		<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
	</div>
</form>
</html>
<?php
die;
};

add_action('wp_ajax_add_pull_quote_function_callback', 'add_pull_quote_function_callback');
function add_pull_quote_function_callback() {?>
<!DOCTYPE html>
<head>
    <title>Create a Pull-Quote</title>
<script type="text/javascript" src="<?php bloginfo('url')?>/wp-includes/js/tinymce/tiny_mce_popup.js?ver=358-20121205"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory')?>/js/pull_quote_dialog.js"></script>
</head>
<body>
<form onsubmit="PullQuote.update();return false;" action="#">
	<table border="0" cellpadding="4" cellspacing="0" role="presentation">
		<tr>
			<td colspan="2" class="title" id="app_title">Add a Pull Quote</td>
		</tr>
		<tr>
			<td class="nowrap"><label for="pq_title">Title:</label></td>
			<td><input name="pq_title" type="text" class="mceFocus" id="pq_title" value="" style="width: 200px" aria-required="true" /></td>
		</tr>
		<tr>
			<td class="nowrap"><label for="pull_quote_content">Content:</label></td>
			<td><textarea name="pull_quote_content" class="mceFocus" cols="37" id="pull_quote_content"></textarea></td>
		</tr>
		<tr>
			<td class="nowrap"><label for="pull_quote_align">Alignment:</label></td>
			<td>
            <select name="pull_quote_align" class="mceFocus" id="pull_quote_align">
                <option value="block">None</option>
                <option value="alignleft">Left</option>
                <option value="aligncenter">Center</option>
                <option value="alignright">Right</option>
            </select>
            </td>
		</tr>
		<tr>
			<td class="nowrap"><label for="pull_quote_width">Width:</label></td>
			<td>
            <select name="pull_quote_width" class="mceFocus" id="pull_quote_width">
                <option value="width_10">10%</option>
                <option value="width_20">20%</option>
                <option value="width_30">30%</option>
                <option value="width_40">40%</option>
                <option value="width_60">60%</option>
                <option value="width_90">90%</option>
                <option value="">Block display</option>
            </select>
            </td>
		</tr>
	</table>

	<div class="mceActionPanel">
		<input type="submit" id="insert" name="insert" value="{#update}" />
		<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
	</div>
</form>
</html>
<?php
die;
};

function get_taxonomy_parents($id, $taxonomy, $link = false, $separator = '/', $nicename = false, $visited = array()) {
	$chain = '';
	$parent = &get_term($id, $taxonomy);

	if (is_wp_error($parent)) {
		return $parent;
	}

	if ($nicename) {
		$name = $parent->slug;
	} else {

		$name = $parent->name;
	}

	if ($parent->parent && ($parent->parent != $parent->term_id) && !in_array($parent->parent, $visited)) {
		$visited[] = $parent->parent;
		$chain .= get_taxonomy_parents($parent->parent, $taxonomy, $link, $separator, $nicename, $visited);

	}

	if ($link) {
		// nothing, can't get this working :(
	} else {

		$chain .= $name . $separator;
	}

	return $chain;
}

/**
 * Released under GPL
 * This is a simple class that gives you options to use bitly's API method
 * /v3/expand and /v3/shorten
 * @access public
 * @author Jaspreet Chahal
 * @copyright Jaspreet Chahal
 * @version 0.1a
 * @link http://jaspreetchahal.org
 * @package bitly
 */

class kt_bitly {
	private $_username = null;
	private $_apikey = null;
	private $_format = null;
	// format can be json,xml,txt
	// read more here http://dev.bitly.com/formats.html
	private $_apiurl = null;
	public function __construct($username, $apikey, $secure = false, $format = 'txt') {
		$this->_username = $username;
		$this->_apikey = $apikey;
		$this->_format = $format;
		if ($secure) {
			$this->_apiurl = 'https://api-ssl.bitly.com';
		} else {
			$this->_apiurl = 'http://api.bitly.com';
		}
	}

	/*
	Read more: http://dev.bitly.com/links.html#v3_shorten
	 */
	public function shortenURL($urltoshorten) {
		// check here if the URL is valid or not
		$bitlyconnector = $this->_apiurl . '/v3/shorten?login=' . $this->_username . '&apiKey=' . $this->_apikey . '&uri=' . urlencode($urltoshorten) . '&format=' . $this->_format;
		return curl_get_result($bitlyconnector);
	}

	/*
	Read more: http://dev.bitly.com/links.html#v3_expand
	 */
	function longURL($urltolongify) {
		$bitlyconnector = $this->_apiurl . '/v3/expand?login=' . $this->_username . '&apiKey=' . $this->_apikey . '&shortUrl=' . urlencode($urltolongify) . '&format=' . $this->_format;
		return curl_get_result($bitlyconnector);
	}

}

function social_link() {
	$options = KwikThemeOptions::kt_get_options();
	if (!empty($options['bitly'][0])) {
		bitly();
	} else {
		currentPageURL();
	}
}

function bitly() {
	global $post;
	$options = KwikThemeOptions::kt_get_options();
	$bitly_meta = get_post_meta($post->ID, 'bitly_meta', true);

	if (is_single() && $bitly_meta) {
		return urldecode($bitly_meta);
	} else {

		$bitly = new kt_bitly($options['bitly'][0], $options['bitly'][1]);
		$bitly_short_url = $bitly->shortenURL(currentPageURL());
		if (is_single() && !$bitly_meta) {add_post_meta($post->ID, 'bitly_meta', urlencode($bitly_short_url), true);
		}

		return $bitly_short_url;
	}

}

// Save Bitly meta data
function save_bitly_urls($post_id, $post) {

	// Is the user allowed to edit the post or page?
	if (!current_user_can('edit_post', $post->ID)) {return $post->ID;
	}

	$options = KwikThemeOptions::kt_get_options();
	if (!empty($options['bitly'][0]) && !empty($options['bitly'][1])) {
		$bitly = new kt_bitly($options['bitly'][0], $options['bitly'][1]);
		$bitly_short_url = $bitly->shortenURL(get_permalink($post_id));

		if ($post->post_type == 'revision') {return;
		}

		__update_post_meta($post->ID, 'bitly_meta', urlencode($bitly_short_url));
	} else {
		return;
	}

}
//add_action('save_post', 'save_bitly_urls', 1, 2);

/* returns a result form url */
function curl_get_result($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function currentPageURL() {
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"]) == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function widget_count($sidebar_id, $echo = true) {
	$the_sidebars = wp_get_sidebars_widgets();
	if (!isset($the_sidebars[$sidebar_id])) {
		return __('Invalid sidebar ID');
	}

	if ($echo) {
		echo count($the_sidebars[$sidebar_id]);
	} else {

		return count($the_sidebars[$sidebar_id]);
	}
}



function archive_feature_image($post_id, $echo = true) {

	if (has_post_thumbnail()) {
		$thumb = get_the_post_thumbnail($post_id, 'thumbnail');
	} else {

		$attached_image = get_children("post_parent=" . $post_id . "&post_type=attachment&post_mime_type=image&numberposts=1");
		if ($attached_image) {
			foreach ($attached_image as $attachment_id => $attachment) {
				set_post_thumbnail($post_id, $attachment_id);
				$thumb = wp_get_attachment_image($attachment_id, 'thumbnail');
			}
		} else {

			$args = array(
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'post_status' => 'inherit',
				'posts_per_page' => 1,
				'orderby' => 'rand',
			);

			$query_images = new WP_Query($args);

			$thumb = wp_get_attachment_image($query_images->posts[0]->ID, 'thumbnail');

		}

	}

	if (!$echo) {return $thumb;} else {echo $thumb;}

}

function kt_paginate($is_child = true) {
	global $wp_query;
	$pagination = '';
	$int = 9999999;
	$pagination .= '<div class="pagination">';
	$pagination .= paginate_links(array(
		'base' => str_replace($int, '%#%', get_pagenum_link($int)),
		'format' => '?paged=%#%',
		'current' => max(1, get_query_var('paged')),
		'total' => $wp_query->max_num_pages
	));
	$pagination .= '</div>';
	echo $pagination;
}

function neat_trim($str, $n, $delim = '&hellip;', $neat = true) {

	$len = strlen($str);
	if ($len > $n) {
		if ($neat) {
			preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
			return rtrim($matches[1]) . $delim;
		} else {
			return substr($str, 0, $n) . $delim;

		}
	} else {
		return $str;
	}

}


function getDomain($url) {
	$pieces = parse_url($url);
	$domain = isset($pieces['host']) ? $pieces['host'] : '';
	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
		return $regs['domain'];
	}
	return false;
}

//Add class to edit button
function custom_edit_links($output) {

	$obj = get_post_type_object(get_post_type());
	$type = $obj->labels->singular_name;

	$output = str_replace('title="Edit Post"', 'title="Edit ' . $type . '"', $output);
	return $output;
}
add_filter('edit_post_link', 'custom_edit_links');

function getRealIp() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

// The edit page Meta box
function do_by_the_numbers($post_id) {
	global $post;

	$btns_good = get_post_meta($post_id, 'btn_good', false);
	$btns_good = $btns_good[0];
	$btns_bad = get_post_meta($post_id, 'btn_bad', false);
	$btns_bad = $btns_bad[0];
	$btn_meta = '';

	$btn_meta .= '<div class="clear">';
	$btn_meta .= '<ul id="btn_good" class="btn_ul clear" style="margin-right:12%">';
	$btn_meta .= '<li class="btn_ul_title"><h2 style="color:#3f9731">' . __('The Good', 'op') . '</h2></li>';
	if (!empty($btns_good)) {
		foreach ($btns_good as $btn_good) {
			$btn_meta .= '<li><div class="btn_nums"><span class="big_num">' . $btn_good[0] . '</span>' . $btn_good[1] . '</div><div class="btn_desc">' . $btn_good[2] . '</div></li>';
		}
	}
	$btn_meta .= '</ul>';
	$btn_meta .= '<ul id="btn_bad" class="btn_ul clear">';
	$btn_meta .= '<li class="btn_ul_title"><h2 style="color:#cc3300">' . __('The Bad', 'op') . '</h2></li>';
	if (!empty($btns_bad)) {
		foreach ($btns_bad as $btn_bad) {
			$btn_meta .= '<li><div class="btn_nums"><span class="big_num">' . $btn_bad[0] . '</span>' . $btn_bad[1] . '</div><div class="btn_desc">' . $btn_bad[2] . '</div></li>';
		}
	}
	$btn_meta .= '</ul>';
	$btn_meta .= '</div>';

	echo $btn_meta;

}

function kt_child_links() {

	if (is_page() || is_home() || is_single()) {
		global $post;
		if (is_home()) {
			$child_links = '';
			$cats = get_categories('hide_empty=1&taxonomy=category&orderby=term_order');
			$child_links = '<li><a href="#all">' . __('All', 'kwik') . '</a></li>';
			/*$child_links .= '<li><a rel="category-press-releases" title="Show only Press Releases posts" id="category-press-releases-filter" href="#category-press-releases">Press Releases</a></li>';
			$child_links .= '<li><a rel="category-industry-coverage" title="Show only Industry Coverage posts" id="category-industry-coverage-filter" href="#category-industry-coverage">Industry Coverage</a></li>';
			$child_links .= '<li><a rel="category-events" title="Show only Events posts" id="category-events-filter" href="#category-events">Events</a></li>';
			$child_links .= '<li><a rel="category-videos" title="Show only Videos posts" id="category-videos-filter" href="#category-videos">Videos</a></li>';
			$child_links .= '<li><a rel="category-blogs" title="Show only Blogs posts" id="category-blogs-filter" href="#category-blogs">Blogs</a></li></ul>';*/

			foreach ($cats as $cat) {
				$child_links .= '<li><a href="#category-' . $cat->category_nicename . '" id="category-' . $cat->category_nicename . '-filter" title="Show only ' . $cat->name . ' posts" rel="category-' . $cat->category_nicename . '">' . $cat->name . '</a></li>';
			}
		} else if ($post->post_parent) {
			$ancestors = get_post_ancestors($post->ID);
			$root = $post->post_parent == '532' ? count($ancestors) - 2 : count($ancestors) - 1;
			$parent = $ancestors[$root];
			if ($root == 0) {
				if ($post->post_parent == '532') {
					$child_links = wp_list_pages("sort_column=menu_order&title_li=&child_of=137&echo=0&depth=1&exclude=532");
				} else {
					$child_links = wp_list_pages("sort_column=menu_order&exclude=532&title_li=&child_of=" . $post->post_parent . "&echo=0&depth=1");
				}
			}
		} else {
			$child_links = wp_list_pages("sort_column=menu_order&&exclude=532&title_li=&child_of=" . $post->ID . "&echo=0&depth=1");
		}

		if (isset($child_links) && !empty($child_links)) {//print_r( $child_links );
			$children = '<ul id="child_links" class="' . (is_home() ? 'article_filter' : '') . '">';
			$children .= '<li><ul>';
			$children .= $child_links;
			$children .= '</ul></li>';
			//if($post->post_name === 'technical-resources' || $post->post_parent == '532') $children .= '<li style="border-top:0 none">'.tech_resource_menu().'</li>';
			$this_page_id = get_queried_object_id();
			if ($this_page_id == 532 || $this_page_id == 137 || $this_page_id == 140 || $this_page_id == 142) {
				$children .= '<li class="techli' . ($this_page_id == 532 ? "open" : " techliclose") . '"><a href="' . get_page_link(532) . '">Technical Resources</a>';
				$cargs = array('type' => 'technical_resources', 'orderby' => 'name', 'order' => 'ASC', 'taxonomy' => 'technical_resources_category');
				$categories = get_categories($cargs);
				if ($this_page_id == 532) {
					$children .= '<ul class="subtags">';
				} else {
					$children .= '<ul class="subtags subtagsclose">';
				}
				foreach ($categories as $category) {
					$cid = $category->term_id;
					$children .= '<li><a href="' . get_term_link($category, 'technical_resources_category') . '">' . $category->name . '</a></li>';
				}
				$children .= '</ul></li>';
			}
			$children .= '</ul>';

			return $children;
		} else {
			return false;
		}

	} else if (is_home()) {

	}

}


if (!function_exists('get_kt_posted_on')):
  function get_kt_posted_on($post_id) {
    $posted_on = sprintf(__('<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>', 'op'),
      esc_url(get_permalink($post_id)),
      esc_attr(get_the_time()),
      esc_attr(get_the_date('c')),
      esc_html(get_the_date())
    );
    return $posted_on;
  }
endif;

if ( ! function_exists( 'kt_posted_on' ) ) :
function kt_posted_on() {
  if ( is_sticky() && is_home() && ! is_paged() ) {
    echo '<span class="featured-post">' . __( 'Sticky', 'twentyfourteen' ) . '</span>';
  }
  echo get_kt_posted_on(the_id());
}
endif;

// page and section headers
function OPtopHeader($wp_query) {
	// $options = KwikThemeOptions::kt_get_options();
  $options = KwikThemeOptions::kt_get_options();
	if (!is_404()) {
		if (is_page() && !is_front_page()) {
			$page_id = $wp_query->queried_object->ID;

			$header_img_id = get_post_meta($page_id, "header_img", true);
			$header_img_text = get_post_meta($page_id, "header_text", true);

		}// if(is_page())
		elseif (is_home()) {// if blog page

			$header_img_id = $options['headers']['value']['post']['img'];
			$header_img_text = $options['headers']['value']['post']['text'];

		}
	} else {

		$header_img_id = $options['headers']['value']['404']['img'];
		$header_img_text = $options['headers']['value']['404']['text'];

	}

	if (isset($header_img_id) && $header_img_id != '') {
		$output = '';

		$header_img = wp_get_attachment_image_src($header_img_id, 'header_img');
		$header_img_src = $header_img['0'];
		$header_src_height = $header_img['2'];

		$output .= '<style type="text/css">';
		$output .= '#page_header{
		                  background:url("' . $header_img_src . '") no-repeat 50% 0; background-size: 100% auto;
		                  height: ' . (!empty($header_src_height) ? ($header_src_height + 20) . 'px' : '80px') . ';
		                }';
		$output .= '</style>';

		$output .= '<div id="page_header">';
		$output .= ($header_img_text ? '<h2>' . $header_img_text . '</h2>' : '');
		$output .= '</div>';

		return $output;

	}// if($header_img_id)
}
