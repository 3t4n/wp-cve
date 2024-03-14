<?php
namespace Thim_EL_Kit\Upgrade;

use Thim_EL_Kit\SingletonTrait;

class DB_Updates {

	use SingletonTrait;

	public function update_to_110() {
		// List all options.
		$options = array(
			'thim_ekits_archive_post',
			'thim_ekits_archive_product',
			'thim_ekits_single_post',
			'thim_ekits_single_product',
			'thim_ekits_archive_course',
			'thim_ekits_single_course',
		);

		// Loop all options.
		foreach ( $options as $option ) {
			$post_id = get_option( $option );

			if ( $post_id ) {
				$conditions = get_post_meta( $post_id, 'thim_ekits_conditions', true );
				$conditions = ! empty( $conditions ) ? $conditions : array();

				// Check if item conditions has type 'all', if not has type 'all' then add type 'all' to item conditions
				$has_type_all = false;
				foreach ( $conditions as $condition ) {
					if ( $condition['type'] === 'all' ) {
						$has_type_all = true;
						break;
					}
				}

				if ( ! $has_type_all ) {
					$conditions[] = array(
						'comparison' => 'include',
						'type'       => 'all',
						'query'      => null,
					);
				}

				update_post_meta( $post_id, 'thim_ekits_conditions', $conditions );

				delete_option( $option );
			}
		}

		\Thim_EL_Kit\Modules\Cache::instance()->regenerate();
	}

	public function update_110_header_footer() {
		$posts = get_posts(
			array(
				'post_type'      => 'thim_elementor_kit',
				'posts_per_page' => - 1,
				'meta_query'     => array(
					array(
						'key'   => 'thim_elementor_type',
						'value' => array( 'header', 'footer' ),
					),
				),
			)
		);

		$lists = array( 'entire', 'archive', 'archiveList', 'singular', 'singularList', 'page', 'pageList', '404', 'realpress-agent' );

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$conditions = get_post_meta( $post->ID, 'thim_ekits_conditions', true );
				$conditions = ! empty( $conditions ) ? $conditions : array();

				foreach ( $lists as $list ) {
					$data = get_post_meta( $post->ID, 'thim_ekits_cond_' . $list, true );

					if ( empty( $data ) ) {
						continue;
					}

					switch ( $list ) {
						case 'entire':
							$conditions[] = array(
								'comparison' => 'include',
								'type'       => 'all',
								'query'      => null,
							);
							break;
						case 'archiveList':
							foreach ( $data as $item ) {
								$conditions[] = array(
									'comparison' => 'include',
									'type'       => 'archive_post_type',
									'query'      => $item,
								);
							}
							break;
						case 'singularList':
							foreach ( $data as $item ) {
								$conditions[] = array(
									'comparison' => 'include',
									'type'       => 'singular_post_type',
									'query'      => $item,
								);
							}
							break;
						case 'pageList':
							foreach ( $data as $item ) {
								$conditions[] = array(
									'comparison' => 'include',
									'type'       => 'select_page',
									'query'      => $item,
								);
							}
							break;
						case '404':
							$conditions[] = array(
								'comparison' => 'include',
								'type'       => '404_page',
								'query'      => null,
							);
							break;
						case 'realpress-agent':
							$conditions[] = array(
								'comparison' => 'include',
								'type'       => 'realpress_agent',
								'query'      => null,
							);
							break;
					}

					delete_post_meta( $post->ID, 'thim_ekits_cond_' . $list );
				}

				update_post_meta( $post->ID, 'thim_ekits_conditions', $conditions );
			}

			\Thim_EL_Kit\Modules\Cache::instance()->regenerate();
		}
	}

	public function update_110_db_version() {
		update_option( 'thim_ekit_db_version', '1.1.0' );
	}
}
