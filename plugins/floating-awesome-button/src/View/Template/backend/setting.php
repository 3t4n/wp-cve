<div class="fab-container">

	<div class="content py-4 mr-4">
		<?php foreach ( $this->sections as $path => $section ) : ?>
			<?php extract( $this->sectionLoopLogic( $path, $section ) ); ?>
			<div id="section-<?php echo esc_attr( $slug ); ?>" class="tab-content fab-sections <?php echo ( $active ) ? 'current' : ''; ?>">
				<?php
				if ( isset( $section['link'] ) && strpos( $section['link'], '//' ) ) {
					echo esc_url( $section['link'] );
				} else {
					$this->loadContent( $content );
				}
				?>
			</div>
			<?php if ( $active ) : ?>
				<div stlye="display:none;">
					<input type="hidden" name="activeSection" value="<?php echo esc_attr( $slug ); ?>">
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>

</div>
