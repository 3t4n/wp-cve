<?php if ( isset( $tabs ) ) { ?>
	<nav class="wss-tabs nav-tab-wrapper woo-nav-tab-wrapper">
		<?php foreach ( $tabs as $tab ) { ?>
			<?php $external = isset( $tab['external'] ) && $tab['external']; ?>
			<a href="<?php echo $tab['url']; ?>" <?php echo $external ? 'target="_blank"' : ''; ?>  class="nav-tab <?php echo $tab['active'] ? 'nav-tab-active' : ''; ?>">
				<?php echo $tab['title']; ?>

				<?php if ( $external ) { ?>
					<span class="dashicons dashicons-external"></span>
				<?php } ?>
			</a>
		<?php } ?>
	</nav>
<?php } ?>
