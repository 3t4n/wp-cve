<div class="wrap">

    <div id="STX-admin" class="STX-admin">


            <div class="STX-tr">

                <div class="STX-getting-started">
                    <div class="STX-h1 STX-heading"><?php esc_html_e( 'Welcome to Transition Slider', 'stx' ); ?></div>

                    <p class="STX-sub-heading"><?php esc_html_e( 'Watch the video tutorial for easy start', 'stx' ); ?></p>

                    <iframe class="STX_getting_started__video" width="710" height="400" src="https://www.youtube.com/embed/zJGESBS6ZdM" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>

                    <div class="STX_getting_started__buttons">
                        <div class="STX_getting_started__button_dont_show">
                            <a href="<?php echo get_admin_url() . 'admin.php?page=transition_slider_admin&action=getting_started_dont_show'; ?>"><?php esc_html_e( "Don't show again", "stx"); ?></a>
                        </div>
                        <div class="STX_getting_started__button_dashboard">
                            <a href="<?php echo get_admin_url() . 'admin.php?page=transition_slider_admin&action=dashboard'; ?>"><?php esc_html_e( "Go to Dashboard", "stx"); ?></a>
                        </div>
                    </div>

                </div>

            </div>
            <div class="STX-tr">

                                 <div class="STX-pro-banner">
                    <div style="font-size: 26px;">Why upgrade to Transition Slider Pro?</div>
                    <div class="STX-pro-banner-thumbs-wrapper">
                        <a class="STX-pro-banner-thumb STX-banner-1" href="https://transitionslider.com/templates" target="_blank">High quality templates</a>
                        <a class="STX-pro-banner-thumb STX-banner-2" href="https://transitionslider.com/templates/urban-shop" target="_blank">Quality text animations</a>
                        <a class="STX-pro-banner-thumb STX-banner-3" href="https://transitionslider.com/templates" target="_blank">iFrame element and more...</a>
                        <a class="STX-pro-banner-thumb STX-banner-4" href="https://transitionslider.com/templates" target="_blank">Adjust slider and layer settings on all devices</a>
                        <a class="STX-pro-banner-thumb STX-banner-5" href="https://transitionslider.com/templates" target="_blank">Import / Export sliders</a>
                        <a class="STX-pro-banner-thumb STX-banner-6" href="https://transitionslider.com/templates" target="_blank">Slide transitions: Line advanced, Crossfade gradient...</a>
                        <a class="STX-pro-banner-thumb STX-banner-7" href="https://codecanyon.net/item/transition-slider-wordpress-plugin/23531533/support" target="_blank">6 months support from purchase with options to extend</a>
                    </div>
                </div>

                            </div>

    </div>
</div>

<?php

    wp_enqueue_style('transitionslider-edit-slider-css');
