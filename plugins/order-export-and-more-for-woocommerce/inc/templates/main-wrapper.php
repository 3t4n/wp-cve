<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><div style="margin-right: 15px; padding-top: 20px;">


    <?php JEMEXP_lite::wp_kses_wf($errorMessage); ?>
    <h2><?php echo esc_attr__('JEM Order Export & More', 'order-export-and-more-for-woocommerce'); ?></h2>
    <?php JEMEXP_lite::wp_kses_wf($this->print_admin_messages()); ?>

    <!-- This is a common Modal that anybody can use -->
    <div id="jem-common-modal" class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="commonModal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="display: none">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4><?php esc_attr_e('HEADER TEXT', 'order-export-and-more-for-woocommerce'); ?></h4>
                </div>
                <div class="modal-body">
                    <?php esc_attr_e('The body is here', 'order-export-and-more-for-woocommerce'); ?>
                </div>

            </div>
        </div>
    </div>


    <!-- success Modal HTML -->
    <div id="jem-success-modal" class="modal fade">
        <div class="modal-dialog modal-success">
            <div class="modal-content">
                <div id="jem-success-modal-header" class="modal-header">
                    <div class="icon-box">
                        <span id="jem-success-modal-icon" class="fa fa-check" aria-hidden="true" style="font-size:3em;"></span>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <h4><?php esc_attr_e('HEADER TEXT', 'order-export-and-more-for-woocommerce'); ?></h4>
                    <p><?php esc_attr_e('MSG', 'order-export-and-more-for-woocommerce'); ?></p>
                    <button class="btn btn-success" data-bs-dismiss="modal"><span>Continue</span> <span class="fa fa-arrow-right" aria-hidden="true"></span></button>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link <?php esc_attr_e($export_active); ?>" role="presentation" href="<?php esc_attr_e($adminPageURL); ?>&tab=export"><?php echo __('Export Data', 'order-export-and-more-for-woocommerce'); ?></a></li>
        <li class="nav-item"><a class="nav-link <?php esc_attr_e($settings_active); ?>" role="presentation" href="<?php esc_attr_e($adminPageURL); ?>&tab=settings"><?php echo __('Settings', 'order-export-and-more-for-woocommerce'); ?></a></li>
        <li class="nav-item"><a class="nav-link <?php esc_attr_e($schedule_active); ?>" role="presentation" href="<?php esc_attr_e($adminPageURL); ?>&tab=schedule"><?php echo __('Scheduled Exports', 'order-export-and-more-for-woocommerce'); ?></a></li>
        <li class="nav-item nav-item-pro"><a data-pro-feature="tab" class="nav-link open-jem-pro-dialog" role="presentation" href="#"><?php echo __('PRO', 'order-export-and-more-for-woocommerce'); ?></a></li>
    </ul>

    <div class="jemxp-main-content">
        <div id="msg_box">
        </div>
        <?php JEMEXP_lite::wp_kses_wf($theContent); ?>
    </div>
    <p class="pro-ad-bottom"><a href="#" class="open-jem-pro-dialog" data-pro-feature="footer-ad">Get Order Export PRO with 45% OFF!</a></p>
  </div>

  <div id="jem-export-modal-pro" class="modal fade show" tabindex="-1" aria-modal="true" aria-labelledby="Export PRO">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Get Order Export PRO with 45% OFF</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
            </div>
            <div class="modal-body text-center">
            <table id="jem_pricing">
            <tr>
              <td><b>1 Site License</b></td>
              <td><b>5 Sites License</b></td>
              <td><b>100 Sites License Lifetime</b></td>
            </tr>
            <tr>
              <td><del>$49</del> $39 <small>/year</small></td>
              <td><del>$89</del> $69 <small>/year</small></td>
              <td><del>$299</del> $169 <small>/lifetime deal</small></td>
            </tr>
            <tr><td colspan="3"><br></td></tr>
            <tr class="jem-features">
              <td><i class="fa fa-check-circle"></i> all plugin features<br>
              <i class="fa fa-check-circle"></i> premium support</td>
              <td><i class="fa fa-check-circle"></i> all plugin features<br>
              <i class="fa fa-check-circle"></i> only $13 per license<br>
              <i class="fa fa-check-circle"></i> premium support</td>
              <td><i class="fa fa-check-circle"></i> all plugin features<br>
              <i class="fa fa-check-circle"></i> only $1.6 per license<br>
              <i class="fa fa-check-circle"></i> premium support<br>
              <i class="fa fa-check-circle"></i> whitelabel mode</td>
            </tr>
            <tr class="jem-prices">
              <td><a class="button-primary button-buy" href="https://jem-products.com/buy/export/?ref=pricing-table&product=single-launch" data-href-org="https://jem-products.com/buy/export/?ref=pricing-table&product=single-launch" target="_blank">BUY NOW with 20% OFF</a><br>
              or pay only <a class="button-buy" href="https://jem-products.com/buy/export/?ref=pricing-table&product=single-monthly" data-href-org="https://jem-products.com/buy/export/?ref=pricing-table&product=single-monthly">$6.99 a month</a></td>
              <td><a class="button-primary button-buy" href="https://jem-products.com/buy/export/?ref=pricing-table&product=team-launch" data-href-org="https://jem-products.com/buy/export/?ref=pricing-table&product=team-launch" target="_blank">BUY NOW with 22% OFF</a></td>
              <td><a class="button-primary button-buy" href="https://jem-products.com/buy/export/?ref=pricing-table&product=agency-launch" data-href-org="https://jem-products.com/buy/export/?ref=pricing-table&product=agency-launch" target="_blank">BUY NOW with 45% OFF</a></td>
            </tr>
            <tr><td colspan="3"><hr>
            <p><br><i><b>100% No-Risk Money Back Guarantee!</b> If you don't like the plugin over the next 7 days, we will happily refund your money. No questions asked! Payments are processed by our merchant of records - <a href="https://paddle.com/" target="_blank">Paddle</a>.</i></p>
          </td></tr>
          </table>
            </div>
        </div>
    </div>
</div>
