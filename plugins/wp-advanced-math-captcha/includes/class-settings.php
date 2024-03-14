<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

new Math_Captcha_Settings();

class Math_Captcha_Settings {

	public $mathematical_operations;
	public $groups;
	public $forms;

	public function __construct() {
		// actions
		add_action( 'init', array( $this, 'load_defaults' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu_options' ) );
	}

	/**
	 * Load defaults.
	 */
	public function load_defaults() {
		if ( ! is_admin() )
			return;

		$this->forms = array(
			'login_form'			 => __( 'login form', 'math-captcha' ),
			'registration_form'		 => __( 'registration form', 'math-captcha' ),
			'reset_password_form'	 => __( 'reset password form', 'math-captcha' ),
			'comment_form'			 => __( 'comment form', 'math-captcha' ),
			'bbpress'				 => __( 'bbpress', 'math-captcha' ),
			'contact_form_7'		 => __( 'contact form 7', 'math-captcha' )
		);

		$this->mathematical_operations = array(
			'addition'		 => __( 'addition (+)', 'math-captcha' ),
			'subtraction'	 => __( 'subtraction (-)', 'math-captcha' ),
			'multiplication' => __( 'multiplication (&#215;)', 'math-captcha' ),
			'division'		 => __( 'division (&#247;)', 'math-captcha' )
		);

		$this->groups = array(
			'numbers'	 => __( 'numbers', 'math-captcha' ),
			'words'		 => __( 'words', 'math-captcha' )
		);
	}

	/**
	 * Add options menu.
	 */
	public function admin_menu_options() {
		add_options_page(
			__( 'Math Captcha', 'math-captcha' ), __( 'Math Captcha', 'math-captcha' ), 'manage_options', 'math-captcha', array( $this, 'options_page' )
		);
	}

	/**
	 * Render options page.
	 * 
	 * @return mixed
	 */
	public function options_page() 
    {
        // Get last 30 days
        $logs = WP_CONTENT_DIR.'/uploads/logs/mathcaptcha';
        $html_chart = '';
        $data = array();
        $file_counter = 0;
        if (file_exists($logs))
        {
            for ($i = 30; $i >= 0; $i--)
            {
                $k = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $i,   date("Y")));
                $data[$k] = 0;
            }
            
            $file_counter = 0;

            foreach (glob($logs."/*.log") as $filename) 
            {
                $date = trim(substr(basename($filename), 0, -4));
                if (!isset($data[$date])) unlink($filename);
                else {
                    $data[$date] = filesize($filename);
                    $file_counter++;
                }
            }   
            
            $html_chart = '<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
            <div id="container" style="width: 75%;">
            <canvas id="mathcanvas"></canvas>
            </div>
            
            <script>
		var barChartData = {
			labels: ['."'".implode("','", array_keys($data))."'".'],
			datasets: [{
				label: \'Blocked by captcha\',
				backgroundColor: \'rgba(202, 34, 71, 0.8)\',
				borderColor: \'rgba(202, 34, 71, 0.8)\',
				borderWidth: 1,
				data: ['.implode(",", $data).']
			}]

		};

		window.onload = function() {
			var ctx = document.getElementById(\'mathcanvas\').getContext(\'2d\');
			window.myBar = new Chart(ctx, {
				type: \'bar\',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: \'top\',
					},
					title: {
						display: true,
						text: \'Statistic of blocked sessions (last 30 days)\'
					}
				}
			});

		};
            </script>
            ';         
        }
        
        
        if ($file_counter == 0) $html_chart = '<div class="update-nag notice notice-warning inline">Statistic data is not available yet. Captcha plugin needs to collect more data.</div>';

         
        
		echo '
		<div class="wrap">
			<h2>' . __( 'Math Captcha', 'math-captcha' ) . '</h2>
			<a href="https://pentryforms.com/en?gid=WPPLG" target="_blank"><img src="'.plugins_url('images/', dirname(__FILE__)).'banner-pentryforms.png'.'"/></a><br>
			'.$html_chart.'
            <div class="math-captcha-settings">
				<form action="options.php" method="post">';

		wp_nonce_field( 'update-options' );
		settings_fields( 'math_captcha_options' );
		do_settings_sections( 'math_captcha_options' );

		echo '
					<p class="submit">';

		submit_button( '', 'primary', 'save_mc_general', false );

		echo ' ';

		submit_button( __( 'Reset to defaults', 'math-captcha' ), 'secondary reset_mc_settings', 'reset_mc_general', false );

