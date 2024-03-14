<?php
/**
 * UpStream_Admin_Client_Columns
 *
 * @package UpStream
 */

use UpStream\Plugins\CustomFields\Model;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream_Admin_Client_Columns' ) ) :

	/**
	 * Admin columns
	 *
	 * @version 0.1.0
	 */
	class UpStream_Admin_Client_Columns {

		/**
		 * Label
		 *
		 * @var mixed
		 */
		private $label;

		/**
		 * Label Plural
		 *
		 * @var mixed
		 */
		private $label_plural;

		/**
		 * Constructor
		 *
		 * @since 0.1.0
		 */
		public function __construct() {
			$this->label        = upstream_client_label();
			$this->label_plural = upstream_client_label_plural();

			return $this->hooks();
		}

		/**
		 * Hooks
		 *
		 * @return void
		 */
		public function hooks() {
			add_filter( 'manage_client_posts_columns', array( $this, 'client_columns' ) );
			add_action( 'manage_client_posts_custom_column', array( $this, 'client_data' ), 10, 2 );
		}

		/**
		 * Set columns for client
		 *
		 * @param  mixed $defaults Defaults.
		 */
		public function client_columns( $defaults ) {
			$post_type = 'client';

			$columns    = array();
			$taxonomies = array();

			/* Get taxonomies that should appear in the manage posts table. */
			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$taxonomies = wp_filter_object_list( $taxonomies, array( 'show_admin_column' => true ), 'and', 'name' );

			/* Allow devs to filter the taxonomy columns. */
			$taxonomies = apply_filters(
				'manage_taxonomies_for_upstream_client_columns',
				$taxonomies,
				$post_type
			);
			$taxonomies = array_filter( $taxonomies, 'taxonomy_exists' );

			/* Loop through each taxonomy and add it as a column. */
			foreach ( $taxonomies as $taxonomy ) {
				$columns[ 'taxonomy-' . $taxonomy ] = get_taxonomy( $taxonomy )->labels->name;
			}
			$defaults['title']   = $this->label;
			$defaults['id']      = __( 'ID', 'upstream' );
			$defaults['logo']    = __( 'Logo', 'upstream' );
			$defaults['website'] = __( 'Website', 'upstream' );
			$defaults['phone']   = __( 'Phone', 'upstream' );
			$defaults['address'] = __( 'Address', 'upstream' );
			$defaults['users']   = __( 'Users', 'upstream' );

			if ( is_plugin_active( 'upstream-custom-fields/upstream-custom-fields.php' ) ||
				is_plugin_active( 'UpStream-Custom-Fields/upstream-custom-fields.php' )
			) {
				$rowset = Model::fetchColumnFieldsForType( 'client', false );

				if ( count( $rowset ) > 0 ) {
					foreach ( $rowset as $row ) {
						$defaults[ $row->name ] = $row->label;
					}
				}
			}

			return $defaults;
		}

		/**
		 * Client Data
		 *
		 * @param  string $column_name Column Name.
		 * @param  int    $post_id Post Id.
		 * @return void
		 */
		public function client_data( $column_name, $post_id ) {
			$client       = new UpStream_Client( $post_id );
			$column_value = null;

			if ( 'logo' === $column_name ) {
				$logo_id = $client->get_meta( 'logo_id' );
				if ( ! empty( $logo_id ) ) {
					$logo_img_url = wp_get_attachment_image_src( $logo_id );
					$column_value = '<img height="50" src="' . esc_url( $logo_img_url[0] ) . '" />';
				}
			} elseif ( 'id' === $column_name ) {
				$column_value = $post_id;
			} elseif ( 'website' === $column_name ) {
				$website = $client->get_meta( 'website' );
				if ( ! empty( $website ) ) {
					$column_value = '<a href="' . esc_url( $website ) . '" target="_blank" rel="noopener noreferer">' . esc_html( $website ) . '</a>';
				}
			} elseif ( 'phone' === $column_name ) {
				$phone = $client->get_meta( 'phone' );
				if ( ! empty( $phone ) ) {
					$column_value = esc_html( $phone );
				}
			} elseif ( 'address' === $column_name ) {
				$address = $client->get_meta( 'address' );
				if ( ! empty( $address ) ) {
					$column_value = wp_kses_post( wpautop( $address ) );
				}
			} elseif ( 'users' === $column_name ) {
				$client_users = (array) upstream_get_client_users( $post_id );
				if ( count( $client_users ) > 0 ) {
					upstream_client_render_users_column( upstream_get_client_users( $post_id ) );

					return;
				}
			} else {
				if ( is_plugin_active( 'upstream-custom-fields/upstream-custom-fields.php' ) ||
				is_plugin_active( 'UpStream-Custom-Fields/upstream-custom-fields.php' ) ) {
					$rowset = Model::fetchColumnFieldsForType( 'client', false );

					if ( count( $rowset ) > 0 ) {
						foreach ( $rowset as $row ) {
							if ( $row->name === $column_name ) {
								$values       = array_filter( (array) $row->getValue( $post_id ) );
								$column_value = esc_html( implode( ', ', $values ) );
								break;
							}
						}
					}
				}
			}

			if ( ! empty( $column_value ) ) {
				echo wp_kses_post( $column_value );
			} else {
				echo wp_kses_post(
					'<i style="color: #CCC;">' . __(
						'none',
						'upstream'
					) . '</i>'
				);
			}
		}
	}

	new UpStream_Admin_Client_Columns();

endif;


/**
 * Renders the client users list column value.
 *
 * @since   1.0.0
 *
 * @param   array $users_list Array of client users.
 */
function upstream_client_render_users_column( $users_list ) {
	$users_list       = (array) $users_list;
	$users_list_count = count( $users_list );

	if ( 0 === $users_list_count ) {
		echo '<i>' . esc_html__( 'none', 'upstream' ) . '</i>';
	} else {
		$user_index = 0;
		foreach ( $users_list as $user ) {
			echo esc_html( $user['name'] ) . '<br/>';

			if ( 2 === $user_index ) {
				echo sprintf(
					'<i>' .
					// translators: %1$s: Users List Count.
					// translators: %2$s: User label.
					esc_html__( '+%1$s more %2$s', 'upstream' )
					. '</i>',
					esc_html( $users_list_count ),
					esc_html( $users_list_count > 1 ? __( 'users', 'upstream' ) : __( 'user', 'upstream' ) )
				);
				break;
			}
			$user_index++;
		}
	}
}
