<?php $display_options = get_eod_display_options(); ?>
<div class="wrap">
    <div class="eod_page with_sidebar">
        <?php eod_include( 'admin/template/header.php' ); ?>
        <div>
            <div class="eod_section">
                <div class="h">Settings</div>
                <form method="post" action="options.php" name="form">
                    <div class="field">
                        <div class="h">Use AJAX</div>
                        <p>This technology allows you to avoid blocking the display of content for the site user. This is achieved by receiving data in the background. For this reason, at the first moment after loading the page, EOD blocks may be empty and loaded with a delay.</p>
                        <p>By default, AJAX is used for all possible EOD elements. Here you can turn off for some of them.</p>
                        <div class="field flex">
                            <strong>News:</strong>
                            <button class="eod_toggle timeline">
                                <input type="checkbox" value="off" <?php checked( 'off', $display_options['news_ajax'] ); ?>
                                       name="eod_display_settings[news_ajax]">
                                <span>No</span>
                                <input type="checkbox" value="on" <?php checked( 'on', $display_options['news_ajax'] ); ?>
                                       name="eod_display_settings[news_ajax]">
                                <span>Yes</span>
                            </button>
                        </div>
                    </div>

                    <div class="field">
                        <div class="h">A number of digits after decimal point</div>
                        <div>quantity for base value <i>( AAPL.US xxx.<b>XX</b> (+x.xx) )</i></div>
                        <label>
                            <input type="number" name="eod_display_settings[ndap]" value="<?= $display_options['ndap'] ? : EOD_DEFAULT_SETTINGS['ndap'] ?>" min="0">
                        </label>
                    </div>
                    <div class="field">
                        <div>quantity for evolution <i>( AAPL.US xxx.xx (+x.<b>XX</b>) )</i></div>
                        <label>
                            <input type="number" name="eod_display_settings[ndape]" value="<?= $display_options['ndape'] ? : EOD_DEFAULT_SETTINGS['ndape'] ?>" min="0">
                        </label>
                    </div>

                    <div class="field">
                        <div class="h">Evolution type for tickers</div>
                        <label>
                            <select name="eod_display_settings[evolution_type]">
                                <option <?php selected($display_options['evolution_type'], 'abs') ?> value="abs">absolute value</option>
                                <option <?php selected($display_options['evolution_type'], 'percent') ?> value="percent">percent</option>
                                <option <?php selected($display_options['evolution_type'], 'both') ?> value="both">both (absolute value and percent)</option>
                                <option <?php selected($display_options['evolution_type'], 'hide') ?> value="hide">hide</option>
                            </select>
                        </label>
                    </div>

                    <div class="field">
                        <div class="h">Use custom scrollbar for desktop devices?</div>
                        <p>Some widgets, such as the financial table, require scrolling. If this option is enabled, the stylized version of the scrollbar will be used instead of the browser's. This may result in a slight decrease in performance and page load time.</p>
                        <button class="eod_toggle timeline">
                            <input type="checkbox" value="off" <?php checked( 'off', $display_options['scrollbar'] ); ?>
                                   name="eod_display_settings[scrollbar]">
                            <span>No</span>
                            <input type="checkbox" value="on" <?php checked( 'on', $display_options['scrollbar'] ); ?>
                                   name="eod_display_settings[scrollbar]">
                            <span>Yes</span>
                        </button>
                    </div>

                    <div class="field">
                        <div class="h">Show warning for empty fundamental data?</div>
                        <p>Some fundamental data items is missing for certain tickers. If there is no data, then the warning "no data"/"empty list" will be shown.</p>
                        <button class="eod_toggle timeline">
                            <input type="checkbox" value="off" <?php checked( 'off', $display_options['fd_no_data_warning'] ); ?>
                                   name="eod_display_settings[fd_no_data_warning]">
                            <span>No</span>
                            <input type="checkbox" value="on" <?php checked( 'on', $display_options['fd_no_data_warning'] ); ?>
                                   name="eod_display_settings[fd_no_data_warning]">
                            <span>Yes</span>
                        </button>
                    </div>

                    <div class="field">
                        <div class="h">Main color</div>
                        <p>Used for some interface elements. For example, toggle buttons for financial tables.</p>
                        <input type="text" name="eod_display_settings[main_color]" value="<?=  $display_options['main_color'] ?>" class="eod_color_picker" >
                    </div>

                    <?php settings_fields('eod_display_settings'); ?>
                    <?php // settings_fields('eod_options'); ?>
                    <?php submit_button(); ?>
                </form>
            </div>
        </div>
        <div class="eod_sidebar">
            <?php include( plugin_dir_path( __FILE__ ) . 'sidebar.php'); ?>
        </div>
    </div>
</div>