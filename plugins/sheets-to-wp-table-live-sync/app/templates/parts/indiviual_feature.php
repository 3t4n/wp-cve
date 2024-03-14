<?php
/**
 * Displays individual features.
 *
 * @package SWPTLS
 */

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

?>

<?php if ( isset( $args['type'] ) && 'checkbox' === $args['type'] ) : ?>
<div class="">
	<div class="ui cards">
		<div class="card <?php echo ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ? 'gswpts_pro_card' : ''; ?>">
			<div class="content">
				<div class="card-top-header">
					<span>
						<?php if ( ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ) : ?>
						<i class="fas fa-star pro_star_icon mr-2"></i>
						<?php endif; ?>
						<?php echo esc_html( $args['feature_title'] ); ?>
						<div class="input-tooltip">
							<i class="fas fa-info-circle" style="font-size: 15.5px;"></i>
							<span class="tooltiptext">
								<span><?php echo esc_attr( $args['feature_desc'] ); ?></span>
								<?php if ( $args['show_tooltip'] ) : ?>
								<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/feature-gif/' . $args['input_name'] . '.gif' ); ?>" height="200" alt="<?php echo esc_attr( $args['input_name'] ); ?>">
								<?php endif; ?>
							</span>
						</div>
					</span>
					<div class="ui toggle checkbox">
						<input <?php echo $args['checked'] ? 'checked' : ''; ?> type="checkbox"
							class="<?php echo ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ? 'pro_feature_input' : ''; ?>"
							name="<?php echo esc_attr( $args['input_name'] ); ?>" id="<?php echo esc_attr( $args['input_name'] ); ?>">
						<label for="<?php echo esc_attr( $args['input_name'] ); ?>"></label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if ( isset( $args['type'] ) && 'select' === $args['type'] ) : ?>
<div class="">
	<div class="ui cards">
		<div class="card <?php echo ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ? 'gswpts_pro_card' : ''; ?>">
			<div class="content">
				<div class="card-top-header">
					<span>
						<?php if ( ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ) : ?>
						<i class="fas fa-star pro_star_icon mr-2"></i>
						<?php endif; ?>
						<?php echo esc_html( $args['feature_title'] ); ?>
						<div class="input-tooltip">
							<i class="fas fa-info-circle" style="font-size: 15.5px;"></i>
							<span class="tooltiptext" style="width: 400px; min-height: 65px;">
								<span><?php echo wp_kses_post( $args['feature_desc'] ); ?></span>

								<?php if ( $args['show_tooltip'] ) : ?>
								<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/feature-gif/' . $args['input_name'] . '.gif' ); ?>" height="200" alt="<?php echo esc_attr( $args['input_name'] ); ?>">
								<?php endif; ?>
							</span>
						</div>
					</span>
					<div class="ui fluid selection dropdown" id="<?php echo esc_attr( $args['input_name'] ); ?>">
						<input type="hidden" name="<?php echo esc_attr( $args['input_name'] ); ?>"
							value="<?php echo $args['default_value'] ? esc_attr( $args['default_value'] ) : null; ?>">
						<i class="dropdown icon"></i>
						<div class="default text"><?php esc_html( $args['default_text'] ); ?></div>

						<div class="menu">
							<?php swptls()->settings->selectFieldHTML( $args['values'] ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if ( isset( $args['type'] ) && 'multi-select' === $args['type'] ) : ?>
<div class="">
	<div class="ui cards">
		<div class="card <?php echo ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ? 'gswpts_pro_card' : ''; ?>"
			style="height: unset; min-height: 60px; max-height: 110px;">
			<div class="content">
				<div class="card-top-header">
					<span>
						<?php if ( ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ) : ?>
						<i class="fas fa-star pro_star_icon mr-2"></i>
						<?php endif; ?>
						<?php echo esc_html( $args['feature_title'] ); ?>
						<div class="input-tooltip">
							<i class="fas fa-info-circle" style="font-size: 15.5px;"></i>
							<span class="tooltiptext" style="width: 400px; min-height: 65px;">
								<span>
									<?php echo esc_attr( $args['feature_desc'] ); ?>
								</span>
								<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/feature-gif/' . $args['input_name'] . '.gif' ); ?>" height="200" alt="<?php echo esc_attr( $args['input_name'] ); ?>">
							</span>
						</div>
					</span>
					<div class="ui fluid
					<?php echo swptls()->helpers->is_pro_active() ? 'multiple' : null; ?> selection dropdown mt-2"
						id="table_exporting_container">
						<input type="hidden" name="<?php echo esc_attr( $args['input_name'] ); ?>" id="<?php echo esc_attr( $args['input_name'] ); ?>">
						<i class="dropdown icon"></i>
						<div class="default text"><?php esc_html( $args['default_text'] ); ?></div>
						<div class="menu">
							<?php swptls()->settings->selectFieldHTML( $args['values'] ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if ( isset( $args['type'] ) && 'custom-type' === $args['type'] ) : ?>
<div class="">
	<div class="ui cards">
		<div class="card <?php echo ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ? 'gswpts_pro_card' : ''; ?>" style="cursor: pointer;">
			<div class="content">
				<div class="card-top-header">
					<span>
						<?php if ( ( isset( $args['is_pro'] ) && $args['is_pro'] ) || ( isset( $args['is_upcoming'] ) && $args['is_upcoming'] ) ) : ?>
						<i class="fas fa-star pro_star_icon mr-2"></i>
						<?php endif; ?>
						<?php echo esc_html( $args['feature_title'] ); ?>
						<div class="input-tooltip">
							<i class="fas fa-info-circle" style="font-size: 15.5px;"></i>
							<span class="tooltiptext" style="width: 400px; min-height: 65px;">
								<span><?php echo esc_attr( $args['feature_desc'] ); ?></span>
							</span>
						</div>
					</span>
					<div class="modal-handler">
						<img src="<?php echo esc_url( $args['icon_url'] ); ?>" class="chooseStyle" alt="chooseStyle">
						<input type="hidden" name="<?php echo esc_attr( $args['input_name'] ); ?>" id="<?php echo esc_attr( $args['input_name'] ); ?>" value="">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
