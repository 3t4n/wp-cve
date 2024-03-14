<?php

namespace SocialLinkPages;

use SocialLinkPages\Page;

class Db extends Singleton {

	protected function setup() {
		add_action( 'init', array( $this, 'maybe_migrate' ) );
	}

	public function maybe_migrate() {
		$slugs = get_option( Social_Link_Pages()->plugin_name_friendly
		                     . '_slugs' );

		if ( empty( $slugs ) ) {
			return false;
		}

		$alert = array(
			'success_count' => 0,
			'error'         => array()
		);
		foreach ( $slugs as $slug ) {
			$page_data = get_option(
				sprintf(
					'%s_page_%s',
					Social_Link_Pages()->plugin_name_friendly,
					$slug
				)
			);

			if ( empty( $page_data ) ) {
				continue;
			}

			$post_id = $this->create_page( $page_data );

			if ( false === $post_id ) {
				$alert['error'][] = $slug;
			} else {
				$alert['success_count'] ++;
			}
		}

		add_action( 'admin_notices', function () use ( $alert ) {
			$this->migration_admin_notice( $alert );
		} );

		add_option( Social_Link_Pages()->plugin_name_friendly
		            . '_slugs_migrated', $slugs, false );
		delete_option( Social_Link_Pages()->plugin_name_friendly
		               . '_slugs' );
	}

	public function sanitize_page_data( $page_data ) {
		// If it's a string (maybe json), decode it.
		$page_data = is_string( $page_data ) ? json_decode( $page_data )
			: $page_data;

		$page_data = $this->santize_arr( (array) $page_data );
		$page_data = stripslashes_deep( $page_data );

		if ( empty($page_data['buttons']) ) {
			$page_data['buttons'] = [];
		}

		return (object) $page_data;
	}

	/**
	 * Sanitize all items in an array.
	 *
	 * @link https://woocommerce.wp-a2z.org/oik_api/wc_clean/
	 *
	 * @param $var
	 *
	 * @return array
	 */
	public function santize_arr( $var ) {
		if ( is_object( $var ) ) {
			$var = (array) $var;
		}

		if ( is_array( $var ) ) {
			return array_map( array( $this, 'santize_arr' ), $var );
		} else {
			$my_allowed = wp_kses_allowed_html( 'post' );
			// iframe
			$my_allowed['iframe'] = array(
				'src'             => array(),
				'height'          => array(),
				'width'           => array(),
				'frameborder'     => array(),
				'allowfullscreen' => array(),
			);

			return is_scalar( $var ) ? wp_kses( $var, $my_allowed ) : $var;
		}
	}

	public function migration_admin_notice( $alert ) {

		$message = '';
		if ( $alert['success_count'] > 0 ) {
			$message .= sprintf( '%d pages were successfully migrated. ',
				$alert['success_count'] );
		}
		if ( ! empty( $alert['error'] ) ) {
			$message .= sprintf( 'The following pages could not be migrated: <br>- %s',
				implode( '<br>- ', $alert['error'] )
			);
			$message .= '<br>Please contact support at <a href="https://sociallinkpages.com/contact/" target="_blank">sociallinkpages.com/contact</a> and we can help!';
		}

		if ( ! empty( $message ) ) {
			echo sprintf( '<div class="notice notice-warning is-dismissible">
            	 <p>%s</p>
         	</div>', $message );
		}
	}

	public function create_page( $page_data ) {
		global $wpdb;

		if ( empty( $page_data['slug'] ) ) {
			return false;
		}

		$slug = sanitize_title( $page_data['slug'] );

		if ( ! Db::instance()->is_slug_unique( $slug ) ) {
			return false;
		}

		$page_data = Db::instance()->sanitize_page_data( $page_data );

		// Instead of `wp_update_post`, which strips slashes.
		$success = $wpdb->insert(
			$wpdb->posts,
			array(
				'post_author'       => get_current_user_id(),
				'post_status'       => 'publish',
				'post_name'         => $slug,
				'post_title'        => $slug,
				'post_type'         => Social_Link_Pages()->plugin_name_friendly,
				'post_content'      => json_encode( $page_data,
					JSON_UNESCAPED_SLASHES ),
				'post_date'         => current_time( 'mysql', false ),
				'post_date_gmt'     => current_time( 'mysql', true ),
				'post_modified'     => current_time( 'mysql', false ),
				'post_modified_gmt' => current_time( 'mysql', true )
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);

		return false === $success ? false : $wpdb->insert_id;
	}

	public function is_slug_unique( $slug ) {
		global $wpdb;

		$slug = sanitize_title( $slug );

		$post_count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT count(post_title) FROM $wpdb->posts WHERE post_name like %s;",
				$slug
			)
		);

		return 0 === (int) $post_count;
	}

