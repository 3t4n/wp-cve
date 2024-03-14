<script type="text/ng-template" id="tpl-image-field">
	<?php
	/**
	 * Fires before image field content.
	 *
	 * @since 2.0.0
	 */
	do_action( 'totalcontest/actions/before/admin/contest/editor/fields/field', 'image', $this );
	?>
    <div class="totalcontest-tab-content active" tab="editor>form>field-{{field.uid}}>basic">
		<?php
		/**
		 * Fires before image field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/admin/contest/editor/fields/field/basic', 'image', $this );
		?>
        <div class="totalcontest-settings-item" ng-include="'field-basic-label-template'"></div>
        <div class="totalcontest-settings-item" ng-include="'field-basic-name-template'"></div>
        <div class="totalcontest-settings-item" ng-include="'field-basic-placeholder-template'"></div>
		<?php
		/**
		 * Fires after image field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/admin/contest/editor/fields/field/basic', 'image', $this );
		?>
    </div>
    <div class="totalcontest-tab-content" tab="editor>form>field-{{field.uid}}>validations">
		<?php
		/**
		 * Fires before image field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/admin/contest/editor/fields/field/validations', 'image', $this );
		?>
        <div class="totalcontest-settings-item" ng-include="'field-validations-filled-template'"></div>
        <div class="totalcontest-settings-item" ng-include="'field-validations-file-size-template'"></div>
        <!-- Formats -->
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <label>
                    <input type="checkbox" name="" ng-model="field.validations.formats.enabled" ng-checked="field.validations.formats.enabled">
					<?php  esc_html_e( 'Formats', 'totalcontest' ); ?>
                </label>
            </div>
        </div>
        <div class="totalcontest-settings-item-advanced" ng-class="{active: field.validations.formats.enabled && !field.validations.services.enabled}">

            <div class="totalcontest-settings-field">
                <label class="totalcontest-settings-field-label">
					<?php  esc_html_e( 'Allowed formats', 'totalcontest' ); ?>
                </label>

                <label>
                    <input type="checkbox" name="" ng-model="field.validations.formats.extensions.jpeg">
					<?php  esc_html_e( 'JPEG/JPG', 'totalcontest' ); ?>
                </label>
                &nbsp;&nbsp;

                <label>
                    <input type="checkbox" name="" ng-model="field.validations.formats.extensions.png">
					<?php  esc_html_e( 'PNG', 'totalcontest' ); ?>
                </label>
                &nbsp;&nbsp;

                <label>
                    <input type="checkbox" name="" ng-model="field.validations.formats.extensions.gif">
					<?php  esc_html_e( 'GIF', 'totalcontest' ); ?>
                </label>
                &nbsp;&nbsp;

                <label>
                    <input type="checkbox" name="" ng-model="field.validations.formats.extensions.bmp">
					<?php  esc_html_e( 'BMP', 'totalcontest' ); ?>
                </label>
                &nbsp;&nbsp;

                <label>
                    <input type="checkbox" name="" ng-model="field.validations.formats.extensions.webp">
					<?php  esc_html_e( 'WebP', 'totalcontest' ); ?>
                </label>

            </div>

        </div>

        <!-- Dimensions -->
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <label>
                    <input type="checkbox" name="" ng-model="field.validations.dimensions.enabled" ng-checked="field.validations.dimensions.enabled">
					<?php  esc_html_e( 'Dimensions', 'totalcontest' ); ?>
                </label>
            </div>
        </div>
        <div class="totalcontest-settings-item-advanced" ng-class="{active: field.validations.dimensions.enabled && !field.validations.services.enabled}">
            <div class="totalcontest-settings-item totalcontest-settings-item-inline">
                <div class="totalcontest-settings-field">
                    <label class="totalcontest-settings-field-label">
						<?php  esc_html_e( 'Minimum width (px)', 'totalcontest' ); ?>
                    </label>
                    <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat" ng-model="field.validations.dimensions.minWidth">
                </div>
                <div class="totalcontest-settings-field">
                    <label class="totalcontest-settings-field-label">
						<?php  esc_html_e( 'Minimum height (px)', 'totalcontest' ); ?>
                    </label>
                    <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat" ng-model="field.validations.dimensions.minHeight">
                </div>
            </div>
            <div class="totalcontest-settings-item totalcontest-settings-item-inline">
                <div class="totalcontest-settings-field">
                    <label class="totalcontest-settings-field-label">
						<?php  esc_html_e( 'Maximum width (px)', 'totalcontest' ); ?>
                    </label>
                    <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat" ng-model="field.validations.dimensions.maxWidth">
                </div>
                <div class="totalcontest-settings-field">
                    <label class="totalcontest-settings-field-label">
						<?php  esc_html_e( 'Maximum height (px)', 'totalcontest' ); ?>
                    </label>
                    <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat" ng-model="field.validations.dimensions.maxHeight">
                </div>
            </div>
        </div>

		<?php
		/**
		 * Fires after image field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/admin/contest/editor/fields/field/validations', 'image', $this );
		?>
    </div>
    <div class="totalcontest-tab-content" tab="editor>form>field-{{field.uid}}>html">
		<?php
		/**
		 * Fires before image field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/admin/contest/editor/fields/field/html', 'image', $this );
		?>
        <div class="totalcontest-settings-item" ng-include="'field-html-css-class-template'"></div>
        <div class="totalcontest-settings-item" ng-include="'field-html-template-template'"></div>
		<?php
		/**
		 * Fires after image field content.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/admin/contest/editor/fields/field/html', 'image', $this );
		?>
    </div>
	<?php
	/**
	 * Fires after image field content.
	 *
	 * @since 2.0.0
	 */
	do_action( 'totalcontest/actions/after/admin/contest/editor/fields/field', 'image', $this );
	?>
</script>
