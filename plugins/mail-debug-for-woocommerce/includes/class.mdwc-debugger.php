<?php

if ( ! class_exists( 'MDWC_Debugger' ) ) {
	class MDWC_Debugger {
		/**
		 * Single instance of the class.
		 *
		 * @var MDWC_Debugger
		 */
		private static $instance;

		/**
		 * The current WC email.
		 *
		 * @var WC_Email|null
		 */
		private $current_email;

		/**
		 * The current debug ID.
		 *
		 * @var int
		 */
		private $current_debug_id;

		/**
		 * Singleton implementation.
		 *
		 * @return MDWC_Debugger
		 */
		public static function get_instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * The constructor.
		 */
		private function __construct() {
			if ( mdwc_is_debug_enabled() ) {
				add_filter( 'wp_mail', array( $this, 'set_all_emails_to' ), 9, 1 );
				add_filter( 'wp_mail', array( $this, 'set_all_emails_to' ), 999, 1 );
				add_filter( 'wp_mail', array( $this, 'wp_mail' ), 10, 1 );
				add_action( 'woocommerce_email_header', array( $this, 'set_current_email' ), 10, 2 );
				add_action( 'phpmailer_init', array( $this, 'additional_info' ), 999 );
			}
		}

		/**
		 * Set all emails to
		 *
		 * @param array $args
		 *
		 * @return array
		 * @since 1.0.2
		 */
		public function set_all_emails_to( $args = array() ) {
			$all_emails_to = get_option( 'mdwc_all_emails_to', '' );
			if ( $all_emails_to ) {
				$args['to'] = $all_emails_to;
			}

			return $args;
		}

		public function set_current_email( $heading, $email = false ) {
			if ( $email ) {
				$this->current_email = $email;
			}
		}

		public function debug_email( $args = array() ) {
			$defaults = array(
				'to'                 => '',
				'subject'            => '',
				'message'            => '',
				'headers'            => '',
				'attachments'        => array(),
				'email_id'           => false,
				'email_title'        => '',
				'email_object'       => false,
				'email_object_class' => false,
				'email_object_id'    => false,
				'customer_email'     => '',
			);
			$args     = wp_parse_args( $args, $defaults );

			if ( $this->current_email && $this->current_email instanceof WC_Email ) {
				$args['email_id']    = $this->current_email->id;
				$args['email_title'] = $this->current_email->get_title();
				if ( isset( $this->current_email->object ) && $this->current_email->object && is_object( $this->current_email->object ) ) {
					$object       = $this->current_email->object;
					$object_class = get_class( $object );
					$object_id    = is_callable( array( $object, 'get_id' ) ) ? $object->get_id() : false;

					$nice_class = $object_class;
					$slash_pos  = strrpos( $nice_class, '\\' );
					if ( $slash_pos !== false ) {
						$nice_class = substr( $nice_class, $slash_pos + 1 );
					}

					$object_info = $nice_class;

					if ( $object_id ) {
						$object_info .= ' #' . $object_id;
					}
					$args['email_object']       = $object_info;
					$args['email_object_class'] = $object_class;
					$args['email_object_id']    = $object_id;
				}

				$args['customer_email'] = ! ! $this->current_email->is_customer_email() ? 'yes' : 'no';
			}

			$this->current_debug_id = wp_insert_post(
				array(
					'post_type'   => 'mail-debug',
					'post_status' => 'publish',
					'meta_input'  => $args,
				)
			);

			$this->current_email = false;
		}

		public function wp_mail( $args = array() ) {
			$this->debug_email( $args );

			return $args;
		}

		/**
		 * @param PHPMailer $mailer
		 */
		public function additional_info( $mailer ) {
			if ( $this->current_debug_id ) {

				if ( $mailer->Ical ) {
					update_post_meta( $this->current_debug_id, 'ical', $mailer->Ical );
				}

				if ( $mailer->AltBody ) {
					update_post_meta( $this->current_debug_id, 'alt_body', $mailer->AltBody );
				}

				if ( $mailer->From ) {
					update_post_meta( $this->current_debug_id, 'from', $mailer->From );
				}

				if ( $mailer->FromName ) {
					update_post_meta( $this->current_debug_id, 'from_name', $mailer->FromName );
				}

				$this->current_debug_id = false;
			}
		}
	}
}