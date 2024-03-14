<?php

if (!defined('WPINC')) {
    die;
}

?>

<div class="signals-tile" id="pro">
    <div class="signals-tile-body">
        <div class="signals-tile-title">Coming Soon &amp; Maintenance Mode PRO - Save time &amp; money when building pages</div>
        <p>PRO version of the plugin gives you access to numerous <a href="<?php echo esc_url(csmm_generate_web_link('pro-header', '/features/')); ?>" target="_blank">advanced features</a> including best-in-class SEO options, gallery of over a million images, 100+ themes, 10+ page modules, and refined access control options.</p>

        <div class="signals-section-content">
            <table id="pricing-table">
                <colgroup></colgroup>
                <colgroup></colgroup>
                <colgroup></colgroup>
                <tbody>
                    <tr>
                        <td>
                            <h3>Yearly<br>PRO License</h3>
                            <span>Pay only for the time you use the plugin</span>
                        </td>
                        <td>
                            <h3>Lifetime<br>PRO License</h3>
                            <span>For single site owners who like paying only once</span>
                            <div class="corner-ribbon">Most<br>Popular</div>
                        </td>
                        <td>
                            <h3>Lifetime<br>Agency License</h3>
                            <span>Best value for money</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Monthly / Yearly Payment</td>
                        <td>One Time Payment</td>
                        <td>One Time Payment</td>
                    </tr>
                    <tr>
                        <td>1 Personal or Client Site</td>
                        <td>1 Personal or Client Site</td>
                        <td>100 Client or Personal Sites (sites can be changed)</td>
                    </tr>
                    <tr>
                        <td>All Current &amp; Future Plugin Features</td>
                        <td>All Current &amp; Future Plugin Features</td>
                        <td>All Current &amp; Future Plugin Features</td>
                    </tr>
                    <tr>
                        <td>Advanced SEO Preview &amp; Anaylsis</td>
                        <td>Advanced SEO Preview &amp; Anaylsis</td>
                        <td>Advanced SEO Preview &amp; Anaylsis</td>
                    </tr>
                    <tr>
                        <td>2.5+ Million Hi-Resolution Images</td>
                        <td>2.5+ Million Hi-Resolution Images</td>
                        <td>2.5+ Million Hi-Resolution Images</td>
                    </tr>
                    <tr>
                        <td>95+ PRO Templates</td>
                        <td>95+ PRO Templates</td>
                        <td>95+ PRO Templates</td>
                    </tr>
                    <tr>
                        <td class="not-available">n/a</td>
                        <td class="not-available">n/a</td>
                        <td><b>95+ Extra Agency Templates = 190+ Templates</b></td>
                    </tr>
                    <tr>
                        <td>Dashboard for managing sites &amp; licenses</td>
                        <td>Dashboard for managing sites &amp; licenses</td>
                        <td>Dashboard for managing sites &amp; licenses</td>
                    </tr>
                    <tr>
                        <td class="not-available">n/a</td>
                        <td class="not-available">n/a</td>
                        <td><b>Remote sites stats</b></td>
                    </tr>
                    <tr>
                        <td class="not-available">n/a</td>
                        <td class="not-available">n/a</td>
                        <td><b>Full Rebranding Rights</b></td>
                    </tr>
                    <tr>
                        <td>1 Year/Month of Support &amp; Updates</td>
                        <td>Lifetime Support &amp; Updates</td>
                        <td>Lifetime Priority Support &amp; Updates</td>
                    </tr>


                    <tr>
                        <?php
                        $meta = csmm_get_meta();
                        $promo_delta = HOUR_IN_SECONDS;
                        $promo_delta2 = DAY_IN_SECONDS * 28;
                        $new_prices = true;

                        if ($new_prices) {
                          ?>
                          <td>
                              <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-pro-yearly/olduser/?monthly=true&plugin_info=CSMM+free" target="_blank">BUY NOW &RightArrow; 25% OFF<br><del>$7.99</del> $5.99<small> /month</small></a>
                              <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                  100% No-Risk 7 Days Money Back Guarantee</span>
                          </td>
                          <td>
                              <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-pro-lifetime/newprice/?wanted=0&plugin_info=CSMM+free" target="_blank">BUY NOW &RightArrow; 50% OFF<br><del>$79</del> $39</a>
                              <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                  100% No-Risk 7 Days Money Back Guarantee</span>
                          </td>
                          <td>
                              <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-agency-lifetime/newprices/?wanted=0&plugin_info=CSMM+free" target="_blank">BUY NOW &RightArrow; 50% OFF<br><del>$199</del> $99</a>
                              <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                  100% No-Risk 7 Days Money Back Guarantee</span>
                          </td>
                      <?php
                        } elseif ((time() - $meta['first_install_gmt']) < $promo_delta) {
                        ?>
                            <td>
                                <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-agency-lifetime/welcome/?wanted=0&plugin_info=CSMM+free" target="_blank">BUY NOW - $60 OFF<br><del>$199</del> $139<br><span style="font-weight: normal;">Discount ends in <span class="mm-countdown" data-endtime="<?php echo esc_attr($meta['first_install_gmt'] + $promo_delta); ?>" style="">59 min</span></span></a>
                                <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                    100% No-Risk 7 Days Money Back Guarantee</span>
                            </td>
                            <td>
                                <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-pro-lifetime/welcome/?wanted=0&plugin_info=CSMM+free" target="_blank">BUY NOW - 25% OFF<br><del>$79</del> $59<br><span style="font-weight: normal;">Discount ends in <span class="mm-countdown" data-endtime="<?php echo esc_attr($meta['first_install_gmt'] + $promo_delta); ?>" style="">59 min</span></span></a>
                                <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                    100% No-Risk 7 Days Money Back Guarantee</span>
                            </td>
                            <td>
                                <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-pro-yearly/welcome/?monthly=true&plugin_info=CSMM+free" target="_blank">BUY NOW - 25% OFF<br><del>$7.99</del> $5.99<small> /month</small><br><span style="font-weight: normal;">Discount ends in <span class="mm-countdown" data-endtime="<?php echo esc_attr($meta['first_install_gmt'] + $promo_delta); ?>" style="">59 min</span></span></a>
                                <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                    100% No-Risk 7 Days Money Back Guarantee</span>
                            </td>
                        <?php
                        } elseif ((time() - $meta['first_install_gmt']) > $promo_delta2) {
                        ?>
                            <td>
                                <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-agency-lifetime/olduser/?wanted=0&plugin_info=CSMM+free" target="_blank">BUY NOW - $60 OFF<br><del>$199</del> $139</a>
                                <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                    100% No-Risk 7 Days Money Back Guarantee</span>
                            </td>
                            <td>
                                <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-pro-lifetime/olduser/?wanted=0&plugin_info=CSMM+free" target="_blank">BUY NOW - 25% OFF<br><del>$79</del> $59</a>
                                <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                    100% No-Risk 7 Days Money Back Guarantee</span>
                            </td>
                            <td>
                                <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-pro-yearly/olduser/?monthly=true&plugin_info=CSMM+free" target="_blank">BUY NOW - 25% OFF<br><del>$7.99</del> $5.99<small> /month</small></a>
                                <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                    100% No-Risk 7 Days Money Back Guarantee</span>
                            </td>
                        <?php
                        } else {
                        ?>
                            <td>
                                <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-agency-lifetime/?wanted=0&plugin_info=CSMM+free" target="_blank">BUY
                                    NOW<br>$199</a>
                                <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                    100% No-Risk 7 Days Money Back Guarantee</span>
                            </td>
                            <td>
                                <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-pro-lifetime/?wanted=0&plugin_info=CSMM+free" target="_blank">BUY
                                    NOW<br>$79</a>
                                <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                    100% No-Risk 7 Days Money Back Guarantee</span>
                            </td>
                            <td>
                                <a data-gumroad-single-product="true" class="promo-button go-to-license-key" href="https://gum.co/csmm-pro-yearly/olduser/?monthly=true&plugin_info=CSMM+free" target="_blank">BUY NOW - 25% OFF<br><del>$7.99</del> $5.99<small> /month</small></a>
                                <span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment via Gumroad<br><span class="dashicons dashicons-yes"></span> Instant activation from WordPress admin<br><span class="dashicons dashicons-yes"></span>
                                    100% No-Risk 7 Days Money Back Guarantee</span>
                            </td>
                        <?php
                        }
                        ?>
                    </tr>
                </tbody>
            </table>

            <hr>

            <table id="features-table">
                <tr>
                    <td>
                        <div class="home-box"><span>150+ Pixel Perfect Themes</span>
                            <p>Professional, easily editable <a href="#themes" class="csmm-change-tab">themes</a> that fit and adjust to any brand will enable you
                                to build a page for your online or offline business in minutes. 4 new themes are added every month.</p>
                        </div>
                    </td>
                    <td>
                        <div class="home-box"><span>Over Two Million Premium HD Images</span>
                            <p>Are you still googling for images? Good ones are costly, and others are low-res with watermarks? With our image library, the only
                                thing you have to do is enter a search term and pick an image. Five seconds of work.</p>
                        </div>
                    </td>
                    <td>
                        <div class="home-box"><span>Advanced SEO Preview &amp; Analysis</span>
                            <p>Our SEO analytics tool provides actionable advice based on over 30 SEO signals analyzed on your page. With a bit of optimization,
                                you'll be on the first position in search results in no time.</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="home-box"><span>Drag &amp; Drop Builder that Doesn't Frustrate</span>
                            <p>Nobody has time to read a manual just to use a drag&amp;drop builder because it has 50% options that nobody needs. Our builder has
                                only the options you need. It's fast and dead simple to use!</p>
                        </div>
                    </td>
                    <td>
                        <div class="home-box"><span>Advanced Access Rules</span>
                            <p>Want to show off the new website to a client? But you know they are not very "technical" and need a simple solution? Just send them a
                                secret access link, and they can view the site while it's still in coming soon mode.</p>
                        </div>
                    </td>
                    <td>
                        <div class="home-box"><span>Connect any Email, CRM or Webinar System</span>
                            <p>Are you using a less popular email service provider, CRM or webinar platform that other plugins don't support? Well, we do support
                                them! Our universal autoresponder system works with any 3rd party service.</p>
                        </div>
                    </td>
                </tr>
            </table>

            <p class="below-pricing">Find out more about the <b>PRO features</b> on the plugin's site - <a href="<?php echo esc_url(csmm_generate_web_link('find-out-more')); ?>" target="_blank">comingsoonwp.com</a></p>

