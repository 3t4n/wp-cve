<?php
/** @var MM_WPFS_FormView $view */
/** @var \StdClass $form */
?>
<div class="wpfs-form-group">
    <label class="wpfs-form-label wpfs-form-label--with-info" for="coupon">
        <?php $view->coupon()->label(); ?>
        <span class="wpfs-icon-help-circle wpfs-form-label-info" data-toggle="tooltip"
            data-tooltip-content="info-tooltip"></span>
    </label>
    <div class="wpfs-tooltip-content" data-tooltip-id="info-tooltip">
        <div class="wpfs-info-tooltip">
            <?php $view->coupon()->tooltip(); ?>
        </div>
    </div>
    <div class="wpfs-coupon wpfs-coupon-redeemed-row" style="display: none;">
        <span class="wpfs-coupon-redeemed-label" data-wpfs-coupon-redeemed-label="<?php /* translators: Message displayed in place of a successfully applied coupon code */
        esc_attr_e('Coupon code <strong>%s</strong> added.', 'wp-full-stripe'); ?>">Just text.</span>
        <a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold wpfs-coupon-remove-link" href="">
            <?php /* translators: Button label for removing a redeemed coupon code */
            esc_html_e('Remove', 'wp-full-stripe'); ?>
        </a>
    </div>
    <div class="wpfs-coupon wpfs-coupon--warning wpfs-coupon-wrong-redeemed-row" style="display: none;">
        <span class="wpfs-coupon-wrong-redeemed-label"
            data-wpfs-coupon-wrong-redeemed-label="<?php /* translators: Message displayed in place of an applied coupon code which does not apply to the product or service */
            esc_attr_e('The coupon <strong>%s</strong> doesn\'t apply to <strong>%s</strong>, so the coupon won\'t be redeemed during payment. You can use it later for another payment.', 'wp-full-stripe'); ?>">&nbsp;</span>
        <a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold wpfs-coupon-add-another-link" href="">
            <?php /* translators: Button label for adding another coupon code */
            esc_html_e('Add another coupon', 'wp-full-stripe'); ?>
        </a>
    </div>
    <div class="wpfs-input-group wpfs-coupon-to-redeem-row">
        <input id="<?php $view->coupon()->id(); ?>" name="<?php $view->coupon()->name(); ?>" type="text"
            class="wpfs-input-group-form-control" placeholder="<?php $view->coupon()->placeholder(); ?>">
        <div class="wpfs-input-group-append">
            <a class="wpfs-input-group-link wpfs-coupon-redeem-link" href=""><span>
                    <?php /* translators: Button label for redeeming a coupon */
                    esc_html_e('Redeem', 'wp-full-stripe'); ?>
                </span></a>
        </div>
    </div>
</div>