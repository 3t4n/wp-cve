<?php
require_once dirname( __FILE__ ) . '/eddmp/eddmp.pb.php';

FLBuilder::register_module(
	'EDDMPBeaver',
	array(
		'eddmp-tab' => array(
			'title'    => __( 'Enter the downloads ids and the rest of shortcode attributes', 'music-player-for-easy-digital-downloads' ),
			'sections' => array(
				'eddmp-section' => array(
					'title'  => __( 'Playlist Shortcode', 'music-player-for-easy-digital-downloads' ),
					'fields' => array(
						'downloads_ids' => array(
							'type'  => 'text',
							'label' => __( 'Downloads ids separated by comma (* represents all downloads)', 'music-player-for-easy-digital-downloads' ),
						),
						'attributes'    => array(
							'type'  => 'text',
							'label' => __( 'Additional attributes', 'music-player-for-easy-digital-downloads' ),
						),
					),
				),
			),
		),
	)
);
