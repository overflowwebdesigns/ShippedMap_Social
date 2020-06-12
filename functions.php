<?php

// This file holds the functions used for the main plugin.


function customer_images_post_type(){

$args = array(
    'public'    => true,
    'label' => 'Customer Photos',
    'menu_icon' => 'dashicons-format-gallery',
);

register_post_type( 'Customer Photos', $args );
}


function create_plugin_settings_page()
{

// Add the menu item and page
$page_title = 'Shipped Map Social';
$menu_title = 'ShippedMap Social';
$capability = 'manage_options';
$slug = 'shipped_map_social';
$callback = 'plugin_settings_page_content';
$icon = 'dashicons-admin-plugins';
$position = 100;   
add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
}

function plugin_settings_page_content(){
global $content;
global $wpdb;

?><center><h1>Shipped Map Social Settings</h1></center><?php

?>
<form method="post" action="options.php"> <?php
settings_fields( "header_section" );
do_settings_sections( "shipped_map_social" );
submit_button();
?></form><?php

}


function jt_wrf_display_options(){

add_settings_section( 'header_section', 'ShippedMap Social Settings', 'jt_header_func', 'shipped_map_social' );
add_settings_field( 'jt_color_states', 'What color should not shipped to states be?', 'jt_color_states', 'shipped_map_social', 'header_section' );
add_settings_field( 'jt_shipped_state_color', 'What color should states you have shipped to be?', 'jt_shipped_state_color', 'shipped_map_social', 'header_section' );
register_setting( 'header_section', 'jt_color_states' );
register_setting( 'header_section', 'jt_shipped_state_color' );

}

function jt_header_func(){echo "Availabke options for this plugin.";}

function jt_color_states(){

?>
<input type="color" name="jt_color_states" id="jt_color_states" value="<?php echo get_option( 'jt_color_states' ); ?>" />
<?php
}

function jt_shipped_state_color(){

?>
<input type="color" name="jt_shipped_state_color" id="jt_shipped_state_color" value="<?php echo get_option( 'jt_shipped_state_color' ); ?>" />
<?php
}




function owd_load_plugin_css() {
$plugin_url = plugin_dir_url( __FILE__ );

wp_enqueue_style( 'style1', $plugin_url . 'css/style.css' );
}

function jt_state_images(){
global $wpdb;
$state = $_POST['state'];

$query = $wpdb->prepare('SELECT post_id FROM wp_postmeta WHERE meta_key = "state" AND meta_value = "' . $state . '";');
$results = $wpdb->get_results($query);

$images = [];
$titles = [];
$return = array();
foreach ($results as $ID ) {
    $post_id = $ID->post_id;
    $post = get_field("images", $post_id);
    array_push($images, $post);

    //wp_send_json($post);
    //echo "<img src=" . $post . ">";
    //$post_encode = wp_json_encode($post);
    //echo '<div id="swiper" class="swiper-slide"><img src=' . wp_send_json($post) . '></div>';
    
}

header("Content-Type: application/json");

echo wp_json_encode($images);

wp_die();
} 
