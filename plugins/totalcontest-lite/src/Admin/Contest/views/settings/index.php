<div class="totalcontest-tabs-container has-tabs totalcontest-settings">
    <div class="totalcontest-tabs">
		<?php
		/**
		 * Fires before settings tabs.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/admin/editor/settings/tabs', $this );
		?>

		<?php $firstTab = key( $settingsTabs ) ?>
		<?php foreach ( $settingsTabs as $tabId => $tab ): ?>
            <div track="{ event : 'contest-settings-tab', target: '<?php echo esc_attr($tabId); ?>' }" class="totalcontest-tabs-item <?php echo $tabId == $firstTab ? 'active' : ''; ?>" tab-switch="editor>settings>general><?php echo esc_attr( $tabId ); ?>">
                <span class="dashicons dashicons-<?php echo esc_attr( $tab['icon'] ); ?>"></span>
				<?php echo esc_html( $tab['label'] ); ?>
            </div>
		<?php endforeach; ?>

		<?php
		/**
		 * Fires after settings tabs.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/admin/editor/settings/tabs', $this );
		?>
    </div>
    <div class="totalcontest-tabs-content">
		<?php foreach ( $settingsTabs as $tabId => $tab ): ?>
            <div class="totalcontest-tab-content <?php echo $tabId == $firstTab ? 'active' : ''; ?>" tab="editor>settings>general><?php echo esc_attr( $tabId ); ?>">
				<?php
				/**
				 * Fires before settings tab content.
				 *
				 * @since 2.0.0
				 */
				do_action( 'totalcontest/actions/before/admin/editor/settings/tabs/content', $tabId, $this );

				$path = empty( $tab['file'] ) ? __DIR__ . "/{$tabId}/index.php" : $tab['file'];
				if ( file_exists( $path ) ):
					include_once $path;
                elseif ( ! empty( $tab['tabs'] ) ):
					include __DIR__ . '/subtab.php';
				endif;

				/**
				 * Fires after settings tab content.
				 *
				 * @since 2.0.0
				 */
				do_action( 'totalcontest/actions/after/admin/editor/settings/tabs/content', $tabId, $this );
				?>
            </div>
		<?php endforeach; ?>
    </div>
</div>