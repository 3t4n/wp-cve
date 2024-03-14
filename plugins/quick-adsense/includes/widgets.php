<?php
/**
 * The Quickadsense widgets.
 */
class QuickAdsenseAdWidget extends WP_Widget {
	/**
	 * The class constructor.
	 *
	 * @param mixed $id The widget id.
	 */
	public function __construct( $id ) {
		parent::__construct( sanitize_title( str_replace( [ '(', ')' ], '', 'AdsWidget' . $id . ' (Quick Adsense)' ) ), 'AdsWidget' . $id . ' (Quick Adsense)', [ 'description' => 'Quick Adsense on Sidebar Widget' ] );
	}

	/**
	 * The widget output.
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance — The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$content  = get_the_content();
		$settings = get_option( 'quick_adsense_settings' );
		if ( ( strpos( $content, '<!--OffAds-->' ) === false ) && ( strpos( $content, '<!--OffWidget-->' ) === false ) && ! ( is_home() && $settings['disable_widgets_on_homepage'] ) ) {
			$widget_index = str_replace( [ 'AdsWidget', ' (Quick Adsense)' ], '', $args['widget_name'] );
			if ( isset( $settings[ 'widget_ad_' . $widget_index . '_content' ] ) && ( '' !== $settings[ 'widget_ad_' . $widget_index . '_content' ] ) ) {
				echo wp_kses( $args['before_widget'], quick_adsense_get_allowed_html() );
				echo wp_kses( $settings[ 'widget_ad_' . $widget_index . '_content' ], quick_adsense_get_allowed_html() );
				echo wp_kses( $args['after_widget'], quick_adsense_get_allowed_html() );
			}
		}
	}

	/**
	 * This function should check that $new_instance is set correctly. The newly-calculated value of $instance should be returned. If false is returned, the instance won't be saved/updated.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance — Old settings for this instance.
	 *
	 * @return array — Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	/**
	 * Outputs the settings form.
	 *
	 * @param array $instance — Current settings.
	 *
	 * @return string — Default return is 'noform'.
	 */
	public function form( $instance ) {
		return '<p>There are no options for this widget.</p>';
	}
}

add_action(
	'widgets_init',
	function() {
		$settings = get_option( 'quick_adsense_settings' );
		for ( $i = 1; $i <= 10; $i++ ) {
			if ( isset( $settings[ 'widget_ad_' . $i . '_content' ] ) && ( '' !== $settings[ 'widget_ad_' . $i . '_content' ] ) ) {
				register_widget( new QuickAdsenseAdWidget( $i ) );
			}
		}
	}
);
