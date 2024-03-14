<div id="eod_widget_<?= $_this->get_field_id('target') ?>" class="eod_widget_form eod_fundamental_widget">
    <div class="field">
        <label for="<?= $_this->get_field_id( 'title' ) ?>">
            <b><?php _e('Title:', 'eod_stock_prices'); ?></b>
        </label>
        <input type="text" class="widefat" id="<?= $_this->get_field_id( 'title' ) ?>"
               name="<?= $_this->get_field_name('title') ?>"
               value="<?= esc_attr($widget_title) ?>"/>
    </div>

    <div class="field">
        <label><b>Ticker code/name<span class="require">*</span>:</b></label>
        <div class="eod_search_box common_api_search">
            <input class="eod_search_input" type="text" autocomplete="off" placeholder="Find ticker by code or company name"/>
        </div>
        <input type="hidden" id="<?php echo $_this->get_field_id('target'); ?>" class="storage"
               name="<?= $_this->get_field_name('target') ?>"
               value="<?= esc_attr($target) ?>" />
    </div>


    <div class="field">
        <label for="<?= $_this->get_field_id('preset') ?>">
            <b>Data preset<span class="require">*</span>:</b>
        </label>
        <p>The preset defines the list of data that will be displayed. You can create it on the page <a href="<?= get_admin_url() ?>edit.php?post_type=financials">Financials Table presets</a>.</p>
        <select required id="<?= $_this->get_field_id('preset') ?>" name="<?= $_this->get_field_name('preset') ?>">
            <option value="" <?php selected( '', $preset ); ?>>Select preset</option>
            <?php foreach ($financial_presets as $f_preset){ ?>
                <?php $preset_type = str_replace('->', ' - ', get_post_meta( $preset->ID,'_financial_group', true ) ) ?>
                <option value="<?= $f_preset->ID ?>" <?php selected( $f_preset->ID, $preset ); ?>>
                    <?= $f_preset->post_title ?> (<?= $preset_type ?>)
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="field">
        <label for="fd_preset" class="h">Years interval</span></label>
        <div class="flex">
            <input type="number" name="<?= $_this->get_field_name('year_from') ?>" value="<?= esc_attr($year_from) ?>" min="0" placeholder="from">
            <span> - </span>
            <input type="number" name="<?= $_this->get_field_name('year_to') ?>" value="<?= esc_attr($year_to) ?>" min="0" placeholder="to">
        </div>
    </div>

    <?php if(!$eod_options || !$eod_options['api_key'] || $eod_options['api_key'] === EOD_DEFAULT_API): ?>
        <span class="error eod_error widget_error eod_api_key_error" ><?php _e("You don't have configured a valid API key, you can only ask for AAPL.US ticker",'eod_stock_prices'); ?></span>
    <?php endif; ?>
</div>