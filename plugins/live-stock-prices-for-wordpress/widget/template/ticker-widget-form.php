<div id="eod_widget_<?= $_this->get_field_id('target') ?>" class="eod_widget_form eod_ticker_widget">
    <div class="field">
        <label for="<?= $_this->get_field_id( 'title' ) ?>">
            <b><?php _e('Title:', 'eod_stock_prices'); ?></b>
        </label>
        <input type="text" class="widefat" id="<?= $_this->get_field_id( 'title' ) ?>"
               name="<?= $_this->get_field_name('title') ?>"
               value="<?= esc_attr($widget_title) ?>"/>
    </div>

    <div class="field">
        <div><b><?php _e('Ticker type:', 'eod_stock_prices'); ?></b></div>
        <div>
            <label>
                <input type="radio" name="<?= $_this->get_field_name('type') ?>"
                       value="historical" <?php checked( $type, 'historical' ); ?>>
                historical - when loading the page, the user receives up-to-date data for the last day
            </label>
        </div>
        <div>
            <label>
                <input type="radio" name="<?= $_this->get_field_name('type') ?>"
                       value="live" <?php checked( $type, 'live' ); ?>>
                live - when loading the page, the user receives up-to-date data for the last 15 minutes
            </label>
        </div>
        <div>
            <label>
                <input type="radio" name="<?= $_this->get_field_name('type') ?>"
                       value="realtime" <?php checked( $type, 'realtime' ); ?>>
                realtime - user get real-time data, the element updates it on its own
            </label>
        </div>
    </div>

    <div class="field display_name">
        <div><b><?php _e('Display name:', 'eod_stock_prices'); ?></b></div>
        <div class="flex">
            <label>
                <input type="radio" name="<?= $_this->get_field_name('name') ?>"
                       value="code" <?php checked( $name, 'code' ); ?>>
                <span>code</span>
            </label>
            <label>
                <input type="radio" name="<?= $_this->get_field_name('name') ?>"
                       value="name" <?php checked( $name, 'name' ); ?>>
                <span>company name</span>
            </label>
        </div>
        <p>For each ticker, you can specify a custom name in the context settings below.</p>
    </div>

    <label for="<?= $_this->get_field_id('target') ?>">
        <b><?php _e('Target(s):', 'eod_stock_prices'); ?></b>
    </label>
    <div class="field">
        <div class="eod_search_box multiple with_settings">
            <input class="eod_search_widget_input" type="text" autocomplete="off" placeholder="Find ticker by code or company name">
        </div>
    </div>

    <input type="hidden" id="<?php echo $_this->get_field_id('target'); ?>" class="target_list json"
           name="<?= $_this->get_field_name('target') ?>"
           value="<?= esc_attr($target_json) ?>" />


    <?php if(!$eod_options || !$eod_options['api_key'] || $eod_options['api_key'] === EOD_DEFAULT_API): ?>
    <span class="error eod_error widget_error eod_api_key_error" ><?php _e("You don't have configured a valid API key, you can only ask for AAPL.US ticker",'eod_stock_prices'); ?></span>
    <?php endif; ?>
</div>