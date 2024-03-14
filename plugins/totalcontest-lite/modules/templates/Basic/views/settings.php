<customizer-tabs>
    <customizer-tab target="container">Container</customizer-tab>
    <customizer-tab target="toolbar">Toolbar</customizer-tab>
    <customizer-tab target="menu">Menu</customizer-tab>
    <customizer-tab target="button">Button</customizer-tab>
    <customizer-tab target="message">Message</customizer-tab>
    <customizer-tab target="contentPage">Content Page</customizer-tab>
    <customizer-tab target="form">Form</customizer-tab>
    <customizer-tab target="submission">Submission</customizer-tab>
</customizer-tabs>

<customizer-tab-content name="container">

    <customizer-tabs>
        <customizer-tab target="colors">Colors</customizer-tab>
        <customizer-tab target="padding">Padding</customizer-tab>
    </customizer-tabs>

    <customizer-tab-content name="colors">
        <customizer-control type="color" label="{{ 'Border' | i18n }}" ng-model="$ctrl.settings.custom.container.colors.border"></customizer-control>
        <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.container.colors.background"></customizer-control>
        <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.container.colors.color"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="padding">
        <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.container.padding"></customizer-control>
    </customizer-tab-content>

</customizer-tab-content>
<customizer-tab-content name="toolbar">

    <customizer-tabs>
        <customizer-tab target="colors">Colors</customizer-tab>
        <customizer-tab target="border">Border</customizer-tab>
        <customizer-tab target="padding">Padding</customizer-tab>
        <customizer-tab target="shadows">Shadows</customizer-tab>
    </customizer-tabs>

    <customizer-tab-content name="colors">
        <customizer-control type="color" label="{{ 'Border' | i18n }}" ng-model="$ctrl.settings.custom.toolbar.colors.border"></customizer-control>
        <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.toolbar.colors.background"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="padding">
        <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.toolbar.padding"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="border">
        <customizer-control type="border" label="{{ 'Border' | i18n }}" ng-model="$ctrl.settings.custom.toolbar.border"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="shadows">
        <customizer-control type="text" label="{{ 'Box shadow' | i18n }}" ng-model="$ctrl.settings.custom.toolbar.shadows.box"></customizer-control>
    </customizer-tab-content>

</customizer-tab-content>

<customizer-tab-content name="menu">

    <customizer-tabs>
        <customizer-tab target="colors">Colors</customizer-tab>
        <customizer-tab target="padding">Padding</customizer-tab>
        <customizer-tab target="text">Text</customizer-tab>
    </customizer-tabs>

    <customizer-tab-content name="colors">
        <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.menu.colors.color"></customizer-control>
        <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.menu.colors.background"></customizer-control>
        <customizer-control type="color" label="{{ 'Color (Hover)' | i18n }}" ng-model="$ctrl.settings.custom.menu.colors.colorHover"></customizer-control>
        <customizer-control type="color" label="{{ 'Background (Hover)' | i18n }}" ng-model="$ctrl.settings.custom.menu.colors.backgroundHover"></customizer-control>
        <customizer-control type="color" label="{{ 'Color (Active)' | i18n }}" ng-model="$ctrl.settings.custom.menu.colors.colorActive"></customizer-control>
        <customizer-control type="color" label="{{ 'Background (Active)' | i18n }}" ng-model="$ctrl.settings.custom.menu.colors.backgroundActive"></customizer-control>
        <customizer-control type="color" label="{{ 'Border (Active)' | i18n }}" ng-model="$ctrl.settings.custom.menu.colors.borderActive"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="padding">
        <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.menu.padding"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="text">
        <customizer-control type="typography" label="{{ 'Text' | i18n }}" ng-model="$ctrl.settings.custom.menu.text"></customizer-control>
    </customizer-tab-content>

</customizer-tab-content>

<customizer-tab-content name="message">

    <customizer-tabs>
        <customizer-tab target="colors">Colors</customizer-tab>
        <customizer-tab target="padding">Padding</customizer-tab>
        <customizer-tab target="text">Text</customizer-tab>
    </customizer-tabs>

    <customizer-tab-content name="colors">
        <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.message.colors.background"></customizer-control>
        <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.message.colors.color"></customizer-control>
        <customizer-control type="color" label="{{ 'Background (error)' | i18n }}" ng-model="$ctrl.settings.custom.message.colors.backgroundError"></customizer-control>
        <customizer-control type="color" label="{{ 'Color (error)' | i18n }}" ng-model="$ctrl.settings.custom.message.colors.colorError"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="padding">
        <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.message.padding"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="text">
        <customizer-control type="typography" label="{{ 'Text' | i18n }}" ng-model="$ctrl.settings.custom.message.text"></customizer-control>
    </customizer-tab-content>

