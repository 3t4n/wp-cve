<?php
/**
 * Plugin admin pages functionality of the plugin.
 *
 * @link       http://www.quantcast.com
 * @since      1.0.0
 *
 * @package    QC_Choice
 * @subpackage QC_Choice/admin
 */

/**
 * QC Choice admin pages.
 *
 * @package    QC_Choice
 * @subpackage QC_Choice/admin
 * @author     Ryan Baron <rbaron@quantcast.com>
 */
class QC_Choice_Admin_Pages {
	/**
	 * Quantcast Universal Tag ID.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $qc_choice_cmp_utid    Quantcast Universal Tag ID.
	 */
	private $qc_choice_cmp_utid;

	/**
	 * Enable automatic Data Layer push.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $qc_choice_cmp_datalayer_push    Enable automatic push of consent signals to the data layer.
	 */
	private $qc_choice_cmp_datalayer_push;

	/**
	 * Enable CCPA automatically in the footer
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $qc_choice_cmp_ccpa_wp_footer    Quantcast Universal Tag ID.
	 */
	private $qc_choice_cmp_ccpa_wp_footer;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->qc_choice_cmp_utid = esc_attr( get_option( 'qc_choice_cmp_utid' ) );
		$this->qc_choice_cmp_datalayer_push = esc_attr( get_option( 'qc_choice_cmp_datalayer_push' ) );
		$this->qc_choice_cmp_ccpa_wp_footer = esc_attr( get_option( 'qc_choice_cmp_ccpa_wp_footer' ) );

