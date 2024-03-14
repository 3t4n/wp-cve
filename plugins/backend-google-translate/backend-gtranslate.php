<?php
/**
* Plugin Name: Backend Google Translate
* Description: Integrate Google Translate For Your WordPress Backend Dashboard.
* Version: 1.1
* Author: Vaibhav Govani
* Author URI: https://vaibhavgvb.wordpress.com/
**/
function bgt_wp_add_translation_scripts() {
    ?><style>.goog-te-gadget-simple{padding: 5px;border-radius: 5px;}</style>
    <div id="google_translate_element" style="text-align: right;position: fixed;right: 20px;bottom: 50px;"></div>
    <a id="wp-gtranslate-close" href="javascript:void(0)" title="Close" style="position: fixed;right: 0;bottom: 50px;">
        <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/cleardot.gif'; ?>" width="15" height="15" alt="Close" style="background-image:url(<?php echo plugin_dir_url( __FILE__ ) . 'images/te_ctrl3.gif'; ?>);background-position:-28px 0px">
    </a>
    <script type="text/javascript">
        function wp_bgt_googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }
        jQuery('#wp-gtranslate-close').on('click',function(){
            jQuery('#google_translate_element').hide();
            jQuery('#wp-gtranslate-close').hide();
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=wp_bgt_googleTranslateElementInit"></script>
    <?php
}
add_action( 'admin_footer', 'bgt_wp_add_translation_scripts', 50 );