<div class="flex flex-wrap tred-filtered-shortcode-boxes" id="tred-filtered-shortcode-boxes-course">
    <div class="wrap tred-wrap-grid flex-auto">
        <div class="tred-form-fields">
            <h1>Course Filtered Dash Shortcode <span><code>[easydash_course]</code></span></h1>

            <p style="font-size: 1.2em;margin-bottom: 20px;">
                <?php _e('This shortcode will reproduce on the frontend the "Filter Center" section that you have on the admin side, with "course" kind pre-selected. The visitor will be able to select one course to see its stats.', 'learndash-easy-dash'); ?>
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
                            <?php _e('Course ID. This will show stats for the pre-selected course', 'learndash-easy-dash'); ?>.<br>
                            *
                            <?php _e('don\'t use it if you want to show a dropdown select list so the visitor can select a course in order to see its stats.', 'learndash-easy-dash'); ?><br>
                        </td>
                        <td class="border px-8 py-4">
                            <?php _e('course will be selected on the frontend, on a dropdown select list', 'learndash-easy-dash'); ?>
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
                            <?php _e('Choose whether the header (with course name, etc.) should be displayed at the top of the dash.', 'learndash-easy-dash'); ?><br>
                            **
                            <?php _e('If set to "false", exported data from the tables will show no header with course identification as well.', 'learndash-easy-dash'); ?><br>
                        </td>
                        <td class="border px-8 py-4">
                            <code>'true'</code>.
                            <?php _e('The header will be displayed', 'learndash-easy-dash'); ?>
                        </td>
                    </tr>

                    <?php do_action('tred_shortcode_section_course_rows'); ?>

                </table>
            </div>

            <div>
                <p style="font-weight:800">
                    <?php _e('Examples:', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p><code>[easydash_course]</code></p>
                <p>
                    <?php _e('This will display dropdown fields so the user will select the item to see its stats on all widgets (from all types).', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p><code>[easydash_course id="32478"]</code></p>
                <p>
                    <?php _e('This will display stats on all widgets for the pre-selected course with id = 32478', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_course types="chart" show="1201"]</code>
                    <?php _e('or ', 'learndash-easy-dash'); ?><br>
                    <code>[easydash_course types="chart" show="chart_filtered_most_completed_lessons"]</code>
                </p>
                <p>
                    <?php _e('When user selects a course, will display only one widget (chart_filtered_most_completed_lessons).', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_course id="32478" hide="1103,1104"]</code>
                    <?php _e('or ', 'learndash-easy-dash'); ?><br>
                    <code>[easydash_course id="32478" hide="box_course_days,box_course_same_day]</code>
                </p>
                <p>
                    <?php _e('Will display all widgets from all types, except Average Days and Same Day Completed boxes, for the pre-selected course with id = 32478', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_course types="box,chart"]</code>
                </p>
                <p>
                    <?php _e('When user selects a course, will display all boxes and charts (no table)', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_course table_buttons="all"]</code>
                </p>
                <p>
                    <?php _e('When user selects a course, will display all boxes, charts and tables, with all table buttons in each table', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <?php do_action('tred_shortcode_section_course_examples'); ?>
            </div>

        </div>
        <!-- end tred-form-fields -->


    </div> <!-- end tred-wrap-grid -->

    <div class="wrap tred-wrap-grid tred-wrap-table-widget-names flex-auto">
        <div class="tred-form-fields">
            <h1>Available Widgets</h1>
            <?php tred_mount_widgets_shortcode_section_table_with_json(TRED_FILTERED_COURSE); ?>
        </div>
    </div> <!-- end tred-wrap-grid -->

</div>