<?php 
$options            = get_option('ultimate_subscribe_options');
$overlay_hide       = isset($options['overlay_hide'])?$options['overlay_hide']:0;
$overlay_color      = isset($options['overlay_color'])?$options['overlay_color']:'rgba(25, 23, 23, 0.86)';
$leave_form_id      = isset($options['leave_form_id'])?$options['leave_form_id']:'';
$show_loged_in      = isset($options['show_loged_in'])?$options['show_loged_in']:'';
?>
 <div id="popup-options" class="tab-pane">
    <h3> <?php esc_html_e('Popup Options', 'ultimate-subscribe'); ?> </h3>
    <div class="form-fieldset">
        <div class="field-row">
            <div class="field-label"> <?php _e('Show Popup if user logged in', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <div class="checkbox">  
                    <input type="checkbox" value="1" id="show_loged_in" name="ultimate_subscribe_options[show_loged_in]" <?php checked($show_loged_in, 1, true); ?>/>
                    <label for="show_loged_in"></label>
                </div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('Hide On Overlay Click', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <div class="checkbox">  
                    <input type="checkbox" value="1" id="overlay_hide" name="ultimate_subscribe_options[overlay_hide]" <?php checked($overlay_hide, 1, true); ?>/>
                    <label for="overlay_hide"></label>
                </div>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('Overlay Color', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> <input type="text" class="input-field color" value="<?php echo esc_attr($overlay_color); ?>" name="ultimate_subscribe_options[overlay_color]"> </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('Show Popup When user about to leave', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <select name="ultimate_subscribe_options[leave_form_id]">
                    <option value="">Select Form</option>
                    <?php
                        $args = array( 'post_type' => 'u_subscribe_forms', 'posts_per_page' => -1, 'meta_key' => 'ultimate_subscribe_form_popup_enable', 'meta_value' => 1);
                        $wp_query = new WP_Query( $args );
                        while($wp_query->have_posts()){
                        $wp_query->the_post();
                    ?>
                    <option <?php selected($leave_form_id, get_the_ID(), true);?> value="<?php the_ID(); ?>"> <?php the_title(); ?> </option>
                    <?php } ?>
                </select> 
            </div>
        </div>
    </div>
</div>