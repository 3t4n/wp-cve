<?php

class Youzify_Overview_Tab {

	/**
	 * Tab Core
	 */
	function tab() {

		// Get Overview Widgets
		$profile_widgets = apply_filters(
			'youzify_profile_main_widgets',
			youzify_option(
				'youzify_profile_main_widgets', array(
	            'slideshow'  => 'visible',
	            'project'    => 'visible',
	            'skills'     => 'visible',
	            'portfolio'  => 'visible',
	            'quote'      => 'visible',
	            'instagram'  => 'visible',
	            'services'   => 'visible',
	            'post'       => 'visible',
	            'link'       => 'visible',
	            'video'      => 'visible',
	            'reviews'    => 'visible',
	        	)
			)
		);

		// Get Tab Content.
		echo '<div class="youzify-tab youzify-overview">';
		youzify_widgets()->get_widget_content( $profile_widgets );
		echo '</div>';

	}

}