/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
jQuery(document).ready(function($){
    $.royalshop = {
        init: function () {
            this.focusForCustomShortcut();
        },
        focusForCustomShortcut: function (){
            var fakeShortcutClasses = [
                'royal_shop_top_slider_section',
                'royal_shop_category_tab_section',
                'royal_shop_product_slide_section',
                'royal_shop_cat_slide_section',
                'royal_shop_product_slide_list',
                'royal_shop_product_cat_list',
                'royal_shop_brand',
                'royal_shop_ribbon',
                'royal_shop_banner',
                'royal_shop_highlight',
                'royal_shop_product_big_feature',
                'royal_shop_1_custom_sec',
                'royal_shop_2_custom_sec',
                'royal_shop_3_custom_sec',
                'royal_shop_4_custom_sec',
                'royal_shop_vt2_category_tab_section',
                //Color section Starts
            ];
            fakeShortcutClasses.forEach(function (element){
                $('.customize-partial-edit-shortcut-'+ element).on('click',function (){
                   wp.customize.preview.send( 'royal-shop-customize-focus-section', element );
                });
            });
        }
    };
    $.royalshop.init();
});