<?php
$deactivate_reasons = array(
    'no_longer_needed' => array(
        'title' => esc_html__("I no longer need the plugin", RKMW_PLUGIN_NAME),
        'input_placeholder' => '',
    ),
    'found_a_better_plugin' => array(
        'title' => esc_html__("I found a better plugin", RKMW_PLUGIN_NAME),
        'input_placeholder' => esc_html__("Please share which plugin", RKMW_PLUGIN_NAME),
    ),
    'couldnt_get_the_plugin_to_work' => array(
        'title' => esc_html__("I couldn't get the plugin to work", RKMW_PLUGIN_NAME),
        'input_placeholder' => '',
    ),
    'temporary_deactivation' => array(
        'title' => esc_html__("It's a temporary deactivation", RKMW_PLUGIN_NAME),
        'input_placeholder' => '',
    ),
    'other' => array(
        'title' => esc_html__("Other", RKMW_PLUGIN_NAME),
        'input_placeholder' => esc_html__("Please share the reason", RKMW_PLUGIN_NAME),
    ),
);
?>
<div id="rkmw_uninstall" style="display: none;">
    <div id="rkmw_modal_overlay"></div>
    <div id="rkmw_modal">
        <div id="rkmw_uninstall_header">
            <span id="rkmw_uninstall_header_title"><?php echo sprintf(esc_html__("Deactivate %s", RKMW_PLUGIN_NAME), RKMW_NAME); ?></span>
        </div>
        <form id="rkmw_uninstall_form" method="post">
            <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_uninstall_feedback', 'rkmw_nonce'); ?>
            <input type="hidden" name="action" value="rkmw_uninstall_feedback"/>

            <h4><?php echo esc_html__("Please share why you are deactivating the plugin:", RKMW_PLUGIN_NAME); ?></h4>
            <div id="rkmw_uninstall_form_body">
                <?php foreach ($deactivate_reasons as $reason_key => $reason) { ?>
                    <div class="rkmw_uninstall_feedback_input_line">
                        <input id="rkmw_uninstall_feedback_<?php echo esc_attr($reason_key); ?>" class="rkmw_uninstall_feedback_input" type="radio" name="reason_key" value="<?php echo esc_attr($reason_key); ?>"/>
                        <label for="rkmw_uninstall_feedback_<?php echo esc_attr($reason_key); ?>" class="rkmw_uninstall_feedback_input_label"><?php echo esc_html($reason['title']); ?></label>
                        <?php if (!empty($reason['input_placeholder'])) { ?>
                            <input class="rkmw_uninstall_feedback_text" type="text" name="reason_<?php echo esc_attr($reason_key); ?>" placeholder="<?php echo esc_attr($reason['input_placeholder']); ?>"/>
                        <?php } ?>
                        <?php if (!empty($reason['alert'])) { ?>
                            <div class="rkmw_uninstall_feedback_text"><?php echo esc_html($reason['alert']); ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>


                <div class="rkmw_uninstall_form_buttons_wrapper">
                    <button type="button" class="rkmw_uninstall_form_submit rkmw_uninstall_form_button"><?php echo esc_html__("Submit &amp; Deactivate", RKMW_PLUGIN_NAME); ?></button>
                    <button type="button" class="rkmw_uninstall_form_skip rkmw_uninstall_form_button"><?php echo esc_html__("Skip &amp; Deactivate", RKMW_PLUGIN_NAME); ?></button>
                </div>


                <div class="rkmw_uninstall_form_options_wrapper rkmw_uninstall_feedback_separator">
                    <div class="rkmw_uninstall_feedback_input_line">
                        <input id="rkmw_uninstall_database" class="rkmw_uninstall_feedback_input" type="checkbox" name="option_remove_records" value="1"/>
                        <label for="rkmw_uninstall_database" class="rkmw_uninstall_feedback_input_label" style="color: orangered"><?php echo esc_html__("Disconnect from Rank My WP Cloud", RKMW_PLUGIN_NAME); ?></label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>