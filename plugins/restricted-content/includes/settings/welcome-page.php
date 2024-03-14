
<div class="rsc-welcome-screen">

    <div class="rsc-free-features">

        <span><?php
            if (restrict_fs()->can_use_premium_code()) {
                $welcome_widgets_copy = rsc_welcome_widget_copy('premium');
                _e('Restrict PRO features', 'rsc');
            } else {
                $welcome_widgets_copy = rsc_welcome_widget_copy('free');
                _e('Restrict free plan features', 'rsc');
            }
            ?>
        </span>

        <div class="rsc-sections-wrap">

            <div class="rsc-half-section">

                <div class="rsc-welcome-img">
                    <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/post-content@2x.png" width="298" />
                </div><!-- .rsc-welcome-img -->


                <div class="rsc-welcome-text">
                    <div class="rsc-already-featured"></div>
                    <div class="rsc-rouned-box"></div>
                    <h3><?php echo rsc_esc_html( $welcome_widgets_copy['content']['title'] ); ?></h3>
                    <p><?php echo rsc_esc_html( $welcome_widgets_copy['content']['description'] ); ?></p>
                </div>


            </div><!-- .rsc-half-section -->

            <div class="rsc-half-section">

                <div class="rsc-welcome-img">
                    <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/integrations@2x.png" width="290" />
                </div><!-- .rsc-welcome-img -->

                <div class="rsc-welcome-text">
                    <div class="rsc-already-featured"></div>
                    <div class="rsc-rouned-box"></div>
                    <h3><?php echo rsc_esc_html( $welcome_widgets_copy['integrations']['title'] ); ?></h3>
                    <p><?php echo rsc_esc_html( $welcome_widgets_copy['integrations']['description'] ); ?></p>
                </div>

            </div><!-- .rsc-half-section -->

        </div><!--.rsc-sections-wrap-->

    </div><!-- .rsc-free-features -->

    <?php if (!restrict_fs()->can_use_premium_code()) { ?>
        <div class="rsc-space-wrap">
            <div class="rsc-spaces"></div>
        </div>
    <?php } ?>

    <div class="rsc-premium-section">

        <div class="rsc-premium-features">
            <?php if (!restrict_fs()->can_use_premium_code()) { ?>
                <span><?php _e('Upgrade to the premium version and get the following features', 'rsc'); ?></span>

                <a href="https://restrict.io/pricing/" target="_blank" class="button-primary rsc-premium-button"><?php _e('GO PREMIUM', 'rsc'); ?></a>
            <?php } ?>

            <div class="rsc-sections-wrap">

                <div class="rsc-half-section">

                    <div class="rsc-welcome-img">
                        <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/restrict-part@2x.png" width="280"/>
                    </div><!-- .rsc-welcome-img -->

                    <div class="rsc-welcome-text">

                        <?php rsc_premium_vs_free_button_widget_welcome(); ?>

                        <div class="rsc-rouned-box"></div>
                        <h3><?php echo rsc_esc_html( $welcome_widgets_copy['shortcodes']['title'] ); ?></h3>
                        <p><?php echo rsc_esc_html( $welcome_widgets_copy['shortcodes']['description'] ); ?></p>
                    </div>

                </div><!-- .rsc-half-section -->

                <div class="rsc-half-section">

                    <div class="rsc-welcome-img">
                        <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/restrict-categories@2x.png" width="303" />
                    </div><!-- .rsc-welcome-img -->

                    <div class="rsc-welcome-text">
                        <?php rsc_premium_vs_free_button_widget_welcome(); ?>
                        <div class="rsc-rouned-box"></div>
                        <h3><?php echo rsc_esc_html( $welcome_widgets_copy['category']['title'] ); ?></h3>
                        <p><?php echo rsc_esc_html( $welcome_widgets_copy['category']['description'] ); ?></p>
                    </div>

                </div><!-- .rsc-half-section -->

                <div class="rsc-half-section">

                    <div class="rsc-welcome-img">
                        <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/restrict-widget@2x.png" width="267" />
                    </div><!-- .rsc-welcome-img -->

                    <div class="rsc-welcome-text">
                        <?php rsc_premium_vs_free_button_widget_welcome(); ?>
                        <div class="rsc-rouned-box"></div>
                        <h3><?php echo rsc_esc_html( $welcome_widgets_copy['widgets']['title'] ); ?></h3>
                        <p><?php echo rsc_esc_html( $welcome_widgets_copy['widgets']['description'] ); ?></p>
                    </div>

                </div><!-- .rsc-half-section -->

                <div class="rsc-half-section">

                    <div class="rsc-welcome-img">
                        <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/restrict-login@2x.png" width="271" />
                    </div><!-- .rsc-welcome-img -->

                    <div class="rsc-welcome-text">
                        <?php rsc_premium_vs_free_button_widget_welcome(); ?>
                        <div class="rsc-rouned-box"></div>
                        <h3><?php echo rsc_esc_html( $welcome_widgets_copy['login']['title'] ); ?></h3>
                        <p><?php echo rsc_esc_html( $welcome_widgets_copy['login']['description'] ); ?></p>

                    </div><!-- .rsc-welcome-text -->

                </div><!--.rsc-half-section-->


                <div class="rc-clear"></div>

                <div class="rsc-half-section">

                    <div class="rsc-welcome-img">
                        <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/restrict-siteshield@2x.png" width="271" />
                    </div><!-- .rsc-welcome-img -->

                    <div class="rsc-welcome-text">
                        <?php rsc_premium_vs_free_button_widget_welcome(); ?>
                        <div class="rsc-rouned-box"></div>
                        <h3><?php echo rsc_esc_html( $welcome_widgets_copy['site_shield']['title'] ); ?></h3>
                        <p><?php echo rsc_esc_html( $welcome_widgets_copy['site_shield']['description'] ); ?></p>

                    </div><!-- .rsc-welcome-text -->

                </div><!--.rsc-half-section-->
                <div class="rsc-half-section">

                    <div class="rsc-welcome-img">
                        <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/restrict-menu@2x.png" width="267" />
                    </div><!-- .rsc-welcome-img -->

                    <div class="rsc-welcome-text">
                        <?php rsc_premium_vs_free_button_widget_welcome(); ?>
                        <div class="rsc-rouned-box"></div>
                        <h3><?php echo rsc_esc_html( $welcome_widgets_copy['hide_show_menu']['title'] ); ?></h3>
                        <p><?php echo rsc_esc_html( $welcome_widgets_copy['hide_show_menu']['description'] ); ?></p>
                    </div>

                </div><!-- .rsc-half-section -->

                <div class="rc-clear"></div>

                <div class="rsc-half-section">

                    <div class="rsc-welcome-img">
                        <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/restrict-bots-crawlers.svg" width="300" />
                    </div><!-- .rsc-welcome-img -->

                    <div class="rsc-welcome-text">
                        <?php rsc_premium_vs_free_button_widget_welcome(); ?>
                        <div class="rsc-rouned-box"></div>
                        <h3><?php echo rsc_esc_html( $welcome_widgets_copy['bots_crawlers']['title'] ); ?></h3>
                        <p><?php echo rsc_esc_html( $welcome_widgets_copy['bots_crawlers']['description'] ); ?></p>
                    </div>

                </div><!-- .rsc-half-section -->

            </div><!-- .rsc-free-features -->


        </div><!-- .rsc-premium-section -->


    </div><!-- rsc-welvome-screen -->
