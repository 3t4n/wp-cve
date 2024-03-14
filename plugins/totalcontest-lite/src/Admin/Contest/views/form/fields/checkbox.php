<script type="text/ng-template" id="tpl-checkbox-field">
	<?php
	/**
	 * Fires before checkbox field content.
	 *
	 * @since 2.0.0
	 */
	do_action( 'totalcontest/actions/before/admin/contest/editor/fields/field', 'checkbox', $this );
	?>
    <div class="totalcontest-tab-content active" tab="editor>form>field-{{field.uid}}>basic">
		<?php
		/**
		 * Fires before checkbox field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/admin/contest/editor/fields/field/basic', 'checkbox', $this );
		?>
        <div class="totalcontest-settings-item" ng-include="'field-basic-label-template'"></div>
        <div class="totalcontest-settings-item" ng-include="'field-basic-name-template'"></div>
        <div class="totalcontest-settings-item" ng-include="'field-basic-options-template'"></div>
        <div class="totalcontest-settings-item" ng-include="'field-basic-default-value-template'"></div>
		<?php
		/**
		 * Fires after checkbox field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/admin/contest/editor/fields/field/basic', 'checkbox', $this );
		?>
    </div>
    <div class="totalcontest-tab-content" tab="editor>form>field-{{field.uid}}>validations">
		<?php
		/**
		 * Fires before checkbox field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/admin/contest/editor/fields/field/validations', 'checkbox', $this );
		?>
        <div class="totalcontest-settings-item" ng-include="'field-validations-filled-template'"></div>
		<?php
		/**
		 * Fires after checkbox field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/admin/contest/editor/fields/field/validations', 'checkbox', $this );
		?>
    </div>
    <div class="totalcontest-tab-content" tab="editor>form>field-{{field.uid}}>html">
		<?php
		/**
		 * Fires before checkbox field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/admin/contest/editor/fields/field/html', 'checkbox', $this );
		?>
        <div class="totalcontest-settings-item" ng-include="'field-html-css-class-template'"></div>
        <div class="totalcontest-settings-item" ng-include="'field-html-template-template'"></div>
		<?php
		/**
		 * Fires after checkbox field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/admin/contest/editor/fields/field/html', 'checkbox', $this );
		?>
    </div>
	<?php
	/**
	 * Fires after checkbox field content.
	 *
	 * @since 2.0.0
	 */
	do_action( 'totalcontest/actions/after/admin/contest/editor/fields/field', 'checkbox', $this );
	?>
</script>