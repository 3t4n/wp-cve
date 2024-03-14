<?php

/**
 * This class adds the options with the related callbacks and validations.
 */
class Daextinma_Menu_Options {

	/**
	 * Instance of the shared class.
	 *
	 * @var Daexthrmal_Shared|null
	 */
	private $shared = null;

	public function __construct( $shared ) {

		//assign an instance of the plugin info
		$this->shared = $shared;

	}

	public function register_options() {

		//section optimization -------------------------------------------------
		add_settings_section(
			'daextinma_optimization_settings_section',
			null,
			null,
			'daextinma_optimization_options'
		);

		add_settings_field(
			'optimization_num_of_characters',
			esc_html__( 'Characters per Interlink', 'daext-interlinks-manager'),
			array( $this, 'optimization_num_of_characters_callback' ),
			'daextinma_optimization_options',
			'daextinma_optimization_settings_section'
		);

		register_setting(
			'daextinma_optimization_options',
			'daextinma_optimization_num_of_characters',
			array( $this, 'optimization_num_of_characters_validation' )
		);

		add_settings_field(
			'optimization_delta',
			esc_html__( 'Optimization Delta', 'daext-interlinks-manager'),
			array( $this, 'optimization_delta_callback' ),
			'daextinma_optimization_options',
			'daextinma_optimization_settings_section'
		);

		register_setting(
			'daextinma_optimization_options',
			'daextinma_optimization_delta',
			array( $this, 'optimization_delta_validation' )
		);

		//section juice --------------------------------------------------------
		add_settings_section(
			'daextinma_juice_settings_section',
			null,
			null,
			'daextinma_juice_options'
		);

		add_settings_field(
			'default_seo_power',
			esc_html__( 'SEO Power (Default)', 'daext-interlinks-manager'),
			array( $this, 'default_seo_power_callback' ),
			'daextinma_juice_options',
			'daextinma_juice_settings_section'
		);

		register_setting(
			'daextinma_juice_options',
			'daextinma_default_seo_power',
			array( $this, 'default_seo_power_validation' )
		);

		add_settings_field(
			'penality_per_position_percentage',
			esc_html__( 'Penality per Position (%)', 'daext-interlinks-manager'),
			array( $this, 'penality_per_position_percentage_callback' ),
			'daextinma_juice_options',
			'daextinma_juice_settings_section'
		);

		register_setting(
			'daextinma_juice_options',
			'daextinma_penality_per_position_percentage',
			array( $this, 'penality_per_position_percentage_validation' )
		);

		add_settings_field(
			'remove_link_to_anchor',
			esc_html__( 'Remove Link to Anchor', 'daext-interlinks-manager'),
			array( $this, 'remove_link_to_anchor_callback' ),
			'daextinma_juice_options',
			'daextinma_juice_settings_section'
		);

		register_setting(
			'daextinma_juice_options',
			'daextinma_remove_link_to_anchor',
			array( $this, 'remove_link_to_anchor_validation' )
		);

		add_settings_field(
			'remove_url_parameters',
			esc_html__( 'Remove URL Parameters', 'daext-interlinks-manager'),
			array( $this, 'remove_url_parameters_callback' ),
			'daextinma_juice_options',
			'daextinma_juice_settings_section'
		);

		register_setting(
			'daextinma_juice_options',
			'daextinma_remove_url_parameters',
			array( $this, 'remove_url_parameters_validation' )
		);

		//section analysis --------------------------------------------------
		add_settings_section(
			'daextinma_analysis_settings_section',
			null,
			null,
			'daextinma_analysis_options'
		);

		add_settings_field(
			'set_max_execution_time',
			esc_html__( 'Set Max Execution Time', 'daext-interlinks-manager'),
			array( $this, 'set_max_execution_time_callback' ),
			'daextinma_analysis_options',
			'daextinma_analysis_settings_section'
		);

		register_setting(
			'daextinma_analysis_options',
			'daextinma_set_max_execution_time',
			array( $this, 'set_max_execution_time_validation' )
		);

		add_settings_field(
			'max_execution_time_value',
			esc_html__( 'Max Execution Time Value', 'daext-interlinks-manager'),
			array( $this, 'max_execution_time_value_callback' ),
			'daextinma_analysis_options',
			'daextinma_analysis_settings_section'
		);

		register_setting(
			'daextinma_analysis_options',
			'daextinma_max_execution_time_value',
			array( $this, 'max_execution_time_value_validation' )
		);

		add_settings_field(
			'set_memory_limit',
			esc_html__( 'Set Memory Limit', 'daext-interlinks-manager'),
			array( $this, 'set_memory_limit_callback' ),
			'daextinma_analysis_options',
			'daextinma_analysis_settings_section'
		);

		register_setting(
			'daextinma_analysis_options',
			'daextinma_set_memory_limit',
			array( $this, 'set_memory_limit_validation' )
		);

		add_settings_field(
			'memory_limit_value',
			esc_html__( 'Memory Limit Value', 'daext-interlinks-manager'),
			array( $this, 'memory_limit_value_callback' ),
			'daextinma_analysis_options',
			'daextinma_analysis_settings_section'
		);

		register_setting(
			'daextinma_analysis_options',
			'daextinma_memory_limit_value',
			array( $this, 'memory_limit_value_validation' )
		);

		add_settings_field(
			'limit_posts_analysis',
			esc_html__( 'Limit Posts Analysis', 'daext-interlinks-manager'),
			array( $this, 'limit_posts_analysis_callback' ),
			'daextinma_analysis_options',
			'daextinma_analysis_settings_section'
		);

		register_setting(
			'daextinma_analysis_options',
			'daextinma_limit_posts_analysis',
			array( $this, 'limit_posts_analysis_validation' )
		);

		add_settings_field(
			'dashboard_post_types',
			esc_html__( 'Dashboard Post Types', 'daext-interlinks-manager'),
			array( $this, 'dashboard_post_types_callback' ),
			'daextinma_analysis_options',
			'daextinma_analysis_settings_section'
		);

		register_setting(
			'daextinma_analysis_options',
			'daextinma_dashboard_post_types',
			array( $this, 'dashboard_post_types_validation' )
		);

		add_settings_field(
			'juice_post_types',
			esc_html__( 'Juice Post Types', 'daext-interlinks-manager'),
			array( $this, 'juice_post_types_callback' ),
			'daextinma_analysis_options',
			'daextinma_analysis_settings_section'
		);

		register_setting(
			'daextinma_analysis_options',
			'daextinma_juice_post_types',
			array( $this, 'juice_post_types_validation' )
		);

		//meta boxes -----------------------------------------------------------
		add_settings_section(
			'daextinma_metaboxes_settings_section',
			null,
			null,
			'daextinma_metaboxes_options'
		);

		add_settings_field(
			'interlinks_options_post_types',
			esc_html__( 'Interlinks Options Post Types', 'daext-interlinks-manager'),
			array( $this, 'interlinks_options_post_types_callback' ),
			'daextinma_metaboxes_options',
			'daextinma_metaboxes_settings_section'
		);

		register_setting(
			'daextinma_metaboxes_options',
			'daextinma_interlinks_options_post_types',
			array( $this, 'interlinks_options_post_types_validation' )
		);

		add_settings_field(
			'interlinks_optimization_post_types',
			esc_html__( 'Interlinks Optimization Post Types', 'daext-interlinks-manager'),
			array( $this, 'interlinks_optimization_post_types_callback' ),
			'daextinma_metaboxes_options',
			'daextinma_metaboxes_settings_section'
		);

		register_setting(
			'daextinma_metaboxes_options',
			'daextinma_interlinks_optimization_post_types',
			array( $this, 'interlinks_optimization_post_types_validation' )
		);

	}

