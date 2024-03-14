<?php
if ( ! defined( 'ABSPATH' ) ) {
	  exit;
}
/*
* Shop Ready Dashborad
* @since 1.0
* feature ,support and video
*/

?>


<div class="woo--ready--dashboard--content">

	<div class="woo-ready-dashboard-heading">
		<h2><?php echo esc_html__( 'Dashboard', 'shopready-elementor-addon' ); ?> </h2>
	</div>
	<div class="shop-ready-dashboard-content-box">
		<div class="quomodo-row  quomodo-justify-content-center ">
			<div class="quomodo-col-lg-12">
				<div class="shop-ready-dashboard-thumb">
					<img src="<?php echo esc_url( SHOP_READY_PUBLIC_ROOT_IMG . '/shopready-thumb.png' ); ?>" alt="">
				</div>
			</div>
		</div>
		<div class="quomodo-row quomodo-justify-content-center ">
			<div class="quomodo-col-lg-6">
				<div class="quomodo-deshboard-doc">
					<h3 class="quomodo-title">
						<?php echo esc_html__( 'Easy documentation', 'shopready-elementor-addon' ); ?></h3>
					<p><?php echo esc_html__( 'Easy to use element ready plugins with video tutorial and screenshot instructions', 'shopready-elementor-addon' ); ?>
					</p>
					<a
						href="https://quomodosoft.com/plugins-docs/"><?php echo esc_html__( 'Get Started', 'shopready-elementor-addon' ); ?></a>
					<div class="quomodo-thumb">
						<img src="<?php echo esc_url( SHOP_READY_PUBLIC_ROOT_IMG . '/qoc-thumb.svg' ); ?>" alt="">
					</div>
				</div>
			</div>
			<div class="quomodo-col-lg-6">
				<div class="quomodo-deshboard-doc quomodo-deshboard-feature">
					<h3 class="quomodo-title">
						<?php echo esc_html__( 'Do you need  any feature', 'shopready-elementor-addon' ); ?></h3>
					<p> <?php echo esc_html__( 'The Shop Ready is Exclusive Addon for Elementor. Shop Ready has 103+ widgets , 400+ ready elements, 10+ page and more features', 'shopready-elementor-addon' ); ?>
					</p>
					<a target="_blank"
						href="https://quomodosoft.com/feature-request/"><?php echo esc_html__( 'Feature Requested', 'shopready-elementor-addon' ); ?></a>
					<div class="quomodo-thumb">
						<img src="<?php echo esc_url( SHOP_READY_PUBLIC_ROOT_IMG . '/feature-thumb.svg' ); ?>" alt="">
					</div>
				</div>
			</div>
			<div class="quomodo-col-lg-12">
				<div class="quomodo-deshboard-doc quomodo-deshboard-support">
					<h3 class="quomodo-title">
						<?php echo esc_html__( 'Help and support', 'shopready-elementor-addon' ); ?></h3>
					<p><?php echo esc_html__( 'Facing any technical issue? Need consultation with an expert? Simply take our live chat support option.', 'shopready-elementor-addon' ); ?>
					</p>
					<a target="_blank"
						href="http://help.quomodosoft.com/support"><?php echo esc_html__( 'Get Support', 'shopready-elementor-addon' ); ?></a>
					<div class="quomodo-thumb">
						<img src="<?php echo esc_url( SHOP_READY_PUBLIC_ROOT_IMG . '/support-thumb.svg' ); ?>" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
