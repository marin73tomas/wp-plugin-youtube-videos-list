<?php
if (!class_exists('CVL_Admin')) {
     class CVL_Admin extends WP_REST_Controller
     {

          public function __construct()
          {
               add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
               add_action('admin_enqueue_scripts', array($this, 'register_styles'));
               add_action('rest_api_init', array($this, 'rest_end_points'));
               add_action('admin_menu', array($this, 'bulk_import_page'));
          }
          /*
          page which contains the form that submits data to the "ajax action" function through the admin.js script
          */

          function bulk_import_page()
          {
               $page_title = "Bulk Import";

               add_submenu_page('edit.php?post_type=youtube_videos', $page_title, $page_title, 'edit_posts', basename(__FILE__), array($this, 'bulk_import_page_callback'));
          }
          function bulk_import_page_callback()
          {
?>
               <div class="cvl-container">
                    <div class="row">
                         <form action="" id="cvl-bulk-form">
                              <div class="lds-dual-ring"></div>
                              <input type="text" id="yt-id" name="cvl-yt-id">
                              <input class="button button-primary" type="button" value="Submit" id="cvl-yt-submit" name=" cvl-yt-submit">
                         </form>
                    </div>
               </div>
<?php
          }
          function rest_end_points()
          {
               register_rest_route('cvl/v1', '/addvideos', array(
                    'methods' => "POST",
                    'callback' => array($this, 'ajax_action'),
                    // 'permission_callback' => function () {
                    //      return current_user_can('edit_posts');
                    // }
               ));
          }
          function ajax_action(WP_REST_Request $request)
          {
               $body = json_decode($request->get_body());

               $yt_id = sanitize_text_field($body->yt_id);
               $key = "AIzaSyAbm3Xn3KIYTEbdHVSx2n3Md8rN02HYuMc"; //yt api key
               if (!empty($yt_id)) {

                    $yt_request = wp_remote_get("https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=$yt_id&maxResults=50&key=$key");
                    $response_code = wp_remote_retrieve_response_code($yt_request);
                    $data = wp_remote_retrieve_body($yt_request);
                    if (!empty($data) && post_type_exists('youtube_videos')) {
                         $items = $data['items'] ? $data['items'] : array();
                         foreach ($items as $item) {
                              if ($item['id']['kind'] == 'youtube#video') {
                                   $id = wp_insert_post(array('post_title' => 'random', 'post_type' => 'youtube_videos'));
                                   update_post_meta($id, 'cvl_video_id', sanitize_text_field($item['id']['videoId']));
                                   return "succes";
                              }
                         }
                    } else {
                    }
                    //if yt request failed
                    $response_message = wp_remote_retrieve_response_message($yt_request);

                    return new WP_Error($response_code, $response_message);
               }
               return new WP_Error(404, "id doesn't exist");
          }
          function register_scripts()
          {
               wp_register_script('js-clv-admin', CVL_PLUGIN_DIR . '/admin/js/admin.js');
               wp_enqueue_script('js-clv-admin');
               wp_localize_script('js-clv-admin', 'ajax_var', array(
                    'root' => esc_url_raw(rest_url()),
                    'cvl_nonce' => wp_create_nonce('cvl_once')
               ));
          }
          function register_styles()
          {
               wp_register_style('css-clv-admin', CVL_PLUGIN_DIR . '/admin/css/admin.css');
               wp_enqueue_style('css-clv-admin');
          }
     }

     new CVL_Admin();
}
