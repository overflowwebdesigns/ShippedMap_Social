<?php

/*
    Plugin Name: ShippedMap Social
    description: This is a plugin that will create an interactive JS map to show where you have shipped products.
    Author: Justin Tharpe
    Version: 1.0.0
    Tested Up To: 5.4.1
    WC tested up to: 4.1.0
*/


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
        stateStyles: {fill: '#333333'} //defines the default color for a state. In our case the color of a state we have not shipped products to.
        stateSpecificStyles: {
        'MD': {fill: 'yellow'}, //Defines the color to a state we have shipped products to.
        foreach($orders as $order){
            $states = get_post_meta($order->ID, '_billing_state', false); //Takes the order ID's and pulls the state the order is from.
            $state = array_shift($states);
            echo "'" . $state . "': {fill: 'yellow'},";  //returns each state we have sent products to.
        }
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

















