<?php  

function my_simple_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}

  function print_standard_widget() {

        $sws = get_option('quandoo-reservation-standard-fields');
        $bcid = my_simple_crypt( $sws['bcid'], 'd' );
        
        if($sws['activate']) { ?>
            <div class="quandoo-widget-builder" data-config='{"merchant":"<?php echo $bcid; ?>", "position":"<?php echo $sws['select-position'];?>", "format":"text-button", "theme":"<?php echo $sws['select-calendar-color'];?>", "agentID":"2", "txt":"<?php echo $sws['button-text'];?>", "bgcolor":"<?php echo $sws['select-background-color'];?>", "txcolor":"<?php echo $sws['select-text-color'];?>", "round":"no", "font":"<?php echo $sws['select-size'];?>"}'></div>
        <?php }

        echo '<script src="https://s3-eu-west-1.amazonaws.com/quandoo-website/widget-builder/quandoo-widget-builder.js"></script>';
        
    }
    add_action('wp_footer', 'print_standard_widget');




    add_shortcode('qbook', 'reservation_query');

    function reservation_query($atts, $content){

        extract(shortcode_atts(array( // a few default values
        'id' => null,
        'posts_per_page' => '1',
        'caller_get_posts' => 1)
        , $atts));


        $args = array(
        'post_type' => 'quandoo-reservation',
        'numberposts' => -1
        );

        if($atts['id']){
         $args['p'] = $atts['id'];
        }

        global $post;
        $posts = new WP_Query($args);
        $output = '';

        $sws = get_option('quandoo-reservation-standard-fields');
        $bcid = my_simple_crypt( $sws['bcid'], 'd' );

        if ($posts->have_posts())
            while ($posts->have_posts()):
                $posts->the_post();
                $custom_fields = get_post_custom($post->ID);
                $button_text = $custom_fields["_button-text"];
                $select_widget_type = $custom_fields["_select-widget-type"];
                $select_calendar_color = $custom_fields["_select-calendar-color"];
                $select_button_position = $custom_fields["_select-button-position"];
                $select_button_size = $custom_fields["_button-size"];
                $button_text_color = $custom_fields["_select-text-color"];
                $button_background_color = $custom_fields["_select-background-color"];

                //$resArr = explode('},{', substr($custom_fields["_multi"][0], 1));
                $resArr = json_decode($custom_fields["_multi"][0]);
                $formattedResArr = Array();

                foreach($resArr as $res) {
                    $formattedRes = '{'.'"merchant":"'.my_simple_crypt( $res->bcid, 'd' ).'","txt":"'.$res->name.'","theme":"'.$select_calendar_color[0].'", "agentID":"110"}';
                    array_push($formattedResArr, $formattedRes);
                }

                $out = '<div class="reservation-img">
                </div>
                <div class="reservation_content">
                <h2>'. $button_text[0] .'</h2>
                <h3>'. $select_widget_type[0] .'</h3>
                <div class="author-details">
                </div>';
                // add here more...
                $out .='</div>';
                if ($select_widget_type[0] == 'calendar') {
                    $out = '<iframe scrolling="yes" frameborder="0" src="https://booking-widget.quandoo.de/iframe.html?agentId=110&amp;merchantId='.$bcid.'&amp;origin='.get_home_url().'&amp;path=https%3A%2F%2Fbooking-widget.quandoo.com%2F&amp;theme='.$select_calendar_color[0].'" style="width: 380px; max-width: 100%; height: 805px;"></iframe>';
               } else if($select_widget_type[0] == 'button') {
                    $out = '<div class="quandoo-widget-builder" data-config=\'{"merchant":"'. $bcid . '", "position":"' . $select_button_position[0] . '", "format":"text-button", "theme":"'.$select_calendar_color[0].'", "agentID":"110", "txt":"' . $button_text[0] . '", "bgcolor":"'.$button_background_color[0].'", "txcolor":"'.$button_text_color[0].'", "round":"no", "font":"'.$select_button_size[0].'"}\'></div>';
               } else if($select_widget_type[0] == 'multi') {
                    $out = '<div class="quandoo-widget-builder" data-config=\'{"format":"multi-select", "txt":"' . $button_text[0] . '", "bgcolor":"'.$button_background_color[0].'", "txcolor":"'.$button_text_color[0].'", "round":"no", "restaurants":['.implode(", ", $formattedResArr).']}\'></div>';
               }

                
            endwhile;
        else
            return; // no posts found
        wp_reset_query();
        return html_entity_decode($out);
    }