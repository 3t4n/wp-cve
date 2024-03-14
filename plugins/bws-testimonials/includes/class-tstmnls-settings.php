<?php
/**
 * Displays the content on the plugin settings page
 */

if ( ! class_exists( 'Tstmnls_Settings_Tabs' ) ) {
    class Tstmnls_Settings_Tabs extends Bws_Settings_Tabs {
        public $is_general_settings = true;
        public $wp_image_sizes = array();

        /**
         * Constructor.
         *
         * @access public
         *
         * @see Bws_Settings_Tabs::__construct() for more information on default arguments.
         *
         * @param string $plugin_basename
         */
        public function __construct( $plugin_basename ) {
            global $_wp_additional_image_sizes, $tstmnls_options, $tstmnls_plugin_info;

            $this->is_general_settings = ( isset( $_GET['page'] ) && 'testimonials.php' == $_GET['page'] );

            if ( $this->is_general_settings ) {
                $tabs = array(
                    'settings' 		=> array( 'label' => __( 'Settings', 'bws-testimonials'  ) ),
                    'images'        => array( 'label' => __( 'Slider', 'bws-testimonials' ) ),
                    'misc' 			=> array( 'label' => __( 'Misc', 'bws-testimonials' ) ),
                    'custom_code' 	=> array( 'label' => __( 'Custom Code', 'bws-testimonials' ) ),
                );
            }


            parent::__construct( array(
                'plugin_basename' 	 => $plugin_basename,
                'plugins_info'		 => $tstmnls_plugin_info,
                'prefix' 			 => 'tstmnls',
                'default_options' 	 => tstmnls_get_option_defaults(),
                'options' 			 => $tstmnls_options,
                'tabs' 				 => $tabs,
                'wp_slug'			 => 'bws-testimonials',
                'doc_link'              => 'https://bestwebsoft.com/documentation/testimonials/testimonials-user-guide/'
            ) );

            $wp_sizes = get_intermediate_image_sizes();

            foreach ( ( array ) $wp_sizes as $size ) {
                if ( ! array_key_exists( $size, $tstmnls_options['custom_size_px'] ) ) {
                    if ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
                        $width  = absint( $_wp_additional_image_sizes[ $size ]['width'] );
                        $height = absint( $_wp_additional_image_sizes[ $size ]['height'] );
                    } else {
                        $width  = absint( get_option( $size . '_size_w' ) );
                        $height = absint( get_option( $size . '_size_h' ) );
                    }

                    if ( ! $width && ! $height ) {
                        $this->wp_image_sizes[] = array(
                            'value'  => $size,
                            'name'   => ucwords( str_replace( array( '-', '_' ), ' ', $size ) ),
                        );
                    } else {
                        $this->wp_image_sizes[] = array(
                            'value'  => $size,
                            'name'   => ucwords( str_replace( array( '-', '_' ), ' ', $size ) ) . ' ( ' . $width . ' &#215; ' . $height . ' ) ',
                            'width'  => $width,
                            'height' => $height
                        );
                    }
                }
            }

            add_action( get_parent_class( $this ) . '_display_metabox', array( $this, 'display_metabox' ) );
        }

        /**
         * Save plugin options to the database
         * @access public
         * @param  void
         * @return array    The action results
         */
        public function save_options() {
            $message = $notice = $error = '';

            $this->options['count']				= isset( $_POST['tstmnls_count'] ) ? intval( $_POST['tstmnls_count'] ) : '5';

            $new_image_size_photo 		= esc_attr( $_POST['tstmnls_image_size_photo'] );
            $custom_image_size_w_photo 	= intval( $_POST['tstmnls_custom_image_size_w_photo'] );
            $custom_image_size_h_photo 	= intval( $_POST['tstmnls_custom_image_size_h_photo'] );
            $custom_size_px_photo 		= array( $custom_image_size_w_photo, $custom_image_size_h_photo );

            $this->options['custom_size_px']['tstmnls_custom_size'] = $custom_size_px_photo;
            $this->options['image_size_photo'] 				        = $new_image_size_photo;

            $this->options['order_by']			= isset( $_POST['tstmnls_order_by'] ) && in_array( $_POST['tstmnls_order_by'], array( 'ID', 'title', 'date', 'rand' ) ) ? $_POST['tstmnls_order_by'] : 'date';
            $this->options['order']				= isset( $_POST['tstmnls_order'] ) && in_array( $_POST['tstmnls_order'], array( 'ASC', 'DESC' ) ) ? $_POST['tstmnls_order'] : 'DESC';
            $this->options['permissions']		= isset( $_POST['tstmnls_permission'] ) && in_array( $_POST['tstmnls_permission'], array( 'all', 'logged' ) ) ? $_POST['tstmnls_permission'] : 'all';
            $this->options['auto_publication']  = isset( $_POST['tstmnls_auto_publication'] ) ? 1 : 0;

            $this->options['gdpr_tm_name']		= isset( $_POST['tstmnls_gdpr_tm_name'] ) ? sanitize_text_field( htmlspecialchars( $_POST['tstmnls_gdpr_tm_name'] ) ) : $this->options['gdpr_tm_name'];
            $this->options['gdpr_text']			= isset( $_POST['tstmnls_gdpr_text'] ) ? sanitize_text_field( htmlspecialchars( $_POST['tstmnls_gdpr_text'] ) ) : $this->options['gdpr_text'];
            $this->options['gdpr_link']			= isset( $_POST['tstmnls_gdpr_link'] ) ? sanitize_text_field( $_POST['tstmnls_gdpr_link'] ) : $this->options['gdpr_link'];
            $this->options['gdpr']				= isset( $_POST['tstmnls_gdpr'] ) ? 1 : 0;
            $this->options['recaptcha_cb']		= isset( $_POST['tstmnls_enable_recaptcha'] ) ? 1 : 0;
            $this->options['rating_cb']		    = isset( $_POST['tstmnls_enable_rating'] ) ? 1 : 0;
	        $this->options['sender_cb']		    = isset( $_POST['tstmnls_enable_sender'] ) ? 1 : 0;
            $this->options['reviews_per_load']	= intval( $_POST['tstmnls_reviews_per_load'] );

				$this->options['loop']					=  isset( $_POST['tstmnls_loop'] )  ? 1 : 0;
				/* Display navigation button */
				$this->options['nav']					= ( isset( $_POST['tstmnls_nav'] ) ) ? 1 : 0;
				/* Display navigation Dots */
				$this->options['dots']					= ( isset( $_POST['tstmnls_dots'] ) ) ? 1 : 0;
				/* Set count items in 1 slide */
				$this->options['items_in_slide']		= isset( $_POST['tstmnls_items_in_slide'] ) ? intval( $_POST['tstmnls_items_in_slide'] ) : '1';
				/* Set autoplay */
				$this->options['autoplay']				= ( isset( $_POST['tstmnls_autoplay'] ) ) ? 1 : 0;
				/* Autoplay timeout */
				$this->options['autoplay_timeout'] 		= ( ! empty( $_POST['tstmnls_autoplay_timeout'] ) ) ? intval( $_POST['tstmnls_autoplay_timeout']  )*1000 : '2000';
				$this->options['auto_height']				= ( isset( $_POST['tstmnls_auto_height'] ) ) ? 1 : 0;

            $this->options	= array_map( 'stripslashes_deep', $this->options );

            update_option( 'tstmnls_options', $this->options );
            $message .= __( 'Settings saved.', 'bws-testimonials' );

            return compact( 'message', 'notice', 'error' );
        }
        /**
         *s
         */
        public function tab_settings() {
            global $tstmnls_plugin_info, $wp_version ; ?>
            <h3 class="bws_tab_label"><?php _e( 'Testimonials Settings', 'bws-testimonials' ); ?></h3>
            <?php $this->help_phrase(); ?>
            <hr>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Number of Testimonials', 'bws-testimonials' ); ?></th>
                    <td>
                        <input type="number" required class="text" min="1" max="10000" value="<?php echo $this->options['count']; ?>" name="tstmnls_count" />
                        <span class="bws_info"><?php _e( 'per page', 'bws-testimonials' ); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Image Size', 'bws-testimonials' ); ?> </th>
                    <td>
                        <select name="tstmnls_image_size_photo">
                            <?php foreach ( $this->wp_image_sizes as $data ) { ?>
                                <option value="<?php echo $data['value']; ?>" <?php selected( $data['value'], $this->options['image_size_photo'] ); ?>><?php echo $data['name']; ?></option>
                            <?php } ?>
                            <option value="tstmnls_custom_size" <?php selected( 'tstmnls_custom_size', $this->options['image_size_photo'] ); ?> class="bws_option_affect" data-affect-show=".tstmnls_for_custom_image_size"><?php _e( 'Custom', 'bws-testimonials' ); ?></option>
                        </select>
                        <div class="bws_info"><?php _e( 'Maximum testimonials image size. "Custom" uses the Image Dimensions values.', 'bws-testimonials' ); ?></div>
                    </td>
                </tr>
                <tr valign="top" class="tstmnls_for_custom_image_size">
                    <th scope="row"><?php _e( 'Custom Image Size', 'bws-testimonials' ); ?> </th>
                    <td>
                        <input type="number" name="tstmnls_custom_image_size_w_photo" min="1" max="10000" value="<?php echo $this->options['custom_size_px']['tstmnls_custom_size'][0]; ?>" /> x <input type="number" name="tstmnls_custom_image_size_h_photo" min="1" max="10000" value="<?php echo $this->options['custom_size_px']['tstmnls_custom_size'][1]; ?>" /> <?php _e( 'px', 'bws-testimonials' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Sort Testimonials by', 'bws-testimonials' ); ?></th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="radio" name="tstmnls_order_by" value="ID" <?php checked( 'ID', $this->options["order_by"] ); ?> /> <?php _e( 'Testimonial ID', 'bws-testimonials' ); ?>
                            </label>
                            <br />
                            <label>
                                <input type="radio" name="tstmnls_order_by" value="title" <?php checked( 'title', $this->options["order_by"] ); ?> /> <?php _e( 'Title', 'bws-testimonials' ); ?>
                            </label>
                            <br />
                            <label>
                                <input type="radio" name="tstmnls_order_by" value="date" <?php checked( 'date', $this->options["order_by"] ); ?> /> <?php _e( 'Date added', 'bws-testimonials' ); ?>
                            </label>
                            <br />
                            <label>
                                <input type="radio" name="tstmnls_order_by" value="rand" <?php checked( 'rand', $this->options["order_by"] ); ?> /> <?php _e( 'Random', 'bws-testimonials' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Arrange Testimonials by', 'bws-testimonials' ); ?> </th>
                    <td><fieldset>
                            <label><input type="radio" name="tstmnls_order" value="ASC" <?php checked( 'ASC', $this->options["order"] ); ?> /> <?php _e( 'Ascending (e.g. 1, 2, 3; a, b, c)', 'bws-testimonials' ); ?></label><br />
                            <label><input type="radio" name="tstmnls_order" value="DESC" <?php checked( 'DESC', $this->options["order"] ); ?> /> <?php _e( 'Descending (e.g. 3, 2, 1; c, b, a)', 'bws-testimonials' ); ?></label>
                        </fieldset></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Allow Testimonials from', 'bws-testimonials' ); ?></th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="radio" name="tstmnls_permission" value="logged" <?php checked( 'logged', $this->options["permissions"] ); ?> /> <?php _e( 'Logged users', 'bws-testimonials' ); ?>
                            </label>
                            <br />
                            <label>
                                <input type="radio" name="tstmnls_permission" value="all" <?php checked( 'all', $this->options["permissions"] ); ?> /> <?php _e( 'Everyone', 'bws-testimonials' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Auto Approve Testimonials', 'bws-testimonials' ); ?></th>
                    <td>
                        <input type="checkbox" name="tstmnls_auto_publication" id="tstmnls_auto_publication" <?php checked( 1, $this->options['auto_publication'] ); ?> />
                        <label><?php _e( 'Enable to approve new testimonials automatically. Otherwise, new testimonials will be saved as drafts.', 'bws-testimonials' ); ?></label>
                    </td>
                </tr>
                <tr>
                <?php /* Display reCAPTCHA settings */
                $related_plugins = array(
                    'recaptcha' => array(
                        'name'				=> 'reCaptcha plugin',
                        'short_name'		=> 'reCaptcha',
                        'download_link'		=> 'https://bestwebsoft.com/products/wordpress/plugins/google-captcha/?k=8b945710c30a24dd837c9c53c0aed0f8&amp;pn=180&v=' . $tstmnls_plugin_info["Version"] . '&amp;wp_v=' . $wp_version,
                        'status'			=> tstmnls_get_related_plugin_status( 'recaptcha' )
                    ),
                    'rating' => array(
                        'name'				=> 'Rating plugin',
                        'short_name'		=> 'Rating',
                        'download_link'		=> 'https://bestwebsoft.com/products/wordpress/plugins/rating/?k=61d18b51a1d3170ba85a3a5ee07d207c&amp;pn=180&v=' . $tstmnls_plugin_info["Version"] . '&amp;wp_v=' . $wp_version,
                        'status'			=> tstmnls_get_related_plugin_status( 'rating' )
                    ),
                    'sender' => array(
	                    'name'				=> 'Sender plugin',
	                    'short_name'		=> 'Sender',
	                    'download_link'		=> 'https://bestwebsoft.com/products/wordpress/plugins/sender/?k=f8ec4e659d1410d522302d3a2fa46486&amp;pn=180&v=' . $tstmnls_plugin_info["Version"] . '&amp;wp_v=' . $wp_version,
	                    'status'			=> tstmnls_get_related_plugin_status( 'sender' )
                    )
                );
                foreach ( $related_plugins as $plugin_slug => $plugin_data ) {
                    if ( ! empty( $plugin_data['status']['active'] ) && 'outdated' != $plugin_data['status']['active'] ) {
                        $is_enabled = isset( $_POST["tstmnls_enable_{$plugin_slug}"] ) ? true : false;
                        $related_plugins[ $plugin_slug ]['status']['enabled'] = $is_enabled;
                        if ( 'recaptcha' == $plugin_slug && get_option( 'gglcptch_options' )  ) {
                            $gglcptch_options = get_option( 'gglcptch_options' );
                            $gglcptch_options['testimonials_form'] = $is_enabled ? 1 : 0;
                            update_option( 'gglcptch_options', $gglcptch_options );
                        }
	                    if ( 'sender' == $plugin_slug && get_option( 'sndr_options' )  ) {
		                    $sndr_options = get_option( 'sndr_options' );
		                    if ( $is_enabled ) {
			                    $sndr_options['automailout_new_post'][] = 'bws-testimonial';
			                    update_option( 'sndr_options', $sndr_options );
		                    } else {
			                    foreach( array_keys( $sndr_options['automailout_new_post'], 'bws-testimonial' ) as $key ) {
				                    unset( $sndr_options['automailout_new_post'][$key] );
				                    update_option( 'sndr_options', $sndr_options );
			                    }
                            }
	                    }
                    }
                }
                foreach ( $related_plugins as $plugin_slug => $plugin_data ) { ?>
                    <tr valign="top">
                        <th scope="row">
                            <label for="<?php echo "tstmnls-enable-{$plugin_slug}"; ?>">
                                <?php echo $plugin_data['short_name']; ?>
                            </label>
                        </th>
                        <td>
                            <label for="tstmnls-enable-<?php echo $plugin_slug; ?>">
                                <input type="checkbox"
                                    name="tstmnls_enable_<?php echo $plugin_slug; ?>"
                                    id="<?php echo "tstmnls-enable-{$plugin_slug}"; ?>"
                                    <?php checked( ! empty( $this->options[ $plugin_slug . '_cb' ] ) );
                                    disabled(
                                        ! $plugin_data['status']['installed'] ||
                                        ! $plugin_data['status']['active'] ||
                                        'outdated' == $plugin_data['status']['active']
                                    ); ?>
                                    value="1"
                                    <?php if ( 'rating' == $plugin_slug ) echo 'class="bws_option_affect" data-affect-show=".tstmnls_reviews_per_load"' ?>>
                                <span class="bws_info">
                                    <?php if ( 'rating' == $plugin_slug ) {
                                        printf( __( 'Enable to allow people add rating to the form submission with the %s.', 'bws-testimonials' ), $plugin_data['name'] );
                                    } else if ( 'recaptcha' == $plugin_slug ) {
                                        printf( __( 'Enable to protect your testimonials submission form with the %s.', 'bws-testimonials' ), $plugin_data['name'] );
                                    } else {
	                                    printf( __( 'Enable automatic mailing when a new testimonials is posted with the %s.', 'bws-testimonials' ), $plugin_data['name'] );
                                    }
                                    if ( ! $plugin_data['status']['installed'] ) {
                                        printf(
                                            ' <a href="%1$s" target="_blank">%2$s</a>',
                                            $plugin_data['download_link'],
                                            __( 'Install Now', 'bws-testimonials' )
                                        );
                                    } elseif ( ! $plugin_data['status']['active'] ) {
                                        printf(
                                            ' <a href="%1$s" target="_blank">%2$s</a>',
                                            network_admin_url( 'plugins.php' ),
                                            __( 'Activate', 'bws-testimonials' )
                                        );
                                    } else {
                                        if ( 'outdated' == $plugin_data['status']['active'] ) {
                                            printf( __( 'Your %s plugin is outdated. Please update it to the latest version.', 'bws-testimonials' ), $plugin_data['name'] );
                                        }
                                    } ?>
                                </span>
                            </label>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="tstmnls_reviews_per_load">
                    <th><?php _e( 'Reviews per load', 'bws-testimonials' ); ?></th>
                    <td>
                        <label>
                            <input type="number" min="3" max="100" name="tstmnls_reviews_per_load" value="<?php echo $this->options['reviews_per_load']; ?>" />
                            <span class="bws_info"><?php _e( 'The amount of reviews that will be loaded by pressing "See all reviews" button.', 'bws-testimonials' ); ?></span>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="tstmnls-gdpr"><?php _e( 'GDPR Compliance', 'bws-testimonials' ); ?></label>
                    </th>
                    <td class="tstmnls_gdpr_td">
                        <fieldset>
                            <input type="checkbox" id="tstmnls_gdpr" name="tstmnls_gdpr" value="1" <?php checked( '1', $this->options['gdpr'] ); ?> />
                            <label><?php _e( 'Enable to display a GDPR Compliance checkbox.', 'bws-testimonials' ); ?></label>
                            <div id="tstmnls_gdpr_link_options" >
                                <label class="tstmnls_privacy_policy_text" >
                                    <?php _e( 'Checkbox label', 'bws-testimonials' ); ?>
                                    <br />
                                    <input type="text" id="tstmnls_gdpr_tm_name" size="30" name="tstmnls_gdpr_tm_name" value="<?php echo $this->options['gdpr_tm_name']; ?>"/>
                                </label>
                                <span class="bws_info"><strong>{PRIVACYPOLICY}</strong> - <?php _e( 'Link to Privacy Policy page.', 'bws-testimonials' ); ?></span>
                                <label class="tstmnls_privacy_policy_text" >
                                    <?php _e( "Link to Privacy Policy Page", 'bws-testimonials' ); ?>
                                    <br />
                                    <?php wp_dropdown_pages( array(
                                        'depth'                 => 0,
                                        'selected'              => isset( $this->options['gdpr_link'] ) ? $this->options['gdpr_link'] : true,
                                        'name'                  => 'tstmnls_gdpr_link',
                                        'show_option_none'		=> '...'
                                    ) ); ?>
                                </label>
                                <label class="tstmnls_privacy_policy_text" >
                                    <?php _e( "Text for Privacy Policy Link", 'bws-testimonials' ); ?>
                                    <br />
                                    <input type="text" id="tstmnls_gdpr_text" size="30" name="tstmnls_gdpr_text" value="<?php echo $this->options['gdpr_text']; ?>" />
                                </label>
                            </div>
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php }

		public function  tab_images(){?>
			<h3 class="bws_tab_label"><?php _e( 'Slider', 'bws-testimonials' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table tstmnls_settings_form">
				<?php if ( $this->is_general_settings ) { ?>
					<tr>
						<th><?php _e( 'Items in Slide', 'bws-testimonials' ); ?></th>
						<td>
							<label>
								<input type="number" name="tstmnls_items_in_slide" min="1" max="4" value="<?php echo $this->options['items_in_slide']; ?>" />
							</label>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Autoplay', 'bws-testimonials' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="tstmnls_autoplay" class="bws_option_affect" data-affect-show=".tstmnls_autoplay" value="1" <?php checked( 1, $this->options['autoplay'] ); ?> /> <span class="bws_info"><?php _e( 'Enable to turn autoplay on for the slideshow.', 'bws-testimonials' ); ?></span>
							</label>
						</td>
					</tr>
					<tr class="tstmnls_autoplay">
						<th><?php _e( 'Autoplay Timeout', 'bws-testimonials' ); ?></th>
						<td>
							<label>
								<input type="number" name="tstmnls_autoplay_timeout" min="1" max="1000" value="<?php echo $this->options['autoplay_timeout']/1000; ?>" /> <?php _e( 'sec', 'bws-testimonials' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Auto Height', 'bws-testimonials' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="tstmnls_auto_height" value="1" <?php checked( 1, $this->options['auto_height'] ); ?> />
								<span class="bws_info"><?php _e( 'Enable to change slider height automatically (according to the hight of the slide).', 'bws-testimonials' ); ?></span>
							</label>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Loop', 'bws-testimonials' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="tstmnls_loop" value="1" <?php checked( 1, $this->options['loop'] ); ?> />
								<span class="bws_info"><?php _e( 'Enable to loop the slideshow.', 'bws-testimonials' ); ?></span>
							</label>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Navigation', 'bws-testimonials' ); ?></th>
						<td>
							<fieldset>
								<label>
									<input type="checkbox" name="tstmnls_nav" value="1" <?php checked( 1, $this->options['nav'] ); ?> />
									<?php _e( 'Arrows', 'bws-testimonials' ); ?>
								</label>
								<br/>
								<label>
									<input type="checkbox" name="tstmnls_dots" value="1" <?php checked( 1, $this->options['dots'] ); ?> />
									<?php _e( 'Dots', 'bws-testimonials' ); ?>
								</label>
							</fieldset>
						</td>
					</tr>
				<?php } ?>
			</table>
		<?php }

        public function display_metabox() { ?>
            <div class="postbox">
                <h3 class="hndle">
                    <?php _e( 'Testimonials', 'bws-testimonials' ); ?>
                </h3>
                <div class="inside">
                    <?php _e( 'If you would like to display testimonials in widget, you need to add "Testimonials Widget" on the Widgets page.', 'bws-testimonials' ); ?>
                </div>
                <div class="inside">
                    <?php _e( "If you would like to add testimonials use next shortcode:", 'bws-testimonials' ); ?>
                    <?php bws_shortcode_output( "[bws_testimonials]" ); ?>
                </div>
                <div class="inside">
                    <?php _e( "If you would like to add testimonials form use next shortcode:", 'bws-testimonials' ); ?>
                    <?php bws_shortcode_output( "[bws_testimonials_form]" ); ?>
                </div>
                <div class="inside">
                    <?php _e( "If you would like to add testimonials slider use next shortcode:", 'bws-testimonials' ); ?>
                    <?php bws_shortcode_output( "[bws_testimonials_slider]" ); ?>
                </div>
                <div class="inside">
                    <?php _e( "Also, you can paste the following strings into the template source code: ", 'bws-testimonials' ); ?>
                    <code>
                        &lt;?php if ( has_action( 'tstmnls_show_testimonials' ) ) {
                        do_action( 'tstmnls_show_testimonials' );
                        } ?&gt;
                    </code>
                </div>
                <div class="inside">
                    <?php _e( "If you would like to add reviews use next shortcode:", 'bws-testimonials' ); ?>
                    <?php bws_shortcode_output( "[bws_testimonials_reviews]" ) ?>
                </div>
                <div class="inside">
                    <?php _e( "If you would like to add review form use next shortcode:", 'bws-testimonials' ); ?>
                    <?php bws_shortcode_output( "[bws_testimonials_review_form]" ) ?>
                </div>
            </div>
        <?php }
    }
}