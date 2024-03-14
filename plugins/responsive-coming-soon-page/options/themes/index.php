<?php
@session_start();
$wl_rcsm_options = get_option('weblizar_rcsm_options'); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="<?php echo esc_attr($wl_rcsm_options['page_meta_keywords']); ?>">
	<meta name="description" content="<?php echo esc_attr($wl_rcsm_options['page_meta_discription']); ?>">
	<?php if ($wl_rcsm_options['search_robots'] == 'on') { ?>
		<meta name="robots" content="<?php echo esc_attr($wl_rcsm_options['rcsm_robots_meta']); ?>">
	<?php } ?>
	<?php if (isset($wl_rcsm_options['upload_favicon'])) {
		$favicon_img = $wl_rcsm_options['upload_favicon'];
	} else $favicon_img = RCSM_PLUGIN_URL . 'options/images/favicon.png'; ?>
	<link rel="icon" href="<?php echo esc_url($favicon_img); ?>">
	<meta name="author" content="">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo RCSM_PLUGIN_URL . 'options/themes/js/scrollReveal.min.js'; ?>"></script>
	<script src="<?php echo RCSM_PLUGIN_URL . 'options/themes/js/script-frontend.js' ?>"></script>
	<link rel="stylesheet" href="<?php echo RCSM_PLUGIN_URL . 'options/css/all.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo RCSM_PLUGIN_URL . 'options/themes/css/animate.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo RCSM_PLUGIN_URL . 'options/css/bootstrap.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo RCSM_PLUGIN_URL . 'options/themes/css/custom.css'; ?>">
	<link rel="stylesheet" href="<?php echo RCSM_PLUGIN_URL . 'options/themes/css/media-screen.css'; ?>">
	<script src="<?php echo RCSM_PLUGIN_URL . 'options/themes/js/countdown.js'; ?>"></script>
	<script src="<?php echo RCSM_PLUGIN_URL . 'options/js/bootstrap.min.js'; ?>"></script>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Rock+Salt|Neucha|Sans+Serif|Indie+Flower|Shadows+Into+Light|Dancing+Script|Kaushan+Script|Tangerine|Pinyon+Script|Great+Vibes|Bad+Script|Calligraffitti|Homemade+Apple|Allura|Megrim|Nothing+You+Could+Do|Fredericka+the+Great|Rochester|Arizonia|Astloch|Bilbo|Cedarville+Cursive|Clicker+Script|Dawning+of+a+New+Day|Ewert|Felipa|Give+You+Glory|Italianno|Jim+Nightshade|Kristi|La+Belle+Aurore|Meddon|Montez|Mr+Bedfort|Over+the+Rainbow|Princess+Sofia|Reenie+Beanie|Ruthie|Sacramento|Seaweed+Script|Stalemate|Trade+Winds|UnifrakturMaguntia|Waiting+for+the+Sunrise|Yesteryear|Zeyada|Warnes|Abril+Fatface|Advent+Pro|Aldrich|Alex+Brush|Amatic+SC|Antic+Slab|Candal">
	<?php 
	 ?>
	<?php if ($wl_rcsm_options['google_analytics'] != '') {
		echo '<script>' . $wl_rcsm_options['google_analytics'] . '</script>';
	} ?>
	<?php if ($wl_rcsm_options['custom_css'] != '') {
		echo '<style>' . $wl_rcsm_options['custom_css'] . '</style>';
	} ?>
	<?php require_once('css/custom-css.php'); ?>
</head>

