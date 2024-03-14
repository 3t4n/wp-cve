<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="msadmin-wrapper">
	<div id="msadmin-leftmenu">
		<?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
	</div>
	<div id="msadmin-data">
		<?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('admin_aboutus'); ?>
		<div id="msadmin-data-wrp">
			<div class="majestic-support-about-wrapper">
				<div class="mj-logo-text-overall-wrapper">
					<div class="img-logo-mj-overall-wrapper">
						<div class="mj-logo-beautiful-wrapper">
							<a href="https://www.majesticsupport.com" class="mjtc-admin-author-prdct-item"
								title="<?php echo esc_attr(__('Majestic Support','majestic-support'));?>">
								<img alt="<?php echo esc_html(__('Majestic Support','majestic-support')); ?>"
									src="<?php echo esc_url(MJTC_PLUGIN_URL);?>includes/images/aboutus_page/logo.png" />
							</a>
						</div>
					</div>
					<div class="about-logo-heading-discription-wrapper">
						<div class="about-text-heading-dis-overall-wrapper">
							<div class="majestic-support-about-heading-wrapper">
								<span>
									<?php echo esc_html(__('About Majestic Support','majestic-support'));?>
								</span>
							</div>
							<div class="about-logo-heading-discription">
								<span>
									<?php echo esc_html(__('Majestic support is customer service or assistance that is exceptional in nature, providing a high level of satisfaction to the customer by going above and beyond their expectations. It involves providing prompt and efficient response times, personalized attention, and a willingness to solve problems or address concerns in a timely and effective manner.','majestic-support'));?>
								</span>
							</div>
						</div>
					</div>
				</div>
				<!-- Our Products -->
				<div class="about-product-overall-wrapper">
					<div class="product-heading-wrapper">
						<div class="about-product-heading-wrapper">
							<span>
								<?php echo esc_html(__('Our Products','majestic-support'));?>
							</span>
						</div>
						<div class="product-discription">
							<span>
								<?php echo esc_html(__('Explore our other products and services that we offer, you might find something else that interests you or fulfills your needs even better.','majestic-support'));?>
							</span>
						</div>
						<div class="product-image-wrapper">
							<a target="_blank" href="https://wpjobportal.com/" class="mjtc-admin-author-prdct-item"
								title="<?php echo esc_attr(__('Wp Job Portal','majestic-support'));?>">
								<img alt="<?php echo esc_html(__('Wp Job Portal','majestic-support')); ?>"
									src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/aboutus_page/wp-job-portal-banner.png" />
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
