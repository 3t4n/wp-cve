<?php
/** @var string $active_tab */
?>
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title"><?php esc_html_e( 'CSS Editor', 'canvas' ); ?></div>
	<div class="cas--settings__content">
		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item cas--settings__layout-row-item--wide">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Custom CSS for your App', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Include the CSS code that should be injected into your App', 'canvas' ); ?></p>
				<p>
					<textarea class="canvas-editor-textarea canvas-codemirror-css-field" name="canvas_editor_css"
						id="canvas_editor_css"><?php echo stripslashes( htmlspecialchars( get_option( 'canvas_editor_css' ) ) ); ?></textarea>
				</p>
			</div>
		</div>
	</div>
</div>
