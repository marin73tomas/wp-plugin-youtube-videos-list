<?php

/**
 * The template for displaying all single youtube video posts.
 *
 ***/

if (!defined('ABSPATH')) {
     exit; // Exit if accessed directly.
}

$cvl_video_link = get_post_meta(get_the_ID(), 'youtube_video_id', true);

get_header(); ?>

<div class="cvl-video-container">
     <?php global $post;
     $page_id = $post->ID; ?>
     <?php if (!empty($cvl_video_link)) { ?>
          <iframe width="1180" height="664" src="https://www.youtube.com/embed/<?php echo $cvl_video_link; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>

          </iframe>
     <?php } ?>
</div>


<?php get_footer();  ?>