
<div class="wrap">
    <h2>Scrolling text widget settings</h2>

    <form method="post" action="options.php">
        <?php settings_fields( 'baw-settings-group' ); ?>
        <?php do_settings_sections( 'baw-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Scrolling Direction</th>
                <td>
                    <select name="tsw_direction">
                        <option value="up" <?php if(get_option('tsw_direction') =='up'){ echo 'selected'; } ?>>Up</option>
                        <option value="down" <?php if(get_option('tsw_direction') =='down'){ echo 'selected'; } ?>>Down</option>
                        <option value="left" <?php if(get_option('tsw_direction') =='left'){ echo 'selected'; } ?>>Left</option>
                        <option value="right" <?php if(get_option('tsw_direction') =='right'){ echo 'selected'; } ?>>Right</option>
                    </select>

                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Speed</th>
                <td><input type="text" name="tsw_speed" value="<?php echo esc_attr( get_option('tsw_speed') ); ?>" />
                    Enter digit's only e.g. "2" or "3". If kept blank default option is 2.
                </td>
            </tr>


        </table>

        <?php submit_button(); ?>

    </form>
</div>

<ul>
    <li>Hello, Wordpress users, hope this plugin is useful for your website.</li>
    <li>Request you to provide review for this Plugin</li>
    <li>You can also email me at jiteshgondaliya@gmail.com</li>
    <li>You can also contact me for tasks like Wordpress customization, bespoke plugin development, customize existing plugin, woo commerce related task, creating shopping website in wordpress, Website speed related issue etc.</li>

</ul>
    