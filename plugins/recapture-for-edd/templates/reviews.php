<html>
  <head>
    <?php wp_head(); ?>
  </head>

  <body>
    <div id="review-collector">
      <div class="review-container">
        <h2 class="title">
          <a href="/">
            <img src="<?php echo esc_attr($user_logo) ?>" />
          </a>
        </h2>
        <div class="review-inner">
          <h3 class="error recapture-hide" id="error-message"></h3>
          <h2>
            <?php if (isset($order->first_name) && $order->first_name) { ?>
              <?php echo esc_html(RecaptureUtils::replace_tag($page_text->page_title, 'name', $order->first_name)) ?>
            <?php } ?>
          </h2>
          <?php
          // phpcs:ignore
          echo $page_text->page_body
          ?>
          <form method="post" id="review-form" class="review-form">
            <input type="hidden" name="action" value="recapture_submit_reviews" />
            <input type="hidden" name="author" value="<?php echo esc_attr($order->first_name) ?>" />
            <input type="hidden" name="email" value="<?php echo esc_attr($order->email) ?>" />
            <input type="hidden" name="external_id" value="<?php echo esc_attr($order->external_id) ?>" />
            <input type="hidden" name="order_id" value="<?php echo esc_attr($order->id) ?>" />
            <?php
            foreach ($order->products as $product) {
                ?>
                <fieldset class="review-item">
                <input type="hidden" class="skip" name="products[<?php echo esc_attr($product->external_id) ?>][skip]" value="0" />
                <input type="hidden" name="products[<?php echo esc_attr($product->external_id) ?>][sku]" value="<?php echo esc_attr($product->sku) ?>" />
                <input type="hidden" name="products[<?php echo esc_attr($product->external_id) ?>][name]" value="<?php echo esc_attr($product->name) ?>" />
                <input type="hidden" class="product_id" name="products[<?php echo esc_attr($product->external_id) ?>][product_id]" value="<?php echo esc_attr($product->product_id) ?>" />
                <input type="hidden" name="products[<?php echo esc_attr($product->external_id) ?>][external_id]" value="<?php echo esc_attr($product->external_id) ?>" />
                <?php wp_nonce_field('recapture_submit_reviews', 'recapture_submit_reviews'); ?>
                <p class="title">
                  <?php echo esc_html(RecaptureUtils::replace_tag($page_text->review_title, 'product_name', $product->name)) ?>
                  <a
                    href="javascript:;"
                    class="opt-out"
                    data-hide="<?php echo esc_attr($page_text->review_nothanks) ?>"
                    data-show="<?php echo esc_attr($page_text->review_showform) ?>"
                  >
                    <?php echo esc_html($page_text->review_nothanks) ?>
                  </a>
                </p>
                <div class="product-review-form">
                  <div>
                    <img src="<?php echo esc_attr($product->image) ?>" />
                  </div>
                  <div class="product-review">
                    <ul class="form-list">
                      <li class="ratings">
                        <label><?php echo esc_html($page_text->review_rating_label) ?></label>
                        <div class="rating">
                          <div class="stars">
                            <a class="star js-no-transition" href="javascript:;" data-id="rating_<?php echo esc_attr($product->external_id) ?>" data-val="1"></a>
                            <a class="star js-no-transition" href="javascript:;" data-id="rating_<?php echo esc_attr($product->external_id) ?>" data-val="2"></a>
                            <a class="star js-no-transition" href="javascript:;" data-id="rating_<?php echo esc_attr($product->external_id) ?>" data-val="3"></a>
                            <a class="star js-no-transition" href="javascript:;" data-id="rating_<?php echo esc_attr($product->external_id) ?>" data-val="4"></a>
                            <a class="star js-no-transition" href="javascript:;" data-id="rating_<?php echo esc_attr($product->external_id) ?>" data-val="5"></a>
                          </div>
                          <input
                            class="rating_value"
                            type="hidden"
                            id="rating_<?php echo esc_attr($product->external_id) ?>"
                            name="products[<?php echo esc_attr($product->external_id) ?>][rating]"
                            value=""
                          />
                        </div>
                      </li>
                      <? if ($has_title) { ?>
                      <li>
                        <div class="input-box">
                          <label><?php echo esc_html($page_text->review_title_label) ?></label>
                          <input
                            type="text"
                            class="input-text required-entry summary-field"
                            placeholder="<?php echo esc_attr($page_text->review_title_placeholer) ?>"
                            name="products[<?php echo esc_attr($product->external_id) ?>][title]"
                            id="summary_field_<?php echo esc_attr($product->external_id) ?>"
                          />
                        </div>
                      </li>
                      <? } ?>
                      <li>
                        <div class="input-box">
                          <label><?php echo esc_html($page_text->review_content_label) ?></label>
                          <textarea
                            cols="3"
                            rows="3"
                            class="required-entry review-field"
                            placeholder="<?php echo esc_attr($page_text->review_content_placeholder) ?>"
                            name="products[<?php echo esc_attr($product->external_id) ?>][detail]"
                          ></textarea>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>  
              </fieldset>
              <?php
            }
            ?>
            <div class="buttons-set">
              <button
                type="submit"
                title="<?php echo esc_attr($page_text->review_submit) ?>"
                class="button">
                <?php echo esc_html($page_text->review_submit) ?>
              </button>
            </div>
          </form>
        </div>
        <div class="success-inner recapture-hide">
          <h2><?php echo esc_html($page_text->success_title) ?></h2>
          <p><?php echo esc_html($page_text->success_body) ?></p>
          <p>
            <form action="/" method="GET">
              <input type="submit" value="<?php echo esc_html($page_text->success_submit) ?>" />
            </form>
          </p>
        </div>
      </div>
    </div>
    <?php wp_footer(); ?>
  </body>
</html>