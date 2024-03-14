<?php
/**
 * Template Style Nine for Advance Tab
 *
 * @package AbsoluteAddons
 */

/**
 * @var array $tab
 */
?>
<div class="content-left">
	<div class="tab-image">
		<img src="<?php echo esc_url( $tab['tab_image']['url'] ); ?>" alt="Content Image">
	</div>
</div>
<div class="content-right elementor-repeater-item-<?php echo esc_html( $tab['_id'] ) ?>">
	<?php $this->render_tab_content_title( $tab, $settings ); ?>
	<?php $this->render_tab_content( $tab ); ?>
	<?php $this->render_read_more( 'read-more', $tab, [ 'class' => 'tab-content-button' ] ); ?>
</div>
