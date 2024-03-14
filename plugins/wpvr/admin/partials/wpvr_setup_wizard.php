<div class="wpvr-welcome">
    <section class="wpvr-welcome-hero">
        <div class="wpvr-container-1350">
            <div class="hero-content-wrapper">
                <div class="hero-content">
                    <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wpvr_item' ) ); ?>" class="backto-dashboard">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/arrow-return.svg' ?>" alt="arrow-return" />
                        <?php echo __('Back to WPVR Dashboard','wpvr') ?>
                    </a>
                    <h1 class="hero-title"><?php echo __('Thank you','wpvr') ?> <br/> <?php echo __('for Choosing','wpvr') ?> <span>WPVR!</span></h1>
                    <p><?php echo __('Follow the Guided Wizard and Learn How it Works.','wpvr') ?></p>
                    <form action="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" method="get">
                        <input type="text" name="post_type" value="wpvr_item" hidden>
                        <input type="text" name="wpvr-guide-tour" value="1" hidden>
                        <button type="submit" class="vr-welcome-btn hero-btn"><?php echo __('Take The Tour','wpvr') ?></button>
                    </form>
                </div>

                <div class="hero-video">
                    <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/vr-hero-video-shape.svg' ?>" alt="vr-hero-video-shape" class="hero-video-shape" />
                    
                    <div class="box-video">
                        <div class="bg-video">
                            <div class="bt-play">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/youtube-play-icon.svg' ?>" alt="youtube-play-icon" />
                            </div>
                        </div>

                        <div class="video-container">
                            <iframe loading="lazy" width="560" height="315" src="https://www.youtube.com/embed/SWsv-bplne8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="wpvr-features">
        <div class="wpvr-container-1350">
            <div class="section-title">
                <h2>The <span class="primary-color"> <?php echo __('Best WordPress Plugin','wpvr') ?></span> <?php echo __('for Virtual Tours, Viewing Panoramas, and 360 Degree Content','wpvr') ?></h2>
            </div>

            <div class="features-wrapper">
                <div class="single-feature">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/360-view.svg' ?>" alt="360-view" />
                    </span>
                    <h5 class="title"><?php echo __('360 Degree Panorama','wpvr') ?></h5>

                    <a href="https://rextheme.com/docs/wp-vr-create-simple-virtual-tour/" target="_blank" class="see-example">
                    <?php echo __('Learn more','wpvr') ?>
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/arrow-right-blue.svg' ?>" alt="arrow-right" />
                    </a>
                </div>
                
                <div class="single-feature">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/smartphone.svg' ?>" alt="smartphone" />
                    </span>
                    <h5 class="title"><?php echo __('Mobile Panorama','wpvr') ?></h5>

                    <a href="https://rextheme.com/docs/create-virtual-tour-with-mobile-phone-panorama/" target="_blank" class="see-example">
                        <?php echo __('Learn more','wpvr') ?>
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/arrow-right-blue.svg' ?>" alt="arrow-right" />
                    </a>
                </div>

                <div class="single-feature">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/3d-view.svg' ?>" alt="3d-view" />
                    </span>
                    <h5 class="title"><?php echo __('Cubemap Images','wpvr') ?></h5>

                    <a href="https://rextheme.com/docs/wp-vr/" class="see-example" target="_blank">
                        <?php echo __('Learn more','wpvr') ?>
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/arrow-right-blue.svg' ?>" alt="arrow-right" />
                    </a>
                </div>

                <div class="single-feature">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/360-camera.svg' ?>" alt="360-camera" />
                    </span>
                    <h5 class="title"><?php echo __('360 Degree Video','wpvr') ?></h5>

                    <a href="https://rextheme.com/docs/wp-vr-360-degree-interactive-video/" target="_blank" class="see-example">
                        <?php echo __('Learn more','wpvr') ?>
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/arrow-right-blue.svg' ?>" alt="arrow-right" />
                    </a>
                </div>

                <div class="single-feature">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/street-view.svg' ?>" alt="street-view" />
                    </span>
                    <h5 class="title"><?php echo __('Google Street View','wpvr') ?></h5>

                    <a href="https://rextheme.com/docs/wp-vr-embed-google-street-view-tour/" target="_blank" class="see-example">
                        <?php echo __('Learn more','wpvr') ?>
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/arrow-right-blue.svg' ?>" alt="arrow-right" />
                    </a>
                </div>

            </div>


           <?php if(!is_plugin_active('wpvr-pro/wpvr-pro.php')){?>
               <div class="features-cta">
                <h2 class="cta-title"><?php echo __('Unlock all these options by upgrading to Premium version','wpvr')?></h2>
                <a href="https://rextheme.com/wpvr/#pricing" class="vr-welcome-btn cta-btn" target="_blank"><?php echo __('Upgrade Now','wpvr')?></a>
            </div>
            <?php
            }
            ?>

        </div>
    </section>

    <section class="wpvr-feature-settings">
        <div class="wpvr-container-1350">
            <div class="section-title">
                <h2><?php echo __('Features That Can','wpvr')?> <span class="primary-color"><?php echo __('Hook','wpvr')?></span> <?php echo __('Your Viewers - And Make Them Take','wpvr')?> <span class="primary-color"><?php echo __('Decisions','wpvr')?></span></h2>
                <p><?php echo __('Personalize your virtual tours, in the way you want.','wpvr')?></p>
            </div>

            <div class="features-settings-wrapper">
                <div class="single-settings">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/scene-gallery.svg' ?>" alt="Panorama Scene Gallery" />
                    </span>
                    <h5 class="title"><?php echo __('Panorama Scene Gallery','wpvr')?></h5>
                </div>

                <div class="single-settings">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/woocommerce.svg' ?>" alt="Sell WooCommerce Products" />
                    </span>
                    <h5 class="title"><?php echo __('Sell WooCommerce Products','wpvr')?></h5>
                </div>

                <div class="single-settings">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/hotspot.svg' ?>" alt="Multiple Content on Hotspots" />
                    </span>
                    <h5 class="title"><?php echo __('Multiple Content on Hotspots','wpvr')?></h5>
                </div>

                <div class="single-settings">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/control-button.svg' ?>" alt="Custom Control Buttons" />
                    </span>
                    <h5 class="title"><?php echo __('Custom Control Buttons','wpvr')?></h5>
                </div>

                <div class="single-settings">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/contact-form.svg' ?>" alt="Set Contact Form" />
                    </span>
                    <h5 class="title"><?php echo __('Set Contact Form','wpvr')?></h5>
                </div>

                <div class="single-settings">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/background-audio.svg' ?>" alt="Background Audio" />
                    </span>
                    <h5 class="title"><?php echo __('Background Audio','wpvr')?></h5>
                </div>

                <div class="single-settings">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/explainer-video.svg' ?>" alt="Explainer Video" />
                    </span>
                    <h5 class="title"><?php echo __('Explainer Video','wpvr')?></h5>
                </div>

                <div class="single-settings">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/gyroscope.svg' ?>" alt="Gyroscope & Mobile Notices" />
                    </span>
                    <h5 class="title"><?php echo __('Gyroscope & Mobile Notices','wpvr')?></h5>
                </div>

            </div>
            <?php if(!is_plugin_active('wpvr-pro/wpvr-pro.php')){?>
                <div class="setting-cta-btn">
                    <a href="https://rextheme.com/wpvr/" class="vr-welcome-btn" target="_blank"><?php echo __('Checkout All Features','wpvr')?></a>
                </div>
                <div class="feature-settings-cta">
                    <div class="cta-content">
                        <h2> <?php echo __('Get Access to ','wpvr') ?> <span class="primary-color"> <?php echo __('Premium Features','wpvr') ?>  </span> & <span class="primary-color"> <?php echo __('Add-ons','wpvr') ?> </span></h2>
                        <p><?php echo __('Starts from only','wpvr') ?> <span>$79</span>, <?php echo __('for unlimited virtual tours.','wpvr') ?> </p>
                    </div>

                    <a href="https://rextheme.com/wpvr/#pricing" class="vr-welcome-btn cta-btn" target="_blank"><?php echo __('Get Pro Now','wpvr')?></a>
                </div>
                <?php
            }else{?>
                <div class="setting-cta-btn">
                    <form action="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" method="get">
                        <input type="text" name="post_type" value="wpvr_item" hidden>
                        <input type="text" name="wpvr-guide-tour" value="1" hidden>
                        <button type="submit" class="vr-welcome-btn hero-btn"><?php echo __('Take The Tour','wpvr') ?></button>
                    </form>
                </div>
            <?php
            }
            ?>




            <div class="welcome-page-footer">
                <ul class="footer-menu">
                    <li><a href="https://rextheme.com/support/" target="_blank" ><?php echo __('Contact Our Support','wpvr') ?></a></li>
                    <li><a href="https://rextheme.com/docs/wp-vr-install-and-activate/" target="_blank"><?php echo __('Read Documentations','wpvr') ?></a></li>
                    <li><a href="https://rextheme.com/category/virtual-tour/" target="_blank" ><?php echo __('Read Our Complete Guides','wpvr') ?></a></li>
                </ul>
            </div>
        </div>
    </section>

</div>
<style>
    .pano-alert.scene-alert {
        display: none;
    }
    div#error_occured {
        display: none;
    }
</style>
