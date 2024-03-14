<?php

/* widget list right side */
if ( !function_exists( 'fxlive_widget_publish_side' ) ) {
    function fxlive_widget_publish_side( $post ) {
        $fx_is_side_list = '';
        $fx_publish_value = 'publish';
        
        if( isset($_GET['post']) && 
            isset($_GET['action']) && 
            $_GET['action'] == 'edit' ) 
        {
            $fx_is_side_list = "fx-d-none-imp";
            $fx_publish_value = 'update';
        }
?>
    <div class="fx-widget-main fx-widget-side-main">
        <div class="fx-widget-switch-list-main fx-d-none <?php echo esc_attr($fx_is_side_list); ?>">
            <div class="fx-m-t-20">
                <label class="fx-m-l-10 fx-m-b-10">
                    <?php esc_html_e('Switch Widget', 'fx-live-prices'); ?>
                </label><br>
                <select class="fx-search-list" id="fx-widget-switch-list">
                  <option value="market currency rates"> <?php esc_html_e('Market Currency Rates', 'fx-live-prices'); ?></option>
                  <option value="ticker tape"><?php esc_html_e('Ticker Tape', 'fx-live-prices'); ?></option>
                  <option value="single ticker"><?php esc_html_e('Single Ticker', 'fx-live-prices'); ?></option>
                  <option value="forex cross rates"><?php esc_html_e('Forex Cross Rates', 'fx-live-prices'); ?></option>
                  <option value="technical indicator"><?php esc_html_e('Technical Indicator', 'fx-live-prices'); ?></option>
                  <option value="simple moving"><?php esc_html_e('Simple Moving', 'fx-live-prices'); ?></option>
                  <option value="last candle"><?php esc_html_e('Last Candle', 'fx-live-prices'); ?></option>
                  <option value="pivot point"><?php esc_html_e('Pivot Point', 'fx-live-prices'); ?></option>
                </select>
            </div>

            <div class="fx-text-right fx-m-t-10">
                <div id="fx-switch" class="fx-button fx-button-side">
                    <?php esc_html_e('Switch', 'fx-live-prices'); ?>
                </div>
            </div>
        </div>


        <div class="fx-widget-publish-main">
            <div class="fx-m-t-20">
                <label class="fx-m-l-10 fx-m-b-10">
                    <?php esc_html_e('Title', 'fx-live-prices'); ?>
                </label><br>
                <input type="text" name="fx-title" value="<?php echo the_title(); ?>" id="fx-title-widget">
            </div>

            <div class="fx-m-t-20">
                <label class="fx-m-l-10 fx-m-b-10">
                    <?php esc_html_e('Shortcode', 'fx-live-prices'); ?>
                </label><br>
                <input type="text" name="fx-short-code" value="[fx-widget id=<?php echo get_the_ID(); ?>]" id="fx-shortcode" readonly>
            </div>

            <div class="fx-text-right fx-m-t-10">
                <div id="fx-copy-code" class="fx-button fx-button-side fx-tooltip fx-left">
                    <?php esc_html_e('Copy', 'fx-live-prices'); ?>
                    <span class="fx-tooltiptext">
                        <?php esc_html_e('Copy to clipboard', 'fx-live-prices'); ?>
                    </span>
                </div>
                <div id="fx-widget-setting-save" class="fx-button m-l-10 fx-button-side fx-d-none"><?php esc_html_e($fx_publish_value, 'fx-live-prices'); ?></div>
            </div>
        </div>
    </div>

<?php 
    }
}
/* widget list right side end */



