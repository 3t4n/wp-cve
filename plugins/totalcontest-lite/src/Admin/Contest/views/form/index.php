<div class="totalcontest-fields" ng-controller="FormFieldsCtrl as fieldsCtrl">
	<?php
	/**
	 * Fires before fields.
	 *
	 * @since 2.0.0
	 */
	do_action( 'totalcontest/actions/before/admin/contest/editor/fields', $this );
	?>
    <div class="totalcontest-fields-content">
        <div class="totalcontest-fields-content-wrapper">
            <div class="totalcontest-fields-content-sidebar">
				<?php
				/**
				 * Fires before fields types.
				 *
				 * @since 2.0.0
				 */
				do_action( 'totalcontest/actions/after/admin/contest/editor/fields/types', $this );
				?>

				<?php
				$types = apply_filters( 'totalcontest/filters/admin/contest/editor/fields/types',
					[
						'text'      => [
							'label' => esc_html__( 'Text', 'totalcontest' ),
							'icon'  => 'editor-textcolor',
							'args'  => [ 'type' => 'text' ]
						],
						'number'    => [
							'label' => esc_html__( 'Number', 'totalcontest' ),
							'icon'  => 'plus',
							'args'  => [ 'type' => 'number' ]
						],
						'textarea'  => [
							'label' => esc_html__( 'Textarea', 'totalcontest' ),
							'icon'  => 'text',
							'args'  => [ 'type' => 'textarea' ]
						],
						'select'    => [
							'label' => esc_html__( 'Select', 'totalcontest' ),
							'icon'  => 'menu',
							'args'  => [ 'type' => 'select' ]
						],
						'checkbox'  => [
							'label' => esc_html__( 'Checkbox', 'totalcontest' ),
							'icon'  => 'yes',
							'args'  => [ 'type' => 'checkbox' ],

						],
						'radio'     => [
							'label' => esc_html__( 'Radio', 'totalcontest' ),
							'icon'  => 'marker',
							'args'  => [ 'type' => 'radio' ]
						],
						'image'     => [
							'label' => esc_html__( 'Image', 'totalcontest' ),
							'icon'  => 'format-image',
							'args'  => [ 'type' => 'image' ]
						],
						'video'     => [
							'label' => esc_html__( 'Video', 'totalcontest' ),
							'icon'  => 'format-video',
							'args'  => [ 'type' => 'video' ]
						],
						'audio'     => [
							'label' => esc_html__( 'Audio', 'totalcontest' ),
							'icon'  => 'format-audio',
							'args'  => [ 'type' => 'audio' ]
						],
						'category'  => [
							'label' => esc_html__( 'Category', 'totalcontest' ),
							'icon'  => 'category',
							'args'  => [ 'type' => 'category', 'name' => 'category' ]
						],
						'file'      => [
							'label' => esc_html__( 'File', 'totalcontest' ),
							'icon'  => 'media-default',
							'args'  => [ 'type' => 'file' ]
						],
						'rich-text' => [
							'label' => esc_html__( 'Rich Text', 'totalcontest' ),
							'icon'  => 'editor-kitchensink',
							'args'  => [ 'type' => 'richtext' ]
						],
						'embed'     => [
							'label' => esc_html__( 'Embed', 'totalcontest' ),
							'icon'  => 'admin-links',
							'args'  => [
								'type'        => 'embed',
								'validations' => [
									'uploadedVia' => [
										'enabled'  => true,
										'services' => [
											'dailymotion' => true,
											'instagram'   => true,
											'vimeo'       => true,
											'twitter'     => true,
											'facebook'    => true,
										]
									]
								]
							]
						],
					]
				);
				?>
                <div class="totalcontest-fields-types">
					<?php
					
					$proTypes = [ 'video', 'textarea', 'select', 'audio', 'category', 'file', 'rich-text' ];
					
					?>
					<?php
					foreach ( $types as $typeId => $type ): ?>
                        

                        
                        <?php
						if ( in_array( $typeId, $proTypes ) ): ?>
                        <div class="totalcontest-fields-types-item totalcontest-pro-badge-container" disabled>
                            <div class="dashicons dashicons-<?php
							echo esc_attr( $type['icon'] ); ?>"></div>
                            <div class="totalcontest-fields-types-item-title"><?php
							echo esc_html( $type['label'] ); ?></div>
                            <?php
							TotalContest( 'upgrade-to-pro' ); ?>
                        </div>
                        <?php
						else: ?>
                            <?php
							if ( $typeId === 'category' ): ?>
                                <div class="totalcontest-fields-types-item"
                                     ng-click="fieldsCtrl.hasCategoryField() || fieldsCtrl.insertField(<?php
								echo esc_js( json_encode( $type['args'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ); ?>)"
                                     ng-class="{disabled: fieldsCtrl.hasCategoryField()}">
                                    <div class="dashicons dashicons-<?php
								echo esc_attr( $type['icon'] ); ?>"></div>
                                    <div class="totalcontest-fields-types-item-title"><?php
								echo esc_html( $type['label'] ); ?></div>
                                </div>
                            <?php
							else: ?>
                                <div class="totalcontest-fields-types-item"
                                     ng-click="fieldsCtrl.insertField(<?php
								echo esc_js( json_encode( $type['args'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ); ?>)">
                                    <div class="dashicons dashicons-<?php
								echo esc_attr( $type['icon'] ); ?>"></div>
                                    <div class="totalcontest-fields-types-item-title"><?php
								echo esc_html( $type['label'] ); ?></div>
                                </div>
                            <?php
							endif; ?>
                        <?php
						endif; ?>
                    
					<?php
					endforeach; ?>
					<?php
					/**
					 * Fires after fields types.
					 *
					 * @since 2.0.0
					 */
					do_action( 'totalcontest/actions/after/admin/contest/editor/fields/types', $this );
					?>
                </div>
            </div>
            <div class="totalcontest-fields-content-main">
				<?php
				/**
				 * Fires before fields toolbar.
				 *
				 * @since 2.0.0
				 */
				do_action( 'totalcontest/actions/before/admin/contest/editor/fields/toolbar', $this );
				?>
                <div class="totalcontest-containable-toolbar">
                    <div class="button-group">
						<?php
						/**
						 * Fires at the 1st position of toolbar buttons.
						 *
						 * @var int $position
						 * @since 2.0.0
						 */
						do_action( 'totalcontest/actions/admin/contest/editor/fields/toolbar', 1, $this );
						?>
                        <button type="button" class="button button-large" ng-click="fieldsCtrl.collapseFields()">
							<?php
							esc_html_e( 'Collapse', 'totalcontest' ); ?>
                        </button>
						<?php
						/**
						 * Fires at the 2nd position of toolbar buttons.
						 *
						 * @var int $position
						 * @since 2.0.0
						 */
						do_action( 'totalcontest/actions/admin/contest/editor/fields/toolbar', 2, $this );
						?>
                        <button type="button" class="button button-large" ng-click="fieldsCtrl.expandFields()">
							<?php
							esc_html_e( 'Expand', 'totalcontest' ); ?>
                        </button>
						<?php
						/**
						 * Fires at the 3rd position of toolbar buttons.
						 *
						 * @var int $position
						 * @since 2.0.0
						 */
						do_action( 'totalcontest/actions/admin/contest/editor/fields/toolbar', 3, $this );
						?>
                    </div>

					<?php
					/**
					 * Fires at the 4th position of toolbar buttons.
					 *
					 * @var int $position
					 * @since 2.0.0
					 */
					do_action( 'totalcontest/actions/admin/contest/editor/fields/toolbar', 4, $this );
					?>
                    <button type="button" class="button button-large button-danger"
                            ng-click="fieldsCtrl.deleteFields()">
						<?php
						esc_html_e( 'Delete All', 'totalcontest' ); ?>
                    </button>
					<?php
					/**
					 * Fires at the 5th position of toolbar buttons.
					 *
					 * @var int $position
					 * @since 2.0.0
					 */
					do_action( 'totalcontest/actions/admin/contest/editor/fields/toolbar', 5, $this );
					?>
                </div>
				<?php
				/**
				 * Fires after fields toolbar.
				 *
				 * @since 2.0.0
				 */
				do_action( 'totalcontest/actions/after/admin/contest/editor/fields/toolbar', $this );
				?>
                <div class="totalcontest-empty-state" ng-if="!editor.settings.contest.form.fields.length">
                    <div class="totalcontest-empty-state-text">
						<?php
						esc_html_e( 'No custom fields yet. Add some by clicking on buttons below.', 'totalcontest' ); ?>
                    </div>
                </div>
                <div class="totalcontest-containable-list" dnd-list="editor.settings.contest.form.fields">
                    <div class="totalcontest-containable-list-item" ng-repeat="field in fields"
                         ng-class="{active: !fieldsCtrl.isCollapsed($index)}" dnd-draggable="field"
                         dnd-moved="fieldsCtrl.deleteField($index)" dnd-effect-allowed="move">
                        <div class="totalcontest-containable-list-item-toolbar totalcontest-containable-handle ui-sortable-handle"
                             ng-click="fieldsCtrl.toggle($index, $event)" dnd-handle="">
                            <div class="totalcontest-containable-list-item-toolbar-collapse"
                                 ng-click="fieldsCtrl.toggle($index, $event)">
                                <span class="totalcontest-containable-list-item-toolbar-collapse-text">{{ $index + 1 }}</span>
                                <span class="dashicons dashicons-arrow-down"
                                      ng-if="fieldsCtrl.isCollapsed($index)"></span>
                                <span class="dashicons dashicons-arrow-up"
                                      ng-if="!fieldsCtrl.isCollapsed($index)"></span>
                            </div>
							<?php
							/**
							 * Fires before field preview toolbar.
							 *
							 * @since 2.0.0
							 */
							do_action( 'totalcontest/actions/before/admin/contest/editor/fields/toolbar/preview',
								$this );
							?>
                            <div class="totalcontest-containable-list-item-toolbar-preview">
                                <div class="totalcontest-containable-list-item-toolbar-preview-text">{{field.label ||
                                    field.name || 'Untitled'}}
                                </div>
                                <div class="totalcontest-containable-list-item-toolbar-preview-type">
                                    <span>{{field.type}}</span>
                                    <span ng-if="field.validations.filled.enabled"><?php
										esc_html_e( 'Required',
											'totalcontest' ); ?></span>
                                </div>
                            </div>
							<?php
							/**
							 * Fires after field preview toolbar.
							 *
							 * @since 2.0.0
							 */
							do_action( 'totalcontest/actions/after/admin/contest/editor/fields/toolbar/preview',
								$this );
							?>

							<?php
							/**
							 * Fires before field delete button.
							 *
							 * @since 2.0.0
							 */
							do_action( 'totalcontest/actions/before/admin/contest/editor/fields/toolbar/delete',
								$this );
							?>
                            <div class="totalcontest-containable-list-item-toolbar-delete">
                                <button class="button button-danger button-small" type="button"
                                        ng-click="fieldsCtrl.deleteField($index, true, $event)"><?php
									esc_html_e( 'Delete',
										'totalcontest' ); ?></button>
                            </div>
							<?php
							/**
							 * Fires after field delete button.
							 *
							 * @since 2.0.0
							 */
							do_action( 'totalcontest/actions/after/admin/contest/editor/fields/toolbar/delete', $this );
							?>
                        </div>
                        <div class="totalcontest-containable-list-item-editor"
                             ng-class="{active: !fieldsCtrl.isCollapsed($index)}" dnd-nodrag>
                            <div class="totalcontest-tabs-container">
                                <div class="totalcontest-tabs">
									<?php
									/**
									 * Fires before field tabs content.
									 *
									 * @since 2.0.0
									 */
									do_action( 'totalcontest/actions/before/admin/contest/editor/fields/tabs', $this );
									?>
                                    <div class="totalcontest-tabs-item active"
                                         tab-switch="editor>form>field-{{field.uid}}>basic"><?php
										esc_html_e( 'Basic',
											'totalcontest' ); ?></div>
                                    <div class="totalcontest-tabs-item"
                                         tab-switch="editor>form>field-{{field.uid}}>validations"><?php
										esc_html_e( 'Validations',
											'totalcontest' ); ?></div>
                                    <div class="totalcontest-tabs-item"
                                         tab-switch="editor>form>field-{{field.uid}}>html"><?php
										esc_html_e( 'HTML',
											'totalcontest' ); ?></div>
									<?php
									/**
									 * Fires after field tabs content.
									 *
									 * @since 2.0.0
									 */
									do_action( 'totalcontest/actions/after/admin/contest/editor/fields/tabs', $this );
									?>
                                </div>
                                <div class="totalcontest-tabs-content" ng-include="'tpl-' + field.type + '-field'"
                                     ng-init="fieldsCtrl.normalizeField(field)">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dndPlaceholder totalcontest-list-placeholder">
                        <div class="totalcontest-list-placeholder-text">
							<?php
							esc_html_e( 'Move here', 'totalcontest' ); ?>
                        </div>
                    </div>
                </div>
            </div>

			<?php
			include __DIR__ . '/fields/image.php';
			include __DIR__ . '/fields/video.php';
			include __DIR__ . '/fields/audio.php';
			include __DIR__ . '/fields/category.php';
			include __DIR__ . '/fields/text.php';
			include __DIR__ . '/fields/textarea.php';
			include __DIR__ . '/fields/select.php';
			include __DIR__ . '/fields/checkbox.php';
			include __DIR__ . '/fields/radio.php';
			include __DIR__ . '/fields/file.php';
			include __DIR__ . '/fields/richtext.php';
			include __DIR__ . '/fields/embed.php';
			include __DIR__ . '/fields/number.php';

			?>
        </div>
    </div>
	<?php
	/**
	 * Fires after fields.
	 *
	 * @since 2.0.0
	 */
	do_action( 'totalcontest/actions/after/admin/contest/editor/fields', $this );
	?>
</div>
<!-- BASIC: LABEL -->
<script type="text/ng-template" id="field-basic-label-template">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-label">
			<?php
			esc_html_e( 'Label', 'totalcontest' ); ?>
        </label>
        <input id="field-{{field.uid}}-label" class="widefat" type="text"
               placeholder="<?php
		       esc_html_e( 'Field label', 'totalcontest' ); ?>"
               ng-model="field.label"
               ng-blur="fieldsCtrl.generateName(field)">
    </div>
</script>
<!-- BASIC: NAME  -->
<script type="text/ng-template" id="field-basic-name-template">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-name">
			<?php
			esc_html_e( 'Name', 'totalcontest' ); ?>
        </label>
        <input id="field-{{field.uid}}-name" class="widefat" type="text"
               placeholder="<?php
		       esc_html_e( 'Field name', 'totalcontest' ); ?>" ng-model="field.name">
    </div>
</script>
<!-- BASIC: NAME  -->
<script type="text/ng-template" id="field-basic-placeholder-template">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-placeholder">
			<?php
			esc_html_e( 'Placeholder', 'totalcontest' ); ?>
        </label>
        <input id="field-{{field.uid}}-placeholder" class="widefat" type="text"
               placeholder="<?php
		       esc_html_e( 'Field placeholder', 'totalcontest' ); ?>" ng-model="field.placeholder">
    </div>
</script>
<!-- BASIC: DEFAULT VALUE -->
<script type="text/ng-template" id="field-basic-default-value-template">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label"
               for="field-{{field.uid}}-default-value">
			<?php
			esc_html_e( 'Default value', 'totalcontest' ); ?>
        </label>
        <input id="field-{{field.uid}}-default-value" class="widefat" type="text"
               placeholder="<?php
		       esc_html_e( 'Field default value', 'totalcontest' ); ?>"
               ng-if="['text', 'radio', 'embed'].indexOf(field.type) >= 0"
               ng-model="field.default">
        <input id="field-{{field.uid}}-default-value" class="widefat" type="number"
               min="{{field.validations.min.enabled ? field.validations.min.value : 0 }}"
               max="{{field.validations.max.enabled ? field.validations.max.value : '' }}"
               placeholder="<?php
		       esc_html_e( 'Field default value', 'totalcontest' ); ?>"
               ng-if="field.type === 'number'"
               ng-model="field.default">
        <textarea id="field-{{field.uid}}-default-value" class="widefat"
                  placeholder="<?php
		          esc_html_e( 'Field default value', 'totalcontest' ); ?>"
                  ng-if="field.type === 'textarea' || field.type === 'richtext' || field.type === 'select' || field.type === 'checkbox'"
                  ng-model="field.default"></textarea>
        <p class="totalcontest-feature-tip" ng-if="field.type === 'select' || field.type === 'checkbox'">
			<?php
			esc_html_e( 'Default value per line.', 'totalcontest' ); ?>
        </p>
    </div>
</script>
<!-- BASIC: OPTIONS  -->
<script type="text/ng-template" id="field-basic-options-template">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-options">
			<?php
			esc_html_e( 'Options', 'totalcontest' ); ?>
        </label>
        <textarea id="field-{{field.uid}}-options" class="widefat" type="text"
                  placeholder="<?php
		          esc_html_e( 'option_key:Option label', 'totalcontest' ); ?>"
                  ng-model="field.options" rows="6"></textarea>
        <p class="totalcontest-feature-tip">
			<?php
			esc_html_e( 'Option per line.', 'totalcontest' ); ?>
        </p>
        <p class="totalcontest-feature-tip">
			<?php
			esc_html_e( 'Option format is "option : label"', 'totalcontest' ); ?>
        </p>
    </div>
</script>
<!-- BASIC: MULTIPLE SELECTION  -->
<script type="text/ng-template" id="field-basic-multiple-values-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label>
                <input type="checkbox" name="" ng-model="field.attributes.multiple">
				<?php
				esc_html_e( 'Allow multiple values', 'totalcontest' ); ?>
            </label>
        </div>
    </div>
</script>
<!-- VALIDATION: FILLED -->
<script type="text/ng-template" id="field-validations-filled-template">
    <div class="totalcontest-settings-field">
        <label>
            <input type="checkbox" name="" ng-checked="field.validations.filled.enabled"
                   ng-model="field.validations.filled.enabled">
			<?php
			esc_html_e( 'Filled (required)', 'totalcontest' ); ?>
        </label>
    </div>
</script>
<!-- VALIDATION: FILLED -->
<script type="text/ng-template" id="field-validations-filled-template">
    <div class="totalcontest-settings-field">
        <label>
            <input type="checkbox" name="" ng-checked="field.validations.filled.enabled"
                   ng-model="field.validations.filled.enabled">
			<?php
			esc_html_e( 'Filled (required)', 'totalcontest' ); ?>
        </label>
    </div>
</script>

<!-- VALIDATION: MIN -->
<script type="text/ng-template" id="field-validations-min-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label>
                <input type="checkbox" name="" ng-checked="field.validations.min.enabled"
                       ng-model="field.validations.min.enabled"> <?php
				esc_html_e( 'Minimum', 'totalcontest' ); ?>
            </label>
        </div>
    </div>
    <div class="totalcontest-settings-item-advanced" ng-class="{active: field.validations.min.enabled}">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-validations-min"> <?php
				esc_html_e( 'Minimum value', 'totalcontest' ); ?></label>
            <input id="field-{{field.uid}}-validations-min" type="number" min="0" ng-model="field.validations.min.value"
                   class="totalcontest-settings-field-input widefat">
        </div>
    </div>
</script>

<!-- VALIDATION: max -->
<script type="text/ng-template" id="field-validations-max-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label>
                <input type="checkbox" name="" ng-checked="field.validations.max.enabled"
                       ng-model="field.validations.max.enabled"> <?php
				esc_html_e( 'Maximum', 'totalcontest' ); ?>
            </label>
        </div>
    </div>
    <div class="totalcontest-settings-item-advanced" ng-class="{active: field.validations.max.enabled}">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-validations-max"> <?php
				esc_html_e( 'Maximum value', 'totalcontest' ); ?></label>
            <input id="field-{{field.uid}}-validations-max" type="number" min="0" ng-model="field.validations.max.value"
                   class="totalcontest-settings-field-input widefat">
        </div>
    </div>
</script>


<!-- VALIDATION: EMAIL -->
<script type="text/ng-template" id="field-validations-email-template">
    <div class="totalcontest-settings-field">
        <label>
            <input type="checkbox" name="" ng-checked="field.validations.email.enabled"
                   ng-model="field.validations.email.enabled">
			<?php
			esc_html_e( 'Email', 'totalcontest' ); ?>
        </label>
    </div>
</script>
<!-- VALIDATION: UNIQUE -->
<script type="text/ng-template" id="field-validations-unique-template">
    <div class="totalcontest-settings-field">
        <label>
            <input type="checkbox" name="" ng-checked="field.validations.unique.enabled"
                   ng-model="field.validations.unique.enabled">
			<?php
			esc_html_e( 'Unique', 'totalcontest' ); ?>
            <span class="totalcontest-feature-details"
                  tooltip="<?php
			      esc_html_e( 'This field value must be unique in entries table.', 'totalcontest' ); ?>">?</span>
        </label>
    </div>
</script>
<!-- VALIDATION: FILTER -->
<script type="text/ng-template" id="field-validations-filter-template">
    <div class="totalcontest-settings-field">
        <label>
            <input type="checkbox" name="" ng-checked="field.validations.filter.enabled"
                   ng-model="field.validations.filter.enabled">
			<?php
			esc_html_e( 'Filter by list', 'totalcontest' ); ?>
        </label>
    </div>
    <div class="totalcontest-settings-item-advanced" ng-class="{active: field.validations.filter.enabled}">
        <table class="wp-list-table widefat striped"
               ng-controller="RepeaterCtrl as $repeater"
               ng-init="$repeater.items = field.validations.filter.rules = (field.validations.filter.rules || [])">
            <thead>
            <tr>
                <th class="totalcontest-width-15">
					<?php
					esc_html_e( 'Type', 'totalcontest' ); ?>
                </th>
                <th class="widefat">
					<?php
					esc_html_e( 'Value', 'totalcontest' ); ?>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="item in $repeater.items track by $index">
                <td>
                    <select class="totalcontest-settings-field-input widefat" ng-model="item.type">
                        <option value="allow">
							<?php
							esc_html_e( 'Allow', 'totalcontest' ); ?>
                        </option>
                        <option value="deny">
							<?php
							esc_html_e( 'Deny', 'totalcontest' ); ?>
                        </option>
                    </select>
                </td>
                <td>
                    <input type="text" class="totalcontest-settings-field-input widefat" ng-model="item.value"
                           placeholder="<?php
					       esc_html_e( '* means wildcard.', 'totalcontest' ); ?>">
                </td>
                <td>
                    <div class="button-group">
                        <button type="button" class="button button-icon" ng-click="$repeater.moveUp($index)"
                                ng-disabled="$index === 0">
                            <span class="dashicons dashicons-arrow-up-alt2"></span>
                        </button>
                        <button type="button" class="button button-icon" ng-click="$repeater.moveDown($index)"
                                ng-disabled="$index === $repeater.items.length - 1">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </button>
                        <button type="button" class="button button-danger" ng-click="$repeater.deleteItem($index)">
							<?php
							esc_html_e( 'Delete', 'totalcontest' ); ?>
                        </button>
                    </div>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3">
                    <div class="textright">
                        <button type="button" class="button button-primary"
                                ng-click="$repeater.addItem({type: 'allow'})">
							<?php
							esc_html_e( 'Add new value', 'totalcontest' ); ?>
                        </button>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</script>

<!-- VALIDATION: OEMBED -->
<script type="text/ng-template" id="field-validations-oembed-template">
    <div class="totalcontest-settings-field">
        <label>
            <div class="totalcontest-settings-field">
                <label><?php
					esc_html_e( 'Allowed services', 'totalcontest' ); ?></label>
            </div>
        </label>
    </div>
    <div class="totalcontest-settings-item-advanced active">
        <div class="totalcontest-settings-options">
			<?php
			foreach ( $embedProviders as $provider ): ?>
                <label class="totalcontest-settings-options-item">
                    <input type="checkbox" name="" ng-model="field.validations.uploadedVia.services.<?php
					echo esc_attr( $provider ); ?>">
					<?php
					echo esc_html( ucfirst( $provider ) ); ?>
                </label>
			<?php
			endforeach; ?>
        </div>
    </div>
</script>

<!-- VALIDATION: REGEX -->
<script type="text/ng-template" id="field-validations-regex-template">
    <div class="totalcontest-settings-field">
        <label>
            <input type="checkbox" name="" ng-checked="field.validations.regex.enabled"
                   ng-model="field.validations.regex.enabled">
			<?php
			esc_html_e( 'Regular Expression', 'totalcontest' ); ?>
        </label>
    </div>
    <div class="totalcontest-settings-item-advanced" ng-class="{active: field.validations.regex.enabled}">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-validations-regex-against">
				<?php
				esc_html_e( 'Expression', 'totalcontest' ); ?>
                <span class="totalcontest-feature-details"
                      tooltip="<?php
				      esc_html_e( 'Run user input against a regular expression.', 'totalcontest' ); ?>">?</span>
            </label>
            <input id="field-{{field.uid}}-validations-regex-pattern" type="text"
                   class="totalcontest-settings-field-input widefat"
                   ng-model="field.validations.regex.pattern">
            <p class="totalcontest-feature-tip">
				<?php
				esc_html_e( 'Must be a valid regular expression.', 'totalcontest' ); ?>
            </p>
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-validations-regex-error-message">
				<?php
				esc_html_e( 'Error message', 'totalcontest' ); ?>
                <span class="totalcontest-feature-details"
                      tooltip="<?php
				      esc_html_e( 'This message will be shown if the validation failed.',
					      'totalcontest' ); ?>">?</span>
            </label>
            <input id="field-{{field.uid}}-validations-regex-error-message" type="text"
                   ng-model="field.validations.regex.errorMessage"
                   class="totalcontest-settings-field-input widefat">
            <p class="totalcontest-feature-tip" ng-non-bindable>
				<?php
				esc_html_e( '{{label}} for a field label.', 'totalcontest' ); ?>
            </p>
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php
				esc_html_e( 'Comparison', 'totalcontest' ); ?>
            </label> <label>
                <input type="radio" name="" ng-model="field.validations.regex.type"
                       ng-checked="field.validations.regex.type == 'match'"
                       value="match">
				<?php
				esc_html_e( 'Must match', 'totalcontest' ); ?>
            </label> &nbsp; <label>
                <input type="radio" name="" ng-model="field.validations.regex.type"
                       ng-checked="field.validations.regex.type == 'notmatch'"
                       value="notmatch">
				<?php
				esc_html_e( 'Must not match', 'totalcontest' ); ?>
            </label>
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php
				esc_html_e( 'Modifiers', 'totalcontest' ); ?>
            </label> <label>
                <input type="checkbox" name="" ng-model="field.validations.regex.modifiers['i']" value="i">
				<?php
				esc_html_e( 'Case Insensitive', 'totalcontest' ); ?>
            </label> &nbsp; <label>
                <input type="checkbox" name="" ng-model="field.validations.regex.modifiers['m']" value="m">
				<?php
				esc_html_e( 'Multiline', 'totalcontest' ); ?>
            </label> &nbsp; <label>
                <input type="checkbox" name="" ng-model="field.validations.regex.modifiers['u']" value="u">
				<?php
				esc_html_e( 'Unicode Support', 'totalcontest' ); ?>
            </label>
        </div>
    </div>
</script>
<!-- VALIDATION: SIZE -->
<script type="text/ng-template" id="field-validations-size-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label>
                <input type="checkbox" name="" ng-checked="field.validations.size.enabled"
                       ng-model="field.validations.size.enabled"> <?php
				esc_html_e( 'Size', 'totalcontest' ); ?>
            </label>
        </div>
    </div>
    <div class="totalcontest-settings-item-advanced" ng-class="{active: field.validations.size.enabled}">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label"
                   for="field-{{field.uid}}-validations-size-min"> <?php
				esc_html_e( 'Minimum length',
					'totalcontest' ); ?></label>
            <input id="field-{{field.uid}}-validations-size-min" type="number" min="0"
                   ng-model="field.validations.size.min" class="totalcontest-settings-field-input widefat">
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label"
                   for="field-{{field.uid}}-validations-size-max"> <?php
				esc_html_e( 'Maximum length',
					'totalcontest' ); ?></label>
            <input id="field-{{field.uid}}-validations-size-max" type="number" min="0"
                   ng-model="field.validations.size.max" class="totalcontest-settings-field-input widefat">
        </div>
    </div>
</script>
<!-- VALIDATION: FILE SIZE -->
<script type="text/ng-template" id="field-validations-file-size-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label>
                <input type="checkbox" name="" ng-model="field.validations.size.enabled"
                       ng-checked="field.validations.size.enabled">
				<?php
				esc_html_e( 'File size', 'totalcontest' ); ?>
            </label>
        </div>
    </div>
    <div class="totalcontest-settings-item-advanced" ng-class="{active: field.validations.size.enabled}">
        <div class="totalcontest-settings-item totalcontest-settings-item-inline">
            <div class="totalcontest-settings-field">
                <label class="totalcontest-settings-field-label">
					<?php
					esc_html_e( 'Minimum file size (kB)', 'totalcontest' ); ?>
                </label>
                <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat"
                       ng-model="field.validations.size.min">
            </div>
            <div class="totalcontest-settings-field">
                <label class="totalcontest-settings-field-label">
					<?php
					esc_html_e( 'Maximum file size (kB)', 'totalcontest' ); ?>
                </label>
                <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat"
                       ng-model="field.validations.size.max">
            </div>
        </div>
    </div>
</script>
<!-- HTML: FIELD CLASS -->
<script type="text/ng-template" id="field-html-css-class-template">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-class">
			<?php
			esc_html_e( 'Field CSS classes', 'totalcontest' ); ?>
        </label>
        <input id="field-{{field.uid}}-class" class="widefat" type="text"
               placeholder="<?php
		       esc_html_e( 'Field classes', 'totalcontest' ); ?>" ng-model="field.attributes.class">
    </div>
</script>
<!-- HTML: FIELD TEMPLATE -->
<script type="text/ng-template" id="field-html-template-template">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label" for="field-{{field.uid}}-template">
			<?php
			esc_html_e( 'Template', 'totalcontest' ); ?>
        </label>
        <input id="field-{{field.uid}}-template" class="widefat" type="text"
               placeholder="<?php
		       esc_html_e( 'Field template', 'totalcontest' ); ?>" ng-model="field.template">
        <p></p>
        <div class="button-group">
            <button type="button" class="button button-small"
                    ng-click="field.template = '<?php
			        echo esc_attr( '<div class="totalcontest-form-field totalcontest-column-full">{{label}}{{field}}<div class="totalcontest-form-field-errors">{{errors}}</div></div>' ); ?>'">
				<?php
				esc_html_e( 'full column', 'totalcontest' ); ?>
            </button>
            <button type="button" class="button button-small"
                    ng-click="field.template = '<?php
			        echo esc_attr( '<div class="totalcontest-form-field totalcontest-column-half">{{label}}{{field}}<div class="totalcontest-form-field-errors">{{errors}}</div></div>' ); ?>'">
				<?php
				esc_html_e( '1/2 column', 'totalcontest' ); ?>
            </button>
            <button type="button" class="button button-small"
                    ng-click="field.template = '<?php
			        echo esc_attr( '<div class="totalcontest-form-field totalcontest-column-third">{{label}}{{field}}<div class="totalcontest-form-field-errors">{{errors}}</div></div>' ); ?>'">
				<?php
				esc_html_e( '1/3 column', 'totalcontest' ); ?>
            </button>
        </div>
        <p class="totalcontest-feature-tip" ng-non-bindable>
			<?php
			esc_html_e( '{{label}} for field label.', 'totalcontest' ); ?>
        </p>
        <p class="totalcontest-feature-tip" ng-non-bindable>
			<?php
			esc_html_e( '{{errors}} for field errors.', 'totalcontest' ); ?>
        </p>
        <p class="totalcontest-feature-tip" ng-non-bindable>
			<?php
			esc_html_e( '{{field}} for field input.', 'totalcontest' ); ?>
        </p>
    </div>
</script>
