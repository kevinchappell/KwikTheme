<?php
/**
 * KwikTheme functions and definitions.
 *
 * @package WordPress
 * @subpackage KwikTheme
 *
 * @since KwikTheme 1.0
 */

define( 'KT_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'KT_SETTINGS', preg_replace( '/-/', '_', KT_BASENAME ) . '_settings' );
define( 'THEME_PREFIX', 'kt_' );


function run_activate_plugin( $plugin ) {
    $current = get_option( 'active_plugins' );
    $plugin = plugin_basename( trim( $plugin ) );

    if ( !in_array( $plugin, $current ) ) {
        $current[] = $plugin;
        sort( $current );
        do_action( 'activate_plugin', trim( $plugin ) );
        update_option( 'active_plugins', $current );
        do_action( 'activate_' . trim( $plugin ) );
        do_action( 'activated_plugin', trim( $plugin) );
    }

    return null;
}

run_activate_plugin( 'kwik-framework/kwik-framework.php' );

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */

if ( ! isset( $content_width) ) {
	$content_width = 625;
}

/**
 * Setup theme defaults and register the various WordPress features that
 * KwikTheme supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 *     custom background, and post formats.
 * @uses register_nav_menus() To add support for navigation menus.
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
	 * to change 'kwik' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'kwik', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ));
	register_nav_menus(array(
		'main' => 'Main Menu',
		'top' => 'Top Menu Menu',
		'footer' => 'Footer Menu',
	));
	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );

	add_image_size("header_img", 1920, 450, true);
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(300, 300);

}
add_action( 'after_setup_theme', 'kt_setup' );

function tags_for_pages() {
	register_taxonomy_for_object_type( 'post_tag', 'page' );
}
add_action( 'init', 'tags_for_pages' );

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
	if (is_singular() && comments_open() && get_option( 'thread_comments' )) {
		wp_enqueue_script( 'comment-reply' );
	}

	/*
	 * Adds JavaScript for handling the navigation menu hide-and-show behavior.
	 */
	wp_enqueue_script( 'site', get_template_directory_uri() . '/js/site.js', array( 'jquery' ));

	wp_enqueue_script( 'jquery-cycle', 'http://malsup.github.io/min/jquery.cycle2.min.js', array( 'jquery' ));
	wp_enqueue_style( 'kt-style', get_stylesheet_uri());
	wp_enqueue_style( 'kt-icons', get_template_directory_uri() . '/style/icons.css' );
	wp_enqueue_style( 'kt-custom', get_template_directory_uri() . '/style/kt-custom.php', array( 'kt-style' ), '11032014' );

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	// wp_enqueue_style( 'kt-ie', get_template_directory_uri() . '/css/ie.css', array( 'kt-style' ), '20121010' );
	// $wp_styles->add_data( 'kt-ie', 'conditional', 'lte IE 9' );
	/*
global $is_IE;
if( $is_IE){
wp_register_script( 'site-ie', get_template_directory_uri().'/js/site-ie.js' );
wp_enqueue_script( 'site-ie' );
}
 */
}
add_action( 'wp_enqueue_scripts', 'kt_scripts_styles' );

function kt_admin_script( $hook_suffix) {
	wp_enqueue_style( 'kwik-theme-admin', get_template_directory_uri() . '/style/admin.css' );
}
add_action( 'admin_enqueue_scripts', 'kt_admin_script', 10, 1);

/**
 * queries all tags of the site and current post, makes the top 9 the meta keywords
 * post or page tags appear first
 * @return [String] String of comma separated keywords for the current page
 */
function get_SEO_tags() {
	global $wp_query;
	$page_or_post_id = $wp_query->get( 'page_id' ) ? $wp_query->get( 'page_id' ) : $wp_query->queried_object_id;
	$args = array(
		'orderby' => 'count',
		'order' => 'DESC',
	);
	$all_tags_obj = get_tags( $args); // Get all tags by popularity
	$page_or_post_tags_obj = wp_get_post_tags( $page_or_post_id, $args); // get post or page tags
	$tags = array();
	foreach ( $page_or_post_tags_obj as $page_or_post_tag) {
		array_push( $tags, $page_or_post_tag->name);
	}
	foreach ( $all_tags_obj as $all_tag) {
		array_push( $tags, $all_tag->name);
	}
	$tags = implode(", ", array_slice(array_unique( $tags), 0, 9)); // implode a unque, sliced array of tags
	return rtrim( $tags, " "); // remove last space
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
			$out_excerpt = substr( $out_excerpt, 0, 150);
			$description .= apply_filters( 'the_excerpt_rss', $out_excerpt);
		endwhile;
	elseif (is_category() || is_tag()):
		if (is_category()):
			$description .= "Posts in to Category:" . ucfirst(single_cat_title("", false));
		elseif (is_tag()):
			$description .= "Posts with Tag:" . ucfirst(single_tag_title("", false));
		endif;
	else:
		$description .= get_bloginfo( 'description' );
	endif;

	return $description;

}