		add_action( 'admin_init', array( $this, 'qc_choice_options_page_init' ) );
		add_action( 'admin_menu', array( $this, 'add_qc_choice_admin_pages' ) );
	}

	/**
	 * QC Choice admin options
	 *
	 * @since    1.0.0
	 */
	public function qc_choice_options() { ?>
		<div class="wrap wrap-qc-choice-options">

			<div class="plugin-title-wrapper">
				<h1><?php _e('Quantcast Choice', 'qc-choice'); ?></h1>
			</div>

			<?php settings_errors(); ?>

			<?php
				if( isset( $_GET[ 'tab' ] ) ) {
					$active_tab = $_GET[ 'tab' ];
				} else {
					$active_tab = 'overview_screen';
				}
			?>

			<div class="nav-tab-wrapper">
				<?php if ( 'overview_screen' === $active_tab ) { ?>
					<a href="?page=qc-choice-options&tab=overview_screen" class="nav-tab active"><?php _e( 'Overview', 'qc-choice' ); ?></a>
				<?php } else { ?>
					<a href="?page=qc-choice-options&tab=overview_screen" class="nav-tab"><?php _e( 'Overview', 'qc-choice' ); ?></a>
				<?php }?>

				<?php if ( 'tcfv2_screen' === $active_tab ) { ?>
					<a href="?page=qc-choice-options&tab=tcfv2_screen" class="nav-tab active"><?php _e( 'TCF v2.0 Settings', 'qc-choice' ); ?></a>
				<?php } else { ?>
					<a href="?page=qc-choice-options&tab=tcfv2_screen" class="nav-tab"><?php _e( 'TCF v2.0 Settings', 'qc-choice' ); ?></a>
				<?php }?>
			</div>

			<form method="post" action="options.php">

				<?php if( $active_tab === 'overview_screen' ) { ?>

					<div class="tab-content">
						<div class="tab-content-inside">
							<div class='wordmark'>
								<div>
									<a target="_blank" href="<?php _e( 'https://www.quantcast.com/?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=logo&utm_content=choice', 'qc-choice' ); ?>" target="_blank">
										<img src="<?php echo plugins_url( 'quantcast-choice/admin/img/quantcast-choice-wordmark.png' ); ?>" />
									</a>
								</div>
							</div>

							<div class="instructions-wrapper">
								<div class="instructions-box instructions-box-left">
									<div class="instructions-box-inside">
										<h4><?php _e('Choice TCF v2.0 Setup Intructions (Required)', 'qc-choice'); ?></h4>
										<ol>
											<li>
												<?php printf(
													__(
														'Create your <strong>Free</strong> <a %1$s href="%2$s">quantcast.com account</a>.',
														'qc-choice'
													),
													'target="_blank"',
													esc_url('https://www.quantcast.com/signin/register?qcRefer=/protect/sites/newUser&utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=tcfv2&utm_term=overview-create-account&utm_content=choice')
												);
												?>
											</li>
											<li>
												<?php printf(
													__(
														'Add your website url, under "<a %1$s href="%2$s">Privacy</a>" in your the quantcast.com account dashboard.".',
														'qc-choice'
													),
													'target="_blank"',
													esc_url('https://www.quantcast.com/protect/sites?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=tcfv2&utm_term=add-site&utm_content=chocie')
												);
												?>
											</li>
											<li>
												<?php _e('Configure your CMP settings (name, logo, consent settings).', 'qc-choice'); ?>
											</li>
											<li>
												<?php printf(
													__(
														'Add your <strong>Universal Tag ID (UTID)</strong> & <strong>Enable Choice TCF v2.0</strong> on the <a %1$s href="%2$s">TCF v2.0 Settings</a> tab.',
														'qc-choice'
													),
													'target="_self"',
													esc_url('/wp-admin/admin.php?page=qc-choice-options&tab=tcfv2_screen')
												);
												?>
											</li>
										</ol>

										<h4><?php _e( 'Advanced setup (optional)', 'qc-choice' ); ?></h4>
										<ol>
											<li><?php _e( 'Add <strong>Non-IAB vendors</strong>.', 'qc-choice' ); ?></li>
											<li><?php _e( 'Enable <strong>CCPA support</strong>.', 'qc-choice' ); ?></li>
											<li><?php _e( '<strong>Customise the CMP display</strong> with a theme.', 'qc-choice' ); ?></li>
											<li><?php _e( '<strong>Enable Data Layer consent data push</strong>, automatically passing consent signals to Google Tag Manager for consumption by customer triggers and trigger groups.', 'qc-choice' ); ?></li>
										</ol>

										<br>
										<h4><?php _e( 'Additional Setup & Configuration Help', 'qc-choice' ); ?></h4>
										<div>

											<p>
												<?php printf(
													__(
														'Check out the <a %1$s href="%2$s">help center guides</a> for the list of configuration options and setup guides for Quantcast Choice TCF v2.0.',
														'qc-choice'
													),
													'target="_blank"',
													esc_url('https://help.quantcast.com/hc/en-us/sections/360008320914-Configuration-Options-for-TCF-v2?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=tcfv2&utm_term=cmp-configuration-help&utm_content=choice')
												);
												?>
											</p>
										</div>
									</div>
								</div>
							</div>

							<br>
							<hr>
							<br><br>
							<h3><?php _e('Additional Information'); ?></h3>
							<div class="instructions-wrapper">
								<div class="instructions-box instructions-box-left">
									<div class="instructions-box-inside">
										<div class="more-info-box">
											<div>
												<ul>
													<li>
														<strong>Purpose Notification</strong>
														<p>Provides notices of the purpose and vendors for processing personal data.</p>
													</li>

													<li>
														<strong>Privacy Preferences</strong>
														<p>Provides users options to allow or disallows processing on a granular basis under both the consent and legitimate interest legal basis.</p>
													</li>

													<li>
														<strong>Signal Storing & Sharing</strong>
														<p>Signals vendor and purpose permissions for processing personal data.</p>
													</li>

													<li>
														<strong>Audit Log</strong>
														<p>Site owners can access the audit log and use it to prove that consent was given.</p>
													</li>
												</ul>
											</div>
											<div class="text-center">
												<a target="_blank" class="button" href="https://www.quantcast.com/gdpr/consent-management-solution/?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=tcfv2&utm_term=learn-more&utm_content=choice">Learn more</a>
											</div>
										</div>
									</div>
								</div>
								<div class="instructions-box instructions-box-right">
									<div class="instructions-box-inside">
										<h4>More Resources</h4>
										<nav>
											<ul>
												<li>
													<a target="_blank" href="<?php _e( esc_url('https://help.quantcast.com/hc/en-us/categories/360002940873-Quantcast-Choice?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=help&utm_content=choice'), 'qc-choice' ); ?>" target="_blank"><?php _e( 'Quantcast Choice Help Guides', 'qc-choice' ); ?></a>
												</li>
												<li>
													<a target="_blank" href="<?php _e( esc_url('https://www.quantcast.com/gdpr/consent-management-solution/?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=learn-more&utm_content=choice'), 'qc-choice' ); ?>" target="_blank"><?php _e( 'More about Quantcast Choice', 'qc-choice' ); ?></a>
												</li>
												<li>
													<a target="_blank" href="<?php _e( esc_url('https://www.quantcast.com/terms/quantcast-choice-terms-of-service/?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=terms&utm_content=choice'), 'qc-choice' ); ?>" target="_blank"><?php _e( 'Quantcast Choice Terms of Service', 'qc-choice' ); ?></a>
												</li>
											</ul>
										</nav>
									</div>
								</div>
							</div>
						</div>
					</div>

				<?php } elseif( $active_tab === 'tcfv2_screen' ) { ?>

					<?php settings_fields( 'choice-cmp-config' ); ?>
					<?php do_settings_sections( 'choice-cmp-config' ); ?>

					<!-- Start - UI Configuration Section -->
					<div class="tab-content">
						<div class="tab-content-inside">
							<table class="form-table" role="presentation">
								<tbody>
									<tr valign="top">
										<th scope="row">
											<?php _e( 'Universal Tag ID (UTID)', 'qc-choice' ); ?>
										</th>
										<td>
											<?php if( empty($this->qc_choice_cmp_utid ) ) { ?>
												<div class="desc"><em style="color:red">A UTID value must be set in order to enable Choice TCF v2.0 for website visitors.</em></div>
											<?php } ?>
											<input name="qc_choice_cmp_utid" type="text" value="<?php echo $this->qc_choice_cmp_utid; ?>">
											<div class="desc">
												<div>
													<?php printf(
														__(
															'If you already have a quantcast.com account you can find your UTID by following <a %1$s href="%2$s">this help center guide</a>.',
															'qc-choice'
														),
														'target="_blank"',
														esc_url('https://help.quantcast.com/hc/en-us/articles/360051794614-Quantcast-Choice-TCFv2-GTM-Implementation-Guide-Finding-your-UTID?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=tcfv2&utm_term=cmp-config-utid&utm_content=choice')
													);
													?>
												</div>
												<div>
													<?php printf(
														__(
															'If you do <strong>NOT</strong> have a quantcast.com account you can create one for <strong>FREE</strong> at quantcast.com. <a %1$s href="%2$s">Create quantcast.com account</a>. Detailed instructions and a video can be found on the <a %3$s href="%4$s">overview tab</a>.',
															'qc-choice'
														),
														'target="_blank"',
														esc_url('https://www.quantcast.com/signin/register?qcRefer=/protect/sites/newUser&utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=tcfv2&utm_term=settings-create-account&utm_content=choice'),
														'target="_self"',
														esc_url('/wp-admin/admin.php?page=qc-choice-options&tab=overview_screen')
													);
													?>
												</div>
											</div>

										</td>
									</tr>

									<tr valign="top">
										<th scope="row">
											<?php _e( 'Push IAB & Non-IAB consent data to the Data Layer', 'qc-choice' ); ?>
										</th>
										<td>
											<select name="qc_choice_cmp_datalayer_push">
												<option value="" <?php selected( $this->qc_choice_cmp_datalayer_push, '' ); ?>><?php _e( 'No', 'qc-choice' ); ?></option>
												<option value="true" <?php selected( $this->qc_choice_cmp_datalayer_push, 'true' ); ?>><?php _e( 'Yes', 'qc-choice' ); ?></option>
											</select>
											<div class="desc">
												<?php printf(
													__(
														'Learn more about using consent data passed to Google Tag Manager using Trigger events and Trigger groups to allow/block Non-IAB vendors. <a %1$s href="%2$s">View the guide</a>',
														'qc-choice'
													),
													'target="_blank"',
													esc_url('https://help.quantcast.com/hc/en-us/articles/360051794134-Quantcast-Choice-TCFv2-Google-Tag-Manager-GTM-Implementation-Guide#h_01EEVBK4AENH0F5YPKHXH70PXX?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=tcfv2&utm_term=datalayer-triggers&utm_content=choice')
												);
												?>
											</div>
										</td>
									</tr>

									<tr valign="top">
										<th scope="row">
											<?php _e( 'Automatically add footer CCPA message', 'qc-choice' ); ?>
											<div class="desc"><?php _e('You <strong>must also enable CCPA via the quantcast.com dashboard</strong> where you can also configure the popup style, text and links.', 'qc-choice'); ?></div>
										</th>
										<td>
											<select name="qc_choice_cmp_ccpa_wp_footer">
												<option value="" <?php selected( $this->qc_choice_cmp_ccpa_wp_footer, '' ); ?>><?php _e( 'Disable', 'qc-choice' ); ?></option>
												<option value="auto" <?php selected( $this->qc_choice_cmp_ccpa_wp_footer, 'auto' ); ?>><?php _e( 'Automatically add CCPA to footer', 'qc-choice' ); ?></option>
												<option value="manual" <?php selected( $this->qc_choice_cmp_ccpa_wp_footer, 'manual' ); ?>><?php _e( 'Manually place footer div', 'qc-choice' ); ?></option>
											</select>
											<div>
												<p class="desc">
													<?php _e("You can select 'Manually place footer div' and add '<strong>&#x3C;div id=&#x22;choice-footer-msg&#x22;&#x3E;&#x3C;/div&#x3E;</strong>' your website template footer for manual CCPA message placement (The CCPA message & Do Not Sell My Data button will be added to the empty div automatically once Choice loads).", 'qc-choice'); ?>
												</p>
											</div>
										</td>
									</tr>

									<?php if( ! empty($this->qc_choice_cmp_utid ) ) { ?>
										<tr valign="top">
										<th scope="row">
											<?php _e( 'CMP Configuration & Settings', 'qc-choice' ); ?>
										</th>
										<td>
											<div>
												<h4>
													<?php printf(
														__(
															'Configuration options avaliable at <a %1$s href="%2$s">quantcast.com/protect/sites</a>.',
															'qc-choice'
														),
														'target="_blank"',
														esc_url('https://www.quantcast.com/protect/sites?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=tcfv2&utm_term=cmp-configuration&utm_content=choice')
													);
													?>
												</h4>
											</div>
											<ol>
												<li><?php _e('<strong>Geo location</strong> targeting.', 'qc-choice'); ?></li>
												<li><?php _e('Add <strong>Non-IAB vendors</strong>.', 'qc-choice'); ?></li>
												<li><?php _e('Enable <strong>CCPA support</strong>.', 'qc-choice'); ?></li>
												<li><?php _e('<strong>Customise the CMP display</strong> with a theme.', 'qc-choice'); ?></li>
												<li><?php _e('Customise the <strong>CMP display position</strong>, text, links & other content and more.', 'qc-choice'); ?></li>
												<li><?php _e('Customise <strong>consent scope</strong>.', 'qc-choice'); ?></li>
												<li><?php _e('Customise <strong>consent configuration</strong>.', 'qc-choice'); ?></li>
												<li><?php _e('<strong>And more....</strong>', 'qc-choice'); ?></li>
											</ol>
											<div>
												<p><?php _e('Log into your quantcast.com account to customise your CMP.', 'qc-choice'); ?></p><br>
												<a class="btn button" target="_blank" href="<?php echo esc_url('https://www.quantcast.com/protect/sites?utm_source=wordpress&utm_medium=plugin-admin&utm_campaign=tcfv2&utm_term=cmp-configuration&utm_content=choice'); ?>">
													<?php _e('Go to your quantcast.com CMP dashboard', 'qc-choice'); ?>
												</a>
											</div>
										</td>
									</tr>
								<?php } ?>

								</tbody>
							</table>
						</div>
					</div>
					<!-- END - UI Configuration Section -->

				<?php if ( $active_tab !== 'overview_screen' ) { ?>

					<table class="form-table options-form-table">
						<tr>
							<td><?php submit_button(); ?></td>
						</tr>
					</table>

				<?php } ?>
			</form>
		</div>
		<?php
		}
	}

	/**
	 * QC Choice admin pages
	 *
	 * @since    1.0.0
	 */
	public function add_qc_choice_admin_pages() {

		// Adding the top levelqc_choice page
		$qc_choice_admin_page = add_menu_page(
			'QC Choice',
			'QC Choice',
			'administrator',
			'qc-choice-options',
			array( $this, 'qc_choice_options' ),
			plugins_url( 'quantcast-choice/admin/img/quantcast-icon.png' )
		);

	}

	/**
	 * Register QC Choice admin option fields
	 *
	 * @since    1.0.0
	 */
	public function qc_choice_options_page_init() {

		// TCF v2.0 settings
		register_setting(
			'choice-cmp-config', // Option group
			'qc_choice_cmp_utid', // Option name
			array(
				'type' => 'string',
				'sanitize_callback' => array( $this, 'sanitize_utid' )
			)
		);
		register_setting(
			'choice-cmp-config', // Option group
			'qc_choice_cmp_datalayer_push', // Option name
			array(
				'type' => 'string',
				'sanitize_callback' => array( $this, 'sanitize_text' )
			)
		);
		register_setting(
			'choice-cmp-config', // Option group
			'qc_choice_cmp_ccpa_wp_footer', // Option name
			array(
				'type' => 'string',
				'sanitize_callback' => array( $this, 'sanitize_text' )
			)
		);
	}

	/**
	 * Sanitize the qc_choice text fields.
	 *
	 * @since   1.0.0
	 *
	 * @param   string $input  $input contains submitted textarea input
	 */
	public function sanitize_text( $input ) {

		$input = sanitize_text_field( $input );
		return $input;

	}

	/**
	 * Sanitize the qc_choice utid field & remove leading 'p-'
	 *
	 * @since   2.0.5
	 *
	 * @param   string $input  $input contains submitted textarea input
	 */
	public function sanitize_utid( $input ) {

		$input = sanitize_text_field( $input );

		// Remove 'p-' for the utid input.
		if (substr($input, 0, 2) === 'p-') {
			$input = substr($input, 2);
		}

		return $input;

	}
}
