<?php
/*
  Template Name: What's New Page
 */

// custom fields
$days_back = get_post_meta('7', 'Days Back', true);
$old_date = date('Y-m-d', strtotime('-'.$days_back.' days'));
$number_posts = get_post_meta('7', "Number of Posts", true);

get_header(); ?>

<div class="gallery-content">

  <?php

  global $post;
  $recent_args = array(
    'numberposts'		=>	$number_posts,
    'date_query'    =>  array(
      'after'   =>  $old_date
    ),
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
