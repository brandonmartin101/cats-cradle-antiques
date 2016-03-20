<?php
/*
  Template Name: About Page
 */

get_header(); ?>

<div class="container">
  <?php  // TO SHOW THE PAGE CONTENTS
    while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
      <div class="about-content">
        <?php the_content(); ?> <!-- Page Content -->
      </div><!-- .entry-content-page -->

<?php
  endwhile; //resetting the page loop
  wp_reset_query(); //resetting the page query
?>

</div><!-- end container -->

<?php
get_footer(); ?>
