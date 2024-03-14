<?php if ( $settings['text_editor_description'] ) : ?>
<div class="xpro-text-editor-wrapper<?php echo 'yes' === $settings['drop_cap'] ? ' xpro-text-editor-drop-cap' : ''; ?>">
	<?php xpro_elementor_kses( $settings['text_editor_description'] ); ?>
</div>
<?php endif; ?>
