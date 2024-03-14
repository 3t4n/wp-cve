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
        <label for="<?= $_this->get_field_id('preset') ?>">
            <b>Data preset <span class="require">*</span>:</b>
        </label>
        <p>The preset defines the list of data that will be displayed. You can create it on the page <a href="<?= get_admin_url() ?>edit.php?post_type=fundamental-data">Fundamental Data presets</a>.</p>
        <select required class="fd_preset" id="<?= $_this->get_field_id('preset') ?>" name="<?= $_this->get_field_name('preset') ?>">
            <option value="" <?php selected( '', $preset ); ?>>Select preset</option>
            <?php foreach ($fd_presets as $fd_preset){ ?>
                <?php $preset_type = str_replace('_', ' ', get_post_meta( $fd_preset->ID,'_fd_type', true ) ) ?>
                <option value="<?= $fd_preset->ID ?>" data-type="<?= $preset_type ?>" <?php selected( $fd_preset->ID, $preset ); ?>>
                    <?= $fd_preset->post_title ?> (<?= $preset_type ?>)
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="field">
        <label><b>Ticker code/name <span class="require">*</span>:</b></label>
        <div class="eod_search_box common_api_search">
            <input class="eod_search_input"  type="text" autocomplete="off" placeholder="Find ticker by code or company name"/>
        </div>
        <input type="hidden" id="<?= $_this->get_field_id('target'); ?>" class="storage"
               name="<?= $_this->get_field_name('target') ?>"
               value="<?= esc_attr($target) ?>" />
    </div>


    <?php if(!$eod_options || !$eod_options['api_key'] || $eod_options['api_key'] === EOD_DEFAULT_API): ?>
        <span class="error eod_error widget_error eod_api_key_error" ><?php _e("You don't have configured a valid API key, you can only ask for AAPL.US ticker",'eod_stock_prices'); ?></span>
    <?php endif; ?>
</div>