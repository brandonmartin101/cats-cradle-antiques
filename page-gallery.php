<?php
/*
  Template Name: Gallery Page
 */

// Custom fields
$number_posts = get_post_meta('7', "Number of Posts", true);

get_header(); ?>

<div class="gallery-content">

  <?php

  wp_add_fb_photos();
  global $post;
  $recent_args = array(
    'numberposts'		=>	$number_posts,
    'orderby'				=>	'post_date',
    'order'					=>	'DESC',
    'post_type'			=>	'post'
  );
  $most_recent_posts = get_posts($recent_args);
  foreach ($most_recent_posts as $post) : setup_postdata($post); ?>

    <div><?php the_content(); ?></div>

  <?php endforeach; ?>

</div>

<?php
get_footer();
