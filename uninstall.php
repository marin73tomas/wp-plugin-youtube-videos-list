<?php

// If uninstall is not called from WordPress, or the current user doesn't have the capability to delete plugins
if (!defined('WP_UNINSTALL_PLUGIN') || !current_user_can_for_blog($GLOBALS['blog_id'], 'delete_plugins')) {
     exit();
}

function cvl_delete_plugin()
{

     $posts = get_posts(array(
          'numberposts' => -1,
          'post_type' => 'youtube_videos',
          'post_status' => 'any'
     ));

     foreach ($posts as $post) {
          delete_post_meta($post->ID, 'youtube_video_id');
          wp_delete_post($post->ID, true);
     }
}

cvl_delete_plugin();