	public function page_data_from_post( $post ) {

		if ( empty( $post ) ) {
			return false;
		}

		if ( ! $post instanceof \WP_Post ) {
			$post = get_post( $post, OBJECT );
		}

		if ( ! $post instanceof \WP_Post
		     || 'publish' !== $post->post_status
		) {
			return false;
		}

		$post->post_content = htmlspecialchars_decode( $post->post_content,
			ENT_NOQUOTES );
		$post->post_content = str_replace( '&quot;', '\"',
			$post->post_content );

		$page_data = ! empty( $post->post_content )
			? (object) json_decode( $post->post_content )
			: (object) array();
		$page_data = stripslashes_deep( $page_data );

		$page_data = (object) array_merge( (array) Page::instance()->get_default_page_data(), (array) $page_data );

		$page_data->id                = $post->ID;
		$page_data->slug              = $post->post_name;
		$page_data->modified_at       = $post->post_modified_gmt;
		$page_data->created_at        = $post->post_date_gmt;
		$page_data->showCookieConsent = ! empty( $page_data->showCookieConsent )
			? (bool) intval( $page_data->showCookieConsent )
			: false;

		if ( empty( $page_data->buttons ) ) {
			$page_data->buttons = [];
		}

		foreach ( $page_data->buttons as &$button ) {
			$button->isNew = $button->isNew && 'true' === $button->isNew ? true : false;
			$button->type  = ucfirst( $button->type ); // Fixes legacy misnaming.
		}

		return apply_filters(
			Social_Link_Pages()->plugin_name_friendly
			. '_page_data_from_post',
			(object) array_merge( (array) Page::instance()
			                                  ->get_default_page_data(),
				(array) $page_data )
		);
	}

	public function update_page_data( $post_id, $new_page_data ) {
		global $wpdb;

		$post = get_post( $post_id );

		if ( ! $post ) {
			return false;
		}

		$permission_check = apply_filters(
			Social_Link_Pages()->plugin_name_friendly
			. '_update_page_data_permission_check',
			true,
			$post
		);

		if ( ! $permission_check ) {
			return false;
		}

		$page_data = Db::instance()->page_data_from_post( $post );

		if ( ! $page_data ) {
			return false;
		}

		$page_data = array_merge( (array) $page_data,
			(array) $new_page_data );

		$page_data = $this->sanitize_page_data( $page_data );

		$page_data = apply_filters(
			Social_Link_Pages()->plugin_name_friendly
			. '_update_page_data_before',
			$page_data,
			$post
		);

		// Instead of `wp_update_post`, which strips slashes.
		$success = $wpdb->update(
			$wpdb->posts,
			array(
				'post_content'      => json_encode( $page_data,
					JSON_UNESCAPED_SLASHES ),
				'post_modified'     => current_time( 'mysql', false ),
				'post_modified_gmt' => current_time( 'mysql', true )
			),
			array( 'ID' => $post->ID ),
			array(
				'%s',
				'%s',
				'%s',
			),
			array( '%d' )
		);

		wp_cache_flush();

		do_action(
			Social_Link_Pages()->plugin_name_friendly
			. '_update_page_data_after',
			$page_data
		);

		return false === $success ? false : $page_data;
	}

	public static function get_option_name_plugin_data() {
		return Social_Link_Pages()->plugin_name_friendly . '_plugin_data';
	}

	public static function get_option_plugin_data() {
		$option_name        = Db::get_option_name_plugin_data();
		$plugin_option_data = get_option( $option_name, [] );

		return $plugin_option_data;
	}
}