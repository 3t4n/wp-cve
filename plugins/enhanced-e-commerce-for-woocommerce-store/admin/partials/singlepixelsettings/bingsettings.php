<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$is_sel_disable = 'disabled';
?>
<div class="convcard p-4 mt-0 rounded-3 shadow-sm">
    <form id="pixelsetings_form" class="convpixsetting-inner-box">
        <div>
            <!-- MS Bing Pixel -->
            <?php $microsoft_ads_pixel_id = isset($ee_options['microsoft_ads_pixel_id']) ? $ee_options['microsoft_ads_pixel_id'] : ""; ?>
            <div id="msbing_box" class="py-1">
                <div class="row pt-2">
                    <div class="col-7">
                        <label class="d-flex fw-normal mb-1 text-dark">
                            <?php esc_html_e("Microsoft Ads (Bing) Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <span class="material-symbols-outlined text-secondary md-18 ps-2" data-bs-toggle="tooltip" data-bs-placement="top" title="The Microsoft Ads pixel ID looks like. 343003931">
                                info
                            </span>
                        </label>
                        <input type="text" name="microsoft_ads_pixel_id" id="microsoft_ads_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($microsoft_ads_pixel_id); ?>" placeholder="e.g. 343003931" popuptext ="Microsoft Ads (Bing) Pixel:">
                    </div>
                </div>

                <div class="hideme_msbingconversios disabled" id="checkboxes_box" class="pt-2">
                    <?php $msbing_conversion = isset($ee_options['msbing_conversion']) ? $ee_options['msbing_conversion'] : ""; ?>
                    <div class="d-flex pt-2 align-items-center">
                        <input class="form-check-input convchkbox_setting" type="checkbox" value="<?php echo esc_attr($msbing_conversion); ?>" id="msbing_conversion" name="msbing_conversion" <?php echo (esc_attr($msbing_conversion) == 1) ? 'checked="checked"' : ''; ?>>
                        <label class="form-check-label ps-2" for="msbing_conversion">
                            <?php esc_html_e("Enable Microsoft Ads (Bing) Conversion Tracking (Only for purchase event)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </label>
                    </div>
                </div>

            </div>
            <!-- MS Bing Pixel End-->
            <hr>
            <!-- MS ClarityPixel -->
            <?php $msclarity_pixel_id = isset($ee_options['msclarity_pixel_id']) ? $ee_options['msclarity_pixel_id'] : ""; ?>
            <div id="msclarity_box" class="py-1">
                <div class="row pt-2">
                    <div class="col-7">
                        <label class="d-flex fw-normal mb-1 text-dark">
                            <?php esc_html_e("Microsoft Clarity ID", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <span class="material-symbols-outlined text-secondary md-18 ps-2" data-bs-toggle="tooltip" data-bs-placement="top" title="The Microsoft Clarity ID looks like. ij312itarj">
                                info
                            </span>
                        </label>
                        <input type="text" name="msclarity_pixel_id" id="msclarity_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($msclarity_pixel_id); ?>" placeholder="e.g. ij312itarj" popuptext ="Microsoft Clarity ID:">
                    </div>
                </div>
            </div>
            <!-- MS Clarity Pixel End-->
        </div>
    </form>
    <input type="hidden" id="valtoshow_inpopup" value="Microsoft Ads (Bing) Pixel:" />

</div>

<script>
    jQuery(function() {
        jQuery('.convchkbox_setting').change(function() {
            this.value = (Number(this.checked));
        });

        if (jQuery("#microsoft_ads_pixel_id").val() == "") {
            jQuery("#msbing_conversion").attr('disabled', true);
            jQuery("#msbing_conversion").prop("checked", false);
            jQuery("#msbing_conversion").attr('checked', false);
        }

        jQuery("#microsoft_ads_pixel_id").change(function() {
            if (jQuery(this).hasClass('conv-border-danger') || jQuery(this).val() == "") {
                jQuery("#msbing_conversion").attr('disabled', true);
                jQuery("#msbing_conversion").prop("checked", false);
                jQuery("#msbing_conversion").attr('checked', false);
            } else {
                jQuery("#msbing_conversion").removeAttr('disabled');
            }
        });
    });
</script>

<script>
    jQuery(function() {
        //jQuery("#upgradetopro_modal_link").attr("href", '<?php echo esc_url($TVC_Admin_Helper->get_conv_pro_link_adv("popup", "twittersettings",  "conv-link-blue fw-bold", "linkonly")); ?>');

        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        let tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>