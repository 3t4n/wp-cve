<?php

class ContentAd__Includes__Widget extends WP_Widget {

    function ContentAd__Includes__Widget() {
      parent::__construct(
			$id = false,
            $title = __('Content.ad Widget', 'contentad' ),
            $widget_ops = array(
                'classname' => 'content-ad-widget',
                'description' => 'Displays ads that are set to display within a widget.',
            )
        );
    }

	public function form( $instance ) {
		$id = isset( $instance['widget_id'] ) ? $instance['widget_id'] : false;
		$widgets = ContentAd__Includes__Init::get_local_widgets( array(
			'meta_query' => array(
				array(
					'key' => 'placement',
					'value' => 'in_widget',
				),
			),
		) );
		echo '<select id="' . $this->get_field_id( 'widget_id' ) . '" name="' . $this->get_field_name( 'widget_id' ) . '">';
			echo '<option value="">Show all ContentAd widgets</option>';
			foreach( $widgets as $widget ) {
				$selected = selected( $id, $widget->ID, false );
				echo '<option value="' . $widget->ID . '"' . $selected . '>'. $widget->post_title . '</option>';
			}
		echo '</select>';
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['widget_id'] = absint( $new_instance['widget_id'] );
		return $instance;
	}

    function widget( $args, $instance ){

		// Begin widget wrapper
		echo $args['before_widget'];

		// Display widget content to user
		if( $widget_id = $instance['widget_id'] ) {
			echo ContentAd__Includes__API::get_code_for_single_ad( $widget_id, 'in_widget' );
		} else {
			$atts = array(
				'meta_query' => array(
					'placement' => array(
						'key' => 'placement',
						'value' => 'in_widget'
					)
				)
			);
			echo ContentAd__Includes__API::get_ad_code($atts);
		}


		// End widget wrapper
		echo $args['after_widget'];

    }

}
