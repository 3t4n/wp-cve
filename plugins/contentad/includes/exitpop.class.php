<?php

/**
 * A class to handle a exit pops.
 */
class ContentAd__Includes__Exitpop {

    protected
        $atts = array();

    public static function contentad_exitpop( $atts ) {

		// Only show the newest exit pop & mobile exit pop widgets
		$atts = array( 
			'exit_pop' => array(
				'posts_per_page' => 1,
				'post_status' => 'publish',
				'orderby' => 'modified',
				'order' => 'desc',
				'meta_query' => array(
					'placement' => array(
						'key' => 'placement',
						'value' => 'in_exit_pop'
					),
					'status' => array(
						'key' => '_ca_widget_inactive',
						'compare' => 'NOT EXISTS'
					)
				)
			),
			'mobile_exit_pop' => array(
				'posts_per_page' => 1,
				'post_status' => 'publish',
				'orderby' => 'modified',
				'order' => 'desc',
				'meta_query' => array(
					'placement' => array(
						'key' => 'placement',
						'value' => 'in_mobile_exit_pop'
					),
					'status' => array(
						'key' => '_ca_widget_inactive',
						'compare' => 'NOT EXISTS'
					)
				)
			)
		);

		echo ContentAd__Includes__API::get_ad_code($atts['exit_pop']);
		echo ContentAd__Includes__API::get_ad_code($atts['mobile_exit_pop']);
		return;
    }

}