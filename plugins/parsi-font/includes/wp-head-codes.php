<?php
add_action('wp_head', 'sitefont_add_css');	
function sitefont_add_css(){
$options = get_option('site_font_settings');
?>
    <style type="text/css">
        <?php esc_attr_e($options['c1listidclass']);
        ?> {
            font-family: <?php esc_attr_e($options['c1fontname']); ?> !important;
            font-size: <?php esc_attr_e($options['c1fontsize']); ?>px !important;
        }

        <?php esc_attr_e($options['c2listidclass']);
        ?> {
            font-family: <?php esc_attr_e($options['c2fontname']); ?> !important;
            font-size: <?php esc_attr_e($options['c2fontsize']); ?>px !important;
        }

        <?php esc_attr_e($options['c3listidclass']);
        ?> {
            font-family: <?php esc_attr_e($options['c3fontname']); ?> !important;
            font-size: <?php esc_attr_e($options['c3fontsize']); ?>px !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: <?php esc_attr_e($options['hfontname']); ?> !important;
        }

        body {
            font-family: <?php esc_attr_e($options['bodyfontname']); ?> !important;
        }

        .rtl #wpadminbar *,
        #wpadminbar * {
            font: 400 13px/32px <?php esc_attr_e($options['adminfontname']); ?>;
        }
		
		pre, code {
			font-family: VRCD, monospaced;
		}
    </style>
    <?php
}