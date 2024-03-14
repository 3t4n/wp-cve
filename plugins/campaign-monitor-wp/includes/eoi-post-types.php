<?php
	
class EasyOptInsPostTypes {

	public $settings;

	private $activity_day_interval = array(
		'form_list' => null,
		'dashboard_widget' => 30
	);

	private $two_step_ids_on_page = array();

	public function __construct( $settings ) {

		$this->settings = $settings;

		$providers_available = array_keys( $this->settings[ 'providers' ] );

		// Register custom post type
		add_action( 'init', array( $this, 'register_custom_post_type' ) );
		add_filter( 'manage_easy-opt-ins_posts_columns', array( $this, 'add_new_columns' ) );
		add_action( 'manage_easy-opt-ins_posts_custom_column', array( $this, 'set_column_data' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );

		// Reset action
		add_action( 'admin_post_fca_eoi_reset_stats', array( $this, 'reset_stats' ) );

		// Dashboard widget
		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_setup' ) );

		// Save
		add_action( 'save_post', array( $this, 'save_meta_box_content' ), 1, 2 );

		// Live preview
		add_filter( 'the_content', array( $this, 'live_preview' ) );

		// Scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

		add_action( 'admin_head', array( $this, 'hide_minor_publishing' ) );
		
		add_action( 'admin_notices', array( $this, 'onboard_help' ), 1 );
		
		if ( $this->settings['distribution'] === 'free' ) { 
			add_action( 'admin_notices', array( $this, 'review_notice' ) );
		}
		
		add_filter( 'admin_body_class', array( $this, 'add_body_class' ) );

		add_filter( 'wp_insert_post_data', array( $this, 'force_published' ) );

		add_action( 'wp_ajax_fca_eoi_subscribe', array( $this, 'ajax_subscribe' ) );
		add_action( 'wp_ajax_nopriv_fca_eoi_subscribe', array( $this, 'ajax_subscribe' ) );
		
		add_action( 'wp_ajax_fca_eoi_dismiss', array( $this, 'ajax_dismiss_notice' ) );
				
		add_filter( 'get_user_option_screen_layout_easy-opt-ins', array( $this, 'force_one_column' ) );

		add_filter( 'get_user_option_meta-box-order_easy-opt-ins', array( $this, 'order_columns' ) );

		add_filter( 'post_updated_messages', array( $this, 'override_text' ) );

		add_filter( 'bulk_actions-edit-easy-opt-ins', array( $this, 'disable_bulk_edit' ) );

		add_filter( 'post_row_actions', array( $this, 'remove_quick_edit' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_filter( 'enter_title_here', array( $this, 'change_default_title' ) );

		add_filter( 'init', array( $this, 'bind_content_filter' ), 10 );

		add_filter( 'plugin_action_links_' . FCA_EOI_PLUGIN_BASENAME, array( $this, 'add_plugin_action_links' ) );
		
		//ADD ACTIONS TO GET THE ENTIRE PAGE OUTPUT IN BUFFER
	
		if ( wp_get_theme() == 'Customizr' ) {
			add_filter( 'the_content', array( $this, 'scan_for_shortcodes' ) );
		} else {
			add_action('wp_head', array( $this, 'fca_eoi_buffer_start' ));
			add_action('wp_footer', array( $this, 'fca_eoi_buffer_end' ));	
		}

		add_filter( 'wp_footer', array( $this, 'maybe_show_lightbox' ) );

		foreach ( $providers_available as $provider ) {
			add_action( 'wp_ajax_fca_eoi_' . $provider . '_get_lists', $provider . '_ajax_get_lists' );
		}

		// Hook provder callback functions
		foreach ( $providers_available as $provider ) {
			add_filter( 'fca_eoi_alter_admin_notices', $provider . '_admin_notices', 10, 1 );
		} 

		// Handle licensing
		if( count( $providers_available ) > 1 ) {
			require_once FCA_EOI_PLUGIN_DIR . 'includes/licensing.php';
			new  EasyOptInsLicense( $this->settings );
		}
	}
	
	public function add_plugin_action_links( $links ) {
		$url = admin_url('post-new.php?post_type=easy-opt-ins');
		
		$support_url = 'https://fatcatapps.com/support';
	
		switch ( FCA_EOI_PLUGIN_SLUG ) {
			case 'aweber-wp':
				$support_url = 'https://wordpress.org/support/plugin/aweber-wp';
				break;
			
			case 'campaign-monitor-wp':
				$support_url = 'https://wordpress.org/support/plugin/campaign-monitor-wp';
				break;
				
			case 'mailchimp-wp':
				$support_url = 'https://wordpress.org/support/plugin/mailchimp-wp';
				break;
				
			case 'mad-mimi-wp':
				$support_url = 'https://wordpress.org/support/plugin/mad-mimi-wp';
				break;
				
			case 'getresponse-wp':
				$support_url = 'https://wordpress.org/support/plugin/getresponse';
				break;
			
		}

		$new_links = array(
			'addnew' => "<a href='$url' >" . __('Add New Optin Form', 'easy-opt-ins' ) . '</a>',
			'support' => "<a target='_blank' href='$support_url' >" . __('Support', 'easy-opt-ins' ) . '</a>'
		);
		
		$links = array_merge( $new_links, $links );
	
		return $links;
		
	}
	
	function fca_eoi_buffer_start() { ob_start(array( $this, 'scan_for_shortcodes' )); }
	function fca_eoi_buffer_end() { ob_end_flush(); }
	
	public function register_custom_post_type() {

		$labels = array(
			'name' => __('Optin Forms') ,
			'singular_name' => __('Optin Form') ,
			'add_new' => __('Add New') ,
			'add_new_item' => __('Add New Optin Form') ,
			'edit_item' => __('Edit Optin Form') ,
			'new_item' => __('New Optin Form') ,
			'all_items' => __('All Optin Forms') ,
			'view_item' => __('View Optin Form') ,
			'search_items' => __('Search Optin Form') ,
			'not_found' => __('No Optin Form Found') ,
			'not_found_in_trash' => __('No Optin Form Found in Trash') ,
			'parent_item_colon' => '',
			'menu_name' => __('Optin Forms')
		);
		$args = array(
			'menu_icon' => FCA_EOI_PLUGIN_URL . '/icon.png',
			'labels' => $labels,
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'easy-opt-ins',
			) ,
			'capability_type' => 'page',
			'has_archive' => false,
			'hierarchical' => false,
			'menu_position' => 106,
			'supports' => array(
				'title',
			) ,
			'register_meta_box_cb' => array(
				$this,
				'add_meta_boxes'
			)
		);
		register_post_type('easy-opt-ins', $args);
	}

	private function enqueue_activity_style() {
		wp_enqueue_style( 'admin-cpt-easy-opt-ins-activity', FCA_EOI_PLUGIN_URL . '/assets/admin/cpt-easy-opt-ins-activity.min.css', array(), FCA_EOI_VER );
	}

	public function add_new_columns( $columns ) {
		$new_columns = array();

		if ( ! empty( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
		}

		if ( ! empty( $columns['title'] ) ) {
			$new_columns['title'] = $columns['title'];
		}

		$this->enqueue_activity_style();
		$activity = EasyOptInsActivity::get_instance();

		$period =
			'<span class="fca_eoi_activity_period">(' .
				$activity->get_text( 'period', null, array( $this->activity_day_interval['form_list'] ) ) .
			')</span>';

		foreach ( array( 'impressions', 'conversions', 'conversion_rate' ) as $activity_type ) {
			$new_columns[ $activity_type ] = esc_html( $activity->get_text( $activity_type, 'form' ) ) . '<br>' . $period;
		}

		return $new_columns;
	}

	public function set_column_data( $column_name, $form_id ) {
		$activity = EasyOptInsActivity::get_instance();

		$stats = $activity->get_form_stats( $this->activity_day_interval['form_list'] );
		$value = 0;

		if ( ! empty( $stats[ $column_name ][ $form_id ] ) ) {
			$value = $stats[ $column_name ][ $form_id ];
		}

		echo $activity->format_column_text( $column_name, $value );
	}

	public function post_row_actions( $actions, $post ) {
		if ( $post->post_type == 'easy-opt-ins' ) {
			$action = 'fca_eoi_reset_stats';
			$title  = __( 'Reset stats for this item' );
			$label  = __( 'Reset Stats' );

			$url = add_query_arg( 'action', $action, admin_url( 'admin-post.php?post=' . $post->ID ) );
			$url = wp_nonce_url( $url );

			$actions[$action] = $this->confirm_tag(
				'<a href="' . $url . '" title="' . $title . '">' . $label . '</a>',
				__( 'Are you sure?' ),
				__( 'Do you really want to reset this Optin Form\'s stats? This action cannot be undone.' )
			);
		}
		return $actions;
	}

	public function reset_stats() {
		if ( wp_verify_nonce( $_REQUEST['_wpnonce'] ) ) {
			EasyOptInsActivity::get_instance()->reset_stats( (int) $_REQUEST['post'] );
			wp_redirect( wp_get_referer() );
		}
	}

	private function confirm_tag( $tag, $title, $message ) {
		return preg_replace(
			'/>/',
			' onclick="return confirm(' .
				esc_html( '"' . $title . '\n\n' . $message . '"' ) .
			')">',
			$tag, 1 );
	}

	public function dashboard_setup() {
		
		if ( defined ( 'FCA_EOI_DISABLE_STATS_TRACKING' )) {
			$title = 'Optin Cat Summary (TRACKING DISABLED)';
		} else {
			$title = 'Optin Cat Summary';
		}
		add_meta_box(
			'fca_eoi_dashboard_widget',
			$title,
			array( $this, 'add_dashboard_widget' ),
			'dashboard',
			'normal',
			'high'
		);
	}

	public function add_dashboard_widget() {
		wp_enqueue_script( 'd3_js', FCA_EOI_PLUGIN_URL . '/assets/vendor/nvd3/d3.min.js', array(), FCA_EOI_VER, true );
		wp_enqueue_script( 'nvd3_js', FCA_EOI_PLUGIN_URL . '/assets/vendor/nvd3/nv.d3.min.js', array(), FCA_EOI_VER, true );
		wp_enqueue_style( 'nvd3_css', FCA_EOI_PLUGIN_URL . '/assets/vendor/nvd3/nv.d3.min.css', array(), FCA_EOI_VER );
		$this->enqueue_activity_style();

		$day_interval = $this->activity_day_interval['dashboard_widget'];
		$activity = EasyOptInsActivity::get_instance();
		$stats = $activity->get_daily_stats( $day_interval );

		$date_labels = array();
		foreach ( array_keys( $stats['impressions'] ) as $date ) {
			$date_labels[] = date( "j M", strtotime( $date ) );
		}

		$colors = array(
			'impressions' => '#5b90bf',
			'conversions' => '#bf616a'
		);

		?>
		<div class="fca_eoi_activity_chart_title_container">
			<div class="fca_eoi_activity_chart_legend">
				<?php foreach ( array( 'impressions', 'conversions' ) as $activity_type ): ?>
					<div class="fca_eoi_activity_chart_legend_item">
						<div class="fca_eoi_activity_chart_legend_sample" style="background-color: <?php echo $colors[ $activity_type ] ?>;"></div>
						<div class="fca_eoi_activity_chart_legend_text">
							<?php echo esc_html( $activity->get_text( $activity_type, 'total' ) ) ?>
						</div>
					</div>
				<?php endforeach ?>
			</div>
			<div class="fca_eoi_activity_chart_period">
				<?php echo esc_html( $activity->get_text( 'period', null, array( $day_interval ) ) ) ?>
				-
				<a href="<?php echo admin_url( 'edit.php?post_type=easy-opt-ins' ) ?>"><?php echo __( 'View All Data' ) ?></a>
			</div>
		</div>
		<div class="fca_eoi_activity_chart" id="fca_eoi_activity_chart"></div>
		<div class="fca_eoi_activity_chart_stat">
			<?php foreach ( array( 'impressions', 'conversions', 'conversion_rate' ) as $activity_type ): ?>
				<div class="fca_eoi_activity_chart_stat_item">
					<div class="fca_eoi_activity_chart_stat_value">
						<?php echo $activity->format_column_text( $activity_type, $stats['totals'][ $activity_type ] ) ?>
					</div>
					<div class="fca_eoi_activity_chart_stat_title">
						<?php echo esc_html( $activity->get_text( $activity_type, 'total' ) ) ?>
					</div>
				</div>
			<?php endforeach ?>
		</div>
		<script>
			jQuery( function() {
				var impressions = <?php echo json_encode( array_values( $stats['impressions'] ) ) ?>;
				var conversions = <?php echo json_encode( array_values( $stats['conversions'] ) ) ?>;
				var dates = <?php echo json_encode( $date_labels ) ?>;

				var chart = nv.models.lineChart().options({
					duration: 0,
					transitionDuration: 0,
					useInteractiveGuideline: true,
					isArea: true,
					showLegend: false,
					margin: { top: 10, right: 20, bottom: 30, left: 40 }
				} );

				chart.xAxis.tickFormat( function( index ) { return dates[ index ]; } );
				chart.yAxis.tickFormat( d3.format( 'd' ) );
				chart.forceY( [ 0, d3.max(impressions) || 1 ] );

				var valuesToPoint = function( value, index ) {
					return { x: index, y: value };
				};

				d3.select( '#fca_eoi_activity_chart' ).append( 'svg' ).datum( [
					{ color: '<?php echo $colors['impressions'] ?>', key: 'Impressions', values: impressions.map(valuesToPoint) },
					{ color: '<?php echo $colors['conversions'] ?>', key: 'Conversions', values: conversions.map(valuesToPoint) }
				] ).call( chart );

				nv.utils.windowResize( chart.update );
			} );
		</script>
	<?php
	}

	public function add_meta_boxes() {

		add_meta_box(
			'fca_eoi_meta_box_setup',
			__( 'Setup' ),
			array( &$this, 'meta_box_content_setup' ),
			'easy-opt-ins',
			'side',
			'high'
		);
		add_meta_box(
			'fca_eoi_meta_box_build',
			__( 'Form Builder' ),
			array( &$this, 'meta_box_content_build' ),
			'easy-opt-ins',
			'side',
			'high'
		);
		
		add_meta_box(
			'fca_eoi_meta_box_provider',
			__( 'Email Marketing Provider Integration' ),
			array( &$this, 'meta_box_content_provider' ),
			'easy-opt-ins',
			'side',
			'high'
		);
		add_meta_box(
		'fca_eoi_meta_box_publish',
			__( 'Publication' ),
			array( &$this, 'meta_box_content_publish' ),
			'easy-opt-ins',
			'side',
			'high'
		);
		add_meta_box(
			'fca_eoi_meta_box_thanks',
			__( 'Messages' ),
			array( &$this, 'meta_box_content_messages' ),
			'easy-opt-ins',
			'side',
			'high'
		);
		if ( has_action( 'fca_eoi_powerups' ) ) {
			add_meta_box(
				'fca_eoi_meta_box_powerups',
				__( 'Power Ups' ),
				array( &$this, 'meta_box_content_powerups' ),
				'easy-opt-ins',
				'side',
				'high'
			);
		}
	}

	public function meta_box_content_setup() {

		$layouts_types = array(
			'lightbox' => 'Popups',
			'postbox' => 'Post Boxes',
			'widget' => 'Sidebar Widgets',
			'banner' => 'Optin Bars',
			'overlay' => 'Slide Ins',
		);
		
		// Layout tabs
		echo '<ul class="category-tabs" id="layouts_types_tabs">';
			foreach ( $layouts_types as $key => $value ) {
				echo "<li data-target='$key' >$value</li>";
			}
			
			echo '<button type="button" class="button button-primary" id="fca_eoi_layout_revert_button" style="display: none">' . __('Back', 'easy-opt-in') . '</button>';
		
		echo '</ul>';
				
		//GENERATE SCREENSHOTS / OPTIN THEME PICKER
		$layout_paths = glob( FCA_EOI_PLUGIN_DIR . "layouts/screenshots/*.png" );
		
		//OUR 'NO CSS' FRIENDS
		$layout_paths[] = 'layout_0';
		$layout_paths[] = 'postbox_0';
		
		echo '<div id="layout_previews">';
		
		foreach ( $layout_paths as $layout_path ) {
			
			$layout_id = str_replace( '.png', '', basename( $layout_path ) );
			
			$layout_helper	=	new EasyOptInsLayout( $layout_id );
			$layout_name	=	$layout_helper->layout_name();
			$layout_order	=	$layout_helper->layout_order();
			$layout_type	=	$layout_helper->layout_type;
			$screenshot_src =	$layout_helper->screenshot_src();
			
			if ( $layout_helper->layout_enabled() ) {
				echo "<div style='display:none;' class='fca_eoi_layout has-tip fca_eoi_layout_preview' data-layout-id='$layout_id' data-layout-order='$layout_order' data-layout-type='$layout_type'>";
					echo "<img src='$screenshot_src'>";
					echo "<div class='fca_eoi_layout_info'>";
						echo "<h3>$layout_name</h3>";
						echo '<button type="button" class="button button-primary button-large fca-layout-button">Select Layout</button>';
					echo "</div>";
				echo "</div>";
			} else {					
				switch ( FCA_EOI_PLUGIN_SLUG ) {
					case 'aweber-wp':
						$upgrade_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree%2BAweber&utm_medium=plugin';
						break;
					
					case 'campaign-monitor-wp':
						$upgrade_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree%2BCampaign%2BMonitor&utm_medium=plugin';
						break;
						
					case 'mailchimp-wp':
						$upgrade_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree%2BMailChimp&utm_medium=plugin';
						break;
						
					case 'getresponse-wp':
						$upgrade_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree%2BGetResponse&utm_medium=plugin';
						break;
						
					default: 
						$upgrade_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree&utm_medium=plugin';

				}
				
				echo "<div class='fca_eoi_layout has-tip fca_eoi_layout_preview layout-disabled' data-layout-id='$layout_id' data-layout-order='$layout_order' data-layout-type='$layout_type'>";
					echo "<div class='fca_eoi_layout_image_overlay'>";
						echo "<img src='$screenshot_src'>";
					echo "</div>";		
					echo "<div class='fca_eoi_layout_info'>";
						echo '<h3>' . $layout_name . ' ' . __( '(Premium Only)', 'easy-opt-in' ) . '</h3>';
						echo "<a target='_blank' href='$upgrade_link' class='button button-primary button-large fca-layout-button upgrade-link'>Upgrade Now</a>";
					echo "</div>";
				echo "</div>";		
			}
		}
		
		echo "</div>";
		echo '<br clear="all">';
		
	}

	public function meta_box_content_provider() {
		
		global $post;
		$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );
		
		//DISABLE CUSTOM FORM
		$allow_customform = get_option ( 'fca_eoi_allow_customform', 'false' );
		if ( $allow_customform == 'false' ) {
			$providers = $this->settings[ 'providers' ];
			unset ( $providers['customform'] );
			$providers_available = array_keys( $providers );
		} else {
			$providers_available = array_keys( $this->settings[ 'providers' ] );
		}		
		
		$providers_options = array();

        // Prepare providers options
		foreach ( $this->settings[ 'providers' ] as $provider_id => $provider ) {
			$providers_options[ $provider_id ] = $provider[ 'info' ][ 'name' ];
		}

		if ( $allow_customform == 'false') {
			unset ( $providers_options['customform'] );
		}
				
		// Provider choice if there are many providers
		if ( 1 < count( $providers_available) ) {

			$provider = get_option( 'fca_eoi_last_provider', '' );
			$provider = empty($provider) ? 'mailchimp' : $provider; // use mailchimp by default
		
			K::select( 'fca_eoi[provider]'
				, array( 
					'class' => 'select2',
					'style' => 'width: 27em;',
				)
				, array( 
					'format' => '<p><label>:select</label></p>',
					'options' => array( '' => 'Not set - Store Optins Locally' ) + $providers_options,
					'selected' => K::get_var( 'provider', $fca_eoi, $provider ),
				)
			);
		}

		foreach ( $providers_available as $provider ) {
			call_user_func( $provider . '_integration', $this->settings );
		}
	}
	
	//SEND JS TEMPLATE DATA FOR RULE CREATION
	public function prepare_publish_rules_html( $rules = array() ) {
				
		switch ( $this->settings['distribution'] ) {
			case 'free':
				$conditions_options = array(
					'' => __( 'Choose a rule...' ),
					'time_on_page' => __( 'Time on page' ),
				);
				ob_start();
				?>
				<tr>
				  <th><select class="fca_eoi_condition_select">
					<option selected="selected">
						Choose a rule...
					</option>

					<option value="scrolled_percent">
					  Scrolled down
					</option>
					<optgroup label="Premium Only">
						
					<option value="pageviews" disabled>
					  Number of Pageviews
					</option>

					<option value="time_on_page" disabled>
					  Time on page
					</option>

					<option value="include" disabled>
					  Only display on these pages
					</option>

					<option value="exclude" disabled>
					  Never display on these pages
					</option>

					<option value="exit_intervention" disabled>
					  Exit Intervention
					</option>
					</optgroup>
				  </select><span class='fca_eoi_at_least' style=
				  'display:none'>at&nbsp;least</span></th>

				  <td></td>

				  <td class='fca_eoi_delete_condition' title='Click to remove'><span class='fca_eoi_close_icon dashicons dashicons-no'></span></td>
				</tr>
				
				<?php
					
				$rowNew = ob_get_clean();
				break;
				
			case 'premium':
			
				$conditions_options = array(
					'' => __( 'Choose a rule...' ),
					'scrolled_percent' => __( 'Scrolled down' ),
					'pageviews' => __( 'Number of Pageviews' ),
					'time_on_page' => __( 'Time on page' ),
					'include' => __( 'Only display on these pages' ),
					'exclude' => __( 'Never display on these pages' ),
					'exit_intervention' => __( 'Exit Intervention' ),
				);
				
				$rowNew = "<tr><th>" . 
					K::select(
						'',
						array(
							'class' => 'fca_eoi_condition_select',
						),
						array(
							'options' => $conditions_options,
							'return' => true,
						)
					) . "<span class='fca_eoi_at_least' style='display:none' >at&nbsp;least</span></th><td></td><td class='fca_eoi_delete_condition' title='Click to remove'><span class='fca_eoi_close_icon dashicons dashicons-no'></span></td></tr>";
				
				break;
		}
		
		$ruleTableHtml = array (
			'rowNew' => $rowNew,

			'dataScroll' => K::input(
				'fca_eoi[publish_lightbox][conditions][scrolled_percent]',
					array(
						'type' => 'number',
						'min' => '0',
						'max' => '100',
						'value' => '30',
						'class' => '',
					),
					array(
						'return' => true,
					)
				) . '&nbsp;%',
			
			'dataPageviews' => K::input(
				'fca_eoi[publish_lightbox][conditions][pageviews]',
					array(
						'type' => 'number',
						'min' => '0',
						'value' => '2',
						'class' => '',
					),
					array(
						'return' => true,
					)
				) . '&nbsp;pageviews',
			
			'dataTime' => K::input(
				'fca_eoi[publish_lightbox][conditions][time_on_page]',
					array(
						'type' => 'number',
						'min' => '0',
						'value' => '30',
						'class' => '',
					),
					array(
						'return' => true,
					)
				) . '&nbsp;seconds',
			
			'dataExit' => K::input( 'fca_eoi[publish_lightbox][conditions][exit_intervention]',
				array(
					'type' => 'checkbox',
					'class' => 'switch-input'
				),
				array(
					'format' => '<label class="switch">:input<span class="switch-label" data-on="On" data-off="Off"></span><span class="switch-handle"></span></label>',
					'return' => true,
				)
			),
			
			'dataInclude' => k_selector( 'fca_eoi[publish_lightbox][conditions][include]', array(), true ),
			'dataExclude' => k_selector( 'fca_eoi[publish_lightbox][conditions][exclude]', array(), true ),
		);
			
		wp_localize_script( 'fca-eoi-rules', 'fcaEoiRuleTableHtml', $ruleTableHtml );
		wp_localize_script( 'fca-eoi-rules', 'fcaEoiRules', $rules  );
		wp_localize_script( 'fca-eoi-rules', 'fcaEoiDistro', array( $this->settings['distribution'] ) );
	}

	public function meta_box_content_publish( $post ) {
		
		$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );
		$fca_eoi = empty( $fca_eoi ) ? array() : $fca_eoi;
		$conditions = empty(  $fca_eoi['publish_lightbox']['conditions'] ) ? array() : $fca_eoi['publish_lightbox']['conditions'];
		$this->prepare_publish_rules_html( $conditions );

					
		K::wrap(
			sprintf(
				__( 'You can publish this optin box by going to <a href="%s" target="_blank">Appearance › Widgets</a>'),
				admin_url( 'widgets.php')
			),
			array( 'id' => 'fca_eoi_publish_widget' ),
			array( 'in' => 'p' )
		);

		// Post boxes
		echo '<div id ="fca_eoi_publish_postbox">';
			K::wrap( __( 'Shortcode'),
				array( 'style' => 'padding-left: 0px; padding-right: 0px; ' ),
				array( 'in' => 'h3' )
			);
			K::wrap( __( "Copy and paste beneath shortcode anywhere on your site where you'd like this opt-in form to appear." ),
				null,
				array( 'in' => 'p' )
			);
			K::input( '',
				array(
					'class' => 'regular-text autoselect',
					'readonly' => 'readonly',
					'value' => sprintf( '[%s id=%d]', $this->settings[ 'shortcode' ], $post->ID ),
				),
				array( 'format' => '<p>:input</p>', )
			);
			K::wrap( __( 'Append to post or page'),
				array( 'style' => 'padding-left: 0px; padding-right: 0px; ' ),
				array( 'in' => 'h3' )
			);
			K::wrap( __( 'Automatically append this optin to the following posts, categories and/or pages.' ),
				null,
				array( 'in' => 'p' )
			);
			k_selector( 'fca_eoi[publish_postbox]', K::get_var( 'publish_postbox', $fca_eoi, array() ) );
		echo '</div>';

		// Lightboxes
		

		$fca_eoi[ 'publish_lightbox' ] = K::get_var( 'publish_lightbox', $fca_eoi, array() );

			
		echo "<div id='fca_eoi_publish_lightbox'>";

			echo '<div id="publish_lightbox_mode_selector_div">';
				if ( 'premium' === $this->settings[ 'distribution' ] ) {
					
					K::input( 'fca_eoi[publish_lightbox_mode]',
						array(
							'type' => 'radio',
							'value' => 'two_step_optin',
							'checked' => 'two_step_optin' === K::get_var( 'publish_lightbox_mode', $fca_eoi ),
						),
						array(
								'format' => '<p><label>:input Two-Step Optin (Trigger popup only when the visitor clicks on a call to action)</label></p>',
							)
						);
				}

				K::input( 'fca_eoi[publish_lightbox_mode]',
					array(
						'type' => 'radio',
						'value' => 'traditional_popup',
						'checked' => 'traditional_popup' === K::get_var( 'publish_lightbox_mode', $fca_eoi ),
					),
					array(
						'format' => '<p><label>:input Traditional Popup (Trigger popup when the visitor is browsing your site)</label></p>',
					)
				);
			echo '</div>';

			echo '<div id="fca_eoi_publish_lightbox_mode_traditional_popup">';
				echo "<h3>Rules</h3>";
				
				echo "<table class='fca_eoi_display_rules_table'>";
					echo "<tr><th>Display Frequency" . fca_eoi_tooltip ( __('Set the minimum time between visits your optin will display to each user', 'easy-opt-ins') )  . "</th><td>";
						K::select(
							'fca_eoi[publish_lightbox][show_every]',
							array(),
							array(
								'options' => array(
									'always' => __( 'On every pageview' ),
									'session' => __( 'Once per visit' ),
									'day' => __( 'Once per day' ),
									'month' => __( 'Once per month' ),
									'once' => __( 'Only once' ),
								),
								'selected' => K::get_var( 'show_every', $fca_eoi[ 'publish_lightbox' ], 'month' ),
							)
						);
					echo "</td></tr>";
					
					echo "<tr><th>Devices to Display on</th><td>";
						K::select(
							'fca_eoi[publish_lightbox][devices]',
							array(),
							array(
								'options' => array(
									'desktop' => __( 'Desktop Only' ),
									'mobile' => __( 'Mobile Only' ),
									'all' => __( 'Desktop & Mobile' ),
								),
								'selected' => K::get_var( 'devices', $fca_eoi[ 'publish_lightbox' ], 'all' ),
							)
						);
					echo "</td></tr>";
					
					echo "<tr><th>Success Cookie Duration" . fca_eoi_tooltip ( __('The number of days before the optin will display again once the user successfully opts in to your campaign.', 'easy-opt-ins') ) . "</th><td>";
						K::input( 'fca_eoi[publish_lightbox][success_duration]',
							array(
								'type' => 'number',
								'class' => 'regular-text',
								'min' => 0,
								'value' => K::get_var( 'success_duration', $fca_eoi[ 'publish_lightbox' ], 365 ),
							)
						);
						
					echo " days</td></tr>";
					

				echo "</table>";
				
				echo "<table class='fca_eoi_display_rules_table' id='fca_eoi_primary_rules_table' >";
					//to be filled dynamically?
				echo "</table>";
				
				echo "<a href='#' id='fca_eoi_add_rule'>+ Add a rule</a>";
				
				echo "<h3>Go Live</h3>";
				echo "<table class='fca_eoi_display_rules_table' >";
					echo "<tr><th>Deployed (makes this optin go live)</th><td>";
						K::input( 'fca_eoi[publish_lightbox][live]',
							array(
								'type' => 'checkbox',
								'checked' => K::get_var( 'live', $fca_eoi[ 'publish_lightbox' ], '' ),
								'class' => 'switch-input'
							),
							array(
								'format' => '<label class="switch">:input<span class="switch-label" data-on="On" data-off="Off"></span><span class="switch-handle"></span></label>'
							)
						);
					echo "</td></tr>";
				echo "</table>";
			echo "</div>";
			
		
			
			if ( 'premium' === $this->settings[ 'distribution' ] ) { 
				
				echo '<div id="fca_eoi_publish_lightbox_mode_two_step_optin">';
				echo "<h3>Two-Step</h3>";
					K::input( 'fca_eoi[lightbox_cta_text]',
						array(
							'value' => K::get_var( 'lightbox_cta_text', $fca_eoi ) ? K::get_var( 'lightbox_cta_text', $fca_eoi ) : __( 'Free Download' ),
							'class' => 'regular-text',
						),
						array(
							'format' => '<p><label>Call to action text :input</label></p>',
						)
					);
					K::input( 'fca_eoi[lightbox_cta_link]',
						array(
							'readonly' => 'readonly',
							'value' => htmlspecialchars( sprintf( '<button data-optin-cat="%d">%s</button>',
								$post->ID,
								__( 'Free Download' ) ) ),
							'class' => 'regular-text autoselect',
						),
						array(
							'format' => '<p><label>Call to action link :input</label></p>',
						)
					);

					K::wrap( __( 'Add this to your post or page using a Block in the Gutenberg editor.<br> Advanced users can paste above html anywhere on their site. You can learn more <a href="https://fatcatapps.com/knowledge-base/create-two-step-optin/" target="_blank">here</a>.' ),
						array( 'class' => 'description' ),
						array( 'in' => 'p' )
					);
				echo "</div>";
			}
		echo "</div>";
	}

	private function meta_box_field( $id, $title, $controls = array() ) {
		$content = '';

		foreach ( $controls as $control ) {
			$control[2] = empty( $control[2] ) ? array() : $control[2];
			$control[3] = empty( $control[3] ) ? array() : $control[3];

			$control[3][ 'return' ] = true;

			$content .= call_user_func_array( 'K::' . $control[0], array_slice( $control, 1 ) );
		}

		$content = trim( $content );
		$class_name = 'accordion-section-primary-' . ( empty( $content ) ? 'empty' : 'full' );
		$content = '<div class="' . $class_name . '">' . $content . '</div>';
		
		$title_id = str_replace ( ' ', '_', $title );
		$title_id = strtolower  ( $title_id );
		$title_id = preg_replace('/[^a-zA-Z0-9_.]/', '', $title_id);
		
		K::wrap(
			K::wrap(
				$title . "<span class='accordion-info' id='accordion-info-$title_id'></span>",
				array( 'class' => 'accordion-section-title' ),
				array( 'return' => true )
			) .
			K::wrap(
				$content,
				array( 'class' => 'accordion-section-content' ),
				array( 'return' => true )
			),
			array(
				'class' => 'accordion-section',
				'id' => $id
			)
		);
	}
	
	public function meta_box_content_build() {
		
		global $post;
		$post_meta = get_post_meta( $post->ID, 'fca_eoi', true );
		$layout = get_post_meta( $post->ID, 'fca_eoi_layout', true );
		
		$selected_layout = empty( $layout ) ? 'lightbox_not_set' : $layout;
		$class = empty( $layout ) ? 'fca-new-layout' : '';
		echo "<input id='fca_eoi_layout_select' name='fca_eoi[layout]' value='$selected_layout' hidden readonly class='$class'>";
		//OUTPUT SELECTED OR DEFAULT TEMPLATE
		
		echo "<div id='fca_eoi_form_preview'>";
		
		$output = '<div id="fca_eoi_preview">';
		//JS WILL LOAD THE TEMPLATE
		//$output .= fca_eoi_get_layout_html( $selected_layout );

		$output .= '</div>';
		echo $output;
		/* END FORM HTML GENERATION */
		
		$providers_available = array_keys( $this->settings[ 'providers' ] );
		$providers_options = array();
		$screen = get_current_screen();

		// Prepare providers options
		foreach ($this->settings[ 'providers' ] as $provider_id => $provider ) {
			$providers_options[ $provider_id ] = $provider[ 'info' ][ 'name' ];
		}
		
		echo '<div id="fca_eoi_settings" class="accordion-container">';
		
		echo '<div id="fca_eoi_fieldset_layout">';
			echo '<span id="accordion-info-layout">Layout</span><button title="Change Layout" type="button" id="fca_eoi_layout_select_button" class="button button-secondary"><span style="margin-top: 3px; margin-right: 3px;" class="dashicons dashicons-admin-appearance"></span>Change Layout</button>';
		echo '</div>';
		
		//HIDDEN IMAGE INPUT
		$image_input = K::get_var( 'image_input', $post_meta, false );
		echo "<input type='hidden' id='image_input' name='fca_eoi[image_input]' value=$image_input ></input>";	
		
		$this->meta_box_field( 'fca_eoi_fieldset_form', 'Form', array(

			array( 'input', 'fca_eoi[toggle_overlay_position]',
				array(
					'type' => 'checkbox',
					'checked' => K::get_var( 'toggle_overlay_position', $post_meta, 'off' ) === 'off' ? '' : 'on',
					'class' => 'switch-input'
				),
				array(
					'format' => '<p id="fca_eoi_overlay_position_p"><span class="control-title">Placement</span><br><label class="switch">:input<span class="switch-label" data-on="" data-off=""></span><span class="switch-handle"></span></label></p>'
				),
			),
			array( 'input', 'fca_eoi[show_close]',
				array(
					'type' => 'checkbox',
					'checked' => K::get_var( 'show_close', $post_meta, 'on' ) === 'on' ? 'on' : '',
					'class' => 'switch-input'
				),
				array(
					'format' => '<p id="fca_eoi_close_button_p"><span class="control-title">Close Button</span><br><label class="switch">:input<span class="switch-label" data-on="Show" data-off="Hide"></span><span class="switch-handle"></span></label></p>'
				),
			),
			
			array( 'input', 'fca_eoi[push_page]',
				array(
					'type' => 'checkbox',
					'checked' => K::get_var( 'push_page', $post_meta, 'on' ) === 'on' ? 'on' : '',
					'class' => 'switch-input'
				),
				array(
					'format' => '<p id="fca_eoi_push_page_p"><span class="control-title">Push Page Down</span><br><label class="switch">:input<span class="switch-label" data-on="On" data-off="Off"></span><span class="switch-handle"></span></label></p>'
				),
			),
			
			array( 'input', "fca_eoi[offset]",
				array(
					'type' => 'number',
					'min' => '0',
					'value' => K::get_var( 'offset', $post_meta, 0 ),
					'class' => 'fca_eoi_number_input',
				),
				array(
					'format' => "<p id='fca_eoi_offset_p'><label><span class='control-title'>Offset</span></label><br />:inputpx</p>",
				)
			),

			
			$this->generate_hidden_css_select_input('form_background_color_selector'),
			$this->generate_color_picker('form_background_color','Form Background Color'),
	        $this->generate_hidden_css_select_input('form_bottom_color_selector'),
	        $this->generate_color_picker('form_bottom_color','Form Bottom Color'),
			$this->generate_hidden_css_select_input('form_border_color_selector'),
			$this->generate_color_picker('form_border_color','Border Color'),
			$this->generate_hidden_css_select_input('form_width_selector'),
			$this->generate_width_select_input('form_width','Width', 100),
			//$this->generate_hidden_css_select_input('form_alignment_selector'),
			//$this->generate_alignment_select_input('form_alignment','Alignment', 'center'),

		) );

		$this->meta_box_field( 'fca_eoi_fieldset_headline', 'Headline', array(
			array( 'input', 'fca_eoi[show_headline_field]',
				array(
					'type' => 'checkbox',
					'checked' => K::get_var( 'show_headline_field', $post_meta, 'on' ) == 'off' ? '' : 'on',
					'class' => 'switch-input'
				),
				array(
					'format' => '<p><span class="control-title">Headline Field</span><br><label class="switch">:input<span class="switch-label" data-on="Show" data-off="Hide"></span><span class="switch-handle"></span></label></p>'
				),
			),
			array( 'input', 'fca_eoi[headline_copy]',
				array( 'class' => 'fca_eoi_text_input', 'value' => K::get_var( 'headline_copy', $post_meta ) ),
				array( 'format' => '<p><label><span class="control-title">Headline Copy</span><br />:input</label></p>' )
			),
			$this->generate_hidden_css_select_input('headline_font_size_selector'),
			$this->generate_font_size_picker ('headline_font_size', 'Font Size'),
			$this->generate_hidden_css_select_input('headline_font_color_selector'),
			$this->generate_color_picker('headline_font_color','Font Color'),
			$this->generate_hidden_css_select_input('headline_background_color_selector'),
			$this->generate_color_picker('headline_background_color','Background Color'),

		) );
		
		$this->meta_box_field( 'fca_eoi_fieldset_description', 'Description', array(
			array( 'input', 'fca_eoi[show_description_field]',
				array(
					'type' => 'checkbox',
					'checked' => K::get_var( 'show_description_field', $post_meta, 'on' ) == 'off' ? '' : 'on',
					'class' => 'switch-input'
				),
				array(
					'format' => '<p><span class="control-title">Description Field</span><br><label class="switch">:input<span class="switch-label" data-on="Show" data-off="Hide"></span><span class="switch-handle"></span></label></p>'
				),
			),
			array( 'textarea', 'fca_eoi[description_copy]', array(), array(
				'format' => ':textarea',
				'editor' => true,
				'value' => K::get_var( 'description_copy', $post_meta ),
			) ),
			$this->generate_hidden_css_select_input('description_font_size_selector'),
			$this->generate_font_size_picker ('description_font_size', 'Font Size'),
			$this->generate_hidden_css_select_input('description_font_color_selector'),
			$this->generate_color_picker('description_font_color','Font Color'),
		) );

		$this->meta_box_field( 'fca_eoi_fieldset_email_field', 'Input Fields', array(
			array( 'input', 'fca_eoi[show_name_field]',
				array(
					'type' => 'checkbox',
					'checked' => K::get_var( 'show_name_field', $post_meta, 'off' ) == 'off' ? '' : 'on',
					'class' => 'switch-input'
				),
				array(
					'format' => '<p><span class="control-title">Name Field</span><br><label class="switch">:input<span class="switch-label" data-on="Show" data-off="Hide"></span><span class="switch-handle"></span></label></p>'
				),
			),
			array( 'input', 'fca_eoi[name_placeholder]',
				array( 'class' => 'fca_eoi_text_input', 'value' => K::get_var( 'name_placeholder', $post_meta, 'First Name' ) ),
				array( 'format' => '<p id="fca_eoi_name_field"><label><span class="control-title">Name Placeholder Text</span><br />:input</label></p>' )
			),
			array( 'input', 'fca_eoi[email_placeholder]',
				array( 'class' => 'fca_eoi_text_input', 'value' => K::get_var( 'email_placeholder', $post_meta, 'Your Email' ) ),
				array( 'format' => '<p><label><span class="control-title">Email Placeholder Text</span><br />:input</label></p>' )
			),
			$this->generate_hidden_css_select_input('name_font_size_selector'),
			$this->generate_font_size_picker ('name_font_size', 'Font Size'),
			$this->generate_hidden_css_select_input('name_font_color_selector'),
			$this->generate_color_picker('name_font_color','Font Color'),
			$this->generate_hidden_css_select_input('name_background_color_selector'),
			$this->generate_color_picker('name_background_color','Background Color'),
			$this->generate_hidden_css_select_input('name_border_color_selector'),
			$this->generate_color_picker('name_border_color','Border Color'),
			$this->generate_hidden_css_select_input('name_width_selector'),
			$this->generate_width_select_input('name_width','Width', 100),
			$this->generate_hidden_css_select_input('email_font_size_selector'),
			$this->generate_font_size_picker ('email_font_size', 'Font Size'),
			$this->generate_hidden_css_select_input('email_font_color_selector'),
			$this->generate_color_picker('email_font_color','Font Color'),
			$this->generate_hidden_css_select_input('email_background_color_selector'),
			$this->generate_color_picker('email_background_color','Background Color'),
			$this->generate_hidden_css_select_input('email_border_color_selector'),
			$this->generate_color_picker('email_border_color','Border Color'),
			$this->generate_hidden_css_select_input('email_width_selector'),
			$this->generate_width_select_input('email_width','Width', 100),
			//$this->generate_hidden_css_select_input('email_alignment_selector'),
			//$this->generate_alignment_select_input('email_alignment','Alignment', 'left'),
		) );

		$this->meta_box_field( 'fca_eoi_fieldset_button', 'Button', array(
			array( 'input', 'fca_eoi[button_copy]',
				array( 'class' => 'fca_eoi_text_input', 'value' => K::get_var( 'button_copy', $post_meta, 'Subscribe Now' ) ),
				array( 'format' => '<p><label><span class="control-title">Button Copy</span><br />:input</label></p>' )
			),
			$this->generate_hidden_css_select_input('button_font_size_selector'),
			$this->generate_font_size_picker ('button_font_size', 'Font Size'),
			$this->generate_hidden_css_select_input('button_font_color_selector'),
			$this->generate_color_picker('button_font_color','Font Color'),
			
			$this->generate_hidden_css_select_input('button_background_color_selector'),
			$this->generate_color_picker('button_background_color','Background Color'),
			$this->generate_hidden_css_select_input('button_wrapper_background_color_selector'),
			$this->generate_color_picker('button_wrapper_background_color','Bottom Border Color'),
			$this->generate_hidden_css_select_input('button_border_color_selector'),
			$this->generate_color_picker('button_border_color','Border Color'),
			
			$this->generate_hidden_css_select_input('button_hover_color_selector'),
			$this->generate_color_picker('button_hover_color','Hover Color'),
			
			$this->generate_hidden_css_select_input('button_width_selector'),
			$this->generate_width_select_input('button_width','Width', 100),
			//$this->generate_hidden_css_select_input('button_alignment_selector'),
			//$this->generate_alignment_select_input('button_alignment','Alignment', 'center'),
			
		) );

		$this->meta_box_field( 'fca_eoi_fieldset_privacy', 'After Button Area', array(
			array( 'input', 'fca_eoi[show_privacy_field]',
				array(
					'type' => 'checkbox',
					'checked' =>  K::get_var( 'show_privacy_field', $post_meta, 'on' ) == 'off' ? '' : 'on',
					'class' => 'switch-input'
				),
				array(
					'format' => '<p><span class="control-title">After Button Area</span><br><label class="switch">:input<span class="switch-label" data-on="Show" data-off="Hide"></span><span class="switch-handle"></span></label></p>'
				),
			),
			array( 'textarea', 'fca_eoi[privacy_copy]',
				array(
					'class' => 'large-text fca_eoi_text_input',
				),
				array(
					'format' => '<p><label><span class="control-title">After Button Area Copy</span><br />:textarea</label></p>',
					'value' => K::get_var( 'privacy_copy', $post_meta ),
				)
			),
			$this->generate_hidden_css_select_input('privacy_font_size_selector'),
			$this->generate_font_size_picker ('privacy_font_size', 'Font Size'),
			$this->generate_hidden_css_select_input('privacy_font_color_selector'),
			$this->generate_color_picker('privacy_font_color','Font Color'),
		) );
		if ( $this->settings['distribution'] === 'free' ) {
			$this->meta_box_field( 'fca_eoi_fieldset_fatcatapps', 'Branding', array(
				array( 'input', 'fca_eoi[show_fatcatapps_link]',
					array(
						'type' => 'checkbox',
						'checked' => K::get_var( 'show_fatcatapps_link', $post_meta ),
						'class' => 'switch-input'
					),
					array(
						'format' => '<p><span class="control-title"><a href="http://fatcatapps.com/" target="_blank">Optin Cat</a> Branding</span><br><label class="switch">:input<span class="switch-label" data-on="Show" data-off="Hide"></span><span class="switch-handle"></span></label></p>'
					),
				),
				
				$this->generate_hidden_css_select_input('branding_font_color_selector'),
				$this->generate_color_picker ('branding_font_color', 'Font Color'),
			) );
		}

		echo '</div>';
		echo '</div>';
		echo '<br clear="all"/>';

		
	}
	public function generate_width_select_input( $id, $name, $default = 100 ) {
		global $post;
		$post_meta = get_post_meta( $post->ID, 'fca_eoi', true );
		$value = K::get_var( "$id", $post_meta, $default );
		$units = K::get_var( "$id" . "-units", $post_meta, '%' );
		
		$px_selected = $units == 'px' ? 'selected' : '';
		$pct_selected = $units == '%' ? 'selected' : '';
		
		return array( 'input', "fca_eoi[$id]",
			array(
				'type' => 'number',
				'min' => '0',
				'value' => $value,
				'class' => 'fca_eoi_width_input',
			),
			array(
				'format' => "<p><label><span class='control-title'>$name</span></label><br />:input <select class='fca_eoi_width_units_select' name='fca_eoi[$id-units]'><option value='%' $pct_selected>%</option><option value='px' $px_selected>px</option></select></p>",
				
			)
		);
	}
	
	public function generate_alignment_select_input( $id, $name, $default ) {
		global $post;
		$post_meta = get_post_meta( $post->ID, 'fca_eoi', true );
		$value = K::get_var( "$id", $post_meta, $default );

		return array( 'input', "fca_eoi[$id]",
			array(
				'type' => 'hidden',
				'value' => $value,
				'class' => 'fca_eoi_alignment_input',
			),
			array(
				'format' => "<p><label><span class='control-title'>$name</span></label><br><span data-value='left' class='dashicons dashicons-editor-alignleft fca-eoi-align-button fca-eoi-align-left-btn'></span><span data-value='center' class='dashicons dashicons-editor-aligncenter fca-eoi-align-button fca-eoi-align-center-btn'></span><span data-value='right' class='dashicons dashicons-editor-alignright fca-eoi-align-button fca-eoi-align-right-btn'></span>:input</p>",
			)
		);
	}
	
	public function generate_hidden_css_select_input ($id) {
		return array( 'input', "fca_eoi[$id]",
			array (	'class' => 'fca-hidden-input hidden',
					'value' => ''
				)
		);
	}
	
	public function generate_color_picker ($id, $name) {
		global $post;
		$post_meta = get_post_meta( $post->ID, 'fca_eoi', true );
		return array( 'input', "fca_eoi[$id]",
			array (	'class' => 'fca-color-picker',
					'value' => K::get_var( "$id", $post_meta )
				),
			array( 
				'format' => '<p><span class="control-title">'. $name . '</span><br /><label>:input</label></p>',
			)
		);
	}
	
	public function generate_font_size_picker ($id, $name) {
		global $post;
		$post_meta = get_post_meta( $post->ID, 'fca_eoi', true );
		return array( 'select', "fca_eoi[$id]",
		
			array (
				'data-selected' => K::get_var( "$id", $post_meta ),
				'class' => 'fca-font-size-picker ',
				),
			array( 
			'format' => '<p class="clear"><label><span class="control-title">'. $name . '</span><br />:select</label></p>',
			'options' => array(
					'none' => '',
					'7px' => '7px',
					'8px' => '8px',
					'9px' => '9px',
					'10px' => '10px',
					'11px' => '11px',
					'12px' => '12px',
					'13px' => '13px',
					'14px' => '14px',
					'15px' => '15px',
					'16px' => '16px',
					'17px' => '17px',
					'18px' => '18px',
					'19px' => '19px',
					'20px' => '20px',
					'21px' => '21px',
					'22px' => '22px',
					'23px' => '23px',
					'24px' => '24px',
					'25px' => '25px',
					'26px' => '26px',
					'27px' => '27px',
					'28px' => '28px',
					'29px' => '29px',
					'30px' => '30px',
					'31px' => '31px',
					'32px' => '32px',
					'33px' => '33px',
					'34px' => '34px',
					'35px' => '35px',
					'36px' => '36px',
				),
				'selected' => K::get_var( "$id", $post_meta ),
				'return' => true,
			)
		);
	}
	
	public function meta_box_content_messages( $post ) {

		$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );
		$screen = get_current_screen();
		
		// Get the previous thank you page if this is a new post
		$last_form_meta = get_option( 'fca_eoi_last_form_meta', '' );
		$thank_you_page_suggestion = empty ($last_form_meta['thank_you_page']) ? '~' : $last_form_meta['thank_you_page'];
		$thank_you_mode_suggestion = empty ($last_form_meta['thankyou_page_mode']) ? 'ajax' : $last_form_meta['thankyou_page_mode'];
		$thank_you_text_suggestion = empty ($last_form_meta['thankyou_ajax']) ?  __('Thank you! Please check your inbox for your confirmation email.', 'easy-opt-ins') : stripslashes( $last_form_meta['thankyou_ajax'] );
		$thank_you_text_color = empty ($last_form_meta['thank_you_text_color']) ? '#fff' : $last_form_meta['thank_you_text_color'];
		$thank_you_bg_color = empty ($last_form_meta['thank_you_bg_color']) ? '#00b894' : $last_form_meta['thank_you_bg_color'] ;
		$subscribing_suggestion = empty ($last_form_meta['subscribing_message']) ? __('Subscribing...', 'easy-opt-ins') : stripslashes( $last_form_meta['subscribing_message'] );
		$text_error_suggestion = empty ($last_form_meta['error_text_field_required']) ? __('Please fill out this field to continue', 'easy-opt-ins') : stripslashes( $last_form_meta['error_text_field_required'] );
		$email_error_suggestion = empty ($last_form_meta['error_text_invalid_email']) ? __('Please enter a valid email address. For example "example@example.com".', 'easy-opt-ins') : stripslashes( $last_form_meta['error_text_invalid_email'] );
		$email_error_text_color = empty ($last_form_meta['email_error_text_color']) ? '#fff' : $last_form_meta['email_error_text_color'];
		$email_error_bg_color = empty ($last_form_meta['email_error_bg_color']) ? '#d63031' : $last_form_meta['email_error_bg_color'] ;

		echo '<h3>' . __('Subscribing Message', 'easy-opt-ins') . fca_eoi_tooltip ( __('This message will be displayed after your visitors submit your form.', 'easy-opt-ins') ) . '</h3>';
		echo '<table class="fca_eoi_text_messages_table">';
		echo '<tr><th>Message</th><td>';
			K::input( 'fca_eoi[subscribing_message]',
				array(
					'class' => 'regular-text',
					'value' => K::get_var( 'subscribing_message', $fca_eoi, $subscribing_suggestion ),
				),
				array()
			);

		echo '</td></tr></table>';
		
		echo '<h3>' . __('Thank You Message', 'easy-opt-ins') . fca_eoi_tooltip( __('This message will be displayed after someone has successfully subscribed.', 'easy-opt-ins') ) . '</h3>';
		echo '<table class="fca_eoi_text_messages_table">';
		echo '<tr><th>Behavior</th><td>';
			_e('Display immediately', 'easy-opt-ins');
			$checked = K::get_var( 'thankyou_page_mode', $fca_eoi, $thank_you_mode_suggestion );
			
			K::input( 'fca_eoi[thankyou_page_mode]',
				array(
					'type' => 'checkbox',
					'value' => 'redirect',
					'checked' => 'redirect' === $checked,
					'class' => 'switch-input fca-eoi-redirect-mode-toggle'
				),
				array(
					'format' => '<label class="switch" id="fca-eoi-redirect-mode-toggle-label" >:input<span class="switch-label" data-on="" data-off=""></span><span class="switch-handle"></span></label>'
				)
			);
			_e('Redirect', 'easy-opt-ins');	

		echo '</td></tr>';
		
		echo '<tr id="fca_eoi_thankyou_ajax_msg"><th>Thank You Message</th><td>';

			K::textarea( 'fca_eoi[thankyou_ajax]', array(
					'class' => 'large-text fca_eoi_text_input',
				),
				array(
					'value' => K::get_var( 'thankyou_ajax', $fca_eoi, $thank_you_text_suggestion ),
				)
			);

		echo '</td></tr>';
		
		echo '<tr id="fca_eoi_thank_you_text_color"><th>Text color</th><td>';

			K::input( 'fca_eoi[thank_you_text_color]', array(
					'class' => 'fca-color-picker',
					'value' => K::get_var( 'thank_you_text_color', $fca_eoi, $thank_you_text_color ),
				)
			);

		echo '</td></tr>';

		echo '<tr id="fca_eoi_thank_you_bg_color"><th>Background color</th><td>';

			K::input( 'fca_eoi[thank_you_bg_color]', array(
					'class' => 'fca-color-picker',
					'value' => K::get_var( 'thank_you_bg_color', $fca_eoi, $thank_you_bg_color ),
				)
			);

		echo '</td></tr>';
		
		echo '<tr id="fca_eoi_thankyou_redirect"><th>Redirect to';
		
				K::select(
					'fca_eoi[redirect_page_mode]',
					array(
						'class' => 'fca-eoi-redirect-page-toggle',
					),
					array(
						'options' => array(
							'page' => __( 'Page' ),
							'url' => __( 'URL' ),
							
						),
						'selected' => K::get_var( 'redirect_page_mode', $fca_eoi, 'page' ),
					)
				);
				
			
			echo '</th><td>';
			
			echo '<span id="fca_eoi_redirect_page_span">';
			$pages = array( '~' => __( 'Front page' ) );
			$pages_objects = get_pages();
			foreach ( $pages_objects as $page_obj ) {
				$pages[ $page_obj->ID ] = $page_obj->post_title;
			}
			K::select( 'fca_eoi[thank_you_page]',
				array( 
					'class' => 'select2',
					'style' => 'width: 27em;',
				),
				array( 
					'format' => '<p><label>:select</label></p>',
					'options' => $pages,
					'selected' => 'add' === $screen->action
						? $thank_you_page_suggestion
						: K::get_var( 'thank_you_page', $fca_eoi, '~' ),
				)
			);
			echo '</span>';
			echo '<span id="fca_eoi_redirect_url_span">';
			K::input( 'fca_eoi[thank_you_url]',
				array(
					'class' => 'regular-text',
					'value' => K::get_var( 'thank_you_url', $fca_eoi ),
					'placeholder' => 'enter URL here',
				),
				array()
			);
			echo '</span>';
		echo '</td></tr>';
		
		echo '</table>';
		
		echo '<h3>' . __('Error Messages', 'easy-opt-ins') . fca_eoi_tooltip( __("These messages will be displayed if someone fills out the form incorrectly.", 'easy-opt-ins') ) . '</h3>';
		echo '<table class="fca_eoi_text_messages_table">';
		echo '<tr><th>Field Required</th><td>';

			K::textarea( 'fca_eoi[error_text_field_required]',
				array(
					'class' => 'large-text fca_eoi_text_input',
				),
				array(
					'value' => K::get_var( 'error_text_field_required', $fca_eoi, $text_error_suggestion ),
				)
			);

		echo '</td></tr>';
		
		echo '<tr><th>Invalid Email</th><td>';

			K::textarea( 'fca_eoi[error_text_invalid_email]',
				array(
					'class' => 'large-text fca_eoi_text_input',
				),
				array(
					'value' => K::get_var( 'error_text_invalid_email', $fca_eoi, $email_error_suggestion )
				)
			);

		echo '</td></tr>';

		echo '<tr id="fca_eoi_email_error_text_color"><th>Text color</th><td>';

			K::input( 'fca_eoi[email_error_text_color]', array(
					'class' => 'fca-color-picker',
					'value' => K::get_var( 'email_error_text_color', $fca_eoi, $email_error_text_color ),
				)
			);

		echo '</td></tr>';

		echo '<tr id="fca_eoi_email_error_bg_color"><th>Background color</th><td>';

			K::input( 'fca_eoi[email_error_bg_color]', array(
					'class' => 'fca-color-picker',
					'value' => K::get_var( 'email_error_bg_color', $fca_eoi, $email_error_bg_color ),
				)
			);

		echo '</td></tr>';
		
		echo '</table>';
	}

