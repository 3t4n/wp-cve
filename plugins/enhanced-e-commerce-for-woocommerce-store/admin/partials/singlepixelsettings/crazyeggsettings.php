<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$is_sel_disable = 'disabled';
?>
<div class="convcard p-4 mt-0 rounded-3 shadow-sm">
    <form id="pixelsetings_form" class="convpixsetting-inner-box">
        <div>
            <!-- crazyegg Pixel -->
            <?php $crazyegg_pixel_id = isset($ee_options['crazyegg_pixel_id']) ? $ee_options['crazyegg_pixel_id'] : ""; ?>
            <div id="crazyegg_box" class="py-1">
                <div class="row pt-2">
                    <div class="col-7">
                        <label class="d-flex fw-normal mb-1 text-dark">
                            <?php esc_html_e("Crazyegg Pixel ID", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <span class="material-symbols-outlined text-secondary md-18 ps-2" data-bs-toggle="tooltip" data-bs-placement="top" title="The Crazyegg Pixel ID Looks Like. 36948643">
                                info
                            </span>
                        </label>
                        <input type="text" name="crazyegg_pixel_id" id="crazyegg_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($crazyegg_pixel_id); ?>" placeholder="eg.36948643">
                    </div>
                </div>
            </div>
            <!-- crazyegg Pixel End-->

        </div>
    </form>
    <input type="hidden" id="valtoshow_inpopup" value="crazyegg Pixel ID:" />

</div>

<script>
    jQuery(function() {
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        let tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>