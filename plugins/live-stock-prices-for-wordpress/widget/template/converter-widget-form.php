<div id="eod_widget_<?= $_this->get_field_id('target') ?>" class="eod_widget_form eod_converter_widget">
    <div class="field">
        <label for="<?= $_this->get_field_id( 'title' ) ?>">
            <b><?php _e('Title:', 'eod_stock_prices'); ?></b>
        </label>
        <input type="text" class="widefat" id="<?= $_this->get_field_id( 'title' ) ?>"
               name="<?= $_this->get_field_name('title') ?>"
               value="<?= esc_attr($widget_title) ?>"/>
    </div>


    <div class="field">
        <label><b>First currency:</b></label>
        <p>The main currency to be converted. Will be on the left.</p>
        <div class="eod_search_box first_currency">
            <input class="eod_search_widget_input" type="text" autocomplete="off" placeholder="Find ticker by code or company name"/>
        </div>
        <input type="hidden" id="<?php echo $_this->get_field_id('first_currency'); ?>" class="storage"
               name="<?= $_this->get_field_name('first_currency') ?>"
               value="<?= esc_attr($first_currency) ?>" />
    </div>


    <div class="field">
        <label><b>Amount of first currency:</b></label>
        <input type="number" value="<?= $amount ?>" min="1"
               name="<?= $_this->get_field_name('amount') ?>"
               id="<?= $_this->get_field_id('amount') ?>">
    </div>


    <div class="field">
        <label><b>Second currency:</b></label>
        <p>The second currency, the amount of which will need to be calculated.</p>
        <div class="eod_search_box second_currency">
            <input class="eod_search_widget_input" type="text" autocomplete="off" placeholder="Find ticker by code or company name"/>
        </div>
        <input type="hidden" id="<?php echo $_this->get_field_id('second_currency'); ?>" class="storage"
               name="<?= $_this->get_field_name('second_currency') ?>"
               value="<?= esc_attr($second_currency) ?>" />
    </div>


    <div class="field">
        <label><b>Ability for users to change currency:</b></label>
        <button class="eod_toggle">
            <input type="radio" value="0" name="<?= $_this->get_field_name('changeable') ?>"
                <?php checked($changeable, '0'); ?>>
            <span>No</span>
            <input type="radio" value="1" name="<?= $_this->get_field_name('changeable') ?>"
                <?php checked($changeable, '1'); ?>>
            <span>Yes</span>
        </button>
    </div>
    <div class="field">
        <p>You can limit the list of currencies available for changing</p>
        <div class="eod_search_box whitelist multiple">
            <input class="eod_search_widget_input" type="text" autocomplete="off" placeholder="Find ticker by code or company name"/>
        </div>
        <input type="hidden" id="<?php echo $_this->get_field_id('whitelist'); ?>" class="storage"
               name="<?= $_this->get_field_name('whitelist') ?>"
               value="<?= esc_attr($whitelist) ?>" />
    </div>


    <?php if(!$eod_options || !$eod_options['api_key'] || $eod_options['api_key'] === EOD_DEFAULT_API): ?>
        <span class="error eod_error widget_error eod_api_key_error" ><?php _e("You don't have configured a valid API key, you can only ask for AAPL.US ticker",'eod_stock_prices'); ?></span>
    <?php endif; ?>
</div>