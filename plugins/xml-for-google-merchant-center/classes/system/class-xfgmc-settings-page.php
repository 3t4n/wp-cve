<?php 
/**
 * The class return the Settings page of the plugin XML for Google Merchant Center
 *
 * @package                 iCopyDoc Plugins (v1, core 08-08-2023)
 * @subpackage              XML for Google Merchant Center
 * @since                   0.1.0
 * 
 * @version                 3.0.8 (28-11-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param                   
 *
 * @depends                 classes:    XFGMC_Data_Arr
 *                                      XFGMC_Error_Log 
 *                                      XFGMC_WP_List_Table
 *                                      XFGMC_Settings_Feed_WP_List_Table
 *                          traits:     
 *                          methods:    
 *                          functions:  common_option_get
 *                                      common_option_upd
 *                                      XFGMC_optionGET
 *                                      XFGMC_optionUPD
 *                                      XFGMC_optionDEL
 *                          constants:  XFGMC_PLUGIN_UPLOADS_DIR_PATH
 *                          options:    
 *
 */
defined( 'ABSPATH' ) || exit;

class XFGMC_Settings_Page {
	/**
	 * Allowed HTML tags for use in wp_kses()
	 */
	const ALLOWED_HTML_ARR = [ 
		'a' => [ 
			'href' => true,
			'title' => true,
			'target' => true,
			'class' => true,
			'style' => true
		],
		'br' => [ 'class' => true ],
		'i' => [ 'class' => true ],
		'small' => [ 'class' => true ],
		'strong' => [ 'class' => true, 'style' => true ],
		'p' => [ 'class' => true, 'style' => true ]
	];

	/**
	 * Feed ID
	 * @var string
	 */
	private $feed_id;
	/**
	 * The value of the current tab
	 * @var string
	 */
	private $cur_tab = 'main_tab';
	private $feedback;

	public function __construct() {
		$this->feedback = new XFGMC_Feedback();

		if ( isset( $_GET['feed_id'] ) ) {
			$this->feed_id = sanitize_text_field( $_GET['feed_id'] );
		} else {
			if ( empty( xfgmc_get_first_feed_id() ) ) {
				$this->feed_id = '1';
			} else {
				$this->feed_id = xfgmc_get_first_feed_id();
			}
		}
		if ( isset( $_GET['tab'] ) ) {
			$this->cur_tab = sanitize_text_field( $_GET['tab'] );
		}

		$this->init_classes();
		$this->init_hooks();
		$this->listen_submit();

		$this->print_view_html_form();
	}

	/**
	 * Initialization classes
	 * 
	 * @return void
	 */
	public function init_classes() {
		return;
	}

	/**
	 * Initialization hooks
	 * 
	 * @return void
	 */
	public function init_hooks() {
		// наш класс, вероятно, вызывается во время срабатывания хука admin_menu.
		// admin_init - следующий в очереди срабатывания, на хуки раньше admin_menu нет смысла вешать
		// add_action('admin_init', [ $this, 'my_func' ], 10, 1);
		return;
	}
	
	/**
	 * Summary of print_view_html_form
	 * 
	 * @return void
	 */
	public function print_view_html_form() { ?>
		<div class="wrap">
			<h1>
				<?php _e( 'Exporter Google Merchant Center', 'xml-for-google-merchant-center' ); ?>
			</h1>
			<?php $this->get_html_banner(); ?>
			<div id="poststuff">
				<?php $this->get_html_feeds_list(); ?>

				<div id="post-body" class="columns-2">

					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<?php $this->get_html_info_block(); ?>

							<?php do_action( 'xfgmc_before_support_project' ); ?>

							<?php $this->feedback->get_block_support_project(); ?>

							<?php do_action( 'xfgmc_between_container_1', $this->get_feed_id() ); ?>

							<?php $this->feedback->get_form(); ?>

							<?php do_action( 'xfgmc_append_container_1', $this->get_feed_id() ); ?>
						</div>
					</div><!-- /postbox-container-1 -->

					<div id="postbox-container-2" class="postbox-container">
						<div class="meta-box-sortables">
							<?php if ( empty( $this->get_feed_id() ) ) : ?>
								<?php _e( 'No XML feed found', 'xml-for-google-merchant-center' ); ?>.
								<?php _e( 'Click the "Add New Feed" button at the top of this page', 'xml-for-google-merchant-center' ); ?>.
							<?php else :
								if ( isset( $_GET['tab'] ) ) {
									$tab = $_GET['tab'];
								} else {
									$tab = 'main_tab';
								}
								echo $this->get_html_tabs( $tab ); ?>

								<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
									<?php do_action( 'xfgmc_prepend_form_container_2', $this->get_feed_id() ); ?>
									<input type="hidden" name="xfgmc_num_feed_for_save" value="<?php echo $this->get_feed_id(); ?>">
									<?php switch ( $tab ) :
										case 'main_tab': ?>
											<?php $this->get_html_main_settings(); ?>
											<?php break;
										case 'shop_data': ?>
											<?php $this->get_html_shop_data(); ?>
											<?php $this->get_html_shipping_setting(); ?>
											<?php break;
										case 'tags': ?>
											<?php $this->get_html_tags_settings(); ?>
											<?php $xfgms_settings_feed_wp_list_table = new XFGMC_Settings_Feed_WP_List_Table( $this->get_feed_id() ); ?>
											<?php $xfgms_settings_feed_wp_list_table->prepare_items();
											$xfgms_settings_feed_wp_list_table->display(); ?>
											<?php break;
										case 'filtration': ?>
											<?php $this->get_html_filtration(); ?>
											<?php do_action( 'xfgmc_after_main_param_block', $this->get_feed_id() ); ?>
											<?php break; ?>
									<?php endswitch; ?>

									<?php do_action( 'xfgmc_after_optional_elemet_block', $this->get_feed_id() ); ?>
									<div class="postbox">
										<div class="inside">
											<table class="form-table">
												<tbody>
													<tr>
														<th scope="row"><label for="button-primary"></label></th>
														<td class="overalldesc">
															<?php wp_nonce_field( 'xfgmc_nonce_action', 'xfgmc_nonce_field' ); ?><input
																id="button-primary" class="button-primary" type="submit"
																name="xfgmc_submit_action" value="<?php
																if ( $tab === 'main_tab' ) {
																	echo __( 'Save', 'xml-for-google-merchant-center' ) . ' & ' . __( 'Create feed', 'xml-for-google-merchant-center' );
																} else {
																	_e( 'Save', 'xml-for-google-merchant-center' );
																}
																?>" /><br />
															<span class="description"><small>
																	<?php _e( 'Click to save the settings', 'xml-for-google-merchant-center' ); ?><small></span>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</form>
							<?php endif; ?>
						</div>
					</div><!-- /postbox-container-2 -->

				</div>
			</div><!-- /poststuff -->
			<?php $this->get_html_icp_banners(); ?>
			<?php $this->get_html_my_plugins_list(); ?>
		</div>
	<?php // end print_view_html_form();
	}

	public function get_html_banner() {
		return '<div class="notice notice-info">
			<p><span class="xfgmc_bold">XML for Google Merchant Center Pro</span> - ' . __( 'a necessary extension for those who want to', 'xml-for-google-merchant-center' ) . ' <span class="xfgmc_bold" style="color: green;">' . __( 'save on advertising budget', 'xml-for-google-merchant-center' ) . '</span> ' . __( 'on Google', 'xml-for-google-merchant-center' ) . '! <a href="https://icopydoc.ru/product/plagin-xml-for-google-merchant-center-pro/?utm_source=xml-for-google-merchant-center&utm_medium=organic&utm_campaign=in-plugin-xml-for-google-merchant-center&utm_content=settings&utm_term=about-xml-google-pro"' . __( 'Learn More', 'xml-for-google-merchant-center' ) . '</a>.</p> 
		</div>';
	} // end get_html_banner();