</customizer-tab-content>

<customizer-tab-content name="button">

    <customizer-tabs>
        <customizer-tab target="colors">Colors</customizer-tab>
        <customizer-tab target="padding">Padding</customizer-tab>
        <customizer-tab target="text">Text</customizer-tab>
    </customizer-tabs>

    <customizer-tab-content name="colors">
        <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.button.colors.background"></customizer-control>
        <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.button.colors.color"></customizer-control>
        <customizer-control type="color" label="{{ 'Border' | i18n }}" ng-model="$ctrl.settings.custom.button.colors.border"></customizer-control>
        <customizer-control type="color" label="{{ 'Background (hover)' | i18n }}" ng-model="$ctrl.settings.custom.button.colors.backgroundHover"></customizer-control>
        <customizer-control type="color" label="{{ 'Color (hover)' | i18n }}" ng-model="$ctrl.settings.custom.button.colors.colorHover"></customizer-control>
        <customizer-control type="color" label="{{ 'Border (hover)' | i18n }}" ng-model="$ctrl.settings.custom.button.colors.borderHover"></customizer-control>

        <customizer-control type="color" label="{{ 'Primary background' | i18n }}" ng-model="$ctrl.settings.custom.button.colors.backgroundPrimary"></customizer-control>
        <customizer-control type="color" label="{{ 'Primary border' | i18n }}" ng-model="$ctrl.settings.custom.button.colors.borderPrimary"></customizer-control>
        <customizer-control type="color" label="{{ 'Primary color' | i18n }}" ng-model="$ctrl.settings.custom.button.colors.colorPrimary"></customizer-control>
        <customizer-control type="color" label="{{ 'Primary background (hover)' | i18n }}"
                            ng-model="$ctrl.settings.custom.button.colors.backgroundPrimaryHover"></customizer-control>
        <customizer-control type="color" label="{{ 'Primary border (hover)' | i18n }}"
                            ng-model="$ctrl.settings.custom.button.colors.borderPrimaryHover"></customizer-control>
        <customizer-control type="color" label="{{ 'Primary color (hover)' | i18n }}"
                            ng-model="$ctrl.settings.custom.button.colors.colorPrimaryHover"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="padding">
        <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.container.padding"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="text">
        <customizer-control type="typography" label="{{ 'Text' | i18n }}" ng-model="$ctrl.settings.custom.button.text"></customizer-control>
    </customizer-tab-content>

</customizer-tab-content>

<customizer-tab-content name="contentPage">

    <customizer-tabs>
        <customizer-tab target="colors">Colors</customizer-tab>
        <customizer-tab target="padding">Padding</customizer-tab>
        <customizer-tab target="text">Text</customizer-tab>
    </customizer-tabs>

    <customizer-tab-content name="colors">
        <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.contentPage.colors.background"></customizer-control>
        <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.contentPage.colors.color"></customizer-control>
        <customizer-control type="color" label="{{ 'Border' | i18n }}" ng-model="$ctrl.settings.custom.contentPage.colors.border"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="padding">
        <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.contentPage.padding"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="text">
        <customizer-control type="typography" label="{{ 'Text' | i18n }}" ng-model="$ctrl.settings.custom.contentPage.text"></customizer-control>
    </customizer-tab-content>

</customizer-tab-content>