	//optimization options callbacks and validations ---------------------------

	public function optimization_num_of_characters_callback( $args ) {

		$html = '<input type="text" id="daextinma_optimization_num_of_characters" name="daextinma_optimization_num_of_characters" class="regular-text" value="' . intval( get_option( "daextinma_optimization_num_of_characters" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The "Recommended Interlinks" value available in the "Dashboard" menu and in the "Interlinks Optimization" meta box is based on the defined "Characters per Interlink" and on the content length of the post. For example if you define 500 "Characters per Interlink", in the "Dashboard" menu, with a post that has a content length of 2000 characters you will get 4 as the value for the "Recommended Interlinks".', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function optimization_num_of_characters_validation( $input ) {

		$input = intval( $input, 10 );

		if ( ! preg_match( $this->shared->regex_number_ten_digits, $input ) or ( intval( $input,
					10 ) < 1 ) or ( intval( $input, 10 ) > 1000000 ) ) {
			add_settings_error( 'daextinma_optimization_num_of_characters', 'daextinma_optimization_num_of_characters',
				esc_html__( 'Please enter a number from 1 to 1000000 in the "Characters per Interlink" option.', 'daext-interlinks-manager') );
			$output = get_option( 'daextinma_optimization_num_of_characters' );
		} else {
			$output = $input;
		}

		return intval( $output, 10 );

	}

	public function optimization_delta_callback( $args ) {

		$html = '<input type="text" id="daextinma_optimization_delta" name="daextinma_optimization_delta" class="regular-text" value="' . intval( get_option( "daextinma_optimization_delta" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The "Optimization Delta" is used to generate the "Optimization Flag" available in the "Dashboard" menu and the text message diplayed in the "Interlinks Optimization" meta box. This option determines how different can be the actual number of interlinks in a post from the calculated "Recommended Interlinks". This option defines a range, so for example in a post with 10 "Recommended Interlinks" and this option value equal to 4, the post will be considered optimized when it includes from 8 to 12 interlinks.', 'daext-interlinks-manager') . '"></div>';


		echo $html;

	}

	public function optimization_delta_validation( $input ) {

		$input = intval( $input, 10 );

		if ( ! preg_match( $this->shared->regex_number_ten_digits, $input ) or ( intval( $input, 10 ) > 1000000 ) ) {
			add_settings_error( 'daextinma_optimization_delta', 'daextinma_optimization_delta',
				esc_html__( 'Please enter a number from 0 to 1000000 in the "Optimization Delta" option.', 'daext-interlinks-manager') );
			$output = get_option( 'daextinma_optimization_delta' );
		} else {
			$output = $input;
		}

		return intval( $output, 10 );

	}

	//juice options callbacks and validations ----------------------------------
	public function default_seo_power_callback( $args ) {

		$html = '<input type="text" id="daextinma_default_seo_power" name="daextinma_default_seo_power" class="regular-text" value="' . intval( get_option( "daextinma_default_seo_power" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The "SEO Power" is the base value used to calculate the flow of "Link Juice" and this option determines the default "SEO Power" value of a post. You can override this value for specific posts in the "Interlinks Options" meta box.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function default_seo_power_validation( $input ) {

		$input = intval( $input, 10 );

		if ( ! preg_match( $this->shared->regex_number_ten_digits, $input ) or ( intval( $input,
					10 ) < 100 ) or ( intval( $input, 10 ) > 1000000 ) ) {
			add_settings_error( 'daextinma_default_seo_power', 'daextinma_default_seo_power',
				esc_html__( 'Please enter a number from 100 to 1000000 in the "SEO Power (Default)" option.', 'daext-interlinks-manager') );
			$output = get_option( 'daextinma_default_seo_power' );
		} else {
			$output = $input;
		}

		return intval( $output, 10 );

	}

	public function penality_per_position_percentage_callback( $args ) {

		$html = '<input type="text" id="daextinma_penality_per_position_percentage" name="daextinma_penality_per_position_percentage" class="regular-text" value="' . intval( get_option( "daextinma_penality_per_position_percentage" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With multiple links in an article, the algorithm that calculates the "Link Juice" passed by each link removes a percentage of the passed "Link Juice" based on the position of a link compared to the other links.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function penality_per_position_percentage_validation( $input ) {

		$input = intval( $input, 10 );

		if ( ! preg_match( $this->shared->regex_number_ten_digits, $input ) or ( intval( $input, 10 ) > 100 ) ) {
			add_settings_error( 'daextinma_penality_per_position_percentage',
				'daextinma_penality_per_position_percentage',
				esc_html__( 'Please enter a number from 0 to 100 in the "Penality per position" option.', 'daext-interlinks-manager') );
			$output = get_option( 'daextinma_penality_per_position_percentage' );
		} else {
			$output = $input;
		}

		return intval( $output, 10 );

	}

	public function remove_link_to_anchor_callback( $args ) {

		$html = '<select id="daextinma_remove_link_to_anchor" name="daextinma_remove_link_to_anchor" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextinma_remove_link_to_anchor" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daext-interlinks-manager') . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextinma_remove_link_to_anchor" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daext-interlinks-manager') . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select "Yes" to automatically remove links to anchors from every URL used to calculate the link juice. With this option enabled "http://example.com" and "http://example.com#myanchor" will both contribute to generate link juice only for a single URL, that is "http://example.com".', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function remove_link_to_anchor_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	public function remove_url_parameters_callback( $args ) {

		$html = '<select id="daextinma_remove_url_parameters" name="daextinma_remove_url_parameters" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextinma_remove_url_parameters" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daext-interlinks-manager') . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextinma_remove_url_parameters" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daext-interlinks-manager') . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select "Yes" to automatically remove the URL parameters from every URL used to calculate the link juice. With this option enabled "http://example.com" and "http://example.com?param=1" will both contribute to generate link juice only for a single URL, that is "http://example.com". Please note that this option should not be enabled if your website is using URL parameters to actually identify specific pages. (for example with pretty permalinks not enabled)', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function remove_url_parameters_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	//analysis options callbacks and validations ----------------------------
	public function set_max_execution_time_callback( $args ) {

		$html = '<select id="daextinma_set_max_execution_time" name="daextinma_set_max_execution_time" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextinma_set_max_execution_time" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daext-interlinks-manager') . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextinma_set_max_execution_time" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daext-interlinks-manager') . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select "Yes" to enable your custom "Max Execution Time Value" on long running scripts.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function set_max_execution_time_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	public function max_execution_time_value_callback( $args ) {

		$html = '<input type="text" id="daextinma_max_execution_time_value" name="daextinma_max_execution_time_value" class="regular-text" value="' . intval( get_option( "daextinma_max_execution_time_value" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This value determines the maximum number of seconds allowed to execute long running scripts.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function max_execution_time_value_validation( $input ) {

		$input = intval( $input, 10 );

		if ( ! preg_match( $this->shared->regex_number_ten_digits, $input ) or intval( $input,
				10 ) < 1 or intval( $input, 10 ) > 1000000 ) {
			add_settings_error( 'daextinma_max_execution_time_value', 'daextinma_max_execution_time_value',
				esc_html__( 'Please enter a number from 1 to 1000000 in the "Max Execution Time Value" option.', 'daext-interlinks-manager') );
			$output = get_option( 'daextinma_max_execution_time_value' );
		} else {
			$output = $input;
		}

		return intval( $output, 10 );

	}

	public function set_memory_limit_callback( $args ) {

		$html = '<select id="daextinma_set_memory_limit" name="daextinma_set_memory_limit" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextinma_set_memory_limit" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daext-interlinks-manager') . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextinma_set_memory_limit" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daext-interlinks-manager') . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select "Yes" to enable your custom "Memory Limit Value" on long running scripts.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function set_memory_limit_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	public function memory_limit_value_callback( $args ) {

		$html = '<input type="text" id="daextinma_memory_limit_value" name="daextinma_memory_limit_value" class="regular-text" value="' . intval( get_option( "daextinma_memory_limit_value" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This value determines the PHP memory limit in megabytes allowed to execute long running scripts.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function memory_limit_value_validation( $input ) {

		$input = intval( $input, 10 );

		if ( ! preg_match( $this->shared->regex_number_ten_digits, $input ) or intval( $input,
				10 ) < 1 or intval( $input, 10 ) > 1000000 ) {
			add_settings_error( 'daextinma_memory_limit_value', 'daextinma_memory_limit_value',
				esc_html__( 'Please enter a number from 1 to 1000000 in the "Memory Limit Value" option.', 'daext-interlinks-manager') );
			$output = get_option( 'daextinma_memory_limit_value' );
		} else {
			$output = $input;
		}

		return intval( $output, 10 );

	}

	public function limit_posts_analysis_callback( $args ) {

		$html = '<input type="text" id="daextinma_limit_posts_analysis" name="daextinma_limit_posts_analysis" class="regular-text" value="' . intval( get_option( "daextinma_limit_posts_analysis" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With this options you can determine the maximum number of posts analyzed to get information about your internal links and to get information about the internal links juice. If you select for example "1000", the analysis performed by the plugin will use your latest "1000" posts.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function limit_posts_analysis_validation( $input ) {

		$input = intval( $input, 10 );

		if ( ! preg_match( $this->shared->regex_number_ten_digits, $input ) or intval( $input,
				10 ) < 1 or intval( $input, 10 ) > 100000 ) {
			add_settings_error( 'daextinma_limit_posts_analysis', 'daextinma_limit_posts_analysis',
				esc_html__( 'Please enter a number from 1 to 100000 in the "Limit Posts Analysis" option.', 'daext-interlinks-manager') );
			$output = get_option( 'daextinma_limit_posts_analysis' );
		} else {
			$output = $input;
		}

		return intval( $output, 10 );

	}

	public function dashboard_post_types_callback( $args ) {

		$dashboard_post_types_a = get_option( "daextinma_dashboard_post_types" );

		$available_post_types_a = get_post_types( array(
			'public'  => true,
			'show_ui' => true
		) );

		//Remove the "attachment" post type
		$available_post_types_a = array_diff( $available_post_types_a, array( 'attachment' ) );

		$html = '<select id="daextinma-dashboard-post-types" name="daextinma_dashboard_post_types[]" class="daext-display-none" multiple>';

		foreach ( $available_post_types_a as $single_post_type ) {
			if ( is_array( $dashboard_post_types_a ) and in_array( $single_post_type, $dashboard_post_types_a ) ) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$post_type_obj = get_post_type_object( $single_post_type );
			$html          .= '<option value="' . esc_attr( $single_post_type ) . '" ' . $selected . '>' . esc_html( $post_type_obj->label ) . '</option>';
		}

		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With this option you are able to determine the post types analyzed in the Dashboard menu.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function dashboard_post_types_validation( $input ) {

		if ( is_array( $input ) and count( $input ) > 0 ) {
			$output = [];
			foreach ( $input as $value ) {
				$output[] = sanitize_key( $value );
			}
		} else {
			add_settings_error( 'daextinma_dashboard_post_types_validation',
				'daextinma_dashboard_post_types_validation',
				esc_html__( 'Please enter at least one post type in the "Dashboard Post Types" option.', 'daext-interlinks-manager') );
			$output = get_option( 'daextinma_dashboard_post_types' );
		}

		return $output;

	}

	public function juice_post_types_callback( $args ) {

		$juice_post_types_a = get_option( "daextinma_juice_post_types" );

		$available_post_types_a = get_post_types( array(
			'public'  => true,
			'show_ui' => true
		) );

		//Remove the "attachment" post type
		$available_post_types_a = array_diff( $available_post_types_a, array( 'attachment' ) );

		$html = '<select id="daextinma-juice-post-types" name="daextinma_juice_post_types[]" class="daext-display-none" multiple>';

		foreach ( $available_post_types_a as $single_post_type ) {
			if ( is_array( $juice_post_types_a ) and in_array( $single_post_type, $juice_post_types_a ) ) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$post_type_obj = get_post_type_object( $single_post_type );
			$html          .= '<option value="' . esc_attr( $single_post_type ) . '" ' . $selected . '>' . esc_html( $post_type_obj->label ) . '</option>';
		}

		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With this option you are able to determine the post types analyzed in the Juice menu.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function juice_post_types_validation( $input ) {

		if ( is_array( $input ) and count( $input ) > 0 ) {
			$output = [];
			foreach ( $input as $value ) {
				$output[] = sanitize_key( $value );
			}
		} else {
			add_settings_error( 'daextinma_juice_post_types_validation', 'daextinma_juice_post_types_validation',
				esc_html__( 'Please enter at least one post type in the "Juice Post Types" option.', 'daext-interlinks-manager') );
			$output = get_option( 'daextinma_juice_post_types' );
		}

		return $output;

	}

	//metaboxes options callbacks and validation -------------------------------
	public function interlinks_options_post_types_callback( $args ) {

		$interlinks_options_post_types_a = get_option( "daextinma_interlinks_options_post_types" );

		$available_post_types_a = get_post_types( array(
			'public'  => true,
			'show_ui' => true
		) );

		//Remove the "attachment" post type
		$available_post_types_a = array_diff( $available_post_types_a, array( 'attachment' ) );

		$html = '<select id="daextinma-interlinks-options-post-types" name="daextinma_interlinks_options_post_types[]" class="daext-display-none" multiple>';

		foreach ( $available_post_types_a as $single_post_type ) {
			if ( is_array( $interlinks_options_post_types_a ) and in_array( $single_post_type,
					$interlinks_options_post_types_a ) ) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$post_type_obj = get_post_type_object( $single_post_type );
			$html          .= '<option value="' . esc_attr( $single_post_type ) . '" ' . $selected . '>' . esc_html( $post_type_obj->label ) . '</option>';
		}

		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With this option you are able to determine in which post types the "Interlinks Options" meta box should be loaded.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function interlinks_options_post_types_validation( $input ) {

		if ( is_array( $input ) and count( $input ) > 0 ) {
			$output = [];
			foreach ( $input as $value ) {
				$output[] = sanitize_key( $value );
			}
		} else {
			return '';
		}

		return $output;

	}

	public function interlinks_optimization_post_types_callback( $args ) {

		$interlinks_optimization_post_types_a = get_option( "daextinma_interlinks_optimization_post_types" );

		$available_post_types_a = get_post_types( array(
			'public'  => true,
			'show_ui' => true
		) );

		//Remove the "attachment" post type
		$available_post_types_a = array_diff( $available_post_types_a, array( 'attachment' ) );

		$html = '<select id="daextinma-interlinks-optimization-post-types" name="daextinma_interlinks_optimization_post_types[]" class="daext-display-none" multiple>';

		foreach ( $available_post_types_a as $single_post_type ) {
			if ( is_array( $interlinks_optimization_post_types_a ) and in_array( $single_post_type,
					$interlinks_optimization_post_types_a ) ) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$post_type_obj = get_post_type_object( $single_post_type );
			$html          .= '<option value="' . esc_attr( $single_post_type ) . '" ' . $selected . '>' . esc_html( $post_type_obj->label ) . '</option>';
		}

		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With this option you are able to determine in which post types the "Interlinks Optimization" meta box should be loaded.', 'daext-interlinks-manager') . '"></div>';

		echo $html;

	}

	public function interlinks_optimization_post_types_validation( $input ) {

		if ( is_array( $input ) and count( $input ) > 0 ) {
			$output = [];
			foreach ( $input as $value ) {
				$output[] = sanitize_key( $value );
			}
		} else {
			return '';
		}

		return $output;

	}

}