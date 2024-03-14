<?php

defined( 'ABSPATH' ) or die('No direct access');
$plugin_relative_root = parse_url(PUSHLY_PLUGIN_URL_ROOT)['path'];

?>
<script>
    var PushlySDK = window.PushlySDK || [];
    function pushly() { PushlySDK.push(arguments) }
    pushly('load', {
        domainKey: decodeURIComponent("<?php echo rawurlencode((string) $settings['pushly_domain_key']); ?>"),
        sw: <?php echo wp_json_encode(esc_url($plugin_relative_root . 'assets/js/pushly-sdk-worker.js.php'), JSON_UNESCAPED_SLASHES); ?>,
        swScope: <?php echo wp_json_encode(esc_url($plugin_relative_root), JSON_UNESCAPED_SLASHES); ?>
    });
</script>
