<div class="wrap" xmlns="http://www.w3.org/1999/html">
    <h2><?php _e('URL Params Options', 'urlparams'); ?></h2>

    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <?php settings_fields('urlparams'); ?>

        <p><?php _e('Congratulations on installing the URL Params plugin! <a href="https://wordpress.org/plugins/url-params/" target="_blank">Read the Docs</a>.', 'urlparams'); ?></p>

        <h2><?php _e('Advanced Settings', 'urlparams'); ?></h2>

        <p><?php _e('By default, if you use URL Params to create HTML tags, your tags and attributes will be <a href="https://developer.wordpress.org/reference/functions/wp_kses_allowed_html/" target="_blank">sanitized</a>.', 'urlparams'); ?></p>

        <p><?php _e('This is to prevent less privileged users from creating pages that execute javascript code for more privileged users like you.', 'urlparams'); ?></p>

        <label for="defaulttags"><?php _e('Supported Tags and Attributes:', 'urlparams'); ?></label><br/>

        <textarea id="defaulttags" rows="10" cols="80" style="max-width: 100%; overflow-wrap: normal; overflow-x: scroll;" readonly><?php
        $tags = wp_kses_allowed_html('post');
        foreach($tags as $tagName => $attributes) {
            print $tagName.': '.implode(', ', array_keys($attributes))."\n";
        }
        ?></textarea>

        <p><?php _e('If for some reason you need more advanced tags or attributes, you can add them below.', 'urlparams'); ?></p>

        <p><?php _e('Enter one HTML tag type per line, followed by a colon, followed by a comma delimited list of attributes.', 'urlparams'); ?></p>

        <label for="urlparams_customtags"><?php _e('Custom Tags and Attributes:', 'urlparams'); ?></label><br/>

        <textarea id="urlparams_customtags" name="urlparams_customtags" rows="10" cols="80" style="max-width: 100%;" placeholder="<?php _e('exampletag: exampleattribute1, exampleattribute2, exampleattribute3', 'urlparams'); ?>"><?php echo(get_option('urlparams_customtags')) ?></textarea>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>

    </form>
</div>