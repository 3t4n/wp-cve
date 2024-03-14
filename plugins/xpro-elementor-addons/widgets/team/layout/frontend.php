<?php

use Elementor\Icons_Manager;

$title_tag   = ( $settings['title_link']['url'] ) ? 'a' : 'h2';
$title_attr  = $settings['title_link']['is_external'] ? ' target="_blank"' : '';
$title_attr .= $settings['title_link']['nofollow'] ? ' rel="nofollow"' : '';
$title_attr .= $settings['title_link']['url'] ? ' href="' . $settings['title_link']['url'] . '"' : '';

?>

<div class="xpro-team-wrapper xpro-team-layout-<?php echo esc_attr( $settings['layout'] ); ?>">
	<?php if ( $settings['designation'] && '2' === $settings['layout'] ) : ?>
		<h4 class="xpro-team-designation"><?php echo esc_attr( $settings['designation'] ); ?></h4>
	<?php endif; ?>

	<?php if ( $settings['image']['id'] || $settings['image']['url'] ) : ?>
	<div class="xpro-team-image">
		<?php
		$image_markup = ( ! empty( $settings['image']['id'] ) ) ? wp_get_attachment_image( $settings['image']['id'], $settings['thumbnail_size'] ) : '';
		echo ! empty( $image_markup ) ? $image_markup : '<img alt="team-img" src="' . esc_url( $settings['image']['url'] ) . '">';
		?>
		<?php if ( '8' === $settings['layout'] || '9' === $settings['layout'] ) : ?>
		<div class="xpro-team-inner-content">
			<?php if ( $settings['title'] ) : ?>
			<<?php echo esc_attr( $title_tag ); ?><?php xpro_elementor_kses( $title_attr ); ?>
			class="xpro-team-title"><?php echo esc_attr( $settings['title'] ); ?></<?php echo esc_attr( $title_tag ); ?>>
	<?php endif; ?>
			<?php if ( $settings['designation'] ) : ?>
			<h4 class="xpro-team-designation"><?php echo esc_attr( $settings['designation'] ); ?></h4>
		<?php endif; ?>
	</div>
<?php endif; ?>
		<?php if ( $settings['social_enable'] && $settings['social_icon_list'] && ( '2' === $settings['layout'] || '3' === $settings['layout'] || '5' === $settings['layout'] || '8' === $settings['layout'] || '12' === $settings['layout'] || '13' === $settings['layout'] || '15' === $settings['layout'] ) ) : ?>
		<ul class="xpro-team-social-list">
			<?php
			foreach ( $settings['social_icon_list'] as $i => $icon ) {
				$html_tag = $icon['icon_link']['url'] ? 'a' : 'span';
				$attr     = $icon['icon_link']['is_external'] ? ' target="_blank"' : '';
				$attr    .= $icon['icon_link']['nofollow'] ? ' rel="nofollow"' : '';
				$attr    .= $icon['icon_link']['url'] ? ' href="' . $icon['icon_link']['url'] . '"' : '';

				?>
			<li class="elementor-repeater-item-<?php echo esc_attr( $icon['_id'] ); ?>">
				<<?php echo esc_attr( $html_tag ); ?> <?php xpro_elementor_kses( $attr ); ?> class="xpro-team-social-icon">
				<?php Icons_Manager::render_icon( $icon['social_icon'], array( 'aria-hidden' => 'true' ) ); ?>
				</<?php echo esc_attr( $html_tag ); ?>>
				</li>
			<?php } ?>
		</ul>
	<?php endif; ?>
</div>
<?php endif; ?>
<div class="xpro-team-content">
	<?php if ( '8' !== $settings['layout'] && '9' !== $settings['layout'] ) : ?>
		<?php if ( $settings['title'] ) : ?>
	<<?php echo esc_attr( $title_tag ); ?><?php xpro_elementor_kses( $title_attr ); ?>
	class="xpro-team-title"><?php echo esc_attr( $settings['title'] ); ?></<?php echo esc_attr( $title_tag ); ?>>
<?php endif; ?>
		<?php if ( $settings['designation'] && '2' !== $settings['layout'] ) : ?>
	<h4 class="xpro-team-designation"><?php echo esc_attr( $settings['designation'] ); ?></h4>
		<?php endif; ?>
<?php endif; ?>
<?php if ( $settings['description'] ) : ?>
	<p class="xpro-team-description"><?php echo esc_attr( $settings['description'] ); ?></p>
<?php endif; ?>

<?php if ( $settings['social_enable'] && $settings['social_icon_list'] && ( '2' !== $settings['layout'] && '3' !== $settings['layout'] && '5' !== $settings['layout'] && '8' !== $settings['layout'] && '12' !== $settings['layout'] && '13' !== $settings['layout'] && '15' !== $settings['layout'] ) ) : ?>
	<ul class="xpro-team-social-list">
		<?php
		foreach ( $settings['social_icon_list'] as $i => $icon ) {
			$html_tag = $icon['icon_link']['url'] ? 'a' : 'span';
			$attr     = $icon['icon_link']['is_external'] ? ' target="_blank"' : '';
			$attr    .= $icon['icon_link']['nofollow'] ? ' rel="nofollow"' : '';
			$attr    .= $icon['icon_link']['url'] ? ' href="' . $icon['icon_link']['url'] . '"' : '';
			?>
		<li class="elementor-repeater-item-<?php echo esc_attr( $icon['_id'] ); ?>">
			<<?php echo esc_attr( $html_tag ); ?> <?php xpro_elementor_kses( $attr ); ?> class="xpro-team-social-icon">
			<?php Icons_Manager::render_icon( $icon['social_icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</<?php echo esc_attr( $html_tag ); ?>>
			</li>
		<?php } ?>
	</ul>
<?php endif; ?>
</div>
</div>
