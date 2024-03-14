<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Admin_Pages extends CPT_Component {
	/**
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'admin_menu', array( $this, 'init_admin_pages' ) );
	}

	/**
	 * @param $id
	 * @param $title
	 * @param $content
	 *
	 * @return void
	 */
	private function render_callback( $id, $title, $content = false ) {
		?>
		<div class="wrap cpt-admin-page">
			<h1 class="cpt-admin-page-title"><?php echo $title; ?></h1>
			<?php
			if ( ! empty( $content ) ) {
				printf(
					'<div class="cpt-admin-page-content">%s</div>',
					'tools' == $id ? $content : apply_filters( 'the_content', $content ) //phpcs:ignore Universal.Operators.StrictComparisons
				);
			}
			ob_start();
			do_settings_sections( $id );
			$fields = ob_get_clean();
			if ( ! empty( $fields ) ) {
				?>
				<form method="post" action="options.php" novalidate="novalidate">
					<?php settings_fields( $id ); ?>
					<?php echo $fields; ?>
					<?php submit_button(); ?>
				</form>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * @return array
	 */
	public function get_registered_admin_pages() {
		$admin_pages = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => CPT_UI_PREFIX . '_page',
				'post_status'    => 'publish',
			)
		);

		$registered_admin_pages = array();

		foreach ( $admin_pages as $page ) {
			$page_id     = ! empty( get_post_meta( $page->ID, 'id', true ) ) ? sanitize_title( get_post_meta( $page->ID, 'id', true ) ) : sanitize_title( $page->post_title );
			$page_parent = ! empty( get_post_meta( $page->ID, 'parent', true ) ) ? get_post_meta( $page->ID, 'parent', true ) : null;
			$page_order  = is_numeric( get_post_meta( $page->ID, 'order', true ) ) ? get_post_meta( $page->ID, 'order', true ) : null;
			$page_icon   = ! empty( get_post_meta( $page->ID, 'menu_icon', true ) ) ? get_post_meta( $page->ID, 'menu_icon', true ) : '';
			$admin_only  = 'true' == get_post_meta( $page->ID, 'admin_only', true ); //phpcs:ignore Universal.Operators.StrictComparisons
			if ( $page_parent && stripos( $page_parent, '/' ) !== false ) {
				$page_parent = explode( '/', $page_parent );
				$page_parent = end( $page_parent );
			}
			$registered_admin_pages[] = array(
				'id'         => $page_id,
				'parent'     => $page_parent,
				'order'      => $page_order,
				'menu_icon'  => $page_icon,
				'title'      => $page->post_title,
				'content'    => $page->post_content,
				'admin_only' => $admin_only,
			);
		}

		unset( $admin_pages );

		return (array) apply_filters( 'cpt_admin_pages_register', $registered_admin_pages );
	}

	/**
	 * @return void
	 */
	public function init_admin_pages() {
		$admin_pages = $this->get_registered_admin_pages();

		$admin_pages = array_merge( cpt_utils()->get_args( 'core-admin-pages' ), $admin_pages );

		foreach ( $admin_pages as $i => $page ) {
			$id         = ! empty( $page['id'] ) && is_string( $page['id'] ) ? $page['id'] : false;
			$parent     = ! empty( $page['parent'] ) && is_string( $page['parent'] ) ? $page['parent'] : false;
			$order      = ! empty( $page['order'] ) && is_numeric( $page['order'] ) ? $page['order'] : null;
			$icon       = ! empty( $page['menu_icon'] ) && is_string( $page['menu_icon'] ) ? $page['menu_icon'] : '';
			$title      = ! empty( $page['title'] ) && is_string( $page['title'] ) ? wp_kses_post( $page['title'] ) : false;
			$content    = ! empty( $page['content'] ) && is_string( $page['content'] ) ? $page['content'] : false;
			$capability = ! empty( $page['admin_only'] ) ? 'administrator' : 'edit_posts';

			if ( $parent && ! cpt_utils()->current_user_can_access_parent_page( $parent ) ) {
				return;
			}

			$notice_title = cpt_utils()->get_notices_title();
			$error_info   = cpt_utils()->get_registration_error_notice_info( $page, 'page' );

			if ( ! $id || ! $title ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'],
							'title'       => $notice_title,
							'message'     => __( 'Admin page registration was not successful ("id" and "title" args are required).', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);
						return $args;
					}
				);
				unset( $admin_pages[ $i ] );
				continue;
			}

			if ( in_array( $id, cpt_utils()->get_admin_pages_blacklist(), true ) ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'],
							'title'       => $notice_title,
							'message'     => __( 'Admin page reserved or already registered, try a different "id".', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);
						return $args;
					}
				);
				unset( $admin_pages[ $i ] );
				continue;
			}

			$callback = function () use ( $id, $title, $content ) {
				$this->render_callback( $id, $title, $content );
			};

			$registered_admin_page = $parent ? add_submenu_page( $parent, $title, $title, $capability, $id, $callback, $order ) : add_menu_page( $title, $title, $capability, $id, $callback, $icon, $order );

			if ( ! $registered_admin_page ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'] . '_core',
							'title'       => $notice_title,
							'message'     => __( 'Admin page registration was not successful.', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);
						return $args;
					}
				);
				unset( $admin_pages[ $i ] );
			}
		}

		unset( $admin_pages );
	}
}
