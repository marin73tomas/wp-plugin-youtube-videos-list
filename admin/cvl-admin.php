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
                              <label for="yt-id">Enter User Id or Channel Id:</label>
                              <input type="text" id="yt-id" name="cvl-yt-id">
                              <button class="button button-primary" type="button" id="cvl-yt-submit" name=" cvl-yt-submit">Submit</button>
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
                    //      return current_user_can('administrator');
                    // }
               ));
          }
          function ajax_action(WP_REST_Request $request)
          {

               $body = json_decode($request->get_body());

               $yt_id = sanitize_text_field($body->yt_id);
               $key = "AIzaSyAbm3Xn3KIYTEbdHVSx2n3Md8rN02HYuMc"; //yt api key
               $count = 0;
               $duplicates = 0;
               $page_token = '';
               while (!empty($yt_id)) {
                    $yt_request = wp_remote_get("https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=$yt_id&maxResults=50&key=$key&type=video&videoEmbeddable=true$page_token");
                    $response_code = wp_remote_retrieve_response_code($yt_request);
                    $data = json_decode(wp_remote_retrieve_body($yt_request));

                    if (!empty($data) && post_type_exists('youtube_videos')) {
                         $items = $data->items ? $data->items : array();

                         foreach ($items as $item) {

                              $video_id = $item->id->videoId;
                              $posts = get_posts(array(
                                   'post_type' => 'youtube_videos',
                                   'meta_key' => 'youtube_video_id',
                                   'meta_value' => $video_id,
                              ));

                              if (empty($posts)) { //video id doesn't exist in the database
                                   $title = $item->snippet->title;
                                   $description = $item->snippet->description;
                                   $id = wp_insert_post(array(
                                        'post_title' => $title,
                                        'post_content' => $description,
                                        'post_type' => 'youtube_videos',
                                        'post_status' => 'publish',

                                   ));
                                   update_post_meta($id, 'youtube_video_id', sanitize_text_field($video_id));

                                   //get video categories
                                   $video_id = $item->id->videoId;
                                   $categories_request = wp_remote_get("https: //www.googleapis.com/youtube/v3/videoCategories?part=snippet&key=$key&id=$video_id");
                                   $cat_data = json_decode(wp_remote_retrieve_body($categories_request));

                                   if (!empty($cat_data)) {
                                        $cat_items = $cat_data->items ? $cat_data->items : array();
                                        foreach ($cat_items as $cat_item) {
                                             $cat_title = $item->snippet->title;
                                             if (!has_term($cat_title, 'youtube_videos'))
                                                  wp_insert_term($cat_title, 'cvt_categories');
                                        }
                                   }

                                   $count++;
                              } else $duplicates++;
                         }
                    }



                    if (property_exists($data, "nextPageToken")) {
                         $page_token = "&pageToken=$data->nextPageToken";
                         continue;
                    }
                    if ($count == 0 && $duplicates == 0) {
                         $response_message = wp_remote_retrieve_response_message($yt_request);

                         return new WP_Error($response_code, $response_message);
                    }
                    return "$count videos created, $duplicates duplicates found";
               }


               return new WP_Error(404, "user or channel id doesn't exist");
          }
          function register_scripts()
          {
               wp_register_script('js-clv-sweet', CVL_PLUGIN_DIR . '/admin/js/sweetalert2.js');
               wp_register_script('js-clv-admin', CVL_PLUGIN_DIR . '/admin/js/admin.js');
               wp_enqueue_script('js-clv-sweet');
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
