<?php // Display Settings

if (!defined('ABSPATH')) exit;

function banhammer_menu_pages() {
	
	$plugin_name = esc_html__('Banhammer', 'banhammer');
	
	// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page($plugin_name, $plugin_name, 'manage_options', 'banhammer', 'banhammer_display_settings', 'dashicons-banhammer'); // avoid duplicate menu item: menu function = submenu function
	
	// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	add_submenu_page('banhammer', esc_html__('Settings', 'banhammer'), esc_html__('Settings', 'banhammer'), 'manage_options', 'banhammer',        'banhammer_display_settings'); // avoid duplicate menu item: parent slug = menu slug
	add_submenu_page('banhammer', esc_html__('Armory',   'banhammer'), esc_html__('Armory',   'banhammer'), 'manage_options', 'banhammer-armory', 'banhammer_display_armory');
	add_submenu_page('banhammer', esc_html__('Tower',    'banhammer'), esc_html__('Tower',    'banhammer'), 'manage_options', 'banhammer-tower',  'banhammer_display_tower');
	
}

function banhammer_display_settings() { ?>
	
	<div class="wrap">
		
		<div class="banhammer-header">
			<div class="banhammer-intro">
				<p class="banhammer-logo">
					<?php echo BANHAMMER_NAME; ?> 
					<span class="banhammer-version"><?php echo BANHAMMER_VERSION; ?></span>
				</p>
				<p><strong><?php esc_html_e('Protect your site against enemy hordes!', 'banhammer'); ?></strong></p>
				<ul>
					<li><?php esc_html_e('Monitor traffic and ban any user or bot with a click.', 'banhammer'); ?></li>
					<li><?php esc_html_e('Increase site security by blocking unwanted visitors.', 'banhammer'); ?></li>
					<li><?php esc_html_e('Banhammer is lightweight, fast, and easy on resources.', 'banhammer'); ?></li>
					<li><?php esc_html_e('Complete documentation in the Help tab.', 'banhammer'); ?></li>
				</ul>
				<p><em><?php esc_html_e('Thanks for using Banhammer. May it serve you well.', 'banhammer'); ?></em></p>
				<p class="banhammer-pro-news">
					<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/banhammer-pro/" title="<?php esc_attr_e('Banhammer Pro @ Plugin Planet', 'banhammer'); ?>">
						<?php esc_html_e('Check out Banhammer Pro', 'banhammer'); ?> &raquo;
					</a>
				</p>
			</div>
		</div>
		
		<h1><span class="dashicons-banhammer"></span> <?php esc_html_e('Banhammer Settings', 'banhammer'); ?></h1>
		
		<?php settings_errors(); ?>
		
		<form method="post" action="options.php">
			
			<?php 
				settings_fields('banhammer_settings');
				do_settings_sections('banhammer_settings');
				submit_button(); 
			?>
			
		</form>
		
	</div>
	
<?php }
