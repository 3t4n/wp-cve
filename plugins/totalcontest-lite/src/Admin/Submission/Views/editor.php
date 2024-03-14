<div id="totalcontest-submission-editor" ng-app="submission-editor" ng-controller="SubmissionCtrl as $ctrl" ng-cloak="">
    <div class="totalcontest-tabs-container has-tabs totalcontest-settings totalcontest-options">
		<?php do_action( 'totalcontest/actions/admin/submission/editor/before' ); ?>
        <div class="totalcontest-tabs">
            <div class="totalcontest-tabs-item active" tab-switch="submission>preview">
                <span class="dashicons dashicons-visibility"></span>
				<?php  esc_html_e( 'Preview', 'totalcontest' ); ?>
            </div>
            <div class="totalcontest-tabs-item" tab-switch="submission>fields">
                <span class="dashicons dashicons-feedback"></span>
				<?php  esc_html_e( 'Fields', 'totalcontest' ); ?>
            </div>
            <div class="totalcontest-tabs-item" tab-switch="submission>contents">
                <span class="dashicons dashicons-format-aside"></span>
				<?php  esc_html_e( 'Contents', 'totalcontest' ); ?>
            </div>
            <div class="totalcontest-tabs-item" tab-switch="submission>votes">
                <span class="dashicons dashicons-chart-bar"></span>
				<?php  esc_html_e( 'Votes', 'totalcontest' ); ?>
            </div>
            <div class="totalcontest-tabs-item" tab-switch="submission>designation">
                <span class="dashicons dashicons-megaphone"></span>
				<?php  esc_html_e( 'Designation', 'totalcontest' ); ?>
            </div>
			<?php do_action( 'totalcontest/actions/admin/submission/editor/tabs' ); ?>
        </div>
        <div class="totalcontest-tabs-content">
			<?php include __DIR__ . '/tabs/preview.php'; ?>
			<?php include __DIR__ . '/tabs/fields.php'; ?>
			<?php include __DIR__ . '/tabs/contents.php'; ?>
			<?php include __DIR__ . '/tabs/votes.php'; ?>
			<?php include __DIR__ . '/tabs/designation.php'; ?>
			<?php do_action( 'totalcontest/actions/admin/submission/editor/tabs-content' ); ?>
        </div>
		<?php do_action( 'totalcontest/actions/admin/submission/editor/after' ); ?>
    </div>

    <!-- Helpers -->
    <input type="hidden" name="totalcontest_current_tab" ng-value="getCurrentTab()">

    <!-- Submission settings field -->
    <textarea name="content" rows="60" class="widefat" readonly ng-bind-template="{{settings|json}}" hidden><?php echo isset( $GLOBALS['post'] ) ? esc_textarea( $GLOBALS['post']->post_content ) : '{}'; ?></textarea>
</div>
