<!doctype html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<?php $template_url = plugin_dir_url( __FILE__ ); ?>
	<?php $template_dir = plugin_dir_path( __FILE__ ); ?>
	<title><?php $wp_title = single_post_title( '', false ); echo $wp_title; ?></title>
	<?php resumecv_head(); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo RESUMECV_PLUGIN_URL; ?>assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo RESUMECV_PLUGIN_URL; ?>assets/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">
	<link rel="stylesheet" type="text/css" href="<?php echo esc_url($template_url); ?>css/style.css">
</head>
<body> 
<div class="--l-rcv-box">
	<div class="--l-rcv-top-space"></div>
	
	
	<?php if ( has_nav_menu( 'resume-cv-primary' ) ) { ?>
		<div class="main-navigation-container">
			<div class="container"><div class="row">
				<div class="col-md-12">
						
							<nav id="site-navigation" class="main-navigation">
								<div class="menu-toggle-container text-center">
									<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'headline' ); ?></button>
								</div>
								<?php
								wp_nav_menu( array(
									'theme_location' => 'resume-cv-primary',
									'menu_id'        => 'primary-menu',
								) );
								?>
								
							</nav><!-- #site-navigation -->
						
					</header><!-- #masthead -->
			
				</div>
			</div></div><!-- container -->
		</div><!-- main-navigation-container -->
	<?php } ?>
	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php require_once ($template_dir . '/parts/profile-title.php'); ?>
			</div>
			<div class="col-md-3">
				<?php require_once ($template_dir . '/parts/profile.php'); ?>
				
				
			</div>
			<div class="col-md-9">
				<?php require_once ($template_dir . '/parts/profile-right.php'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?php require_once ($template_dir . '/parts/qualification.php'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?php require_once ($template_dir . '/parts/service.php'); ?>
			</div>
			<div class="col-md-12">
				<?php require_once ($template_dir . '/parts/experience.php'); ?>
			</div>
			<div class="col-md-12">
				<?php require_once ($template_dir . '/parts/education.php'); ?>
			</div>
			<div class="col-md-12">
				<?php require_once ($template_dir . '/parts/skillbar.php'); ?>
			</div>
			<?php
				$reference_options = get_option( 'resumecv_reference_options');
				$award_options = get_option( 'resumecv_award_options');
				$contact_options = get_option( 'resumecv_contact_options');
				
				$col = 'col-md-12';
				$col_count = 0;
				if ( resumecv_data($reference_options,'show') == 'enable') 
					$col_count++;
				if ( resumecv_data($award_options,'show') == 'enable')
					$col_count++;
				if ( resumecv_data($contact_options,'show') == 'enable')
					$col_count++;
				
				switch ($col_count) {
					case 1 :
						$col = 'col-md-12';
						break;
					case 2 :
						$col = 'col-md-6';
						break;
					case 3 :
						$col = 'col-md-4';
						break;
						
				}					
				
				if ( resumecv_data($reference_options,'show') == 'enable') {
			?>
					<div class="<?php echo esc_attr($col); ?>">
						<?php require_once ($template_dir . '/parts/reference.php'); ?>
					</div>
			<?php
				}
				
				if ( resumecv_data($award_options,'show') == 'enable') {
			?>
					<div class="<?php echo esc_attr($col); ?>">
						<?php require_once ($template_dir . '/parts/award.php'); ?>
					</div>
			<?php
				}
				
				if ( resumecv_data($contact_options,'show') == 'enable') {
			?>
					<div class="<?php echo esc_attr($col); ?>">
						<?php require_once ($template_dir . '/parts/contact.php'); ?>
					</div>
			<?php
				}
			?>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?php require_once ($template_dir . '/parts/hobby.php'); ?>
			</div>
		</div>
	</div>
	
	
	<?php resumecv_footer_widgets(); ?>
	
</div>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<p class="rcv-copyright"><?php printf(esc_html__('Create in %1$s | %2$s by wpamanuke', 'cvresume'), date("Y"), '<a href="' . esc_url('http://wpamanuke.com/resume-cv/') . '" rel="nofollow">Resume CV WordPress Plugin</a>'); ?></p>
			</div>
		</div>
	</div>
</div>

	<script type="text/javascript" src="<?php echo esc_url($template_url); ?>js/script.js?p=<?php echo rand(1,100000); ?>"></script> 
	<?php resumecv_footer(); ?>
</body>
</html>