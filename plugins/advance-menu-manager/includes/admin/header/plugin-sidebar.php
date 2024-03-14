<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$review_url = 'https://wordpress.org/plugins/advance-menu-manager/#reviews';
$plugin_at = 'WP.org';
$changelog_url = 'https://wordpress.org/plugins/advance-menu-manager/#developers';
?>
</div>
    <div class="dots-settings-right-side">
        <div class="dots-seperator">
            <button class="toggleSidebar" title="toogle sidebar">
                <span class="dashicons dashicons-arrow-right-alt2"></span>
            </button>
        </div>
        <div class="dotstore_plugin_sidebar">
            <?php 
?>
                <div class="dotstore-sidebar-section dotstore-upgrade-to-pro">
                    <div class="dotstore-important-link-heading">
                        <span class="heading-text">
                            <?php 
esc_html_e( 'Upgrade to Advance Menu Pro', 'advance-menu-manager' );
?>
                        </span>
                    </div>
                    <div class="dotstore-important-link-content">
                        <ul class="dotstore-pro-list">
                            <li><?php 
esc_html_e( 'Track, compare, restore all your changes with Menu Revisions', 'advance-menu-manager' );
?></li>
                            <li><?php 
esc_html_e( 'Advanced interface to add menu item in your menu', 'advance-menu-manager' );
?></li>
                            <li><?php 
esc_html_e( 'Put the menu anywhere on your site/blog with short-code', 'advance-menu-manager' );
?></li>
                            <li><?php 
esc_html_e( 'Menu Lock Functionality. you can lock particular Menu for other users', 'advance-menu-manager' );
?></li>
                            <li><?php 
esc_html_e( 'Create new pages within the menu without leaving your add menu item screen.', 'advance-menu-manager' );
?></li>
                            <li><?php 
esc_html_e( 'Edit whole page/post from the menu.', 'advance-menu-manager' );
?></li>
                            <li><?php 
esc_html_e( 'View page / post attributes withing menu.', 'advance-menu-manager' );
?></li>
                        </ul>
                        <div class="dotstore-pro-button">
                            <a class="button" target="_blank" href="<?php 
echo  esc_url( ammp_fs()->get_upgrade_url() ) ;
?>">
                                <?php 
esc_html_e( 'Get Premium Now »', 'advance-menu-manager' );
?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
?>
            
            <div class="dotstore-sidebar-section">
                <div class="content_box">
                    <h3><?php 
esc_html_e( 'Like This Plugin?', 'advance-menu-manager' );
?></h3>
                    <div class="et-star-rating">
                        <input type="radio" id="5-stars" name="rating" value="5" />
                        <label for="5-stars" class="star"></label>
                        <input type="radio" id="4-stars" name="rating" value="4" />
                        <label for="4-stars" class="star"></label>
                        <input type="radio" id="3-stars" name="rating" value="3" />
                        <label for="3-stars" class="star"></label>
                        <input type="radio" id="2-stars" name="rating" value="2" />
                        <label for="2-stars" class="star"></label>
                        <input type="radio" id="1-star" name="rating" value="1" />
                        <label for="1-star" class="star"></label>
                        <input type="hidden" id="et-review-url" value="<?php 
echo  esc_url( $review_url ) ;
?>">
                    </div>
                    <p><?php 
esc_html_e( 'Your Review is very important to us as it helps us to grow more.', 'advance-menu-manager' );
?></p>
                </div>
            </div>
            
            <div class="dotstore-sidebar-section">
                <div class="dotstore-important-link-heading">
                    <span class="dashicons dashicons-editor-kitchensink"></span>
                    <span class="heading-text"><?php 
esc_html_e( 'Changelog', 'advance-menu-manager' );
?></span>
                </div>
                <div class="dotstore-important-link-content">
                    <p><?php 
esc_html_e( 'We improvise our products on a regular basis to deliver the best results to customer satisfaction.', 'advance-menu-manager' );
?></p>
                    <a target="_blank" href="<?php 
echo  esc_url( $changelog_url ) ;
?>"><?php 
esc_html_e( 'Visit Here »', 'advance-menu-manager' );
?></a>
                </div>
            </div>

            <div class="dotstore-important-link dotstore-sidebar-section">
                <div class="dotstore-important-link-heading">
                    <span class="dashicons dashicons-plugins-checked"></span>
                    <span class="heading-text"><?php 
