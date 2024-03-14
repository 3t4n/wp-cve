<?php if ( $this->Page ) : ?>
	<h1><?php echo esc_attr( $this->Page->getPageTitle() ); ?></h1>
<?php endif; ?>

<div class="fab-tailwind">

	<div class="header <?php echo ( isset( $background ) ) ? esc_attr( $background ) : ''; ?>">
		<?php ( isset( $nav ) ) ? $this->loadContent( $nav ) : ''; ?>

		<ul class="nav-tab-wrapper <?php echo ( isset( $disableTab ) ) ? '' : 'nav-tab-general'; ?>">
			<?php foreach ( $this->sections as $path => $section ) : ?>
				<?php extract( $this->sectionLoopLogic( $path, $section ) ); ?>
				<li class="nav-tab <?php echo ( $active ) ? 'nav-tab-active' : ''; ?>" data-tab="section-<?php echo esc_attr( $slug ); ?>">
					<?php
						/** Tab contains 2 types: External Link (https://google.com) and Internal Link (#setting) */
						echo strpos( $tab, '//' ) ? esc_url( $tab ) : esc_attr( $tab );
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="content">
		<?php foreach ( $this->sections as $path => $section ) : ?>
			<?php extract( $this->sectionLoopLogic( $path, $section ) ); ?>
			<div id="section-<?php echo esc_attr( $slug ); ?>" class="tab-content <?php echo ( esc_attr( $active ) ) ? 'current' : ''; ?>">
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
