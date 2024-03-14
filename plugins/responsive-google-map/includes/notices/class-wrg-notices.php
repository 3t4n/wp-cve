<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists('Wrg_Notices') ) {

    class Wrg_Notices {

        private static $instance;

        private static $version = '1.0.0';
        
        private static $notices = array();

        public function __construct() {
            add_action( 'admin_notices', array( $this, 'initialize_notices' ), 30 );
            // add_action();
        }

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function initialize_notices() {
            
            $defaults = array(
				'id'                         => '',      // Optional, Notice ID. If empty it set `wrg-notices-id-<$array-index>`.
				'type'                       => 'info',  // Optional, Notice type. Default `info`. Expected [info, warning, notice, error].
				'message'                    => '',      // Optional, Message.
				'show_if'                    => true,    // Optional, Show notice on custom condition. E.g. 'show_if' => if( is_admin() ) ? true, false, .
				'repeat-notice-after'        => '',      // Optional, Dismiss-able notice time. It'll auto show after given time.
				'display-notice-after'       => false,   // Optional, Dismiss-able notice time. It'll auto show after given time.
				'class'                      => '',      // Optional, Additional notice wrapper class.
				'priority'                   => 10,      // Priority of the notice.
				'display-with-other-notices' => true,    // Should the notice be displayed if other notices  are being displayed.
				'is_dismissible'             => true,
            );
            
            // Count for the notices that are rendered.
			$notices_displayed = 0;

			// sort the array with priority.
            usort( self::$notices, array( $this, 'sort_notices' ) );
            
            foreach ( self::$notices as $key => $notice ) {

                $notice = wp_parse_args( $notice, $defaults );

				$notice['id'] = self::get_notice_id( $notice, $key );

                $notice['classes'] = self::get_wrap_classes( $notice );
                
                // Notices visible after transient expire.
				if ( isset( $notice['show_if'] ) && true === $notice['show_if'] ) {

					// don't display the notice if it is not supposed to be displayed with other notices.
					if ( 0 !== $notices_displayed && false === $notice['display-with-other-notices'] ) {
						continue;
					}

					if ( self::is_expired( $notice ) ) {

						self::markup( $notice );
						++$notices_displayed;
					}
				}
            }
        }

        public static function markup( $notice = array() ) {

			do_action( 'wrg_notice_before_markup' );

			do_action( "wrg_notice_before_markup_{$notice['id']}" );

			?>
			<div id="<?php echo esc_attr( $notice['id'] ); ?>" class="<?php echo esc_attr( $notice['classes'] ); ?>" data-repeat-notice-after="<?php echo esc_attr( $notice['repeat-notice-after'] ); ?>">
				<div class="notice-container">
					<?php do_action( "wrg_notice_inside_markup_{$notice['id']}" ); ?>
					<?php echo wp_kses_post( $notice['message'] ); ?>
				</div>
			</div>
			<?php

			do_action( "wrg_notice_after_markup_{$notice['id']}" );

			do_action( 'wrg_notice_after_markup' );

		}

        private static function is_expired( $notice ) {
			$transient_status = get_transient( $notice['id'] );

			if ( false === $transient_status ) {

				if ( isset( $notice['display-notice-after'] ) && false !== $notice['display-notice-after'] ) {

					if ( 'delayed-notice' !== get_user_meta( get_current_user_id(), $notice['id'], true ) &&
						'notice-dismissed' !== get_user_meta( get_current_user_id(), $notice['id'], true ) ) {
						set_transient( $notice['id'], 'delayed-notice', $notice['display-notice-after'] );
						update_user_meta( get_current_user_id(), $notice['id'], 'delayed-notice' );

						return false;
					}
				}

				// Check the user meta status if current notice is dismissed or delay completed.
				$meta_status = get_user_meta( get_current_user_id(), $notice['id'], true );

				if ( empty( $meta_status ) || 'delayed-notice' === $meta_status ) {
					return true;
				}
			}

			return false;
        }
        

        private static function get_wrap_classes( $notice ) {
			$classes = array( 'wrg-notice', 'notice' );

			if ( $notice['is_dismissible'] ) {
				$classes[] = 'is-dismissible';
			}

			$classes[] = $notice['class'];
			if ( isset( $notice['type'] ) && '' !== $notice['type'] ) {
				$classes[] = 'notice-' . $notice['type'];
			}

			return esc_attr( implode( ' ', $classes ) );
		}

        private static function get_notice_id( $notice, $key ) {
			if ( isset( $notice['id'] ) && ! empty( $notice['id'] ) ) {
				return $notice['id'];
			}

			return 'wrg-notices-id-' . $key;
		}

        public function sort_notices( $array1, $array2 ) {
			if ( ! isset( $array1['priority'] ) ) {
				$array1['priority'] = 10;
			}
			if ( ! isset( $array2['priority'] ) ) {
				$array2['priority'] = 10;
			}

			return $array1['priority'] - $array2['priority'];
		}
    }

    Wrg_Notices::get_instance();
}