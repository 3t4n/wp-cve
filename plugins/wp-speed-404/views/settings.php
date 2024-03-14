<?php if(!defined('ABSPATH')) exit; ?>
<div class="wrap">
    <h1>
        <img src="<?php echo WPSpeed404::asset_url('icon.png'); ?>" style="height:20px">
        <?php esc_html_e(WPSpeed404::$title, 'wp-speed-404'); ?>
    </h1>

    <h3>
        <?php
            printf(
                __(
                    'Join Our <a href="%s">Facebook Group</a>',
                    'wp-speed-404'
                ), 'https://www.facebook.com/groups/imincomelab/'
            );
        ?>
    </h3>

    <form method="post" novalidate="novalidate">
        <input type="hidden" name="option_page" value="general">
        <input type="hidden" name="action" value="update">

        <?php wp_nonce_field('update'); ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Mode', 'wp-speed-404'); ?>
                    </th>
                    <td>
                        <?php foreach(WPSpeed404_Engine::$modes as $value => $description): ?>
                            <label>
                                <input
                                    type="radio"
                                    name="mode"
                                    value="<?php echo esc_attr($value); ?>"
                                    <?php checked($settings->mode, $value); ?>
                                >
                                <?php esc_html_e($description, 'wp-speed-404'); ?>
                            </label><br />
                        <?php endforeach; ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php esc_html_e('Include wp-includes folder', 'wp-speed-404'); ?>
                    </th>
                    <td>
                        <label>
                            <input
                                type="checkbox"
                                class="checkbox"
                                name="include_wp_includes"
                                <?php checked($settings->include_wp_includes); ?>
                            ><?php esc_html_e('Include wp-includes folder in "fixing"', 'wp-speed-404'); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php esc_html_e('Include wp-admin folder', 'wp-speed-404'); ?>
                    </th>
                    <td>
                        <label>
                            <input
                                type="checkbox"
                                class="checkbox"
                                name="include_wp_admin"
                                <?php checked($settings->include_wp_admin); ?>
                            ><?php esc_html_e('Include wp-admin folder in "fixing"', 'wp-speed-404'); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php esc_html_e('Notification Email Address', 'wp-speed-404'); ?>
                    </th>
                    <td>
                        <label>
                            <input
                                type="text"
                                class="regular-text"
                                name="notify_email"
                                value="<?php echo esc_attr($settings->notify_email); ?>"
                            ><?php esc_html_e('Email address notifications will be sent to', 'wp-speed-404'); ?>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input
                type="submit"
                name="save"
                id="submit"
                class="button button-primary"
                value="<?php esc_attr_e('Save Changes', 'wp-speed-404'); ?>"
            >
            <?php if($settings->mode == 'log'): ?>
                <input
                    type="submit"
                    name="clear"
                    id="submit"
                    class="button"
                    value="<?php esc_attr_e('Clear Log', 'wp-speed-404'); ?>"
                >
            <?php endif; ?>
        </p>
    </form>
</div>

<?php if($settings->mode == 'log'): ?>
    <h3>Log</h3>
    <textarea style="width:80%;height: 150px;font-family: courier"
    ><?php echo WPSpeed404_Log::instance()->format(true) ?></textarea>
<?php endif; ?>
