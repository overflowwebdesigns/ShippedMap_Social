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

    add_settings_section( 'header_section', 'Product Selection Settings', 'jt_header_func', 'shipped_map_social' );
    add_settings_field( 'jt_num_to_keep', 'How Many Prodcuts to Keep', 'jt_num_to_keep', 'shipped_map_social', 'header_section' );
    add_settings_field( 'jt_when_to_change', 'How Often Should Featured Products Change (In Seconds)', 'jt_when_to_change', 'shipped_map_social', 'header_section' );
    register_setting( 'header_section', 'jt_num_to_keep' );
    register_setting( 'header_section', 'jt_when_to_change' );
    
}

function jt_header_func(){echo "This will configured various options associated with the plugin.";}

function jt_num_to_keep(){

    ?>
    <input type="number" name="jt_num_to_keep" id="jt_num_to_keep" value="<?php echo get_option( 'jt_num_to_keep' ); ?>" />
    <?php
}

function jt_when_to_change(){

    ?>
    <input type="number" name="jt_when_to_change" id="jt_when_to_change" value="<?php echo get_option( 'jt_when_to_change' ); ?>" />
    <?php
}

function reconfigure_options(){
    RunFeatured();
}




function owd_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'style1', $plugin_url . 'css/map_style.css' );
}


function owd_map_shortcode($atts) {

$orders = wc_get_orders(array()); //Gets all orders.


   ?>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"> </script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/us-map/1.0.1/jquery.usmap.js"></script>
    
    <center>
    <h2 class="elementor-heading-title elementor-size-default">Who is wearing Loud Proud American?</h2>
    <h3 class="elementor-heading-title elementor-size-default">Help us cover the USA!</h3>
    <h3> Test <?php echo $results; ?></h3>
    <div id="map" style="width: 800px; height: 800px;">
    <script>
    $(document).ready(function() {
    
    $('#map').usmap({ //Creates interactive JS map

        showLabels: true,
        stateStyles: {fill: '#333333'}, //defines the default color for a state. In our case the color of a state we have not shipped products to.
        stateSpecificStyles: {
            <?php
            foreach($orders as $order){
                $states = get_post_meta($order->ID, '_billing_state', false); //Takes the order ID's and pulls the state the order is from.
                $state = array_shift($states);
                echo "'" . $state . "': {fill: 'yellow'},";  //Defines the color to a state we have shipped products to.
            } ?>
        }    
    });
    });
    </script>
    </div>
    </center>

    <?php

}

add_action( 'wp_enqueue_scripts', 'owd_load_plugin_css' );
add_shortcode('owd-map', 'owd_map_shortcode');

?>