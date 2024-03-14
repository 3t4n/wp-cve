<?php
/**
 * The Fish_n_Ships_SB class. 
 *
 * This add the pane on WooCommerce > Settings > Shipping > FnS Shipping Boxes
 *
 * @package Fish and Ships
 * @version 1.4.13
 */

defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Fish_n_Ships_SB' ) ) {

	class Fish_n_Ships_SB {

		/**
		 * Constructor.
		 *
		 * @since 1.2.5
		 */
		public function __construct() {

			// Add the sub tab option
			add_filter( 'woocommerce_get_sections_shipping', array($this, 'wc_sections'), 20, 1 );
			
			// Add the settings in the WC way
			add_filter( 'woocommerce_get_settings_shipping', array ($this, 'wc_settings') , 10, 2 );
			
			// Print the custom fields
			add_action( 'woocommerce_admin_field_fns-shipping-boxes-fields', array ($this, 'admin_fields' ) );
			
			// Save the boxes info fields
			add_action( 'woocommerce_settings_save_shipping', array ($this, 'save_shipping' ) );
		}

		public function wc_sections ($sections) {
			
			$sections['fns-shipping-boxes'] = 'FnS Shipping Boxes';
			
			return $sections;
		}
		
		public function wc_settings ($settings, $current_section) {
			
			if ( $current_section == 'fns-shipping-boxes') {
				
				$settings = array (
				
								array (
									'title' => 'Fish and Ships Shipping Boxes',
									'type'  => 'title',
									'id'    => 'fns-shipping-boxes'
								),
								array (
									'type'  => 'fns-shipping-boxes-fields',
								),
								array (
									'type'	=> 'sectionend',
									'id'	=> 'fns-shipping-boxes'
								)
							);
			}
			return $settings;
		}

		public function admin_fields ( $value ) {
			
			global $Fish_n_Ships;
			
			if ( !$Fish_n_Ships->im_pro() ) {
				?>
				<p><?php echo wp_kses( __('This feature is only available in <strong>Fish and Ships Pro</strong>.', 'fish-and-ships'),
					 array('strong'=>array())
				); ?></p>
				<p><a href="https://www.youtube.com/watch?v=y2EJFluXx9Q" target="_blank" class="fns-video-link" title="Watch shipping boxes video on YouTube"><img src="<?php echo WC_FNS_URL; ?>assets/img/shipping-boxes-video-preview.jpg" width="800" height="450" alt="Shipping boxes flat rate video" /></a></p>
				<p><?php _e('Here you can read more about:', 'fish-and-ships'); ?><br />
				<a href="https://www.wp-centrics.com/shipping-boxes-flat-rate/" target="_blank">https://www.wp-centrics.com/shipping-boxes-flat-rate/</a></p>
				<style>
				.woocommerce-save-button { display: none !important; }
				</style>
				<?php				
				return;
			}
			
			$boxes = $Fish_n_Ships->get_option('boxes');
						
			$weight_unit = ' (' . get_option('woocommerce_weight_unit') . ')';
			$dimension_unit = ' (' . get_option('woocommerce_dimension_unit') . ')';


			// Get all shipping methods IDs for checking boxes usage
			$zone_ids = array_keys( array('') + WC_Shipping_Zones::get_zones() );

			// Loop through shipping Zones IDs
			foreach ( $zone_ids as $zone_id ) {
				
				// Get the shipping Zone object & methods
				$shipping_zone = new WC_Shipping_Zone($zone_id);
				$shipping_methods = $shipping_zone->get_shipping_methods();

				// Loop through each shipping methods set for the current shipping zone
				foreach ( $shipping_methods as $instance_id => $shipping_method ) {
					
					if ($shipping_method->id != 'fish_n_ships') continue;
					
					// Read settings
					$settings = get_option('woocommerce_fish_n_ships_' . $instance_id . '_settings');
					if ( is_array($settings) && isset($settings['shipping_rules']) ) {
						
						foreach ( $settings['shipping_rules'] as $rule) {
							if ( !isset($rule['actions']) ) continue;
							
							// Loop through actions
							foreach ( $rule['actions'] as $action ) {
								if ( !isset($action['method']) || $action['method'] != 'boxes' ) continue;
								
								// Seek all boxes on every boxes action
								foreach ( $boxes as $kbox=>$box) {

									if ( !isset($boxes[$kbox]['usage']) ) $boxes[$kbox]['usage'] = array();
									if ( isset($action['values']) && isset($action['values']['active_'.$box['id']]) && $action['values']['active_'.$box['id']] == '1' ) {
										$boxes[$kbox]['usage'][] = $instance_id;
									}
								}
							}
						}
					}
				}
			}
			
			if ( version_compare(PHP_VERSION, '7.3.0', '<') ) {
				echo '<div class="info inline error"><p>Box Packer algorithm requires at least PHP 7.3, you have: ' . esc_html( PHP_VERSION ) . '</p></div>';
			}

			echo '<div class="fns-sb-bigger">';
			echo apply_filters ( 'the_content', esc_html__('Define all the boxes for shipping here, with internal dimensions and max weight.', 'fish-and-ships') );
			echo '</div>';
			
			echo '<a href="https://www.youtube.com/watch?v=y2EJFluXx9Q" target="_blank" title="Watch shipping boxes video on YouTube" style="float: right; margin-left: 5px;" class="button" style="margin:0 10px"><span class="dashicons-before dashicons-video-alt3 fns-yt-on-button"></span>' . esc_html__('Watch video', 'fish-and-ships') . '</a>';
			echo '<a href="https://www.wp-centrics.com/shipping-boxes-flat-rate/" target="_blank" title="Shipping boxes flat rate" style="float: right" class="button" style="margin:0 10px"><span class="dashicons-before dashicons-editor-help fns-yt-on-button"></span>' . esc_html__('More info', 'fish-and-ships') . '</a>';
			
			
			echo apply_filters ( 'the_content', esc_html__('After that, you can choose which of the boxes are available on each individual shipping method . Boxes in use can\'t be deleted.', 'fish-and-ships') );
			?>
			</table>
			<table class="wp-list-table widefat fixed striped table-view-list" id="fns_sb_table">
				<thead><tr>
					<th class="fns-sb-id">ID</th>
					<th><?php esc_html_e('Box name', 'fish-and-ships'); ?></th>
					<th class="fns-sb-num"><?php esc_html_e('Length', 'fish-and-ships'); echo esc_html($dimension_unit); ?></th>
					<th class="fns-sb-num"><?php esc_html_e('Width', 'fish-and-ships'); echo esc_html($dimension_unit); ?></th>
					<th class="fns-sb-num"><?php esc_html_e('Height', 'fish-and-ships'); echo esc_html($dimension_unit); ?></th>
					<th class="fns-sb-num"><?php esc_html_e('Max. weight', 'fish-and-ships'); echo esc_html($weight_unit); ?></th>
					<th class="fns-sb-actions"><?php esc_html_e('Actions', 'fish-and-ships'); ?></th>
				</tr></thead>
				<tbody>
				<?php
				if ( !is_array($boxes) || count($boxes) == 0 ) $boxes = array('');
				
				$max_id = 0;
				foreach ($boxes as $box) {
					if ( !isset($box['id']) ) continue;
					if ( $max_id < $box['id'] ) $max_id = $box['id'];
					$this->print_form_line($box);
				}
				
				// Empty? Let's put one line
				if ( $max_id == 0 ) {
					$this->print_form_line( array('id' => '', 'name' => '', 'width' => 0, 'height' => 0, 'length' => 0, 'weight' => 0) );
				}
				?>
				</tbody>
				<tfoot>
					<tr><td colspan="7">
						<a href="#" class="button add-box"></span> Add a new box</a>
					</td></tr>
				</tfoot>
			</table>
			<input type="hidden" name="fns-sb-max_id" value="<?php echo esc_attr($max_id); ?>" />
			<table class="form-table">
			<?php
		}
		
		function print_form_line ($box) {
			
			echo '<tr>';
			echo '<td class="fns-sb-id">#<span class="numid">' . esc_html( $box['id'] ) . '</span>';
			echo '<input type="hidden" name="fns-sb-id[]" value="' . esc_attr( $box['id'] ) . '" /></td>';
			echo '<td><input type="text" name="fns-sb-name[]" value="' . esc_attr($box['name']) . '" required /></td>';
			echo '<td class="fns-sb-num"><input type="text" name="fns-sb-length[]" value="' . esc_attr($box['length']) . '" class="wc_fns_input_positive_decimal" required /></td>';
			echo '<td class="fns-sb-num"><input type="text" name="fns-sb-width[]" value="' . esc_attr($box['width']) . '" class="wc_fns_input_positive_decimal" required /></td>';
			echo '<td class="fns-sb-num"><input type="text" name="fns-sb-height[]" value="' . esc_attr($box['height']) . '" class="wc_fns_input_positive_decimal" required /></td>';
			echo '<td class="fns-sb-num"><input type="text" name="fns-sb-weight[]" value="' . esc_attr($box['weight']) . '" class="wc_fns_input_positive_decimal" required /></td>';
			echo '<td class="fns-sb-actions">';
			if ( !isset($box['usage']) || $box['usage'] == array() ) {
				echo 'Unused. [<a href="#" class="fns-sb-delete">delete</a>]';
			} else {
				$n=0;
				foreach ( $box['usage'] as $method_id ) {
					echo $n==0 ? 'Used on: ' : ', '; $n++;
					echo '<a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&instance_id=' . esc_attr($method_id) ) . '">#' . $method_id . '</a>';
				}
			}
			echo '</td></tr>';
			
		}
		
		public function save_shipping( $current_tab ) {

			global $Fish_n_Ships;
									
			if ( !isset( $_POST['fns-sb-max_id'] ) ) return;
			
			$boxes = $this->sanitize_raw_form();
			
			if ( $boxes !== false ) {
				$Fish_n_Ships->set_option( 'boxes', $boxes );
			}

		}
		
		function sanitize_raw_form () {
			
			global $Fish_n_Ships;
			
			if ( !isset( $_POST['fns-sb-id'] ) || !is_array ($_POST['fns-sb-id']) ) return array();

			$boxes = array();
			$max_id = isset ( $_POST['fns-sb-max_id'] ) ? intval ($_POST['fns-sb-max_id']) : 1;
			
			foreach ( $_POST['fns-sb-id'] as $key => $id ) {
				
				// Sanitize ID and give it next number if it is a new box
				$id = $Fish_n_Ships->sanitize_number( $id, 'positive-integer' );
				if ($id == 0) {
					$max_id++;
					$id = $max_id;
				}
				
				// Sanitize name and give it one
				$name = sanitize_text_field( $_POST['fns-sb-name'][$key] );
				if ($name == '') $name = 'Unnamed #' . $id;

				$boxes[] = array (
				
						'id'     => $id,
						'name'   => $name,
						'length' => $Fish_n_Ships->sanitize_number( $_POST['fns-sb-length'][$key], 'positive-decimal' ),
						'width'  => $Fish_n_Ships->sanitize_number( $_POST['fns-sb-width' ][$key], 'positive-decimal' ),
						'height' => $Fish_n_Ships->sanitize_number( $_POST['fns-sb-height'][$key], 'positive-decimal' ),
						'weight' => $Fish_n_Ships->sanitize_number( $_POST['fns-sb-weight'][$key], 'positive-decimal' ),
				);
			}
			
			if ( isset($_GET['fns-sb-reset']) ) return array();
			return $boxes;
		}
		
		
	} // End Fish_n_Ships_SB class.

	$Fish_n_Ships_SB = new Fish_n_Ships_SB();
}