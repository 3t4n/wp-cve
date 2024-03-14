<?php global $arformsmain; ?>
<div class="page_main_div">
    <nav class="arflite_growth_top">
        <a class="nav_logo" href="<?php echo admin_url( 'admin.php?page=ARForms' ); //phpcs:ignore ?>">
            <img class="arflite_growth_top_logo" src="<?php echo ARFLITEIMAGESURL . '/arform-header-logo.png' //phpcs:ignore ?>"> 
        </a>
        <div class="nav_content"><div class="arf_growth">Growth Plugins</div></div>
    
    </nav>
    <div class="arflite_growth_bottom_main">
        <div class="arflite_growth_bottom_first_content">
            <?php if( !$arformsmain->arforms_is_pro_active() ): ?>
                <h1>ARForms Pro</h1>
                <h2 class="bottom_head_text_1">All-in-one WordPress Form Builder Plugin to Create All Type of Forms</h2>
                <div class="first_description">
                    <h3>Upgrade to ARForms Pro today. Using this enhanced version, you'll have the tools to create dynamic, interactive forms that
                    stand out. This comprehensive package includes a wide range of features, such as seamless integration with Payment
                    Gateways, the ability to create Popup Forms, advanced Math Logic, a suite of Marketing Tools, and so much more. You're just a
                    few simple steps away from unlocking the full potential of ARForms Pro, and transforming your website into an engaging
                    platform that caters to your every need. Don't miss out on this opportunity!</h3>
                </div>

                <div class="arflite_growth_bottom_first_content_premium">
                    <div class="arflite_growth_bottom_first_content_premium_inner">
                        <div class="content1">
                            <img class="content1_img" src="<?php echo ARFLITEIMAGESURL . '/cs-lifetime-update.png' //phpcs:ignore ?>">
                        </div>
                        <div class="content2">
                            <label>One Time Fees for</label>
                            <div class="lable2"><label >Lifetime Updates</label></div>
                        </div>
                        <div class="content3">
                            <img class="content2_img" src="<?php echo ARFLITEIMAGESURL . '/cs-lifetime-premium-addon.png' //phpcs:ignore ?>">
                        </div>
                        <div class="content4">
                            <label>17 Premium Addons</label>
                            <div class="lable2"><label >Completely Free!</label></div>
                        </div>
                    </div>
                </div>

                <div class="arflite_growth_bottom_second_main_content">
                    <div class="arflite_growth_bottom_second_main_content_inner">
                        <div class="arflite_growth_bottom_second_main_content_heading">
                            <span class="arf-page-heading-highlight"> Premium </span> Features Highlight
                        </div>
                        <div class="arf-featurelist-cls">
                            <ul class="arf-feature-list-cls">
                                <li class="arf-feature-list-li"> Real-time Form Editor </li>
                                <li class="arf-feature-list-li"> 33+ Premium Addons Support </li>
                                <li class="arf-feature-list-li"> 30+ Form Elements Support </li>
                                <li class="arf-feature-list-li"> Conditional + Math Logic </li>
                                <li class="arf-feature-list-li"> Multi-language & RTL support </li>
                                <li class="arf-feature-list-li"> Multi-step Survey Forms </li>
                                <li class="arf-feature-list-li"> 6 Months of Premium Support </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="arflite_pro_upgrade_button_wrapper">
                    <button onclick="arf_upgrade_to_premium()" class="upgrade_button">Upgrade to Premium</button>
                </div>

                <img class="page_break_img" src="<?php echo ARFLITEIMAGESURL . '/cs-lifetime-section-divider.png' //phpcs:ignore ?>">
            
            <?php endif; ?>

            <div class="plugin_details_main <?php echo ( $arformsmain->arforms_is_pro_active() ) ? 'arforms_pro_active' : ''; ?>">
                <div class="plugin_details_main_heading">
                        <img src="<?php echo ARFLITEIMAGESURL . '/cs-lifetime-family-plugin-star.png' //phpcs:ignore ?>">
                        <label class="plugin_details_main_heading_content"> 
                            <label class="lable1">Our </label> 
                            <label class="lable2">Family WordPress Plugins</label>
                        </label>
                        <img src="<?php echo ARFLITEIMAGESURL . '/cs-lifetime-family-plugin-star.png' //phpcs:ignore ?>">
                </div>
                <div class="plugin_details_main_description">
                    You will get the same user-friendly experience throughout all of our plugins. Enjoy single-window 24/7 support for all our plugins. All of our plugins are compatible with each other.
                </div>
                <div class="plugin_cards">

                        <!-- booking press card -->
                        <div class="card1">
                            <div class="logo"> <img src="<?php echo ARFLITEIMAGESURL . '/bookingpress-logo.png' //phpcs:ignore ?>"></div>
                            <div class="content">
                                <div class="card_heading">
                                    <label class="bookigpress_heading"><label class="lable1">BookingPress</label><label class="lable2"><b> - WordPress Booking Plugin</b></label></label>
                                </div>
                                <div class="card_description">
                                    Imagine a WordPress BookingPress Plugin that's remarkably user-friendly, equipped with an extensive feature set, excelling in performance, and featuring a sleek modern interface. It distinguishes itself as a superior option, surpassing even the most popular Appointment Booking plugins available.
                                </div>
                                <div class="key_features">
                                    <div class="key_features_heading"><b>Key Features:</b></div>
                                    <ul class="arf-feature-list-cls-plugin-dec">
                                        <li class="arf-feature-list-li-plugin"> Great UI And UX </li>
                                        <li class="arf-feature-list-li-plugin"> Interactive booking wizard </li>
                                        <li class="arf-feature-list-li-plugin"> Online Payment Gateways </li>
                                        <li class="arf-feature-list-li-plugin"> Offline Payment </li>
                                        <li class="arf-feature-list-li-plugin"> Built-in Spam Facility </li>
                                        <li class="arf-feature-list-li-plugin"> Custom Email Notifications </li>
                                    </ul>
                                </div>

                                <div class="card_last_section">
                                        <a href="https://wordpress.org/plugins/bookingpress-appointment-booking/" target="_blank" class="learn_more_booking_press">Learn More</a>  
                                        <label class="second_a">
                                            <input type="hidden" name="arf_install_booking_press_nonce" id="arf_install_booking_press_nonce" value="<?php echo wp_create_nonce("arf_install_booking_press_nonce"); //phpcs:ignore ?>">
                                            <?php 
                                                if ( (is_plugin_active('bookingpress-appointment-booking/bookingpress-appointment-booking')) || file_exists( WP_PLUGIN_DIR . '/bookingpress-appointment-booking/bookingpress-appointment-booking.php')  ) {

                                                ?> <button class="arf_install_booking_press_installed">Installed</button> <?php
                                                }
                                                else
                                                {
                                                    ?> <button class="arf_install_booking_press">Install</button> <?php
                                                }
                                            ?>
                                        </label>             
                                                
                                </div>
                                <div class="position_of__loader"><span class="load_event_img" id="load_event_bookingpress_id" ></div>
                            </div>
                        </div>

                    <!-- armember card -->
                    <div class="card1">
                            <div class="logo"> <img src="<?php echo ARFLITEIMAGESURL . '/armember-logo.png' //phpcs:ignore ?>"></div>
                            <div class="content">
                                <div class="card_heading">
                                    <label class="armember_heading"><label class="lable1">ARMember</label><label class="lable2"><b> -  WordPress Membership Plugin</b></label></label>
                                </div>
                                <div class="card_description">
                                    Can you imagine a WordPress Membership Plugin that is ridiculously easy to operate, offers a wide range of features, excels in performance, and boasts a modern user interface? It's very different and much better than even the most popular membership plugins available here.
                                </div>
                                <div class="key_features">
                                    <div class="key_features_heading"><b>Key Features:</b></div>
                                    <ul class="arf-feature-list-cls-plugin-dec">
                                        <li class="arf-feature-list-li-plugin"> Membership Setup Wizard </li>
                                        <li class="arf-feature-list-li-plugin"> Email Notification Templates </li>
                                        <li class="arf-feature-list-li-plugin"> Unlimited Membership Levels </li>
                                        <li class="arf-feature-list-li-plugin"> Live Form Editor </li>
                                        <li class="arf-feature-list-li-plugin"> Create Free & Paid Memberships </li>
                                        <li class="arf-feature-list-li-plugin"> Captcha Free Anti-spam Facility </li>
                                    </ul>
                                </div>

                                <div class="card_last_section">
                                        <a href="https://wordpress.org/plugins/armember-membership/" target="_blank" class="learn_more_armember">Learn More</a>
                                        <label class="second_button">
                                            <input type="hidden" name="arf_install_armember_nonce" id="arf_install_armember_nonce" value="<?php echo wp_create_nonce("arf_install_armember_nonce"); //phpcs:ignore ?>">
                                            <?php
                                                if ( (is_plugin_active('armember-membership/armember-membership.php')) || file_exists( WP_PLUGIN_DIR . '/armember-membership/armember-membership.php')  ) {
                                                    ?>
                                                        <button disabled="is_disabled" class="arf_install_armember_installed">Installed</button> 
                                                    <?php
                                                }
                                                else
                                                {
                                            ?>
                                            <button class="arf_install_armember">Install</button> 
                                            <?php }?>
                                        </label>      
                                                                
                                </div>
                                <div class="position_of__loader"><span class="load_event_img" id="load_event_armember_id" ></div>
                            </div>
                        </div>

                        <!-- arprice card -->
                        <div class="card1">
                            <div class="logo"> <img src="<?php echo ARFLITEIMAGESURL . '/arprice-logo.png' //phpcs:ignore ?>"></div>
                            <div class="content">
                                <div class="card_heading">
                                    <label class="arprice_heading"><label class="lable1">ARPrice</label><label class="lable2"><b> - WordPress Pricing Table Plugin</b></label></label>
                                </div>
                                <div class="card_description">
                                    ARPrice is a WordPress pricing table plugin that enables you to effortlessly craft responsive pricing tables and plan comparison tables. With its powerful and flexible real-time editor, you can swiftly design pricing tables, using multiple templates, to suit various WordPress themes.
                                </div>
                                <div class="key_features">
                                    <div class="key_features_heading"><b>Key Features:</b></div>
                                    <ul class="arf-feature-list-cls-plugin-dec">
                                        <li class="arf-feature-list-li-plugin"> Real-time Pricing Table Editor </li>
                                        <li class="arf-feature-list-li-plugin"> Unlimited Color Options </li>
                                        <li class="arf-feature-list-li-plugin"> Create Team Showcases </li>
                                        <li class="arf-feature-list-li-plugin"> Translation Ready </li>
                                        <li class="arf-feature-list-li-plugin"> Responsive Pricing Tables </li>
                                        <li class="arf-feature-list-li-plugin"> Multi-Site Compatible </li>
                                    </ul>
                                </div>

                                <div class="card_last_section">
                                    <a href="https://wordpress.org/plugins/arprice-responsive-pricing-table/" target="_blank" class="learn_more_arprice">Learn More</a>
                                        <input type="hidden" name="arf_install_arprice_nonce" id="arf_install_arprice_nonce" value="<?php echo wp_create_nonce('arf_install_arprice_nonce'); //phpcs:ignore ?>">
                                        <label class="second_button">
                                            <?php
                                                if ((is_plugin_active('arprice-responsive-pricing-table/arprice-responsive-pricing-table.php')) || file_exists( WP_PLUGIN_DIR . '/arprice-responsive-pricing-table/arprice-responsive-pricing-table.php')  ) {

                                                    ?><button disabled="is_disabled" class="arf_install_arprice_installed">Installed</button> <?php
                                                    }
                                                else
                                                {
                                            ?>
                                            <button class="arf_install_arprice">Install</button> 
                                            <?php  } ?>
                                        </label>    

                                </div>
                                <div class="position_of__loader"><span class="load_event_img" id="load_event_arprice_id" ></div>
                            </div>

                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php do_action( 'arforms_quick_help_links', 'cross_selling_page' ); ?>