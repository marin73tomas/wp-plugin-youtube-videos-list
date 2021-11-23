<?php
if (!class_exists('CVL_Admin')) {
     class CVL_Admin extends WP_REST_Controller
     {

          public function __construct()
          {
               add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
               add_action('admin_enqueue_scripts', array($this, 'register_styles'));
               add_action('rest_api_init', array($this, 'rest_end_points'));
          }
          private function rest_end_points()
          {
               register_rest_route('cvl/v1', '/add_videos', array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'ajax_action'),
                    'permission_callback' => function () {
                         return current_user_can('administrator');
                    }
               ));
          }
          private function ajax_action(WP_REST_Request $request)
          {
               $input_data = sanitize_text_field($request['input_data']);

               if (!empty($input_data)) {

                    // Create the response object
                    $response = new WP_REST_Response($data);
                    // Add a custom status code
                    $response->set_status(201);
               }
          }
          private function register_scripts()
          {
               wp_register_style('clv-admin', CVL_PLUGIN_DIR . 'admin/js/admin.js');
               wp_enqueue_style('clv-admin');
          }
          private function register_styles()
          {
               wp_register_style('clv-admin', CVL_PLUGIN_DIR . 'admin/css/admin.css');
               wp_enqueue_style('clv-admin');
          }
     }
     new CVL_Admin();
}
