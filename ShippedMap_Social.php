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

    foreach ($results as $ID ) {
        $post_id = $ID->post_id;
        $post = get_field("images", $post_id);

        //wp_send_json($post);
        //echo "<img src=" . $post . ">";
        //$post_encode = wp_json_encode($post);
        $post_encode = json_encode($post);
        print_r($post_encode);
        //echo '<div id="swiper" class="swiper-slide"><img src=' . wp_send_json($post) . '></div>';

    }

    wp_die();
} 

function owd_map_shortcode($atts) {

$orders = wc_get_orders(array()); //Gets all orders.

   ?>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"> </script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/us-map/1.0.1/jquery.usmap.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.min.css">
    <script src="https://unpkg.com/swiper/js/swiper.js"></script>
    <script src="https://unpkg.com/swiper/js/swiper.min.js"></script>
    
    <center>
    <h2 class="elementor-heading-title elementor-size-default">Who is wearing Loud Proud American?</h2>
    <h3 class="elementor-heading-title elementor-size-default">Help us cover the USA!</h3>
    <div id="map" style="width: auto; height: 800px;">
    <script>

    
    $('#map').usmap({ //Creates interactive JS map
        showLabels: true,
        stateStyles: {fill: <?php echo "'" . get_option('jt_color_states') . "'"; ?>}, //defines the default color for a state. In our case the color of a state we have not shipped products to.
        stateSpecificStyles: {
            <?php
            foreach($orders as $order){
                $states = get_post_meta($order->ID, '_billing_state', false); //Takes the order ID's and pulls the state the order is from.
                $state = array_shift($states);
                echo "'" . $state . "': {fill: " . "'" . get_option('jt_shipped_state_color') . "'" . "},"; //Defines the color to a state we have shipped products to.
            } ?>
        },
        click: function(event, data) {
            var state = data.name;
                //console.log('You clicked '+state); //This is going to be a pop up lightbox style image slider that opens to show images of people from those states wearing the products.
                $.ajax({
                type:'POST',
                url: '../../wp-admin/admin-ajax.php',
                data: {
                    action: 'jt_state_images',
                    state: state
                },
                dataType: "json",
                success:function (output) {
                    $('#openModal h2').html(state);
                    var myArr = JSON.parse(output);
                    //alert(myArr[0]);
                    alert(myArr);
                    $('#swiper').html(myArr);
                    $('#openModal').show();
                },
                error:function (error) {
                    
                }

            });
        }
    });
    
    </script>
    </div>
    <div id="openModal" class="modalDialog" style="display: none;">
    <div class="modal-content">
        <a href="#close" title="Close" class="close" onclick="$('#openModal').hide()">X</a>
        <h2>Modal Box</h2>
        <p>Hello world</p>

                <!-- Slider main container -->
        <div class="swiper-container">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                <div id="swiper" class="swiper-slide"></div>
            </div>
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>

            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <!-- If we need scrollbar -->
            <div class="swiper-scrollbar"></div>
        </div>
    </div>
    </div>
    </center>

    <?php

}


add_action( 'wp_enqueue_scripts', 'owd_load_plugin_css' );
add_shortcode('owd-map', 'owd_map_shortcode');
add_action('admin_menu', 'create_plugin_settings_page');
add_action( 'admin_init', 'jt_wrf_display_options' );
add_action( 'wp_ajax_jt_state_images', 'jt_state_images' );
add_action( 'wp_ajax_nopriv_jt_state_images', 'jt_state_images' );
add_action( 'init', 'customer_images_post_type' );

?>