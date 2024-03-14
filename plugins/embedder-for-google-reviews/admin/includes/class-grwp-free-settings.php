<?php

Class GRWP_Free_Settings {

    private $google_reviews_options;

    public function __construct() {
        $this->google_reviews_options = get_option( 'google_reviews_option_name' );
        $this->add_settings();
    }

    private function add_settings() {
        /*
        add_settings_field(
            'gmb_id_1', // id
            __( 'Place ID', 'grwp' ), // title
            array( $this, 'gmb_id_1_callback' ), // callback
            'google-reviews-admin', // page
            'google_reviews_setting_section' // section
        );
        */

	    add_settings_field(
		    'serp_business_name', // id
		    __( 'Search for your business:', 'grwp' ), // title
		    array( $this, 'serp_business_name_callback' ), // callback
		    'google-reviews-admin', // page
		    'google_reviews_setting_section' // section
	    );

	    add_settings_field(
		    'serp_data_id', // id
		    false, // title
		    array( $this, 'serp_data_id_callback' ), // callback
		    'google-reviews-admin', // page
		    'google_reviews_setting_section', // section
		    array( 'class' => 'hidden' )
	    );

        add_settings_field(
            'video_intro', // id
            '', // title
            array( $this, 'video_intro_callback' ), // callback
            'google-reviews-admin', // page
            'google_reviews_setting_section' // section
        );
    }

	/**
	 * Echo Business Search Field
	 */
	public function serp_business_name_callback() {
		ob_start();

		$search_disabled = '';
		$pull_button_disabled = '';

		// If business is already saved, disable 'Search business' button
		if ( isset($this->google_reviews_options['serp_business_name'])
		     && $this->google_reviews_options['serp_business_name'] !== '' ) {
			$search_disabled = 'disabled';
		}

		// If business name has not yet been saved, disable both buttons
		if ( ! isset($this->google_reviews_options['serp_business_name'])
		     || $this->google_reviews_options['serp_business_name'] == '' ) {
			$pull_button_disabled = 'disabled';
			$search_disabled = 'disabled';
		}

		// If reviews have already been pulled, disable 'Pull reviews' button
		$reviews = GRWP_Pro_API_Service::parse_pro_review_json();
		if ($reviews !== null) {
			$pull_button_disabled = 'disabled';
		}

		?>

        <div class="serp-container">
            <div class="serp-search">
                <input type="search"
                       class="regular-text js-serp-business-search"
                       name="google_reviews_option_name[serp_business_name]"
                       id="serp_business_name"
                       value="<?php echo esc_attr( isset( $this->google_reviews_options['serp_business_name'] ) ? $this->google_reviews_options['serp_business_name'] : '' ); ?>"
                       autocomplete="off"
                       placeholder="<?php _e('Search for your business', 'grwp');?>"
                />
                <div class="button-row">
                    <a class="button search-business pro" <?php echo $search_disabled; ?>>
						<?php _e('Search business', 'grwp');?>
                    </a>
                    <a class="button pull-reviews pro" <?php echo $pull_button_disabled; ?>>
						<?php _e('Pull reviews', 'grwp');?>
                    </a>
                </div>
                <fieldset class="serp-results"></fieldset><!-- /.serp-results -->
            </div><!-- /.serp-search -->
        </div><!-- /.serp-container -->

        <p id="errors"></p>
        <p>
			<?php _e( 'Details like country, state, city and/or phone number may help achieving more accurate results.', 'grwp' ); ?>
        </p>

		<?php
		$html = ob_get_clean();

		echo $html;
	}

	/**
	 * Echo Hidden SERP Data ID Field
	 */
	public function serp_data_id_callback() {
		global $allowed_html;
		ob_start();
		?>

        <input type="hidden" class="hidden js-serp-data-id" name="google_reviews_option_name[serp_data_id]" id="serp_data_id" value="<?php echo esc_attr( isset( $this->google_reviews_options['serp_data_id'] ) ? $this->google_reviews_options['serp_data_id'] : '' ); ?>">

		<?php
		$html = ob_get_clean();

		echo wp_kses($html, $allowed_html);
	}

    /**
     * Show modal with video introduction
     */
    public function video_intro_callback() { ?>
        <div id="how_to_modal" class="modal hide">

            <!-- Modal content -->
            <div class="modal-inner">
                <div class="modal-content">
                    <span id="modal_close" class="close">&times;</span>
                    <h3><?php _e('How to use this plugin', 'grwp'); ?></h3>
                    <p><?php _e('Explained in less than 1 minute...', 'grwp'); ?></p>
                    <div class="responsive_iframe">
                        <iframe style="display:block;" width="560" height="315" src="https://www.youtube-nocookie.com/embed/y2pWCW_cuNk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div class="modal-overlay"></div>

        </div>

        <?php
    }

    /**
     * Echo place ID field
     */
    public function gmb_id_1_callback() {
        global $allowed_html; ?>

        <div class="serp-container">
            <div class="serp-search">

        <?php
        printf(
            '<input class="regular-text free" 
                            type="text" 
                            name="google_reviews_option_name[gmb_id_1]" 
                            id="gmb_id_1" 
                            value="%s"
                            placeholder="%s">',
            isset( $this->google_reviews_options['gmb_id_1'] ) ? esc_attr( $this->google_reviews_options['gmb_id_1']) : '',
            __( 'Paste the Place ID of your business here.', 'grwp' )
        ); ?>

                <a class="button pull-reviews free"><?php _e('Pull reviews', 'grwp');?></a>
            </div>
        </div>


        <?php
        $video_link = get_site_url() .'/wp-admin/admin.php?page=how-to-free-version';

        ?>
        <p id="errors"></p>
        <p>
            <?php

            echo
            wp_kses(
                sprintf(
                        __('Search for your business in the map below and copy/paste the Place ID into the field above (<a href="%s" target="_blank">short explainer video</a>).', 'grwp'),
                        $video_link
                ),
                $allowed_html
            );
            ?>
        </p>
        <br>
        <h4>
            <?php
                _e('Look up your Place ID and paste it in the field above.', 'grwp');
            ?>
        </h4>
        <iframe id="mapFrame" height="200" style="height: 200px; width: 100%; max-width: 700px;display:block;" src="https://geo-devrel-javascript-samples.web.app/samples/places-placeid-finder/app/dist/" allow="fullscreen;"></iframe>
        <?php

    }

}
