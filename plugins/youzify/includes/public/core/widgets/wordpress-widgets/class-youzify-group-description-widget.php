<?php
/**
 * Group Description Widget
 */

class Youzify_Group_Description_Widget extends WP_Widget {

	function __construct() {

		parent::__construct(
			'youzify_group_description_widget',
			__( 'Youzify - Group Description', 'youzify' ),
			array( 'description' => __( 'Youzify group description widget', 'youzify' ) )
		);

	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {

		// Check if widget should be visible or not.
		if ( ! bp_is_active( 'groups' ) || ! bp_is_groups_component() || ! bp_group_is_visible() ) {
			return false;
		}

		global $bp;

		// Get Group Data
		$group = $bp->groups->current_group;

		// Get Group Description.
		$group_description = $group->description;

		if ( apply_filters( 'youzify_disable_group_description_html', false ) ) {
			$group_description = sanitize_textarea_field( $group->description );
		}

		if ( empty( $group_description ) ) {
			return false;
		}

		?>

		<div class="youzify-group-infos-widget">
			<div class="youzify-group-widget-title">
				<i class="fas fa-file-alt"></i>
				<?php echo _e( 'Description', 'youzify' ); ?>
			</div>
			<div class="youzify-group-widget-content"><?php echo apply_filters( 'the_content', html_entity_decode( $group_description ) ); ?></div>
		</div>

		<?php

	}

	/**
	 * Login Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( 'This widget will show the opened group description', 'youzify' ) . '</p>';
	}

}