function kt_excerpt_length( $length) {

	if (is_tag() || is_archive() || is_home()) {
		return 50;
	}if (is_page( 'home' )) {
		return 18;
	} else {
		return 50;
	}
}
add_filter( 'excerpt_length', 'kt_excerpt_length' );

// Replaces the excerpt "more" text by a link
function new_excerpt_more( $more) {
	global $post;
	return '&hellip; <a class="moretag" href="' . get_permalink( $post->ID) . '">Read more</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since KwikTheme 1.0
 */
function kt_page_menu_args( $args) {
	if ( ! isset( $args['show_home'])) {
		$args['show_home'] = true;
	}

	return $args;
}
add_filter( 'wp_page_menu_args', 'kt_page_menu_args' );

if ( ! function_exists( 'kt_content_nav' )):
	/**
	 * Displays navigation to next/previous pages when applicable.
	 *
	 * @since KwikTheme 1.0
	 */
	function kt_content_nav( $html_id) {
		global $wp_query;
		$html_id = esc_attr( $html_id);
		if ( $wp_query->max_num_pages > 1): ?>
								<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
									<h3 class="assistive-text"><?php _e( 'Post navigation', 'kwik' ); ?></h3>
									<div class="nav-previous alignleft"><?php next_posts_link(__( '<span class="meta-nav">&larr;</span> Older posts', 'kwik' )); ?></div>
									<div class="nav-next alignright"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'kwik' )); ?></div>
								</nav><!-- #<?php echo $html_id; ?>.navigation -->
		<?php endif;
}
endif;
if ( ! function_exists( 'kt_comment' ) ):
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
	function kt_comment( $comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type):
		case 'pingback':
		case 'trackback':
			// Display trackbacks differently than normal comments. ?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
    		<p><?php _e( 'Pingback:', 'kwik' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__( '(Edit)', 'kwik' ), '<span class="edit-link">', '</span>' ); ?></p>
				<?php
		break;
		default:
			// Proceed with normal comments.
			global $post;
			?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
			<?php
    		echo get_avatar( $comment, 44 );
			printf( '<cite class="fn">%1$s %2$s</cite>',
				get_comment_author_link(),
				// If current post author is also comment author, make it known visually.
				( $comment->user_id === $post->post_author) ? '<span> ' . __( 'Post author', 'kwik' ) . '</span>' : ''
			);
			printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
				esc_url( get_comment_link( $comment->comment_ID) ),
				get_comment_time( 'c' ),
			/* translators: 1: date, 2: time */
				sprintf(__( '%1$s at %2$s', 'kwik' ), get_comment_date(), get_comment_time())
			);
			?>
				</header><!-- .comment-meta -->
				<?php if ( '0' == $comment->comment_approved): ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'kwik' ); ?></p>
				<?php endif; ?>
		<section class="comment-content comment">
		<?php comment_text(); ?>
										<?php edit_comment_link(__( 'Edit', 'kwik' ), '<p class="edit-link">', '</p>' ); ?>
		</section><!-- .comment-content -->
			<div class="reply">
		<?php comment_reply_link(array_merge( $args, array( 'reply_text' => __( 'Reply', 'kwik' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
		</div><!-- .reply -->
		</article><!-- #comment-## -->
		<?php
	break;
		endswitch; 	// end comment_type check
	}
	endif;

	if ( ! function_exists( 'kt_entry_meta' )):
	/**
	 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
	 * Create your own kt_entry_meta() to override in a child theme.
	 * @since KwikTheme 1.0
	 */
		function kt_entry_meta() {
			$utility_text = '';
			// Translators: used between list items, there is a space after the comma.
			$categories_list = get_the_category_list(__( ', ', 'kwik' ));
			// Translators: used between list items, there is a space after the comma.
			$tag_list = get_the_tag_list( '', __( ', ', 'kwik' ));

			// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
			if ( $tag_list) {
				$utility_text = __( '<div class="entry-tags"><strong>Tags:</strong> %2$s</div>', 'kwik' );
		}

		if ( $categories_list) {
			$utility_text .= __( '<div class="entry-categories"><strong>Category(s):</strong> %1$s</div>', 'kwik' );
		}

		if ( ! is_home()) {
			printf(
				$utility_text,
				$categories_list,
				$tag_list
			);

		}
	}

endif;

if ( ! function_exists( 'kt_author_date' )):
	/**
	 * Prints HTML with meta information for current post author and date.
	 *
	 * @since KwikTheme 1.0
	 */
	function kt_author_date() {

		if (function_exists( 'coauthors_posts_links' )) {
			$author = coauthors_posts_links(null, null, __( 'By: ', 'kwik' ), null, false);
		} else {
		$author = sprintf( '<span class="author vcard">By: <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url(get_author_posts_url(get_the_author_meta( 'ID' ))),
			esc_attr(sprintf(__( 'View all posts by %s', 'kwik' ), get_the_author())),
			get_the_author()
		);
	}

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url(get_permalink()),
		esc_attr(get_the_time()),
		esc_attr(get_the_date( 'c' )),
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
 *
 * @since KwikTheme 1.0
 *
 * @param  array Existing class values.
 * @return array Filtered class values.
 */
function kt_body_class( $classes) {

	if (kt_child_links()) {
		$classes[] = 'has_children';
	}

	if (is_front_page()) {
		$classes[] = 'front-page';
	}

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'templates/no-sidebar.php' )) {
		$classes[] = 'full-width';
	}

	if (is_page() || is_single()) {
		if (has_post_thumbnail()) {
			$classes[] = 'has-post-thumbnail';
		}
	}

	// Enable custom font class only if the font CSS is queued to load.
	if (wp_style_is( 'kt-fonts', 'queue' )) {
		$classes[] = 'custom-font-enabled';
	}

	if ( ! is_multi_author()) {
		$classes[] = 'single-author';
	}

	return $classes;
}
add_filter( 'body_class', 'kt_body_class' );

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since KwikTheme 1.0
 */
function kt_content_width() {
	if (is_page_template( 'templates/full-width.php' ) || is_attachment() || !is_active_sidebar( 'sidebar-1' )) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'kt_content_width' );

// Auto-set the featured image
function kt_autoset_featured() {
	global $post;
	$already_has_thumb = has_post_thumbnail( $post->ID);
	if ( ! $already_has_thumb) {
		$attached_image = get_children("post_parent=" . $post->ID . "&post_type=attachment&post_mime_type=image&numberposts=1");
		if ( $attached_image) {
			foreach ( $attached_image as $attachment_id => $attachment) {
				set_post_thumbnail( $post->ID, $attachment_id);
			}
		}
	}
}//end function
//add_action( 'the_post', 'kt_autoset_featured' );
add_action( 'publish_post', 'kt_autoset_featured' );
/*add_action( 'draft_to_publish', 'kt_autoset_featured' );
add_action( 'new_to_publish', 'kt_autoset_featured' );
add_action( 'pending_to_publish', 'kt_autoset_featured' );
add_action( 'future_to_publish', 'kt_autoset_featured' );*/

function kt_paginate( $is_child = true) {
	global $wp_query;
	$pagination = '';
	$int = 9999999;
	$pagination .= '<div class="pagination">';
	$pagination .= paginate_links(array(
		'base' => str_replace( $int, '%#%', get_pagenum_link( $int)),
		'format' => '?paged=%#%',
		'current' => max(1, get_query_var( 'paged' )),
		'total' => $wp_query->max_num_pages,
	));
	$pagination .= '</div>';
	echo $pagination;
}

//Add class to edit button
function custom_edit_links( $output) {

	$obj = get_post_type_object(get_post_type());
	$type = $obj->labels->singular_name;

	$output = str_replace( 'title="Edit Post"', 'title="Edit ' . $type . '"', $output);
	return $output;
}
add_filter( 'edit_post_link', 'custom_edit_links' );

function kt_child_links() {

	if ( is_page() || is_home() || is_single() ) {
		global $post;
		if ( is_home() ) {

			if (  'page' === get_option( 'show_on_front' ) ) {
				$blog_link = get_permalink( get_option( 'page_for_posts' ) );
			} else {
				$blog_link = bloginfo( 'url' );
			}

			$child_links = '';
			$cats = get_categories( 'hide_empty=1&taxonomy=category&orderby=term_order' );
			$child_links = '<li><a href="' . $blog_link . '">' . __( 'All', 'kwik' ) . '</a></li>';
			foreach ( $cats as $cat ) {
				$child_links .= '<li><a href="' . get_category_link( $cat->cat_ID ) . '" id="category-' . $cat->category_nicename . '-filter" title="Show only ' . $cat->name . ' posts" rel="category-' . $cat->category_nicename . '">' . $cat->name . '</a></li>';
			}
		} elseif ( $post->post_parent ) {
			$ancestors = get_post_ancestors( $post->ID );
			$root = count( $ancestors ) - 1;
			$parent = $ancestors[$root];
			if ( 0 == $root ) {
				$child_links = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0&depth=1' );
			}
		} else {
			$child_links = wp_list_pages( 'sort_column=menu_order&&exclude=532&title_li=&child_of=' . $post->ID . '&echo=0&depth=1' );
		}

		if ( isset( $child_links ) && ! empty( $child_links ) ) {
			$children = '<ul id="child_links" class="' . (is_home() ? 'article_filter' : '' ) . '">';
			$children .= $child_links;
			$children .= '</ul>';
			return $children;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'get_kt_posted_on' )):
	function get_kt_posted_on( $post_id) {
		$posted_on = sprintf(__( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>', 'kwik' ),
			esc_url(get_permalink( $post_id)),
			esc_attr(get_the_time()),
			esc_attr(get_the_date( 'c' )),
			esc_html(get_the_date())
		);
		return $posted_on;
	}
endif;

if ( ! function_exists( 'kt_posted_on' )):
	function kt_posted_on() {
		if (is_sticky() && is_home() && !is_paged()) {
			echo '<span class="featured-post">' . __( 'Sticky', 'twentyfourteen' ) . '</span>';
		}
		echo get_kt_posted_on(the_id());
	}
endif;

// page and section headers
function kt_content_header( $wp_query) {
	$inputs = new KwikInputs();
	$options = KwikThemeOptions::kt_get_options();
	if ( ! is_404()) {
		if (is_page() && !is_front_page()) {
			$page_id = $wp_query->queried_object->ID;
			$page_meta = KwikMeta::get_meta_array( $page_id, 'page_meta_fields' );

			if ( ! empty( $page_meta)) {
				$header_img_id = $page_meta['banner_img'];
				$header_img_text = $page_meta['banner_text'];
			}

		}// if(is_page())
		elseif (is_home()) {
			// $header_img_id = $options['headers']['value']['post']['img'];
			// $header_img_text = $options['headers']['value']['post']['text'];
		}
	} else {
		// $header_img_id = $options['headers']['value']['404']['img'];
		// $header_img_text = $options['headers']['value']['404']['text'];
	}

	if (isset( $header_img_id) && '' !== $header_img_id) {
		$output = '';

		$header_img = wp_get_attachment_image_src( $header_img_id, 'header_img' );
		$header_img_src = $header_img['0'];

		$output .= '<style type="text/css">';
		$output .= '#page_header{
		                  background-image:url("' . $header_img_src . '");
		                }';
		$output .= "#page_header .banner_blur{background-image:url({$header_img_src})}";
		$output .= '</style>';

		$header_img_text = $header_img_text ? $inputs->markup( 'h2', $header_img_text) : '';
		$inner = $inputs->markup( 'div', $header_img_text, array( 'class' => 'inner' ));
		$header_inner = $inputs->markup( 'div', $inner, array( 'class' => 'banner_inner' ));
		$header_blur = $inputs->markup( 'div', null, array( 'class' => 'banner_blur' ));
		$output .= $inputs->markup( 'div', $header_inner . $header_blur, array( 'id' => 'page_header' ));

		return $output;

	}// if( $header_img_id)
}

function site_logo() {
	$options = KwikThemeOptions::kt_get_options();
	$inputs = new KwikInputs();
	if ( $options['logo']) {
		$logo_src = wp_get_attachment_image_src( $options['logo'], 'full' );
		$logo = $inputs->markup( 'img', null, array( 'class' => 'site_logo', 'src' => $logo_src[0]) );
		return $inputs->markup( 'a', $logo, array( 'href' => esc_url(home_url( '/' )), 'rel' => 'home' ));
	}
}

function site_favicon() {
	$options = KwikThemeOptions::kt_get_options();
	$inputs = new KwikInputs();
	if ( $options['favicon']) {
		$favicon_src = wp_get_attachment_image_src( $options['favicon'], 'full' );
		$favicon_src = $favicon_src[0];
	} else {
		$favicon_src = bloginfo( 'template_directory' ) . '/images/favicon.ico';
	}
	return $inputs->markup( 'link', null, array( 'href' => $favicon_src, 'rel' => 'shortcut icon', 'type' => 'image/x-icon' ));
}


add_action('wp_head','google_analytics_tracking_code');

function google_analytics_tracking_code() {
	if ( false === ( $analytics_tracking = get_transient( 'analytics_tracking' ) ) ) {
	ob_start();
	$options = KwikThemeOptions::kt_get_options();
	if ( $options['analytics'] === '' ) {
		return;
	}
	?>
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '<?php echo esc_attr( $options['analytics'] ); ?>']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
	<?php
		$analytics_tracking = ob_get_clean();
		set_transient( 'analytics_tracking', $analytics_tracking, HOUR_IN_SECONDS );
	}
	echo $analytics_tracking;
}


function kwik_title(){
	$the_title = get_the_title();
	if ( is_page() ) {
		$the_id = get_the_id();
		$page_meta = KwikMeta::get_meta_array( $the_id, 'page_meta_fields' );
		if ( isset($page_meta['banner_text']) && $page_meta['banner_text'] === $the_title ) {
			$the_title = '';
		}
	}
	echo $the_title;
}
