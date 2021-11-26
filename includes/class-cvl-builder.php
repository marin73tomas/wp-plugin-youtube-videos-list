<?php


if (!class_exists('CVL_Builder')) {

     class CVL_Builder
     {
          public function __construct()
          {
               add_action('init', array($this, 'create_posttype'));
          }

          function create_posttype()
          {
               $labels = array(
                    'name'                => _x('Easy Videos', 'Post Type General Name', 'twentytwenty'),
                    'singular_name'       => _x('Easy Video', 'Post Type Singular Name', 'twentytwenty'),
                    'menu_name'           => __('Easy Videos', 'twentytwenty'),
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
                    'label'               => __('Easy Videos', 'twentytwenty'),
                    'description'         => __('Youtube video list', 'twentytwenty'),
                    'labels'              => $labels,
                    'supports'            => array('title', 'content','editor', 'author', 'custom-fields',),
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

               register_taxonomy(
                    'cvt_categories',
                    array('work'),
                    array(
                         'hierarchical' => true,
                         'label' => 'Categories',
                         'singular_label' => 'Category',
                         'rewrite' => array('slug' => 'cvt_categories', 'with_front' => false)
                    )
               );
               register_taxonomy_for_object_type('cvt_categories', 'youtube_videos'); 

               $set = get_option('post_type_rules_flased_cvt');
               if ($set !== true) {
                    flush_rewrite_rules(false);
                    update_option('post_type_rules_flased_cvt', true);
               }
          }
     }
     new CVL_Builder();
}
