<?php


if (!class_exists('CVL_Builder')) {

     class CVL_Builder
     {
          public function __construct()
          {
               add_action('init', array($this, 'create_posttype'));
               add_action('add_meta_boxes', array($this, 'create_custom_fields'));
               add_action('save_post', array($this, 'save_meta_box'));
          }

          function create_posttype()
          {
               $labels = array(
                    'name'                => _x('Youtube Videos', 'Post Type General Name', 'twentytwenty'),
                    'singular_name'       => _x('Youtube Video', 'Post Type Singular Name', 'twentytwenty'),
                    'menu_name'           => __('Youtube Videos', 'twentytwenty'),
                    'parent_item_colon'   => __('Parent Youtube Video', 'twentytwenty'),
                    'all_items'           => __('All Youtube Videos', 'twentytwenty'),
                    'view_item'           => __('View Youtube Video', 'twentytwenty'),
                    'add_new_item'        => __('Add New Youtube Video', 'twentytwenty'),
                    'add_new'             => __('Add New', 'twentytwenty'),
                    'edit_item'           => __('Edit Youtube Video', 'twentytwenty'),
                    'update_item'         => __('Update Youtube Video', 'twentytwenty'),
                    'search_items'        => __('Search Youtube Video', 'twentytwenty'),
                    'not_found'           => __('Not Found', 'twentytwenty'),
                    'not_found_in_trash'  => __('Not found in Trash', 'twentytwenty'),
               );
               $args = array(
                    'label'               => __('Youtube videos', 'twentytwenty'),
                    'description'         => __('youtube Youtube video list', 'twentytwenty'),
                    'labels'              => $labels,
                    'supports'            => array('title', 'editor', 'author', 'custom-fields',),
                    'hierarchical'        => true,
                    'public'              => true,
                    'show_ui'             => true,
                    'show_in_menu'        => true,
                    'show_in_nav_menus'   => true,
                    'show_in_admin_bar'   => true,
                    'menu_position'       => 5,
                    'can_export'          => true,
                    'has_archive'         => true,
                    'exclude_from_search' => false,
                    'publicly_queryable'  => true,
                    'capability_type'     => 'post',
                    'show_in_rest' => true,

               );
               // Registering your Youtube Videos CPT
               register_post_type('youtube_videos', $args);
          }
          function create_custom_fields()
          {
               add_meta_box('video_link', "Youtube Video Link", array($this, "display_custom_fields_fallback"), "post");
          }
          function display_custom_fields_fallback($post_id)
          {
?>
               <div class="cvl_box">
                    <p class="meta-options cvl_field">
                         <label for="cvl_video_id">Video Link</label>
                         <input id="cvl_video_id" type="text" name="cvl_video_id" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'cvl_video_id', true)); ?>">
                    </p>
               </div>

<?php
          }

          function save_meta_box($post_id)
          {
               if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
               if ($parent_id = wp_is_post_revision($post_id)) {
                    $post_id = $parent_id;
               }
               $fields = [
                    'cvl_video_id',
               ];
               foreach ($fields as $field) {
                    if (array_key_exists($field, $_POST)) {
                         update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
                    }
               }
          }
     }
     new CVL_Builder();
}
