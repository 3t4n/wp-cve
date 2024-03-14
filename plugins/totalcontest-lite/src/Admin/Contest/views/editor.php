<div id="totalcontest-contest-editor" ng-app="contest-editor" ng-controller="EditorCtrl as editor">
	<?php
	/**
	 * Fires before contest editor content.
	 *
	 * @since 2.0.0
	 */
	do_action( 'totalcontest/actions/before/admin/contest/editor', $this );
	?>
	<?php include_once __DIR__ . '/loading.php'; ?>
    <div class="totalcontest-contest-wrapper">
		<?php
		if ( $GLOBALS['current_screen']->action !== 'add' ): ?>
            <div class="totalcontest-contest-issues">
                <div class="totalcontest-contest-issues-item" ng-if="!editor.settings.contest.submissions.blocks.enabled && !editor.settings.contest.submissions.content.trim()">
                    <div class="totalcontest-contest-issues-item-icon">
                        <span class="dashicons dashicons-warning"></span>
                    </div>
                    <div class="totalcontest-contest-issues-item-description"><?php  esc_html_e( 'Submissions content template is empty, your visitors will not be able to see the content of submission.', 'totalcontest' ); ?></div>
                </div>
            </div>
		<?php endif; ?>
        <div class="totalcontest-contest-tabs">
			<?php $firstTab = key( $tabs ) ?>
			<?php foreach ( $tabs as $tabId => $tab ): ?>
                <div class="totalcontest-contest-tabs-item <?php echo $tabId == $firstTab ? 'active' : ''; ?>" tab-switch="editor><?php echo esc_attr( $tabId ); ?>" <?php if ( $tabId == 'translations' ): ?>ng-if="editor.languages.length"<?php endif; ?>>
                    <div class="totalcontest-contest-tabs-item-icon">
                        <span class="dashicons dashicons-<?php echo esc_attr( $tab['icon'] ); ?>"></span>
                    </div>
					<?php echo esc_html( $tab['label'] ); ?>
                </div>
			<?php endforeach; ?>
        </div>
        <div class="totalcontest-contest-tabs-content-wrapper">
			<?php foreach ( $tabs as $tabId => $tab ): ?>
                <div class="totalcontest-tab-content <?php echo $tabId == $firstTab ? 'active' : ''; ?>" tab="editor><?php echo esc_attr( $tabId ); ?>">
					<?php
					/**
					 * Fires before contest editor tab content.
					 *
					 * @since 2.0.0
					 */
					do_action( 'totalcontest/actions/before/admin/contest/editor/tabs/content', $tabId, $this );

					$path = empty( $tab['file'] ) ? __DIR__ . "/{$tabId}/index.php" : $tab['file'];
					if ( file_exists( $path ) ):
						include_once $path;
					endif;

					/**
					 * Fires after contest editor tab content.
					 *
					 * @since 2.0.0
					 */
					do_action( 'totalcontest/actions/after/admin/contest/editor/tabs/content/', $tabId, $this );
					?>
                </div>
			<?php endforeach; ?>
        </div>
    </div>

    <!-- Helpers -->
    <input type="hidden" name="totalcontest_current_tab" ng-value="getCurrentTab()">

    <!-- Contest settings field -->
    <textarea name="content" rows="30" class="widefat" readonly hidden
              ng-bind-template="{{editor.settings|json}}"><?php echo empty( $this->post ) ? '{}' : esc_textarea( $GLOBALS['post']->post_content ); ?></textarea>
	<?php
	// The ugly way, unfortunately.
	ob_start();
	wp_editor( '', 'tinymce-field', [
		'textarea_name'     => 'tinymce-textarea-name',
		'textarea_rows'     => 10,
		'drag_drop_upload'  => true,
		'tabfocus_elements' => 'content-html,save-post',
		'tinymce'           => [
			'wp_autoresize_on'   => false,
			'add_unload_trigger' => false,
		],
	] );
	$tinyMce = ob_get_clean();
	?>
    <script type="text/javascript">
        var TinyMCETemplate = <?php echo json_encode( $tinyMce, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?>
    </script>

    <script type="text/ng-template" id="progressive-textarea-template">
        <textarea name="" ng-model="$ctrl.model" rows="{{$ctrl.rows || 4}}" ng-if="$ctrl.isSimple()" class="totalcontest-settings-field-input widefat"></textarea>
        <tinymce ng-model="$ctrl.model" uid="{{ $ctrl.uid }}" ng-if="$ctrl.isAdvanced()"></tinymce>
        <a ng-click="$ctrl.switchToAdvanced()" ng-if="$ctrl.isSimple()"><?php  esc_html_e( 'Switch to advanced', 'totalcontest' ); ?></a>
    </script>

    <script type="text/javascript">
        document.querySelector('form#post').setAttribute('novalidate', 'novalidate');
    </script>

	<?php
	/**
	 * Fires after contest editor content.
	 *
	 * @since 2.0.0
	 */
	do_action( 'totalcontest/actions/after/admin/contest/editor', $this );
	?>

    <feedback-collector></feedback-collector>

    <!-- Templates -->
	<?php include __DIR__ . '/feedback-collector.php'; ?>

</div>
