<?php

defined( 'ABSPATH' ) || exit;

global $x_currency;

$switcher_type = get_post_meta( $template_id, 'type', true );
$customizer_id = get_post_meta( $template_id, 'customizer_id', true );

if ( empty( $customizer_id ) ) {
    $customizer_id = $switcher_type . '-default';
}

$template_json = get_post_meta( $template_id, 'template', true );
$custom_css    = get_post_meta( $template_id, 'custom_css', true );

$tag = "xc-{$customizer_id}";

?>

<script data-cfasync="false" type="text/javascript">
    if(!window.x_currency_data) {
        window.x_currency_data = {};
    }
    
    window.x_currency_data[<?php x_currency_render( $template_id ) ?>] = {
        customCSS: '<?php x_currency_render( base64_encode( $custom_css ) ) ?>',
        template: <?php x_currency_render( $template_json ) ?>,
    }

    if( document.readyState === "complete" || document.readyState === "interactive" ) {
        const event = new CustomEvent('x-currency-switcher-loaded');
        window.dispatchEvent(event);
    } else {
        window.addEventListener('load', function() {
            const event = new CustomEvent('x-currency-switcher-loaded');
            window.dispatchEvent(event);
        });
    }
</script>
<<?php x_currency_render( $tag ) ?> style="display: inline-block; position:relative; z-index:999;" id="<?php x_currency_render( $template_id ) ?>"></<?php x_currency_render( $tag ) ?>>