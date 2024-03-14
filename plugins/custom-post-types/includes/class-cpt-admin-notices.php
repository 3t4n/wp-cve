<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Admin_Notices extends CPT_Component {

	public $has_notices = false;

	/**
	 * @return void
	 */
	public function init_hooks() {
		add_filter( 'cpt_ajax_actions_register', array( $this, 'ajax_actions' ) );
		add_action( 'admin_init', array( $this, 'init_notices' ) );
	}

	/**
	 * @param $actions
	 *
	 * @return mixed
	 */
	public function ajax_actions( $actions ) {
		$actions['cpt-dismiss-notice'] = array(
			'required' => array( 'key' ),
			'callback' => function ( $params ) {
				$notice   = $params['key'];
				$duration = $params['duration'];
				$this->dismiss_notice( $notice, ( 'lifetime' == $duration ? -1 : intval( $duration ) ) ); //phpcs:ignore Universal.Operators.StrictComparisons
				return 'OK';
			},
		);
		return $actions;
	}

	/**
	 * @return array
	 */
	private function get_dismissed_notices() {
		$dismissed_notices = get_option( CPT_OPTIONS_PREFIX . 'dismissed_notices', array() );
		return is_array( $dismissed_notices ) ? $dismissed_notices : array();
	}

	/**
	 * @param $id
	 * @param $days
	 *
	 * @return void
	 */
	private function dismiss_notice( $id, $days = 2 ) {
		$dismissed_notices = $this->get_dismissed_notices();
		if ( $days < 0 ) {
			$days = 36500;
		}
		$dismissed_notices[ $id ] = strtotime( "+$days day", time() );
		update_option( cpt_utils()->get_option_name( 'dismissed_notices' ), $dismissed_notices );
	}

	/**
	 * @param $id
	 *
	 * @return bool
	 */
	private function is_dismissed( $id ) {
		$dismissed_notices = $this->get_dismissed_notices();
		return ! empty( $dismissed_notices[ $id ] ) && time() < intval( $dismissed_notices[ $id ] );
	}

	/**
	 * @param $id
	 * @param $title
	 * @param $message
	 * @param $type
	 * @param $dismissible
	 * @param $buttons
	 *
	 * @return void
	 */
	private function init_notice( $id = false, $title = false, $message = '', $type = 'warning', $dismissible = false, $buttons = array() ) {
		if ( ! $id ) {
			return;
		}
		$id = sanitize_title( $id );
		if ( $this->is_dismissed( $id ) ) {
			return;
		}
		$type           = in_array( $type, array( 'error', 'warning', 'success', 'info' ), true ) ? $type : 'info';
		$class          = "notice notice-$type cpt-notice" . ( $dismissible ? ' is-dismissible' : '' );
		$notice_buttons = array();
		if ( is_array( $buttons ) ) {
			foreach ( $buttons as $button ) {
				$is_cta           = ! empty( $button['cta'] ) ? $button['cta'] : false;
				$notice_buttons[] = sprintf(
					'<a href="%1$s" %2$s target="%3$s" title="%4$s" aria-label="%4$s">%4$s%5$s</a>',
					! empty( $button['link'] ) ? $button['link'] : '',
					$is_cta ? 'class="button button-secondary"' : '',
					! empty( $button['target'] ) ? $button['target'] : '_self',
					! empty( $button['label'] ) ? esc_html( $button['label'] ) : '',
					! empty( $button['target'] ) && '_blank' == $button['target'] && ! $is_cta ? '<span class="dashicons dashicons-external"></span>' : '' //phpcs:ignore Universal.Operators.StrictComparisons
				);
			}
		}
		if ( $dismissible ) {
			$button_label     = true === $dismissible ? __( 'Dismiss notice', 'custom-post-types' ) : sprintf( __( 'Dismiss notice for %s days', 'custom-post-types' ), (int) $dismissible ); //phpcs:ignore Universal.Operators.StrictComparisons
			$notice_buttons[] = sprintf(
				'<a href="#" class="cpt-dismiss-notice" data-notice="%1$s" data-duration="%2$s" title="%3$s" aria-label="%3$s">%3$s</a>',
				$id,
				( true === $dismissible ? 'lifetime' : $dismissible ), //phpcs:ignore Universal.Operators.StrictComparisons
				$button_label
			);
		}

		$title = ! empty( $title ) ? sprintf( '<p class="notice-title">%s</p>', wp_kses_post( $title ) ) : '';

		add_action(
			'admin_notices',
			function () use ( $class, $message, $notice_buttons, $title ) {
				printf(
					'<div class="%s">%s<div class="message">%s</div>%s</div>',
					$class,
					$title,
					wp_kses_post( $message ),
					! empty( $notice_buttons ) ? '<p class="actions">' . implode( '', $notice_buttons ) . '</p>' : ''
				);
			}
		);

		$this->has_notices = true;
	}

	/**
	 * @return array
	 */
	private function get_registered_notices() {
		$notices = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => CPT_UI_PREFIX . '_notice',
				'post_status'    => 'publish',
			)
		);

		$registered_notices = array();

		foreach ( $notices as $notice ) {
			$id          = ! empty( get_post_meta( $notice->ID, 'id', true ) ) ? sanitize_title( get_post_meta( $notice->ID, 'id', true ) ) : sanitize_title( $notice->post_title );
			$type        = ! empty( get_post_meta( $notice->ID, 'type', true ) ) ? get_post_meta( $notice->ID, 'type', true ) : 'info';
			$dismissable = ! empty( get_post_meta( $notice->ID, 'dismissible', true ) ) ? get_post_meta( $notice->ID, 'dismissible', true ) : false;
			$admin_only  = 'true' == get_post_meta( $notice->ID, 'admin_only', true ); //phpcs:ignore Universal.Operators.StrictComparisons
			$buttons     = ! empty( get_post_meta( $notice->ID, 'buttons', true ) ) ? get_post_meta( $notice->ID, 'buttons', true ) : false;
			if ( $dismissable < 0 ) {
				$dismissable = true;
			}
			$registered_notices[] = array(
				'id'          => $id,
				'title'       => $notice->post_title,
				'message'     => wpautop( $notice->post_content ),
				'type'        => $type,
				'dismissible' => $dismissable,
				'admin_only'  => $admin_only,
				'buttons'     => $buttons,
			);
		}

		unset( $notices );

		return (array) apply_filters( 'cpt_admin_notices_register', $registered_notices );
	}

	/**
	 * @return void
	 */
	public function init_notices() {
		$notices = $this->get_registered_notices();

		foreach ( $notices as $i => $notice ) {
			$id          = ! empty( $notice['id'] ) && is_string( $notice['id'] ) ? $notice['id'] : false;
			$title       = ! empty( $notice['title'] ) ? $notice['title'] : false;
			$message     = ! empty( $notice['message'] ) && is_string( $notice['message'] ) ? $notice['message'] : false;
			$type        = ! empty( $notice['type'] ) ? $notice['type'] : false;
			$dismissible = ! empty( $notice['dismissible'] ) ? $notice['dismissible'] : false;
			$buttons     = ! empty( $notice['buttons'] ) ? $notice['buttons'] : false;
			$admin_only  = ! empty( $notice['admin_only'] ) ? $notice['admin_only'] : false;
			if (
				( $admin_only && ! current_user_can( 'manage_options' ) ) ||
				( ! $admin_only && ! current_user_can( 'edit_posts' ) )
			) {
				continue;
			}
			if ( ! $id || ! $message ) {
				$this->init_notice(
					'notice_args_error_' . $i,
					cpt_utils()->get_notices_title(),
					__( 'Notice registration was not successful ("id" and "message" args are required).', 'custom-post-types' ),
					'error'
				);
				continue;
			}
			$this->init_notice( $id, $title, $message, $type, $dismissible, $buttons );
		}
	}
}