		echo '
					</p>
				</form>
			</div>
			<div class="clear"></div>
		</div>';
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		// general settings
		register_setting( 'math_captcha_options', 'math_captcha_options', array( $this, 'validate_settings' ) );
		add_settings_section( 'math_captcha_settings', __( 'Math Captcha settings', 'math-captcha' ), '', 'math_captcha_options' );
		add_settings_field( 'mc_general_enable_captcha_for', __( 'Enable Math Captcha for', 'math-captcha' ), array( $this, 'mc_general_enable_captcha_for' ), 'math_captcha_options', 'math_captcha_settings' );
		add_settings_field( 'mc_general_hide_for_logged_users', __( 'Hide for logged in users', 'math-captcha' ), array( $this, 'mc_general_hide_for_logged_users' ), 'math_captcha_options', 'math_captcha_settings' );
		add_settings_field( 'mc_general_mathematical_operations', __( 'Mathematical operations', 'math-captcha' ), array( $this, 'mc_general_mathematical_operations' ), 'math_captcha_options', 'math_captcha_settings' );
		add_settings_field( 'mc_general_groups', __( 'Display captcha as', 'math-captcha' ), array( $this, 'mc_general_groups' ), 'math_captcha_options', 'math_captcha_settings' );
		add_settings_field( 'mc_general_title', __( 'Captcha field title', 'math-captcha' ), array( $this, 'mc_general_title' ), 'math_captcha_options', 'math_captcha_settings' );
		add_settings_field( 'mc_general_time', __( 'Captcha time', 'math-captcha' ), array( $this, 'mc_general_time' ), 'math_captcha_options', 'math_captcha_settings' );
		add_settings_field( 'mc_general_block_direct_comments', __( 'Block Direct Comments', 'math-captcha' ), array( $this, 'mc_general_block_direct_comments' ), 'math_captcha_options', 'math_captcha_settings' );
		add_settings_field( 'mc_general_enable_ip_rules', __( 'IP rules', 'math-captcha' ), array( $this, 'mc_general_ip_rules' ), 'math_captcha_options', 'math_captcha_settings' );
		add_settings_field( 'mc_general_enable_geo', __( 'Enable GEO captcha rules', 'math-captcha' ), array( $this, 'mc_general_geo_captcha_rules' ), 'math_captcha_options', 'math_captcha_settings' );
		add_settings_field( 'mc_general_deactivation_delete', __( 'Deactivation', 'math-captcha' ), array( $this, 'mc_general_deactivation_delete' ), 'math_captcha_options', 'math_captcha_settings' );
	}

	public function mc_general_enable_captcha_for() {
		echo '
		<div id="mc_general_enable_captcha_for">
			<fieldset>';

		foreach ( $this->forms as $val => $trans ) {
			echo '
				<input id="mc-general-enable-captcha-for-' . $val . '" type="checkbox" name="math_captcha_options[enable_for][]" value="' . $val . '" ' . checked( true, Math_Captcha()->options['general']['enable_for'][$val], false ) . ' ' . disabled( (($val === 'contact_form_7' && ! class_exists( 'WPCF7_ContactForm' )) || ($val === 'bbpress' && ! class_exists( 'bbPress' )) ), true, false ) . '/><label for="mc-general-enable-captcha-for-' . $val . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<br/>
				<span class="description">' . __( 'Select where you\'d like to use Math Captcha.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_hide_for_logged_users() {
		echo '
		<div id="mc_general_hide_for_logged_users">
			<fieldset>
				<input id="mc-general-hide-for-logged" type="checkbox" name="math_captcha_options[hide_for_logged_users]" ' . checked( true, Math_Captcha()->options['general']['hide_for_logged_users'], false ) . '/><label for="mc-general-hide-for-logged">' . __( 'Enable to hide captcha for logged in users.', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'Would you like to hide captcha for logged in users?', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_mathematical_operations() {
		echo '
		<div id="mc_general_mathematical_operations">
			<fieldset>';

		foreach ( $this->mathematical_operations as $val => $trans ) {
			echo '
				<input id="mc-general-mathematical-operations-' . $val . '" type="checkbox" name="math_captcha_options[mathematical_operations][]" value="' . $val . '" ' . checked( true, Math_Captcha()->options['general']['mathematical_operations'][$val], false ) . '/><label for="mc-general-mathematical-operations-' . $val . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<br/>
				<span class="description">' . __( 'Select which mathematical operations to use in your captcha.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_groups() {
		echo '
		<div id="mc_general_groups">
			<fieldset>';

		foreach ( $this->groups as $val => $trans ) {
			echo '
				<input id="mc-general-groups-' . $val . '" type="checkbox" name="math_captcha_options[groups][]" value="' . $val . '" ' . checked( true, Math_Captcha()->options['general']['groups'][$val], false ) . '/><label for="mc-general-groups-' . $val . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<br/>
				<span class="description">' . __( 'Select how you\'d like to display you captcha.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_title() {
		echo '
		<div id="mc_general_title">
			<fieldset>
				<input type="text" name="math_captcha_options[title]" value="' . Math_Captcha()->options['general']['title'] . '"/>
				<br/>
				<span class="description">' . __( 'How to entitle field with captcha?', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_time() {
		echo '
		<div id="mc_general_time">
			<fieldset>
				<input type="text" name="math_captcha_options[time]" value="' . Math_Captcha()->options['general']['time'] . '"/>
				<br/>
				<span class="description">' . __( 'Enter the time (in seconds) a user has to enter captcha value.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}



	public function mc_general_ip_rules() {
		echo '
		<div id="mc_general_ip_rules">
			<fieldset>
				<input id="mc-general-ip-rules" type="checkbox" name="math_captcha_options[ip_rules]" ' . checked( true, Math_Captcha()->options['general']['ip_rules'], false ) . '/><label for="mc-general-ip-rules">' . __( 'Enable IP rules', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'IP rules allows to hide captcha for specific IP addresses (or range of IP addresses)', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
        
        echo '<br>';
        
        $ip_rules_list = '';
        if (isset(Math_Captcha()->options['general']['ip_rules_list'])) $ip_rules_list = implode("\n", Math_Captcha()->options['general']['ip_rules_list']);
        
		echo '
		<div id="mc_general_hide_for_countries">
			<fieldset>
                <span class="description"><b>' . __( 'Add IP addresses where captcha will be disabled (one per row)', 'math-captcha' ) . '</b></span>
                    <textarea name="math_captcha_options[ip_rules_list]" rows="5" cols="50">'.$ip_rules_list.'</textarea>  
                <span class="description"><b>' . __( 'e.g. 1.1.1.1 or 1.1.1.* or 1.1.1.0/24', 'math-captcha' ) . '</b></span>
			</fieldset>
		</div>';
	}
    

	public function mc_general_geo_captcha_rules() {
		echo '
		<div id="mc_general_geo_captcha_rules">
			<fieldset>
				<input id="mc-general-geo-captcha-rules" type="checkbox" name="math_captcha_options[geo_captcha_rules]" ' . checked( true, Math_Captcha()->options['general']['geo_captcha_rules'], false ) . '/><label for="mc-general-geo-captcha-rules">' . __( 'Hide captcha for specific countries', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'Enable this if you need to show the captcha for all visitors, except e.g. USA, Canada visitors.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
        
        echo '<br>';
        
		echo '
		<div id="mc_general_hide_for_countries">
			<fieldset>
                <span class="description"><b>' . __( 'Select countries where captcha will be disabled.', 'math-captcha' ) . '</b></span>
                ';

        $geo = new MathCaptcha_GEO();
        $countries = $geo->getCountryMapList();
		foreach ( $countries as $country_code => $country_name ) 
        {
            if ($country_code == 'A1' || $country_code == 'A2' || $country_code == 'O1') continue;
            
			echo '
				<input id="mc-general-hide-for-countries-' . $country_code . '" type="checkbox" name="math_captcha_options[hide_for_countries][]" value="' . $country_code . '" ' . checked( true, Math_Captcha()->options['general']['hide_for_countries'][$country_code], false ) . '/><label for="mc-general-hide-for-countries-' . $country_code . '">' . esc_html( $country_name ) . '</label>'."<br>";
		}

		echo '
			</fieldset>
		</div>';
	}
    

    
    
	public function mc_general_block_direct_comments() {
		echo '
		<div id="mc_general_block_direct_comments">
			<fieldset>
				<input id="mc-general-block-direct-comments" type="checkbox" name="math_captcha_options[block_direct_comments]" ' . checked( true, Math_Captcha()->options['general']['block_direct_comments'], false ) . '/><label for="mc-general-block-direct-comments">' . __( 'Block direct access to wp-comments-post.php.', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'Enable this to prevent spambots from posting to Wordpress via a URL.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_deactivation_delete() {
		echo '
		<div id="mc_general_deactivation_delete">
			<fieldset>
				<input id="mc-general-deactivation-delete" type="checkbox" name="math_captcha_options[deactivation_delete]" ' . checked( true, Math_Captcha()->options['general']['deactivation_delete'], false ) . '/><label for="mc-general-deactivation-delete">' . __( 'Delete settings on plugin deactivation.', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'Delete settings on plugin deactivation', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	/**
	 * Validate settings.
	 * 
	 * @param array $input
	 * @return array
	 */
	public function validate_settings( $input ) {
		if ( isset( $_POST['save_mc_general'] ) ) {
			// enable captcha forms
			$enable_for = array();

			if ( empty( $input['enable_for'] ) ) {
				foreach ( Math_Captcha()->defaults['general']['enable_for'] as $enable => $bool ) {
					$input['enable_for'][$enable] = false;
				}
			} else {
				foreach ( $this->forms as $enable => $trans ) {
					$enable_for[$enable] = (in_array( $enable, $input['enable_for'] ) ? true : false);
				}

				$input['enable_for'] = $enable_for;
			}

			if ( ! class_exists( 'WPCF7_ContactForm' ) && Math_Captcha()->options['general']['enable_for']['contact_form_7'] )
				$input['enable_for']['contact_form_7'] = true;

			if ( ! class_exists( 'bbPress' ) && Math_Captcha()->options['general']['enable_for']['bbpress'] )
				$input['enable_for']['bbpress'] = true;

			// enable mathematical operations
			$mathematical_operations = array();

			if ( empty( $input['mathematical_operations'] ) ) {
				add_settings_error( 'empty-operations', 'settings_updated', __( 'You need to check at least one mathematical operation. Defaults settings of this option restored.', 'math-captcha' ), 'error' );

				$input['mathematical_operations'] = Math_Captcha()->defaults['general']['mathematical_operations'];
			} else {
				foreach ( $this->mathematical_operations as $operation => $trans ) {
					$mathematical_operations[$operation] = (in_array( $operation, $input['mathematical_operations'] ) ? true : false);
				}

				$input['mathematical_operations'] = $mathematical_operations;
			}

			// enable groups
			$groups = array();

			if ( empty( $input['groups'] ) ) {
				add_settings_error( 'empty-groups', 'settings_updated', __( 'You need to check at least one group. Defaults settings of this option restored.', 'math-captcha' ), 'error' );

				$input['groups'] = Math_Captcha()->defaults['general']['groups'];
			} else {
				foreach ( $this->groups as $group => $trans ) {
					$groups[$group] = (in_array( $group, $input['groups'] ) ? true : false);
				}

				$input['groups'] = $groups;
			}

			// hide for logged in users
			$input['hide_for_logged_users'] = isset( $input['hide_for_logged_users'] );

			// block direct comments access
			$input['block_direct_comments'] = isset( $input['block_direct_comments'] );
            
            
			// IP rules
			$input['ip_rules'] = isset( $input['ip_rules'] );
			$input['ip_rules_list'] = trim( $input['ip_rules_list'] );
            if ($input['ip_rules_list'] != '') $input['ip_rules_list'] = explode("\n", $input['ip_rules_list']); 
            
			// geo captcha rules
			$input['geo_captcha_rules'] = isset( $input['geo_captcha_rules'] );
            
            // math_captcha_options[hide_for_countries]
			$hide_for_countries = array();
			//$mathematical_operations = array();
            
			if ( empty( $input['hide_for_countries'] ) ) {
	
			} else {

				foreach ( $input['hide_for_countries'] as $country_code ) {
					$hide_for_countries[$country_code] = (in_array( $country_code, $input['hide_for_countries'] ) ? true : false);
				}

				$input['hide_for_countries'] = $hide_for_countries;
			}
            

			// deactivation delete
			$input['deactivation_delete'] = isset( $input['deactivation_delete'] );

			// captcha title
			$input['title'] = trim( $input['title'] );

			// captcha time
			$time = (int) $input['time'];
			$input['time'] = ($time < 0 ? Math_Captcha()->defaults['general']['time'] : $time);

			// flush rules
			$input['flush_rules'] = true;
		} elseif ( isset( $_POST['reset_mc_general'] ) ) {
			$input = Math_Captcha()->defaults['general'];

			add_settings_error( 'settings', 'settings_reset', __( 'Settings restored to defaults.', 'math-captcha' ), 'updated' );
		}

		return $input;
	}

}