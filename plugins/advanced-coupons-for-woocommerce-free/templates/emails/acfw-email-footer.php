<?php
/**
 * Advanced Coupon email footer.
 *
 * @version 4.5.4.2
 */
defined( 'ABSPATH' ) || exit;
?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <!-- End Content -->
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- End Body -->
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <!-- Footer -->
                                    <table border="0" cellpadding="10" cellspacing="0" width="100%" id="template_footer">
                                        <tr>
                                            <td valign="top">
                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td colspan="2" valign="middle" id="credit">
                                                            <?php if ( ! apply_filters( 'acfw_use_woocommerce_email_footer', false ) ) : ?>
                                                                <a style="text-decoration: none;" href="https://advancedcouponsplugin.com/powered-by/?utm_source=acfwf&utm_medium=sendcouponemail&utm_campaign=sendcouponpoweredby" target="_blank" rel="nofollow">
                                                                    <span style="font-size: 0.7em"><?php esc_html_e( 'Powered by', 'advanced-coupons-for-woocommerce-free' ); ?></span>
                                                                    <img style="width: 80px; margin-left: 5px;" src="<?php echo esc_url( \ACFWF()->Plugin_Constants->IMAGES_ROOT_URL . 'acfw-logo.png' ); ?>" alt="Advanced Coupons logo" />
                                                                </a>
                                                            <?php else : ?>
                                                                <?php echo wp_kses_post( wpautop( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Footer -->
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
            </tr>
        </table>
    </body>
</html>
