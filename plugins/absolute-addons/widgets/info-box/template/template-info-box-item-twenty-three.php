<?php
/**
 * Template Style Twenty Three for Info Box
 *
 * @package AbsoluteAddons
 * @var $settings
 * @var $feature_list_icon
 */
defined( 'ABSPATH' ) || exit;

?>
<div class="info-box">
	<div class="info-box-icon">
		<?php $this->render_box_icon( $settings ); ?>
	</div>
	<div class="info-box-content">
		<?php if ( ! empty( $settings['info_box_sub_title'] ) ) { ?>
			<span <?php $this->print_render_attribute_string( 'info_box_sub_title' ); ?>><?php absp_render_title( $settings['info_box_sub_title'] ); ?></span>
		<?php } ?>
		<?php if ( ! empty( $settings['info_box_title'] ) ) { ?>
			<h2 <?php $this->print_render_attribute_string( 'info_box_title' ); ?> ><?php absp_render_title( $settings['info_box_title'] ); ?></h2>
		<?php } ?>
		<div class="info-box-list">
			<?php if ( is_array( $settings['features_line_value'] ) ) {
				foreach ( $settings['features_line_value'] as $index => $feature ) {
					$repeater_setting_key = $this->get_repeater_setting_key( 'feature_single_line_text', 'features_line_value', $index );
					$this->add_inline_editing_attributes( $repeater_setting_key );
					$this->add_render_attribute( $repeater_setting_key, 'class', 'single-feature ' . $feature_list_icon . ' ' . $settings['feature_list_icon'] );
					?>
					<div class="single-feature-wrapper">
						<div <?php $this->print_render_attribute_string( $repeater_setting_key ); ?>>
							<?php absp_render_title( $feature['feature_single_line_text'] ) ?>
						</div>
					</div>
				<?php }
			} ?>
		</div>
		<?php $this->render_button( $settings ); ?>
	</div>
</div>




