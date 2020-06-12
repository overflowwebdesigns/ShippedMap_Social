<?php


function owd_map_shortcode($atts) {

$orders = wc_get_orders(array()); //Gets all orders.

   ?>
    <html>
    <head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"> </script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/us-map/1.0.1/jquery.usmap.js"></script>
    </head>
    <center>
    <h2 class="elementor-heading-title elementor-size-default">Who is wearing Loud Proud American?</h2>
    <h3 class="elementor-heading-title elementor-size-default">Help us cover the USA!</h3>
    <div id="map" style="width: auto; height: 800px;">
    <script>

    $('body').click(function (event) 
    {
    if(!$(event.target).closest('#openModal').length && !$(event.target).is('#openModal')) {
        $(".modalDialog").hide();
    }     
    });

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
            var i;
            var slides = "";
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
                    for (i = 0; i < output.length; i++) {
                        slides += '<img class="mySlides" src="' + output[i] + '"style="width:100%">';
                    }
                    $('#openModal h2').html(state);
                    $('#slider').html(slides);
                    $('#openModal').show();
                },
                error:function (error) {
                    
                }

            });
        }
    });
    
    </script>
    </div>
    <body>
    <div id="openModal" class="modalDialog" style="display: none;">
    <div class="modal-content">
        <a href="#close" title="Close" class="close" onclick="$('#openModal').hide()">X</a>
        <center>
        <h2>Modal Box</h2>
        <p>~customers Name Here~</p>
        </center>
        <h2 class="w3-center">Manual Slideshow</h2>

        <div id="slider" class="w3-content w3-display-container">

        <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
        <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
        </div>

        <script>
        var slideIndex = 1;
        showDivs(slideIndex);

        function plusDivs(n) {
        showDivs(slideIndex += n);
        }

        function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("mySlides");
        if (n > x.length) {slideIndex = 1}
        if (n < 1) {slideIndex = x.length}
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";  
        }
        x[slideIndex-1].style.display = "block";  
        }
        </script>
    </div>
    </div>

    </center>
    </body>
    </html>

    <?php

}