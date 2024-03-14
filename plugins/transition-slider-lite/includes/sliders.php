<div class="wrap" style="display: none;">
    <?php
        if (isset($_GET['action'])){

            if($_GET['action'] == "import_from_json_confirm" ) {

                echo '<div id="message" class="updated notice is-dismissible below-h2">
                        <p>Sliders imported from JSON.</p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                    </div>';
            }
        }
    ?>

    <div id="STX-admin" class="STX-admin">
        <div class="STX-nav">
            <div class="STX-header">
                <div class="STX-header-admin-nav STX-header-right">
                    <div class="STX-nav-logo"></div>
                    <a class="STX-h4 STX-btn-topbar STX-btn-dashboard" data-form-name="dashboard"><?php esc_html_e( 'Dashboard', 'stx' ); ?></a>
                </div>
            </div>

                        <div class="STX-header-right-nav">
                <a class="STX-nav-go-pro" href="https://codecanyon.net/item/transition-slider-wordpress-plugin/23531533?ref=creativeinteractivemedia&amp;ref2=wporg" target="_blank" deluminate_imagetype="unknown">GO PRO VERSION!</a>
            </div>

                    </div>

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

                    <div class="STX-admin-content STX-table STX-table-fixed STX-content">
            <div class="STX-tr">
                <div class="STX-dashboard-wrapp STX-td STX-content-base-bg STX-content-wrapper">
                    <div class="STX-heading-bar ">
                        <div class="STX-heading-bar-left">
                           <div class="STX-h1 STX-heading"><?php esc_html_e( 'Dashboard', 'stx' ); ?></div>
                        </div>
                        <a class="delete_all_sliders" title="Delete sliders">
                            <div class="STX-slider-trash-btn-large btns-dashboard-nav"></div>
                        </a>
                        <div class="STX-slider-export-btn btns-dashboard-nav" title="Export sliders">
                            <a href='<?php echo admin_url( "admin.php?page=transition_slider_admin&action=download_json" ); ?>'><?php esc_html_e( 'EXPORT', 'stx' ); ?></a>
                        </div>
                        <div id="import-sliders" class="STX-slider-import-btn btns-dashboard-nav" title="Import sliders"><?php esc_html_e( 'IMPORT', 'stx' ); ?></div>
                        <div class="dropdown btns-dashboard-nav" style="margin-right: 10px;">
                            <div class="select">
                                <span><?php esc_html_e( 'Select', 'stx' ); ?></span>
                                <i aria-hidden="true" class="fa fa-chevron-down"></i>
                            </div>
                            <input type="hidden" name="select-sliders">
                            <ul class="dropdown-menu">
                                <li id="selectAll"><?php esc_html_e( 'Select all', 'stx' ); ?></li>
                                <li id="selectNone"><?php esc_html_e( 'Select none', 'stx' ); ?></li>
                            </ul>
                        </div>
                        <div class="dropdown btns-dashboard-nav">
                            <div class="select">
                                <span><?php esc_html_e( 'Order by', 'stx' ); ?></span>
                                <i aria-hidden="true" class="fa fa-chevron-down"></i>
                            </div>
                            <input type="hidden" name="order-by">
                            <ul class="dropdown-menu">
                                <li id="newestFirst"><?php esc_html_e( 'Newest first', 'stx' ); ?></li>
                                <li id="oldestFirst"><?php esc_html_e( 'Oldest first', 'stx' ); ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="STX-outer-container">
                        <div id="STX-container">
                           <div class="STX-inner">
                              <div class="STX-container">
                                 <div class="STX-rect STX-h3 STX-uc STX-edit-slider-box">
                                    <div class="STX-create STX-btn STX-btn-l STX-button-green STX-radius-global STX-uc STX-h3" href="#">
                                        <a href='<?php echo admin_url( "admin.php?page=transition_slider_admin&action=add_new" ); ?>'><?php esc_html_e( 'NEW SLIDER', 'stx' ); ?></a>
                                    </div>
                                 </div>

                                 <div class="STX-rect STX-h3 STX-uc STX-edit-slider-box">
                                    <div class="STX-designs STX-btn STX-btn-l STX-button-yellow STX-radius-global STX-uc STX-h3" href="#">
                                        <a href='#'><?php esc_html_e( 'SLIDER TEMPLATES', 'stx' ); ?></a>
                                    </div>
                                 </div>

                              </div>
                           </div>
                        </div>
                     </div>

                </div>
            </div>
        </div>

    </div>
</div>

