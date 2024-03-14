<?php
if ( ! class_exists( 'Elex_Review_Components' ) ) {
	class Elex_Review_Components {
		/**
		 * Initiate review component
		 *
		 * @param array $data
		 *  $data = [
		 *      'name' => (string) Plugin name
		 *      'basename' => (string) Plugin basename folder/main_file.php
		 *      'rating_url' => (string) Url to review the plugin
		 *      'documentation_url' => (string) Url to the plugin document
		 *      'support_url' => (string) Url to the support form
		 *  ]
		 */
		protected $data = array();
		public function __construct( array $data ) {
			$this->data = $data;
			add_action( 'activate_' . $data['basename'], array( $this, 'on_activation' ) );
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			add_action( 'admin_init', array( $this, 'update_get_options' ) );
			add_action( 'deactivate_' . $data['basename'], array( $this, 'delete_options' ), 1 );
		}

		public function update_get_options() {

			if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_GET['_wpnonce'] ) ) ) {
				return;
			}

			if ( ! isset( $_GET['review_component_action'] ) || ! isset( $_GET['plugin_basename'] ) ) {
				return;
			}
			
			if ( sanitize_text_field( $_GET['plugin_basename'] ) !== $this->data['basename'] ) {
				return;
			}

			$review_component_action = sanitize_text_field( $_GET['review_component_action'] );

			if ( 'troubleshoot_never_ask_again' === $review_component_action ) {
				$this->update_option( 'troubleshoot_never_ask_again', true );
			}

			if ( 'review_never_ask_again' === $review_component_action ) {
				$this->update_option( 'review_never_ask_again', true );
			}

			if ( 'review_will_do_it_later' === $review_component_action ) {
				$this->update_option( 'review_will_do_it_later', strtotime( '+1 day' ) );
			}

			wp_redirect( remove_query_arg( array( 'plugin_basename', 'review_component_action' ) ) );
		}

		public function on_activation() {
			$this->update_option( 'activation_date', current_time( 'mysql' ) );
		}
		public function get_option( $key ) {
			 return get_option( $this->data['basename'] . '_' . $key );
		}
		public function update_option( $key, $value ) {
			update_option( $this->data['basename'] . '_' . $key, $value );
		}
		public function admin_notice() {
			$activattion_date = $this->get_option( 'activation_date' );

			if ( ! $activattion_date ) {
				return;
			}
			$activattion_date = date_create( $activattion_date );
			$current_date     = date_create();
			$diff             = date_diff( $activattion_date, $current_date );

			if ( $diff->format( '%R%a days' ) < 7 ) {
				$this->show_trubleshoot();
			} else {
				$this->show_review();
			}
		}

		public function show_trubleshoot() {
		   $trouble = $this->get_option( 'troubleshoot_never_ask_again' );
			if ( $trouble ) {
				return;
			}

		   include __DIR__ . '/resources/troubleshoot.php';
		}

		public function show_review() {
			$never_ask_again = $this->get_option( 'review_never_ask_again' );
			if ( $never_ask_again ) {
				return;
			}

			if ( $this->get_option( 'review_will_do_it_later' ) > strtotime( 'now' ) ) {
				return;
			}

			include __DIR__ . '/resources/review.php';
		}
		public function delete_options() {
			delete_option( $this->data['basename'] . '_review_never_ask_again' );
			delete_option( $this->data['basename'] . '_review_will_do_it_later' );
			delete_option( $this->data['basename'] . '_troubleshoot_never_ask_again' );
		}
	}
}


