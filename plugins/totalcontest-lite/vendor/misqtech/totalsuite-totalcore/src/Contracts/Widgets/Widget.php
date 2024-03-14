<?php

namespace TotalContestVendors\TotalCore\Contracts\Widgets;


/**
 * Widget base class
 * @package TotalContestVendors\TotalCore\Contracts\Widgets\Widget
 * @since   1.0.0
 */
interface Widget {
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance );

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance );

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance );

	/**
	 * Widget fields.
	 *
	 * @param $fields
	 * @param $instance
	 *
	 * @return mixed
	 */
	public function fields( $fields, $instance );

	/**
	 * Widget content.
	 *
	 * @param $args
	 * @param $instance
	 *
	 * @return mixed
	 */
	public function content( $args, $instance );
}