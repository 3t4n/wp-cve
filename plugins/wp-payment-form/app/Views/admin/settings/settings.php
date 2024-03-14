<?php do_action('wppayform_before_global_settings_option_render'); ?>

<div class="wppayform_global_settings_option" id="wppayform_global_settings_option_app">
    <component
        :settings_key="settings_key"
        :is="component"
        :current_component="component"
        :app="App"
    ></component>
</div>

<?php do_action('wppayform_after_global_settings_option_render'); ?>