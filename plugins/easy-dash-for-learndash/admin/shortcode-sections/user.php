<div class="flex flex-wrap tred-filtered-shortcode-boxes" id="tred-filtered-shortcode-boxes-user"
    style="display: none;">
    <div class="wrap tred-wrap-grid flex-auto">
        <div class="tred-form-fields">
            <h1>User Filtered Dash Shortcode <span><code>[easydash_user]</code></span></h1>

            <p style="font-size: 1.2em;margin-bottom: 20px;">
                <?php _e('This shortcode will reproduce on the frontend the "Filter Center" section that you have on the admin side, with "user" kind pre-selected. The visitor will be able to select one user to see its stats.', 'learndash-easy-dash'); ?>
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
                            <?php _e('buttons names:', 'learndash-easy-dash'); ?> <code>'copy'</code>,
                            <code>'csv'</code> ,<code>'excel'</code> ,<code>'pdf'</code> ,<code>'print'</code>
                            ,<code>'colvis'</code>
                            <?php _e('(comma separated)', 'learndash-easy-dash'); ?>.<br>
                            *
                            <?php _e('use', 'learndash-easy-dash'); ?> <code>'all'</code>
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
                            <?php _e('User ID. This will show stats for the pre-selected user', 'learndash-easy-dash'); ?>.<br>
                            *
                            <?php _e('don\'t use it if you want to show a dropdown search box so the visitor can find and select a user in order to see its stats.', 'learndash-easy-dash'); ?><br>
                        </td>
                        <td class="border px-8 py-4">
                            <?php _e('user will be selected on the frontend, on a dropdown search box', 'learndash-easy-dash'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="border px-8 py-4">
                            <strong>current</strong>
                        </td>
                        <td class="border px-8 py-4">
                            <code>'true'</code>
                            <?php _e('or', 'learndash-easy-dash'); ?> <code>'false'</code>;
                            <?php _e('if', 'learndash-easy-dash'); ?> <code>'true'</code>,
                            <?php _e('stats for the current logged user will be displayed. Visitors, that is, users not logged in will see nothing.', 'learndash-easy-dash'); ?><br>
                            *
                            <?php _e('if used together with the "id" parameter (above), the latter will be ignored.', 'learndash-easy-dash'); ?><br>
                            ** <code>[easydash_current_user]</code>
                            <?php _e('is a shortcut and can be used in place of', 'learndash-easy-dash'); ?>
                            <code>[easydash_user current='true']</code>,
                            <?php _e('along with other parameters above (except "id", of course)', 'learndash-easy-dash'); ?><br>
                        </td>
                        <td class="border px-8 py-4">
                            <code>'false'</code>
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
                            <?php _e('Choose whether the header (with student name, etc.) should be displayed at the top of the dash.', 'learndash-easy-dash'); ?><br>
                            **
                            <?php _e('If set to "false", exported data from the tables will show no header with student identification as well.', 'learndash-easy-dash'); ?><br>
                        </td>
                        <td class="border px-8 py-4">
                            <code>'true'</code>.
                            <?php _e('The header will be displayed', 'learndash-easy-dash'); ?>
                        </td>
                    </tr>

                    <?php do_action('tred_shortcode_section_user_rows'); ?>

                </table>


            </div>

            <div>
                <p style="font-weight:800">
                    <?php _e('Examples:', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p><code>[easydash_user]</code></p>
                <p>
                    <?php _e('This will display a dropdown so a user can be selected and the correspondant stats will be displayed on all widgets (from all types).', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p><code>[easydash_user id="10976"]</code></p>
                <p>
                    <?php _e('This will display stats on all widgets for the pre-selected user with id = 10976', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_user types="table" show="3101"]</code>
                    <?php _e('or ', 'learndash-easy-dash'); ?><br>
                    <code>[easydash_user types="table" show="table_user_courses_overview"]</code>
                </p>
                <p>
                    <?php _e('When user is selected, the dash will display only one widget (table_user_courses_overview).', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_user id="10976" hide="2103,2104"]</code>
                    <?php _e('or ', 'learndash-easy-dash'); ?><br>
                    <code>[easydash_user id="10976" hide="box_user_courses_points,box_user_courses_hours]</code>
                </p>
                <p>
                    <?php _e('Will display all widgets from all types, except Courses Points and Hours Spent in Courses boxes, for the pre-selected user with id = 10976', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_user types="box"]</code>
                </p>
                <p>
                    <?php _e('When a user is selected, the dash will display all boxes, and no other type of widget)', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_user table_buttons="all"]</code>
                </p>
                <p>
                    <?php _e('When a user is selected, the dash will display all widgets, with all table buttons in each table', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_user current="true" table_buttons="all" header="false"]</code>
                    <?php _e('or ', 'learndash-easy-dash'); ?><br>
                    <code>[easydash_current_user table_buttons="all" header="false"]</code>
                </p>
                <p>
                    <?php _e('Will display all widgets, with all table buttons in each table, for the current user (logged in), with no header at the top of the dash. Great to use on the student profile page.', 'learndash-easy-dash'); ?>
                </p>

                <br>
                <?php do_action('tred_shortcode_section_user_examples'); ?>
            </div>

        </div>


    </div> <!-- end tred-wrap-grid -->

    <div class="wrap tred-wrap-grid tred-wrap-table-widget-names flex-auto">
        <div class="tred-form-fields">
            <h1>
                <?php _e('Available Widgets', 'learndash-easy-dash'); ?>
            </h1>
            <?php tred_mount_widgets_shortcode_section_table_with_json(TRED_FILTERED_USER); ?>
        </div>
    </div> <!-- end tred-wrap-grid -->
</div>