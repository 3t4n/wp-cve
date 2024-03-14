<customizer-tabs>
	<?php foreach ( $designTab['tabs'] as $subtabId => $subtab ): ?>
        <customizer-tab target="<?php echo esc_attr( $designTabId ); ?>-<?php echo esc_attr( $subtabId ); ?>"><?php echo esc_html( $subtab['label'] ); ?></customizer-tab>
	<?php endforeach; ?>
</customizer-tabs>

<?php foreach ( $designTab['tabs'] as $subtabId => $subtab ): ?>
    <customizer-tab-content name="<?php echo esc_attr( $designTabId ); ?>-<?php echo esc_attr( $subtabId ); ?>" class="totalcontest-design-tabs-content-<?php echo esc_attr( $designTabId ); ?>-<?php echo esc_attr( $subtabId ); ?>">
		<?php
		/**
		 * Fires before design settings tab content.
		 *
		 * @since 4.0.0
		 */
		do_action( "totalcontest/actions/before/admin/editor/design/tabs/content/{$designTabId}-{$subtabId}" );

		$path = empty( $subtab['file'] ) ? __DIR__ . "/{$designTabId}-{$subtabId}.php" : $subtab['file'];
		if ( file_exists( $path ) ):
			include_once $path;
		endif;

		/**
		 * Fires after design settings tab content.
		 *
		 * @since 4.0.0
		 */
		do_action( "totalcontest/actions/after/admin/editor/design/tabs/content/{$designTabId}-{$subtabId}" );
		?>
    </customizer-tab-content>
<?php endforeach; ?>