<customizer-tab-content name="form">

    <customizer-tabs>
        <customizer-tab target="colors">Colors</customizer-tab>
        <customizer-tab target="padding">Padding</customizer-tab>
        <customizer-tab target="text">Text</customizer-tab>
        <customizer-tab target="label">Label</customizer-tab>
        <customizer-tab target="input">Input</customizer-tab>
        <customizer-tab target="error">Error</customizer-tab>
    </customizer-tabs>

    <customizer-tab-content name="colors">
        <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.form.colors.background"></customizer-control>
        <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.form.colors.color"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="padding">
        <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.form.padding"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="text">
        <customizer-control type="typography" label="{{ 'Text' | i18n }}" ng-model="$ctrl.settings.custom.form.text"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="label">

        <customizer-tabs>
            <customizer-tab target="colors">Colors</customizer-tab>
            <customizer-tab target="padding">Padding</customizer-tab>
            <customizer-tab target="text">Text</customizer-tab>
        </customizer-tabs>

        <customizer-tab-content name="colors">
            <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.form.label.colors.color"></customizer-control>
        </customizer-tab-content>

        <customizer-tab-content name="padding">
            <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.form.label.padding"></customizer-control>
        </customizer-tab-content>

        <customizer-tab-content name="text">
            <customizer-control type="typography" label="{{ 'Text' | i18n }}" ng-model="$ctrl.settings.custom.form.label.text"></customizer-control>
        </customizer-tab-content>

    </customizer-tab-content>

    <customizer-tab-content name="input">

        <customizer-tabs>
            <customizer-tab target="colors">Colors</customizer-tab>
            <customizer-tab target="border">Border</customizer-tab>
            <customizer-tab target="padding">Padding</customizer-tab>
            <customizer-tab target="text">Text</customizer-tab>
        </customizer-tabs>

        <customizer-tab-content name="colors">
            <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.form.input.colors.background"></customizer-control>
            <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.form.input.colors.color"></customizer-control>
            <customizer-control type="color" label="{{ 'Border' | i18n }}" ng-model="$ctrl.settings.custom.form.input.colors.border"></customizer-control>
        </customizer-tab-content>

        <customizer-tab-content name="border">
            <customizer-control type="border" label="{{ 'Border' | i18n }}" ng-model="$ctrl.settings.custom.form.input.border"></customizer-control>
        </customizer-tab-content>

        <customizer-tab-content name="padding">
            <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.form.input.padding"></customizer-control>
        </customizer-tab-content>

        <customizer-tab-content name="text">
            <customizer-control type="typography" label="{{ 'Text' | i18n }}" ng-model="$ctrl.settings.custom.form.input.text"></customizer-control>
        </customizer-tab-content>

    </customizer-tab-content>

    <customizer-tab-content name="error">

        <customizer-tabs>
            <customizer-tab target="colors">Colors</customizer-tab>
            <customizer-tab target="padding">Padding</customizer-tab>
            <customizer-tab target="text">Text</customizer-tab>
        </customizer-tabs>

        <customizer-tab-content name="colors">
            <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.form.error.colors.background"></customizer-control>
            <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.form.error.colors.color"></customizer-control>
        </customizer-tab-content>

        <customizer-tab-content name="padding">
            <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.form.error.padding"></customizer-control>
        </customizer-tab-content>

        <customizer-tab-content name="text">
            <customizer-control type="typography" label="{{ 'Text' | i18n }}" ng-model="$ctrl.settings.custom.form.error.text"></customizer-control>
        </customizer-tab-content>

    </customizer-tab-content>

</customizer-tab-content>

<customizer-tab-content name="submission">

    <customizer-tabs>
        <customizer-tab target="colors">Colors</customizer-tab>
        <customizer-tab target="border">Border</customizer-tab>
        <customizer-tab target="padding">Padding</customizer-tab>
    </customizer-tabs>

    <customizer-tab-content name="colors">
        <customizer-control type="color" label="{{ 'Background' | i18n }}" ng-model="$ctrl.settings.custom.submission.colors.background"></customizer-control>
        <customizer-control type="color" label="{{ 'Color' | i18n }}" ng-model="$ctrl.settings.custom.submission.colors.color"></customizer-control>
        <customizer-control type="color" label="{{ 'Border' | i18n }}" ng-model="$ctrl.settings.custom.submission.colors.border"></customizer-control>
        <customizer-control type="color" label="{{ 'Background (Hover)' | i18n }}" ng-model="$ctrl.settings.custom.submission.colors.backgroundHover"></customizer-control>
        <customizer-control type="color" label="{{ 'Color (Hover)' | i18n }}" ng-model="$ctrl.settings.custom.submission.colors.colorHover"></customizer-control>
        <customizer-control type="color" label="{{ 'Border (Hover)' | i18n }}" ng-model="$ctrl.settings.custom.submission.colors.borderHover"></customizer-control>
        <customizer-control type="color" label="{{ 'Background (Checked)' | i18n }}" ng-model="$ctrl.settings.custom.submission.colors.backgroundChecked"></customizer-control>
        <customizer-control type="color" label="{{ 'Color (Checked)' | i18n }}" ng-model="$ctrl.settings.custom.submission.colors.colorChecked"></customizer-control>
        <customizer-control type="color" label="{{ 'Border (Checked)' | i18n }}" ng-model="$ctrl.settings.custom.submission.colors.borderChecked"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="border">
        <customizer-control type="border" label="{{ 'Border' | i18n }}" ng-model="$ctrl.settings.custom.submission.border"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="padding">
        <customizer-control type="padding" label="{{ 'Padding' | i18n }}" ng-model="$ctrl.settings.custom.submission.padding"></customizer-control>
    </customizer-tab-content>

    <customizer-tab-content name="text">
        <customizer-control type="typography" label="{{ 'Text' | i18n }}" ng-model="$ctrl.settings.custom.submission.text"></customizer-control>
    </customizer-tab-content>

</customizer-tab-content>