<?php
/**
 * Displays affiliate notices.
 *
 * @package SWPTLS
 */

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$class = '';
if ( isset( $args['is_pro'] ) && $args['is_pro'] ) {
	$class = 'pro_setting';
} elseif ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) {
	$class = 'upcoming_setting pro_setting';
}
?>
<div class="ui cards settings_row">
	<div class="card <?php echo ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ? 'gswpts_pro_card' : ''; ?>">
		<div class="content">
			<div class="description d-flex justify-content-between align-items-center">
				<h5 class="m-0">
					<span>
						<?php if ( ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ) : ?>
						<i class="fas fa-star pro_star_icon mr-2"></i>
						<?php endif; ?>
						<?php echo esc_html( $args['setting_title'] ); ?>
					</span>
					<span>
						<div class="input-tooltip">
							<i class="fas fa-info-circle"></i>
							<span class="tooltiptext" style="width: 400px; min-height: 65px;">
								<?php if ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) : ?>
								<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/feature-gif/' . $args['input_name'] . '.gif' ); ?>" height="150" alt="<?php echo esc_attr( $args['input_name'] ); ?>">
								<?php else : ?>
								<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/feature-gif/' . $args['input_name'] . '.gif' ); ?>" alt="<?php echo esc_attr( $args['input_name'] ); ?>">
								<?php endif; ?>
							</span>
						</div>
					</span>
				</h5>
				<div class="ui toggle checkbox m-0">
					<input class="<?php echo esc_attr( $class ); ?>" type="checkbox" <?php echo esc_attr( $args['is_checked'] ); ?>
						name="<?php echo esc_attr( $args['input_name'] ); ?>" id="<?php echo esc_attr( $args['input_name'] ); ?>">
					<label class="m-0" for="<?php echo esc_attr( $args['input_name'] ); ?>"></label>
				</div>
			</div>
		</div>
		<div class="settings_desc">
			<p><?php echo wp_kses_post( $args['setting_desc'] ); ?></p>
			<?php if ( 'custom_css' === $args['input_name'] ) : ?>
				<br>
				<textarea name="css_code_value" id="css_code_value"><?php echo esc_html( get_option( 'css_code_value' ) ); ?></textarea>

				<div id="gswptsCSSeditor"
					style="min-height: 110px;<?php echo ! get_option( 'custom_css' ) && ! swptls()->helpers->is_pro_active() ? 'opacity: 0.5; pointer-events: none;' : null; ?>">
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
