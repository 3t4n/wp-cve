<?php
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! function_exists( 'plz_wpbakery_element' ) ) :
	function plz_wpbakery_element() {
		if ( ! $forms = get_transient( 'plezi_wpbakery_forms' ) ) :
			$forms = plz_get_forms_list( 'sort_by=created_at&sort_dir=desc&page=1&per_page=20', [ 'sort_by' => 'created_at', 'sort_dir' => 'desc', 'page' => '1', 'per_page' => '20' ] );		

			set_transient( 'plezi_wpbakery_forms', $forms, 60 );
		endif;

		$select_options = [ __( 'Choose a Plezi form', 'plezi-for-wordpress' ) => '' ];

		if ( $forms && ! isset( $forms->error ) && isset( $forms['list'] ) ) :
			foreach ( $forms['list'] as $form ) :
				$select_options[ $form->attributes->custom_title ] = $form->id;
			endforeach;
		endif;

		vc_map( [ 
			'name' => __( 'Plezi form', 'plezi-for-wordpress' ),
			'base' => 'plezi',
			'category' => __( 'Plezi', 'plezi-for-wordpress' ),
			'icon' => 'icon-wpb-plezi',
			'params' => [
         		[
					'type' => 'dropdown',
					'heading' => __( 'Choose a Plezi form', 'plezi-for-wordpress' ),
					'param_name' => 'form',
					'value' => $select_options
				]
			]
   		] );
	}
endif;