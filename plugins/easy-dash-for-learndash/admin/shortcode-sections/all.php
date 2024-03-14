<div class="flex flex-wrap tred-filtered-shortcode-boxes" id="tred-filtered-shortcode-boxes-all" style="display: none;">
    <div class="wrap tred-wrap-grid flex-auto">
        <div class="tred-form-fields">
            <h1>All Filters Dash Shortcode <span><code>[easydash_filtered]</code></span></h1>

            <p style="font-size: 1.2em;margin-bottom: 20px;">
                <?php _e('This shortcode will reproduce on the frontend the "Filter Center" section that you have on the admin side. The user will be able to select a kind (course, user etc) and then select one item to see its stats.', 'learndash-easy-dash'); ?>
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
                            buttons names: <code>'copy'</code>, <code>'csv'</code> ,<code>'excel'</code>
                            ,<code>'pdf'</code> ,<code>'print'</code> ,<code>'colvis'</code>
                            <?php _e('(comma separated)', 'learndash-easy-dash'); ?>.<br>
                            *
                            <?php _e('Use', 'learndash-easy-dash'); ?> <code>'all'</code>
                            <?php _e('to display all buttons', 'learndash-easy-dash'); ?>.
                        </td>
                        <td class="border px-8 py-4">
                            <?php _e('no buttons will be displayed', 'learndash-easy-dash'); ?>
                        </td>
                    </tr>

                    <?php do_action('tred_shortcode_section_all_rows'); ?>

                </table>
            </div>

            <div>
                <p style="font-weight:800">
                    <?php _e('Examples:', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p><code>[easydash_filtered]</code></p>
                <p>
                    <?php _e('This will display dropdown fields so the user will select an item type (course, user etc) and then select one item to see its stats on all widgets.', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_filtered types="chart"]</code>
                </p>
                <p>
                    <?php _e('When user selects an item, will display all charts related only (no tables or boxes).', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_filtered types="box,chart"]</code>
                </p>
                <p>
                    <?php _e('When user selects an item, will display all boxes and charts related (no table)', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <p>
                    <code>[easydash_filtered table_buttons="all"]</code>
                </p>
                <p>
                    <?php _e('When user selects an item, will display all boxes, charts and tables, with all table buttons in each table', 'learndash-easy-dash'); ?>
                </p>
                <br>
                <?php do_action('tred_shortcode_section_all_examples'); ?>
            </div>


        </div>
    </div>
</div>