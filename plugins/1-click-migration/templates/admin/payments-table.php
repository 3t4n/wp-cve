<div id="ocm-paypal-div">
  <h2>Payment</h2>
  <ul>
    <li class="ocm-payment-col ocm-float-left">
      <div class="ocm-payment-instructions-col">
        <p>
          This fee helps us with our hosting and development costs.
          Once paid you will have 7 days of unlimited migrations when using the same restore URL and email address.
          If you are having problems with the payment or if you are looking for bulk migration pricing please contact us via <a target="_blank" href="https://1clickmigration.com/contact-us/">this Contact Form</a>. We offer a 100% money back guarantee if we cant get your site migrated.
        </p>
        <a class="button button-primary button-large ocm-button ocm-contact-us" href="https://1clickmigration.com/contact-us/">Contact Us</a>
      </div>
    </li>
    <li  class="ocm-payment-col ocm-float-right">
      <div class="ocm-payment-tb-col">
        <table id="ocm-payment-table">
          <tr>
            <th colspan = "2">Description</td>
            <th>Amount</td>
          </tr>
          <tr>
           <td colspan = "2">Migration of <a class="ocm-blue-text" href = "<?php echo esc_url(get_site_url()); ?>"><?php echo esc_url(get_site_url()); ?></td>
           <td id="ocm-api-price"></td>
          </tr>
          <tr id="ocm_migration_code" class="ocm-hide-mc-row">
            <td><h3>Migration Code<span></span></h3>
            </td>
          </tr>
          <tr id="ocm_migration_code_row">
              <td colspan = "2">
                <ul class="ocm-coupon-row">
                  <li class="ocm-coupon-code-col">
                    <label for = "ocm-coupon-code">Migration Code</label>
                  </li>
                  <li class="ocm-coupon-input-col">
                    <span class="ocm-settings-coupon-icon"></span>
                    <input type = 'text' name='ocm-coupon-code' id = 'ocm-coupon-code'/>

                  </li>
                </ul>

                </td>
                <td>
                  <div id='ocm-discount-response'></div>
                  <button type = "submit" class = "button button-primary button-large" id = "ocm-coupon-button">Apply</button>
                </td>

          </tr>
          <tr class="ocm-coupon-total-row">
            <td colspan = "2" class="ocm-blue-text ocm-coupon-total">Total:</td>
            <td id = "ocm-coupon-amount"></td>
          </tr>
        </table>
        <div id='ocm-paypal-button'></div>
      </div>
    </li>
  </ul>
</div>