<hr id="pricing-table-above">

            <div class="signals-form-group" id="tab-pro">
                <?php
                global $csmm_lc;
                echo '<p>The License key is visible on the confirmation screen, right after purchasing. You can also find it in the confirmation email sent to the email address provided on purchase. Or use keys created with the <a href="https://dashboard.comingsoonwp.com/licenses/" target="_blank">license manager</a>.</p>
                    <p>If you don\'t have a license - <a class="scrollto" href="#pricing-table">purchase one now</a>. In case of problems with the license please <a href="' . esc_url(csmm_generate_web_link('pro-tab-license', '/contact/')) . '" target="_blank">contact support</a>.</p>';

                echo '<hr>';
                echo '<p><label for="csmm-license-key">License Key: </label><input class="regular-text" type="text" id="signals_csmm_license_key" value="' . ($csmm_lc->get_license('license_key') != 'keyless' ? esc_attr($csmm_lc->get_license('license_key')) : '') . '" placeholder="12345678-12345678-12345678-12345678">';

                echo '<br><label>Status: </label>';
                if ($csmm_lc->is_active()) {
                    $license_formatted = $csmm_lc->get_license_formatted();
                    echo '<b style="color: #66b317;">Active</b><br>
                    <label>Type: </label>' . esc_html($license_formatted['name_long']);
                    echo '<br><label>Valid: </label>' . esc_html($license_formatted['valid_until']);

                    echo '<p class="center">Thank you for purchasing Coming Soon &amp; Maintenance Mode PRO! <b>Your license has been verified and activated.</b> ';
                    echo '<br>To start using the PRO version, please follow these steps:</p>';
                    echo '<ol>';
                    echo '<li><a href="https://dashboard.comingsoonwp.com/pro-download/" target="_blank">Download</a> the latest version of the PRO plugin or grab it from the purchase confirmation email we sent you.</li>';
                    echo '<li>Go to <a href="' . admin_url('plugin-install.php') . '">Plugins - Add New - Upload Plugin</a> and upload the ZIP you just downloaded.</li>';
                    echo '<li>If asked to replace (overwrite) the free version - confirm it.</li>';
                    echo '<li>Activate the plugin.</li>';
                    echo '<li>That\'s it, no more steps.</li>';
                    echo '</ol><br>';
                } else { // not active
                    echo '<strong style="color: #ea1919;">Inactive</strong>';
                    if (!empty($csmm_lc->get_license('error'))) {
                        echo '<br><label>Error: </label>' . esc_html($csmm_lc->get_license('error'));
                    }
                }
                echo '</p>';

                echo '<p>';
                if ($csmm_lc->is_active()) {
                    echo '<a href="#" id="csmm_save_license" data-text-wait="Validating. Please wait." class="signals-btn">Save &amp; Revalidate License</a>';
                    echo '&nbsp; &nbsp;<a href="#" id="csmm_deactivate_license" data-text-wait="Deactivating. Please wait." class="signals-btn signals-btn-red">Deactivate License</a>';
                } else {
                    echo '<a href="#" id="csmm_save_license" data-text-wait="Activating. Please wait." class="signals-btn">Save &amp; Activate License</a>';
                    echo '&nbsp; &nbsp;<a href="#" data-text-wait="Activating. Please wait." class="signals-btn signals-btn-secondary" id="csmm_keyless_activation">Keyless Activation</a>';
                }
                echo '</p>';
                echo '<p class="mb0"><i>By attempting to activate the license you agree to share the following data with <a target="_blank" href="https://www.webfactoryltd.com/">WebFactory Ltd</a>: license key, site URL, site title, site WP version, and Coming Soon &amp; Maintenance Mode (free) version.</i>';
                echo '</p>';
                ?>
            </div>

        </div>
    </div>
</div><!-- #pro -->
