<?php

/**
 * Plugin Name: Custom Video List
 * Plugin URI: https://github.com/marin73tomas/wp-plugin-youtube-videos-list
 * Description: A custom plugin that display a list of videos in the frontend and store them in custom post types.
 * Version: 0.1
 * Author: Tomas Marin
 */


if (!defined('ABSPATH')) {
     exit; // Exit if accessed directly.
}

define('CVL_PLUGIN_DIR', plugin_dir_url(__FILE__));

include_once('includes/class-cvl-builder.php');
include_once('admin/cvl-admin.php');
include_once('public/cvl-public.php');
