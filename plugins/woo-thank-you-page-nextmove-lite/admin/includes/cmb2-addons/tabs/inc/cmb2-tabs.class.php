<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class xlwcty_CMB2_Tabs
 * @package cmb2_tabs\inc
 * @since   1.0.1
 */
if ( ! class_exists( 'cmb2_XL_Tabs' ) ) {

	class cmb2_XL_Tabs {

		protected static $instance = null;

		/**
		 * xlwcty_CMB2_Tabs constructor.
		 */
		public function __construct() {
			add_action( 'cmb2_render_tabs', array( $this, 'render' ), 10, 3 );
			add_filter( 'cmb2_sanitize_tabs', array( $this, 'save' ), 10, 4 );
		}

		/**
		 * Return an instance of this class.
		 * @since     1.0.0
		 * @return    object    A single instance of this class.
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Hook: Render field
		 *
		 * @param $field_object
		 * @param $escaped_value
		 * @param $object_id
		 */
		public function render( $field_object, $escaped_value, $object_id ) {
			$settings   = $field_object->args['tabs'];
			$attrs      = $field_object->args['attributes'];
			$attributes = '';
			foreach ( $attrs as $attr => $val ) {
				// if data attribute, use single quote wraps, else double
				$quotes     = false !== stripos( $attr, 'data-' ) ? "'" : '"';
				$attributes .= sprintf( ' %1$s=%3$s%2$s%3$s', $attr, $val, $quotes );
			}
			?>
            <div class="dtheme-cmb2-tabs" <?php echo $attributes; ?>>

                <ul>
					<?php foreach ( $settings['tabs'] as $key => $tab ): ?>
                        <li><a href="#<?php echo $tab['id']; ?>"><?php echo $tab['title']; ?></a></li>
					<?php endforeach; ?>
                </ul>

				<?php foreach ( $settings['tabs'] as $key => $tab ): ?>
                    <div id="<?php echo $tab['id']; ?>" class='angular_components'>

						<?php
						// set options to cmb2
						$setting_fields = array_merge( $settings['args'], array( 'fields' => $tab['fields'] ) );
						$CMB2           = new \CMB2( $setting_fields, $object_id );

						foreach ( $tab['fields'] as $key_field => $field ) {
							if ( $CMB2->is_options_page_mb() ) {

								$CMB2->object_type( $settings['args']['object_type'] );
							}

							// cmb2 render field
							$CMB2->render_field( $field );
						}
						?>

                    </div>
				<?php endforeach; ?>
            </div>
			<?php
		}

		/**
		 * Hook: Save field values
		 *
		 * @param $override_value
		 * @param $value
		 * @param $post_id
		 * @param $data
		 */
		public function save( $override_value, $value, $post_id, $data ) {
			foreach ( $data['tabs']['tabs'] as $tab ) {
				$setting_fields = array_merge( $data['tabs']['args'], array( 'fields' => $tab['fields'] ) );
				$CMB2           = new \CMB2( $setting_fields, $post_id );

				if ( $CMB2->is_options_page_mb() ) {
					$cmb2_options = cmb2_options( $post_id );
					$values       = $CMB2->get_sanitized_values( $_POST );
					foreach ( $values as $key => $value ) {
						$cmb2_options->update( $key, $value );
					}
				} else {
					$CMB2->save_fields();
				}
			}
		}

	}

}