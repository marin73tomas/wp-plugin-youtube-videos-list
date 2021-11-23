<?php


if (!class_exists('CVL_Builder')) {

     class CVL_Builder
     {
          public function __construct()
          {
               add_action('admin_init', array($this, 'create_post_type'));

             
          }
        
          private function create_posttype()
          {
               if (!get_option('cvl_created')) {
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
                    update_option('cvl_created', true);
               }
          }
     }
     new CVL_Builder();
}
