<div id="eod_widget_<?= $_this->get_field_id('target') ?>" class="eod_widget_form eod_news_widget">
    <div class="field">
        <label for="<?= $_this->get_field_id( 'title' ) ?>">
            <b><?php _e('Title:', 'eod_stock_prices'); ?></b>
        </label>
        <input type="text" class="widefat" id="<?= $_this->get_field_id( 'title' ) ?>"
               name="<?= $_this->get_field_name('title') ?>"
               value="<?= esc_attr($widget_title) ?>"/>
    </div>

    <div class="field news_type">
        <div><b><?php _e('News selection:', 'eod_stock_prices'); ?></b></div>
        <div class="flex">
            <label>
                <input type="radio" name="<?= $_this->get_field_name('type') ?>"
                       value="ticker" <?php checked( $type, 'ticker' ); ?>>
                <span>by ticker</span>
            </label>
            <label>
                <input type="radio" name="<?= $_this->get_field_name('type') ?>"
                       value="topic" <?php checked( $type, 'topic' ); ?>>
                <span>by topic</span>
            </label>
        </div>
    </div>

    <div class="field by_topic" <?= $type === 'topic' ? '' : 'style="display: none;"' ?>>
        <label><b>Topic:</b></label>
        <p>We have more than 50 tags to get news for a given topic, this list is expanding, below you can find all recommended tags in alphabet order:</p>
        <select name="<?= $_this->get_field_name('topic') ?>">
            <option value="" <?php selected( '', $topic ); ?> disabled hidden>select topic</option>
            <?php foreach($topics as $tag){ ?>
                <option value="<?= $tag ?>" <?php selected( $tag, $topic ); ?>>
                    <?= $tag ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="field by_ticker" <?= $type === 'ticker' ? '' : 'style="display: none;"' ?>>
        <label><b>Ticker code/name:</b></label>
        <div class="eod_search_box common_api_search multiple">
            <input class="eod_search_widget_input" type="text" autocomplete="off" placeholder="Find ticker by code or company name"/>
        </div>
        <input type="hidden" id="<?php echo $_this->get_field_id('target'); ?>" class="storage"
               name="<?= $_this->get_field_name('target') ?>"
               value="<?= esc_attr($target) ?>" />
    </div>


    <div class="field">
        <label for="<?= $_this->get_field_id('limit') ?>"><b>Limit:</b></label>
        <p>The number of results should be returned with the query. Default value: 50, maximum value: 1000.</p>
        <input type="number" value="<?= $limit ?>" min="0" max="1000"
               name="<?= $_this->get_field_name('limit') ?>"
               id="<?= $_this->get_field_id('limit') ?>">
    </div>

    <div class="field">
        <label for="<?= $_this->get_field_id('pagination') ?>"><b>Pagination:</b></label>
        <p>The number of news items per page. Default 0 disables pagination.</p>
        <input type="number" value="<?= $pagination ?>" min="0"
               name="<?= $_this->get_field_name('pagination') ?>"
               id="<?= $_this->get_field_id('pagination') ?>">
    </div>

    <div class="field">
        <label><b>Time interval:</b></label>
        <div>
            <span>from</span>
            <input type="date" name="<?= $_this->get_field_name('from') ?>" value="<?= $from ?>" max="<?= Date('Y-m-d') ?>">
            <span>to</span>
            <input type="date" name="<?= $_this->get_field_name('to') ?>" value="<?= $to ?>" max="<?= Date('Y-m-d') ?>">
        </div>
    </div>


    <?php if(!$eod_options || !$eod_options['api_key'] || $eod_options['api_key'] === EOD_DEFAULT_API): ?>
        <span class="error eod_error widget_error eod_api_key_error" ><?php _e("You don't have configured a valid API key, you can only ask for AAPL.US ticker",'eod_stock_prices'); ?></span>
    <?php endif; ?>
</div>