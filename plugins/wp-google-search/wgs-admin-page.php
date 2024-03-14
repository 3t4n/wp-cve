<?php

class WGS_Admin_Page {
	
	function __construct() {
		add_action( 'admin_menu', array($this, 'wgs_admin_menu') ) ;
		add_action( 'admin_init', array($this, 'wgs_admin_init') );		
	}		

	function wgs_admin_menu () {
		
		//add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
		add_options_page( __('WP Google Search','wp-google-search'),__('WP Google Search','wp-google-search')
			,'manage_options','wp-google-search', array($this, 'add_options_page_callback' ));
	}
	
	
	function wgs_admin_init()
	{
		
		$this->wgs_set_defaults();
	
		//register_setting( $option_group, $option_name, $sanitize_callback );        
		register_setting(
			'wgs_general_settings', // Option group / tab page
			'wgs_general_settings', // Option name
			array($this, 'sanitize') // Sanitize
		);
	
		add_settings_section(
			'wgs_general_section', // ID 
			__('General Settings','wp-google-search'), // Title //ML
			array($this,'print_section_info'), // Callback 
			'wgs_general_settings' // Page / tab page
		);
		
		//add_settings_field( $id, $title, $callback, $page, $section, $args );

		add_settings_field(
			'google_search_engine_id', // ID
			__('Google Search Engine ID','wp-google-search'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wgs_general_settings', // Page / tab page
			'wgs_general_section' // Section           
		);

		add_settings_field(
			'searchbox_before_results', // ID
			__('Display search box before search results','wp-google-search'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wgs_general_settings', // Page / tab page
			'wgs_general_section' // Section           
		);

		add_settings_field(
			'support_overlay_display', // ID
			__('Support Overlay Display','wp-google-search'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wgs_general_settings', // Page / tab page
			'wgs_general_section' // Section           
		);

		add_settings_field(
			'linktarget_blank', // ID
			__('Link Target Blank','wp-google-search'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wgs_general_settings', // Page / tab page
			'wgs_general_section' // Section           
		);
		
		add_settings_field(
			'use_default_correction_css', // ID
			__('Use default corrections CSS','wp-google-search'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wgs_general_settings', // Page / tab page
			'wgs_general_section' // Section           
		);

		add_settings_field(
			'use_default_correction_css2', // ID
			__('Use default corrections CSS version2','wp-google-search'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wgs_general_settings', // Page / tab page
			'wgs_general_section' // Section           
		);

		add_settings_field(
			'use_default_correction_css3', // ID
			__('Use default corrections CSS version3','wp-google-search'), // Title 
			array($this, 'posttype_callback'), // Callback
			'wgs_general_settings', // Page / tab page
			'wgs_general_section' // Section           
		);

        add_settings_field( //HIDDEN
            'search_gcse_page_id', // ID
            'search_gcse_page_id', // Title 
			array($this, 'posttype_callback'), // Callback
			'wgs_general_settings', // Page / tab page
			'wgs_general_section' // Section               
        );      

        add_settings_field( //HIDDEN
            'search_gcse_page_url', // ID
            'search_gcse_page_url', // Title 
			array($this, 'posttype_callback'), // Callback
			'wgs_general_settings', // Page / tab page
			'wgs_general_section' // Section          
        );   
	
	}

	function wgs_set_defaults() {

		$options = get_option( 'wgs_general_settings' ); 
					
		$options = wp_parse_args( $options, array(
			'google_search_engine_id' => '', 
			'searchbox_before_results' => '0',
			'linktarget_blank' => '0',
			'support_overlay_display' => '0',
			'use_default_correction_css' => '0', //this is an older css, thus it is not switched on by default
			'use_default_correction_css2' => '1',
			'use_default_correction_css3' => '0', //this is an optional correction, thus default is 0
		) );
		
		update_option( 'wgs_general_settings', $options );
		
	}
	
	function add_options_page_callback()
	{
		
		wp_enqueue_style( 'wgs-admin', plugins_url('wgs-admin.css', __FILE__) );
		
		?>
		<div class="wrap">
			<?php //screen_icon(); ?>
			<h2><?php _e('WP Google Search by WebshopLogic','wp-google-search') ?></h2>
			
			<div style="float:left; width: 70%">
			
				<form method="post" action="options.php"><!--form-->  
					
					<?php
		
					settings_fields( 'wgs_general_settings' );					
					$options = get_option( 'wgs_general_settings' ); //option_name
					
					?>
					<h3><?php _e('General Settings','wp-google-search') ?></h3>
					<?php 
					//echo __('Enter your settings below','wp-google-search') . ':' 
					?>
		
					<table class="form-table">
		
						<tr valign="top">
							<th scope="row"><?php echo __('Google Search Engine ID','wp-google-search') . ':' ?></th>
							<td>
								<?php
						        printf(
						            '<input type="text" id="google_search_engine_id" name="wgs_general_settings[google_search_engine_id]" value="%s" size="50" />',
						            esc_attr( $options['google_search_engine_id'])
						        );
								echo '<br /><span class="description">' . __('Register to Google Custom Search Engine and get your Google Search Engine ID here: ','wp-google-search') . '<a href="https://www.google.com/cse/" target="_blank">https://www.google.com/cse/</a>' . '</span>';
								echo '<br /><span class="description">' . __('You will get a Google Search Engine ID like this: 012345678901234567890:0ijk_a1bcde','wp-google-search') . '</span>';
								echo '<br /><span class="description">' . __('Enter this Google Search Engine ID here.','wp-google-search') . '</span>';
						        						        
							    ?>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php echo __('Display search box before search results','wp-google-search') . ':' ?></th>
							<td>
								<?php
								printf(
									'<input type="hidden" name="wgs_general_settings[searchbox_before_results]" value="0"/>
									<input type="checkbox" id="searchbox_before_results" name="wgs_general_settings[searchbox_before_results]"
									value="1"' . checked( 1, esc_attr( $options['searchbox_before_results']), false ) . ' />'
								);
								echo '<br /><span class="description">' . __('If this option is turned on, the search field will appear above the search results.','wp-google-search') . '</span>';
	
								?>    
							</td>
						</tr>							
			 

						<tr valign="top">
							<th scope="row"><?php echo __('Link Target Blank','wp-google-search') . ':' ?></th>
							<td>
								<?php
								printf(
									'<input type="hidden" name="wgs_general_settings[linktarget_blank]" value="0"/>
									<input type="checkbox" id="linktarget_blank" name="wgs_general_settings[linktarget_blank]"
									value="1"' . checked( 1, esc_attr( $options['linktarget_blank']), false ) . ' />'
								);
								echo '<br /><span class="description">' . __('Display content of the links of the result set on new browser tab.','wp-google-search') . '</span>';
									
								?>    
							</td>
						</tr>							
			 
						<tr valign="top">
							<th scope="row"><?php echo __('Support Overlay Display','wp-google-search') . ':' ?></th>
							<td>
								<?php
								printf(
									'<input type="hidden" name="wgs_general_settings[support_overlay_display]" value="0"/>
									<input type="checkbox" id="support_overlay_display" name="wgs_general_settings[support_overlay_display]"
									value="1"' . checked( 1, esc_attr( $options['support_overlay_display']), false ) . ' />'
								);
								echo '<br /><span class="description">' . __('If you set on Google CSE admin page that result set is displayed in Overlay mode, then also set this checkbox. </br>In this case search results will be displayed without loading a new search result page. </br>If you do not use overlay display mode in GCSE, then clear this checkbox, because result set can not be displayed correctly.','wp-google-search') . '</span>';
									
								?>    
							</td>
						</tr>							
			 
						<tr valign="top">
							<th scope="row"><?php echo __('Use default corrections CSS v1','wp-google-search') . ':' ?></th>
							<td>
								<?php
								printf(
									'<input type="hidden" name="wgs_general_settings[use_default_correction_css]" value="0"/>
									<input type="checkbox" id="use_default_correction_css" name="wgs_general_settings[use_default_correction_css]"
									value="1"' . checked( 1, esc_attr( $options['use_default_correction_css']), false ) . ' />'
								);
								echo '<br /><span class="description">' . __('DEPRECTED: If this option is turned on, some css will be applied to improve the appearance of search elements in case of most WordPress themes. This is an older css settings collection, please use the following CSS 2 setting instead of this.','wp-google-search') . '</span>';
									
								?>    
							</td>
						</tr>							

						<tr valign="top">
							<th scope="row"><?php echo __('Use default corrections CSS v2','wp-google-search') . ':' ?></th>
							<td>
								<?php
								printf(
									'<input type="hidden" name="wgs_general_settings[use_default_correction_css2]" value="0"/>
									<input type="checkbox" id="use_default_correction_css2" name="wgs_general_settings[use_default_correction_css2]"
									value="1"' . checked( 1, esc_attr( $options['use_default_correction_css2']), false ) . ' />'
								);
								echo '<br /><span class="description">' . __('If this option is turned on, some css will be applied to improve the appearance of search elements. Styling of Google Search box is theme dependent because your theme settings have an effect on Google CSE styling too. It is possible that custom css settings are needed. Aks your site designer about it.','wp-google-search') . '</span>';
								
								?>    
							</td>
						</tr>							
	
						<tr valign="top">
							<th scope="row"><?php echo __('Use default corrections CSS v3','wp-google-search') . ':' ?></th>
							<td>
								<?php
								printf(
									'<input type="hidden" name="wgs_general_settings[use_default_correction_css3]" value="0"/>
									<input type="checkbox" id="use_default_correction_css3" name="wgs_general_settings[use_default_correction_css3]"
									value="1"' . checked( 1, esc_attr( $options['use_default_correction_css3']), false ) . ' />'
								);
								echo '<br /><span class="description">' . __('Switch it on to move down search button some pixels.','wp-google-search') . '</span>';
									
								?>    
							</td>
						</tr>							
	
						<tr valign="top">
							<th scope="row"><?php echo __('Search Page Target URL','wp-google-search') . ':' ?></th>
						
							<td>

								<?php						
						        printf(
						            '<input type="hidden" id="search_gcse_page_id" name="wgs_general_settings[search_gcse_page_id]" value="%s" />',
						            esc_attr( $options['search_gcse_page_id'])
								);
			
						        printf(
						            '<input type="text" id="search_gcse_page_url" name="wgs_general_settings[search_gcse_page_url]" value="%s" size="50" disabled />',
						            esc_attr( get_page_link( $options['search_gcse_page_id'] ))
								);
			
								echo '<br /><span class="description">' . __('The plugin automatically generated a page for displaying search results. You can see here the URL of this page. Please do not delete this page and do not change the permalink of it!','wp-google-search') . '</span>';								
								?>
							</td>
						</tr>

		
					</table>
					
		
					<?php
					submit_button();
					?>
		
				</form><!--end form-->
	
			</div><!--emd float:left; width: 70% / 100% -->
		
			<div class="wri_admin_left_sidebar" style="float:right; ">
				
				<style>
					a.wli_pro:link {color: black; text-decoration:none;}
					a.wli_pro:visited {color: black; text-decoration:none;}
					a.wli_pro:hover {color: black; text-decoration:underline;}
					a.wli_pro:active {color: black; text-decoration:none;}
				</style>
	
			
				<a href="http://webshoplogic.com/products/" class="wli_pro" target="_blank">
					<h2><?php _e('Try out WP Related Items plugin', 'wp-google-search'); ?></h2>
				</a>							
				
				<a href="http://webshoplogic.com/products/" class="wli_pro" target="_blank">
					<img src="http://emberpalanta.hu/wp-content/plugins/wp-related-items/images/WLI_product_box_PRO_upgrade_right_v1_2e_235x235.png" alt="Upgrade to PRO">
				</a>
	
				<?php echo __('WP Related Items plugin makes visible every kind of hidden connections of your WordPress site for your business.','wp-google-search') . '<br><br>' ; ?>				
				<?php echo __('Would you like to offer some related products to your blog posts? Do you have an event calendar plugin, and want to suggest some programs connected to an article? Do you have a custom movie catalog plugin and want to associate some articles to your movies?','wp-google-search') ; ?>
			
			</div>
	
		</div>
		<?php
					
	}
	
	function sanitize( $input )
	{
		if( !is_numeric( $input['id_number'] ) )
			$input['id_number'] = '';  
	
		if( !empty( $input['title'] ) )
			$input['title'] = sanitize_text_field( $input['title'] );
	
		return $input;
	}

	//delete wgs options
	//delete from em_options where option_name like 'wgs_gen%' 			


}