<?php

add_action('admin_menu', function () {
    add_options_page('Include Me', 'Include Me', 'administrator', 'include-me/admin/options.php');
});