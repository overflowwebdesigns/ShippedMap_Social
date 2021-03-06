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
    <div id="map" style="width: auto; height: 800px;">
    <script>

    $('body').click(function (event) 
    {
    if(!$(event.target).closest('#openModal').length && !$(event.target).is('#openModal')) {
        $(".modalDialog").hide();
        mapModalSwiper.destroy();
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
                    for (i = 0; i < output['images'].length; i++) {
                        slides += '<div class="swiper-slide"><div id="slide-title"><center><h2></h2><p>' + output['names'][i] + '</p></center></div><br><img src=' + output['images'][i] + '></div>';
                    }
                    $('#swiper').html(slides);
                    $('#slide-title h2').html('Who is rocking our gear in ' + states[state] + '?');
                    $('#openModal').show();
                    window.mapModalSwiper = new Swiper ('.swiper-container', {
                        // Optional parameters
                        direction: 'horizontal',
                        loop: true,
                        initialSlide: 0,
                        slidesPerView: 1,
                        slidesPerColumn: 1,
                        observer: true,
                        observeParents: true,

                        pagination: {
                        el: '.swiper-pagination',
                        },

                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                    })
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
        <a href="#close" title="Close" class="close" onclick="$('#openModal').hide(), mapModalSwiper.destroy();">X</a>
        <center>
        <h2></h2>
        </center>
        <script src="https://unpkg.com/swiper/js/swiper.min.js"></script>
        <script src="/wp-content/plugins/ShippedMap_Social/js/js_functions.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.min.css">
        <!-- Slider main container -->
        <div class="swiper-container">
            <!-- Additional required wrapper -->
            <div id="swiper" class="swiper-wrapper">
                
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
    </body>
    </html>

    <?php

}