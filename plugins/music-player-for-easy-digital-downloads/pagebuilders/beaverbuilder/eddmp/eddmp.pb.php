<?php
class EDDMPBeaver extends FLBuilderModule {
	public function __construct() {
		 $modules_dir = dirname( __FILE__ ) . '/';
		$modules_url  = plugins_url( '/', __FILE__ ) . '/';

		parent::__construct(
			array(
				'name'            => __( 'Music Player for Easy Digital Downloads', 'music-player-for-easy-digital-downloads' ),
				'description'     => __( 'Insert the playlist shortcode', 'music-player-for-easy-digital-downloads' ),
				'group'           => __( 'Music Player for Easy Digital Downloads', 'music-player-for-easy-digital-downloads' ),
				'category'        => __( 'Music Player for Easy Digital Downloads', 'music-player-for-easy-digital-downloads' ),
				'dir'             => $modules_dir,
				'url'             => $modules_url,
				'partial_refresh' => true,
			)
		);
	}
}
