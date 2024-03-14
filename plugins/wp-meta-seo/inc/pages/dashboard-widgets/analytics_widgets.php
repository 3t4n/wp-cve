<div class="wpms-analytics-widget-content">
    <div class="analytics-header">
        <!-- Generate request options -->
        <div class="request-date">
            <select name="wpms-request-date" id="wpms-request-date">
                <?php foreach ($requestDates as $date) { ?>
                    <option <?php if ($selectedDate === $date['value']) {
                        echo esc_attr(' selected ');
                            }?> value="<?php echo esc_attr($date['value']); ?>">
                        <?php echo esc_html($date['html']); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="request-query">
            <select name="wpms-request-query" id="wpms-request-query">
                <?php foreach ($requestQuery as $query) { ?>
                    <option <?php if ($selectedQuery === $query['value']) {
                        echo esc_attr(' selected ');
                            }?> value="<?php echo esc_attr($query['value']); ?>">
                        <?php echo esc_html($query['html']); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="request-loading">
            <span class="spinner wpms-spinner-loading"></span>
        </div>

    </div>
    <?php
    if (empty($google_analytics['tableid_jail']) || empty($profile)) {
        echo "<div class='wpms-message'><p>";
        esc_html_e('Please ', 'wp-meta-seo');
        echo '<a target="_blank" href="' . esc_url(admin_url('admin.php?page=metaseo_google_analytics&view=wpms_gg_service_data')) . '">' .esc_html('select a profile') . '</a>';
        esc_html_e(' first in order to get Google Analytics data', 'wp-meta-seo');
        echo '</p></div>';
    } else { ?>
    <div class="wpms-error-response"><?php echo esc_html('Invalid response, ') . '<a target="_blank" href="' . esc_url(admin_url('admin.php?page=metaseo_google_analytics&view=wpms_gg_service_data')) . '">' .esc_html('check your setting.') . '</a>'; ?></div>
    <div class="analytics-charts" id="wpms-analytics-charts">
        <!-- Render charts by ajax here -->
    </div>
    <?php } ?>
</div>