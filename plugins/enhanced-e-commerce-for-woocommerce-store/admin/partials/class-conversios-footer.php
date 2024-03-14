<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since      4.0.2
 * Description: Conversios Onboarding page, It's call while active the plugin
 */
if (!class_exists('Conversios_Footer')) {
	class Conversios_Footer
	{
		protected $TVC_Admin_Helper="";
		public function __construct()
		{
			add_action('add_conversios_footer', array($this, 'before_end_footer'));
			add_action('add_conversios_footer', array($this, 'before_end_footer_add_script'));
			$this->TVC_Admin_Helper = new TVC_Admin_Helper();
		}
		public function before_end_footer()
		{
?>
			<div class="tvc_footer_links">
			</div>
			<?php
			$licenceInfoArr = array(
				"Plan Type:" => "Free",
				"Plan Price:" => "Not Available",
				"Active License Key:" => "Not Available",
				"Subscription ID:" => "Not Available",
				"Active License Key:" => "Not Available",
				"Last Bill Date:" => "Not Available",
				"Next Bill Date:" => "Not Available",
			);
			?>


			<div class="modal fade" id="convLicenceInfoMod" tabindex="-1" aria-labelledby="convLicenceInfoModLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg modal-dialog-centered" style="width: 700px;">
					<div class="modal-content">
						<div class="modal-header badge-dark-blue-bg text-white">
							<h5 class="modal-title text-white" id="convLicenceInfoModLabel">
								<?php esc_html_e("My Subscription", "enhanced-e-commerce-for-woocommerce-store"); ?>
							</h5>
							<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="container-fluid">
								<div class="row">
									<?php foreach ($licenceInfoArr as $key => $value) { ?>
										<div class="<?php echo $key == "Connected with:" ? "col-md-12" : "col-md-6"; ?> py-2 px-0">
											<span class="fw-bold">
												<?php 
			                                    printf(
			                                        esc_html__( '%s', 'enhanced-e-commerce-for-woocommerce-store' ),
			                                        esc_html( $key )
			                                    );
			                                    ?>
											</span>
											<span class="ps-2">
												<?php 
			                                    printf(
			                                        esc_html__( '%s', 'enhanced-e-commerce-for-woocommerce-store' ),
			                                        esc_html( $value )
			                                    );
			                                    ?>
											</span>
										</div>
									<?php  } ?>
								</div>
							</div>
						</div>
						<div class="modal-footer justify-content-center">
							<div class="fs-6">
								<span><?php esc_html_e("You are currently using our free plugin, no license needed! Happy Analyzing.", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
								<span><?php esc_html_e("To unlock more features of Google Products ", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
								<?php echo wp_kses_post( $this->TVC_Admin_Helper->get_conv_pro_link_adv("planpopup", "globalheader", "conv-link-blue", "anchor", "Upgrade to Pro Version") ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		}

		public function before_end_footer_add_script()
		{
			$TVC_Admin_Helper = new TVC_Admin_Helper();
			$subscriptionId =  sanitize_text_field($TVC_Admin_Helper->get_subscriptionId());
		?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					var screen_name = '<?php echo isset($_GET['page']) ? esc_js(sanitize_text_field($_GET['page'])) : ''; ?>';
					var error_msg = 'null';
					jQuery('.navinfotopnav ul li a').click(function() {
						var slug = $(this).find('span').text();
						var menu = $(this).attr('href');
						str_menu = slug.replace(/\s+/g, '_').toLowerCase();
						user_tracking_data('click', error_msg, screen_name, 'topmenu_' + str_menu);
					});
				});

				function user_tracking_data(event_name, error_msg, screen_name, event_label) {
					// alert();
					jQuery.ajax({
						type: "POST",
						dataType: "json",
						url: tvc_ajax_url,
						data: {
							action: "update_user_tracking_data",
							event_name: event_name,
							error_msg: error_msg,
							screen_name: screen_name,
							event_label: event_label,
							TVCNonce: "<?php echo esc_js(wp_create_nonce('update_user_tracking_data-nonce')); ?>"
						},
						success: function(response) {
							console.log('user tracking');
						}
					});
				}
			</script>
			<script>
				window.fwSettings = {
					'widget_id': 81000001743
				};
				! function() {
					if ("function" != typeof window.FreshworksWidget) {
						var n = function() {
							n.q.push(arguments)
						};
						n.q = [], window.FreshworksWidget = n
					}
				}()
			</script>
			<script type='text/javascript' src='https://ind-widget.freshworks.com/widgets/81000001743.js' async defer></script>
<?php
		}
	}
}
new Conversios_Footer();