	public function meta_box_content_powerups() {

		global $post;
		$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );
		do_action('fca_eoi_powerups', $fca_eoi ); 
	}

	/**
	 * Save the Metabox Data
	 */
	public function save_meta_box_content( $post_id, $post ) {
		
		if ( isset ($_POST['fca_eoi'])) {
			$form_id = $post_id;
			$meta = $_POST['fca_eoi'];
			//SET SHOW/HIDE SETTINGS
			$toggles = array (
				'show_headline_field',
				'show_description_field',
				'show_name_field',
				'show_close',
				'show_privacy_field',
				'push_page',
			);
			
			forEach ( $toggles as $field ) {
				$meta[$field] = empty ( $meta[$field] ) ? 'off' : 'on';				
			}
						
			$settings = array (
				'form_background_color_selector' => 'background-color',
				'form_border_color_selector' => 'border-color',
				'headline_font_size_selector' => 'font-size',
				'headline_font_color_selector' => 'color',
				'headline_background_color_selector' => 'background-color',
				'description_font_size_selector' => 'font-size',
				'description_font_color_selector' => 'color',
				'name_font_size_selector' => 'font-size',
				'name_font_color_selector' => 'color',
				'name_background_color_selector' => 'background-color',
				'name_border_color_selector' => 'border-color',
				'email_font_size_selector' => 'font-size',
				'email_font_color_selector' => 'color',
				'email_background_color_selector' => 'background-color',
				'email_border_color_selector' => 'border-color',
				'button_font_size_selector' => 'font-size',
				'button_font_color_selector' => 'color',
				'button_background_color_selector' => 'background-color',
				'button_border_color_selector' => 'border-color',
				// 'button_hover_color_selector' => 'background-color', special case
				'button_wrapper_background_color_selector' => 'background-color',
				'privacy_font_size_selector' => 'font-size',
				'privacy_font_color_selector' => 'color',
				'branding_font_color_selector' => 'color',		
			);
			
			// Add provider if missing (happens on free distros where there is only one provider)
			if( ! K::get_var( 'provider', $meta ) ) {
				$meta[ 'provider' ] = $this->settings[ 'provider' ];
			}
			
			// Keep only the current providers settings, Remove all [provider]_[setting] not belonging to the current provider
			$provider = K::get_var( 'provider', $meta );
			if( $provider ) {
				$providers = array_keys( $this->settings[ 'providers' ] );
				$other_providers = array_values( array_diff( $providers, array( $provider ) ) );
				foreach ( $meta as $k => $v ) {
					$p = explode( '_', $k );
					$k_1 = array_shift( $p );
					if( in_array( $k_1, $other_providers ) ) {
						unset( $meta[ $k ] );
					}
				}

				foreach ( $_POST as $k => $v ) {
					if ( strpos( $k, 'fca_eoi_' . $provider . '_' ) === 0 ) {
						delete_post_meta( $post->ID, $k );
						add_post_meta( $post->ID, $k, $v );
						$meta[ substr($k, 8) ] = $v;
					}
				}
			}

			// Make sure empty value for publish_postbox or publish_lightbox are saved as array(-1)
			if( ! K::get_var( 'publish_postbox' , $meta, array() ) ) {
				$meta[ 'publish_postbox' ] = array(-1);
			}

			if( ! K::get_var( 'publish_lightbox' , $meta, array() ) ) {
				$meta[ 'publish_lightbox' ] = array(-1);
			}

			//sanitize thank you ajax message
			if ( !empty ( $meta[ 'thankyou_ajax' ] ) ) {
				$meta[ 'thankyou_ajax' ] = htmlentities($meta[ 'thankyou_ajax' ], ENT_QUOTES, "UTF-8");
			}
						
			//RN NOTE: THIS DO ANYTHING? -> ONLY FOR CUSTOM HTML FORMS SEEMS LIKE
			$on_save_function = $provider . '_on_save';
			if ( function_exists( $on_save_function ) ) {
				$meta = $on_save_function( $meta );
			}
			
			//COMPILE CSS AND SAVE INTO 'HEAD' META
			$layout_id = $meta[ 'layout' ];
			
			// General CSS for all forms
			$css = "<style type='text/css' class='fca-eoi-style'>.fca_eoi_form{ margin: auto; } .fca_eoi_form p { width: auto; } #fca_eoi_form_$form_id input{ max-width: 9999px; }";

			if ( !empty( $layout_id ) ) {
				
				// CACHE (ALMOST) ALL THE OUTPUT HERE
				$layout    = new EasyOptInsLayout( $layout_id );
				$scss_path = $layout->path_to_resource( 'layout', 'scss' );
				
				if ( file_exists( $scss_path ) ) {
					$css_path = str_replace ( '.scss' , '_min.css', $scss_path );
					$css_file = file_get_contents( $css_path );
				}
				
				$show_name = K::get_var( 'show_name_field', $meta, 'off' );
				if ( $show_name === 'off' ) {
					$css .= "#fca_eoi_form_$form_id .fca_eoi_layout_name_field_wrapper {display: none !important;}";
				}

				$show_headline_field = K::get_var( 'show_headline_field', $meta, 'on' );
				if ( $show_headline_field === 'off' ) {
					$css .= "#fca_eoi_form_$form_id .fca_eoi_layout_headline_copy_wrapper {display: none !important;}";
				}
				
				$show_description_field = K::get_var( 'show_description_field', $meta, 'on' );
				if ( $show_description_field === 'off' ) {
					$css .= "#fca_eoi_form_$form_id .fca_eoi_layout_description_copy_wrapper {display: none !important;}";
				}
				
				$show_close = K::get_var( 'show_close', $meta, 'on' );
				if ( $show_close === 'off' ) { 
					$css .= "#fca_eoi_form_$form_id .fca_eoi_banner_close_btn {display: none !important;}";
				}
				
				$email_error_text_color = K::get_var( 'email_error_text_color', $meta, '#fff' );
					$css .= ".tooltipster-sidetip.tooltipster-borderless.tooltipster-optin-cat .tooltipster-box .tooltipster-content { color: $email_error_text_color }";

				$email_error_bg_color = K::get_var( 'email_error_bg_color', $meta, '#d63031' );
					$css .= ".tooltipster-optin-cat.tooltipster-sidetip.tooltipster-top .tooltipster-arrow-border { border-top-color: $email_error_bg_color !important }";
					$css .= ".tooltipster-optin-cat.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow-border { border-bottom-color: $email_error_bg_color !important }";
					$css .= ".tooltipster-sidetip.tooltipster-borderless.tooltipster-optin-cat .tooltipster-box { background-color: $email_error_bg_color !important }";

				$form_bottom_color = K::get_var( 'form_bottom_color', $meta, '#3b3b3b' );
					$css .= ".fca_eoi_layout_inputs_wrapper { background-color: $form_bottom_color !important }";

				$toggle_position = K::get_var( 'toggle_overlay_position', $meta, 'off' );
				$offset = K::get_var( 'offset', $meta, 0 ) . 'px';
				
				if ( $toggle_position === 'on' && strrpos( $layout_id, 'overlay' ) !== false  ) {
					$css .= "#fca_eoi_form_$form_id .fca_eoi_layout_overlay  { left: $offset !important; right: initial !important; }";
				} else if ( strrpos( $layout_id, 'overlay' ) !== false ) {
					$css .= "#fca_eoi_form_$form_id .fca_eoi_layout_overlay  { right: $offset !important; }";
				}
				if ( $toggle_position === 'on' && strrpos( $layout_id, 'banner' ) !== false  ) { 
					$css .= "#fca_eoi_form_$form_id .fca_eoi_layout_banner  { bottom: $offset !important; top: inherit !important; }";
				} else if ( strrpos( $layout_id, 'banner' ) !== false ) {
					$css .= "#fca_eoi_form_$form_id .fca_eoi_layout_banner  { top: $offset !important; }";
				}

				//VENDOR PREFIXED PLACEHOLDER COLORS
				$placeholder_color = K::get_var( 'email_font_color', $meta, '#000000' );
				$css .= "#fca_eoi_form_$form_id .fca_eoi_form_input_element::-webkit-input-placeholder {opacity:0.6;color:$placeholder_color;}";
				$css .= "#fca_eoi_form_$form_id .fca_eoi_form_input_element::-moz-placeholder {opacity:0.6;color:$placeholder_color;}";
				$css .= "#fca_eoi_form_$form_id .fca_eoi_form_input_element:-ms-input-placeholder {opacity:0.6;color:$placeholder_color;}";
				$css .= "#fca_eoi_form_$form_id .fca_eoi_form_input_element:-moz-placeholder {opacity:0.6;color:$placeholder_color;}";
				
				//HOVER 
				$hover_color = K::get_var( 'button_hover_color', $meta, 'initial' );
				$selector = K::get_var( 'button_hover_color_selector', $meta, '' );
				
				$s1 = str_replace ( ' input', ':hover', $selector );
				$s2 = str_replace ( ' input', ' input:hover', $selector );
				
				$css .= "#fca_eoi_form_$form_id $s1, #fca_eoi_form_$form_id $s2 {background-color:$hover_color !important;}";
				
				//WIDTHS
				$width_selects = array(
					'form_width',
					'name_width',
					'email_width',
					'button_width',
				);
				
				forEach ( $width_selects as $w ) {
					$width = K::get_var( $w, $meta, '100' );
					$units = K::get_var( "$w-units", $meta, '%' );
					$selector = K::get_var( $w . "_selector", $meta, '' );
					$css .= "
						#fca_eoi_form_$form_id $selector {
							width:$width$units;
						}
						@media screen and ( max-width: $width$units ) {
								#fca_eoi_form_$form_id $selector {
								width:100%;
							}
						}
					";
					
				}

				//ADD CSS FROM FILE
				$css .= $css_file;
				
				//ADD CUSTOM CSS FROM SAVE
				
				$added_widget_3_css_rule = false;
				
				foreach ( $settings as $key => $property ) {
					$selector = empty ( $meta[$key] ) ? '' : $meta[$key];
					$input = str_replace ( '_selector', '', $key);
					
					if ( !empty ( $selector ) ) {
						//SPECIAL CASE FOR BUTTON BORDER
						if ( $key === 'button_wrapper_background_color_selector' ) {
							$selector = str_replace ( 'input', '', $selector );
						}
						//SPECIAL CASE FOR WIDGET 3
						if ( $selector == '.fca_eoi_layout_3.fca_eoi_layout_widget div.fca_eoi_layout_headline_copy_wrapper div' && !$added_widget_3_css_rule && $input == 'headline_background_color' ) {
							$css .= "#fca_eoi_form_$form_id form.fca_eoi_layout_3.fca_eoi_layout_widget svg.fca_eoi_layout_headline_copy_triangle { fill: $meta[$input] !important; }";
							$added_widget_3_css_rule = true;
						}
								
						$css .= "#fca_eoi_form_$form_id $selector {	$property: $meta[$input] !important; }";
						
					}
				}
				
				$animation  = isset( $_POST['fca_eoi_animations'] ) && isset( $_POST['fca_eoi_show_animation_checkbox'] ) ? $_POST['fca_eoi_animations'] : '';
				update_post_meta( $post->ID, 'fca_eoi_animation', $animation );
					
				$head = $css . '</style>';
				
				$html = fca_eoi_get_html( $form_id, $meta );
				
				$head = $head . $html;
				$meta[ 'post_id' ] = $post_id;
				
				//format conditions
				$new_conditions = array();
				$conditions = empty ( $meta['publish_lightbox']['conditions'] ) ? array() : $meta['publish_lightbox']['conditions'];
				
				forEach ( $conditions as $key => $value ) {
					$new_conditions[] = array ( 
						'parameter' => $key,
						'value' => $value,
					);
				}
				$meta['publish_lightbox']['conditions'] = $new_conditions;
				
				//SET THE LIVE TO 'FALSE' FOR BACKWARD COMPATIBILITY
				$meta['publish_lightbox']['live'] = empty ( $meta['publish_lightbox']['live'] ) ? false : true;
				
				update_option( 'fca_eoi_last_provider', $meta[ 'provider' ] );
				update_option( 'fca_eoi_last_form_meta', $meta );
				update_post_meta( $post->ID, 'fca_eoi_meta_format', '2.0' );
				update_post_meta( $post->ID, 'fca_eoi', $meta );
				update_post_meta( $post->ID, 'fca_eoi_layout', $meta[ 'layout' ] );
				update_post_meta( $post->ID, 'fca_eoi_provider', $meta[ 'provider' ] );
				update_post_meta( $post->ID, 'fca_eoi_head', $head );
				
			}
		}
	}

	public function live_preview( $content ) {
		global $post;
		if (get_post_type() == 'easy-opt-ins' && is_main_query()) {
			$shortcode = sprintf( '[%s id=%d]', $this->settings[ 'shortcode' ], $post->ID );
			return do_shortcode($shortcode);
		} else {
			return $content;
		}
	}

	public function admin_enqueue() {

		$provider = $this->settings[ 'provider' ];
		$providers_available = array_keys( $this->settings[ 'providers' ] );
		
		/**
		 * Disable autosaving optin forms since it causes data loss
		 */
		if ( 'easy-opt-ins' == get_post_type() ) {
			wp_dequeue_script( 'autosave' );
		}

		$screen = get_current_screen();
		if( 'easy-opt-ins' === $screen->id ){
			global $post;
			$meta = get_post_meta($post->ID, 'fca_eoi', true );
			$options = get_option( 'fca_eoi_settings' );
			//LOAD DEPENDENCIES
			wp_enqueue_media();	
			wp_enqueue_script( 'fca_eoi_tooltipster', FCA_EOI_PLUGIN_URL . '/assets/vendor/tooltipster/tooltipster.bundle.min.js', array(), FCA_EOI_VER, true );
			wp_enqueue_style( 'fca_eoi_tooltipster_css', FCA_EOI_PLUGIN_URL . '/assets/vendor/tooltipster/tooltipster.bundle.min.css', array(), FCA_EOI_VER );
			wp_enqueue_style( 'fca_eoi_tooltipster_theme_css', FCA_EOI_PLUGIN_URL . '/assets/vendor/tooltipster/tooltipster-borderless.min.css', array(), FCA_EOI_VER );
			
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.js', array(), FCA_EOI_VER, true );
			wp_enqueue_style( 'fca-eoi-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css', array(), FCA_EOI_VER );
			wp_enqueue_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.css', array(), FCA_EOI_VER );
			wp_enqueue_script( 'accordion' );
			
			if ( !empty( $options['animation'] ) ) {
				wp_enqueue_style( 'fca_eoi_powerups_animate', FCA_EOI_PLUGIN_URL . '/assets/vendor/animate/animate.min.css', array(), FCA_EOI_VER );
			}
						
			//LOAD CUSTOM AJAX SPINNER CODE/CSS
			wp_enqueue_script( 'fca-eoi-ajax-spinner', FCA_EOI_PLUGIN_URL . '/assets/admin/fca-eoi-ajax-spinner.min.js', array(), FCA_EOI_VER, true );
			wp_enqueue_style( 'fca-eoi-ajax-spinner', FCA_EOI_PLUGIN_URL . '/assets/admin/fca-eoi-ajax-spinner.min.css', array(), FCA_EOI_VER );
			
			//LOAD COMMON CSS
			wp_enqueue_style( 'fca-eoi-common-css', FCA_EOI_PLUGIN_URL .'/assets/style-new.min.css', array(), FCA_EOI_VER );
			
			//LOAD EDITOR JS
			wp_enqueue_script( 'fca-eoi-editor', FCA_EOI_PLUGIN_URL . '/assets/admin/fca-eoi-editor.js', array('jquery', 'fca_eoi_tooltipster', 'wp-color-picker', 'select2', 'accordion' ), FCA_EOI_VER, true );
			wp_enqueue_script( 'fca-eoi-rules', FCA_EOI_PLUGIN_URL . '/assets/admin/fca-eoi-rules.min.js', array('jquery' ), FCA_EOI_VER, true );
			
			//LOAD PROVIDER JS AND CSS
			foreach ( $providers_available as $provider ) {
				wp_enqueue_script( 'admin-cpt-easy-opt-ins-' . $provider, FCA_EOI_PLUGIN_URL . '/providers/' . $provider . '/cpt-easy-opt-ins.min.js', array(), FCA_EOI_VER, true );

				$css_path = '/providers/' . $provider . '/cpt-easy-opt-ins.min.css';
				if ( is_readable( FCA_EOI_PLUGIN_DIR . $css_path ) ) {
					wp_enqueue_style( 'admin-cpt-easy-opt-ins-' . $provider, FCA_EOI_PLUGIN_URL . $css_path, array(), FCA_EOI_VER );
				}
			}
			//SEND VARIABLES TO JS
			$useGroups = empty ( $options['mailchimp_groups'] ) ? array('off') : array('on') ;
			wp_localize_script( 'admin-cpt-easy-opt-ins-mailchimp', 'fcaEoiUseGroups', $useGroups );

			$tags = empty($meta['aweber_tags']) ? '' : $meta['aweber_tags'];
			$aweberSettings = array(
				'tags' => $tags,
			);
			wp_localize_script( 'admin-cpt-easy-opt-ins-aweber', 'fcaEoiAweberSettings', $aweberSettings );
			
			$file = plugin_dir_path( __FILE__ ) . "layout-cache.json";
			$layout_data = json_decode( file_get_contents($file), true ); 
			
			wp_localize_script( 'fca-eoi-editor', 'fcaEoiLayouts',  $layout_data );
			
			//EDITOR CSS
			wp_enqueue_style( 'fca-eoi-editor-css', FCA_EOI_PLUGIN_URL . '/assets/admin/fca-eoi-editor.min.css', array(), FCA_EOI_VER );
						
			if ( has_action( 'fca_eoi_powerups' ) ) {
				wp_enqueue_script('fca_eoi_powerups', FCA_EOI_PLUGIN_URL . '/assets/powerups/fca_eoi_powerups.min.js', array(), FCA_EOI_VER, true);
			}
		}
		if( 'widgets' === $screen->id ){
			wp_enqueue_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.js', array(), FCA_EOI_VER, true );
			wp_enqueue_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.css', array(), FCA_EOI_VER );
		}
	}

	/**
	 * Hides minor publising form items (status, visibility and publication date)
	 *
	 * This function shoud be used along with force_published to prevent
	 * saving posts as drafts
	 */
	public function hide_minor_publishing() {
		$screen = get_current_screen();
		if( in_array( $screen->id, array( 'easy-opt-ins' ) ) ) {
			echo '<style>#minor-publishing { display: none; }</style>';
		}
	}
	
	public function onboard_help() {

		$current_screen = get_current_screen();

		// Exit function if we are not on the opt-in list
		if ( $current_screen->id === 'edit-easy-opt-ins' ) {
			$posts = get_posts( 'posts_per_page=-1&post_type=easy-opt-ins' );
			
			if ( empty ( $posts ) ) {
				
				wp_enqueue_style( 'fca_eoi_onboard_stylesheet', FCA_EOI_PLUGIN_URL . '/assets/admin/onboard.min.css', array(), FCA_EOI_VER );
				
				echo '<div class="error fca_eoi_onboard_div">';
				
				echo '<img id="fca_eoi_onboard_text" src="' . FCA_EOI_PLUGIN_URL . '/assets/admin/onboarding-text.png' . '">';	
				echo '<img id="fca_eoi_onboard_arrow" src="' . FCA_EOI_PLUGIN_URL . '/assets/admin/onboarding-arrow.png' . '">';
				echo '</div>';
			}
		}
	}
	
	public function review_notice() {
		
		$dismissed = get_option ( 'fca_eoi_dismiss_review' );
		if( !current_user_can( 'manage_options' ) ) {
			return;
		} 
		
		if ( $dismissed !== 'true' ) {
			$activity = EasyOptInsActivity::get_instance();
			$stats = $activity->get_form_stats( $this->activity_day_interval['form_list'] );
			$conversions = empty ( $stats['conversions'] ) ? array() : $stats['conversions'];
			$conversions = array_sum ( $conversions );
			
			if ( $conversions >= 25 ) {
				$review_link =  'https://wordpress.org/support/plugin/' . FCA_EOI_PLUGIN_SLUG . '/reviews/?rate=5#new-post';

				wp_enqueue_script( 'fca_eoi_dismiss_review_js', FCA_EOI_PLUGIN_URL . '/assets/admin/dismiss.min.js', array(), FCA_EOI_VER );
				wp_localize_script( 'fca_eoi_dismiss_review_js', 'fcaEoiDismiss', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),	'nonce' =>  wp_create_nonce( 'fca_eoi_dismiss' ) ) );
				
				echo '<div class="notice notice-success fca_eoi_review_div">';
					echo '<img style="float:left" width="120" height="120" src="' . FCA_EOI_PLUGIN_URL . '/assets/admin/optincat.png' . '">';
					echo '<p><strong>' . __( "Great work! You've gotten more than 25 email subscribers using Optin Cat.", 'easy-opt-ins' ) . '</strong></p>';
					
					echo '<p>' . sprintf( __( "If you love Optin Cat, why not leave us a nice review on %sWordPress.org%s? Reviews keeps us motivated - we'd really appreciate it.", 'easy-opt-ins' ), "<a target='_blank' href='$review_link'>", '</a>') . '</p>';
					echo '<br>';
					
					echo "<a target='_blank' href='$review_link' class='button button-primary'>" . __( 'Leave a Review', 'easy-opt-ins') . "</a> ";
					echo "<button type='button' class='button button-secondary' data-option='fca_eoi_dismiss_review' id='fca-eoi-dismiss-review-btn'>" . __( 'Dismiss', 'easy-opt-ins') . "</button>";
					echo '<br style="clear:both">';
				echo '</div>';
				
			}
		}
	}
	
	public function ajax_dismiss_notice() {
		
		if( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		
		$nonce = empty ( $_REQUEST['nonce'] ) ? '' : sanitize_text_field( $_REQUEST['nonce'] );
		
		if ( wp_verify_nonce ( $nonce, 'fca_eoi_dismiss' ) == 1 ) {
			if ( update_option( 'fca_eoi_dismiss_review', 'true' ) ) {
				wp_send_json_success();
			}
		}
		wp_send_json_error();
	}

	/**
	 * Forces one column
	 */
	public function force_one_column() {
		
		return 1;
	}

	/**
	 * Sort metaboxes
	 */
	public function order_columns( $order ) {
		return array(
			'normal' => join( ",", array(
				'submitdiv',
				'fca_eoi_meta_box_nav',
				'fca_eoi_meta_box_setup',
				'fca_eoi_meta_box_build',
				'fca_eoi_meta_box_provider',
				'fca_eoi_meta_box_thanks',
				'fca_eoi_meta_box_publish',
				'fca_eoi_meta_box_powerups',
				'fca_eoi_meta_box_debug',
			) ),
			'side' => '',
			'advanced' => '',
		);
	}

	/**
	 * replacing the default "Enter title here" placeholder text in the title input box to 
	 * 
	 */
	public function change_default_title($title) {

		$screen = get_current_screen();

		if ( 'easy-opt-ins' == $screen->post_type ) {
			$title = 'Enter name here';
		}

		return $title;
	}

	/**
	 * Override some strings to match our likings
	 */
	public function override_text( $messages ) {
		
		global $post;

        $post_ID = $post->ID;
        $post_type = get_post_type( $post_ID );

        $obj = get_post_type_object( $post_type );
        $singular = $obj->labels->singular_name;
		
		if ( 'easy-opt-ins' === $post->post_type ) {

		        $messages[$post_type] = array(
                0 => '', // Unused. Messages start at index 1.
                1 => __( 'Opt-In Form updated.' ),
                2 => __( 'Opt-In Form updated.' ),
                3 => __( 'Opt-In Form deleted.' ),
                4 => __( 'Opt-In Form updated.' ),
                5 => isset( $_GET['revision']) ? sprintf( __('%2$s restored to revision from %1$s' ), wp_post_revision_title( (int) $_GET['revision'], false ), esc_attr( $singular ) ) : false,
                6 => __( 'Opt-In Form saved.' ),
                7 => sprintf( __( '%s saved.' ), esc_attr( $singular ) ),
                8 => sprintf( __( '%s submitted. <a href="%s" target="_blank">Preview %s</a>'), $singular, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), strtolower( $singular ) ),
                9 => sprintf( __( '%s scheduled for: <strong>%s</strong>. <a href="%s" target="_blank">Preview %s</a>' ), $singular, date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ), strtolower( $singular ) ),
                10 => sprintf( __( '%s draft updated. <a href="%s" target="_blank">Preview %s</a>'), $singular, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), strtolower( $singular ) )
			);

		}

        return $messages;

	}
	
	public function force_published( $post ) {

		if( ! in_array( $post[ 'post_status' ], array( 'auto-draft', 'trash') ) ) {
			if( in_array( $post[ 'post_type' ], array( 'easy-opt-ins' ) ) ) {
				$post['post_status'] = 'publish';
			}
		}
		return $post;
	}

	/**
	 * Disables bulk editing
	 */
	public function disable_bulk_edit( $actions ){
		unset( $actions[ 'edit' ] );
		return $actions;
	}

	/**
	 * Removes quick edit
	 */
	public function remove_quick_edit( $actions ) {
		global $post;
		if( 'easy-opt-ins' === $post->post_type ) {
			unset($actions['inline hide-if-no-js']);
		}
		return $actions;
	}

	/**
	 * Add the desired body classes (backend)
	 */
	public function add_body_class( $classes ) {
		return "$classes fca_eoi";
	}
	
	
	/**
	 * Handle Adding a subscriber with Ajax
	 */
	public function ajax_subscribe() {

		$id = intVal( $_REQUEST['form_id'] );
		
		if ( empty ( $id ) ){
			echo "✗ Missing form ID";
			exit;
		}

		$fca_eoi = get_post_meta( $id, 'fca_eoi', true );
		
		if ( empty ( $fca_eoi ) ){
			echo "✗ Missing post meta";
			exit;
		}		
		
		$provider = K::get_var( 'provider' , $fca_eoi );
		
		// Check a list_id is provided
		$list_id = K::get_var( $provider . '_list_id' , $fca_eoi );
		if ( empty( $list_id ) && $provider !== 'zapier' && $provider !== 'Not set - Store Optins Locally' ) {
			echo "✗ List not set";
			exit;
		}	
		$nonce = sanitize_text_field( $_REQUEST['nonce'] );
		
		$nonceVerified =  wp_verify_nonce( $nonce, 'fca_eoi_submit_form') == 1;;
		if ( !$nonceVerified ) {
			echo "✗ Couldn't verify submission - try reloading";
			exit;
		}
		
		// Subscribe user
		
		if ( K::get_var( 'consent_granted', $_POST ) === 'false' ) {
			$status = 'denied';
		} else {
			$status = call_user_func( $provider . '_add_user', $this->settings, $_POST, $list_id );
		}
		
		do_action('fca_eoi_after_submission', $fca_eoi, $status );
	
		if ( $status === TRUE ) {
			echo '✓';
			EasyOptInsActivity::get_instance()->add_conversion( $id );
			
		} else if ( !empty( $status ) ){
			echo "✗ Failed to add user - $status";
		} else {
			echo '✓';
			EasyOptInsActivity::get_instance()->add_conversion( $id );
		}
		exit;

	}

	public function admin_notices() {

		//RN NOTE: MAYBE REWRITE
		$current_screen = get_current_screen();

		// Exit function if we are not on the opt-in editing page
		if ( ! (
				'easy-opt-ins' === $current_screen->id 
				&& 'post' === $current_screen->base
				&& 'edit' === $current_screen->parent_base
				&& '' === $current_screen->action
			) ) {
			return;
		}

		global $post;
		$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );
		$provider = K::get_var( 'provider', $fca_eoi);
		$errors = array();

		// Add error for missing thank you page
		$confirmation_page_set = ( bool ) K::get_var( 'thank_you_page', $fca_eoi);
		if( ! $confirmation_page_set ) {
			$errors[] = __( 'No "Thank you" page selected. You will not be able to use this form.' );
		}

		// Add error for missing list setting for the current provider
		$list_set = ( bool ) K::get_var( $provider . '_list_id', $fca_eoi);

		// @todo: remove
		// Hack for mailchimp upgrade
		if( empty( $fca_eoi[ 'provider' ] ) ) {
			$fca_eoi[ 'mailchimp_list_id' ] = K::get_var(
				'mailchimp_list_id'
				, $fca_eoi
				, K::get_var( 'list_id' , $fca_eoi )
			);
			$list_set = ( bool ) K::get_var( 'mailchimp_list_id', $fca_eoi);
		}
		// End of Hack


		if( !$list_set && $provider !== 'zapier' && $provider !== 'Not set - Store Optins Locally' ) {
			$errors[] = __( 'No List selected. You will not be able to use this form.' );
		}

		$errors = apply_filters( 'fca_eoi_alter_admin_notices', $errors );

		foreach ( $errors as $error ) {
			echo '<div class="error"><p>' . $error . '</p></div>';
		}
	}

	public function bind_content_filter() {

		// Do nothing in backend
		if ( is_admin() ) {
			return;
		}

		add_action( 'wp', array( $this, 'content' ), 10 );
	}
	
	//SEARCH THE CONTENT AND APPEND ANY RELEVANT POST BOXES
	public function content() {
		
		global $post;
		
		// Work only when viewing a post of any type & not viewing an opt-in editor
		if ( empty( $post ) OR $post->post_type === 'easy-opt-ins' ) {
			return;
		}
				
		//ADD SESSION COOKIE TRACKING SCRIPT
		wp_enqueue_script( 'fca_eoi_pagecount_js', FCA_EOI_PLUGIN_URL.'/assets/pagecount.min.js', array(), FCA_EOI_VER, true );

		$priorities = array();

		// Post details
		$post_ID = $post->ID;
		$post_type = get_post_type( $post_ID );

		// Build the array for testing
		$post_cond = array(
			'*',
			$post_type,
			'#' . $post_ID,
		);
		if ( is_front_page() ) {
			$post_cond[] = '~';
		}

		$priorities[] = '#' . $post_ID;

		$taxonomies = get_taxonomies('', 'names');
		$post_taxonomies = wp_get_object_terms( $post->ID, $taxonomies);
		foreach ( $post_taxonomies as $t ) {
			$condition = $post_type . ':' . $t->term_id;

			$post_cond[] = $condition;
			$priorities[] = $condition;
		}

		$priorities[] = $post_type;
		$priorities[] = '*';

		$optins = get_posts( 'posts_per_page=99&post_type=easy-opt-ins' );

		$fca_eoi_postboxes = array();
		
		foreach ( $optins as $i => $p ) {
			$fca_eoi_postboxes[ $i ][ 'post' ] = $p;
			$fca_eoi_postboxes[ $i ][ 'fca_eoi' ] = get_post_meta( $p->ID, 'fca_eoi', true );
		}

		$postboxes = array();

		// Append postcode shortcode when the conditions match
		foreach( $fca_eoi_postboxes as $f ) {

			// Get conditions
			$eoi_form_cond = K::get_var( 'publish_postbox', $f[ 'fca_eoi' ], array() );

			// Append
			if ( array_intersect( $eoi_form_cond, $post_cond ) ) {
				foreach ( $eoi_form_cond as $cond ) {
					if ( empty( $postboxes[ $cond ] ) ) {
						$postboxes[ $cond ] = sprintf( '[%s id=%d]', $this->settings['shortcode'], $f['post']->ID );
					}
				}
			}
		}

		if ( ! empty( $postboxes ) ) {
			foreach ( $priorities as $cond ) {
				if ( !empty( $postboxes[ $cond ] ) ) {
					$post->post_content .= $postboxes[ $cond ];
					return;
				}
			}

			$post->post_content .= reset( $postboxes );
			return;
		}
	}

	public function scan_for_shortcodes( $content ) {
		if ( preg_match_all( '/data-optin-cat\s*=\s*["\']?\s*(\d+)/', $content, $matches ) ) {
			$this->two_step_ids_on_page = array_map( 'intval', $matches[1] );
		}

		return $content;
	}

	public function maybe_show_lightbox() {
		
		// Get lightboxes
		$lightboxes = get_posts( array(
			'post_type' => 'easy-opt-ins',
			'posts_per_page' => -1,
			'orderby' => 'ID',
			'meta_key' => 'fca_eoi_layout',
			'meta_value' => 'lightbox_',
			'meta_compare' => 'like',
		) );
		
		// BANNERS
		$banners = get_posts( array(
			'post_type' => 'easy-opt-ins',
			'posts_per_page' => -1,
			'orderby' => 'ID',
			'meta_key' => 'fca_eoi_layout',
			'meta_value' => 'banner_',
			'meta_compare' => 'like',
		) );
		
		//OVERLAY AND BANNERS WORK FUNCTIONALLY THE SAME
		$banners = array_merge ( $banners, get_posts( array(
			'post_type' => 'easy-opt-ins',
			'posts_per_page' => -1,
			'orderby' => 'ID',
			'meta_key' => 'fca_eoi_layout',
			'meta_value' => 'overlay_',
			'meta_compare' => 'like',
		) ) );

		if( empty( $lightboxes ) && empty ( $banners ) ) {
			return false;
		}

		$two_step_popups = array();
		$traditional_popups = array();
		$banners_to_show = array();
		
		foreach ( $banners as $banner ) {
			$m = get_post_meta( $banner->ID , 'fca_eoi', true );
			$conditions = K::get_var( 'publish_lightbox', $m, array() );
	
			if ( $this->test_server_conditions( $conditions ) ) {
				$banners_to_show[] = array(
					'id' => $banner->ID,
					'meta' => $m,
					'conditions' => $conditions,					
				);
			}
		}
		
		foreach ( $lightboxes as $lightbox ) {

			// Get conditions
			$lightbox->fca_eoi = get_post_meta( $lightbox->ID , 'fca_eoi', true );
			$publish_lightbox_mode = K::get_var( 'publish_lightbox_mode', $lightbox->fca_eoi, array() );
			
			$lightbox_conditions = K::get_var( 'publish_lightbox', $lightbox->fca_eoi, array() );
						
			// If on a free distribution, force traditional popup mode
			if (  $this->settings['distribution'] === 'free' ) {
				$publish_lightbox_mode = 'traditional_popup';
			}
			
			if ( 'two_step_optin' === $publish_lightbox_mode ) {
				$two_step_popups[] = $lightbox->ID;
			} else if ( $this->test_server_conditions( $lightbox_conditions ) ) {
				$traditional_popups[] = array(
					'id' => $lightbox->ID,
					'meta' => $lightbox->fca_eoi,
					'conditions' => $lightbox_conditions,					
				);
			}
		}
		
		$two_step_popups = array_intersect( $two_step_popups, $this->two_step_ids_on_page );
		
		if ( !empty( $traditional_popups ) OR !empty( $two_step_popups ) OR !empty( $banners_to_show ) ) {
			wp_enqueue_script ( 'jquery' );
			wp_enqueue_style( 'fca_eoi_tooltipster_css', FCA_EOI_PLUGIN_URL.'/assets/vendor/tooltipster/tooltipster.bundle.min.css', array(), FCA_EOI_VER );
			wp_enqueue_style( 'fca_eoi_tooltipster_borderless', FCA_EOI_PLUGIN_URL.'/assets/vendor/tooltipster/tooltipster-borderless.min.css', array(), FCA_EOI_VER );
			wp_enqueue_script( 'fca_eoi_tooltipster_js', FCA_EOI_PLUGIN_URL.'/assets/vendor/tooltipster/tooltipster.bundle.min.js', array(), FCA_EOI_VER, true );
			
			wp_enqueue_script( 'fca_eoi_featherlight_js', FCA_EOI_PLUGIN_URL.'/assets/vendor/featherlight/release/featherlight.min.js', array(), FCA_EOI_VER, true );
			wp_enqueue_style( 'fca_eoi_featherlight_css', FCA_EOI_PLUGIN_URL.'/assets/vendor/featherlight/release/featherlight.min.css', array(), FCA_EOI_VER );
			
			wp_enqueue_script( 'fca_eoi_jstz', FCA_EOI_PLUGIN_URL . '/assets/vendor/jstz/jstz.min.js', array(), FCA_EOI_VER, true );
			
			if( FCA_EOI_DEBUG ) {
				wp_enqueue_script( 'fca_eoi_script_js', FCA_EOI_PLUGIN_URL.'/assets/script.js', array( 'fca_eoi_jstz', 'jquery', 'fca_eoi_tooltipster_js', 'fca_eoi_featherlight_js' ), FCA_EOI_VER, true );
			} else {
				wp_enqueue_script( 'fca_eoi_script_js', FCA_EOI_PLUGIN_URL.'/assets/script.min.js', array( 'fca_eoi_jstz', 'jquery', 'fca_eoi_tooltipster_js', 'fca_eoi_featherlight_js' ), FCA_EOI_VER, true );
			}

			
			$options = get_option( 'fca_eoi_settings' );
			$consent_msg = empty( $options['consent_msg'] ) ? '' : $options['consent_msg'];
			$consent_headline = empty( $options['consent_headline'] ) ? '' : $options['consent_headline'];
			$data = array (
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' =>  wp_create_nonce( 'fca_eoi_submit_form' ),
				'gdpr_checkbox' =>  fca_eoi_show_gdpr_checkbox(),
				'consent_headline' =>  $consent_headline,
				'consent_msg' =>  $consent_msg,
			);
			wp_localize_script( 'fca_eoi_script_js', 'fcaEoiScriptData', $data );	
			
			empty ( $traditional_popups ) ? '' : $this->display_traditional_popups( $traditional_popups );
			empty ( $two_step_popups ) ? '' : $this->display_two_step_popups( $two_step_popups );
			empty ( $banners_to_show ) ? '' : $this->display_banners( $banners_to_show );
		}

	}
	
	public function test_server_conditions( $conditions ) {
		
		//CHECK FOR LIVE
		if ( ! K::get_var( 'live', $conditions, true ) ) {
			return false;
		}
			
		//CHECK MOBILE
		$is_mobile = wp_is_mobile();	
				
		$mobile_mode = K::get_var( 'devices', $conditions, 'all' );
		$fail_mobile = $mobile_mode === 'mobile' && !$is_mobile;
		$fail_desktop = $mobile_mode === 'desktop' && $is_mobile;
		$pass_mobile = !$fail_mobile && !$fail_desktop;
		
		if ( empty( $conditions['conditions'] ) ) {
			return $pass_mobile;
		}	
		
		//CHECK INCLUDED AND EXCLUDED PAGES & TAXONOMIES
		global $post;
		$post_id = $post->ID;
		
		$includes = array();
		$excludes = array();
		
		forEach ( $conditions['conditions'] as $condition ) {
			if ( $condition['parameter'] === 'exclude' ) {
				$excludes = $condition['value'];
			} else if ( $condition['parameter'] === 'include' ) {
				$includes = $condition['value'];
			}
		}
		
		$post_cond = array( '*' );
		
		if ( is_front_page() ) {
			$post_cond[] = '~';
		}
		
		if ( $post_id ) {
			$post_type = get_post_type( $post_id );
			$post_cond[] = $post_type;
			$post_cond[] = '#' . $post_id;
			$taxonomies = get_taxonomies( '', 'names' );
			$post_taxonomies = wp_get_object_terms( $post_id, $taxonomies );
			foreach ( $post_taxonomies as $t ) {
				$post_cond[] = $post_type . ':' . $t->term_id;
			}
		} else {
			//FIX FOR NOT DISPLAYING ON BLOG PAGE
			if ( is_home() ) {
				$post_cond[] = '#' . get_option('page_for_posts');
			}
		
		}
		
		$pass_includes = empty( $includes ) ? true : count ( array_intersect( $includes, $post_cond ) ) > 0;
		$pass_excludes = empty( $excludes ) ? true : count ( array_intersect( $excludes, $post_cond ) ) == 0;
				
		return $pass_includes && $pass_excludes && $pass_mobile;
		
	}

	private function display_two_step_popups( $lightbox_ids ) {
		wp_enqueue_script( 'fca_eoi_twostep_js', FCA_EOI_PLUGIN_URL.'/assets/twostep.min.js', array( 'jquery', 'fca_eoi_tooltipster_js', 'fca_eoi_featherlight_js', 'fca_eoi_script_js' ), FCA_EOI_VER, true );

		foreach ( $lightbox_ids as $id ) {
			$this->prepare_lightbox_html( $id );
		}
	}
	
	private function display_banners( $banners ) {
		
		$target_data = array();

		forEach ( $banners as $p ) {
			$id = $p['id'];
			$target_data[$id] = empty( $p['conditions'] ) ? array() : $p['conditions'];

			echo "<div id='fca_eoi_banner_$id' style='display:none;'>";
				echo do_shortcode( "[easy-opt-in id=$id]" );
			echo '</div>';
		}

		wp_enqueue_script( 'fca_eoi_targeting_js', FCA_EOI_PLUGIN_URL.'/assets/targeting.min.js', array( 'jquery' ), FCA_EOI_VER, true );
		
		$targetData = array (
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'fca_eoi_activity' ),
			'banners' => $target_data,
		);
		
		wp_localize_script( 'fca_eoi_targeting_js', 'fcaEoiBannerTargetingData', $targetData );
		
		return true;
	}

	private function display_traditional_popups( $popups ) {
		
		$target_data = array();

		forEach ( $popups as $p ) {
			$this->prepare_lightbox_html( $p['id'] );
			$target_data[$p['id']] = empty( $p['conditions'] ) ? array() : $p['conditions'];
		}

		wp_enqueue_script( 'fca_eoi_targeting_js', FCA_EOI_PLUGIN_URL.'/assets/targeting.min.js', array( 'jquery' ), FCA_EOI_VER, true );
		
		$targetData = array (
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'fca_eoi_activity' ),
			'popups' => $target_data,
		);
		
		wp_localize_script( 'fca_eoi_targeting_js', 'fcaEoiTargetingData', $targetData );
		
		return true;
	}

	public function prepare_lightbox_html( $id ) {
		$id = (int) $id;
		
		$content = do_shortcode( "[easy-opt-in id=$id]" );
		
		?>
		<div style="display:none">
			<div id="fca_eoi_lightbox_<?php echo $id ?>"><?php echo $content ?></div>
		</div>
		<?php
	}

}
