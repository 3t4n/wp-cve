<textarea
    name="<?php echo UBConfig::UB_RESPONSE_HEADERS_FORWARDED_KEY; ?>"
    rows="<?php echo count($value); ?>"
    data-default="<?php echo esc_attr(implode(PHP_EOL, $default)); ?>"
    class="ub-settings-input"><?php echo sanitize_textarea_field(implode(PHP_EOL, $value)); ?></textarea>

<p class="description">
    Each line represents an HTTP header sent by Unbounce that is allowed to be forwarded to visitors. In order to forward all headers, set this field to <code>*</code>.
    <strong>Note:</strong> the following headers are always forwarded whether they are listed or not:
    <?php
    $headers = array_map(function ($header) {
        return '<code>'.$header.'</code>';
    }, get_option(UBConfig::UB_DYNAMIC_CONFIG_CACHE_KEY, array())['response_header_allow']);
    echo implode(',', array_slice($headers, 0, -1)).' and '.end($headers);
    ?>
</p>