<div class="STX-modal-window" style="display: none;">
    <div class="STX-modal-window-overlay"></div>
    <div class="STX-modal-window-inside import">
        <div class="STX-heading-bar-modal">
            <div class="STX-heading-bar-left">
               <div class="STX-h1 STX-heading STX-modal-window-title"><?php esc_html_e( 'Import Sliders', 'stx' ); ?></div>
            </div>
            <div class="STX-modal-close-btn">
                <i aria-hidden="true" class="fa fa-times"></i>
            </div>
        </div>
        <div class="STX-modal-window-content import">
            <div class="STX-editing-slide-title"><?php esc_html_e( 'To import Sliders copy and paste sliders config below:', 'stx' ); ?></div>
            <div class="STX-editing-slide-table-wrapp">
                <div class="slider_preview"></div>
                <div class="STX-modal-window-import-text" contentEditable=true>
                    <form method="post" id="slider-modal-import-form" enctype="multipart/form-data" action="admin.php?page=transition_slider_admin&amp;action=import_from_json_confirm">
                            <?php
                                if (isset($_GET['action']) && $_GET['action'] == "download_json") {
                                    echo '<textarea id="STX-admin-json-text" rows="20" cols="100" >' . json_encode($sliders) . '</textarea>';
                                }
                            ?>
                            <textarea name="sliders" id="STX-admin-json-text" rows="20" cols="100"></textarea>
                            <input type="submit" name="submit" id="submit" class="STX-slider-import-btn btns-dashboard-nav STX-slider-import-btn-modal" value="<?php esc_html_e( 'IMPORT', 'stx' ); ?>">
                    </form>
                </div>
            </div>
            </div>

    </div>
</div>

<div id="templates-modal" tabindex="0" class="STX-fullscreen-modal media-modal wp-core-ui" style="display: none;">
    <button type="button" class="media-modal-close STX-modal-close"><span class="media-modal-icon"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'stx' ); ?></span></span></button>
    <div class="media-modal-content STX-modal-content">
        <div class="edit-attachment-frame mode-select hide-menu hide-router">
            <div class="edit-media-header">


            </div>
            <div class="media-frame-title STX-modal-title"><h1><?php esc_html_e( 'Slider Templates', 'stx' ); ?></h1></div>

            <div class="media-frame-content STX-modal-frame-content">

                <div class="STX-templates"></div>

            </div>
        </div>
    </div>
</div>

<div id="preview-slider-modal" tabindex="0" class="STX-fullscreen-modal media-modal wp-core-ui" style="display: none;">
    <button type="button" class="media-modal-close STX-modal-close STX-modal-close-preview"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>
    <div class="media-modal-content STX-modal-content">
    <div class="edit-attachment-frame mode-select hide-menu hide-router">
            <div class="edit-media-header">
            </div>
            <div class="media-frame-title STX-modal-title"><h1>Slider Preview</h1>
                <div class="devices">


                                                            <div alt="f471" class="pro-version-icon enabled dashicons dashicons-desktop"></div>
                    <div alt="f471" class="pro-version-icon disabled dashicons dashicons-tablet"></div>
                    <div alt="f470" class="pro-version-icon disabled dashicons dashicons-smartphone"></div>

                                    </div>
            </div>
            <div class="media-frame-content STX-modal-frame-content">
                <div id="slider-preview-container">
                    <div id="slider-preview"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="media-modal-backdrop" style="display: none;"></div>

<div class="STX-loader-container"><div class="STX-loader"></div></div>



<?php
    wp_enqueue_media();

    wp_enqueue_script("transitionslider-lib-three");
    wp_enqueue_script("transitionslider-lib-swiper");
    wp_enqueue_script("transitionslider-lib-color-pickr");
    wp_enqueue_script("transitionslider-lib-select2");
    wp_enqueue_script("transitionslider-lib-anime-js");
    wp_enqueue_script("transitionslider-lib-tipsy");
	wp_enqueue_script("transitionslider-lib-fontselect");
    wp_enqueue_script("transitionslider-lib-webfontloader");
    wp_enqueue_script("transitionslider-lib-iconify");
    wp_enqueue_script("transitionslider-build");
    wp_enqueue_script("transitionslider-build-webgl");

    wp_enqueue_script('transitionslider-sliders');

    wp_enqueue_style('transitionslider-animate-css');
    wp_enqueue_style( "transitionslider-css");
    wp_enqueue_style( "transitionslider-swiper-css");
    wp_enqueue_style( "transitionslider-pickr-css");
    wp_enqueue_style( "transitionslider-select2-css");
	wp_enqueue_style( "transitionslider-fontselect-css");
    wp_enqueue_style( "transitionslider-fontawesome-css");

    wp_enqueue_style('transitionslider-edit-slider-css');

    $ajax_nonce = wp_create_nonce( "stx_nonce");

    $sliders_formatted = array();
    foreach ($sliders as $s) {
        $s2 = array(
            "id" => $s['id'],
            "instanceName" => $s['instanceName'],
            "date" => $s['date']
        );
        $s2["slides"] = array();
        $s2["slides"][0] = array();
        $s2["slides"][0]["src"] = $s["slides"][0]["src"];
        if(isset($s["slides"][0]["thumbSrc"]))
            $s2["slides"][0]["thumbSrc"] = $s["slides"][0]["thumbSrc"];
        array_push($sliders_formatted,$s2);
    }

    wp_localize_script( 'transitionslider-sliders', 'data', array(
            'sliders' => json_encode($sliders_formatted),
            'stx_nonce' => $ajax_nonce,
            'stx_plugin_url' => $this->PLUGIN_DIR_URL
        ) );
