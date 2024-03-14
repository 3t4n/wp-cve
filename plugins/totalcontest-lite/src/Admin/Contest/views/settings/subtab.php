<div class="totalcontest-tabs-container">
    <div class="totalcontest-tabs">
		<?php $firstSubtab = key( $tab['tabs'] ) ?>
		<?php foreach ( $tab['tabs'] as $subTabId => $subtab ): ?>
            <div class="totalcontest-tabs-item <?php echo $subTabId == $firstSubtab ? 'active' : ''; ?>" tab-switch="editor>settings>general><?php echo esc_attr( $tabId ); ?>><?php echo esc_attr( $subTabId ); ?>">
                <span class="dashicons dashicons-<?php echo esc_attr( $subtab['icon'] ); ?>"></span>
				<?php echo esc_html( $subtab['label'] ); ?>
            </div>
		<?php endforeach; ?>
    </div>
    <div class="totalcontest-tabs-content">
		<?php foreach ( $tab['tabs'] as $subTabId => $subtab ): ?>
            <div class="totalcontest-tab-content <?php echo $subTabId == $firstSubtab ? 'active' : ''; ?>" tab="editor>settings>general><?php echo esc_attr( $tabId ); ?>><?php echo esc_attr( $subTabId ); ?>">
				<?php
				/**
				 * Fires before settings sub tab content.
				 *
				 * @since 2.0.0
				 */
				do_action( 'totalcontest/actions/before/admin/editor/settings/tabs/content', "{$tabId}-{$subTabId}", $this );

				$path = empty( $subtab['file'] ) ? __DIR__ . "/{$tabId}/{$subTabId}.php" : $subtab['file'];
				if ( file_exists( $path ) ):
					include_once $path;
				endif;

				/**
				 * Fires after settings sub tab content.
				 *
				 * @since 2.0.0
				 */
				do_action( 'totalcontest/actions/after/admin/editor/settings/tabs/content', "{$tabId}-{$subTabId}", $this );
				?>
            </div>
		<?php endforeach; ?>
    </div>
</div>