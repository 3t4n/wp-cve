<customizer-tabs>
    <customizer-tab target="primary"><?php  esc_html_e( 'Primary', 'totalcontest' ); ?></customizer-tab>
    <customizer-tab target="secondary"><?php  esc_html_e( 'Secondary', 'totalcontest' ); ?></customizer-tab>
    <customizer-tab target="accent"><?php  esc_html_e( 'Accent', 'totalcontest' ); ?></customizer-tab>
    <customizer-tab target="gray"><?php  esc_html_e( 'Gray', 'totalcontest' ); ?></customizer-tab>
    <customizer-tab target="dark"><?php  esc_html_e( 'Dark', 'totalcontest' ); ?></customizer-tab>
</customizer-tabs>

<customizer-tab-content name="primary">
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Primary', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.primary"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Primary (Contrast)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.primaryContrast"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Primary (Light)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.primaryLight"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Primary (Lighter)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.primaryLighter"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Primary (Dark)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.primaryDark"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Primary (Darker)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.primaryDarker"></customizer-control>
</customizer-tab-content>

<customizer-tab-content name="secondary">
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Secondary', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.secondary"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Secondary (Contrast)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.secondaryContrast"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Secondary (Light)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.secondaryLight"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Secondary (Lighter)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.secondaryLighter"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Secondary (Dark)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.secondaryDark"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Secondary (Darker)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.secondaryDarker"></customizer-control>
</customizer-tab-content>

<customizer-tab-content name="accent">
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Accent', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.accent"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Accent (Contrast)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.accentContrast"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Accent (Light)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.accentLight"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Accent (Lighter)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.accentLighter"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Accent (Dark)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.accentDark"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Accent (Darker)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.accentDarker"></customizer-control>
</customizer-tab-content>

<customizer-tab-content name="gray">
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Gray', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.gray"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Gray (Contrast)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.grayContrast"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Gray (Light)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.grayLight"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Gray (Lighter)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.grayLighter"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Gray (Dark)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.grayDark"></customizer-control>
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Gray (Darker)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.grayDarker"></customizer-control>
</customizer-tab-content>

<customizer-tab-content name="dark">
    <customizer-control
            type="color"
            label="<?php esc_attr_e( 'Dark (Body Text)', 'totalcontest' ); ?>"
            ng-model="$ctrl.settings.colors.dark"></customizer-control>
</customizer-tab-content>
