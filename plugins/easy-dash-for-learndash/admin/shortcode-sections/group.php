<div class="flex flex-wrap tred-filtered-shortcode-boxes" id="tred-filtered-shortcode-boxes-group">
    <div class="wrap tred-wrap-grid flex-auto">
        <div class="tred-form-fields">
            <h1>Group Filtered Dash Shortcode <span><code>[easydash_group]</code></span></h1>

            <p style="font-size: 1.2em;margin-bottom: 20px;">
                <?php _e('This shortcode will reproduce on the frontend the "Filter Center" section that you have on the admin side, with "group" kind pre-selected. The visitor will be able to select one group to see its stats.', 'learndash-easy-dash'); ?>
            </p>

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
                            <?php _e('buttons names', 'learndash-easy-dash'); ?>: <code>'copy'</code>,
                            <code>'csv'</code> ,<code>'excel'</code> ,<code>'pdf'</code> ,<code>'print'</code>
                            ,<code>'colvis'</code>
                            <?php _e('(comma separated)', 'learndash-easy-dash'); ?>.<br>
                            *
                            <?php _e('Use', 'learndash-easy-dash'); ?> <code>'all'</code>
                            <?php _e('to display all buttons', 'learndash-easy-dash'); ?>.
                        </td>
                        <td class="border px-8 py-4">
                            <?php _e('no buttons will be displayed', 'learndash-easy-dash'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="border px-8 py-4">
                            <strong>id</strong>
                        </td>
                        <td class="border px-8 py-4">
                            <?php _e('Group ID. This will show stats for the pre-selected group', 'learndash-easy-dash'); ?>.<br>
                            *
                            <?php _e('don\'t use it if you want to show a dropdown select list so the visitor can select a group in order to see its stats.', 'learndash-easy-dash'); ?><br>
                        </td>
                        <td class="border px-8 py-4">
                            <?php _e('group will be selected on the frontend, on a dropdown select list', 'learndash-easy-dash'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="border px-8 py-4">
                            <strong>header</strong>
                        </td>
                        <td class="border px-8 py-4">
                            <code>'true'</code>
                            <?php _e('or', 'learndash-easy-dash'); ?> <code>'false'</code>.<br>
                            *
                            <?php _e('Choose whether the header (with group name, etc.) should be displayed at the top of the dash.', 'learndash-easy-dash'); ?><br>
                            **
                            <?php _e('If set to "false", exported data from the tables will show no header with group identification as well.', 'learndash-easy-dash'); ?><br>
                        </td>
                        <td class="border px-8 py-4">
                            <code>'true'</code>.
                            <?php _e('The header will be displayed', 'learndash-easy-dash'); ?>
                        </td>
                    </tr>
                    <!-- 2.4.3 -->
                    <tr>
                        <td class="border px-8 py-4">
                            <strong>restrict_leader</strong>
                        </td>
                        <td class="border px-8 py-4">
                            <code>'true'</code>
                            <?php _e('or', 'learndash-easy-dash'); ?> <code>'false'</code>.<br>
                            <?php _e('Choose whether the group stats should be shown only to the group leader or not.', 'learndash-easy-dash'); ?><br>
                            *
                            <?php _e('Admins can see all groups stats.', 'learndash-easy-dash'); ?><br>
                        </td>
                        <td class="border px-8 py-4">
                            <code>'false'</code>.
                            <?php _e('The group stats will be displayed for all', 'learndash-easy-dash'); ?>
                        </td>
                    </tr>
                    <!-- 2.4.2 -->
                    <?php do_action('tred_shortcode_section_group_rows'); ?>

                </table>
            </div>

            <div>
                <p style="font-weight:800">
                    <?php _e('Examples:', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p><code>[easydash_group]</code></p>
                <p>
                    <?php _e('This will display dropdown fields so the user will select the item to see its stats on all widgets (from all types).', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p><code>[easydash_group id="33445"]</code></p>
                <p>
                    <?php _e('This will display stats on all widgets for the pre-selected group with id = 33445', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_group types="table" show="4302"]</code>
                    <?php _e('or ', 'learndash-easy-dash'); ?><br>
                    <code>[easydash_group types="table" show="table_group_users"]</code>
                </p>
                <p>
                    <?php _e('When user select a group, will display only one widget (table_group_users).', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_group id="33445" hide="4103,4104"]</code>
                    <?php _e('or ', 'learndash-easy-dash'); ?><br>
                    <code>[easydash_group id="33445" hide="box_group_quizzes,box_group_leaders]</code>
                </p>
                <p>
                    <?php _e('Will display all widgets from all types, except Quizzes Box and Leaders Box, for the pre-selected group with id = 33445', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_group types="box,table"]</code>
                </p>
                <p>
                    <?php _e('When user selects a group, will display all boxes and tables (no chart)', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_group table_buttons="all"]</code>
                </p>
                <p>
                    <?php _e('When user selects a group, will display all boxes, charts and tables, with all table buttons in each table', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <!-- 2.4.3 -->
                <p>
                    <code>[easydash_group id="3345" restrict_leader="true"]</code>
                </p>
                <p>
                    <?php _e('Will display the stats for the group with id 3345, but only if the current user is the leader of that group (or admin).', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_group restrict_leader="true"]</code>
                </p>
                <p>
                    <?php _e('Will display the stats for the groups that the current user is the leader of. Admins will see all groups stats.', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <!-- 2.4.2 -->
                <?php do_action('tred_shortcode_section_group_examples'); ?>
            </div>

        </div>
        <!-- end tred-form-fields -->


    </div> <!-- end tred-wrap-grid -->

    <div class="wrap tred-wrap-grid tred-wrap-table-widget-names flex-auto">
        <div class="tred-form-fields">
            <h1>Available Widgets</h1>
            <?php tred_mount_widgets_shortcode_section_table_with_json(TRED_FILTERED_GROUP); ?>
        </div>
    </div> <!-- end tred-wrap-grid -->

</div>