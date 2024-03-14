<?php ?>
<div class="wrap">
<h1>Block Editor for ManyChat</h1>
Please enter your Facebook page ID.
<form method="post" action="options.php">
<?php
    settings_fields('wpmc_options_group'); 
    do_settings_sections('wp-manychat');
    submit_button();
?>
<small>* Page ID is numeric and can be found on the "About" page of your Facebook page profile.
<a href="https://www.simb.co/blog/manychat-wordpress-plugin/?utm_source=wp-admin&utm_medium=link&utm_campaign=wp-manychat" target="_blank" class="">Setup Instructions and Help</a>


</form>
</div>
