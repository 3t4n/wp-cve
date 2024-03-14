<?php

namespace ECSL\Classes;


class Helper {


	public function get_link_module() {
		$modules = [
			'wrapper-links' => [
				'name'    => 'Wrapper Link',
				'enabled' => true,
				'type'    => 'feature',
			],
		];

		return $modules;
	}


	public function get_current_url_non_paged() {
		global $wp;
		$url = get_pagenum_link( 1 );

		return trailingslashit( $url );
	}

}
