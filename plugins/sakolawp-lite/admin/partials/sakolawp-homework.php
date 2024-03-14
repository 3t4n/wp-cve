<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://themesawesome.com/
 * @since      1.0.0
 *
 * @package    Sakolawp
 * @subpackage Sakolawp/admin/partials
 */


global $wpdb;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="homework-page skwp-content-inner">

	<nav class="skwp-tabs-menu">
		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			<div class="skwp-logo">
				<img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'img/swp-logo.png'); ?>" alt="<?php echo esc_attr('Sakola Logo'); ?>">
			</div>
			<a class="nav-item nav-link active" href="admin.php?page=sakolawp-homework"><?php esc_html_e('Homework', 'sakolawp'); ?></a>

		</div>
	</nav>

	<div class="skwp-tab-content tab-content free-ver-wrap" id="nav-tabContent">
		<!-- start of class table -->
		<div class="table-responsive sakola-ss-example">
			<img src="<?php echo plugin_dir_url(__DIR__); ?>img/homework-ss.jpg" alt="<?php echo esc_attr('Sakola Homework'); ?>">
		</div>
		<!-- end of class table -->

		<div class="free-ver-over">
			<div class="lite-context-wrap">
				<div class="lite-context-detail">
					<h2><?php echo esc_html('SakolaWP Pro Feature'); ?></h2>
					<p><?php echo esc_html__('Please buy our PRO Version to use this awesome features.', 'sakolawp'); ?></p>
					<a class="btn skwp-btn btn-sm btn-info" href="<?php echo esc_url('https://1.envato.market/D7AL2'); ?>">
						<?php echo esc_html__('Buy Now!', 'sakolawp'); ?>
					</a>
				</div>
			</div>
		</div>

	</div>
</div>