	public function get_html_feeds_list() {
		$xfgmcListTable = new XFGMC_WP_List_Table(); ?>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
			<?php wp_nonce_field( 'xfgmc_nonce_action_add_new_feed', 'xfgmc_nonce_field_add_new_feed' ); ?><input class="button"
				type="submit" name="xfgmc_submit_add_new_feed"
				value="<?php _e( 'Add New Feed', 'xml-for-google-merchant-center' ); ?>" />
		</form>
		<?php $xfgmcListTable->print_html_form();
	} // end get_html_feeds_list();

	public function get_html_info_block() {
		$status_sborki = (int) xfgmc_optionGET( 'xfgmc_status_sborki', $this->get_feed_id() );
		$xfgmc_file_url = urldecode( xfgmc_optionGET( 'xfgmc_file_url', $this->get_feed_id(), 'set_arr' ) );
		$xfgmc_date_sborki = xfgmc_optionGET( 'xfgmc_date_sborki', $this->get_feed_id(), 'set_arr' );
		$xfgmc_date_sborki_end = xfgmc_optionGET( 'xfgmc_date_sborki_end', $this->get_feed_id(), 'set_arr' );
		$xfgmc_status_cron = xfgmc_optionGET( 'xfgmc_status_cron', $this->get_feed_id(), 'set_arr' );
		$xfgmc_count_products_in_feed = xfgmc_optionGET( 'xfgmc_count_products_in_feed', $this->get_feed_id(), 'set_arr' );
		?>
		<div class="postbox">
			<?php if ( is_multisite() ) {
				$cur_blog_id = get_current_blog_id();
			} else {
				$cur_blog_id = '0';
			} ?>
			<h2 class="hndle">
				<?php _e( 'Feed', 'xml-for-google-merchant-center' ); ?>
				<?php echo $this->get_feed_id(); ?>:
				<?php if ( $this->get_feed_id() !== '1' ) {
					echo $this->get_feed_id();
				} ?>feed-xml-
				<?php echo $cur_blog_id; ?>.xml
				<?php $assignment = xfgmc_optionGET( 'xfgmc_feed_assignment', $this->get_feed_id(), 'set_arr' );
				if ( $assignment === '' ) {
				} else {
					echo '(' . $assignment . ')';
				} ?>
				<?php if ( empty( $xfgmc_file_url ) ) : ?>
					<?php _e( 'not created yet', 'xml-for-google-merchant-center' ); ?>
				<?php else : ?>
					<?php if ( $status_sborki !== -1 ) : ?>
						<?php _e( 'updating', 'xml-for-google-merchant-center' ); ?>
					<?php else : ?>
						<?php _e( 'created', 'xml-for-google-merchant-center' ); ?>
					<?php endif; ?>
				<?php endif; ?>
			</h2>
			<div class="inside">
				<p><strong style="color: green;">
						<?php _e( 'Instruction', 'xml-for-google-merchant-center' ); ?>:
					</strong> <a
						href="https://icopydoc.ru/kak-sozdat-woocommerce-xml-instruktsiya/?utm_source=xml-for-google-merchant-center&utm_medium=organic&utm_campaign=in-plugin-xml-for-google-merchant-center&utm_content=settings&utm_term=main-instruction"
						target="_blank">
						<?php _e( 'How to create a XML-feed', 'xml-for-google-merchant-center' ); ?>
					</a>.</p>
				<?php if ( empty( $xfgmc_file_url ) ) : ?>
					<?php if ( $status_sborki !== -1 ) : ?>
						<p>
							<?php _e( 'We are working on automatic file creation. XML will be developed soon', 'xml-for-google-merchant-center' ); ?>.
						</p>
					<?php else : ?>
						<p>
							<?php _e( 'In order to do that, select another menu entry (which differs from "off") in the box called "Automatic file creation". You can also change values in other boxes if necessary, then press "Save"', 'xml-for-google-merchant-center' ); ?>.
						</p>
						<p>
							<?php _e( 'After 1-7 minutes (depending on the number of products), the feed will be generated and a link will appear instead of this message', 'xml-for-google-merchant-center' ); ?>.
						</p>
					<?php endif; ?>
				<?php else : ?>
					<?php if ( $status_sborki !== -1 ) : ?>
						<p>
							<?php _e( 'We are working on automatic file creation. XML will be developed soon', 'xml-for-google-merchant-center' ); ?>.
						</p>
					<?php else : ?>
						<p><span class="fgmc_bold">
								<?php _e( 'Your XML feed here', 'xml-for-google-merchant-center' ); ?>:
							</span><br /><a target="_blank" href="<?php echo $xfgmc_file_url; ?>"><?php echo $xfgmc_file_url; ?></a>
							<br />
							<?php _e( 'File size', 'xml-for-google-merchant-center' ); ?>:
							<?php clearstatcache();
							if ( $this->get_feed_id() === '1' ) {
								$prefFeed = '';
							} else {
								$prefFeed = $this->get_feed_id();
							}
							$upload_dir = (object) wp_get_upload_dir();
							if ( is_multisite() ) {
								$filename = $upload_dir->basedir . "/" . $prefFeed . "feed-xml-" . get_current_blog_id() . ".xml";
							} else {
								$filename = $upload_dir->basedir . "/" . $prefFeed . "feed-xml-0.xml";
							}
							if ( is_file( $filename ) ) {
								echo xfgmc_formatSize( filesize( $filename ) );
							} else {
								echo '0 KB';
							} ?>
							<br />
							<?php _e( 'Start of generation', 'xml-for-google-merchant-center' ); ?>:
							<?php echo $xfgmc_date_sborki; ?>
							<br />
							<?php _e( 'Generated', 'xml-for-google-merchant-center' ); ?>:
							<?php echo $xfgmc_date_sborki_end; ?>
							<br />
							<?php _e( 'Products', 'xml-for-google-merchant-center' ); ?>:
							<?php echo $xfgmc_count_products_in_feed; ?>
						</p>
					<?php endif; ?>
				<?php endif; ?>
				<p>
					<?php _e( 'Please note that Google Merchant Center checks XML no more than 3 times a day! This means that the changes on the Google Merchant Center are not instantaneous', 'xml-for-google-merchant-center' ); ?>!
				</p>
			</div>
		</div>
		<?php
	} // end get_html_info_block();

	public function get_html_tabs( $current = 'main_tab' ) {
		$tabs = array(
			'main_tab' => __( 'Main settings', 'xml-for-google-merchant-center' ),
			'shop_data' => __( 'Shop data', 'xml-for-google-merchant-center' ),
			'tags' => __( 'Attribute settings', 'xml-for-google-merchant-center' ),
			'filtration' => __( 'Filtration', 'xml-for-google-merchant-center' )
		);

		$html = '<div class="nav-tab-wrapper" style="margin-bottom: 10px;">';
		foreach ( $tabs as $tab => $name ) {
			if ( $tab === $current ) {
				$class = ' nav-tab-active';
			} else {
				$class = '';
			}
			if ( isset( $_GET['feed_id'] ) ) {
				$nf = '&feed_id=' . sanitize_text_field( $_GET['feed_id'] );
			} else {
				$nf = '';
			}
			$html .= sprintf( '<a class="nav-tab%1$s" href="?page=xfgmcexport&tab=%2$s%3$s">%4$s</a>', $class, $tab, $nf, $name );
		}
		$html .= '</div>';

		return $html;
	} // end get_html_tabs();

