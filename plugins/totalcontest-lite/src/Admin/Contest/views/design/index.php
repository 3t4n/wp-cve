<customizer></customizer>
<!-- Template -->
<script type="text/ng-template" id="customizer-component-template">
    <div class="totalcontest-design" ng-attr-device="{{$ctrl.getDevice()}}">
		<?php include __DIR__ . '/sidebar.php'; ?>
		<?php include __DIR__ . '/preview.php'; ?>
    </div>
</script>
<script type="text/ng-template" id="customizer-tabs-component-template">
    <div class="totalcontest-design-tabs" ng-class="{'active': $ctrl.$customizer.hasActiveTabAfter($ctrl.getTarget())}" ng-transclude target="{{$ctrl.getTarget()}}"></div>
</script>
<script type="text/ng-template" id="customizer-tab-component-template">
    <div class="totalcontest-design-tabs-item"
         ng-click="$ctrl.$customizer.setActiveTab($ctrl.getTarget(), $event.currentTarget.firstChild.textContent.trim() || $event.currentTarget.children[0].textContent.trim())"
         ng-transclude target="{{$ctrl.getTarget()}}"></div>
</script>
<script type="text/ng-template" id="customizer-tab-content-component-template">
    <div class="totalcontest-design-tabs-content" ng-class="{'active': $ctrl.$customizer.hasActiveTab($ctrl.getTarget())}" ng-transclude target="{{$ctrl.getTarget()}}"></div>
</script>
<script type="text/ng-template" id="customizer-preview-body-template">
    <div id="totalcontest" class="totalcontest-wrapper <?php echo is_rtl() ? 'is-rtl' : 'is-ltr'; ?>" totalcontest-uid="demo" ng-controller="PreviewCtrl as $preview">
        <div class="totalcontest-container" ng-include="$ctrl.getCurrentTemplatePreviewContentId()"></div>
    </div>
</script>
<script type="text/ng-template" id="customizer-preview-head-template">
    <meta ng-init="design = $root.settings.design">
    <meta ng-init="uid = 'demo'">
    <style type="text/css" ng-include="$ctrl.getCurrentTemplatePreviewCssId()"></style>
    <style type="text/css" ng-bind="$ctrl.settings.css"></style>
    <style>
        * {
            vertical-align: baseline;
            box-sizing: border-box;
        }

        html, body {
            min-height: 100%;
        }

        body {
            height: min-content;
            margin: 0;
            padding: 1em;
            font-family: sans-serif;
        }
    </style>
</script>
<script type="text/ng-template" id="design-typography-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Font family', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.settings.fontFamily">
        </div>
    </div>
    <div class="totalcontest-settings-item totalcontest-settings-item-inline">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Font size', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.settings.fontSize">
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Line height', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.settings.lineHeight">
        </div>
    </div>
    <div class="totalcontest-settings-item totalcontest-settings-item-inline">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Align', 'totalcontest' ); ?>
            </label>
            <select class="totalcontest-settings-field-input widefat" ng-model="$ctrl.settings.align">
                <option value="inherit" ng-selected="$ctrl.settings.align == 'inherit'">
					<?php  esc_html_e( 'Inherit', 'totalcontest' ); ?>
                </option>
                <option value="right" ng-selected="$ctrl.settings.align == 'right'">
					<?php  esc_html_e( 'Right', 'totalcontest' ); ?>
                </option>
                <option value="center" ng-selected="$ctrl.settings.align == 'center'">
					<?php  esc_html_e( 'Center', 'totalcontest' ); ?>
                </option>
                <option value="left" ng-selected="$ctrl.settings.align == 'left'">
					<?php  esc_html_e( 'Left', 'totalcontest' ); ?>
                </option>
            </select>
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Transform', 'totalcontest' ); ?>
            </label>
            <select class="totalcontest-settings-field-input widefat"
                    ng-model="$ctrl.settings.transform">
                <option value="none" ng-selected="$ctrl.settings.transform == 'inherit'">
					<?php  esc_html_e( 'Inherit', 'totalcontest' ); ?>
                </option>
                <option value="none" ng-selected="$ctrl.settings.transform == 'none'">
					<?php  esc_html_e( 'Normal', 'totalcontest' ); ?>
                </option>
                <option value="uppercase" ng-selected="$ctrl.settings.transform == 'uppercase'">
					<?php  esc_html_e( 'UPPERCASE', 'totalcontest' ); ?>
                </option>
                <option value="lowercase" ng-selected="$ctrl.settings.transform == 'lowercase'">
					<?php  esc_html_e( 'lowercase', 'totalcontest' ); ?>
                </option>
                <option value="capitalize" ng-selected="$ctrl.settings.transform == 'capitalize'">
					<?php  esc_html_e( 'Capitalize', 'totalcontest' ); ?>
                </option>
            </select>
        </div>
    </div>
</script>
<script type="text/ng-template" id="design-padding-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Top', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat"
                   ng-model="$ctrl.settings.top">
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Right', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat"
                   ng-model="$ctrl.settings.right">
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Bottom', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat"
                   ng-model="$ctrl.settings.bottom">
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Left', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat"
                   ng-model="$ctrl.settings.left">
        </div>
    </div>

</script>
<script type="text/ng-template" id="customizer-control-component-template">
    <ng-include src="$ctrl.getTemplate()"></ng-include>
</script>
<script type="text/ng-template" id="customizer-control-text-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label" ng-if="$ctrl.label">{{ $ctrl.label }}</label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel">
        </div>
    </div>
</script>
<script type="text/ng-template" id="customizer-control-checkbox-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label>
                <input type="checkbox" name="" ng-model="$ctrl.ngModel" ng-checked="$ctrl.ngModel">
                {{ $ctrl.label }}
                <span class="totalcontest-feature-details" ng-if="$ctrl.help" tooltip="{{ $ctrl.help }}">?</span>
            </label>
        </div>
    </div>
