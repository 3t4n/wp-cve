<?php

if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'save')) {
    if (isset($_POST['save'])) {
        if (isset($_POST['options'])) {
            $options = stripslashes_deep($_POST['options']);
            update_option('includeme', $options);
        } else {
            update_option('includeme', []);
        }
    }

    if (isset($_POST['find'])) {
        global $wpdb;
        $posts = $wpdb->get_results("select id, post_title from " . $wpdb->prefix . "posts where post_content like '%[includeme%' and post_type in ('post', 'page')");
    }
} else {
    $options = get_option('includeme', []);
}
?>
<style>
<?php include __DIR__ . '/admin.css' ?>
</style>

<div class="wrap">

    <h2>Include Me</h2>

    <div class="notice notice-info">
        <p style="font-weight: bold;">
            Yes, there is a good reason to
            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5PHGDGNHAYLJ8" target="_blank"><img style="vertical-align: bottom" src="http://www.satollo.net/images/donate.png"></a>
            and even <b>2$</b> help. <a href="https://www.satollo.net/donations" target="_blank">Read more</a>.
        </p>
    </div>

    <h3><?php _e('Configuration', 'include-me') ?></h3>



    <form action="" method="post">
        <?php wp_nonce_field('save') ?>
        <table class="form-table">
            <tr>
                <th><?php _e('Execute shortcodes', 'include-me') ?></th>
                <td>
                    <input type="checkbox" name="options[shortcode]" value="1" <?php echo isset($options['shortcode']) ? 'checked' : ''; ?>>
                    <p class="description">
                        <?php _e('When checked short codes (like [gallery]) contained in included files will be executed as if they where inside the post or page body content.', 'include-me') ?>
                    </p>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input class="button button-primary" type="submit" name="save" value="<?php _e('Save') ?>"/>
        </p>

        <h3><?php _e('How to use', 'include-me') ?></h3>
        <p>
            Files to be included with the shortcode <code>[includeme file="..."]</code> should be placed into the <code>wp-content/include-me</code> folder.
        </p>
        <p>
            The <code>file</code> attribute should be a relative path relative to the <code>wp-content/include-me</code>.<br>
            For example <code>[includeme file="my-list.php"]</code> or <code>[includeme file="subfolder/my-list.php"]</code>.<br>
            Non PHP files can be included (eg. text/HTML files).
        </p>
        <p style="font-weight: bold;">
            <a href="https://www.satollo.net/plugins/include-me" target="_blank">See the documentation for more</a>.
        </p>
        <p>
            To change the including folder, specify it definning the constant <code>INCLUDE_ME_DIR</code> in your <code>wp-config.php</code> file.<br>
            Setting the constant to "*" you allow the inclusion of files from everywhere (using an absolute path).
        </p>
        <p>
            The <code>INCLUDE_ME_DIR</code> constant is actually set to <code><?php echo esc_html(INCLUDE_ME_DIR)?></code></p>
        </p>

        <h3>Where is it used?</h3>

        <?php if (isset($posts)) { ?>
            <?php if (empty($posts)) { ?>
                <p>No posts or pages with the <code>[includeme]</code> shortcode.</p>
            <?php } else { ?>
                <ul>
                    <?php foreach ($posts as $post) { ?>
                        <li><a href="<?php echo get_permalink($post->id) ?>" target="_blank"><?php echo esc_html($post->post_title) ?></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        <?php } ?>

        <p class="submit">
            <input class="button button-primary" type="submit" name="find" value="<?php _e('Find') ?>"/>
        </p>
    </form>
</div>
