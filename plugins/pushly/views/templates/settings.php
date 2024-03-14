<?php
settings_errors( 'pushly_messages' );
?>
<div id="pushly_settings_form" class="wrap">
    <div>
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('pushly');
            do_settings_sections('pushly');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
</div>
