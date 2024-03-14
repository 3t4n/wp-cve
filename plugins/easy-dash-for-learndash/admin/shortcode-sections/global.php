<div class="wrap tred-wrap-grid flex-auto">
    <div class="tred-form-fields">
        <h1>Global Dash Shortcode <span><code>[easydash]</code></span></h1>

        <p style="font-size: 1.2em;margin-bottom: 20px;">
            <?php _e('Publish your global dash on the frontend! Use one or more shortcodes on a post, page or custom post type. Check the parameters below:', 'learndash-easy-dash'); ?>
        </p>

        <?php if (!TRED_PRO_ACTIVATED) { ?>
            <div class="notice notice-error is-dismissible tred-pro-notice">
                <p>
                    <?php _e('This is a', 'learndash-easy-dash'); ?> <strong>
                        <?php _e('premium feature', 'learndash-easy-dash'); ?>
                    </strong>
                    <?php _e('and you don\'t have the', 'learndash-easy-dash'); ?> <a
                        href="https://wptrat.com/easy-dash-for-learndash?from=plugin">Easy Dash for Learndash Pro</a>
                    <?php _e('add-on installed and/or activated. Please click', 'learndash-easy-dash'); ?> <a
                        href="https://wptrat.com/easy-dash-for-learndash?from=plugin">
                        <?php _e('here', 'learndash-easy-dash'); ?>
                    </a>
                    <?php _e('and get it, otherwise the shortcode will not work at all.', 'learndash-easy-dash'); ?>
                </p>
            </div>
        <?php } ?>

        <div class="tred-shortcode-instructions mt-12 md:mt-2 pb-24 md:pb-5">
            <table class="widefat">
                <tr>
                    <th class="bg-blue-100 border text-left px-8 py-4">
                        <?php _e('PARAMETER', 'learndash-easy-dash'); ?>
                    </th>
                    <th class="bg-blue-100 border text-left px-8 py-4">
                        <?php _e('POSSIBLE VALUES', 'learndash-easy-dash'); ?>
                    </th>
                    <th class="bg-blue-100 border text-left px-8 py-4">
                        <?php _e('DEFAULT', 'learndash-easy-dash'); ?>
                    </th>
                </tr>
                <tr>
                    <td class="border px-8 py-4">
                        <strong>types</strong>
                    </td>
                    <td class="border px-8 py-4">
                        <code>'box'</code>, <code>'chart'</code>
                        <?php _e('and/or', 'learndash-easy-dash'); ?> <code>'table'</code>
                        <?php _e('(comma separated)', 'learndash-easy-dash'); ?>.<br>
                        <?php _e('* don\'t use it if you want to display all types', 'learndash-easy-dash'); ?>
                    </td>
                    <td class="border px-8 py-4">
                        <?php _e('all types will be displayed', 'learndash-easy-dash'); ?>
                    </td>
                </tr>

                <tr>
                    <td class="border px-8 py-4">
                        <strong>show</strong>
                    </td>
                    <td class="border px-8 py-4">
                        <?php _e('widget number (or name) or comma separated list of numbers (or names).', 'learndash-easy-dash'); ?>
                        <br>
                        <?php _e('* don\'t use it if you want to display all widgets', 'learndash-easy-dash'); ?>
                    </td>
                    <td class="border px-8 py-4">
                        <?php _e('all widgets will be displayed', 'learndash-easy-dash'); ?>
                    </td>
                </tr>

                <tr>
                    <td class="border px-8 py-4">
                        <strong>hide</strong>
                    </td>
                    <td class="border px-8 py-4">
                        <?php _e('widget number (or name) or comma separated list of numbers (or names).', 'learndash-easy-dash'); ?>
                    </td>
                    <td class="border px-8 py-4">
                        <?php _e('no widgets will be hidden', 'learndash-easy-dash'); ?>
                    </td>
                </tr>

                <tr>
                    <td class="border px-8 py-4">
                        <strong>table_buttons</strong>
                    </td>
                    <td class="border px-8 py-4">
                        buttons names: <code>'copy'</code>, <code>'csv'</code> ,<code>'excel'</code> ,<code>'pdf'</code>
                        ,<code>'print'</code> ,<code>'colvis'</code>
                        <?php _e('(comma separated)', 'learndash-easy-dash'); ?>.<br>
                        *
                        <?php _e('Use', 'learndash-easy-dash'); ?> <code>'all'</code>
                        <?php _e('to display all buttons', 'learndash-easy-dash'); ?>.
                    </td>
                    <td class="border px-8 py-4">
                        <?php _e('no buttons will be displayed', 'learndash-easy-dash'); ?>
                    </td>
                </tr>

                <?php do_action('tred_shortcode_section_global_rows'); ?>

            </table>


        </div>

        <div>
            <p style="font-weight:800">
                <?php _e('Examples:', 'learndash-easy-dash'); ?>
            </p>
            <br>
            <p><code>[easydash]</code></p>
            <p>
                <?php _e('This will display all widgets (from all types).', 'learndash-easy-dash'); ?>
            </p>
            <br>
            <p>
                <code>[easydash types="table" show="301"]</code>
                <?php _e('or ', 'learndash-easy-dash'); ?>
                <code>[easydash types="table" show="table_completion_course_stats"]</code>
            </p>
            <p>
                <?php _e('Will display only one widget (table_completion_course_stats), with no table button.', 'learndash-easy-dash'); ?>
            </p>
            <br>
            <p>
                <code>[easydash hide="108,109"]</code>
                <?php _e('or ', 'learndash-easy-dash'); ?>
                <code>[easydash hide="box_course_enrolls,box_course_starts]</code>
            </p>
            <p>
                <?php _e('Will display all widgets from all types, except course enrolls and starts boxes', 'learndash-easy-dash'); ?>
            </p>
            <br>
            <p>
                <code>[easydash types="box,chart"]</code>
            </p>
            <p>
                <?php _e('Will display all boxes and charts (no table)', 'learndash-easy-dash'); ?>
            </p>
            <br>
            <p>
                <code>[easydash table_buttons="all"]</code>
            </p>
            <p>
                <?php _e('Will display all boxes, charts and tables, with all table buttons in each table', 'learndash-easy-dash'); ?>
            </p>
            <br>
            <?php do_action('tred_shortcode_section_global_examples'); ?>
        </div>

    </div>


</div> <!-- end tred-wrap-grid -->

<div class="wrap tred-wrap-grid tred-wrap-table-widget-names flex-auto">
    <div class="tred-form-fields">
        <h1>Available Widgets</h1>
        <?php tred_mount_widgets_shortcode_section_table_with_json(TRED_GLOBAL); ?>
    </div>
</div> <!-- end tred-wrap-grid -->