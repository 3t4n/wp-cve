<div class="totalcontest-integration">
    <div class="totalcontest-integration-tabs">
		<?php $firstIntegrationTab = key( $integrationTabs ) ?>
		<?php foreach ( $integrationTabs as $integrationTabId => $integrationTab ): ?>
            <div track="{ event : 'contest-integration', target: '<?php echo esc_attr( $integrationTabId ); ?>' }" class="totalcontest-integration-tabs-item <?php echo $integrationTabId == $firstIntegrationTab ? 'active' : ''; ?>" tab-switch="editor>integration>methods><?php echo esc_attr( $integrationTabId ); ?>">
                <div class="totalcontest-integration-tabs-item-icon">
                    <span class="dashicons dashicons-<?php echo esc_attr( $integrationTab['icon'] ); ?>"></span>
                </div>
                <div class="totalcontest-integration-tabs-item-title">
                    <h3 class="totalcontest-h3">
						<?php echo esc_html( $integrationTab['label'] ); ?>
                    </h3>
                    <p>
						<?php echo esc_html( $integrationTab['description'] ); ?>
                    </p>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
	<?php foreach ( $integrationTabs as $integrationTabId => $integrationTab ): ?>
        <div class="totalcontest-integration-tabs-content <?php echo $integrationTabId == $firstIntegrationTab ? 'active' : ''; ?>" tab="editor>integration>methods><?php echo esc_attr( $integrationTabId ); ?>">
			<?php
			/**
			 * Fires before integration tab content.
			 *
			 * @since 2.0.0
			 */
			do_action( "totalcontest/actions/before/admin/contest/editor/integration/tabs/content/{$integrationTabId}" );

			$path = empty( $integrationTab['file'] ) ? __DIR__ . "/{$integrationTabId}.php" : $integrationTab['file'];
			if ( file_exists( $path ) ):
				include_once $path;
			endif;

			/**
			 * Fires after integration tab content.
			 *
			 * @since 2.0.0
			 */
			do_action( "totalcontest/actions/after/admin/contest/editor/integration/tabs/content/{$integrationTabId}" );
			?>
        </div>
	<?php endforeach; ?>
</div>