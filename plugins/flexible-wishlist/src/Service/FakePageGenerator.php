<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Service;

use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveTitleOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveUrlOption;

/**
 * Supports generating virtual posts simulating wishlist pages.
 */
class FakePageGenerator {

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	public function __construct( SettingsRepository $settings_repository ) {
		$this->settings_repository = $settings_repository;
	}

	/**
	 * @return object
	 *
	 * @throws InvalidSettingsOptionKey
	 */
	public function generate_archive_page( string $post_content = '' ) {
		return $this->get_post_object(
			-999,
			$this->settings_repository->get_value( TextArchiveTitleOption::FIELD_NAME ),
			$post_content
		);
	}

	/**
	 * @return object
	 *
	 * @throws InvalidSettingsOptionKey
	 */
	public function generate_single_page( string $post_title, string $post_content = '' ) {
		$post = $this->generate_archive_page();
		wp_cache_set( -999, $post, 'posts' );

		return $this->get_post_object(
			-998,
			$post_title,
			$post_content,
			-999
		);
	}

	/**
	 * @return object
	 *
	 * @throws InvalidSettingsOptionKey
	 */
	private function get_post_object( int $post_id, string $post_title, string $post_content = '', int $post_parent = 0 ) {
		return (object) [
			'ID'                    => $post_id,
			'post_author'           => '0',
			'post_date'             => '0000-00-00 00:00:00',
			'post_date_gmt'         => '0000-00-00 00:00:00',
			'post_content'          => $post_content,
			'post_title'            => $post_title,
			'post_excerpt'          => '',
			'post_status'           => 'publish',
			'comment_status'        => 'closed',
			'ping_status'           => 'closed',
			'post_password'         => '',
			'post_name'             => $this->settings_repository->get_value( TextArchiveUrlOption::FIELD_NAME ),
			'to_ping'               => '',
			'pinged'                => '',
			'post_modified'         => '0000-00-00 00:00:00',
			'post_modified_gmt'     => '0000-00-00 00:00:00',
			'post_content_filtered' => '',
			'post_parent'           => $post_parent,
			'guid'                  => '',
			'menu_order'            => 0,
			'post_type'             => 'page',
			'post_mime_type'        => '',
			'comment_count'         => '0',
			'filter'                => 'raw',
		];
	}
}