<body>
	<div id="wrapper">
		<?php
		$template_bg_select = $wl_rcsm_options['template_bg_select'];
		if ($template_bg_select == 'Background_Color') { ?>
			<div class="row background_color">
				<?php page_data(); ?>
			</div>
		<?php
		}
		if ($template_bg_select == 'Custom_Background') { ?>
			<div class="row custom_background-image">
				<?php page_data(); ?>
			</div>
		<?php } ?>
		<?php function page_data()
		{
			$wl_rcsm_options = get_option('weblizar_rcsm_options'); ?>
			<header>
				<div class="social-icons">
					<ul class="animated fadeInDownBig">
						<?php
						for ($i = 1; $i <= 5; $i++) {
							if ($wl_rcsm_options['social_icon_' . $i] != '') { ?>
								<li><a target="<?php if ($wl_rcsm_options['link_tab_' . $i] == 'on') echo '_blank'; ?>" href="<?php echo esc_url($wl_rcsm_options['social_link_' . $i]); ?>"><i class="<?php echo esc_attr($wl_rcsm_options['social_icon_' . $i] . 'icon'); ?>"></i></a></li><?php } } ?>
					</ul>
				</div>
			</header>
			<div class="container">
				<div class="carousel-caption form_align">
					<h4 class="logo_class ff">
						<?php $site_logo_value = $wl_rcsm_options['site_logo'];
							if ($site_logo_value == 'logo_text') {
								echo esc_html($wl_rcsm_options['logo_text_value']);
							} elseif ($wl_rcsm_options['upload_image_logo'] == null) {
								echo esc_attr($wl_rcsm_options['logo_text_value']);
							} else { ?>
							<img width="<?php echo esc_attr($wl_rcsm_options['logo_width']); ?>" height="<?php echo esc_attr($wl_rcsm_options['logo_height']); ?>" src="<?php echo esc_url($wl_rcsm_options['upload_image_logo']); ?>">
						<?php } ?>
					</h4>
					<?php $layout_status = $wl_rcsm_options['layout_status'];
					if ($layout_status == 'coming_soon_switch') { ?>
						<h1 class="custom_bg_title_color" data-sr="enter top over 1s and move 110px wait 0.3s"><?php echo esc_html($wl_rcsm_options['coming-soon_title']); ?></h1>
						<h4 class="custom_bg_title_color" data-sr="enter top over 1s and move 110px wait 0.3s"><?php echo esc_html($wl_rcsm_options['coming-soon_sub_title']); ?></h4>
						<div class="box1" data-sr="enter left over 1s and move 110px wait 0.3s">
							<div class="box2"></div>
						</div>
						<h3 class="custom_bg_desc_color" data-sr="enter bottom over 1s and move 110px wait 0.3s"><?php echo esc_html($wl_rcsm_options['coming-soon_message']); ?></h3>
						<?php
					}
					$button_onoff = $wl_rcsm_options['button_onoff'];
					if ($button_onoff == 'on') {
						if ($wl_rcsm_options['button_text']) { ?>
							<a class="button_link btn" href="<?php echo esc_url($wl_rcsm_options['button_text_link']); ?>" data-sr="enter bottom over 1s and move 110px wait 0.3s">
								<?php echo esc_html($wl_rcsm_options['button_text']); ?>
							</a>
							<?php
							}
						}
					?>
				</div>
			</div>
			<!-- Maintains Page End -->
		<?php
		}

		$data = $wl_rcsm_options['page_layout_swap'];
		if ($data != '') {
			foreach ($data as $key => $value) {
				switch ($value) {
					case 'Count Down Timer':
						include 'counterclock-progressbar.php';
						break;

					case 'Subscriber Form':
						include 'subscriber-from-settings.php';
						include 'subscriber-form.php';
						break;
				}
			}
		}
		?>
		<div class="container-fluid copyright">
			<div class="social-icons">
				<ul class="social animated fadeInDownBig">
					<?php for ($i = 1; $i <= 5; $i++) {
						if ($wl_rcsm_options['social_icon_' . $i] != '') { ?>
							<li><a target="<?php if ($wl_rcsm_options['link_tab_' . $i] == 'on') echo '_blank'; ?>" href="<?php echo esc_url($wl_rcsm_options['social_link_' . $i]); ?>"><i class="<?php echo esc_html($wl_rcsm_options['social_icon_' . $i]); ?>  icon"></i></a></li>
					<?php 	}
					} ?>
				</ul>
			</div>
			<div class="container">
				<div class="row" data-sr="enter top over 1s and move 110px wait 0.3s">
					<?php echo esc_html($wl_rcsm_options['footer_copyright_text']); ?> <a href="<?php echo esc_url($wl_rcsm_options['footer_link']); ?>"> <?php echo esc_html($wl_rcsm_options['footer_link_text']); ?></a>
				</div>
			</div>
		</div>
		<?php
		if ($wl_rcsm_options['link_admin'] == 'on') {
			if (is_user_logged_in()) {
				//get logined in user role
				global $current_user;
				wp_get_current_user();
				$LoggedInUserID = $current_user->ID;
				$UserData = get_userdata($LoggedInUserID);
				//if user role not 'administrator' redirect him to message page
				if ($UserData->roles[0] == 'administrator') {
					$admin_link_text = $wl_rcsm_options['admin_link_text'];
					if ($wl_rcsm_options['admin_link_text']) {
		?><a class="btn left_side_link" href="<?php echo get_admin_url()."admin.php?page=rcsm-weblizar"; ?>" data-sr="enter bottom over 1s and move 110px wait 0.3s"> <?php echo esc_html($admin_link_text); ?></a><?php
																																																					}
																																																				}
																																																			}
																																																		} ?>

		<script type="text/javascript">
			(function(jQuery) {
				'use strict';
				window.sr = new scrollReveal({
					reset: true,
					move: '50px',
					mobile: true
				});
			})();
		</script>
	</div>
</body>

</html>