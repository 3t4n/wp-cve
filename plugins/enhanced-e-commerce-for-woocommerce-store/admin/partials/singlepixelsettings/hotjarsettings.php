<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
$is_sel_disable = 'disabled';
?>
<div class="convcard p-4 mt-0 rounded-3 shadow-sm">
    <form id="pixelsetings_form" class="convpixsetting-inner-box">
        <div>
            <!-- hotjar Pixel -->
            <?php $hotjar_pixel_id = isset($ee_options['hotjar_pixel_id']) ? $ee_options['hotjar_pixel_id'] : ""; ?>
            <div id="hotjar_box" class="py-1">
                <div class="row pt-2">
                    <div class="col-7">
                        <label class="d-flex fw-normal mb-1 text-dark">
                            <?php esc_html_e("Hotjar Pixel ID", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <span class="material-symbols-outlined text-secondary md-18 ps-2" data-bs-toggle="tooltip" data-bs-placement="top" title="The Hotjar Pixel ID Looks Like. 3694864">
                                info
                            </span>
                        </label>
                        <input type="text" name="hotjar_pixel_id" id="hotjar_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($hotjar_pixel_id); ?>" placeholder="eg.3694864">
                    </div>
                </div>
            </div>
            <!-- Hotjar Pixel End-->

        </div>
    </form>
    <input type="hidden" id="valtoshow_inpopup" value="Hotjar Pixel ID:" />

</div>
<script>
    jQuery(function() {
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        let tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>