	public function get_html_main_settings() {
		$xfgmc_status_cron = xfgmc_optionGET( 'xfgmc_status_cron', $this->get_feed_id(), 'set_arr' );
		$xfgmc_ufup = xfgmc_optionGET( 'xfgmc_ufup', $this->get_feed_id(), 'set_arr' );
		$xfgmc_feed_assignment = stripslashes( htmlspecialchars( xfgmc_optionGET( 'xfgmc_feed_assignment', $this->get_feed_id(), 'set_arr' ) ) );
		$xfgmc_adapt_facebook = xfgmc_optionGET( 'xfgmc_adapt_facebook', $this->get_feed_id(), 'set_arr' );

		$xfgmc_target_country = xfgmc_optionGET( 'xfgmc_target_country', $this->get_feed_id(), 'set_arr' );
		$xfgmc_step_export = xfgmc_optionGET( 'xfgmc_step_export', $this->get_feed_id(), 'set_arr' );
		$xfgmc_cache = xfgmc_optionGET( 'xfgmc_cache', $this->get_feed_id(), 'set_arr' );

		$xfgmc_usa_tax_info = xfgmc_optionGET( 'xfgmc_usa_tax_info', $this->get_feed_id(), 'set_arr' );
		$xfgmc_tax_region = xfgmc_optionGET( 'xfgmc_tax_region', $this->get_feed_id(), 'set_arr' );
		$xfgmc_tax_rate = xfgmc_optionGET( 'xfgmc_tax_rate', $this->get_feed_id(), 'set_arr' );
		$xfgmc_sipping_tax = xfgmc_optionGET( 'xfgmc_sipping_tax', $this->get_feed_id(), 'set_arr' );
		?>
		<div class="postbox">
			<h2 class="hndle">
				<?php _e( 'Main parameters', 'xml-for-google-merchant-center' ); ?> (
				<?php _e( 'Feed', 'xml-for-google-merchant-center' ); ?> ID:
				<?php echo $this->get_feed_id(); ?>)
			</h2>
			<div class="inside">
				<table class="form-table">
					<tbody>

						<tr>
							<th scope="row"><label for="xfgmc_run_cron">
									<?php _e( 'Automatic file creation', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_run_cron" id="xfgmc_run_cron">
									<option value="off" <?php selected( $xfgmc_status_cron, 'off' ); ?>><?php _e( 'Off', 'xml-for-google-merchant-center' ); ?></option>
									<?php $xfgmc_enable_five_min = xfgmc_optionGET( 'xfgmc_enable_five_min' );
									if ( $xfgmc_enable_five_min === 'on' ) : ?>
										<option value="five_min" <?php selected( $xfgmc_status_cron, 'five_min' ); ?>><?php _e( 'Every five minutes', 'xml-for-google-merchant-center' ); ?></option>
									<?php endif; ?>
									<option value="hourly" <?php selected( $xfgmc_status_cron, 'hourly' ) ?>><?php _e( 'Hourly', 'xml-for-google-merchant-center' ); ?></option>
									<option value="six_hours" <?php selected( $xfgmc_status_cron, 'six_hours' ); ?>><?php _e( 'Every six hours', 'xml-for-google-merchant-center' ); ?></option>
									<option value="twicedaily" <?php selected( $xfgmc_status_cron, 'twicedaily' ) ?>><?php _e( 'Twice a day', 'xml-for-google-merchant-center' ); ?></option>
									<option value="daily" <?php selected( $xfgmc_status_cron, 'daily' ) ?>><?php _e( 'Daily', 'xml-for-google-merchant-center' ); ?></option>
								</select><br />
								<span class="description"><small>
										<?php _e( 'The refresh interval on your feed', 'xml-for-google-merchant-center' ); ?>
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_ufup">
									<?php _e( 'Update feed when updating products', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input type="checkbox" name="xfgmc_ufup" id="xfgmc_ufup" <?php checked( $xfgmc_ufup, 'on' ); ?> />
							</td>
						</tr>
						<?php do_action( 'xfgmc_after_ufup_option', $this->get_feed_id() ); /* С версии 2.1.0 */?>
						<tr>
							<th scope="row"><label for="xfgmc_feed_assignment">
									<?php _e( 'Feed assignment', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input type="text" maxlength="25" name="xfgmc_feed_assignment" id="xfgmc_feed_assignment"
									value="<?php echo $xfgmc_feed_assignment; ?>"
									placeholder="<?php _e( 'For Google', 'xml-for-google-merchant-center' ); ?>" /><br />
								<span class="description"><small>
										<?php _e( 'Not used in feed. Inner note for your convenience', 'xml-for-google-merchant-center' ); ?>.
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_adapt_facebook">
									<?php _e( 'Adapt for Facebook', 'xml-for-google-merchant-center' ); ?> (beta)
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_adapt_facebook" id="xfgmc_adapt_facebook">
									<option value="no" <?php selected( $xfgmc_adapt_facebook, 'no' ); ?>><?php _e( 'No', 'xml-for-google-merchant-center' ); ?></option>
									<option value="yes" <?php selected( $xfgmc_adapt_facebook, 'yes' ); ?>><?php _e( 'Yes', 'xml-for-google-merchant-center' ); ?></option>
								</select><br />
								<span class="description"><small>
										<?php _e( 'If you want to create a Facebook feed, set the value to ', 'xml-for-google-merchant-center' ); ?>
										"
										<?php _e( 'Yes', 'xml-for-google-merchant-center' ); ?>".
										<?php _e( 'If the feed is for Google Merchant Center, select', 'xml-for-google-merchant-center' ); ?>
										"
										<?php _e( 'No', 'xml-for-google-merchant-center' ); ?>"
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_target_country">
									<?php _e( 'Target country', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_target_country" id="xfgmc_target_country">
									<?php print_html_tags_option( $xfgmc_target_country, COUNTRIES_LIST ); ?>
								</select><br />
								<span class="description"><small>
										<?php _e( 'Select your target country', 'xml-for-google-merchant-center' ); ?>
									</small></span>
							</td>
						</tr>
						<tr class="xfgmc_tr">
							<th scope="row"><label for="xfgmc_step_export">
									<?php _e( 'Step of export', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_step_export" id="xfgmc_step_export">
									<?php do_action( 'xfgmc_before_step_export_option', $this->get_feed_id() ); ?>
									<option value="80" <?php selected( $xfgmc_step_export, '80' ); ?>>80</option>
									<option value="200" <?php selected( $xfgmc_step_export, '200' ); ?>>200</option>
									<option value="300" <?php selected( $xfgmc_step_export, '300' ); ?>>300</option>
									<option value="450" <?php selected( $xfgmc_step_export, '450' ); ?>>450</option>
									<option value="500" <?php selected( $xfgmc_step_export, '500' ); ?>>500</option>
									<option value="800" <?php selected( $xfgmc_step_export, '800' ); ?>>800</option>
									<option value="1000" <?php selected( $xfgmc_step_export, '1000' ); ?>>1000</option>
									<?php do_action( 'xfgmc_after_step_export_option', $this->get_feed_id() ); ?>
								</select><br />
								<span class="description"><small>
										<?php _e( 'The value affects the speed of file creation', 'xml-for-google-merchant-center' ); ?>.
										<?php _e( 'If you have any problems with the generation of the file - try to reduce the value in this field', 'xml-for-google-merchant-center' ); ?>.
										<?php _e( 'More than 500 can only be installed on powerful servers', 'xml-for-google-merchant-center' ); ?>.
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_cache">
									<?php _e( 'Ignore plugin cache', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_cache" id="xfgmc_cache">
									<option value="disabled" <?php selected( $xfgmc_cache, 'disabled' ); ?>><?php _e( 'Disabled', 'xml-for-google-merchant-center' ); ?></option>
									<option value="enabled" <?php selected( $xfgmc_cache, 'enabled' ); ?>><?php _e( 'Enabled', 'xml-for-google-merchant-center' ); ?></option>
								</select><br />
								<span class="description"><small>
										<?php _e( "Changing this option can be useful if your feed prices don't change after syncing", 'xfgmc' ); ?>.
										<a
											href="https://icopydoc.ru/pochemu-ne-obnovilis-tseny-v-fide-para-slov-o-tihih-pravkah/?utm_source=xml-for-google-merchant-center&utm_medium=organic&utm_campaign=in-plugin-xml-for-google-merchant-center&utm_content=settings&utm_term=about-cache">
											<?php _e( 'Learn More', 'xml-for-google-merchant-center' ); ?>
										</a>.
									</small></span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="postbox">
			<h2 class="hndle">
				<?php _e( 'Tax settings for US', 'xml-for-google-merchant-center' ); ?> [g:tax]
			</h2>
			<div class="inside">
				<p><span>
						<?php _e( "Required for the United States when you need to override the account tax settings that you created in Merchant Center. This attribute exclusively covers US sales tax. Don't use it for other taxes, such as value-added tax (VAT) or import tax", "xfgmc" ); ?>
					</span></p>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="xfgmc_usa_tax_info">
									<?php _e( 'Tax', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_usa_tax_info" id="xfgmc_usa_tax_info">
									<option value="disabled" <?php selected( $xfgmc_usa_tax_info, 'disabled' ); ?>><?php _e( 'Disabled', 'xml-for-google-merchant-center' ); ?></option>
									<option value="enabled" <?php selected( $xfgmc_usa_tax_info, 'enabled' ); ?>><?php _e( 'Enabled', 'xml-for-google-merchant-center' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_tax_region">
									<?php _e( 'Region', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_tax_region" id="xfgmc_tax_region">
									<?php print_html_tags_option( $xfgmc_tax_region, USA_STATES ); ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_tax_rate">
									<?php _e( 'Rate tax', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input type="number" min="0" step="0.01" maxlength="25" name="xfgmc_tax_rate"
									id="xfgmc_tax_rate" value="<?php echo $xfgmc_tax_rate; ?>" placeholder="5,00" />
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_sipping_tax">
									<?php _e( 'Shipping tax', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_sipping_tax" id="xfgmc_sipping_tax">
									<option value="no" <?php selected( $xfgmc_sipping_tax, 'no' ); ?>>no</option>
									<option value="yes" <?php selected( $xfgmc_sipping_tax, 'yes' ); ?>>yes</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	} // end get_html_main_settings();

	public function get_html_shop_data() {
		$xfgmc_main_product = xfgmc_optionGET( 'xfgmc_main_product', $this->get_feed_id(), 'set_arr' );
		$xfgmc_shop_name = stripslashes( htmlspecialchars( xfgmc_optionGET( 'xfgmc_shop_name', $this->get_feed_id(), 'set_arr' ) ) );
		$xfgmc_def_store_code = xfgmc_optionGET( 'xfgmc_def_store_code', $this->get_feed_id(), 'set_arr' );
		$xfgmc_default_currency = xfgmc_optionGET( 'xfgmc_default_currency', $this->get_feed_id(), 'set_arr' );
		$xfgmc_wooc_currencies = xfgmc_optionGET( 'xfgmc_wooc_currencies', $this->get_feed_id(), 'set_arr' );
		?>
		<div class="postbox">
			<h2 class="hndle">
				<?php _e( 'Shop data', 'xml-for-google-merchant-center' ); ?> (
				<?php _e( 'Feed', 'xml-for-google-merchant-center' ); ?> ID:
				<?php echo $this->get_feed_id(); ?>)
			</h2>
			<div class="inside">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="xfgmc_main_product">
									<?php _e( 'What kind of products do you sell', 'xml-for-google-merchant-center' ); ?>?
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_main_product" id="xfgmc_main_product">
									<option value="electronics" <?php selected( $xfgmc_main_product, 'electronics' ); ?>><?php _e( 'Electronics', 'xml-for-google-merchant-center' ); ?></option>
									<option value="computer" <?php selected( $xfgmc_main_product, 'computer' ); ?>><?php _e( 'Computer techologies', 'xml-for-google-merchant-center' ); ?></option>
									<option value="clothes_and_shoes" <?php selected( $xfgmc_main_product, 'clothes_and_shoes' ); ?>><?php _e( 'Clothes and shoes', 'xml-for-google-merchant-center' ); ?></option>
									<option value="auto_parts" <?php selected( $xfgmc_main_product, 'auto_parts' ); ?>><?php _e( 'Auto parts', 'xml-for-google-merchant-center' ); ?></option>
									<option value="products_for_children" <?php selected( $xfgmc_main_product, 'products_for_children' ); ?>><?php _e( 'Products for children', 'xml-for-google-merchant-center' ); ?></option>
									<option value="sporting_goods" <?php selected( $xfgmc_main_product, 'sporting_goods' ); ?>>
										<?php _e( 'Sporting goods', 'xml-for-google-merchant-center' ); ?></option>
									<option value="goods_for_pets" <?php selected( $xfgmc_main_product, 'goods_for_pets' ); ?>>
										<?php _e( 'Goods for pets', 'xml-for-google-merchant-center' ); ?></option>
									<option value="sexshop" <?php selected( $xfgmc_main_product, 'sexshop' ); ?>><?php _e( 'Sex shop (Adult products)', 'xml-for-google-merchant-center' ); ?></option>
									<option value="books" <?php selected( $xfgmc_main_product, 'books' ); ?>><?php _e( 'Books', 'xml-for-google-merchant-center' ); ?></option>
									<option value="health" <?php selected( $xfgmc_main_product, 'health' ); ?>><?php _e( 'Health products', 'xml-for-google-merchant-center' ); ?></option>
									<option value="food" <?php selected( $xfgmc_main_product, 'food' ); ?>><?php _e( 'Food', 'xml-for-google-merchant-center' ); ?></option>
									<option value="construction_materials" <?php selected( $xfgmc_main_product, 'construction_materials' ); ?>><?php _e( 'Construction Materials', 'xml-for-google-merchant-center' ); ?></option>
									<option value="other" <?php selected( $xfgmc_main_product, 'other' ); ?>><?php _e( 'Other', 'xml-for-google-merchant-center' ); ?></option>
								</select><br />
								<span class="description"><small>
										<?php _e( 'Specify the main category', 'xml-for-google-merchant-center' ); ?>
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_shop_name">
									<?php _e( 'Shop name', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input maxlength="20" type="text" name="xfgmc_shop_name" id="xfgmc_shop_name"
									value="<?php echo $xfgmc_shop_name; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'Required element', 'xml-for-google-merchant-center' ); ?>
										<strong>title</strong>.
										<?php _e( 'The short name of the store should not exceed 20 characters', 'xml-for-google-merchant-center' ); ?>.
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_def_store_code">
									<?php _e( 'Store code', 'xml-for-google-merchant-center' ); ?>
								</label><br />(
								<?php _e( 'In most cases, you can leave this field blank', 'xml-for-google-merchant-center' ); ?>)
							</th>
							<td class="overalldesc">
								<input type="text" name="xfgmc_def_store_code" id="xfgmc_def_store_code"
									value="<?php echo $xfgmc_def_store_code; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'Optional attribute', 'xml-for-google-merchant-center' ); ?>
										<strong>store_code</strong>. <a href="//support.google.com/merchants/answer/9673755"
											target="_blank">
											<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
										</a>
									</small></span>
							</td>
						</tr>
						<?php do_action( 'xfgmc_before_default_currency', $this->get_feed_id() ); ?>
						<tr>
							<th scope="row"><label for="xfgmc_default_currency">
									<?php _e( 'Store currency', 'xml-for-google-merchant-center' ); ?>
								</label><br />(
								<?php _e( 'Uppercase letter', 'xml-for-google-merchant-center' ); ?>!)
							</th>
							<td class="overalldesc">
								<input type="text" placeholder="USD" name="xfgmc_default_currency" id="xfgmc_default_currency"
									value="<?php echo $xfgmc_default_currency; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'For example', 'xml-for-google-merchant-center' ); ?>: <strong>USD</strong>.
										<a href="//support.google.com/merchants/answer/160637" target="_blank">
											<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
										</a>
									</small></span>
							</td>
						</tr>
						<?php if ( class_exists( 'WOOCS' ) ) :
							global $WOOCS;
							$currencies_arr = $WOOCS->get_currencies();
							if ( is_array( $currencies_arr ) ) :
								$array_keys = array_keys( $currencies_arr ); ?>
								<tr>
									<th scope="row"><label for="xfgmc_wooc_currencies">
											<?php _e( 'Feed currency', 'xml-for-google-merchant-center' ); ?>
										</label></th>
									<td class="overalldesc">
										<select name="xfgmc_wooc_currencies" id="xfgmc_wooc_currencies">
											<?php for ( $i = 0; $i < count( $array_keys ); $i++ ) : ?>
												<option value="<?php echo $currencies_arr[ $array_keys[ $i ] ]['name']; ?>" <?php selected( $xfgmc_wooc_currencies, $currencies_arr[ $array_keys[ $i ] ]['name'] ); ?>><?php echo $currencies_arr[ $array_keys[ $i ] ]['name']; ?></option>
											<?php endfor; ?>
										</select><br />
										<span class="description"><small>
												<?php _e( 'You have plugin installed', 'xml-for-google-merchant-center' ); ?> <strong
													class="xfgmc_bold">WooCommerce Currency Switcher by PluginUs.NET. Woo Multi Currency
													and Woo Multi Pay</strong><br />
												<?php _e( 'Indicate in what currency the prices should be', 'xml-for-google-merchant-center' ); ?>.<br /><strong
													class="xfgmc_bold">
													<?php _e( 'Please note', 'xml-for-google-merchant-center' ); ?>:
												</strong>
												<?php _e( 'The currency must match the one you specified in the field above', 'xml-for-google-merchant-center' ); ?>
											</small>
										</span>
									</td>
								</tr>
							<?php endif; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	} // end get_html_shop_data();

	public function get_html_shipping_setting() {
		$xfgmc_def_shipping_weight_unit = xfgmc_optionGET( 'xfgmc_def_shipping_weight_unit', $this->get_feed_id(), 'set_arr' );
		$xfgmc_def_shipping_country = xfgmc_optionGET( 'xfgmc_def_shipping_country', $this->get_feed_id(), 'set_arr' );
		$xfgmc_def_delivery_area_type = xfgmc_optionGET( 'xfgmc_def_delivery_area_type', $this->get_feed_id(), 'set_arr' );
		$xfgmc_def_delivery_area_value = xfgmc_optionGET( 'xfgmc_def_delivery_area_value', $this->get_feed_id(), 'set_arr' );
		$xfgmc_def_shipping_service = xfgmc_optionGET( 'xfgmc_def_shipping_service', $this->get_feed_id(), 'set_arr' );
		$xfgmc_def_shipping_price = xfgmc_optionGET( 'xfgmc_def_shipping_price', $this->get_feed_id(), 'set_arr' ); ?>
		<div class="postbox">
			<h2 class="hndle">
				<?php _e( 'Shipping', 'xml-for-google-merchant-center' ); ?>
			</h2>
			<div class="inside">
				<p><i>
						<?php _e( 'Google recommend that you set up shipping costs through Merchant Center settings instead of submitting the shipping attribute in the feed', 'xml-for-google-merchant-center' ); ?>.
						<a href="//support.google.com/merchants/answer/6069284" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>
					</i></p>
				<p><i>
						<?php _e( 'To add this element to your feed make sure the fields are filled', 'xml-for-google-merchant-center' ); ?>
						"country"
						<?php _e( 'and', 'xml-for-google-merchant-center' ); ?> "
						<?php _e( 'Delivery area', 'xml-for-google-merchant-center' ); ?>". <a
							href="//support.google.com/merchants/answer/6324484" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>
					</i></p>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="xfgmc_def_shipping_weight_unit">
									<?php _e( 'Unit of measurement of', 'xml-for-google-merchant-center' ); ?> shipping_weight
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_def_shipping_weight_unit" id="xfgmc_def_shipping_weight_unit">
									<option value="kg" <?php selected( $xfgmc_def_shipping_weight_unit, 'kg' ) ?>>kg</option>
									<option value="g" <?php selected( $xfgmc_def_shipping_weight_unit, 'g' ) ?>>g</option>
									<option value="lbs" <?php selected( $xfgmc_def_shipping_weight_unit, 'lbs' ) ?>>lbs</option>
									<option value="oz" <?php selected( $xfgmc_def_shipping_weight_unit, 'oz' ) ?>>oz</option>
								</select>
							</td>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_def_shipping_country">
									<?php _e( 'Attribute', 'xml-for-google-merchant-center' ); ?> country
								</label></th>
							<td class="overalldesc">
								<input type="text" name="xfgmc_def_shipping_country" id="xfgmc_def_shipping_country"
									value="<?php echo $xfgmc_def_shipping_country; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'Required attribute', 'xml-for-google-merchant-center' ); ?>
										<strong>shipping_country</strong>.
										<?php _e( 'Leave this field blank if you do not want to add a default value', 'xml-for-google-merchant-center' ); ?>.
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php _e( 'Delivery area', 'xml-for-google-merchant-center' ); ?><select
									name="xfgmc_def_delivery_area_type">
									<option value="region" <?php selected( $xfgmc_def_delivery_area_type, 'region' ); ?>>region
									</option>
									<option value="postal_code" <?php selected( $xfgmc_def_delivery_area_type, 'postal_code' ); ?>>postal_code</option>
									<option value="location_id" <?php selected( $xfgmc_def_delivery_area_type, 'location_id' ); ?>>location_id</option>
									<option value="location_group_name" <?php selected( $xfgmc_def_delivery_area_type, 'location_group_name' ); ?>>location_group_name</option>
								</select>
							</th>
							<td class="overalldesc">
								<input type="text" name="xfgmc_def_delivery_area_value"
									value="<?php echo $xfgmc_def_delivery_area_value; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'To specify a delivery area (which is optional), submit 1 of the 4 available options for the shipping attribute', 'xml-for-google-merchant-center' ); ?>.
										<a href="//support.google.com/merchants/answer/6324484" target="_blank">
											<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
										</a>
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_def_shipping_service">
									<?php _e( 'Attribute', 'xml-for-google-merchant-center' ); ?> service
								</label></th>
							<td class="overalldesc">
								<input type="text" name="xfgmc_def_shipping_service" id="xfgmc_def_shipping_service"
									value="<?php echo $xfgmc_def_shipping_service; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'Optional attribute', 'xml-for-google-merchant-center' ); ?>
										<strong>service</strong>.
										<?php _e( 'Leave this field blank if you do not want to add a default value', 'xml-for-google-merchant-center' ); ?>.
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_def_shipping_price">
									<?php _e( 'Attribute', 'xml-for-google-merchant-center' ); ?> price
								</label></th>
							<td class="overalldesc">
								<input type="text" name="xfgmc_def_shipping_price" id="xfgmc_def_shipping_price"
									value="<?php echo $xfgmc_def_shipping_price; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'Optional attribute', 'xml-for-google-merchant-center' ); ?>
										<strong>price</strong>.
										<?php _e( 'Leave this field blank if you do not want to add a default value', 'xml-for-google-merchant-center' ); ?>.
									</small></span>
							</td>
						</tr>
						<?php do_action( 'xfgmc_append_optional_elemet_table', $this->get_feed_id() ); ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	} // end get_html_shipping_setting();

	public function get_html_tags_settings() {
		$xfgmc_shop_description = stripslashes( htmlspecialchars( xfgmc_optionGET( 'xfgmc_shop_description', $this->get_feed_id(), 'set_arr' ) ) );
		$xfgmc_behavior_onbackorder = xfgmc_optionGET( 'xfgmc_behavior_onbackorder', $this->get_feed_id(), 'set_arr' );
		$availability_date = xfgmc_optionGET( 'xfgmc_availability_date', $this->get_feed_id(), 'set_arr' );
		$xfgmc_product_type = xfgmc_optionGET( 'xfgmc_product_type', $this->get_feed_id(), 'set_arr' );
		$xfgmc_product_type_home = xfgmc_optionGET( 'xfgmc_product_type_home', $this->get_feed_id(), 'set_arr' );

		?>
		<div class="postbox">
			<h2 class="hndle">
				<?php _e( 'Tags settings', 'xml-for-google-merchant-center' ); ?> (
				<?php _e( 'Feed', 'xml-for-google-merchant-center' ); ?> ID:
				<?php echo $this->get_feed_id(); ?>)
			</h2>
			<div class="inside">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="xfgmc_shop_description">
									<?php _e( 'The name of your data feed', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input type="text" name="xfgmc_shop_description" id="xfgmc_shop_description"
									value="<?php echo $xfgmc_shop_description; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'Required element', 'xml-for-google-merchant-center' ); ?>
										<strong>description</strong>.
										<?php _e( 'The name of your data feed', 'xml-for-google-merchant-center' ); ?>.
									</small></span>
							</td>
						</tr>
						<tr class="xfgmc_tr">
							<th scope="row"><label for="xfgmc_behavior_onbackorder">
									<?php _e( 'For pre-order products, establish availability equal to', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_behavior_onbackorder" id="xfgmc_behavior_onbackorder">
									<option value="in_stock" <?php selected( $xfgmc_behavior_onbackorder, 'in_stock' ); ?>>
										in_stock</option>
									<option value="out_of_stock" <?php selected( $xfgmc_behavior_onbackorder, 'out_of_stock' ) ?>>
										out_of_stock</option>
									<option value="onbackorder" <?php selected( $xfgmc_behavior_onbackorder, 'onbackorder' ) ?>>
										preorder</option>
								</select><br />
								<span class="description"><small>
										<?php _e( 'For pre-order products, establish availability equal to', 'xml-for-google-merchant-center' ); ?>
										in_stock/out_of_stock/preorder
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_availability_date">
									<?php _e( 'Availability date', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input type="text" name="xfgmc_availability_date" id="xfgmc_availability_date"
									placeholder="2016-12-25T13:00-0800" value="<?php echo $availability_date; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'Optional element', 'xml-for-google-merchant-center' ); ?>
										<strong>availability_date</strong>.
										<?php _e(
											'This parameter applies only to products whose status is equal to',
											'xml-for-google-merchant-center' ); ?> "preorder"
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_product_type">
									<?php _e( 'Product type', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_product_type" id="xfgmc_product_type">
									<option value="disabled" <?php selected( $xfgmc_product_type, 'disabled' ); ?>><?php _e( 'Disabled', 'xml-for-google-merchant-center' ); ?></option>
									<option value="enabled" <?php selected( $xfgmc_product_type, 'enabled' ); ?>><?php _e( 'Enabled', 'xml-for-google-merchant-center' ); ?></option>
								</select><br />
								<span class="description">
									<?php _e( 'Add root element', 'xml-for-google-merchant-center' ); ?>:
								</span><br />
								<input type="text" name="xfgmc_product_type_home" id="xfgmc_product_type_home"
									placeholder="<?php _e( 'Home', 'xml-for-google-merchant-center' ); ?>"
									value="<?php echo $xfgmc_product_type_home; ?>" /><br />
								<span class="description"><small>
										<?php _e( 'Optional element', 'xml-for-google-merchant-center' ); ?>
										<strong>product_type</strong>.
									</small></span>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
		<?php
	} // end get_html_tags_settings();

	public function get_html_filtration() {
		$xfgmc_whot_export = xfgmc_optionGET( 'xfgmc_whot_export', $this->get_feed_id(), 'set_arr' );
		$xfgmc_the_content = xfgmc_optionGET( 'xfgmc_the_content', $this->get_feed_id(), 'set_arr' );
		$xfgmc_var_desc_priority = xfgmc_optionGET( 'xfgmc_var_desc_priority', $this->get_feed_id(), 'set_arr' );
		$xfgmc_skip_missing_products = xfgmc_optionGET( 'xfgmc_skip_missing_products', $this->get_feed_id(), 'set_arr' );
		$xfgmc_skip_backorders_products = xfgmc_optionGET( 'xfgmc_skip_backorders_products', $this->get_feed_id(), 'set_arr' );
		$xfgmc_no_default_png_products = xfgmc_optionGET( 'xfgmc_no_default_png_products', $this->get_feed_id(), 'set_arr' );
		$xfgmc_one_variable = xfgmc_optionGET( 'xfgmc_one_variable', $this->get_feed_id(), 'set_arr' );
		?>
		<div class="postbox">
			<h2 class="hndle">
				<?php _e( 'Filtration', 'xml-for-google-merchant-center' ); ?> (
				<?php _e( 'Feed', 'xml-for-google-merchant-center' ); ?> ID:
				<?php echo $this->get_feed_id(); ?>)
			</h2>
			<div class="inside">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="xfgmc_whot_export">
									<?php _e( 'Whot export', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_whot_export" id="xfgmc_whot_export">
									<option value="all" <?php selected( $xfgmc_whot_export, 'all' ); ?>><?php _e( 'Simple & Variable products', 'xml-for-google-merchant-center' ); ?></option>
									<option value="simple" <?php selected( $xfgmc_whot_export, 'simple' ); ?>><?php _e( 'Only simple products', 'xml-for-google-merchant-center' ); ?></option>
									<option value="variable" <?php selected( $xfgmc_whot_export, 'variable' ); ?>><?php _e( 'Only Variable products', 'xml-for-google-merchant-center' ); ?></option>
									<?php do_action( 'xfgmc_after_whot_export_option', $this->get_feed_id() ); ?>
								</select><br />
								<span class="description"><small>
										<?php _e( 'Whot export', 'xml-for-google-merchant-center' ); ?>
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_the_content">
									<?php _e( 'Use the filter', 'xml-for-google-merchant-center' ); ?> the_content
								</label></th>
							<td class="overalldesc">
								<select name="xfgmc_the_content" id="xfgmc_the_content">
									<option value="disabled" <?php selected( $xfgmc_the_content, 'disabled' ); ?>><?php _e( 'Disabled', 'xml-for-google-merchant-center' ); ?></option>
									<option value="enabled" <?php selected( $xfgmc_the_content, 'enabled' ); ?>><?php _e( 'Enabled', 'xml-for-google-merchant-center' ); ?></option>
								</select><br />
								<span class="description"><small>
										<?php _e( 'Default', 'xml-for-google-merchant-center' ); ?>:
										<?php _e( 'Enabled', 'xml-for-google-merchant-center' ); ?>
									</small></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_var_desc_priority">
									<?php _e( 'The varition description takes precedence over others', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input type="checkbox" name="xfgmc_var_desc_priority" id="xfgmc_var_desc_priority" <?php checked( $xfgmc_var_desc_priority, 'on' ); ?> />
							</td>
						</tr>
						<tr class="xfgmc_tr">
							<th scope="row"><label for="xfgmc_skip_missing_products">
									<?php _e( 'Skip missing products', 'xml-for-google-merchant-center' ); ?> (
									<?php _e( 'except for products for which a pre-order is permitted', 'xml-for-google-merchant-center' ); ?>.)
								</label></th>
							<td class="overalldesc">
								<input type="checkbox" name="xfgmc_skip_missing_products" id="xfgmc_skip_missing_products" <?php checked( $xfgmc_skip_missing_products, 'on' ); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_skip_backorders_products">
									<?php _e( 'Skip backorders products', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input type="checkbox" name="xfgmc_skip_backorders_products" id="xfgmc_skip_backorders_products"
									<?php checked( $xfgmc_skip_backorders_products, 'on' ); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_no_default_png_products">
									<?php _e( 'Remove default.png from XML', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input type="checkbox" name="xfgmc_no_default_png_products" id="xfgmc_no_default_png_products"
									<?php checked( $xfgmc_no_default_png_products, 'on' ); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="xfgmc_one_variable">
									<?php _e( 'Upload only the first variation', 'xml-for-google-merchant-center' ); ?>
								</label></th>
							<td class="overalldesc">
								<input type="checkbox" name="xfgmc_one_variable" id="xfgmc_one_variable" <?php checked( $xfgmc_one_variable, 'on' ); ?> />
							</td>
						</tr>

						<?php do_action( 'xfgmc_append_main_param_table', $this->get_feed_id() ); ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	} // end get_html_filtration();

	public function get_html_icp_banners() { ?>
		<div id="icp_slides" class="clear">
			<div class="icp_wrap">
				<input type="radio" name="icp_slides" id="icp_point1">
				<input type="radio" name="icp_slides" id="icp_point2">
				<input type="radio" name="icp_slides" id="icp_point3" checked>
				<input type="radio" name="icp_slides" id="icp_point4">
				<input type="radio" name="icp_slides" id="icp_point5">
				<input type="radio" name="icp_slides" id="icp_point6">
				<input type="radio" name="icp_slides" id="icp_point7">
				<div class="icp_slider">
					<div class="icp_slides icp_img1"><a href="//wordpress.org/plugins/yml-for-yandex-market/"
							target="_blank"></a></div>
					<div class="icp_slides icp_img2"><a href="//wordpress.org/plugins/import-products-to-ok-ru/"
							target="_blank"></a></div>
					<div class="icp_slides icp_img3"><a href="//wordpress.org/plugins/xml-for-google-merchant-center/"
							target="_blank"></a></div>
					<div class="icp_slides icp_img4"><a href="//wordpress.org/plugins/gift-upon-purchase-for-woocommerce/"
							target="_blank"></a></div>
					<div class="icp_slides icp_img5"><a href="//wordpress.org/plugins/xml-for-avito/" target="_blank"></a></div>
					<div class="icp_slides icp_img6"><a href="//wordpress.org/plugins/xml-for-o-yandex/" target="_blank"></a>
					</div>
					<div class="icp_slides icp_img7"><a href="//wordpress.org/plugins/import-from-yml/" target="_blank"></a>
					</div>
				</div>
				<div class="icp_control">
					<label for="icp_point1"></label>
					<label for="icp_point2"></label>
					<label for="icp_point3"></label>
					<label for="icp_point4"></label>
					<label for="icp_point5"></label>
					<label for="icp_point6"></label>
					<label for="icp_point7"></label>
				</div>
			</div>
		</div>
		<?php
	} // end get_html_icp_banners()

	public function get_html_my_plugins_list() { ?>
		<div class="metabox-holder">
			<div class="postbox">
				<h2 class="hndle">
					<?php _e( 'My plugins that may interest you', 'xml-for-google-merchant-center' ); ?>
				</h2>
				<div class="inside">
					<p><span class="xfgmc_bold">XML for Google Merchant Center</span> -
						<?php _e( 'Сreates a XML-feed to upload to Google Merchant Center', 'xml-for-google-merchant-center' ); ?>.
						<a href="https://wordpress.org/plugins/xml-for-google-merchant-center/" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
					<p><span class="xfgmc_bold">YML for Yandex Market</span> -
						<?php _e( 'Сreates a YML-feed for importing your products to Yandex Market', 'xml-for-google-merchant-center' ); ?>.
						<a href="https://wordpress.org/plugins/yml-for-yandex-market/" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
					<p><span class="xfgmc_bold">Import from YML</span> -
						<?php _e( 'Imports products from YML to your shop', 'xml-for-google-merchant-center' ); ?>. <a
							href="https://wordpress.org/plugins/import-from-yml/" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
					<p><span class="xfgmc_bold">Integrate myTarget for WooCommerce</span> -
						<?php _e( 'This plugin helps setting up myTarget counter for dynamic remarketing for WooCommerce', 'xml-for-google-merchant-center' ); ?>.
						<a href="https://wordpress.org/plugins/wc-mytarget/" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
					<p><span class="xfgmc_bold">XML for Hotline</span> -
						<?php _e( 'Сreates a XML-feed for importing your products to Hotline', 'xml-for-google-merchant-center' ); ?>.
						<a href="https://wordpress.org/plugins/xml-for-hotline/" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
					<p><span class="xfgmc_bold">Gift upon purchase for WooCommerce</span> -
						<?php _e( 'This plugin will add a marketing tool that will allow you to give gifts to the buyer upon purchase', 'xml-for-google-merchant-center' ); ?>.
						<a href="https://wordpress.org/plugins/gift-upon-purchase-for-woocommerce/" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
					<p><span class="xfgmc_bold">Import products to ok.ru</span> -
						<?php _e( 'With this plugin, you can import products to your group on ok.ru', 'xml-for-google-merchant-center' ); ?>.
						<a href="https://wordpress.org/plugins/import-products-to-ok-ru/" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
					<p><span class="xfgmc_bold">XML for Avito</span> -
						<?php _e( 'Сreates a XML-feed for importing your products to', 'xml-for-google-merchant-center' ); ?>
						Avito. <a href="https://wordpress.org/plugins/xml-for-avito/" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
					<p><span class="xfgmc_bold">XML for O.Yandex (Яндекс Объявления)</span> -
						<?php _e( 'Сreates a XML-feed for importing your products to', 'xml-for-google-merchant-center' ); ?>
						Яндекс.Объявления. <a href="https://wordpress.org/plugins/xml-for-o-yandex/" target="_blank">
							<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
				</div>
			</div>
		</div>
		<?php
	} // end get_html_my_plugins_list()

	private function get_feed_id() {
		return $this->feed_id;
	}

	private function save_plugin_set( $opt_name, $feed_id, $save_if_empty = false ) {
		if ( isset( $_POST[ $opt_name ] ) ) {
			xfgmc_optionUPD( $opt_name, sanitize_text_field( $_POST[ $opt_name ] ), $feed_id, 'yes', 'set_arr' );
		} else {
			if ( $save_if_empty === true ) {
				xfgmc_optionUPD( $opt_name, '0', $feed_id, 'yes', 'set_arr' );
			}
		}
		return;
	}

	private function listen_submit() {
		// массовое удаление фидов по чекбоксу checkbox_xml_file
		if ( isset( $_GET['xfgmc_form_id'] ) && ( $_GET['xfgmc_form_id'] === 'xfgmc_wp_list_table' ) ) {
			if ( is_array( $_GET['checkbox_xml_file'] ) && ! empty( $_GET['checkbox_xml_file'] ) ) {
				if ( $_GET['action'] === 'delete' || $_GET['action2'] === 'delete' ) {
					$checkbox_xml_file_arr = $_GET['checkbox_xml_file'];
					$xfgmc_settings_arr = xfgmc_optionGET( 'xfgmc_settings_arr' );
					for ( $i = 0; $i < count( $checkbox_xml_file_arr ); $i++ ) {
						$feed_id = $checkbox_xml_file_arr[ $i ];
						unset( $xfgmc_settings_arr[ $feed_id ] );
						wp_clear_scheduled_hook( 'xfgmc_cron_period', array( $feed_id ) ); // отключаем крон
						wp_clear_scheduled_hook( 'xfgmc_cron_sborki', array( $feed_id ) ); // отключаем крон
						$upload_dir = (object) wp_get_upload_dir();
						$name_dir = $upload_dir->basedir . "/xfgmc";
						//				$filename = $name_dir.'/ids_in_xml.tmp'; if (file_exists($filename)) {unlink($filename);}
						xfgmc_remove_directory( $name_dir . '/feed' . $feed_id );
						xfgmc_optionDEL( 'xfgmc_status_sborki', $i );

						$xfgmc_registered_feeds_arr = xfgmc_optionGET( 'xfgmc_registered_feeds_arr' );
						for ( $n = 1; $n < count( $xfgmc_registered_feeds_arr ); $n++ ) { // первый элемент не проверяем, тк. там инфо по последнему id
							if ( $xfgmc_registered_feeds_arr[ $n ]['id'] === $feed_id ) {
								unset( $xfgmc_registered_feeds_arr[ $n ] );
								$xfgmc_registered_feeds_arr = array_values( $xfgmc_registered_feeds_arr );
								xfgmc_optionUPD( 'xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr );
								break;
							}
						}
					}
					xfgmc_optionUPD( 'xfgmc_settings_arr', $xfgmc_settings_arr );
					$feed_id = xfgmc_get_first_feed_id();
				}
			}
		}

		if ( isset( $_GET['feed_id'] ) ) {
			if ( isset( $_GET['action'] ) ) {
				$action = sanitize_text_field( $_GET['action'] );
				switch ( $action ) {
					case 'edit':
						$feed_id = sanitize_text_field( $_GET['feed_id'] );
						break;
					case 'delete':
						$feed_id = sanitize_text_field( $_GET['feed_id'] );
						$xfgmc_settings_arr = xfgmc_optionGET( 'xfgmc_settings_arr' );
						unset( $xfgmc_settings_arr[ $feed_id ] );
						wp_clear_scheduled_hook( 'xfgmc_cron_period', array( $feed_id ) ); // отключаем крон
						wp_clear_scheduled_hook( 'xfgmc_cron_sborki', array( $feed_id ) ); // отключаем крон
						$upload_dir = (object) wp_get_upload_dir();
						$name_dir = $upload_dir->basedir . "/xfgmc";
						//				$filename = $name_dir.'/ids_in_xml.tmp'; if (file_exists($filename)) {unlink($filename);}
						xfgmc_remove_directory( $name_dir . '/feed' . $feed_id );
						xfgmc_optionUPD( 'xfgmc_settings_arr', $xfgmc_settings_arr );
						xfgmc_optionDEL( 'xfgmc_status_sborki', $feed_id );
						$xfgmc_registered_feeds_arr = xfgmc_optionGET( 'xfgmc_registered_feeds_arr' );
						for ( $n = 1; $n < count( $xfgmc_registered_feeds_arr ); $n++ ) { // первый элемент не проверяем, тк. там инфо по последнему id
							if ( $xfgmc_registered_feeds_arr[ $n ]['id'] === $feed_id ) {
								unset( $xfgmc_registered_feeds_arr[ $n ] );
								$xfgmc_registered_feeds_arr = array_values( $xfgmc_registered_feeds_arr );
								xfgmc_optionUPD( 'xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr );
								break;
							}
						}

						$feed_id = xfgmc_get_first_feed_id();
						break;
					default:
						$feed_id = xfgmc_get_first_feed_id();
				}
			} else {
				$feed_id = sanitize_text_field( $_GET['feed_id'] );
			}
		} else {
			$feed_id = xfgmc_get_first_feed_id();
		}

		if ( isset( $_REQUEST['xfgmc_submit_add_new_feed'] ) ) { // если создаём новый фид
			if ( ! empty( $_POST ) && check_admin_referer( 'xfgmc_nonce_action_add_new_feed', 'xfgmc_nonce_field_add_new_feed' ) ) {
				$xfgmc_settings_arr = xfgmc_optionGET( 'xfgmc_settings_arr' );

				if ( is_multisite() ) {
					$xfgmc_registered_feeds_arr = get_blog_option( get_current_blog_id(), 'xfgmc_registered_feeds_arr' );
					$feed_id = $xfgmc_registered_feeds_arr[0]['last_id'];
					$feed_id++;
					$xfgmc_registered_feeds_arr[0]['last_id'] = (string) $feed_id;
					$xfgmc_registered_feeds_arr[] = array( 'id' => (string) $feed_id );
					update_blog_option( get_current_blog_id(), 'xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr );
				} else {
					$xfgmc_registered_feeds_arr = get_option( 'xfgmc_registered_feeds_arr' );
					$feed_id = $xfgmc_registered_feeds_arr[0]['last_id'];
					$feed_id++;
					$xfgmc_registered_feeds_arr[0]['last_id'] = (string) $feed_id;
					$xfgmc_registered_feeds_arr[] = array( 'id' => (string) $feed_id );
					update_option( 'xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr );
				}

				$upload_dir = (object) wp_get_upload_dir();
				$name_dir = $upload_dir->basedir . '/xfgmc/feed' . $feed_id;
				if ( ! is_dir( $name_dir ) ) {
					if ( ! mkdir( $name_dir ) ) {
						error_log( 'ERROR: Ошибка создания папки ' . $name_dir . '; Файл: export.php; Строка: ' . __LINE__, 0 );
					}
				}

				$def_plugin_date_arr = new XFGMC_Data_Arr();
				$xfgmc_settings_arr[ $feed_id ] = $def_plugin_date_arr->get_opts_name_and_def_date( 'all' );

				xfgmc_optionUPD( 'xfgmc_settings_arr', $xfgmc_settings_arr );

				xfgmc_optionADD( 'xfgmc_status_sborki', '-1', $feed_id );
				xfgmc_optionADD( 'xfgmc_last_element', '-1', $feed_id );
				print '<div class="updated notice notice-success is-dismissible"><p>' . __( 'Feed added', 'xml-for-google-merchant-center' ) . '. ID = ' . $feed_id . '.</p></div>';
			}
		}

		$status_sborki = (int) xfgmc_optionGET( 'xfgmc_status_sborki', $feed_id );

		if ( isset( $_REQUEST['xfgmc_submit_action'] ) ) {
			if ( ! empty( $_POST ) && check_admin_referer( 'xfgmc_nonce_action', 'xfgmc_nonce_field' ) ) {
				do_action( 'xfgmc_prepend_submit_action', $feed_id );

				$feed_id = sanitize_text_field( $_POST['xfgmc_num_feed_for_save'] );

				// 1335808087 - временная зона GMT (Unix формат)
				xfgmc_optionUPD( 'xfgmc_date_save_set', current_time( 'timestamp', 1 ), $feed_id, 'yes', 'set_arr' );

				if ( isset( $_POST['xfgmc_run_cron'] ) ) {
					$arr_maybe = array( 'off', 'five_min', 'hourly', 'six_hours', 'twicedaily', 'daily', 'week' );
					$xfgmc_run_cron = sanitize_text_field( $_POST['xfgmc_run_cron'] );

					if ( in_array( $xfgmc_run_cron, $arr_maybe ) ) {
						xfgmc_optionUPD( 'xfgmc_status_cron', $xfgmc_run_cron, $feed_id, 'yes', 'set_arr' );
						if ( $xfgmc_run_cron === 'off' ) {
							// отключаем крон
							wp_clear_scheduled_hook( 'xfgmc_cron_period', array( $feed_id ) );
							xfgmc_optionUPD( 'xfgmc_status_cron', 'off', $feed_id, 'yes', 'set_arr' );

							wp_clear_scheduled_hook( 'xfgmc_cron_sborki', array( $feed_id ) );
							xfgmc_optionUPD( 'xfgmc_status_sborki', '-1', $feed_id );
						} else {
							$recurrence = $xfgmc_run_cron;
							wp_clear_scheduled_hook( 'xfgmc_cron_period', array( $feed_id ) );
							wp_schedule_event( time(), $recurrence, 'xfgmc_cron_period', array( $feed_id ) );
							new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; xfgmc_cron_period внесен в список заданий; Файл: export.php; Строка: ' . __LINE__ );
						}
					} else {
						new XFGMC_Error_Log( 'Крон ' . $xfgmc_run_cron . ' не зарегистрирован. Файл: export.php; Строка: ' . __LINE__ );
					}
				}

				$def_plugin_date_arr = new XFGMC_Data_Arr();
				$opts_name_and_def_date_arr = $def_plugin_date_arr->get_opts_name_and_def_date( 'public' );
				foreach ( $opts_name_and_def_date_arr as $key => $value ) {
					$save_if_empty = false;
					switch ( $key ) {
						case 'xfgmc_status_cron':
						case 'xfgmcp_exclude_cat_arr': // селект категорий в прошке
							continue 2;
						case 'xfgmc_var_desc_priority':
						case 'xfgmc_one_variable':
						case 'xfgmc_skip_missing_products':
						case 'xfgmc_skip_backorders_products':
						case 'xfgmc_no_default_png_products':
						/* И галки в прошке */
						case 'xfgmcp_use_del_vc':
						case 'xfgmcp_excl_thumb':
						case 'xfgmcp_use_utm':
							if ( ! isset( $_GET['tab'] ) || ( $_GET['tab'] !== 'filtration' ) ) {
								continue 2;
							} else {
								$save_if_empty = true;
							}
							break;
						case 'xfgmc_ufup':
							if ( isset( $_GET['tab'] ) && ( $_GET['tab'] !== 'main_tab' ) ) {
								continue 2;
							} else {
								$save_if_empty = true;
							}
							break;
					}
					$this->save_plugin_set( $key, $feed_id, $save_if_empty );
				}

			}
		}

		$this->feed_id = $feed_id;
		return;
	}
}