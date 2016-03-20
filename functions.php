<?php
/**
 * Cat\'s Cradle functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Cat\'s_Cradle
 */

if ( ! function_exists( 'cats_cradle_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function cats_cradle_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Cat\'s Cradle, use a find and replace
	 * to change 'cats-cradle' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'cats-cradle', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'cats-cradle' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'cats_cradle_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'cats_cradle_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cats_cradle_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'cats_cradle_content_width', 640 );
}
add_action( 'after_setup_theme', 'cats_cradle_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cats_cradle_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'cats-cradle' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'cats_cradle_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function cats_cradle_scripts() {
	wp_enqueue_style( 'cats-cradle-style', get_stylesheet_uri() );

	wp_enqueue_script( 'cats-cradle-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'cats-cradle-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cats_cradle_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

// cron hook
add_action('wp_add_fb_photos', 'wp_add_fb_photos');

function wp_add_fb_photos() {
	// function run in cron job to pull FB photos from Cat's Cradle Antiques
	$app_id = "191471607884311";
	$app_secret = "8b68269bdaf5edb003bbe8aa14558145";
	$fb_access_token = "?access_token=".$app_id."|".$app_secret;
	$fb_base_link = "https://graph.facebook.com/v2.5/";
	$fb_sub_link = "martintestpage/photos";
	$fb_url = $fb_base_link.$fb_sub_link.$fb_access_token."&type=uploaded";

	// get list of photos on page
	$curl = curl_init($fb_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	curl_close($curl);
	$json = json_decode($curl_response,true);

	// grab each id for separate API calls
	// add a time condition here
	// only add id's with timestamps newer than most recent post
	$most_recent_post_date = recent_post_date();
	if ($most_recent_post_date === "No posts found") {
		$most_recent_post_date = "2000-01-01";
	}
	//echo $most_recent_post_date."<br>".strtotime($most_recent_post_date)."<br>";

	$img_array = array();
	foreach ($json["data"] as &$value) {
		$post_date = $value["created_time"];
		//echo $post_date."<br>".strtotime($post_date)."<br>";
		if (strtotime($post_date) > strtotime($most_recent_post_date)) {
			//echo "FB image is new<br>";
			array_push($img_array, $value["id"]);
		}
	}
	array_reverse($img_array);

	// loop through photo ID's
	// pull each ID's image, and create a blog post for it
	foreach ($img_array as &$image_id) {
		$fb_url = $fb_base_link.$image_id.$fb_access_token."&fields=name,link,images";
		$curl = curl_init($fb_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		curl_close($curl);
		$json = json_decode($curl_response, true);
		create_post ($json["name"], $json["link"], $json["images"][0]["source"]);
	}
}

function recent_post_date($no_posts = 1, $excerpts = true) {
  global $wpdb;
  $request = "SELECT ID, post_title, post_excerpt, post_date FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='post' ORDER BY post_date DESC LIMIT $no_posts";
  $posts = $wpdb->get_results($request);
  if($posts) {
    foreach ($posts as $posts) {
			$post_date = $posts->post_date;
      $output .= $post_date;
    }
  } else {
    $output .= 'No posts found';
  }
  return $output;
}

function create_post($name, $link, $image_source) {
	$post_content = "
		<div class='container'>
			<div class='row'>
				<div class='col-md-6 col-md-offset-3'>
					<div class='dot-left'></div><div class='dot-right'></div>
					<div class='image-block col-md-6'>
						<a href='".$link."' target='_blank'><img src='".$image_source."'></a>
					</div>
				  <div class='image-desc col-md-6'>".$name."</div>
				</div>
			</div>
		</div>";
	$post_data = array(
		'post_title'		=> 'Auto Post',
		'post_content'	=> $post_content,
		'post_status'		=> 'publish',
		'post_type'			=> 'post',
		'post_author'		=> 'Cron Job',
		'post_category'	=> 'test',
		'page_template'	=> NULL
	);
	wp_insert_post($post_data, $error_obj);
}
