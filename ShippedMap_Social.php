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
            this.labelHitAreas[state].node.dataState = state;
        }


        var otherStates = {
            HI: {x:295, y:560}, 
                AK: {x:120, y:495},
                CA: {x:70, y:280},
                NV: {x:130, y:235},
                OR: {x:90, y:125},
                WA: {x:115, y:50},
                ID: {x:185, y:145},
                MT: {x:270, y:85},
                WY: {x:295, y:185},
                UT: {x:215, y:255},
                AZ: {x:195, y:365},
                CO: {x:310, y:270},
                NM: {x:295, y:370},
                TX: {x:420, y:455},
                OK: {x:455, y:360},
                KS: {x:440, y:290},
                NE: {x:420, y:225},
                SD: {x:410, y:160},
                ND: {x:415, y:95},
                MN: {x:500, y:125},
                IA: {x:520, y:215},
                WI: {x:578, y:160},
                IL: {x:593, y:255},
                MO: {x:540, y:294},
                AR: {x:540, y:375},
                LA: {x:544, y:455},
                MS: {x:596, y:420},
                AL: {x:648, y:410},
                TN: {x:645, y:346},
                KY: {x:680, y:304},
                IN: {x:645, y:250},
                MI: {x:665, y:185},
                OH: {x:696, y:240},
                PA: {x:775, y:215},
                NY: {x:810, y:160},
                ME: {x:895, y:85},
                WV: {x:735, y:278},
                VA: {x:785, y:285},
                NC: {x:778, y:334},
                SC: {x:758, y:376},
                GA: {x:710, y:410},
                FL: {x:763, y:508},
        };
        var textAttr = this.options.labelTextStyles;
        for(var state in otherStates) {
            // attributes for styling the text
            stateAttr = {};
            if(this.options.stateSpecificLabelTextStyles[state]) {
                $.extend(stateAttr, textAttr, this.options.stateSpecificLabelTextStyles[state]);
            } else {
                $.extend(stateAttr, textAttr);
            }
            // adjust font-size
            if(stateAttr['font-size']) {
                stateAttr['font-size'] = (parseInt(stateAttr['font-size'])/this.scale) + 'px';
            }

            this.labelTexts[state] = R.text( otherStates[state].x, otherStates[state].y, state).attr( stateAttr );
            this.labelHitAreas[state] = R.rect(otherStates[state].x-this.options.labelWidth/this.scale/2, otherStates[state].y-this.options.labelHeight/this.scale/2, this.options.labelWidth/this.scale, this.options.labelHeight/this.scale, this.options.labelRadius/this.scale).attr({
                fill: "#000",
                "stroke-width": 0, 
                "opacity" : 0.0, 
                'cursor': 'pointer'
                });
            this.labelHitAreas[state].node.dataState = state;
        }

        // Bind events
        for(var state in this.labelHitAreas) {
    @@ -614,4 +680,4 @@
    // Create the plugin
    jQueryPluginFactory($, 'usmap', methods, getters);

    })(jQuery, document, window, Raphael); 
    })(jQuery, document, window, Raphael);
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