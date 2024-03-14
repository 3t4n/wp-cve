<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 *
 * @ignore
 */
class Remote_Loader {
	/**
	 * @var string
	 */
	private $api_url;
	/**
	 * @var int
	 */
	private $file_id;
	/**
	 * @var Extensions
	 */
	private $extensions;

	/**
	 * Loader constructor.
	 *
	 * @param string $api_url
	 * @param int $file_id
	 * @param Extensions $extensions
	 */
	public function __construct( $api_url, $file_id, Extensions $extensions ){
		$this->api_url    = $api_url;
		$this->file_id    = $file_id;
		$this->extensions = $extensions;

		$this->get_addons();
	}

	/**
	 * Get remote add-ons
	 *
	 * @param string $transient
	 */
	private function get_addons( $transient = 'vimeotheque_addons' ){
		$addons = get_transient( $transient );
		if( !$addons ){
			$r = wp_remote_get(
				$this->get_rest_api_endpoint(),
				[
					'timeout' => 30,
					'sslverify' => false
				]
			);
			if( is_wp_error( $r ) ){
				$addons = [];
			}else{
				$addons = json_decode( wp_remote_retrieve_body( $r ) );
			}

			set_transient( $transient, $addons, DAY_IN_SECONDS );
		}

		if( is_array( $addons ) ){
			foreach( $addons as $addon ){
				if( isset( $addon->file ) ) {
					$extension = new Extension(
						$addon->file,
						$addon->name,
						$addon->description
					);
					$extension->set_pro_addon();
					$extension->set_file_id( $addon->id );

					$this->extensions->register_extension( $extension );
				}
			}
		}
	}

	/**
	 * The Rest API endpoint
	 *
	 * @return string
	 */
	private function get_rest_api_endpoint(){
		return trailingslashit( $this->api_url ) . 'wp-json/codeflavors_api/v1/query/addons?id=' . $this->file_id;
	}
}