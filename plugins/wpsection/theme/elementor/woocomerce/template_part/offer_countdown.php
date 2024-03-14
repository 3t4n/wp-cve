   
                                
<?php if (isset($settings['show_countdown']) && $settings['show_countdown']) { ?>





                                         <?php if (!get_post_meta( get_the_id(), 'meta_show_offer', true ) ) { ?>  

                      
        

                          
                              
<div class="wps_order order-<?php echo $settings['position_order_six']; ?> ">          
                                                                       

    <?php 
    $metaDate = wp_kses(get_post_meta(get_the_ID(), 'meta_date', true), wp_kses_allowed_html('post'));
    if ($metaDate) {
        $unique_id = 'countdown-timer-' . get_the_ID() . '_' . wp_generate_password(5, false); 
    ?>
<div class="wps_offer_count">   
    
    <div id="<?php echo $unique_id; ?>" class="wps-countdown">
        <button class="wps-days wps_date"></button>
        <button class="wps-hours wps_date"></button>
        <button class="wps-minutes wps_date"></button>
        <button class="wps-seconds wps_date"></button>
    </div>

    <script>
        function updateCountdown_<?php echo get_the_ID(); ?>() {
            var metaDate = new Date("<?php echo $metaDate; ?>");
            var currentDate = new Date();
            var timeDifference = metaDate - currentDate;

            if (timeDifference > 0) {
                var days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                var hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                var daysText = "<?php echo esc_js($settings['offer_days']); ?>";
                var hoursText = "<?php echo esc_js($settings['offer_hours']); ?>";
                var minutesText = "<?php echo esc_js($settings['offer_min']); ?>";
                var secondsText = "<?php echo esc_js($settings['offer_sec']); ?>";

                document.querySelector("#<?php echo $unique_id; ?> .wps-days").innerHTML = "<span class='wps-days'>" + days + " " + daysText + "</span>";
                document.querySelector("#<?php echo $unique_id; ?> .wps-hours").innerHTML = "<span class='wps-hours'>" + hours + " " + hoursText + "</span>";
                document.querySelector("#<?php echo $unique_id; ?> .wps-minutes").innerHTML = "<span class='wps-minutes'>" + minutes + " " + minutesText + "</span>";
                document.querySelector("#<?php echo $unique_id; ?> .wps-seconds").innerHTML = "<span class='wps-seconds'>" + seconds + " " + secondsText + "</span>";
            } else {
                document.getElementById("<?php echo $unique_id; ?>").innerHTML = "The deadline has passed.";
            }
        }

        // Check if the function for the specific countdown exists before executing
        if (typeof updateCountdown_<?php echo get_the_ID(); ?> === 'function') {
            setInterval(updateCountdown_<?php echo get_the_ID(); ?>, 1000);
            updateCountdown_<?php echo get_the_ID(); ?>();
        }
    </script>
    
</div>  
    <?php
    } 
    ?>

                                           
                                        </div>
                                                                
                                         <?php } }  ?>                                      

