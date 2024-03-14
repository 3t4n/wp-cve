<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Create a widget 1
//
KIANFR::createWidget( 'kianfr_widget_example_1', [
	'title'       => 'Codestar Widget Example 1',
	'classname'   => 'kianfr-widget-classname',
	'description' => 'A description for widget example 1',
	'fields'      => [

		[
			'id'    => 'title',
			'type'  => 'text',
			'title' => 'Title',
		],

		[
			'id'      => 'opt-text',
			'type'    => 'text',
			'title'   => 'Text',
			'default' => 'Default text value',
		],

		[
			'id'    => 'opt-color',
			'type'  => 'color',
			'title' => 'Color',
		],

		[
			'id'    => 'opt-upload',
			'type'  => 'upload',
			'title' => 'Upload',
		],

		[
			'id'    => 'opt-textarea',
			'type'  => 'textarea',
			'title' => 'Textarea',
			'help'  => 'The help text of the field.',
		],

	],
] );

//
// Front-end display of widget example 1
// Attention: This function named considering above widget base id.
//
if ( ! function_exists( 'kianfr_widget_example_1' ) ) {
	function kianfr_widget_example_1( $args, $instance )
	{
		echo $args['before_widget'];

		// if ( ! empty( $instance['title'] ) ) {
		//   echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		// }

		echo '<div style="padding: 20px; background-color: #f7f7f7;">';
		echo '<h3>Codestar Widget Example 1</h3>';
		echo '<p><strong>Title:</strong> ' . $instance['title'] . '</p>';
		echo '<p><strong>Text:</strong> ' . $instance['opt-text'] . '</p>';
		echo '<p><strong>Color:</strong> ' . $instance['opt-color'] . '</p>';
		echo '<p><strong>Upload:</strong> ' . $instance['opt-upload'] . '</p>';
		echo '<p><strong>Textarea:</strong> ' . $instance['opt-textarea'] . '</p>';
		echo '</div>';

		echo $args['after_widget'];
	}
}

//
// Create a widget 2
//
KIANFR::createWidget( 'kianfr_widget_example_2', [
	'title'       => 'Codestar Widget Example 2',
	'classname'   => 'kianfr-widget-classname',
	'description' => 'A description for widget example 2',
	'fields'      => [

		[
			'id'    => 'title',
			'type'  => 'text',
			'title' => 'Title',
		],

		[
			'id'      => 'opt-text',
			'type'    => 'text',
			'title'   => 'Text',
			'default' => 'Default text value',
		],

		[
			'id'    => 'opt-color',
			'type'  => 'color',
			'title' => 'Color',
		],

		[
			'id'    => 'opt-switcher',
			'type'  => 'switcher',
			'title' => 'Switcher',
			'label' => 'The label text of the switcher.',
		],

		[
			'id'    => 'opt-checkbox',
			'type'  => 'checkbox',
			'title' => 'Checkbox',
			'label' => 'The label text of the checkbox.',
		],

		[
			'id'          => 'opt-select',
			'type'        => 'select',
			'title'       => 'Select',
			'placeholder' => 'Select an option',
			'options'     => [
				'opt-1' => 'Option 1',
				'opt-2' => 'Option 2',
				'opt-3' => 'Option 3',
			],
		],

		[
			'id'      => 'opt-radio',
			'type'    => 'radio',
			'title'   => 'Radio',
			'options' => [
				'yes' => 'Yes, Please.',
				'no'  => 'No, Thank you.',
			],
			'default' => 'yes',
		],
		[
			'type'    => 'notice',
			'style'   => 'success',
			'content' => 'A <strong>notice</strong> field with <strong>success</strong> style.',
		],

		[
			'id'    => 'opt-textarea',
			'type'  => 'textarea',
			'title' => 'Textarea',
			'help'  => 'The help text of the field.',
		],

	],
] );

//
// Front-end display of widget example 2
// Attention: This function named considering above widget base id.
//
if ( ! function_exists( 'kianfr_widget_example_2' ) ) {
	function kianfr_widget_example_2( $args, $instance )
	{
		echo $args['before_widget'];

		// if ( ! empty( $instance['title'] ) ) {
		//   echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		// }

		echo '<div style="padding: 20px; background-color: #f7f7f7;">';
		echo '<h3>Codestar Widget Example 2</h3>';
		echo '<p><strong>Title:</strong> ' . $instance['title'] . '</p>';
		echo '<p><strong>Text:</strong> ' . $instance['opt-text'] . '</p>';
		echo '<p><strong>Color:</strong> ' . $instance['opt-color'] . '</p>';
		echo '<p><strong>Switcher:</strong> ' . $instance['opt-switcher'] . '</p>';
		echo '<p><strong>Checkbox:</strong> ' . $instance['opt-checkbox'] . '</p>';
		echo '<p><strong>Select:</strong> ' . $instance['opt-select'] . '</p>';
		echo '<p><strong>Radio:</strong> ' . $instance['opt-radio'] . '</p>';
		echo '<p><strong>Textarea:</strong> ' . $instance['opt-textarea'] . '</p>';
		echo '</div>';

		echo $args['after_widget'];
	}
}
