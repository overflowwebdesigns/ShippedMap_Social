<?php

/*
    Plugin Name: ShippedMap Social
    description: This is a plugin that will create an interactive JS map to show where you have shipped products.
    Author: Justin Tharpe
    Version: 1.0.0
    Tested Up To: 5.4.1
    WC tested up to: 4.1.0
*/


if (!defined('ABSPATH')) die('No direct access allowed');

require_once('functions.php');
require_once('map.php');


add_action( 'wp_enqueue_scripts', 'owd_load_plugin_css' );
add_shortcode('owd-map', 'owd_map_shortcode');
add_action('admin_menu', 'create_plugin_settings_page');
add_action( 'admin_init', 'jt_wrf_display_options' );
add_action( 'wp_ajax_jt_state_images', 'jt_state_images' );
add_action( 'wp_ajax_nopriv_jt_state_images', 'jt_state_images' );
add_action( 'init', 'customer_images_post_type' );

?>