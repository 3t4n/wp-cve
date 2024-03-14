<?php
/**
 * Timeline main template
 */

$settings = $this->get_settings_for_display();

$this->add_render_attribute( 'wrapper', 'class',
	array(
		'lakit-htimeline',
		'lakit-htimeline--layout-' . esc_attr( $settings['vertical_layout'] ),
		'lakit-htimeline--align-' . esc_attr( $settings['horizontal_alignment'] ),
		'lakit-htimeline--' . esc_attr( $settings['navigation_type'] ),
	)
);

$desktop_columns = ! empty( $settings['columns'] ) ? $settings['columns'] : 3;
$tablet_columns = ! empty( $settings['columns_tablet'] ) ? $settings['columns_tablet'] : $desktop_columns;
$mobile_columns = ! empty( $settings['columns_mobile'] ) ? $settings['columns_mobile'] : $tablet_columns;

$data_columns = array(
	'desktop' => $desktop_columns,
	'tablet'  => $tablet_columns,
	'mobile'  => $mobile_columns
);

$this->add_render_attribute( 'wrapper', 'data-columns', json_encode( $data_columns ) );
?>

<div <?php $this->print_render_attribute_string( 'wrapper' ) ?>>
	<div class="lakit-htimeline-inner">
		<div class="lakit-htimeline-track">
			<?php $this->_get_global_looped_template( 'list-top', 'cards_list' ); ?>
			<?php $this->_get_global_looped_template( 'list-middle', 'cards_list' ); ?>
			<?php $this->_get_global_looped_template( 'list-bottom', 'cards_list' ); ?>
		</div>
	</div>
	<?php
		if ( 'arrows-nav' === $settings['navigation_type'] ) {
		    echo $this->_get_icon_setting( $settings['selected_prev_arrow_icon'], '<button class="lakit-arrow prev-arrow arrow-disabled">%s</button>');
            echo $this->_get_icon_setting( $settings['selected_next_arrow_icon'], '<button class="lakit-arrow next-arrow">%s</button>');
		}
	?>
</div>