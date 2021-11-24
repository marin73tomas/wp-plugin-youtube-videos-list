<?php
if (!class_exists('CVL_Public')) {
     class CVL_Public extends WP_REST_Controller
     {

          public function __construct()
          {
               add_action('enqueue_scripts', array($this, 'register_styles'));
               add_filter('single_template', array($this, 'video_template'), 99);
          }
          function register_styles()
          {
               wp_register_style('css-clv-front', CVL_PLUGIN_DIR . '/admin/css/frontend.css');
               wp_enqueue_style('css-clv-front');
          }

          function video_template($template)
          {
               global $post;
               /* Checks for single template by post type */
               if (
                    $post->post_type == 'youtube_videos'
               ) {
                    $template =  CVL_PLUGIN_BASE . "/templates/video-template.php";
               }
               return $template;
          }
     }
     new CVL_Public();
}
