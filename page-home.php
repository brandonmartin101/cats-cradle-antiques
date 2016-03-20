<?php
/*
  Template Name: Home Page
 */

// get background image
$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<!-- Bootstrap & jQuery -->
<link href="<?php bloginfo('stylesheet_directory'); ?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php bloginfo('stylesheet_directory'); ?>/assets/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="<?php bloginfo('stylesheet_directory'); ?>/style.css" rel="stylesheet">

<!-- Fonts -->
<link href='https://fonts.googleapis.com/css?family=Libre+Baskerville' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<?php wp_head(); ?>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body <?php body_class(); ?>>
<div class="background-wrapper"></div>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'cats-cradle' ); ?></a>
  <div class="container-fluid" id="home-header-block">
  	<a href="/"><img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/cats_cradle_logo_5.jpg" alt="Cat's Cradle Antiques" height="145"></a>
  </div>
  <div class="container-fluid">
  	<div class="col-xs-12 center-block">

  			<?php
  				wp_nav_menu(array(
  					'theme_location' => 'primary',
  					'container' => 'div',
  					'container_class' => 'navbar-items text-center',
  					'menu_class' => 'nav navbar-nav navbar-center'
  				));
  			?>

  	</div>
  </div>

<div class="background-block" style="background-image:url('<?php echo $src[0]; ?>');"></div>

<script type="text/javascript">
	// jQuery to match background-block to the device height
	document.addEventListener("DOMContentLoaded", function() {
		var height = (window.innerHeight > 0) ? window.innerHeight : screen.height;
		$(".background-block").css("height",height);
    $("#home-header-block").css("margin-top",height*0.35);
	});
</script>

<?php
get_footer(); ?>
