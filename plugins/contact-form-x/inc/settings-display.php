<?php // Contact Form X Display Settings

if (!defined('ABSPATH')) exit;

function contactformx_menu_pages() {
	
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function )
	add_options_page(__('Contact Form X', 'contact-form-x'), __('Contact Form X', 'contact-form-x'), 'manage_options', 'contactformx', 'contactformx_display_settings');
	
}

function contactformx_get_tabs() {
	
	$tabs = array(
		'tab1' => esc_html__('Email',      'contact-form-x'), 
		'tab2' => esc_html__('Form',       'contact-form-x'), 
		'tab3' => esc_html__('Customize',  'contact-form-x'), 
		'tab4' => esc_html__('Appearance', 'contact-form-x'), 
		'tab5' => esc_html__('Advanced',   'contact-form-x'), 
	);
	
	return $tabs;
	
}

function contactformx_display_settings() {
	
	$tab_active = 'tab1';
	
	if (isset($_GET['tab'])) {
		
		switch ($_GET['tab']) {
			
			case 'tab1' : $tab_active = 'tab1'; break;
			
			case 'tab2' : $tab_active = 'tab2'; break;
			
			case 'tab3' : $tab_active = 'tab3'; break;
			
			case 'tab4' : $tab_active = 'tab4'; break;
			
			case 'tab5' : $tab_active = 'tab5'; break;
			
		}
		
	}
	
	$tab_href = admin_url('options-general.php?page=contactformx');
	
	$tab_names = contactformx_get_tabs();
	
	?>
	
	<div class="wrap wrap-<?php echo $tab_active; ?>">
		<h1><?php esc_html_e('Contact Form', 'contact-form-x'); ?> <span class="cfx-icon">X</span></h1>
		<p><?php esc_html_e('Visit the Help tab above for usage information.', 'contact-form-x'); ?></p>
		<h2 class="nav-tab-wrapper">
			
			<?php 
				
				foreach ($tab_names as $key => $value) {
					
					$active = ($tab_active === $key) ? ' nav-tab-active' : '';
					
					echo '<a href="'. $tab_href .'&tab='. $key .'" class="nav-tab nav-'. $key . $active .'">'. $value .'</a>';
					
				}
				
			?>
			
		</h2>
		<form method="post" action="options.php">
			
			<?php
				
				if ($tab_active === 'tab1') {
					
					settings_fields('contactformx_email');
					do_settings_sections('contactformx_email');
				
				} elseif ($tab_active === 'tab2') {
					
					settings_fields('contactformx_form');
					do_settings_sections('contactformx_form');
					
				} elseif ($tab_active === 'tab3') {
					
					settings_fields('contactformx_customize');
					do_settings_sections('contactformx_customize');
					
				} elseif ($tab_active === 'tab4') {
					
					settings_fields('contactformx_appearance');
					do_settings_sections('contactformx_appearance');
					
				} elseif ($tab_active === 'tab5') {
					
					settings_fields('contactformx_advanced');
					do_settings_sections('contactformx_advanced');
					
				}
				
				submit_button();
				
				contactformx_wp_resources($tab_active);
				
			?>
			
		</form>
	</div>
	
<?php }

function contactformx_wp_resources($tab_active) {
	
	if ($tab_active !== 'tab1') :
		
		$array = array(
			
			0  => '<a target="_blank" rel="noopener noreferrer" href="https://digwp.com/" title="Take your WP skills to the next level"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/book-digging-into-wordpress.jpg" alt="Digging Into WordPress"></a>',
			
			1  => '<a target="_blank" rel="noopener noreferrer" href="https://htaccessbook.com/" title="Secure and optimize your website"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/book-htaccess-made-easy.jpg" alt=".htaccess made easy"></a>',
			
			2  => '<a target="_blank" rel="noopener noreferrer" href="https://wp-tao.com/" title="Learn the Way of WordPress"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/book-tao-of-wordpress.jpg" alt="The Tao of WordPress"></a>',
			
			3  => '<a target="_blank" rel="noopener noreferrer" href="https://books.perishablepress.com/downloads/wizards-collection-sql-recipes-wordpress/" title="Wizard&rsquo;s SQL Recipes for WordPress"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/book-wizards-sql-wordpress.jpg" alt="Wizard&rsquo;s SQL Recipes for WordPress"></a>',
			
			4  => '<a target="_blank" rel="noopener noreferrer" href="https://wp-tao.com/wordpress-themes-book/" title="Build and sell awesome themes"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/book-wp-themes-in-depth.jpg" alt="WordPress Themes In Depth"></a>',
			
			//
			
			5  => '<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/banhammer-pro/" title="Banhammer Pro: Drop the hammer."><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/plugin-banhammer-pro.jpg" alt="Banhammer Pro"></a>',
			
			6  => '<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/bbq-pro/" title="Fastest WordPress Firewall"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/plugin-bbq-pro.jpg" alt="BBQ Pro Firewall"></a>',	
						
			7  => '<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/blackhole-pro/" title="Trap bad bots in a virtual black hole"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/plugin-blackhole-pro.jpg" alt="Blackhole Pro"></a>',
			
			8  => '<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/ga-google-analytics-pro/" title="Connect Google Analytics to WordPress"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/plugin-ga-pro.jpg" alt="GA Google Analytics Pro"></a>',
			
			9  => '<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/simple-ajax-chat-pro/" title="Unlimited chats for WordPress"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/plugin-sac-pro.jpg" alt="Simple Ajax Chat Pro"></a>',
					
			10  => '<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/usp-pro/" title="Unlimited front-end forms"><img width="125" height="125" src="'. CONTACTFORMX_URL .'img/resources/plugin-usp-pro.jpg" alt="USP Pro"></a>',
			
		);
		
		$items = array_rand($array, 4);
		
		$item1 = isset($array[$items[0]]) ? $array[$items[0]] : null;
		$item2 = isset($array[$items[1]]) ? $array[$items[1]] : null;
		$item3 = isset($array[$items[2]]) ? $array[$items[2]] : null;
		$item4 = isset($array[$items[3]]) ? $array[$items[3]] : null;
		
		if ($item1 && $item3 && $item3 && $item4) :
		
		?>
			
			<hr class="wp-resources-sep">
			
			<div class="wp-resources">
				
				<h2><span class="dashicons dashicons-coffee"></span> <?php esc_html_e('WP Resources', 'contact-form-x'); ?></h2>
				
				<p><?php esc_html_e('Check out more WordPress resources from this developer:', 'contact-form-x'); ?></p>
				
				<div class="wp-resources-wrap"><?php echo $item1 . $item2 . $item3 . $item4; ?></div>
				
			</div>
			
		<?php
		
		endif;
		
	endif;
	
}