<?php
$package = xpro_elementor_get_package_type();
?>

<div class="xpro-dashboard-tab-content xpro-dashboard-tab-dashboard active" id="xpro-dashboard">
	<div class="xpro-dashboard-tab-content-inner">

		<!-- Intro Banner-->
		<div class="xpro-dashboard-intro">
			<div class="xpro-dashboard-intro-content">
				<h4 class="xpro-dashboard-label">
					<?php echo esc_html__( 'Design What You Love', 'xpro-elementor-addons' ); ?>
				</h4>
				<h2 class="xpro-dashboard-title">
					<?php echo esc_html__( 'Welcome to Xpro Addons For Elementor', 'xpro-elementor-addons' ); ?>
				</h2>
				<?php if ( 'free' === $package ) : ?>
					<a href="https://elementor.wpxpro.com/buy" target="_blank" class="xpro-dashboard-btn">
						<?php echo esc_html__( 'Upgrade to Pro', 'xpro-elementor-addons' ); ?>
					</a>
				<?php endif; ?>
				<?php if ( 'invalid' === $package ) : ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=xpro-elementor-addons-license' ) ); ?>" class="xpro-dashboard-btn"><?php echo esc_html__( 'Activate Pro', 'xpro-elementor-addons' ); ?></a>
				<?php endif; ?>
				<?php if ( 'valid' === $package ) : ?>
					<a href="https://wordpress.org/plugins/xpro-elementor-addons/#reviews" target="_blank" class="xpro-dashboard-btn">
						<?php echo esc_html__( 'Leave Us a Rating', 'xpro-elementor-addons' ); ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="xpro-dashboard-intro-image">
				<img src="<?php echo esc_url( XPRO_ELEMENTOR_ADDONS_ASSETS . '/admin/images/intro.gif' ); ?>" alt="xpro-intro">
			</div>
		</div>

		<img class="xpro-dashboard-intro-shape-1" src="<?php echo esc_url( XPRO_ELEMENTOR_ADDONS_ASSETS . '/admin/images/intro-shape-02.png' ); ?>" alt="xpro-shape">
		<img class="xpro-dashboard-intro-shape-2" src="<?php echo esc_url( XPRO_ELEMENTOR_ADDONS_ASSETS . '/admin/images/intro-shape-03.png' ); ?>" alt="xpro-shape">

		<!--About-->
		<div class="xpro-dashboard-about">
			<h2 class="xpro-dashboard-about-heading">
				<?php echo esc_html__( 'Power Up Your Elementor Experience', 'xpro-elementor-addons' ); ?>
			</h2>
			<ul class="xpro-dashboard-about-list">
				<li>
					<span class="xpro-dashboard-about-list-item-count">100+</span>
					<span class="xpro-dashboard-about-list-item-text">
						<?php echo esc_html__( 'Pre-built Templates', 'xpro-elementor-addons' ); ?>
					</span>
				</li>
				<li>
					<span class="xpro-dashboard-about-list-item-count">50+</span>
					<span class="xpro-dashboard-about-list-item-text">
						<?php echo esc_html__( 'Modern Slides', 'xpro-elementor-addons' ); ?>
					</span>
				</li>
				<li>
					<span class="xpro-dashboard-about-list-item-count">90+</span>
					<span class="xpro-dashboard-about-list-item-text">
						<?php echo esc_html__( 'Versatile Sections', 'xpro-elementor-addons' ); ?>
					</span>
				</li>
				<li>
					<span class="xpro-dashboard-about-list-item-count">20+</span>
					<span class="xpro-dashboard-about-list-item-text">
						<?php echo esc_html__( 'Stater Sites', 'xpro-elementor-addons' ); ?>
					</span>
				</li>
			</ul>
			<div class="xpro-dashboard-about-btn-wrapper">
				<a target="_blank" href="<?php echo esc_url( 'https://elementor.wpxpro.com/' ); ?>" class="xpro-dashboard-about-btn">
					<?php echo esc_html__( 'Explore our Website', 'xpro-elementor-addons' ); ?>
				</a>
			</div>
		</div>

	</div>
</div>