esc_html_e( 'Our Popular Plugins', 'advance-menu-manager' );
?></span>
                </div>
                <div class="video-detail important-link">
                    <ul>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'admin/images/thedotstore-images/popular-plugins/Advanced-Flat-Rate-Shipping-Method.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Advanced Flat Rate Shipping Method', 'advance-menu-manager' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/flat-rate-shipping-plugin-for-woocommerce/" ) ;
?>">
                                <?php 
esc_html_e( 'Advanced Flat Rate Shipping Method', 'advance-menu-manager' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'admin/images/thedotstore-images/popular-plugins/Conditional-Product-Fees-For-WooCommerce-Checkout.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Conditional Product Fees For WooCommerce Checkout', 'advance-menu-manager' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/product/woocommerce-extra-fees-plugin/" ) ;
?>">
                                <?php 
esc_html_e( 'Extra Fees Plugin for WooCommerce', 'advance-menu-manager' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'admin/images/thedotstore-images/popular-plugins/hide-shipping.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Hide Shipping Method For WooCommerce', 'advance-menu-manager' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/hide-shipping-method-for-woocommerce/" ) ;
?>">
                                <?php 
esc_html_e( 'Hide Shipping Method For WooCommerce', 'advance-menu-manager' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'admin/images/thedotstore-images/popular-plugins/WooCommerce Conditional Discount Rules For Checkout.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Conditional Discount Rules For WooCommerce Checkout', 'advance-menu-manager' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-conditional-discount-rules-for-checkout/" ) ;
?>">
                                <?php 
esc_html_e( 'Conditional Discount Rules For WooCommerce Checkout', 'advance-menu-manager' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'admin/images/thedotstore-images/popular-plugins/WooCommerce-Blocker-Prevent-Fake-Orders.png' ) ;
?>" alt="<?php 
esc_attr_e( 'WooCommerce Blocker – Prevent Fake Orders', 'advance-menu-manager' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-anti-fraud" ) ;
?>">
                                <?php 
esc_html_e( 'WooCommerce Anti-Fraud', 'advance-menu-manager' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'admin/images/thedotstore-images/popular-plugins/Advanced-Product-Size-Charts-for-WooCommerce.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Product Size Charts Plugin For WooCommerce', 'advance-menu-manager' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-advanced-product-size-charts/" ) ;
?>">
                                <?php 
esc_html_e( 'Product Size Charts Plugin For WooCommerce', 'advance-menu-manager' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'admin/images/thedotstore-images/popular-plugins/wcbm-logo.png' ) ;
?>" alt="<?php 
esc_attr_e( 'WooCommerce Category Banner Management', 'advance-menu-manager' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/product/woocommerce-category-banner-management/" ) ;
?>">
                                <?php 
esc_html_e( 'WooCommerce Category Banner Management', 'advance-menu-manager' );
?>
                            </a>
                        </li>
                        <li>
                            <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'admin/images/thedotstore-images/popular-plugins/woo-product-att-logo.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Product Attachment For WooCommerce', 'advance-menu-manager' );
?>">
                            <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-product-attachment/" ) ;
?>">
                                <?php 
esc_html_e( 'Product Attachment For WooCommerce', 'advance-menu-manager' );
?>
                            </a>
                        </li>
                        </br>
                    </ul>
                </div>
                <div class="view-button">
                    <a class="view_button_dotstore" href="<?php 
echo  esc_url( "http://www.thedotstore.com/plugins/" ) ;
?>"  target="_blank"><?php 
esc_html_e( 'View All', 'advance-menu-manager' );
?></a>
                </div>
            </div>
            <div class="dotstore-sidebar-section">
                <div class="dotstore-important-link-heading">
                    <span class="dashicons dashicons-sos"></span>
                    <span class="heading-text"><?php 
esc_html_e( 'Five Star Support', 'advance-menu-manager' );
?></span>
                </div>
                <div class="dotstore-important-link-content">
                    <p><?php 
esc_html_e( 'Got a question? Get in touch with theDotstore developers. We are happy to help! ', 'advance-menu-manager' );
?></p>
                    <a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/support/' ) ;
?>"><?php 
esc_html_e( 'Submit a Ticket »', 'advance-menu-manager' );
?></a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>