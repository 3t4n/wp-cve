<?php
/**
 * Wizard to guide new users, 5 star rating stuff and news/notices system
 *
 * @package Fish and Ships
 * @since 1.0.0
 * @version 1.5
 */
   
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Fish_n_Ships_Wizard' ) ) {

	class Fish_n_Ships_Wizard {
		
		var $options             = array();
		var $news_and_pointers   = array();
		var $shipping_classes    = null;
		var $pointers_to_print   = array();
		var $all_samples         = null;
				
		public function __construct() {
			
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			
			add_action( 'wp_ajax_wc_fns_wizard', array($this, 'ajax_wizard_action') );

			add_action( 'wp_ajax_wc_fns_samples', array($this, 'ajax_wizard_samples') );

			//add_action( 'woocommerce_after_settings_shipping );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
		
		public function admin_init()
		{
			global $Fish_n_Ships;

			if( 
				( ! current_user_can('manage_options') && ! current_user_can( 'manage_woocommerce' ) ) || 
				! $Fish_n_Ships->is_wc() 
			) return;
			
			// Copy current plugin options
			$this->options = $Fish_n_Ships->get_options();
			
			// Failed AJAX? Let's call the same function
			if( isset( $_GET[ 'fns_wizard_ajax_fail' ] ) ) 
			{
				$this->ajax_wizard_action( true );
			}

			// Restart wizard?
			if( isset( $_GET[ 'wc-fns-wizard' ] ) && $_GET[ 'wc-fns-wizard' ] == 'restart' ) 
			{
				$this->update_wizard_opts( 'wizard', 'now' );
			}
			
			$this->load_all_messages();

			// Is wizard pending to show? (absolute priority)
			if ( $this->options['show_wizard'] < time() ) {

				// We are on WooCommerce>Settings>Shipping>Shipping zones ?
				if ( isset($_GET['page'] ) && $_GET['page'] == 'wc-settings' && 
					 isset( $_GET['tab'] ) &&  $_GET['tab'] == 'shipping' && 
					 (!isset( $_GET['section'] ) ||  $_GET['section'] == '') ) {

					// We are on shipping method configuration screen?
					if (isset($_GET['instance_id'])) {
						
						add_action('admin_notices', array( $this, 'woocommerce_fns_wizard_notice_4' ) );
						
					// We are on a shipping zone creation screen?
					} elseif (isset($_GET['zone_id']) && $_GET['zone_id']=='new') {

						add_action('admin_notices', array( $this, 'woocommerce_fns_wizard_notice_3' ) );
						
					// We are on a shipping zone edition screen?
					} elseif (isset($_GET['zone_id'])) {

						add_action('admin_notices', array( $this, 'woocommerce_fns_wizard_notice_2' ) );

					// So, we are on the main shipping zones screen
					} else {
				
						add_action('admin_notices', array( $this, 'woocommerce_fns_wizard_notice_1') );
					}
					
				} else {
				
					// Let's show the welcome after activation message
					add_action('admin_notices', array( $this, 'woocommerce_fns_wizard_notice_0' ) );
				}

			// Is wordpress repository rate pending to show? (only on free version)
			} else if ( $this->options['five_stars'] < time() && !$Fish_n_Ships->im_pro() ) {

				add_action('admin_notices', array( $this, 'woocommerce_fns_five_stars_notice' ) );

			} else {

				// Then maybe should show some fish and ships news?
				// add_action('admin_notices', array( $this, 'woocommerce_fns_news' ) );
			}
			
		}
		
		/**
		 * Load all messages: news & pointers, from remote, local & third party
		 * Then remove the dismissed & order it by priority
		 *
		 * @since 1.5
		 */
		function load_all_messages() {

			$wizard_on_method = false;

			if ( $this->options['show_wizard'] < time() && isset($_GET['instance_id']) &&
				 isset($_GET['page'] ) && $_GET['page'] == 'wc-settings' && 
				 isset( $_GET['tab'] ) &&  $_GET['tab'] == 'shipping' && 
				 (!isset( $_GET['section'] ) ||  $_GET['section'] == '') ) {

				 $wizard_on_method = true;
			}

			// Load remote news & pointers (loading scheduled, loaded so time ago) in first place
			$this->news_and_pointers = get_option( 'fish-and-ships-woocommerce-news', array() );

			// Load local news & pointers, then merge it with remote
			require WC_FNS_PATH . 'includes/local-news-n-pointers.php';
			$this->news_and_pointers = array_merge( $this->news_and_pointers, $local_news_n_pointers );

			// Let's add third-party news & pointers
			$this->news_and_pointers = apply_filters( 'wc_fns_wizard_messages', $this->news_and_pointers, $wizard_on_method );

			// Remove dismissed / delayed
			foreach ($this->options['closed_news'] as $key => $value ) {
				if ( $value > time() && isset($this->news_and_pointers[$key]) ) unset ( $this->news_and_pointers[$key] );
			}
			
			// Order it
			foreach( $this->news_and_pointers as $key=>$data ) 
			{
				// Unset priority? let's put the default value
				if( !isset( $data['priority'] ) ) $this->news_and_pointers[$key]['priority'] = 10;
			}
			uasort($this->news_and_pointers, function($a, $b) {
				return $a['priority'] <=> $b['priority'];
			});
		}
		
		// populate pointers_to_print list and enqueue the needed scripts & styles if there is some pointer to show
		function admin_enqueue_scripts( $page )
		{
			//echo '<!-- ** ' . $page . '** -->';

			// Popuplate the pointers array when we're on the right page
			foreach( $this->news_and_pointers as $key=>$data ) 
			{
				if( ! isset( $data['type'] ) || $data['type'] != 'pointer' )
					continue;
				
				// 
				if( 
					( ! isset( $data['where'] ) || in_array( $page, (array) $data['where'], TRUE ) ) &&
					( ! isset( $data['where_not'] ) || ! in_array( $page, (array) $data['where_not'], TRUE ) )
				){
					// Set the defaults where there are missing
					if( ! isset( $data['title'] ) ) 		$data['title'] 			= 'Fish and Ships';
					if( ! isset( $data['edge'] ) )  		$data['edge']  			= 'left';
					if( ! isset( $data['align'] ) ) 		$data['align'] 			= 'middle';
					if( ! isset( $data['wrapper_class'] ) ) $data['wrapper_class']	= 'fns-pointer';
					if( ! isset( $data['auto_open'] ) ) 	$data['auto_open'] 		= false;
					
					$data['content'] = trim( apply_filters( 'the_content', $data[ 'content' ] ) );

					$this->pointers_to_print[ $key ] = $data;
				}
			}
			
			// We will enqueue it only if some pointer should be printed
			if( count( $this->pointers_to_print ) > 0 )
			{
				wp_enqueue_style('wp-pointer');
				wp_enqueue_script('wp-pointer');

				add_action('admin_print_footer_scripts', array( $this, 'print_pointers' ) );
			}
		}
		
		/**
		 * Print pointers
		 *
		 * @since 1.5
		 */
		function print_pointers()
		{
			?>
			<script type="text/javascript">
			//<![CDATA[
			fish_and_ships_pointers = <?php echo json_encode( $this->pointers_to_print ); ?>
			//]]>
			</script>
			<?php
		}

		/**
		 * Creates a consistent button links that can be used safely for all widget steps,
		 * five star dialog, news and tooltips. That will trigger an AJAX call, with a fallback link
		 *
		 * @since 1.5
		 */
		function safe_link_builder( $caption, $kind, $key, $param, $class='button', $id='' )
		{
			$href_attrs = array (
								'kind'   => $kind,
								'key'    => $key,
								'param'  => $param,
								'fns_wizard_ajax_fail' => '1'
							);
			$html  = '<a href="' . add_query_arg( $href_attrs ) . '" data-kind="' . esc_attr( $kind ) . '"';
			$html .= ' data-key="' . esc_attr( $key ) . '" data-param="' . esc_attr( $param ) . '"';
			$html .= ' class="' . esc_attr( $class ) . '"' . ( $id == '' ? '' : ' id="' . esc_attr( $id ) . '"' ) . '>';
			$html .= $caption . '</a>';
				  
			return $html;
		}

		/**
		 * Wizard / five stars / news dismiss buttons call
		 * Mainly AJAX call, but can be called internally when AJAX fail, as fallback
		 *
		 * @since 1.0.0
		 * @version 1.5
		 */
		function ajax_wizard_action( $ajax_fail = true )
		{			
			global $Fish_n_Ships;

			$kind   = isset($_GET['kind'])  ? sanitize_key ( $_GET['kind'] )  : '';
			$key    = isset($_GET['key'])   ? sanitize_key ( $_GET['key'] )   : '';
			$param  = isset($_GET['param']) ? sanitize_key ( $_GET['param'] ) : '';

			// Dismiss news
			if ( $Fish_n_Ships->im_pro() && $kind == 'fns-news') {
				
				$this->update_news_opts( $key, $param );
				
				if( ! $ajax_fail ) {
					echo '1';
					exit();
				}
			}

			// Dismiss wizard / five stars (here key is not used) 
			else if ( in_array($kind, array('wizard', 'five-stars'), true )  )
			{
				$this->update_wizard_opts($kind, $param, true);

				if( ! $ajax_fail ) {
					echo '1';
					exit();
				}
			}

			// Dismiss pointers (here param is not used) 
			else if ( $kind == 'pointer' )
			{
				$when = $when = time() + ( YEAR_IN_SECONDS * 3 ); // three years
				$this->options['closed_news'][$key] = intval($when);

				$Fish_n_Ships->set_options($this->options);

				if( ! $ajax_fail ) {
					echo '1';
					exit();
				}
			}
			
			// Other
			else 
			{
				if( ! $ajax_fail ) {
					echo '0';
					exit();
				}
			}
		}

		/**
		 * Update news (messages & pointers) options ( dismiss or show me later )
		 *
		 * @param key: slug of the new
		 * @param $when: never | timestamp
		 *
		 * @since 1.1.0
		 * @version 1.5
		 */
		function update_news_opts( $key, $when )
		{
			global $Fish_n_Ships;
			
			if ($when == 'never') $when = time() + YEAR_IN_SECONDS; // one year
			$when = intval($when); // timestamp in any case
			if ( $when < time() + DAY_IN_SECONDS ) $when = time() + (DAY_IN_SECONDS * 7); // error. wait a week
			$this->options['closed_news'][$key] = intval($when);
			$Fish_n_Ships->set_options($this->options);
		}

		/**
		 * Update wizard / five stars ( dismiss or show me later )
		 *
		 * @version 1.5
		 *
		 * @param $kind: wizard | five-stars
		 * @param $when: now | later | off
		 */
		function update_wizard_opts( $kind, $when )
		{
			global $Fish_n_Ships;

			// We should show now / later / hide wizard forever?
			if ($kind == 'wizard') {
			
				// Request 5 stars now can irritate (bug solved on 1.3)
				$five_stars_time = time() + DAY_IN_SECONDS;
			
				if ($when=='now') 
				{
					$this->options['show_wizard'] = time() -1; // Now
					$this->options['closed_news'] = array();
				}
			
				if ($when=='off') $this->options['show_wizard'] = time() * 2; // Hide forever

				if ($when=='later') {
					$this->options['show_wizard'] = time() + DAY_IN_SECONDS * 7; // a week (bug solved on 1.3)
					$five_stars_time = time() + DAY_IN_SECONDS * 8; // 8 days (bug solved on 1.3)
				}
				
				if ( $this->options['five_stars'] < $five_stars_time) $this->options['five_stars'] = $five_stars_time;
			
			// We should show later / hide five stars forever? (failed AJAX)
			} elseif ($kind == 'five-stars') {
			
				if ($when=='off')   $this->options['five_stars'] = time() * 2; // Hide forever
				if ($when=='later') $this->options['five_stars'] = time() + DAY_IN_SECONDS * 31; // a month (bug solved on 1.3)
			}

			$Fish_n_Ships->set_options( $this->options );
		}



		
		function woocommerce_fns_five_stars_notice() {

			global $Fish_n_Ships;

			if( 
				( ! current_user_can('manage_options') && ! current_user_can( 'manage_woocommerce' ) ) || 
				! $Fish_n_Ships->is_wc() 
			) return;

			echo '<div class="notice wc-fns-wizard wc-fns-five-stars">'
				//. '<a class="notice-dismiss" href="#">' . esc_html__('Dismiss') . '</a>'
				. '<h3>'. esc_html__('Do you like Fish and Ships?', 'fish-and-ships') . '</h3>'
				. '<p class="fns-space-up big">' . esc_html__('Thank you for your continued use of our plugin.', 'fish-and-ships') . '</p><p class="big">' . 
				wp_kses( __('Please, rate <strong>Fish and Ships</strong> on the WordPress repository, it will help us a lot :)', 'fish-and-ships'),
							 array('strong'=>array())
				) . '</p>'
				. '<p><a href="' . esc_url('https://wordpress.org/support/plugin/fish-and-ships/reviews/?rate=5#new-post') . '" class="button-primary" target="_blank" data-kind="five-stars" data-param="later">' . esc_html__('Rate the plugin', 'fish-and-ships') . '</a> &nbsp;'
				  . '<a href="' . add_query_arg('wc-fns-five-stars', 'later') . '" class="button" data-kind="five-stars" data-param="later">' . esc_html__('Remind later', 'fish-and-ships') . '</a> &nbsp;'
				 . '<a href="' . add_query_arg('wc-fns-five-stars', 'off') . '" class="button" data-kind="five-stars" data-param="off">' . esc_html__('Don\'t show again', 'fish-and-ships') . '</a>'

				  . '</p></div>';
		}

		function woocommerce_fns_news() {

			global $Fish_n_Ships;

			if ( is_array($wc_fns_news) ) {
				foreach ($this->options['closed_news'] as $key => $value ) {
					if ( $value > time() && isset($wc_fns_news[$key]) ) unset ( $wc_fns_news[$key] );
				}

				if (count($wc_fns_news) > 0 ) {

					$notice = reset($wc_fns_news);

					echo '<div class="wc-fns-news notice ' . esc_attr($notice['type']) . '">'
						//. '<a class="notice-dismiss" href="#">' . esc_html__('Dismiss') . '</a>'
						. wp_kses_post($notice['message']);

					$buttons = '';

					if ( isset ($notice['call_to_action']) ) $buttons .= wp_kses_post($notice['call_to_action']) . '&nbsp;';

					if ( isset ($notice['later']) ) $buttons .= '<a href="' . add_query_arg(array('wc-fns-notice-dismiss' => key($wc_fns_news), 'time' => intval($notice['later']) ) ) . '" class="button" data-kind="fns-news" data-key ="' . esc_html(key($wc_fns_news)) . '" data-param="' . intval($notice['later']) . '">' . esc_html__('Remind later', 'fish-and-ships') . '</a>&nbsp;';

					if ( isset ($notice['dismissable']) ) $buttons .= '<a href="' . add_query_arg(array('wc-fns-notice-dismiss' => key($wc_fns_news), 'time' => 'never' ) ) . '" class="button" data-kind="fns-news" data-key ="' . esc_html(key($wc_fns_news)) . '" data-param="never">' . esc_html__('Don\'t show again', 'fish-and-ships') . '</a>';

					if ($buttons != '') echo '<p>' . $buttons . '</p>';

					echo '</div>';
				}
			}
		}

		function woocommerce_fns_wizard_notice_0() {

			global $Fish_n_Ships;

			if( 
				( ! current_user_can('manage_options') && ! current_user_can( 'manage_woocommerce' ) ) || 
				! $Fish_n_Ships->is_wc() 
			) return;

			echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-0">'
				. '<h3>'. esc_html__('Welcome to Fish and Ships:', 'fish-and-ships') . '</h3>'
				. '<p class="fns-space-up big">' . esc_html__('A WooCommerce shipping method. Easy to understand and easy to use, it gives you an incredible flexibility.', 'fish-and-ships') . '</p>'
			    . '<p><a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&wc-fns-wizard=now') . '" class="button-primary">' . esc_html__('Start wizard', 'fish-and-ships') . '</a> &nbsp;'

				. $this->safe_link_builder( esc_html__('Remind later', 'fish-and-ships'), 'wizard', '', 'later' ) . ' &nbsp;'
				// . '<a href="' . add_query_arg('wc-fns-wizard', 'later') . '" class="button" data-kind="wizard" data-param="later">' . esc_html__('Remind later', 'fish-and-ships') . '</a> &nbsp;'
				
				. $this->safe_link_builder( esc_html__('Thanks, I know how to use it', 'fish-and-ships'), 'wizard', '', 'off' ) . '</p>'
				// . '<a href="' . add_query_arg('wc-fns-wizard', 'off') . '" class="button" data-kind="wizard" data-param="off">' . esc_html__('Thanks, I know how to use it', 'fish-and-ships') . '</a></p>'
				
				. '<p class="fns-space-up"><a href="#" class="fns-show-videos">' . esc_html__('...or maybe you prefer to see one of our introductory videos before:', 'fish-and-ships') . '</a></p>'
				. '<div class="fns-hidden-videos"><p><a href="https://www.youtube.com/watch?v=wRsoUYiHQRY&ab_channel=WpCentricsFishAndShips" target="_blank" alt="See video on YouTube" class="fns-video-link"><img src="' . WC_FNS_URL . 'assets/img/video-1.png" width="232" height="130" /><span>General overview</span></a>'
				. '<a href="https://www.youtube.com/watch?v=sjQKbt2Nn7k&ab_channel=WpCentricsFishAndShips" target="_blank" alt="See video on YouTube" class="fns-video-link"><img src="' . WC_FNS_URL . 'assets/img/video-2.png" width="232" height="130" /><span>Short tutorial</span></a>'
				. '<a href="https://www.youtube.com/watch?v=y2EJFluXx9Q&ab_channel=WpCentricsFishAndShips" target="_blank" alt="See video on YouTube" class="fns-video-link"><img src="' . WC_FNS_URL . 'assets/img/video-3.png" width="232" height="130" /><span>Shipping boxes</span></a></p></div>'
			  . '</div>';
		}

		function woocommerce_fns_wizard_notice_1() {

			echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-1">'
				. '<h3>' . esc_html__('Fish and Ships Wizard:', 'fish-and-ships') . '</h3>' 
				. '<p class="fns-space-up big">' . esc_html__('First, select one shipping zone, or create a new one:', 'fish-and-ships')
				. '</p></div>';
		}

		function woocommerce_fns_wizard_notice_2() {

			echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-2">'
				. '<h3>' . esc_html__('Fish and Ships Wizard:', 'fish-and-ships') . '</h3>' 
				. '<p class="fns-space-up big">' . esc_html__('Now add a new shipping method:', 'fish-and-ships') . ' ' .
				wp_kses( __('add <strong>Fish and Ships</strong>, and edit it.', 'fish-and-ships'),
						 array('strong'=>array())
				) . '</p></div>';
		}

		function woocommerce_fns_wizard_notice_3() {

			echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-3">'
				. '<h3>' . esc_html__('Fish and Ships Wizard:', 'fish-and-ships') . '</h3>' 
				. '<p>' . esc_html__('Configure the new zone, and then:', 'fish-and-ships') . ' ' .
				wp_kses( __('add <strong>Fish and Ships</strong>, and edit it.', 'fish-and-ships'),
						 array('strong'=>array())
				) . '</p></div>';
		}

		function woocommerce_fns_wizard_notice_4()
		{
			echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-4">'
				. '<h3>' . esc_html__('Fish and Ships Wizard:', 'fish-and-ships') . '</h3>';
			
			if( ! isset($_POST['wc-fns-samples']) )
			{
				echo '<p class="fns-space-up big"><strong>A quick way to get started</strong>...is by selecting a pre-solved full case example that closely matches the configuration you need. Or you can continue the wizard:</p>'
					. '<p><a href="#" class="button button-wc-fns-colors woocommerce-fns-case">Load a full example</a> &nbsp; <a href="' . add_query_arg('wc-fns-wizard', 'off') . '" class="button wc-fns-continue-wizard button-wc-fns-colors" data-kind-OFF="wizard__" data-param-OFF="off__">Continue wizard</a> &nbsp; '
					. '<a href="' . add_query_arg('wc-fns-wizard', 'later') . '" class="button" data-kind="wizard" data-param="later">' . esc_html__('Remind later', 'fish-and-ships') . '</a> &nbsp; '
					. '<a href="' . add_query_arg('wc-fns-wizard', 'off') . '" class="button" data-kind="wizard" data-param="off">' . esc_html__('Thanks, I know how to use it', 'fish-and-ships') . '</a></p>';
			}
			else 
			{
				echo '<p class="fns-space-up big"><strong>Your choosen examples has been added.</strong> Let\'s fine-tune it and finish the tour:</p>'
			
					. '<p><a href="' . add_query_arg('wc-fns-wizard', 'off') . '" class="button wc-fns-continue-wizard button-wc-fns-colors" data-kind-OFF="wizard__" data-param-OFF="off__">Continue</a></p>';
			}
			
			echo '</div>';

		}

		
		/**
		 * Create sample settings (if must do it)
		 *
		 * @since 1.5
		 */
		function create_sample_settings( $shipping_class )
		{
			global $Fish_n_Ships;
			
			$method_id     = isset( $_GET['instance_id'] )      ? intval( $_GET['instance_id'] ) : 0;
			$samples       = isset( $_POST['wc-fns-samples'] )  ? $Fish_n_Ships->sanitize_array_of_keys( $_POST['wc-fns-samples'] ) : array();
			$keep_current  = isset( $_POST['keep_current'] )    ? $Fish_n_Ships->sanitize_allowed( $_POST['keep_current'], array( '1', '0' ) ) : '0';			
			
			if ( $method_id == 0 || count( $samples ) < 1 )
				return; // Nothing to do
						
			// Stored shipping method settings
			$option_name = 'woocommerce_fish_n_ships_'. $method_id .'_settings';

			$settings = get_option( $option_name, array() );
			
			$new_rules = array();
			
			$current_global_group_by          = $settings['global_group_by'];
			$current_global_group_by_method   = $settings['global_group_by_method'];

			// If the method has global group-by strategy and we're Pro, maybe it must be turned off
			$global_group_by_must_turned_off  = false;			

			// If we're on Free, maybe the method must be changed (JavaScript has prevented incompatible changes before)
			$available_group_by_methods_for_free = array_keys( $Fish_n_Ships->get_group_by_options() );

			foreach( $samples as $sample_key )
			{
				if( ! $sample_data = $this->get_sample_data( $sample_key ) )
					continue; // File not found

				// Options outside table rules
				$opts_outside_table = array( 'title', 'tax_status', 'global_group_by', 'global_group_by_method', 'rules_charge', 'free_shipping',
											 'disallow_other', 'volumetric_weight_factor', 'min_shipping_price', 'max_shipping_price' );
				
				// If we're a snippet and the volumetric weight factor is set yet, we won't overwrite it
				if( $sample_data[ 'tab' ] == 'snippets' 
					&& ( $settings['volumetric_weight_factor'] != '' && $settings['volumetric_weight_factor'] == '0' )
				) {
					unset( $opts_outside_table['volumetric_weight_factor'] );
				}
				
				$sample_data   = $sample_data[ 'config' ];
				$sample_rules  = $sample_data[ 'rules' ];
				
				foreach( $opts_outside_table as $opt_key )
				{
					if( isset( $sample_data[$opt_key] ) )
						$settings[$opt_key] = $sample_data[$opt_key];
				}

				foreach( $sample_rules as $rule_key => $rule )
				{
					foreach( $rule['sel'] as $sel_key => $sel )
					{
						// If the group_by isn't set, any value is allowed
						if( isset( $sel[ 'values' ][ 'group_by' ] ) && is_array( $sel['values']['group_by'] ) && isset( $sel['values']['group_by'][0] ) )
						{
							// Free version can't have distinct group-by (JS has prevented incompatible changes before)
							if( ! $Fish_n_Ships->im_pro() )
							{
								$available_group_by_methods_for_free = array_intersect(
										$available_group_by_methods_for_free, $sel[ 'values' ][ 'group_by' ] );
								
								// In any case, the group-by must have some value on each selection (hidden & ignored)
								$sample_rules[$rule_key]['sel'][$sel_key][ 'values' ][ 'group_by' ] = $sel[ 'values' ][ 'group_by' ][0];
							}
							else if( $keep_current && $current_global_group_by == 'yes' && ! $global_group_by_must_turned_off )
							{
								// Currently group-by is global, let's try to keep it
								if( in_array( $current_global_group_by_method, $sel[ 'values' ][ 'group_by' ] ) )
								{
									// It continue global
									$sample_rules[$rule_key]['sel'][$sel_key][ 'values' ][ 'group_by' ] = $current_global_group_by_method;
								}
								else
								{
									// Global must be turned off
									$global_group_by_must_turned_off = true;
									$sample_rules[$rule_key]['sel'][$sel_key][ 'values' ][ 'group_by' ] = $sel[ 'values' ][ 'group_by' ][0];
								}
							}
							else
							{
								$sample_rules[$rule_key]['sel'][$sel_key][ 'values' ][ 'group_by' ] = $sel[ 'values' ][ 'group_by' ][0];
							}

						}

						// Put choosen shipping class ID
						if( isset( $sel[ 'method' ] ) && ( $sel[ 'method' ] == 'in-class' || $sel[ 'method' ] == 'not-in-class' ) )
						{
							$sample_rules[$rule_key]['sel'][$sel_key][ 'values' ][ 'classes' ] = array();
							
							foreach ( $sel[ 'values' ][ 'classes' ] as $key => $val ) {
								if( isset( $_POST[$sample_key.'_sel-s-class'][$val] ) )
								{
									$sample_rules[$rule_key]['sel'][$sel_key][ 'values' ][ 'classes' ][ $key ] = intval( $_POST[$sample_key.'_sel-s-class'][$val] );
								}
							}
						}
					}
					foreach( $rule['actions'] as $action_key => $action )
					{
						// Put choosen shipping boxes ID
						if( isset( $action[ 'method' ] ) && ( $action[ 'method' ] == 'boxes' ) )
						{
							$active  = isset( $action['values']['active'] )  ? $action['values']['active']  : array();
							$price   = isset( $action['values']['price'] )   ? $action['values']['price']   : array();
							$max_qty = isset( $action['values']['max_qty'] ) ? $action['values']['max_qty'] : array();
							
							$modify_values = $sample_rules[$rule_key]['actions'][$action_key]['values'];
							
							unset( $modify_values['active'] );
							unset( $modify_values['price'] );
							unset( $modify_values['max_qty'] );

							if( isset( $_POST[$sample_key.'_sel-s-box'] ) && is_array( $_POST[$sample_key.'_sel-s-box'] ) )
							{
								$index = 0;
								foreach( $_POST[$sample_key.'_sel-s-box'] as $box_id )
								{
									$box_id = intval($box_id);
									if( ! isset( $active[$index] ) || ! isset( $price[$index] ) || ! isset( $max_qty[$index] ) )
										continue;
									
									$modify_values['active_'  . $box_id] = $active[$index];
									$modify_values['price_'   . $box_id] = $price[$index];
									$modify_values['max_qty_' . $box_id] = $max_qty[$index];
									
									$index++;
								}
							}
							$sample_rules[$rule_key]['actions'][$action_key]['values'] = $modify_values;
						}
					}
				}
					
				$new_rules = array_merge( $new_rules, $sample_rules );
			}	
			
			if( $keep_current == '1' )
			{
				// Free version can't have distinct group-by. Maybe we must change global setting
				if( ! $Fish_n_Ships->im_pro() )
				{
					if( !  in_array( $settings['global_group_by_method'], $available_group_by_methods_for_free ) 
						&& count( $available_group_by_methods_for_free ) > 0 
					){
						$settings['global_group_by_method'] = array_values($available_group_by_methods_for_free)[0];
					}
				}
				else if( $global_group_by_must_turned_off )
				{
					// Set each previous selector to the previous global group-by value
					foreach( $settings['shipping_rules'] as $rule_key => $rule )
					{
						foreach( $rule['sel'] as $sel_key => $sel_val )
						{
							$settings['shipping_rules'][$rule_key]['sel'][$sel_key][ 'values' ][ 'group_by' ] = $current_global_group_by_method;
						}
					}
					$settings['global_group_by'] = 'no';
				}
				$new_rules = array_merge( $new_rules, $settings['shipping_rules']);
				
				// We should remove an empty rule at the end? (from starting empty table + add snippets):
				if( count( $new_rules ) > 1 )
				{
					$all_keys = array_keys($new_rules);
					$last_key = end( $all_keys );

					if( ! isset( $new_rules[$last_key]['sel'][0] ) )
						unset( $new_rules[$last_key] );
				}
			}
			
			// Ensure that any extra rule it's under any normal rule
			usort($new_rules, function ($a, $b) {
				if( $a['type'] == $b['type'] ) return 0;
				if( $a['type'] == 'normal' ) return -1;
				return 1;
			});


			$settings['shipping_rules'] = $new_rules;
			update_option( $option_name, $settings );			

			$shipping_class->init_instance_settings();
			$shipping_class->shipping_rules = $new_rules;
		}
		
		/**
		 * Return the samples helper
		 *
		 * @since 1.5
		 */
		function get_samples_helper() {
			
			$restart_url = add_query_arg( 'wc-fns-wizard', 'restart' );
			
			$html = '
				<div class="fns-samples-wizard">
					<div class="wc_fns_cont_cols clearfix">
						<div class="wc_fns_col_menu">
							<nav class="wc_fns_nav_popup">
								<strong><a href="#" data-fns-tab="fullsamples">Full case examples</a></strong>
								<a href="#" data-fns-tab="snippets">Snippets</a>
							</nav>
						</div>
						<div class="wc_fns_col_content">
							<div class="wc_fns_tab wc_fns_tab_snippets">
								<div class="help_note" style="margin-top:2em;">
									<p><strong>Snippets are one or more simple rules that can complement your existing configuration.</strong></p>
									<p>Typically, any combination of snippets is compatible with each other and with your current rule configuration. All the snippets you choose will be inserted at the top of your rules. Maybe you need to reorder it after that.</p>
								</div>
								<p class="snippets-ajax-loading"><span class="wc-fns-spinner"></p>
								<p class="fns-add-sel-p"><button class="button button-wc-fns-colors disabled fns-add-sel-snippets">Add selected snippets</button></p>
							</div>
							<div class="wc_fns_tab wc_fns_tab_fullsamples">
								<div class="help_note" style="margin-top:2em;">
									<p><strong>Full case examples are comprehensive configuration methods that assist you in understanding how Fish and Ships works.</strong></p>
									<p>You can also search for one that suits your requirements and then customize it as needed.</p>
								</div>
								<div class="warning" style="margin-top:2em;">
									<p><span class="dashicons dashicons-warning"></span><b>Please, pay attention: If you proceed, your settings will be overwritten.</b> <br>We recommend loading these examples into a new shipping method.</p>
								</div>
								<p class="fullsamples-ajax-loading"><span class="wc-fns-spinner"></p>
							</div>
						</div><!-- /wc_fns_col_content -->
					</div>
				</div>
				<a href="' . esc_url($restart_url) . '" role="tab" aria-selected="false" aria-controls="activity-panel-activity" id="activity-panel-tab-restart-fns" class="components-button woocommerce-layout__activity-panel-tab">
					<span class="dashicons dashicons-flag"></span>Restart<br> wizard<span class="screen-reader-text">Restart wizard</span>
				</a>';
						
			return $html;
		}
		
		/**
		 * Get one sample HTML
		 *
		 * @since 1.5
		 */
		function get_html_case( $sample, $key ) {
			
			global $Fish_n_Ships;

			$html = '';
			
			$ban_pro = ( ! $Fish_n_Ships->im_pro() ) && $sample['only_pro'];

			$any_group_by = true;

			// We need to check the compatible group-by settings if we are on free version
			if( ! $Fish_n_Ships->im_pro() && $sample['tab'] == 'snippets' )
			{
				$compatibles_group_by = $this->extract_group_by_from_rules( $sample['config']['rules'] );

				// If there isn't any compatible group-by, this snippet is for Pro only (wrongly declared free compatible)
				if( $compatibles_group_by === false )
				{
					$ban_pro = true;
					$any_group_by = true;
				}
				elseif( $compatibles_group_by === true  )
				{
					$any_group_by = true;
				}
				else
				{
					$any_group_by = false;
				}
			}
			
			$html .= '<li class="' . ( $ban_pro ? 'only_pro' : '' ) . '">';
			
			$html .= '<label>' . esc_html( $sample['name'] ) . '<span class="dashicons dashicons-arrow-down"></span>' . ( $ban_pro ? '<span class="fns-pro-icon">PRO</span>' : '' ) . '</label>';
			$html .= '<div class="case">';
			if( $sample['tab'] == 'snippets' )
			{
				$html .= '<input type="checkbox" name="wc-fns-samples[]" value="' . sanitize_key( $key ) . '"' . ( $ban_pro ? ' disabled' : '' );
				if ( ! $any_group_by )
					$html .= ' data-restrict-group_by="' . esc_attr( implode( ',', $compatibles_group_by ) ) . '"';
				$html .= '> ';
			}
			$html .= apply_filters( 'the_content' ,  $sample[ 'case' ] );
			
			$disabled = false;

			if( isset( $sample[ 'choose' ] ) )
			{
				foreach( $sample[ 'choose' ] as $choose )
				{
					switch( $choose['type'] )
					{
						case 'shipping_class' :
							
							$html .= '<div class="chooser_wrapper">';
							$html .= apply_filters( 'the_content',  $choose['label'] );
							
							if( is_array( $this->get_shipping_classes() ) && count( $this->get_shipping_classes() ) > 0 )
							{
								$html .= '<select name="' . esc_attr($key . '_sel-s-class') .'[]"><option value="">Choose one</option>';
								foreach ( $this->get_shipping_classes() as $sc )
								{
									$html .= '<option value="' . $sc->term_id . '">' . $sc->name . '</option>';
								}
								$html .= '</select>';
							}
							else
							{
								$link = add_query_arg( array('page' => 'wc-settings', 'tab' => 'shipping', 'section' => 'classes' ), admin_url('admin.php') );
								$html .= '<p class="fns-error-text">' . sprintf('Please, create some shipping classes %shere%s first.', '<a href="' . esc_url( $link ) . '">', '</a>') . '</p>';
								$disabled = true;
							}
							$html .= '</div>';
							
							break;
							
						case 'shipping_box' :
							
							$html .= '<div class="chooser_wrapper">';
							$html .= apply_filters( 'the_content',  $choose['label'] );
							if( is_array( $this->get_shipping_boxes() ) && count( $this->get_shipping_boxes() ) > 0 )
							{
								$html .= '<select name="' . esc_attr($key . '_sel-s-box') .'[]"><option value="">Choose one</option>';
								foreach ( $this->get_shipping_boxes() as $sb )
								{
									$html .= '<option value="' . $sb['id'] . '">' . $sb['name'] . '</option>';
								}
								$html .= '</select>';
							}
							else
							{
								$link = add_query_arg( array('page' => 'wc-settings', 'tab' => 'shipping', 'section' => 'fns-shipping-boxes' ), admin_url('admin.php') );
								$html .= '<p class="fns-error-text">' . sprintf('Please, create some boxes %shere%s first.', '<a href="' . esc_url( $link ) . '">', '</a>') . '</p>';
								$disabled = true;
							}
							$html .= '</div>';
							
							break;
					}
				}
			}

			if( isset( $sample[ 'note' ] ) )
			{
				// Pro version, or sampre onlypro, don't need the pro badge in the note:
				$note = $sample[ 'note' ];
				if( $Fish_n_Ships->im_pro() || $ban_pro )
					$note = str_replace('<span class="fns-pro-icon">PRO</span>', '', $note);
				
				$html .= '<div class="note_wrapper">';
				$html .= '<p><span class="dashicons dashicons-info-outline"></span> ' . wp_kses_post( $note ) . '</p>';
				$html .= '</div>';
			}

			if( $sample['tab'] == 'fullsamples' )
			{
				$html .= '<p><button class="button button-wc-fns-colors ' . ( $ban_pro || $disabled ? ' disabled' : '' ) . '" value="' . sanitize_key( $key ) . '"' . ( $ban_pro || $disabled ? ' disabled' : '' ) . '>Add this case</button></p>';
			}
			$html .= '</div></li>' . PHP_EOL;
			
			return $html;
		}

		/**
		 * Get all samples. First time will load from files
		 *
		 * @since 1.5
		 */
		function get_all_samples() {
			
			if( ! is_null( $this->all_samples ) )
				return $this->all_samples;
			
			$samples = array( 
								// Full cases: flat rate
								'fns-conditional-flat-rate',
								'fns-flat-rate-furniture',
								'fns-flat-rate-weekdays',
								'fns-shipping-boxes-flat-rate',

								// Full cases: free shipping
								'fns-conditional-free-shipping',
								'fns-three-conditional-free-shipping',
								'fns-two-conditional-free-shipping',
								'fns-cond-free-shipping-message',

								// Full cases: weight ranges
								'fns-weight-ranges-full',
								'fns-dimension-weight-ranges',
								'fns-fresh-ranges', 
								'fns-volumetric-ranges',
								'fns-weight-math-sample',

								// Full cases: Other ranges
								'fns-quantity-ranges',
								'fns-volume-ranges',
								'fns-length-girth-weight',

								// Full cases: Extra rates
								'fns-fresh-50', 
								'fns-volume-ranges-fragile',
								'fns-weekend-extra', 
								'fns-global-insurance', 

								// Full cases: Advanced
								'fns-fixed-and-size', 
								'fns-bricks-pallets',
								'fns-four-seasons',
								'fns-postcode-london',

								// Snippets: Basics
								'fns-fixed-rate', 
								'fns-pct-10',
								'fns-pct-min-max', 
								'fns-pct-min-max-relative',

								// Snippets: Weight or Volumetric weight
								'fns-30-heavy',
								'fns-weight-ranges',
								'fns-weight-math',
								'fns-disable-heavy',
								'fns-volumetric-rates',
								'fns-volumetric-math',

								// Snippets: Dimensions or volume
								'fns-30-big',
								'fns-bricks-pallets-snippet',
								'fns-disable-big',
								'fns-30-bulky',
								'fns-volume-rates',
								'fns-disable-bulky',
								
								// Snippets: Products type / Products quantity
								'fns-flat-rate-class',
								'fns-flat-rate-not-class',
								'fns-extra-class',
								'fns-fragile-insurance',
								'fns-disable-class',
								'fns-disable-not-class',
								'fns-quantity-rates',

								// Snippets: Multiple conditions
								'fns-flat-rate-light',
								'fns-flat-rate-light-100',
								'fns-30-big-heavy-bulky',
								'fns-disable-big-heavy-bulky', 

								// Snippets: Cart subtotal
								'fns-flat-rate-100',
								'fns-off-50',
								'fns-disable-small',

								// Snippets: Date & time
								'fns-extra-10-weekend',
								'fns-disable-weekend',
								'fns-disable-summer',
								'fns-disable-winter',
								'fns-disable-night',

								// Snippets: Advanced: Shipping boxes packer & messages
								'fns-boxes-snippet',
								'fns-boxes-fragile-snippet',
								'fns-free-shipping-message',
			);
			
			$all_samples = array();
			
			// Load each from his file
			foreach( $samples as $key )
			{
				if( $sample = $this->get_sample_data( $key ) )
					$all_samples[$key] = $sample;
			}
			
			// Order it
			uasort($all_samples, function($a, $b) {
				
				$b_priority = isset( $b['priority'] ) ? $b['priority'] : 10;
				$a_priority = isset( $a['priority'] ) ? $a['priority'] : 10;
				
				return $b_priority <=> $a_priority;
			});
			
			// Remember it
			$this->all_samples = $all_samples;
			
			return $all_samples;
		}
		
		function ajax_wizard_samples() {
			
			$html_snippets  = '';
			$last_section     = '';
			
			foreach ( $this->get_all_samples() as $key => $sample ) {
				
				if( $sample['tab'] != 'snippets' )
					continue;
				
				if( $last_section != $sample['section'] )
				{
					if ( $last_section != '' ) $html_snippets .= '</ul></div>';
					$html_snippets .= '<div class="fns-case-wrapper"><h2>' . esc_html( $sample['section'] ) . '<span class="counter"></span><span class="dashicons dashicons-arrow-down"></span></h2><ul class="sample-list">';
					$last_section = $sample['section'];
				}
				
				$html_snippets .= $this->get_html_case( $sample, $key );
			}
			
			$html_snippets .= '</ul></div>';

				
			$last_section = '';
			$html_fullsamples = '';

			foreach ( $this->get_all_samples() as $key => $sample ) {
				
				if( $sample['tab'] != 'fullsamples' )
					continue;

				if( $last_section != $sample['section'] )
				{
					if ( $last_section != '' ) $html_fullsamples .= '</ul></div>';
					$html_fullsamples .= '<div class="fns-case-wrapper"><h2>' . esc_html( $sample['section'] ) . '<span class="dashicons dashicons-arrow-down"></span></h2><ul class="sample-list">';
					$last_section = $sample['section'];
				}
				
				$html_fullsamples .= $this->get_html_case( $sample, $key );
			}
			
			$html_fullsamples .= '</ul></div>';
			
			
			$fragments = array(
							'snippets'     => $html_snippets,
							'fullsamples'  => $html_fullsamples
			);
			echo json_encode($fragments);
			exit();
		}



		/**
		 * Get all sample data (for performance, not loaded if not needed)
		 *
		 * @since 1.5
		 */
		function get_sample_data( $sample_key ) {
			
			$sample = false;

			$filename  = WC_FNS_PATH . 'samples/' . $sample_key . '.php';
							
			if( ! is_file( $filename ) )
				return false;
							
			require( $filename );
			
			if( ! is_array( $sample ) )
				return false;
			
			return $sample;
		}
		
		/**
		 * Commonly used in most configuration files, coded once here.
		 *
		 * @since 1.5
		 */
		function get_operator_and() 
		{
			return array (
						array(
							'method'   => 'logical_operator',
							'values'   => array( 'and' )
						),
			);
		}

		/**
		 * Commonly used in most configuration files, coded once here.
		 *
		 * @since 1.5
		 */
		function get_cost_zero() 
		{
			return array(
							array(
								'method'  => 'once',
								'values'  => array(
												'cost' => 0
											 )
							)
			);
		}

		/**
		 * To make the samples more understandable we will make a conversion units
		 * this function assume input size in centimeters
		 *
		 * @since 1.5
		 */
		function loc_size( $size, $show_units = false ) 
		{
			$dimension_unit = get_option('woocommerce_dimension_unit'); 
			
			if ( $dimension_unit == 'm' || $dimension_unit == 'yd' ) $size = $size / 100;
			if ( $dimension_unit == 'mm' ) $size = $size * 10;
			if ( $dimension_unit == 'in' ) $size = $size / 2;
			
			return $size . ( $show_units ? $dimension_unit : '');
		}

		/**
		 * To make the samples more understandable we will make a conversion units
		 * this function assume input weight in kg
		 *
		 * @since 1.5
		 */
		function loc_weight( $weight, $show_units = false ) 
		{
			$weight_unit = get_option('woocommerce_weight_unit'); 
			
			if ( $weight_unit == 'g'  ) $weight = $weight * 1000;
			if ( $weight_unit == 'oz' ) $weight = $weight * 30;
			if ( $weight_unit == 'lb' ) $weight = $weight * 2;
			
			return $weight . ( $show_units ? $weight_unit : '');
		}
		
		function unit_weight() {
			
			return get_option('woocommerce_weight_unit'); 
		}

		/**
		 * To make the samples more understandable we will make a conversion units
		 * this function assume input size in cm3/kg
		 *
		 * @since 1.5
		 */
		function loc_volumetric( $volume, $show_units = false ) 
		{
			$conversion = 1;
			
			$dimension_unit  = get_option('woocommerce_dimension_unit'); 
			$weight_unit 	 = get_option('woocommerce_weight_unit'); 
			
			if ( $dimension_unit == 'm' || $dimension_unit == 'yd' ) $conversion = 0.000001;
			if ( $dimension_unit == 'mm' ) $conversion = 1000;

			if ( $weight_unit == 'g'  ) $conversion = $conversion * 1000;
			if ( $weight_unit == 'oz' ) $conversion = $conversion * 30;
			if ( $weight_unit == 'lb' ) $conversion = $conversion * 2;
			
			$volume = $volume * $conversion;
			
			return $volume . ( $show_units ? $dimension_unit .'<sup>3</sup>/' . $weight_unit : '' );
		}

		/**
		 * To make the samples more understandable we will make a conversion units
		 * this function assume input size in cubic centimeters
		 *
		 * @since 1.5
		 */
		function loc_volume( $volume, $show_units = false ) 
		{
			$dimension_unit = get_option('woocommerce_dimension_unit'); 
			
			if ( $dimension_unit == 'm' || $dimension_unit == 'yd' ) $volume = $volume / 1000000;
			if ( $dimension_unit == 'mm' ) $volume = $volume * 1000;
			if ( $dimension_unit == 'in' ) $volume = $volume / 20;
			
			return $volume . ( $show_units ? $dimension_unit .'<sup>3</sup>': '');
		}

		/**
		 * Add currency symbol
		 *
		 * @since 1.5
		 */
		function loc_price( $price, $show_units = false ) 
		{
			$decimals = 2;
			
			if( $price == intval($price) ) $decimals = 0;
			
			if( ! $show_units ) return round( $price, $decimals);
			return strip_tags( wc_price( $price, array( 'decimals' => $decimals ) ) );
		}

		/**
		 * Load shipping classes. Only once, then store it for next requests.
		 *
		 * @since 1.5
		 */
		function get_shipping_classes() 
		{
			if( is_null( $this->shipping_classes ) )
				$this->shipping_classes = get_terms( array( 'taxonomy' => 'product_shipping_class', 'hide_empty' => false ) );
			
			return $this->shipping_classes;
		}
		
		/**
		 * Get shipping boxes. Not needed cache here
		 *
		 * @since 1.5
		 */
		function get_shipping_boxes() 
		{
			global $Fish_n_Ships;
			
			return $Fish_n_Ships->get_option('boxes');
		}

		/**
		 * Gives the compatible group-by options for a group of rules
		 *
		 * Return true if there isn't any restriction (all group-by option allowed)
		 * Return false if distinct rules are incompatible (must be pro)
		 * Return array of available options
		 * 
		 * @since 1.5
		 */
		function extract_group_by_from_rules( $rules )
		{
			$any_group_by = true;
			$compatibles_group_by = array();
			
			foreach( $rules as $rule_key => $rule )
			{
				foreach( $rule['sel'] as $sel_key => $sel )
				{
					// If the group_by isn't set or is an empty array, any value is allowed
					if( isset( $sel['values']['group_by'] ) && is_array( $sel['values']['group_by'] ) && count( $sel['values']['group_by'] ) > 0 )
					{
						if( $any_group_by )
						{
							// First selector with group-by requirements
							$any_group_by = false;
							$compatibles_group_by = $sel['values']['group_by'];
						}
						else
						{
							// There is more than one selector with group-by requirements, let's see the coincidences
							$compatibles_group_by = array_intersect( $compatibles_group_by, $sel['values']['group_by'] );
						}
					}
				}
			}
			
			if( $any_group_by )
				return true;

			// If there isn't any compatible group-by, this snippet is for Pro only (wrongly declared free compatible)
			if( ! $any_group_by && count( $compatibles_group_by ) == 0 )
				return false;
			
			return $compatibles_group_by;
		}
	}
	
	global $Fish_n_Ships_Wizard;
	$Fish_n_Ships_Wizard = new Fish_n_Ships_Wizard();
}