</script>
<script type="text/ng-template" id="customizer-control-radioboxes-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">{{ $ctrl.label }}</label>

            <p>
                <span ng-repeat="(optionValue, optionLabel) in $ctrl.options">
                <label>
                    <input type="radio" name="" ng-value="optionValue" ng-model="$ctrl.ngModel">
                    {{optionLabel}}
                </label>
                &nbsp;&nbsp;
                </span>
            </p>

        </div>
    </div>
</script>
<script type="text/ng-template" id="customizer-control-color-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label" ng-if="$ctrl.label">{{ $ctrl.label }}</label>
            <input type="text" color-picker class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel">
        </div>
    </div>
</script>
<script type="text/ng-template" id="customizer-control-number-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label" ng-if="$ctrl.label">{{ $ctrl.label }}</label>
            <input type="number"
                   class="totalcontest-settings-field-input widefat"
                   ng-attr-min="{{$ctrl.options.min}}"
                   ng-attr-max="{{$ctrl.options.max}}"
                   ng-attr-step="{{$ctrl.options.step}}"
                   ng-model="$ctrl.ngModel">
        </div>
    </div>
</script>
<script type="text/ng-template" id="customizer-control-typography-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Font family', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.fontFamily">
        </div>
    </div>
    <div class="totalcontest-settings-item totalcontest-settings-item-inline">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Font size', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.fontSize">
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Line height', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.lineHeight">
        </div>
    </div>
    <div class="totalcontest-settings-item totalcontest-settings-item-inline">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Align', 'totalcontest' ); ?>
            </label>
            <select class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.align">
                <option value="inherit" ng-selected="$ctrl.ngModel.align == 'inherit'">
					<?php  esc_html_e( 'Inherit', 'totalcontest' ); ?>
                </option>
                <option value="right" ng-selected="$ctrl.ngModel.align == 'right'">
					<?php  esc_html_e( 'Right', 'totalcontest' ); ?>
                </option>
                <option value="center" ng-selected="$ctrl.ngModel.align == 'center'">
					<?php  esc_html_e( 'Center', 'totalcontest' ); ?>
                </option>
                <option value="left" ng-selected="$ctrl.ngModel.align == 'left'">
					<?php  esc_html_e( 'Left', 'totalcontest' ); ?>
                </option>
            </select>
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Transform', 'totalcontest' ); ?>
            </label>
            <select class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.transform">
                <option value="inherit" ng-selected="$ctrl.ngModel.transform == 'inherit'">
					<?php  esc_html_e( 'Inherit', 'totalcontest' ); ?>
                </option>
                <option value="none" ng-selected="$ctrl.ngModel.transform == 'none'">
					<?php  esc_html_e( 'Normal', 'totalcontest' ); ?>
                </option>
                <option value="uppercase" ng-selected="$ctrl.ngModel.transform == 'uppercase'">
					<?php  esc_html_e( 'UPPERCASE', 'totalcontest' ); ?>
                </option>
                <option value="lowercase" ng-selected="$ctrl.ngModel.transform == 'lowercase'">
					<?php  esc_html_e( 'lowercase', 'totalcontest' ); ?>
                </option>
                <option value="capitalize" ng-selected="$ctrl.ngModel.transform == 'capitalize'">
					<?php  esc_html_e( 'Capitalize', 'totalcontest' ); ?>
                </option>
            </select>
        </div>
    </div>
</script>
<script type="text/ng-template" id="customizer-control-border-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Width', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.width">
        </div>
    </div>
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Style', 'totalcontest' ); ?>
            </label>
            <select class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.style">
                <option value="inherit" ng-selected="$ctrl.ngModel.align == 'inherit'">
					<?php  esc_html_e( 'Inherit', 'totalcontest' ); ?>
                </option>
                <option value="none" ng-selected="$ctrl.ngModel.align == 'none'">
					<?php  esc_html_e( 'None', 'totalcontest' ); ?>
                </option>
                <option value="solid" ng-selected="$ctrl.ngModel.align == 'solid'">
					<?php  esc_html_e( 'Solid', 'totalcontest' ); ?>
                </option>
                <option value="double" ng-selected="$ctrl.ngModel.align == 'double'">
					<?php  esc_html_e( 'Double', 'totalcontest' ); ?>
                </option>
                <option value="dashed" ng-selected="$ctrl.ngModel.align == 'dashed'">
					<?php  esc_html_e( 'Dashed', 'totalcontest' ); ?>
                </option>
                <option value="dotted" ng-selected="$ctrl.ngModel.align == 'dotted'">
					<?php  esc_html_e( 'Dotted', 'totalcontest' ); ?>
                </option>
                <option value="groove" ng-selected="$ctrl.ngModel.align == 'groove'">
					<?php  esc_html_e( 'Groove', 'totalcontest' ); ?>
                </option>
                <option value="hidden" ng-selected="$ctrl.ngModel.align == 'hidden'">
					<?php  esc_html_e( 'Hidden', 'totalcontest' ); ?>
                </option>
                <option value="ridge" ng-selected="$ctrl.ngModel.align == 'ridge'">
					<?php  esc_html_e( 'Ridge', 'totalcontest' ); ?>
                </option>
            </select>
        </div>
    </div>
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Radius', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.radius">
        </div>
    </div>
</script>
<script type="text/ng-template" id="customizer-control-padding-template">
    <div class="totalcontest-settings-item">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Top', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.top">
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Right', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.right">
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Bottom', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.bottom">
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php  esc_html_e( 'Left', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" ng-model="$ctrl.ngModel.left">
        </div>
    </div>
</script>