/* widget all setting */
if ( !function_exists( 'fxlive_widget_setting_display' ) ) {
    function fxlive_widget_setting_display( $post ) { 
        global $fxlive_db_meta_key;
        $fx_select_widget = '';
        $fx_is_back     = '';

        if( isset($_GET['action']) && $_GET['action'] == 'edit' ) 
        {
            $fx_edit_data = get_post_meta($post->ID, $fxlive_db_meta_key, true);
            if( !empty($fx_edit_data['fx-select-widget']) ) {
                $fx_select_widget   = strtolower($fx_edit_data['fx-select-widget']);
                $fx_is_back             = "fx-d-none";
            }
        }
        wp_nonce_field('fxlive_widget_nonce_action', 'fxlive_widget_nonce_field');
?>

    <div class="fx-widget-main">
        <div class="fx-widget-list-main">
            <div class="fx-m-t-20">
                <label class="fx-m-l-10 fx-m-b-10">
                    <?php esc_html_e('Widget List', 'fx-live-prices'); ?>
                </label><br>
                <select class="fx-search-list" id="fx-widget-select-list">
                  <option value="market currency rates" <?php echo esc_attr(($fx_select_widget == "market currency rates") ? "selected" : ""); ?> ><?php esc_html_e('Market Currency Rates', 'fx-live-prices'); ?></option>
                  <option value="ticker tape"           <?php echo esc_attr(($fx_select_widget == "ticker tape") ? "selected" : ""); ?> ><?php esc_html_e('Ticker Tape', 'fx-live-prices'); ?></option>
                  <option value="single ticker"         <?php echo esc_attr(($fx_select_widget == "single ticker") ? "selected" : ""); ?> ><?php esc_html_e('Single Ticker', 'fx-live-prices'); ?></option>
                  <option value="forex cross rates" <?php echo esc_attr(($fx_select_widget == "forex cross rates") ? "selected" : ""); ?> ><?php esc_html_e('Forex Cross Rates', 'fx-live-prices'); ?></option>
                  <option value="technical indicator"<?php echo esc_attr(($fx_select_widget == "technical indicator") ? "selected" : ""); ?> > <?php esc_html_e('Technical Indicator', 'fx-live-prices'); ?></option>
                  <option value="simple moving"     <?php echo esc_attr(($fx_select_widget == "simple moving") ? "selected" : ""); ?> ><?php esc_html_e('Simple Moving', 'fx-live-prices'); ?></option>
                  <option value="last candle"           <?php echo esc_attr(($fx_select_widget == "last candle") ? "selected" : ""); ?> ><?php esc_html_e('Last Candle', 'fx-live-prices'); ?></option>
                  <option value="pivot point"           <?php echo esc_attr(($fx_select_widget == "pivot point") ? "selected" : ""); ?> ><?php esc_html_e('Pivot Point', 'fx-live-prices'); ?></option>
                </select>
            </div>

            <div class="fx-text-right fx-m-t-20">
                <div id="fx-next" class="fx-button"><?php esc_html_e('Next', 'fx-live-prices'); ?></div>
            </div>
        </div>



        <div class="fx-widget-setting-main fx-d-none">
            <div class="fx-m-t-20 fx-m-b-50">
                <label class="fx-m-l-10 fx-m-b-10 fx-widget-pre-lab"></label><br>
                <span id="fx-show-preview"></span>
            </div>

            <h3 class="fx-setting-heading"><?php esc_html_e('Setting', 'fx-live-prices'); ?></h3>
            <div class="fx-m-t-20 fx-widget-setting"></div>
            <div class="fx-hide-input">
                <input type="hidden" name="fx-real-url" value="" class="fx-real-url">
                <input type="hidden" name="fx-hide-height" value="" class="fx-hide-height">
                <input type="hidden" name="fx-hide-symbol_item" value="" class="fx-hide-symbol_item">
                <input type="hidden" name="fx-hide-iframe_border" value="" class="fx-hide-iframe_border">
                <input type="hidden" name="fx_widget[fx-hide-iframe]" value="" class="fx-hide-iframe">
                <input type="hidden" name="fx_widget[fx-select-widget]" value="" class="fx-hide-select-widget">
            </div>

            <div class="fx-text-right fx-m-t-20">
                <div id="fx-back" class="fx-button <?php echo esc_attr($fx_is_back); ?>"><?php esc_html_e('Back', 'fx-live-prices'); ?></div>
                <div id="fx-widget-setting-apply" class="fx-button m-l-10"><?php esc_html_e('apply', 'fx-live-prices'); ?></div>
            </div>
        </div>

    </div>


<?php 
    }
}
/* widget all setting end */

?>