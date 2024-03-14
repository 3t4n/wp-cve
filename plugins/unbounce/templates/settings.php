<?php
if (isset($_GET['settings-updated']) && count(get_settings_errors(UBConfig::UB_ADMIN_PAGE_SETTINGS)) === 0) {
    // add settings saved message with the class of "updated"
    add_settings_error(UBConfig::UB_ADMIN_PAGE_SETTINGS, 'unbounce-pages-settings-updated', 'Settings Saved.', 'updated');
}
?>

<div class="wrap">
    <?php echo UBTemplate::render('title'); ?>
    <?php settings_errors(UBConfig::UB_ADMIN_PAGE_SETTINGS); ?>

    <form action="options.php" method="post">
        <?php
        settings_fields(UBConfig::UB_ADMIN_PAGE_SETTINGS);
        do_settings_sections(UBConfig::UB_ADMIN_PAGE_SETTINGS);
        ?>

        <p class="submit">
            <?php echo get_submit_button('Save Changes', 'button-primary', 'submit', false); ?>
            <button type="button" class="button" onclick="resetInputs()">Reset Defaults</button>
        </p>
    </form>
    <script>
        function resetInputs() {
            const elements = document.getElementsByClassName('ub-settings-input');
            for (const element of elements) {
                if (element.dataset['default']) {
                    element.value = element.dataset['default'];
                }
            }
        }
    </script>
</div>
