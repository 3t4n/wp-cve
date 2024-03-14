<?php
class SOLWZD_Construct_Admin_Settings {
    public function __construct() {
    	// Hook into the admin menu
    	add_action( 'admin_menu', array( $this, 'solwzd_create_plugin_settings_page' ) );
        // Add Settings and Fields
    	add_action( 'admin_init', array( $this, 'solwzd_setup_sections' ) );
    	add_action( 'admin_init', array( $this, 'solwzd_setup_fields_display_options' ));
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_general_settings' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_panels_utility' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_panels_utility_business' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_incentives' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_emails' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_appointments' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_zipcodes' ) );
		add_action('admin_head', array($this,  'solwzd_addCustomExportButton' ));
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_battery' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_webhook' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_advanced' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_text_configuration_wizard_selection_slide' ) );
		add_action( 'admin_init', array( $this, 'solwzd_setup_fields_solar_ev' ) );
		
		add_action( 'wp_ajax_solwzd_export_quotes', array($this, 'solwzd_export_quotes'));
		add_action( 'wp_ajax_nopriv_solwzd_export_quotes', array($this, 'solwzd_export_quotes'));
    }
	
	public function solwzd_addCustomExportButton(){
        global $current_screen;
    
        // Not our post type, exit earlier
        // You can remove this if condition if you don't have any specific post type to restrict to. 
        if (isset($_GET['post_type']) && $_GET['post_type'] == 'quote') {      
        ?>
            <script type="text/javascript">
    		jQuery(function(){
                jQuery("body.post-type-quote .wrap .page-title-action").after('<a href="<?php echo admin_url( 'admin-ajax.php' )."/?action=solwzd_export_quotes"; ?>" class="page-title-action up">Export</a>');
    		});
            </script>
        <?php
    	}	
    }
	
	public function solwzd_export_quotes(){
		header('Content-Encoding: UTF-8');
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="Export-quotes-'.date('Y-m-d').'.csv"');
		$output = fopen('php://output', 'w');
		fputcsv($output, array('#', 'Form Used', 'Motivates you', 'Best describes you','Important to me','Battery Storage', 'Address', 'Average monthly bill', 'Email', 'Phone', 'Opt-in', 'Solar Installation', 'Purchasing system', 'I\'m in military', 'I\'m a nurse or state worker', 'Learn more about storage', 'Communication Method','Communication Details', 'Date'),',');
		
		$args = array(  
			'post_type' => 'quote',
			'post_status' => 'publish',
			'posts_per_page' => -1
		);
		$loop = new WP_Query( $args ); 
		$cnt=1;
		while ( $loop->have_posts() ) : $loop->the_post(); 
			$_motivate_option = get_post_meta( get_the_ID(), '_motivate_option', true );
			$_more_about = get_post_meta( get_the_ID(), '_more_about', true );
			$_getting_best = get_post_meta( get_the_ID(), '_getting_best', true );
			$_confirmaddress = get_post_meta( get_the_ID(), '_confirmaddress', true );
			$_monthly_bill = get_post_meta( get_the_ID(), '_monthly_bill', true );
			$_email = get_post_meta( get_the_ID(), '_email', true );
			$_phone = get_post_meta( get_the_ID(), '_phone', true );
			$_describe_you = get_post_meta( get_the_ID(), '_describe_you', true );
			$_system_purchase_plan = get_post_meta( get_the_ID(), '_system_purchase_plan', true );
			$_military = get_post_meta( get_the_ID(), '_military', true );
			$_nurse = get_post_meta( get_the_ID(), '_nurse', true );
			$_learn_battery_storage = get_post_meta( get_the_ID(), '_learn_battery_storage', true );
			$_communication_method = get_post_meta( get_the_ID(), '_communication_method', true );
			$_communication_details = get_post_meta( get_the_ID(), '_communication_details', true );
			$_battery_storage = get_post_meta( get_the_ID(), '_battery_storage', true );
			$_date = get_post_meta( get_the_ID(), '_date', true );
			$_form_used = get_post_meta( get_the_ID(), '_form_used', true );
			$_opt_in = get_post_meta( get_the_ID(), '_opt_in', true );
			$row = array(
				$cnt, $_form_used, $_motivate_option, $_more_about, $_getting_best, $_battery_storage, $_confirmaddress, $_monthly_bill, $_email, $_phone, $_opt_in, $_describe_you, $_system_purchase_plan, $_military, $_nurse, $_learn_battery_storage, $_communication_method, $_communication_details, $_date
			);
			fputcsv($output, $row,',');
			$cnt++; 
		endwhile;
		wp_reset_postdata(); 
		exit();
	}
	
    public function solwzd_create_plugin_settings_page() {
    	// Add the menu item and page
    	$page_title = 'Solar Wizard Settings';
    	$menu_title = 'Solar Wizard';
    	$capability = 'manage_options';
    	$slug = 'solar_options';
    	$callback = array( $this, 'solwzd_plugin_settings_page_content' );
    	$icon = 'dashicons-image-filter';
    	$position = 100;
    	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
		add_submenu_page($slug, $page_title, 'Settings', 'manage_options', $slug);
		
		$link_our_new_CPT = 'edit.php?post_type=quote';
		add_submenu_page($slug, 'Solar Quotes', 'Quotes', 'manage_options', $link_our_new_CPT);
		
		$link_our_new_CPT = SOLWZD_UPGRADE_WEBSITE;
		add_submenu_page($slug, 'Upgrade', 'Upgrade', 'manage_options', $link_our_new_CPT);
    }
    public function solwzd_plugin_settings_page_content() {?>
    	<div class="wrap solar-wizard">
			<div id="icon-themes" class="icon32"></div>
			<h2>Solar Wizard Settings</h2>
			<?php settings_errors(); ?>
			
			<?php
				$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field($_GET[ 'tab' ]) : 'general_settings';
			?>
			
    		<h2 class="nav-tab-wrapper">
				<a href="?page=solar_options&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>">General Settings</a>
				<a href="?page=solar_options&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">Display Options</a>
				<a href="?page=solar_options&tab=panels_utility" class="nav-tab <?php echo $active_tab == 'panels_utility' ? 'nav-tab-active' : ''; ?>">Residential Settings</a>
				<a href="?page=solar_options&tab=panels_utility_business" class="nav-tab <?php echo $active_tab == 'panels_utility_business' ? 'nav-tab-active' : ''; ?>">Commercial Settings</a>
				<a href="?page=solar_options&tab=battery" class="nav-tab <?php echo $active_tab == 'battery' ? 'nav-tab-active' : ''; ?>">Battery</a>
				<a href="?page=solar_options&tab=solar_ev" class="nav-tab <?php echo $active_tab == 'solar_ev' ? 'nav-tab-active' : ''; ?>">Solar EV</a>
				<a href="?page=solar_options&tab=incentives" class="nav-tab <?php echo $active_tab == 'incentives' ? 'nav-tab-active' : ''; ?>">Incentives</a>
				<a href="?page=solar_options&tab=emails" class="nav-tab <?php echo $active_tab == 'emails' ? 'nav-tab-active' : ''; ?>">Email Settings</a>
				<a href="?page=solar_options&tab=appointments" class="nav-tab <?php echo $active_tab == 'appointments' ? 'nav-tab-active' : ''; ?>">Appointments</a>
				<a href="?page=solar_options&tab=operative_zipcodes" class="nav-tab <?php echo $active_tab == 'operative_zipcodes' ? 'nav-tab-active' : ''; ?>">Zip Codes</a>
				<a href="?page=solar_options&tab=text_configuration&sub_tab=wizard_selection" class="nav-tab <?php echo $active_tab == 'text_configuration' ? 'nav-tab-active' : ''; ?>">Text Configuration</a>
				<a href="?page=solar_options&tab=webhook" class="nav-tab <?php echo $active_tab == 'webhook' ? 'nav-tab-active' : ''; ?>">Webhook</a>
				<a href="?page=solar_options&tab=advanced" class="nav-tab <?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>">Advanced</a>
				<a href="?page=solar_options&tab=how_to_use" class="nav-tab <?php echo $active_tab == 'how_to_use' ? 'nav-tab-active' : ''; ?>">How to use?</a>
			</h2>
			
    		<form method="POST" action="options.php">
                <?php
					$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field($_GET[ 'tab' ]) : 'general_settings';
					$sub_tab = isset( $_GET[ 'sub_tab' ] ) ? sanitize_text_field($_GET[ 'sub_tab' ]) : 'general';
					if( $active_tab == 'general_settings' ) {
						settings_fields( 'general_settings' );
						do_settings_sections( 'general_settings' );
						echo '<p>Use pro version to change settings. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
					} else if( $active_tab == 'display_options' ) {
						settings_fields( 'display_options' );
						do_settings_sections( 'display_options' );
						echo '<p>Use pro version to change settings. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
					} else if( $active_tab == 'panels_utility' ) {
						settings_fields( 'panels_utility' );
						do_settings_sections( 'panels_utility' );
						echo '<p>Use pro version to change settings. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
					} else if( $active_tab == 'panels_utility_business' ) {
						settings_fields( 'panels_utility_business' );
						do_settings_sections( 'panels_utility_business' );
					} else if( $active_tab == 'battery' ) {
						settings_fields( 'battery' );
						do_settings_sections( 'battery' );
					} else if( $active_tab == 'webhook' ) {
						settings_fields( 'webhook' );
						do_settings_sections( 'webhook' );
					} else if( $active_tab == 'advanced' ) {
						settings_fields( 'advanced' );
						do_settings_sections( 'advanced' );
					} else if( $active_tab == 'solar_ev' ) {
						settings_fields( 'solar_ev' );
						do_settings_sections( 'solar_ev' );
					} else if( $active_tab == 'incentives' ) {
						settings_fields( 'incentives' );
						do_settings_sections( 'incentives' );
						echo '<p>Use pro version to have commercial settings. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
					} else if( $active_tab == 'how_to_use' ) {
						do_settings_sections( 'how_to_use' );
					} else if( $active_tab == 'emails' ) {
						settings_fields( 'emails' );
						do_settings_sections( 'emails' );
						echo '<p>Use pro version to change settings. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
					} else if( $active_tab == 'appointments' ) {
						settings_fields( 'appointments' );
						do_settings_sections( 'appointments' );
					} else if( $active_tab == 'operative_zipcodes' ) {
						settings_fields( 'operative_zipcodes' );
						do_settings_sections( 'operative_zipcodes' );
					} else if( $active_tab == 'text_configuration' ) {
						if($sub_tab == 'wizard_selection'){
							settings_fields( 'text_configuration_wizard_selection' );
							do_settings_sections( 'text_configuration_wizard_selection' );	
						} 
					}
					if( $active_tab != 'how_to_use' && $active_tab != 'battery' && $active_tab != 'appointments' && $active_tab != 'operative_zipcodes' && $active_tab != 'advanced' && $active_tab != 'panels_utility_business' && $active_tab != 'webhook' && $active_tab != 'text_configuration' && $active_tab != 'solar_ev') {
						submit_button();
					}
                ?>
    		</form>
    	</div> <?php
    }
    
    public function solwzd_setup_sections() {
        
			add_settings_section( 'general_settings_section', 'General Settings', array( $this, 'solwzd_section_callback' ), 'general_settings' );
			add_settings_section( 'display_options_section', 'Display Settings', array( $this, 'solwzd_section_callback' ), 'display_options' );
			add_settings_section( 'panels_utility_section', 'Panels and Utility Settings (Residential)', array( $this, 'solwzd_section_callback' ), 'panels_utility' );
			add_settings_section( 'panels_utility_section_business', 'Panels and Utility Settings (Commercial)', array( $this, 'solwzd_section_callback' ), 'panels_utility_business' );
			add_settings_section( 'battery_section', 'Battery Settings', array( $this, 'solwzd_section_callback' ), 'battery' );
			add_settings_section( 'solar_ev_section', 'Solar EV Settings', array( $this, 'solwzd_section_callback' ), 'solar_ev' );
			add_settings_section( 'webhook_section', 'Webhook Settings', array( $this, 'solwzd_section_callback' ), 'webhook' );
			add_settings_section( 'advanced_section', 'Advanced Settings', array( $this, 'solwzd_section_callback' ), 'advanced' );
			add_settings_section( 'incentives_section', 'Incentives Settings', array( $this, 'solwzd_section_callback' ), 'incentives' );
			add_settings_section( 'emails_section', 'Notification Settings', array( $this, 'solwzd_section_callback' ), 'emails' );
			add_settings_section( 'appointments_section', 'Appointments Settings', array( $this, 'solwzd_section_callback' ), 'appointments' );
			add_settings_section( 'operative_zipcodes_section', 'Operative Zip Codes Settings', array( $this, 'solwzd_section_callback' ), 'operative_zipcodes' );
			add_settings_section( 'wizard_selection_text_configuration_section', 'Wizard Selection Slide Text Configuration', array( $this, 'solwzd_section_callback' ), 'text_configuration_wizard_selection' );
			add_settings_section( 'how_to_use_section', '', array( $this, 'solwzd_section_callback' ), 'how_to_use' );
    }
    public function solwzd_section_callback( $arguments ) {
		switch( $arguments['id'] ){
    		case 'general_settings_section':
    			echo 'Set general information for the Wizard';
    			break;
    		case 'display_options_section':
    			echo 'Set the display of the Wizard';
    			break;
			case 'panels_utility_section':
    			echo 'Panel setting and define rates of utility';
    			break;
			case 'panels_utility_section_business':
    			echo '<p>Panel setting and define rates of utility</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
    			break;
			case 'battery_section':
    			echo '<p>Battery settings are configurable. Below is an example of how many solar companies use this tool. The details are not visible to the end user and are for internal team knowledge. Battery settings only apply to residential solar solutions.
				</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
    			break;
			case 'solar_ev_section':
				echo 
					'<p>Solar EV settings are configurable.</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
				break;
    		case 'incentives_section':
    			echo '<p>Incentive settings for users will calculate for customers who select cash as the method they would like to pay for their solar system.</p>
				<p>To use the incentives, you must add a row for every incentive uniquely and then apply a percentage or flat discount as applicable.</p>
				<p>For those using the free version, commercial options are not available. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
    			break;
			case 'emails_section':
    			echo '<p>Send emails to administrator and users</p><p>To get all options availalble <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
    			break;
			case 'appointments_section':
				echo '<p>Set Appointment hours for the last step of the Wizard</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
				break;
			case 'operative_zipcodes_section':
					echo '<p>Save the operative zip codes </p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
					break;
			case 'wizard_selection_text_configuration_section':
				$str = $this->solwzd_text_configuration_subtabs($_GET['sub_tab']);
				$str .=	'<p>Text configuration for the Wizard selection type.</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
					echo $str;
					break;
			case 'webhook_section':
    			echo '<p>Send quote data to URL using POST request</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
    			break;
			case 'advanced_section':
				echo '<p>Advanced Settings</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>';
				break;
			case 'how_to_use_section':
				echo '
					<h3>About Solar Wizard</h3>
					<p>Solar Wizard was created with two audiences in mind.</p>
					<ul style="list-style: initial;list-style-position: inside;">
						<li>For the consumer: our mission is to provide transparency from solar companies to the end user, increasing trust and confidence in going solar. They can understand what the potential costs may be even before committing to a free quote.</li>
						<li>For solar companies: we want to eliminate false leads to save you time and energy, allowing you to concentrate efforts on true potential prospects.</li>
					</ul> 
					<h3>How to use?</h3>
					<p>Use the shortcode [solar_wizard] anywhere in a post, page or popup.</p>
					<p>When you’re ready to <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">upgrade</a> to pro, you’ll be able to enjoy all the amazing features Solar Wizard can offer your company. Please visit <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">solarwizardplugin.com</a> to find more tips, tricks and documentation on our website.</p>
					<p>We love customer feedback! To suggest new features or troubleshoot any plugin problems, please contact us through our website.</p>
					<p>To view the math click <a href="https://docs.google.com/spreadsheets/d/1Sm8QBD5bEgIu56iEvdzwOwiuV4GKffn9/edit#gid=1242844476" target="_blank">here</a></p>';
				break;
    	}
    }
	public function solwzd_text_configuration_subtabs($active_tab){
		$str = '<div class="nav-tab-wrapper">';
		$subtab_array = array(
			array( 'title' => 'Wizard Selection', 'slug' => 'wizard_selection'),
			array( 'title' => 'Solar System', 'slug' => ''),
			array( 'title' => 'Solar Storage', 'slug' => ''),
			array( 'title' => 'Motivation', 'slug' => ''),
			array( 'title' => 'Describe You', 'slug' => ''),
			array( 'title' => 'Fill Blank', 'slug' => ''),
			array( 'title' => 'EV Option', 'slug' => ''),
			array( 'title' => 'State', 'slug' => ''),
			array( 'title' => 'Address Input', 'slug' => ''),
			array( 'title' => 'System Size', 'slug' => ''),
			array( 'title' => 'System Purchase', 'slug' => ''),
			array( 'title' => 'System Cost', 'slug' => ''),
			array( 'title' => 'Final Step', 'slug' => ''),
			array( 'title' => 'Errors', 'slug' => ''),
			array( 'title' => 'Miscellaneous', 'slug' => '')
		);
		foreach($subtab_array as $sub_tab){
			if($sub_tab['slug'] != ''){
				$str .= '<a href="?page=solar_options&tab=text_configuration&sub_tab='.$sub_tab['slug'].'" class="nav-tab';
							if(isset($_GET['sub_tab']) && $_GET['sub_tab'] == $sub_tab['slug']){
								$str .= ' nav-tab-active';
							} 
				$str .= '">'.$sub_tab['title'].'</a>';
			} else {
				$str .= '<span class="nav-tab">'.$sub_tab['title'].'</span>';
			}
		}
		$str .= '</div>';
		return $str;
	}
	public function solwzd_setup_fields_text_configuration_wizard_selection_slide(){
		$fields = array(
			array(
        		'uid' => 'sw_wizard_selection_subtitle',
        		'label' => 'Wizard Selection Subtitle',
        		'section' => 'wizard_selection_text_configuration_section',
        		'type' => 'text_lang',
				'disable' => true,
        		'placeholder' => 'Pick an assessment type & enter your name',
        		'helper' => '',
        		'supplimental' => '', 
        	),
			array(
        		'uid' => 'sw_wizard_selection_lite_title',
        		'label' => 'Wizard Selection Lite Title',
        		'section' => 'wizard_selection_text_configuration_section',
        		'type' => 'text_lang',
				'disable' => true,
        		'placeholder' => 'Quick',
        		'helper' => '',
        		'supplimental' => '', 
        	),
			array(
        		'uid' => 'sw_wizard_selection_full_title',
        		'label' => 'Wizard Selection Full Title',
        		'section' => 'wizard_selection_text_configuration_section',
        		'type' => 'text_lang',
				'disable' => true,
        		'placeholder' => 'More thorough',
        		'helper' => '',
        		'supplimental' => '', 
        	),
			array(
        		'uid' => 'sw_translation_first_name_label',
        		'label' => 'First Name (Label)',
        		'section' => 'wizard_selection_text_configuration_section',
        		'type' => 'text_lang',
				'disable' => true,
        		'placeholder' => 'First Name',
        		'helper' => '',
        		'supplimental' => '', 
        	),
			array(
        		'uid' => 'sw_translation_last_name_label',
        		'label' => 'Last Name (Label)',
        		'section' => 'wizard_selection_text_configuration_section',
        		'type' => 'text_lang',
				'disable' => true,
        		'placeholder' => 'Last Name',
        		'helper' => '',
        		'supplimental' => '', 
        	)
		);
		foreach( $fields as $field ){
			if( $field['type'] == 'text_lang' || $field['type'] == 'textarea_lang' || $field['type'] == 'editor_lang' ){
				
				$locale_language = get_option('sw_language');
				$default_languge = 'en';
				
        		add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'text_configuration_wizard_selection', $field['section'], $field );
            	register_setting( 'text_configuration_wizard_selection', $field['uid'].'_en' );
				if($locale_language != ''){
					add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'text_configuration_wizard_selection', $field['section'], $field );
            		register_setting( 'text_configuration_wizard_selection', $field['uid'].'_'.$locale_language );
				}
			} else {
				add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'text_configuration_wizard_selection', $field['section'], $field );
            	register_setting( 'text_configuration_wizard_selection', $field['uid'] );
			}
    	}
	}
	public function solwzd_setup_fields_battery() {
		$fields = array(
			//General Settings Fields
			array(
        		'uid' => 'sw_battery_manufacturer',
        		'label' => 'Battery Manufacturer Name',
        		'section' => 'battery_section',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => 'Battery used in solar system',
        	),
			array(
        		'uid' => 'sw_battery_matrix',
        		'label' => 'Battery Setup',
        		'section' => 'battery_section',
				'disable' => true,
        		'type' => 'battery_matrix',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
        	)
		);
		foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'battery', $field['section'], $field );
            register_setting( 'battery', $field['uid'] );
    	}
	}
	public function solwzd_setup_fields_solar_ev() {
		$fields = array(
			//General Settings Fields
			array(
        		'uid' => 'sw_solar_ev_battery_multiplier',
        		'label' => 'EV Calculation Multiplier',
				'disable' => true,
        		'section' => 'solar_ev_section',
        		'type' => 'battery_multiplier_metrix',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
        	),
		);
		foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'solar_ev', $field['section'], $field );
            register_setting( 'solar_ev', $field['uid'] );
    	}
	}
	
	public function solwzd_setup_fields_general_settings() {
		$fields = array(
			//General Settings Fields
			array(
        		'uid' => 'sw_company_name',
        		'label' => 'Company Name',
        		'section' => 'general_settings_section',
        		'type' => 'text',
        		'placeholder' => 'Solar Wizard',
        		'helper' => '',
        		'supplimental' => 'Solar Wizard Company Name',
				'disable' => true,
				'readonly' => true
        	),
			array(
        		'uid' => 'sw_wizard_logo',
        		'label' => 'Wizard Logo',
        		'section' => 'general_settings_section',
        		'type' => 'image',
        		'helper' => '',
        		'supplimental' => 'Wizard first screen logo. Max. 400px in width.',
				'disable' => true,
				'readonly' => true
        	),
			array(
        		'uid' => 'sw_wizard_title',
        		'label' => 'Wizard Title',
        		'section' => 'general_settings_section',
        		'type' => 'text',
        		'placeholder' => 'Welcome to Solar Wizard',
        		'helper' => '',
        		'supplimental' => 'Solar Wizard title on first screen',
				'disable' => true,
				'readonly' => true
        	),
			array(
        		'uid' => 'sw_google_autocomplete_address',
        		'label' => 'Google Places API',
        		'section' => 'general_settings_section',
        		'type' => 'text',
        		'placeholder' => 'API Key',
        		'helper' => '',
        		'supplimental' => 'API key for auto complete address',
				'readonly' => true,
				'disable' => true
        	),
			array(
        		'uid' => 'sw_currency_symbol',
        		'label' => 'Currency',
        		'section' => 'general_settings_section',
        		'type' => 'select',
				'sort_array' => true,
				'options' => array(
					'AED' => 'United Arab Emirates dirham (د.إ)',
					'AFN' => 'Afghan afghani (؋)',
					'ALL' => 'Albanian lek (L)',
					'AMD' => 'Armenian dram (AMD)',
					'ANG' => 'Netherlands Antillean guilder (ƒ)',
					'AOA' => 'Angolan kwanza (Kz)',
					'ARS' => 'Argentine peso ($)',
					'AUD' => 'Australian dollar ($)',
					'AWG' => 'Aruban florin (Afl.)',
					'AZN' => 'Azerbaijani manat (AZN)',
					'BAM' => 'Bosnia and Herzegovina convertible mark (KM)',
					'BBD' => 'Barbadian dollar ($)',
					'BDT' => 'Bangladeshi taka (৳&nbsp;)',
					'BGN' => 'Bulgarian lev (лв.)',
					'BHD' => 'Bahraini dinar (.د.ب)',
					'BIF' => 'Burundian franc (Fr)',
					'BMD' => 'Bermudian dollar ($)',
					'BND' => 'Brunei dollar ($)',
					'BOB' => 'Bolivian boliviano (Bs.)',
					'BRL' => 'Brazilian real (R$)',
					'BSD' => 'Bahamian dollar ($)',
					'BTN' => 'Bhutanese ngultrum (Nu.)',
					'BWP' => 'Botswana pula (P)',
					'BYR' => 'Belarusian ruble (Br)',
					'BZD' => 'Belize dollar ($)',
					'CAD' => 'Canadian dollar ($)',
					'CDF' => 'Congolese franc (Fr)',
					'CHF' => 'Swiss franc (CHF)',
					'CLP' => 'Chilean peso ($)',
					'CNY' => 'Chinese yuan (¥)',
					'COP' => 'Colombian peso ($)',
					'CRC' => 'Costa Rican colón (₡)',
					'CUC' => 'Cuban convertible peso ($)',
					'CUP' => 'Cuban peso ($)',
					'CVE' => 'Cape Verdean escudo ($)',
					'CZK' => 'Czech koruna (Kč)',
					'DJF' => 'Djiboutian franc (Fr)',
					'DKK' => 'Danish krone (DKK)',
					'DOP' => 'Dominican peso (RD$)',
					'DZD' => 'Algerian dinar (د.ج)',
					'EGP' => 'Egyptian pound (EGP)',
					'ETB' => 'Ethiopian birr (Br)',
					'EUR' => 'Euro (€)',
					'FJD' => 'Fijian dollar ($)',
					'FKP' => 'Falkland Islands pound (£)',
					'GBP' => 'Pound sterling (£)',
					'GEL' => 'Georgian lari (₾)',
					'GHS' => 'Ghana cedi (₵)',
					'GIP' => 'Gibraltar pound (£)',
					'GMD' => 'Gambian dalasi (D)',
					'GNF' => 'Guinean franc (Fr)',
					'GTQ' => 'Guatemalan quetzal (Q)',
					'GYD' => 'Guyanese dollar ($)',
					'HKD' => 'Hong Kong dollar ($)',
					'HNL' => 'Honduran lempira (L)',
					'HRK' => 'Croatian kuna (kn)',
					'HTG' => 'Haitian gourde (G)',
					'HUF' => 'Hungarian forint (Ft)',
					'IDR' => 'Indonesian rupiah (Rp)',
					'ILS' => 'Israeli new shekel (₪)',
					'IMP' => 'Manx pound (£)',
					'INR' => 'Indian rupee (₹)',
					'IQD' => 'Iraqi dinar (د.ع)',
					'IRR' => 'Iranian rial (﷼)',
					'IRT' => 'Iranian toman (تومان)',
					'ISK' => 'Icelandic króna (kr.)',
					'JEP' => 'Jersey pound (£)',
					'JMD' => 'Jamaican dollar ($)',
					'JOD' => 'Jordanian dinar (د.ا)',
					'JPY' => 'Japanese yen (¥)',
					'KES' => 'Kenyan shilling (KSh)',
					'KGS' => 'Kyrgyzstani som (сом)',
					'KHR' => 'Cambodian riel (៛)',
					'KMF' => 'Comorian franc (Fr)',
					'KPW' => 'North Korean won (₩)',
					'KRW' => 'South Korean won (₩)',
					'KWD' => 'Kuwaiti dinar (د.ك)',
					'KYD' => 'Cayman Islands dollar ($)',
					'KZT' => 'Kazakhstani tenge (₸)',
					'LAK' => 'Lao kip (₭)',
					'LBP' => 'Lebanese pound (ل.ل)',
					'LKR' => 'Sri Lankan rupee (රු)',
					'LRD' => 'Liberian dollar ($)',
					'LSL' => 'Lesotho loti (L)',
					'LYD' => 'Libyan dinar (ل.د)',
					'MAD' => 'Moroccan dirham (د.م.)',
					'MDL' => 'Moldovan leu (MDL)',
					'MGA' => 'Malagasy ariary (Ar)',
					'MKD' => 'Macedonian denar (ден)',
					'MMK' => 'Burmese kyat (Ks)',
					'MNT' => 'Mongolian tögrög (₮)',
					'MOP' => 'Macanese pataca (P)',
					'MRU' => 'Mauritanian ouguiya (UM)',
					'MUR' => 'Mauritian rupee (₨)',
					'MVR' => 'Maldivian rufiyaa (.ރ)',
					'MWK' => 'Malawian kwacha (MK)',
					'MXN' => 'Mexican peso ($)',
					'MYR' => 'Malaysian ringgit (RM)',
					'MZN' => 'Mozambican metical (MT)',
					'NAD' => 'Namibian dollar (N$)',
					'NGN' => 'Nigerian naira (₦)',
					'NIO' => 'Nicaraguan córdoba (C$)',
					'NOK' => 'Norwegian krone (kr)',
					'NPR' => 'Nepalese rupee (₨)',
					'NZD' => 'New Zealand dollar ($)',
					'OMR' => 'Omani rial (ر.ع.)',
					'PAB' => 'Panamanian balboa (B/.)',
					'PEN' => 'Sol (S/)',
					'PGK' => 'Papua New Guinean kina (K)',
					'PHP' => 'Philippine peso (₱)',
					'PKR' => 'Pakistani rupee (₨)',
					'PLN' => 'Polish złoty (zł)',
					'PRB' => 'Transnistrian ruble (р.)',
					'PYG' => 'Paraguayan guaraní (₲)',
					'QAR' => 'Qatari riyal (ر.ق)',
					'RON' => 'Romanian leu (lei)',
					'RSD' => 'Serbian dinar (рсд)',
					'RUB' => 'Russian ruble (₽)',
					'RWF' => 'Rwandan franc (Fr)',
					'SAR' => 'Saudi riyal (ر.س)',
					'SBD' => 'Solomon Islands dollar ($)',
					'SCR' => 'Seychellois rupee (₨)',
					'SDG' => 'Sudanese pound (ج.س.)',
					'SEK' => 'Swedish krona (kr)',
					'SGD' => 'Singapore dollar ($)',
					'SHP' => 'Saint Helena pound (£)',
					'SLL' => 'Sierra Leonean leone (Le)',
					'SOS' => 'Somali shilling (Sh)',
					'SRD' => 'Surinamese dollar ($)',
					'SSP' => 'South Sudanese pound (£)',
					'STN' => 'São Tomé and Príncipe dobra (Db)',
					'SYP' => 'Syrian pound (ل.س)',
					'SZL' => 'Swazi lilangeni (L)',
					'THB' => 'Thai baht (฿)',
					'TJS' => 'Tajikistani somoni (ЅМ)',
					'TMT' => 'Turkmenistan manat (m)',
					'TND' => 'Tunisian dinar (د.ت)',
					'TOP' => 'Tongan paʻanga (T$)',
					'TRY' => 'Turkish lira (₺)',
					'TTD' => 'Trinidad and Tobago dollar ($)',
					'TWD' => 'New Taiwan dollar (NT$)',
					'TZS' => 'Tanzanian shilling (Sh)',
					'UAH' => 'Ukrainian hryvnia (₴)',
					'UGX' => 'Ugandan shilling (UGX)',
					'USD' => 'United States (US) dollar ($)',
					'UYU' => 'Uruguayan peso ($)',
					'UZS' => 'Uzbekistani som (UZS)',
					'VEF' => 'Venezuelan bolívar (Bs F)',
					'VES' => 'Bolívar soberano (Bs.S)',
					'VND' => 'Vietnamese đồng (₫)',
					'VUV' => 'Vanuatu vatu (Vt)',
					'WST' => 'Samoan tālā (T)',
					'XAF' => 'Central African CFA franc (CFA)',
					'XCD' => 'East Caribbean dollar ($)',
					'XOF' => 'West African CFA franc (CFA)',
					'XPF' => 'CFP franc (Fr)',
					'YER' => 'Yemeni rial (﷼)',
					'ZAR' => 'South African rand (R)',
					'ZMW' => 'Zambian kwacha (ZK)'
				),
				'default' => array('USD'),
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_language',
        		'label' => 'Language',
        		'section' => 'general_settings_section',
        		'type' => 'language',
        		'placeholder' => '',
				'disable' => true,
        		'helper' => '',
        		'supplimental' => 'Solar wizard Language. Configure Translation in Text Configuration tab.',
        	),
			array(
        		'uid' => 'sw_enable_panel_utility_home',
        		'label' => 'Enable Residential Settings?',
        		'section' => 'general_settings_section',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array('yes'),
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_enable_panel_utility_business',
        		'label' => 'Enable Commercial Settings?',
        		'section' => 'general_settings_section',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
				'uid' => 'sw_enable_battery_option',
				'label' => 'Enable Battery Storage Selection Step?',
				'section' => 'general_settings_section',
				'type' => 'checkbox',
				'options' => array(
					'yes' => 'Yes'
				),
				'helper' => '',
        		'supplimental' => '',
				'default' => array(),
				'disable' => true
			),
			array(
				'uid' => 'sw_enable_solar_ev_option',
				'label' => 'Enable Solar EV Selection Option?',
				'section' => 'general_settings_section',
				'type' => 'checkbox',
				'options' => array(
					'yes' => 'Yes'
				),
				'helper' => '',
        		'supplimental' => '',
				'default' => array(),
				'disable' => true
			),
			array(
				'uid' => 'sw_enable_how_you_hear_option',
				'label' => 'Enable How did you hear about us Option?',
				'section' => 'general_settings_section',
				'type' => 'checkbox',
				'options' => array(
					'yes' => 'Yes'
				),
				'helper' => '',
        		'supplimental' => '',
				'default' => array(),
				'disable' => true
			),
			array(
        		'uid' => 'sw_enable_state_options',
        		'label' => 'Enable US State wise calculation',
        		'section' => 'general_settings_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'helper' => '',
        		'supplimental' => '',
                'default' => array(),
				'disable' => true
        	),
			array(
        		'uid' => 'sw_state_options_list',
        		'label' => 'US States providing services',
        		'section' => 'general_settings_section',
				'supplimental' => "",
				'helper' => '',
        		'type' => 'select',
				'class' => 'sw_select2_tags',
				'options' => array(
					"empty" => "Please select states"
				),
				'disable' => true,
				'default' => array("empty")
        	),
			array(
        		'uid' => 'sw_enable_custom_calc_options',
        		'label' => 'Enable Service Area',
        		'section' => 'general_settings_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'helper' => '',
        		'supplimental' => '',
                'default' => array(),
				'disable' => true
        	),
			array(
        		'uid' => 'sw_enable_custom_calc_options_list',
        		'label' => 'Service Area',
        		'section' => 'general_settings_section',
        		'type' => 'text_tagify',
        		'placeholder' => '',
        		'helper' => '',
				'default' => '[{"value":"Zone1"},{"value":"Zone2"},{"value":"Zone3"}]',
        		'supplimental' => 'Write the Service Area name and hit enter',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_default_phone_country_code',
        		'label' => 'Default Phone Country Code',
        		'section' => 'general_settings_section',
        		'type' => 'country_code',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_enable_submit_redirect',
        		'label' => 'Redirect after wizard completion?',
        		'section' => 'general_settings_section',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_show_schedule_consultation_page',
        		'label' => 'Show Schedule Consultation Page',
        		'section' => 'general_settings_section',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_wizard_redirect_url',
        		'label' => 'Redirect URL',
        		'section' => 'general_settings_section',
				'disable' => true,
        		'type' => 'text',
        		'placeholder' => 'URL',
        		'helper' => '',
        		'supplimental' => 'Webpage url to redirect after completion of wizard',
        	)
		);
		foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'general_settings', $field['section'], $field );
            register_setting( 'general_settings', $field['uid'] );
    	}
	}
	
	public function solwzd_setup_fields_incentives() {
		$fields = array(
			//Incetinves Settings Fields
			array(
        		'uid' => 'sw_incentives_repeater_name',
        		'label' => 'Available Incentives',
        		'section' => 'incentives_section',
        		'type' => 'repeater',
				'helper' => '', 
				'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_incentives_repeater_value',
        		'label' => '',
        		'section' => 'incentives_section',
        		'type' => 'repeater_hidden',
				'helper' => '', 
				'supplimental' => '',
				'class' => 'admin-hidden-field'
        	),
			array(
        		'uid' => 'sw_incentives_repeater_value_type',
        		'label' => '',
        		'section' => 'incentives_section',
        		'type' => 'repeater_hidden',
				'helper' => '', 
				'supplimental' => '',
				'class' => 'admin-hidden-field'
        	),
			array(
        		'uid' => 'sw_incentives_repeater_applied',
        		'label' => '',
        		'section' => 'incentives_section',
        		'type' => 'repeater_hidden',
				'helper' => '', 
				'supplimental' => '',
				'class' => 'admin-hidden-field'
        	),
			array(
				'uid' => 'html'.rand(),
				'label' => '',
				'section' => 'incentives_section',
				'type' => 'html',
				'helper' => '<h3>System Size Incentives</h3><p>REC Credits are based on the KW system size. An example of how this works:</p>
				<p>Size Of System – 3.43 KW</p>
				<p>REC Credit – 1.382</p>
				<p>Years of Credit – 9</p> 
				<p>REC Per KW - $35</p> 
				<p># of Certificates – KW x REC Credit x Years = 42.66</p>
				<p>Total Incentive - # of Certificates x REC Per kW = $1,493.18</p>
				',
				'supplimental' => '',
				'code' => ''
			),
			array(
        		'uid' => 'sw_system_size_incentives',
        		'label' => 'Available Incentives',
        		'section' => 'incentives_section',
        		'type' => 'system_size_incentive_metrix',
				'helper' => '',
        		'supplimental' => ''
        	),
		);
		foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'incentives', $field['section'], $field );
            register_setting( 'incentives', $field['uid'] );
    	}
	}

	public function solwzd_setup_fields_appointments() {
		$fields = array(
			//General Settings Fields
			array(
        		'uid' => 'sw_avail_communication_method',
        		'label' => 'Commuication Methods',
        		'section' => 'appointments_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'call' => 'Call',
					'virtual_meeting' => 'Virtual Meeting',
					'in_person_meeting' => 'In-Person Meeting'
        		),
				'helper' => '',
				'disable' => true,
        		'supplimental' => '',
                'default' => array()
        	),
			array(
				'uid' => 'sw_appointment_time_enable',
				'label' => 'Enable Time Selection',
				'section' => 'appointments_section',
				'type' => 'checkbox',
				'helper' => '',
				'supplimental' => '',
				'options' => array(
					'yes' => 'Yes'
				),
				'disable' => true,
				'default' => array(),
			),
			array(
				'uid' => 'sw_appointment_hours_set',
				'label' => 'Appointment Schedule',
				'section' => 'appointments_section',
				'type' => 'weekhours',
				'placeholder' => '',
				'helper' => '',
				'supplimental' => '',
				'disable' => true
			),
			array(
				'uid' => 'sw_appointment_hours_in_advance',
				'label' => 'Hours in Advance',
				'section' => 'appointments_section',
				'type' => 'number',
				'helper' => '',
				'placeholder' => '',
				'disable' => true,
				'supplimental' => 'Appointment cannot be booked before the hours in advanced.',
			),
			array(
				'uid' => 'sw_appointment_interval',
				'label' => 'Pick Frequency of Appointment Intervals',
				'section' => 'appointments_section',
				'type' => 'select',
				'disable' => true,
				'options' => array(
					15 => '15 Min',
					30 => '30 Min',
					45 => '45 Min',
					60 => '60 Min',
					90 => '90 Min',
					120 => '120 Min'
				),
				'default' => array(15),
				'helper' => '',
				'placeholder' => '',
				'supplimental' => '',
			)
		);
		foreach( $fields as $field ){
			add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'appointments', $field['section'], $field );
			register_setting( 'appointments', $field['uid'] );
		}
	}

	public function solwzd_setup_fields_zipcodes() {
		
		$fields = array(
			//General Settings Fields
			array(
				'uid' => 'sw_enable_operational_zip_codes',
				'label' => 'Enable Operational Zip Code Feature',
				'section' => 'operative_zipcodes_section',
				'type' => 'checkbox',
				'options' => array(
					'yes' => 'Yes'
				),
				'helper' => '',
				'disable' => true,
				'supplimental' => '',
				'default' => array()
			),
			array(
				'uid' => 'sw_operational_zip_codes_in_person',
				'label' => 'Operative Zip Codes - In Person',
				'section' => 'operative_zipcodes_section',
				'type' => 'textarea',
				'placeholder' => '96805',
				'helper' => '',
				'disable' => true,
				'supplimental' => 'Add comma seperated zip codes or select csv file having zip codes. <input type="file" class="csv_zipcodes" disabled />'
			),
			array(
				'uid' => 'sw_operational_zip_codes_virtual',
				'label' => 'Operative Zip Codes - Virtual',
				'section' => 'operative_zipcodes_section',
				'type' => 'textarea',
				'placeholder' => '96805',
				'helper' => '',
				'supplimental' => 'Add comma seperated zip codes or select csv file having zip codes. <input type="file" class="csv_zipcodes" disabled />',
				'disable' => true
			),
			array(
				'uid' => 'sw_operational_zip_codes_phone',
				'label' => 'Operative Zip Codes - Phone',
				'section' => 'operative_zipcodes_section',
				'type' => 'textarea',
				'placeholder' => '96805',
				'helper' => '',
				'supplimental' => 'Add comma seperated zip codes or select csv file having zip codes. <input type="file" class="csv_zipcodes" disabled />',
				'disable' => true
			)
		);
		foreach( $fields as $field ){
			add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'operative_zipcodes', $field['section'], $field );
			register_setting( 'operative_zipcodes', $field['uid'] );
		}
	}
	
	public function solwzd_setup_fields_emails() {
		$fields = array(
			//Emails Settings Fields
			array(
        		'uid' => 'sw_email_enable_admin_notification',
        		'label' => 'Send notification to Administrator?',
        		'section' => 'emails_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'supplimental' => '',
				'helper' => ''
        	),
			array(
        		'uid' => 'sw_email_send_mid_wizard',
        		'label' => 'Send email once user insert email.',
        		'section' => 'emails_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'supplimental' => '',
				'helper' => ''
        	),
			array(
        		'uid' => 'sw_email_at_wizard_completion',
        		'label' => 'Send email after wizard completion',
        		'section' => 'emails_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'supplimental' => '',
				'helper' => ''
        	),
			array(
        		'uid' => 'sw_email_from_name',
        		'label' => 'From name',
        		'section' => 'emails_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. Company name',
        		'helper' => '',
				'default' => '',
				'size' => 100, 
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_email_from_email',
        		'label' => 'From email',
        		'section' => 'emails_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. example@comapnyname.com',
        		'helper' => '',
				'default' => '',
				'size' => 100, 
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_email_admin_email',
        		'label' => 'Administrator Email<br />(Office Hours)',
        		'section' => 'emails_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. admin@website.com',
        		'helper' => '',
				'size' => 100, 
        		'supplimental' => 'Add comma separated emails to add multiple recipients',
        	),
			array(
        		'uid' => 'sw_email_enable_office_hours_setup',
        		'label' => 'Use Office Hours Email Notifications',
        		'section' => 'emails_section',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'helper' => '',
        		'supplimental' => '',
                'default' => array()
        	),
			array(
        		'uid' => 'sw_email_admin_email_non_office_hours',
        		'label' => 'Administrator Email<br />(Non-Office Hours)',
        		'section' => 'emails_section',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => 'i.e. admin@website.com',
        		'helper' => '',
				'size' => 100, 
        		'supplimental' => 'Add comma separated emails to add multiple recipients',
        	),
			array(
        		'uid' => 'sw_email_office_hours_set',
        		'label' => 'Office Hours',
				'disable' => true,
        		'section' => 'emails_section',
        		'type' => 'weekhours',
        		'helper' => '',
				'supplimental' => 'Select Monday to Sunday Office Hours',
        	),
			/*array(
        		'uid' => 'sw_email_enable_admin_notification_after_system_size',
        		'label' => 'Send notification to Administrator Once system size calculated?',
        		'section' => 'emails_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array()
        	),*/
			array(
        		'uid' => 'sw_email_enable_user_notification',
        		'label' => 'Send notification to User?',
        		'section' => 'emails_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'supplimental' => '',
				'helper' => ''
        	),
			array(
        		'uid' => 'sw_email_template_client_subject',
        		'label' => 'Client Email Subject',
        		'section' => 'emails_section',
        		'type' => 'text',
        		'placeholder' => 'Solar Wizard Quotation Successfully Placed',
				'disable' => true,
        		'helper' => '',
				'size' => 100, 
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_email_template_client',
        		'label' => 'Client Email Template',
        		'section' => 'emails_section',
        		'type' => 'html',
				'code' => '<p>Email template editor.</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>',
				'helper' => 'You can use these mail-tags. Tags in red color will be empty as per the wizard settings so you can ignore them or remove from the email template.',
				'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_email_template_administrator_subject',
        		'label' => 'Administrator Email Subject',
        		'section' => 'emails_section',
        		'type' => 'text',
        		'placeholder' => 'New Solar Wizard Quotation Placed',
				'disable' => true,
        		'helper' => '',
				'size' => 100, 
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_email_template_administrator',
        		'label' => 'Administrator Email Template',
        		'section' => 'emails_section',
        		'type' => 'html',
				'code' => '<p>Email template editor.</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>',
				'helper' => '',
				'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_email_template_administrator_system_size_subject',
        		'label' => 'Administrator Email Subject After System Size',
        		'section' => 'emails_section',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => 'Solar Wizard Quotation after System Size Calculation',
        		'helper' => '',
				'size' => 100, 
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_email_template_administrator_system_size',
        		'label' => 'Administrator Email Template After System Size',
        		'section' => 'emails_section',
        		'type' => 'html',
				'media-button' => true,
				'disable' => true,
				'code' => '<p>Email template editor.</p><p>Available in Pro version. <a href="'.SOLWZD_UPGRADE_WEBSITE.'" target="_blank">Click here</a> to upgrade.</p>',
				'supplimental' => '',
				'helper' => '', 
				'default' => ''
        	)
		);
		foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'emails', $field['section'], $field );
            register_setting( 'emails', $field['uid'] );
    	}
	}
	
	public function solwzd_setup_fields_panels_utility() {
		$fields = array(
			//General Settings Fields
			array(
        		'uid' => 'sw_panel_image',
        		'label' => 'Panel Image',
        		'section' => 'panels_utility_section',
        		'type' => 'image',
        		'helper' => '',
        		'supplimental' => 'Panel image visible on panel screen',
        	),
			array(
        		'uid' => 'sw_panel_manufacturer',
        		'label' => 'Panel Manufacturer',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_panel_watt',
        		'label' => 'Panel Watt',
        		'section' => 'panels_utility_section',
        		'type' => 'number',
				'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_electricity_kwh_cost',
        		'label' => 'Average kWh Utility Cost',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. 0.30',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_utility_increase_rate',
        		'label' => 'Electricity Increase Rate',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. 5',
        		'helper' => '',
        		'supplimental' => 'Rate in percentage',
        	),
			array(
        		'uid' => 'sw_environmental_derate_factor',
        		'label' => 'Environmental Derate Factor',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. 0.9',
        		'helper' => '',
        		'supplimental' => 'Environmental derate factor value',
        	),
			array(
				'uid' => 'sw_sunzone_values',
				'label' => 'Sun Zone Values',
				'section' => 'panels_utility_section',
				'type' => 'select',
				'options' => array(
					'3.4' => '300 | 3.4 effective sunhours',
					'4' => '350 | 4.0 effective sunhours',
					'4.6' => '400 | 4.6 effective sunhours',
					'5.2' => '450 | 5.2 effective sunhours',
					'5.8' => '500 | 5.8 effective sunhours',
					'6.4' => '550 | 6.4 effective sunhours'
				),
				'placeholder' => 'Sun Zone Values',
				'helper' => '<br />Measure effective sun hours per day <br /><br /><strong>OR</strong>',
				'supplimental' => '',
				'default' => array('4.6')
			),
			array(
        		'uid' => 'sw_sunzone_hours',
        		'label' => '',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => 'Custom effective sun hours<br />Leave blank to use Sun Zone values for the calculation',
        	),
			array(
				'uid' => 'sw_panel_annual_derate_home',
				'label' => 'Solar Panel Annual Derate (aging)',
				'section' => 'panels_utility_section',
				'type' => 'select',
				'options' => array(
					'0.65' => '0.65% | Standard',
					'0.30' => '0.30% | Performance'
				),
				'placeholder' => 'Solar Panel Annual Derate (aging)',
				'helper' => '<br />Used to derate annual production for years 2 thru 25<br /><br /><strong>OR</strong>',
				'supplimental' => '',
				'default' => array('0.65'),
				'disable' => true,
			),
			array(
				'uid' => 'sw_panel_annual_derate_custom_home',
				'label' => '',
				'section' => 'panels_utility_section',
				'type' => 'text',
				'placeholder' => '0.30',
				'helper' => '',
				'supplimental' => 'Custom annual derate value in %<br />Leave blank to use Solar Panel Annual Derate (aging) for the calculation',
				'disable' => true,
			),
			array(
        		'uid' => 'sw_show_potential_savings_home',
        		'label' => 'Show Potential Savings',	
        		'section' => 'panels_utility_section',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array('yes'),
				'helper' => '',
				'disable' => true,
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_potential_savings_years_home',
        		'label' => 'Potential Savings Over Years',
        		'section' => 'panels_utility_section',
        		'type' => 'select',
				'disable' => true,
				'options' => array(
					5 => '5 Years',
					10 => '10 Years',
					15 => '15 Years',
					20 => '20 Years',
					25 => '25 Years',
					30 => '30 Years',
					35 => '35 Years'
				),
				'default' => array(30),
				'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_enable_percentage_offset_home',
        		'label' => 'Enable monthly offset bill calculation?',
        		'section' => 'panels_utility_section',
        		'type' => 'checkbox',
				'disable' => true,
				'helper' => 'Note: Disabling offset will only apply to residential settings. If you disable, it will still be visible for EV path.',
				'supplimental' => '',
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array()
        	),
			array(
        		'uid' => 'html'.rand(),
        		'label' => '<h3>Calculations</h3>',
        		'section' => 'panels_utility_section',
        		'type' => 'html',
				'code' => '<p>Solar Wizard can calculate pricing based on fixed prices for high to low results, or by giving ranges determined by the size of the system.</p>',
				'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'html'.rand(),
        		'label' => '<strong>Definitions</strong>',
        		'section' => 'panels_utility_section',
        		'type' => 'html',
				'code' => '<ul style="list-style: disc;"><li>Cash Purchase: How much would YOU, the solar company, be willing to charge a customer who paid cash based on a simple cost per watt pricing ranges - i.e., $2.60 (lowest best price) or $4.25 (highest price).</li><li><strong>Financing/Lease: Two Options</strong><ul><li><strong>% Off</strong> - This is calculated by using the customers current utility bill and then, based on your experience selling solar in your marketplace, using the best financing available. What the customer can expect to save in the worst case (lowest) or best case (highest). Example: if our lowest savings value is 20% and highest is 50%, and if the customer\'s average monthly utility bill is $300, they can expect it to drop to between $180 and $240 per month with solar financing.</li><li><strong>$ Financing Price Per Watt</strong> – Like cash, this is for the price per watt including financing fees. The term of the loan must match the term loan label for the customer as we are going to calculate the monthly range of their new bill with financing over that term. Also, the monthly total cost is based on the gross system cost as most customers will decide if they want to pay down the loan with their incentive (if applicable).</li></ul></li></ul>',
				'helper' => '',
        		'supplimental' => '',
        	),
			array(
				'uid' => 'sw_min_req_sys_size_home',
				'label' => 'Minimum Required System Size',
				'section' => 'panels_utility_section',
				'type' => 'text',
				'placeholder' => 'i.e. 1.85',
				'helper' => '',
				'supplimental' => 'System Size in kW',
				'disable' => true,
			),
			array(
				'uid' => 'sw_max_per_slider_offset_home',
				'label' => 'Offset Slider Max Value',
				'section' => 'panels_utility_section',
				'type' => 'number',
				'placeholder' => 'i.e. 300%',
				'helper' => '',
				'supplimental' => '',
				'disable' => true,
			),
			array(
				'uid' => 'sw_billing_slider_range_min_max_home',
				'label' => 'Range of the Residential Billing Slider',
				'section' => 'panels_utility_section',
				'helper' => '',
        		'supplimental' => '',
				'type' => 'slider-range',
				'disable' => true
			),
			array(
				'uid' => 'sw_calculate_price',
				'label' => 'Calculate price based on?',
				'section' => 'panels_utility_section',
				'type' => 'radio',
				'disable' => true,
				'options' => array(
					'fixed_price_per_watt' => 'Fixed price per watt',
					'system_size_range' => 'System size range'
				),
				'placeholder' => 'Solar wizard can calculate price based on fixed price per watt or system size range',
				'helper' => '',
				'supplimental' => 'Solar wizard can calculate price based on fixed price per watt or system size range',
				'default' => array('fixed_price_per_watt')
			),
			array(
        		'uid' => 'sw_system_size_fixed_pricing_matrix',
        		'label' => 'System Size Fixed Pricing',
        		'section' => 'panels_utility_section',
        		'type' => 'system_size_fixed_price_metrix',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_system_size_matrix',
        		'label' => 'Price by System Size Range',
        		'section' => 'panels_utility_section',
        		'type' => 'system_size_metrix',
				'disable' => true,
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'html'.rand(),
        		'label' => '<h2>Financing Visual Results</h2>',
        		'section' => 'panels_utility_section',
        		'type' => 'html',
				'code' => '',
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_show_purchase_cash_home',
        		'label' => 'Allow Purchase on Cash?',	
        		'section' => 'panels_utility_section',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '',
				'disable' => true,
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_show_purchase_finance_home',
        		'label' => 'Allow Purchase on Finance?',	
        		'section' => 'panels_utility_section',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '',
				'disable' => true,
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_loan_rate',
        		'label' => 'Rate (Loan)',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. 1.99%',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_loan_term',
        		'label' => 'Term (Loan) Label',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. 10 Years',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_credit_score',
        		'label' => 'Credit Score (Loan)',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. 640+',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
				'uid' => 'sw_show_purchase_lease',
				'label' => 'Allow Purchase on Lease?',
				'section' => 'panels_utility_section',
				'type' => 'radio',
				'options' => array(
					'yes' => 'Yes',
					'no' => 'No'
				),
				'placeholder' => 'Option on purchasing your system screen',
				'helper' => '',
				'supplimental' => 'Option on purchasing your system screen',
				'default' => array('no')
			),
			array(
        		'uid' => 'sw_lease_rate',
        		'label' => 'Rate (Lease)',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. 1.99%',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_lease_term',
        		'label' => 'Term (Lease) ',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. 10 Years',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_lease_credit_score',
        		'label' => 'Credit Score (Lease)',
        		'section' => 'panels_utility_section',
        		'type' => 'text',
        		'placeholder' => 'i.e. 640+',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
				'uid' => 'sw_wizard_system_cost_rate_label_home',
				'label' => 'Rate or Escalator Label',
				'section' => 'panels_utility_section',
				'type' => 'text',
				'placeholder' => 'Escalator',
				'helper' => '',
				'supplimental' => '', 
				'disable' => true
			),
			array(
				'uid' => 'sw_wizard_purchase_promotial_text',
				'label' => 'Promotional Text',
				'section' => 'panels_utility_section',
				'type' => 'textarea',
				'placeholder' => '',
				'helper' => '',
				'supplimental' => '', 
				'disable' => true
			)
			
		);
		foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'panels_utility', $field['section'], $field );
            register_setting( 'panels_utility', $field['uid'] );
    	}
	}
	
	public function solwzd_setup_fields_panels_utility_business() {
		$fields = array(
			array(
        		'uid' => 'sw_enable_panel_utility_business',
        		'label' => 'Enabled?',
        		'section' => 'panels_utility_section_business',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'validate' => true,
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_panel_image_business',
        		'label' => 'Panel Image',
        		'section' => 'panels_utility_section_business',
        		'type' => 'image',
				'disable' => true,
        		'helper' => '',
        		'supplimental' => 'Panel image visible on panel screen',
        	),
			array(
        		'uid' => 'sw_panel_manufacturer_business',
        		'label' => 'Panel Manufacturer',
        		'section' => 'panels_utility_section_business',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_panel_watt_business',
        		'label' => 'Panel Watt',
				'disable' => true,
        		'section' => 'panels_utility_section_business',
        		'type' => 'number',
				'default' => '',
				'placeholder' => '',
        		'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_electricity_kwh_cost_business',
        		'label' => 'Average kWh Utility Cost',
        		'section' => 'panels_utility_section_business',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => 'i.e. 0.30',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_utility_increase_rate_business',
        		'label' => 'Electricity Increase Rate',
        		'section' => 'panels_utility_section_business',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => 'i.e. 5',
        		'helper' => '',
        		'supplimental' => 'Rate in percentage',
        	),
			array(
        		'uid' => 'sw_environmental_derate_factor_business',
        		'label' => 'Environmental Derate Factor',
        		'section' => 'panels_utility_section_business',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => 'i.e. 0.9',
        		'helper' => '',
        		'supplimental' => 'Environmental derate factor value',
        	),
			array(
				'uid' => 'sw_sunzone_values_business',
				'label' => 'Sun Zone Values',
				'section' => 'panels_utility_section_business',
				'type' => 'select',
				'disable' => true,
				'options' => array(
					'3.4' => '300 | 3.4 effective sunhours',
					'4' => '350 | 4.0 effective sunhours',
					'4.6' => '400 | 4.6 effective sunhours',
					'5.2' => '450 | 5.2 effective sunhours',
					'5.8' => '500 | 5.8 effective sunhours',
					'6.4' => '550 | 6.4 effective sunhours'
				),
				'placeholder' => 'Sun Zone Values',
				'helper' => '<br />Measure effective sun hours per day <br /><br /><strong>OR</strong>',
				'supplimental' => '',
				'default' => array('4.6')
			),
			array(
        		'uid' => 'sw_sunzone_hours_business',
        		'label' => '',
        		'section' => 'panels_utility_section_business',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => '',
				'disable' => true,
        		'helper' => '',
        		'supplimental' => 'Custom effective sun hours<br />Leave blank to use Sun Zone values for the calculation',
        	),
			array(
				'uid' => 'sw_panel_annual_derate_business',
				'label' => 'Solar Panel Annual Derate (aging)',
				'section' => 'panels_utility_section_business',
				'type' => 'select',
				'options' => array(
					'0.65' => '0.65% | Standard',
					'0.30' => '0.30% | Performance'
				),
				'placeholder' => 'Solar Panel Annual Derate (aging)',
				'helper' => '<br />Used to derate annual production for years 2 thru 25<br /><br /><strong>OR</strong>',
				'supplimental' => '',
				'default' => array('0.65'),
				'disable' => true
			),
			array(
				'uid' => 'sw_panel_annual_derate_custom_business',
				'label' => '',
				'section' => 'panels_utility_section_business',
				'type' => 'text',
				'placeholder' => '0.30',
				'helper' => '',
				'supplimental' => 'Custom annual derate value in %<br />Leave blank to use Solar Panel Annual Derate (aging) for the calculation',
				'disable' => true
			),
			array(
        		'uid' => 'sw_show_potential_savings_business',
        		'label' => 'Show Potential Savings',
        		'section' => 'panels_utility_section_business',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_potential_savings_years_business',
        		'label' => 'Potential Savings Over Years',
        		'section' => 'panels_utility_section_business',
        		'type' => 'select',
				'disable' => true,
				'options' => array(
					5 => '5 Years',
					10 => '10 Years',
					15 => '15 Years',
					20 => '20 Years',
					25 => '25 Years',
					30 => '30 Years',
					35 => '35 Years'
				),
				'default' => array(30),
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_enable_percentage_offset_business',
        		'label' => 'Enable monthly offset bill calculation?',
        		'section' => 'panels_utility_section_business',
				'helper' => 'Note: Disabling offset will only apply to commercial settings. If you disable, it will still be visible for EV path.',
				'supplimental' => '',
				'disable' => true,
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array()
        	),
			array(
        		'uid' => 'html'.rand(),
        		'label' => '<h3>Calculations</h3>',
        		'section' => 'panels_utility_section_business',
        		'type' => 'html',
				'code' => '<p>Solar Wizard can calculate pricing based on fixed prices for high to low results, or by giving ranges determined by the size of the system.</p>',
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
        		'uid' => 'html'.rand(),
        		'label' => '<strong>Definitions</strong>',
        		'section' => 'panels_utility_section_business',
        		'type' => 'html',
				'code' => '<ul style="list-style: disc;"><li>Cash Purchase: How much would YOU, the solar company, be willing to charge a customer who paid cash based on a simple cost per watt pricing ranges - i.e., $2.60 (lowest best price) or $4.25 (highest price).</li><li><strong>Financing/Lease: Two Options</strong><ul><li><strong>% Off</strong> - This is calculated by using the customers current utility bill and then, based on your experience selling solar in your marketplace, using the best financing available. What the customer can expect to save in the worst case (lowest) or best case (highest). Example: if our lowest savings value is 20% and highest is 50%, and if the customer\'s average monthly utility bill is $300, they can expect it to drop to between $180 and $240 per month with solar financing.</li><li><strong>$ Financing Price Per Watt</strong> – Like cash, this is for the price per watt including financing fees. The term of the loan must match the term loan label for the customer as we are going to calculate the monthly range of their new bill with financing over that term. Also, the monthly total cost is based on the gross system cost as most customers will decide if they want to pay down the loan with their incentive (if applicable).</li></ul></li></ul>',
				'helper' => '',
        		'supplimental' => ''
        	),
			array(
				'uid' => 'sw_min_req_sys_size_business',
				'label' => 'Minimum Required System Size',
				'section' => 'panels_utility_section_business',
				'type' => 'text',
				'placeholder' => 'i.e. 1.85',
				'helper' => '',
				'supplimental' => 'System Size in kW',
				'disable' => true
			),
			array(
				'uid' => 'sw_max_per_slider_offset_business',
				'label' => 'Offset Slider Max Value',
				'section' => 'panels_utility_section_business',
				'type' => 'number',
				'placeholder' => 'i.e. 300%',
				'helper' => '',
				'supplimental' => '',
				'disable' => true
			),
			array(
				'uid' => 'sw_billing_slider_range_min_max_business',
				'label' => 'Range of the Commercial Billing Slider',
				'section' => 'panels_utility_section_business',
				'helper' => '',
				'supplimental' => '',
				'type' => 'slider-range',
				'disable' => true
			),
			array(
				'uid' => 'sw_calculate_price_business',
				'label' => 'Calculate Price based on?',
				'section' => 'panels_utility_section_business',
				'type' => 'radio',
				'disable' => true,
				'options' => array(
					'fixed_price_per_watt' => 'Fixed price per watt',
					'system_size_range' => 'System size range'
				),
				'placeholder' => 'Solar wizard can calculate price based on fixed price per watt or system size range',
				'helper' => '',
				'supplimental' => 'Solar wizard can calculate price based on fixed price per watt or system size range',
				'default' => array('fixed_price_per_watt')
			),
			array(
        		'uid' => 'sw_system_size_fixed_pricing_matrix_business',
        		'label' => 'System Size Fixed Pricing',
        		'section' => 'panels_utility_section_business',
        		'type' => 'system_size_fixed_price_metrix',
				'disable' => true,
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_system_size_matrix_business',
        		'label' => 'Price by system size range',
        		'section' => 'panels_utility_section_business',
        		'type' => 'system_size_metrix',
				'disable' => true,
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'html'.rand(),
        		'label' => '<h2>Financing Visual Results</h2>',
        		'section' => 'panels_utility_section_business',
        		'type' => 'html',
				'code' => '',
				'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_show_purchase_cash_business',
        		'label' => 'Allow Purchase on Cash?',	
        		'section' => 'panels_utility_section_business',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '',
				'disable' => true,
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_show_purchase_finance_business',
        		'label' => 'Allow Purchase on Finance?',	
        		'section' => 'panels_utility_section_business',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '',
				'disable' => true,
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_loan_rate_business',
        		'label' => 'Rate (Loan)',
        		'section' => 'panels_utility_section_business',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => 'i.e. 1.99%',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_loan_term_business',
        		'label' => 'Term (Loan) Label',
        		'section' => 'panels_utility_section_business',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => 'i.e. 10 Years',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
				'uid' => 'sw_show_purchase_lease_business',
				'label' => 'Allow Purchase on Lease?',
				'section' => 'panels_utility_section_business',
				'type' => 'radio',
				'disable' => true,
				'options' => array(
					'yes' => 'Yes',
					'no' => 'No'
				),
				'placeholder' => 'Option on purchasing your system screen',
				'helper' => '',
				'supplimental' => 'Option on purchasing your system screen',
				'default' => array('')
			),
			array(
        		'uid' => 'sw_lease_rate_business',
        		'label' => 'Rate (Lease)',
        		'section' => 'panels_utility_section_business',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => 'i.e. 1.99%',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
        		'uid' => 'sw_lease_term_business',
        		'label' => 'Term (Lease) ',
        		'section' => 'panels_utility_section_business',
        		'type' => 'text',
				'disable' => true,
        		'placeholder' => 'i.e. 10 Years',
        		'helper' => '',
        		'supplimental' => '',
        	),
			array(
				'uid' => 'sw_wizard_system_cost_rate_label_business',
				'label' => 'Rate or Escalator Label',
				'section' => 'panels_utility_section_business',
				'type' => 'text',
				'placeholder' => 'Escalator',
				'helper' => '',
				'supplimental' => '', 
				'disable' => true
			),
			array(
				'uid' => 'sw_wizard_purchase_promotial_text_business',
				'label' => 'Promotional Text',
				'section' => 'panels_utility_section_business',
				'type' => 'textarea',
				'placeholder' => '',
				'helper' => '',
				'supplimental' => '', 
				'disable' => true
			)
		);
		foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'panels_utility_business', $field['section'], $field );
            if(isset($field['validate ']) && $field['validate'] == true){
				$validate = array( $this, 'solwzd_validateBusinessData' );
				register_setting( 'panels_utility_business', $field['uid'],  $validate);
			} else {
				register_setting( 'panels_utility_business', $field['uid']);
			}
    	}
	}
    public function solwzd_setup_fields_display_options() {
        $fields = array(
			//Display Option Fields
			array(
        		'uid' => 'sw_primary_color',
        		'label' => 'Primary Color',
        		'section' => 'display_options_section',
        		'type' => 'color',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => 'Primary color for wizard',
        	),
			array(
        		'uid' => 'sw_secondary_color',
        		'label' => 'Secondary Color',
        		'section' => 'display_options_section',
        		'type' => 'color',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => 'Secondary color for wizard',
        	),
			array(
        		'uid' => 'sw_headings_color',
        		'label' => 'Headings',
        		'section' => 'display_options_section',
        		'type' => 'color',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => 'Headings (Titles) color for the Wizard. Only available in Premium Version',
				'class' => 'disable-color'
        	),
			array(
        		'uid' => 'sw_text_color',
        		'label' => 'Text Color',
        		'section' => 'display_options_section',
        		'type' => 'color',
        		'placeholder' => '',
        		'helper' => '',
        		'supplimental' => 'Text color for the Wizard. Only available in Premium Version',
				'class' => 'disable-color'
        	),
			array(
        		'uid' => 'sw_show_logo',
        		'label' => 'Show Logo',
        		'section' => 'display_options_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'helper' => '',
        		'supplimental' => '',
                'default' => array(),
				'disable' => true
        	),
			array(
        		'uid' => 'sw_enable_map_image',
        		'label' => 'Show Satellite Image of House',
        		'section' => 'display_options_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'helper' => '',
        		'supplimental' => '',
                'default' => array(),
				'disable' => true
        	),
			array(
        		'uid' => 'sw_enable_military_or_veteran',
        		'label' => 'Enable military or veteran checkbox option?',
        		'section' => 'display_options_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'disable' => true,
				'helper' => '',
        		'supplimental' => '',
                'default' => array()
        	),
			array(
        		'uid' => 'sw_enable_nurse_or_state_worker',
        		'label' => 'Enable nurse or state worker checkbox option?',
        		'section' => 'display_options_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'disable' => true,
				'helper' => '',
        		'supplimental' => '',
                'default' => array()
        	),
			array(
        		'uid' => 'sw_enable_roi',
        		'label' => 'Enable ROI?',
        		'section' => 'display_options_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'helper' => '',
        		'supplimental' => '',
                'default' => array(),
				'disable' => true
        	),
			array(
        		'uid' => 'sw_enable_sw_quick_version',
        		'label' => 'Enable Solar Wizard Quick Version',
        		'section' => 'display_options_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'disable' => true,
				'helper' => '',
        		'supplimental' => '',
                'default' => array(),
				'validate' => true
        	),
			array(
        		'uid' => 'sw_enable_sw_full_version',
        		'label' => 'Enable Solar Wizard Full Version',
        		'section' => 'display_options_section',
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
				'disable' => true,
				'helper' => '',
        		'supplimental' => '',
                'default' => array(),
				'validate' => true
        	),
			array(
				'uid' => 'sw_penvironment_impact_time_frame',
				'label' => 'Environment Impact Time Frame',
				'section' => 'display_options_section',
				'type' => 'select',
				'disable' => true,
				'options' => array(
					5 => '5 Years',
					10 => '10 Years',
					15 => '15 Years',
					20 => '20 Years',
					25 => '25 Years',
					30 => '30 Years',
					35 => '35 Years',
					40 => '40 Years'
				),
				'default' => array(5),
				'helper' => '',
				'supplimental' => ''
			),
			array(
        		'uid' => 'sw_avail_environment_impact_options',
        		'label' => 'Environment Impact Options',
        		'section' => 'display_options_section',
        		'type' => 'checkbox',
				'disable' => true,
        		'options' => array(
        			'emission_factor' => 'Emission Factor (metric tons CO2)',
					'car_miles_driven' => 'Car miles driven',
					'passenger_vehicles_per_year' => 'Passenger vehicles per year',
					'barrels_of_oil_consumed' => 'Barrels of oil consumed',
					'tree_seedlings_grown' => 'Number of Trees Planted',
					'railcars_of_coal_burned' => 'Railcars of coal burned',
					'trucks_filled_with_gasoline' => 'Tanker trucks filled with gasoline'
        		),
				'helper' => '',
        		'supplimental' => 'Maximum three options are allowed to select.',
                'default' => array(),
				'validate' => true
        	),
			array(
        		'uid' => 'sw_custom_css',
        		'label' => 'Custom CSS',
        		'section' => 'display_options_section',
        		'type' => 'textarea',
				'placeholder' => '',
        		'helper' => '',
        		'supplimental' => ''
        	),
			array(
				'uid' => 'html'.rand(),
				'label' => 'SVGs',
				'section' => 'display_options_section',
				'type' => 'html',
				'helper' => '4 SVGs in sequence (Left to Right)',
				'supplimental' => '',
				'code' => '<h3>What else motivates you to go solar? Steps SVGs</h3>',
				'disable' => true
			),
			array(
        		'uid' => 'sw_motivate_svg_1',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_motivate_svg_2',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_motivate_svg_3',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_motivate_svg_4',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
				'uid' => 'html'.rand(),
				'label' => 'SVGs',
				'section' => 'display_options_section',
				'type' => 'html',
				'helper' => '4 SVGs in sequence (Left to Right)',
				'supplimental' => '',
				'code' => '<h3>What best describes you? Steps SVGs</h3>',
				'disable' => true
			),
			array(
        		'uid' => 'sw_describe_svg_1',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_describe_svg_2',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_describe_svg_3',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_describe_svg_4',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
				'uid' => 'html'.rand(),
				'label' => 'SVGs',
				'section' => 'display_options_section',
				'type' => 'html',
				'helper' => '4 SVGs in sequence (Left to Right)',
				'supplimental' => '',
				'code' => '<h3>Fill in the blank Steps SVGs</h3>'
			),
			array(
        		'uid' => 'sw_fill_in_svg_1',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_fill_in_svg_2',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_fill_in_svg_3',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	),
			array(
        		'uid' => 'sw_fill_in_svg_4',
        		'label' => '',
        		'section' => 'display_options_section',
				'helper' => '',
        		'supplimental' => '',
				'placeholder' => '',
        		'type' => 'textarea',
				'disable' => true
        	)
        );
    	foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'display_options', $field['section'], $field );
            register_setting( 'display_options', $field['uid'] );
    	}
    }

	public function solwzd_setup_fields_advanced() {
		$fields = array(
			array(
        		'uid' => 'sw_enable_record_query_string_email',
        		'label' => 'Enable record query string in Email?',
        		'section' => 'advanced_section',
        		'type' => 'checkbox',
				'supplimental' => '',
				'disable'	=> true,
				'helper' => '',
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array()
			),
			array(
        		'uid' => 'sw_email_query_String_variables',
        		'label' => 'Email Query String Variables',
        		'section' => 'advanced_section',
				'helper' => 'Insert the UTM parameters one by one (one per line). For help, please use this link <a href="https://ga-dev-tools.web.app/campaign-url-builder/" target="_blank">https://ga-dev-tools.web.app/campaign-url-builder/</a>',
        		'supplimental' => '',
				'placeholder' => '',
				'disable'	=> true,
        		'type' => 'textarea',
        	),
		);
		foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'advanced', $field['section'], $field );
            register_setting( 'advanced', $field['uid'] );
    	}
	}
	
	public function solwzd_setup_fields_webhook() {
		$fields = array(
			array(
        		'uid' => 'sw_enable_webhook',
        		'label' => 'Send data to URL?',
        		'section' => 'webhook_section',
				'disable'	=> true,
        		'type' => 'checkbox',
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '', 
				'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_submit_webhook_after_contact_info',
        		'label' => 'Send webhook after contact info?',
        		'section' => 'webhook_section',
        		'type' => 'checkbox',
				'disable'	=> true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '', 
				'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_submit_webhook_after_wizard_completion',
        		'label' => 'Send webhook at wizard completion?',
        		'section' => 'webhook_section',
        		'type' => 'checkbox',
				'disable'	=> true,
        		'options' => array(
        			'yes' => 'Yes'
        		),
                'default' => array(),
				'helper' => '', 
				'supplimental' => ''
        	),
			array(
        		'uid' => 'sw_webhook_url',
        		'label' => 'URL to submit data',
        		'section' => 'webhook_section',
				'disable'	=> true,
        		'type' => 'text',
        		'placeholder' => 'valid and working webhook URL',
        		'helper' => '',
				'size' => 100, 
        		'supplimental' => '',
				'default' => ''
        	)
		);
		foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'solwzd_field_callback' ), 'webhook', $field['section'], $field );
            register_setting( 'webhook', $field['uid'] );
    	}
	}
    public function solwzd_field_callback( $arguments ) {
        $value = get_option( $arguments['uid'] );
        if( ! $value ) {
            $value = isset($arguments['default']) ? $arguments['default'] : '';
        }
		
		$size = isset($arguments['size']) ? $arguments['size'] : false;
		if( ! $size ) {
            $size = 30;;
        }
        switch( $arguments['type'] ){
            case 'text':
            case 'password':
            case 'number':
				$readonly = '';
				if(isset($arguments['readonly'])){
					$readonly = 'readonly="readonly"';
				}
				$disable = '';
				if(isset($arguments['disable']) && $arguments['disable'] == true){
					$disable = 'disabled="disabled"';
				}
                printf( '<input name="%1$s" id="%1$s" type="%2$s" '.$readonly.' '.$disable.' placeholder="%3$s" value="%4$s" size="%5$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value, $size);
                break;
			case 'country_code':
				$readonly = '';
				if(isset($arguments['readonly'])){
					$readonly = 'readonly="readonly"';
				}
				$disable = '';
				if(isset($arguments['disable']) && $arguments['disable'] == true){
					$disable = 'disabled="disabled"';
				}
				printf( '<input name="%1$s" id="%1$s" type="%2$s" '.$readonly.' '.$disable.' placeholder="%3$s" value="%4$s" size="%5$s" class="solwzd_country_code" />', $arguments['uid'], "text", $arguments['placeholder'], $value, $size);
				break;
			case 'text_tagify':
				$readonly = '';
				if(isset($arguments['readonly'])){
					$readonly = 'readonly="readonly"';
				}
				$disable = '';
				if(isset($arguments['disable']) && $arguments['disable'] == true){
					$disable = 'disabled="disabled"';
				}
				$actual_text = array();
				if($value != ''){
					$value = json_decode($value, true);
				} else {
					$value = array();
				}
				foreach($value as $val){
					$actual_text[] = $val['value'];
				}
				printf( '<input name="%1$s" id="%1$s" type="%2$s" '.$readonly.' '.$disable.' placeholder="%3$s" value="%4$s" size="%5$s" />', $arguments['uid'], "text", $arguments['placeholder'], implode(', ', $actual_text), $size);
				break;
            case 'textarea':
				$disable = '';
				if(isset($arguments['disable']) && $arguments['disable'] == true){
					$disable = 'disabled="disabled"';
				}
                printf( '<textarea name="%1$s" id="%1$s" '.$disable.' placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
                break;
			case 'editor':
				wp_editor( $value , $arguments['uid'], array( 'textarea_name' => $arguments['uid'], 'media_buttons' => false) );
				break;
			case 'language':
				$languages = get_available_languages();
				wp_dropdown_languages(array('name' => $arguments['uid'], 'id' => $arguments['uid'], 'selected' => $value, 'languages' => $languages, 'show_available_translations' => false));
				break;
            case 'select':
            case 'multiselect':
				if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
					$disable = '';
					if(isset($arguments['disable']) && $arguments['disable'] == true){
						$disable = 'disabled="disabled"';
					}
                    $attributes = '';
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ){
                        $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value[ array_search( $key, $value, true ) ], $key, false ), $label );
                    }
                    if( $arguments['type'] === 'multiselect' ){
                        $attributes = ' multiple="multiple" ';
                    }
                    printf( '<select name="%1$s[]" '.$disable.' id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup );
                }
                break;
			case 'html':
				if($code = $arguments['code']){
					echo $code;	
				}
				break;
            case 'radio':
            case 'checkbox':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $options_markup = '';
                    $iterator = 0;
					$disable = '';
					if(isset($arguments['disable']) && $arguments['disable'] == true){
						$disable = 'disabled="disabled"';
					}
                    foreach( $arguments['options'] as $key => $label ){
						if(count($value) == 0){
							$value = array(0 => '');
						}
                        $iterator++;
                        $options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s"  '.$disable.' name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked( $value[ array_search( $key, $value, true ) ], $key, false ), $label, $iterator );
                    }
                    printf( '<fieldset>%s</fieldset>', $options_markup );
                }
                break;
			case 'image':
				
					
					if(isset($arguments['readonly']) && $arguments['readonly'] == true){
						printf( '
							<img src="' . $value . '" style="max-width:400px;" />
							<input type="hidden" name="%1$s" id="%1$s" value="%2$s"></div>', $arguments['uid'], $value );
							
					} else 	if(isset($arguments['disable']) && $arguments['disable'] == true){
						echo 'Avaialble in Pro version.';
					} else {
						if($value != ''){
							printf( '
							<div class="file-upl-div">
							<div class="clear"></div>
							<a href="#" class="file-upl img-upld" data="%1$s"><img src="' . $value . '" style="max-width:400px;" /></a>
							<div class="clear"></div>
							<a href="#" class="file-rmv img-upld button button-primary">Remove image</a>
							<input type="hidden" name="%1$s" id="%1$s" value="%2$s"></div>', $arguments['uid'], $value );
							
						} else {
						printf( '
							<div class="file-upl-div">
							<div class="clear"></div>
							<a href="#" class="file-upl img-upld button button-primary" data="%1$s">Upload image</a>
							<div class="clear"></div>
							<a href="#" class="file-rmv img-upld button button-primary" data="%1$s" style="display:none">Remove image</a>
							<input type="hidden" name="%1$s" id="%1$s" value="%2$s"></div>', $arguments['uid'], $value );
						}				
					}
				break;
				
			case 'color':
                printf( '<input class="color-field" name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
				
			case 'repeater_hidden':
				break;
			
				case 'system_size_fixed_price_metrix':
					$system_size_fixed_price_metrix = get_option( $arguments['uid']);
					if($system_size_fixed_price_metrix == ''){
						$system_size_fixed_price_metrix = array(
							'price_per_watt_low' => '',
							'price_per_watt_high' => '',
							'financial_low' => '',
							'financial_high' => '',
							'lease_low' => '',
							'lease_high' => '',
						);
					}
					$disable = '';
					if(isset($arguments['disable']) && $arguments['disable'] == true){
						$disable = 'disabled="disabled"';
					}
					echo	
						'<table id="incentive_table" cellspacing="0" cellpadding="0">
					
							<tr>
								<th colspan="2">Cash Pricing</th>
								<th colspan="2">Financing <select disabled name="" class="financing_type_option">
								<option selected="selected" value="Percentage">Percentage</option>
								<option disabled value="Fixed">Fixed</option>
							</select></th>
								<th colspan="2">Lease <select disabled name="" class="financing_type_option">
								<option selected="selected" value="Percentage">Percentage</option>
								<option disabled value="Fixed">Fixed</option>
							</select></th>
							</tr>
							<tr>
								<th>Price per watt low</th>
								<th>Price per watt high</th>
								<th>lowest %</th>
								<th>highest %</th>
								<th>lowest %</th>
								<th>highest %</th>
							</tr>
							<tr>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_fixed_price_metrix['price_per_watt_low'].'" name="'.$arguments['uid'].'[price_per_watt_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_fixed_price_metrix['price_per_watt_high'].'" name="'.$arguments['uid'].'[price_per_watt_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_fixed_price_metrix['financial_low'].'" name="'.$arguments['uid'].'[financial_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_fixed_price_metrix['financial_high'].'" name="'.$arguments['uid'].'[financial_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_fixed_price_metrix['lease_low'].'" name="'.$arguments['uid'].'[lease_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_fixed_price_metrix['lease_high'].'" name="'.$arguments['uid'].'[lease_high]" /></td>
							</tr>';				
					echo '</table>';	
					break;
				case 'slider-range':
					$slider_range_mertrix = get_option( $arguments['uid']);
					if($slider_range_mertrix == ''){
						$slider_range_mertrix = array(
							'min' => '',
							'max' => ''
						);
					}
					$disable = '';
					if(isset($arguments['disable']) && $arguments['disable'] == true){
						$disable = 'disabled="disabled"';
					}
					echo	
						'<table id="incentive_table" cellspacing="0" cellpadding="0">
					
							<tr>
								<th>Min.</th>
								<th>Max</th>
							</tr>
							<tr>
								<td><input type="number" step="1" '.$disable.' value="'.$slider_range_mertrix['min'].'" name="'.$arguments['uid'].'[min]" /></td>
								<td><input type="number" step="1" '.$disable.' value="'.$slider_range_mertrix['max'].'" name="'.$arguments['uid'].'[max]" /></td>
							</tr>';				
					echo '</table>';	
					break;
				case 'system_size_incentive_metrix':
					echo	
						'<table class="form-table" id="system_size_incentive_table" cellspacing="0" cellpadding="0">
					
							<tr>';
							echo '<th>Incentive</th>
								<th>REC Credit</th>
								<th>Years Of Credit</th>
								<th>REC Per KW</th>
								<th>Applied To</th>
								<th><a class="add_sys_size_inc_row button button-primary" disabled>Add Row</a></th>
							</tr>';			
					echo '</table>';	
					break;
				case 'system_size_metrix':
					$system_size_metrix = get_option( $arguments['uid']);
					if($system_size_metrix == ''){
						$system_size_metrix = array(
							'small' => array(
								'min_system_size' => '',
								'max_system_size' => '',
								'price_per_watt_low' => '',
								'price_per_watt_high' => '',
								'financial_low' => '',
								'financial_high' => '',
								'lease_low' => '',
								'lease_high' => '',
							),
							'medium' => array(
								'min_system_size' => '',
								'max_system_size' => '',
								'price_per_watt_low' => '',
								'price_per_watt_high' => '',
								'financial_low' => '',
								'financial_high' => '',
								'lease_low' => '',
								'lease_high' => '',
							),
							'large' => array(
								'min_system_size' => '',
								'max_system_size' => '',
								'price_per_watt_low' => '',
								'price_per_watt_high' => '',
								'financial_low' => '',
								'financial_high' => '',
								'lease_low' => '',
								'lease_high' => '',
							),
							'super_large' => array(
								'min_system_size' => '',
								'max_system_size' => '',
								'price_per_watt_low' => '',
								'price_per_watt_high' => '',
								'financial_low' => '',
								'financial_high' => '',
								'lease_low' => '',
								'lease_high' => '',
							)
						);
					}
					$disable = '';
					if(isset($arguments['disable']) && $arguments['disable'] == true){
						$disable = 'disabled="disabled"';
					}
					echo	
						'<table id="incentive_table" cellspacing="0" cellpadding="0">
					
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th colspan="2">Cash Pricing</th>
								<th colspan="2">Financing <select disabled name="" class="financing_type_option">
								<option disabled selected="selected" value="Percentage">Percentage</option>
								<option disabled value="Fixed">Fixed</option>
							</select></th>
								<th colspan="2">Lease <select disabled name="" class="financing_type_option">
								<option selected="selected" value="Percentage">Percentage</option>
								<option disabled value="Fixed">Fixed</option>
							</select></th>
							</tr>
							<tr>
								<th>System Size</th>
								<th>min KW</th>
								<th>max KW</th>
								<th>Price per watt low</th>
								<th>Price per watt high</th>
								<th>lowest %</th>
								<th>highest %</th>
								<th>lowest %</th>
								<th>highest %</th>
							</tr>
							<tr>
								<td>Small</td>
								<td><input type="number" step=".01" '.$disable.' value="0" readonly name="'.$arguments['uid'].'[small][min_system_size]" /></td>
								<td><input type="number" step=".01" '.$disable.'  value="'.$system_size_metrix['small']['max_system_size'].'" name="'.$arguments['uid'].'[small][max_system_size]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['small']['price_per_watt_low'].'" name="'.$arguments['uid'].'[small][price_per_watt_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['small']['price_per_watt_high'].'" name="'.$arguments['uid'].'[small][price_per_watt_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['small']['financial_low'].'" name="'.$arguments['uid'].'[small][financial_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['small']['financial_high'].'" name="'.$arguments['uid'].'[small][financial_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['small']['lease_low'].'" name="'.$arguments['uid'].'[small][lease_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['small']['lease_high'].'" name="'.$arguments['uid'].'[small][lease_high]" /></td>
							</tr>
							<tr>
								<td>Medium</td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['medium']['min_system_size'].'" name="'.$arguments['uid'].'[medium][min_system_size]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['medium']['max_system_size'].'" name="'.$arguments['uid'].'[medium][max_system_size]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['medium']['price_per_watt_low'].'" name="'.$arguments['uid'].'[medium][price_per_watt_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['medium']['price_per_watt_high'].'" name="'.$arguments['uid'].'[medium][price_per_watt_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['medium']['financial_low'].'" name="'.$arguments['uid'].'[medium][financial_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['medium']['financial_high'].'" name="'.$arguments['uid'].'[medium][financial_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['medium']['lease_low'].'" name="'.$arguments['uid'].'[medium][lease_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['medium']['lease_high'].'" name="'.$arguments['uid'].'[medium][lease_high]" /></td>
							</tr>
							<tr>
								<td>Large</td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['large']['min_system_size'].'" name="'.$arguments['uid'].'[large][min_system_size]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['large']['max_system_size'].'" name="'.$arguments['uid'].'[large][max_system_size]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['large']['price_per_watt_low'].'" name="'.$arguments['uid'].'[large][price_per_watt_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['large']['price_per_watt_high'].'" name="'.$arguments['uid'].'[large][price_per_watt_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['large']['financial_low'].'" name="'.$arguments['uid'].'[large][financial_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['large']['financial_high'].'" name="'.$arguments['uid'].'[large][financial_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['large']['lease_low'].'" name="'.$arguments['uid'].'[large][lease_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['large']['lease_high'].'" name="'.$arguments['uid'].'[large][lease_high]" /></td>
							</tr>
							<tr>
								<td>Super Large</td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['super_large']['min_system_size'].'" name="'.$arguments['uid'].'[super_large][min_system_size]" /></td>
								<td><input type="text" step=".01" '.$disable.' value="∞" readonly name="'.$arguments['uid'].'[super_large][max_system_size]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['super_large']['price_per_watt_low'].'" name="'.$arguments['uid'].'[super_large][price_per_watt_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['super_large']['price_per_watt_high'].'" name="'.$arguments['uid'].'[super_large][price_per_watt_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['super_large']['financial_low'].'" name="'.$arguments['uid'].'[super_large][financial_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['super_large']['financial_high'].'" name="'.$arguments['uid'].'[super_large][financial_high]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['super_large']['lease_low'].'" name="'.$arguments['uid'].'[super_large][lease_low]" /></td>
								<td><input type="number" step=".01" '.$disable.' value="'.$system_size_metrix['super_large']['lease_high'].'" name="'.$arguments['uid'].'[super_large][lease_high]" /></td>
							</tr>';				
					echo '</table>';	
					break;
					
			case 'battery_matrix':
				$sw_battery_matrix = get_option( $arguments['uid']);
				if($sw_battery_matrix == ''){
					$sw_battery_matrix = array(
						'small' => array(
							'min' => '',
							'max' => '',
							'noofbatteries' => '',
							'desc' => '',
							'price' => '',
						),
						'medium' => array(
							'min' => '',
							'max' => '',
							'noofbatteries' => '',
							'desc' => '',
							'price' => '',
						),
						'large' => array(
							'min' => '',
							'max' => '',
							'noofbatteries' => '',
							'desc' => '',
							'price' => '',
						),
						'super_large' => array(
							'min' => '',
							'max' => '',
							'noofbatteries' => '',
							'desc' => '',
							'price' => '',
						)
					);
				}
				$disable = '';
				if(isset($arguments['disable']) && $arguments['disable'] == true){
					$disable = 'disabled="disabled"';
				}
				//print_r($sw_battery_matrix);
				echo	
					'<table id="incentive_table" cellspacing="0" cellpadding="0">
				
						<tr>
							<th>Battery</th>
							<th>Min. kW</th>
							<th>Max. kW</th>
							<th>Number of Batteries</th>
							<th>Detail</th>
							<th>Price</th>
						</tr>
						<tr>
							<td>Small</td>
							<td><input type="text" '.$disable.' value="0" name="sw_battery_matrix[small][min]" readonly /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['small']['max'].'" name="sw_battery_matrix[small][max]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['small']['noofbatteries'].'" name="sw_battery_matrix[small][noofbatteries]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['small']['desc'].'" name="sw_battery_matrix[small][desc]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['small']['price'].'" name="sw_battery_matrix[small][price]" /></td>
						</tr>
						<tr>
							<td>Medium</td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['medium']['min'].'" name="sw_battery_matrix[medium][min]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['medium']['max'].'" name="sw_battery_matrix[medium][max]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['medium']['noofbatteries'].'" name="sw_battery_matrix[medium][noofbatteries]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['medium']['desc'].'" name="sw_battery_matrix[medium][desc]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['medium']['price'].'" name="sw_battery_matrix[medium][price]" /></td>
						</tr>
						<tr>
							<td>Large</td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['large']['min'].'" name="sw_battery_matrix[large][min]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['large']['max'].'" name="sw_battery_matrix[large][max]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['large']['noofbatteries'].'" name="sw_battery_matrix[large][noofbatteries]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['large']['desc'].'" name="sw_battery_matrix[large][desc]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['large']['price'].'" name="sw_battery_matrix[large][price]" /></td>
						</tr>
						<tr>
							<td>Super Large</td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['super_large']['min'].'" name="sw_battery_matrix[super_large][min]" /></td>
							<td><input type="text" '.$disable.' value="∞" name="sw_battery_matrix[super_large][max]" readonly /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['super_large']['noofbatteries'].'" name="sw_battery_matrix[super_large][noofbatteries]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['super_large']['desc'].'" name="sw_battery_matrix[super_large][desc]" /></td>
							<td><input type="text" '.$disable.' value="'.$sw_battery_matrix['super_large']['price'].'" name="sw_battery_matrix[super_large][price]" /></td>
						</tr>';				
				echo '</table>';	
					break;
			case 'text_lang':
				$disable = '';
				if(isset($arguments['disable']) && $arguments['disable'] == true){
					$disable = 'disabled="disabled"';
				}
				$locale_language = get_option('sw_language');
				$default_languge = 'en';
				$default_value = get_option($arguments['uid'].'_en', '');
				$translation_language = '';
				if($locale_language != ''){
					$translation_language = $locale_language;
					$translation_value = get_option($arguments['uid'].'_'.$translation_language, '');
				}
				//if($locale_language)
				echo	
					'<table id="incentive_table" cellspacing="0" cellpadding="0" width="100%">
				
						<tr>
							<td width="20px">('.$default_languge.')</td>
							<td><input type="text" value="'.$default_value.'" '.$disable.' name="'.$arguments['uid'].'_en" placeholder="'.$arguments['placeholder'].'" /></td>';
							if($translation_language != ''){
								echo '<td></td><td width="20px">('.$translation_language.')</td><td><input type="text" '.$disable.' value="'.$translation_value.'" name="'.$arguments['uid'].'_'.$translation_language.'" /></td>';
							}
					echo '</tr>
				</table>';
				//printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" size="%5$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value, $size);
				break;
			case 'battery_multiplier_metrix':
				$battery_multiplier_metrix = get_option( $arguments['uid']);
				if($battery_multiplier_metrix == ''){
					$battery_multiplier_metrix = array(
						'standard' => '',
						'sport' => '',
						'truck' => ''
					);
				}
				$disable = '';
				if(isset($arguments['disable']) && $arguments['disable'] == true){
					$disable = 'disabled="disabled"';
				}
				echo	
					'<table id="incentive_table" cellspacing="0" cellpadding="0">
				
						<tr>
							<th width="">If user deosn\'t know the EV they pick</th>
							<th>Multiplier</th>
						</tr>
						<tr>
							<td>Standard</td>
							<td><input type="number" step=".01" '.$disable.' value="'.$battery_multiplier_metrix['standard'].'" name="'.$arguments['uid'].'[standard]" /></td>
						</tr>
						<tr>
							<td>Sport</td>
							<td><input type="number" step=".01" '.$disable.' value="'.$battery_multiplier_metrix['sport'].'" name="'.$arguments['uid'].'[sport]" /></td>
						</tr>
						<tr>
							<td>Truck</td>
							<td><input type="number" step=".01" '.$disable.' value="'.$battery_multiplier_metrix['truck'].'" name="'.$arguments['uid'].'[truck]" /></td>
						</tr>';				
				echo '</table>';	
				break;
				
			case 'weekhours':
				$weekhours = get_option( $arguments['uid']);
				if($weekhours == ''){
					$weekhours = array(
						0 => array(
							0 => '',
							1 => array(
								'H' => '',
								'M' => ''
							),
							2 => array(
								'H' => '',
								'M' => ''
							)
						),
						1 => array(
							0 => '',
							1 => array(
								'H' => '',
								'M' => ''
							),
							2 => array(
								'H' => '',
								'M' => ''
							)
						),
						2 => array(
							0 => '',
							1 => array(
								'H' => '',
								'M' => ''
							),
							2 => array(
								'H' => '',
								'M' => ''
							)
						),
						3 => array(
							0 => '',
							1 => array(
								'H' => '',
								'M' => ''
							),
							2 => array(
								'H' => '',
								'M' => ''
							)
						),
						4 => array(
							0 => '',
							1 => array(
								'H' => '',
								'M' => ''
							),
							2 => array(
								'H' => '',
								'M' => ''
							)
						),
						5 => array(
							0 => '',
							1 => array(
								'H' => '',
								'M' => ''
							),
							2 => array(
								'H' => '',
								'M' => ''
							)
						),
						6 => array(
							0 => '',
							1 => array(
								'H' => '',
								'M' => ''
							),
							2 => array(
								'H' => '',
								'M' => ''
							)
						)
					);
				}
				$disable = '';
				if(isset($arguments['disable']) && $arguments['disable'] == true){
					$disable = 'disabled="disabled"';
				}
				
				echo 'Current Time: '.current_time('Y-m-d H:i:s');
				echo '<br />Please adjust time zone if difference in time.';
				echo '<table id="" cellspacing="0" cellpadding="0">
					<tr>
						<td><strong>Day</strong></td>
						<td><strong>Working?</strong></td>
						<td><strong>From</strong></td>
						<td><strong>To</strong></td>
					</tr>';
				$weekdays = array('MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN');
				for($i=0; $i<count($weekdays) ; $i++){
							echo '<tr>
								<td>
									'.$weekdays[$i].'
								</td>
								<td>
									<select name="'.$arguments['uid'].'['.$i.'][0]" '.$disable.'>';
									echo '<option value="Working" ';
									echo $weekhours[$i][0] == 'Working' ? 'selected="selected"' : '';
									echo '>Working</option>';
									echo '<option value="Off" ';
									echo $weekhours[$i][0] == 'Off' ? 'selected="selected"' : '';
									echo '>Off</option>';
								echo '</select>
								</td>
								<td>
									<select name="'.$arguments['uid'].'['.$i.'][1][H]" '.$disable.'>';
									for($k=0; $k<=23; $k++){
										echo '<option value="'.$k.'" ';
										echo $weekhours[$i][1]['H'] == $k ? 'selected="selected"' : '';
										echo '>'.$k.'</option>';
									}
								echo '</select>
									<select name="'.$arguments['uid'].'['.$i.'][1][M]" '.$disable.'>';
										for($j=0; $j<60; $j = $j+15){
											echo '<option value="'.sprintf("%02d", $j).'" ';
											echo $weekhours[$i][1]['M'] == sprintf("%02d", $j) ? 'selected="selected"' : '';
											echo '>'.sprintf("%02d", $j).'</option>';
										}
									echo '</select>
								</td>
								<td>
									<select name="'.$arguments['uid'].'['.$i.'][2][H]" '.$disable.'>';
									for($k=0; $k<=23; $k++){
										echo '<option value="'.$k.'" ';
										echo $weekhours[$i][2]['H'] == $k ? 'selected="selected"' : '';
										echo '>'.$k.'</option>';
									}
								echo '</select>
								<select name="'.$arguments['uid'].'['.$i.'][2][M]" '.$disable.'>';
										for($j=0; $j<60; $j = $j+15){
											echo '<option value="'.sprintf("%02d", $j).'" ';
											echo $weekhours[$i][2]['M'] == sprintf("%02d", $j) ? 'selected="selected"' : '';
											echo '>'.sprintf("%02d", $j).'</option>';
										}
									echo '</select>
								</td>
								</tr>';
				}
				echo '</table>';
				break;
			case 'repeater':
				$inc_name = get_option( $arguments['uid']);
				$inc_value_type = get_option( 'sw_incentives_repeater_value_type');
				$inc_value = get_option( 'sw_incentives_repeater_value');
				$inc_applied = get_option( 'sw_incentives_repeater_applied');
				echo	
					'<table id="incentive_table" cellspacing="0" cellpadding="0">
				
						<tr>
							<th>Incentive</th>
							<th>Fixed or %</th>
							<th>Value</th>
							<th>Applied to</th>
							<th><a href="#" class="add_row button button-primary">Add Row</a></th>
						</tr>';
					
						for($i=0; $i<count($inc_name) && is_array($inc_name); $i++){
							echo '<tr>
								<td>
									<input type="text" name="'.$arguments['uid'].'[]" value="'.$inc_name[$i].'" />
								</td>
								<td>
									<select name="sw_incentives_repeater_value_type[]">
										<option value="Fixed"';
										echo (isset($inc_value_type[$i]) &&  $inc_value_type[$i] == 'Fixed') ? ' selected' : '';
										echo '>Fixed</option>
										<option value="Percentage"';
										echo (isset($inc_value_type[$i]) &&  $inc_value_type[$i] == 'Percentage') ? ' selected' : '';
										echo '>Percentage</option>
									</select>
								</td>
								<td>
									<input name="sw_incentives_repeater_value[]" type="number" value="'.$inc_value[$i].'" />
								</td>
								<td>
									<select name="sw_incentives_repeater_applied['.$i.'][]" multiple>
										<option value="Residential"';
										echo (isset($inc_applied[$i]) && in_array('Residential', $inc_applied[$i])) ? ' selected' : '';
										echo '>Residential</option>
										<option disabled="disabled" value="Commercial"';
										echo (isset($inc_applied[$i]) && in_array('Commercial', $inc_applied[$i])) ? ' selected' : '';
										echo '>Commercial</option>
									</select>
								</td>
								<td><a href="#" class="delete button button-primary">Delete</a></td>
							</tr>';
						}				
				echo '</table>';
				//printf('');
				break;
				
        }
        if( $helper = $arguments['helper'] ){
            printf( '<span class="helper"> %s</span>', $helper );
        }
        if( $supplimental = $arguments['supplimental'] ){
            printf( '<p class="description">%s</p>', $supplimental );
        }
    }
}
new SOLWZD_Construct_Admin_Settings();
?>