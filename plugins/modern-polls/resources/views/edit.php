<?php
/********************************************************************
 * @plugin     ModernPolls
 * @files      views/edit.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

if (!current_user_can('manage_polls')) die('Access Denied');

if (isset($_SESSION['mpp_lastPost'])) {
    $id = $_SESSION['mpp_lastPost']['mpp_id'];

    $oldQuestion = $_SESSION['mpp_lastPost']['mpp_question'];
    $oldContent = $_SESSION['mpp_lastPost']['mpp_content'];
    $oldAnswers = $_SESSION['mpp_lastPost']['mpp_answers'];
    $oldTemplate = $_SESSION['mpp_lastPost']['mpp_template'];
    $oldHandle = $_SESSION['mpp_lastPost']['mpp_showResult'];

    $oldMultiple = $_SESSION['mpp_lastPost']['mpp_multiple'];

    $oldStart = $_SESSION['mpp_lastPost']['mpp_start'];
    $oldEnd = $_SESSION['mpp_lastPost']['mpp_end'];
} else {
    $id = $poll->id;
    $oldQuestion = $poll->question;
    $oldContent = $poll->content;
    $oldAnswers = $pollAnswers;
    $oldTemplate = $poll->template_id;
    $oldHandle = $poll->showresult;

    $oldMultiple = $poll->multiple;

    $oldStart = $poll->start;
    $oldEnd = $poll->expiry;
}

if ($oldHandle == -1) {
    $handleNever = 'checked';
    $handleBefore = 'disabled';
    $handleAfter = 'disabled';
} elseif ($oldHandle == 0) {
    $handleNever = '';
    $handleBefore = '';
    $handleAfter = 'checked';
} elseif ($oldHandle == 1) {
    $handleNever = '';
    $handleBefore = 'checked';
    $handleAfter = 'checked disabled';
}

if ($oldEnd == 0) {
    $expireHide = 'style="display:none;"';
    $expireChecked = 'checked';
} else {
    $expireHide = '';
    $expireChecked = '';
}
?>
<div class="mpp-body_wrapper">
    <div class="mpp-container">
        <h2 class="mpp-border_bottom"><?php _e('Edit Poll', FelixTzWPModernPollsTextdomain); ?></h2>
        <form method="post" action="<?php echo esc_attr(wp_unslash($_SERVER['REQUEST_URI'])); ?>">
            <?php wp_nonce_field('wp-polls_add-poll'); ?>
            <input type="hidden" name="mpp_id" value="<?= $id ?>">
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="action" value="edit">

            <div class="mpp-row">

                <div class="mpp-col-8">

                    <h3><?php _e('Poll Question', FelixTzWPModernPollsTextdomain); ?></h3>

                    <div class="mpp-row <?php if (isset($_SESSION['mpp_error']['question'])) echo 'mpp-error'; ?>">
                        <div class="mpp-input_group">
                            <div class="mpp-input_group_prepend">
                                <div class="mpp-input_group_text"><?php _e('Question', FelixTzWPModernPollsTextdomain) ?></div>
                            </div>
                            <input type="text"
                                   class="mpp-input <?php if (isset($_SESSION['mpp_error']['question'])) echo 'error'; ?>"
                                   name="mpp_question"
                                   value="<?php echo $oldQuestion; ?>">
                        </div>
                    </div><?php
                    if (isset($_SESSION['mpp_error']['question'])) {
                        echo '<p class="mpp-error">' . _e('You must input an Poll Question.', FelixTzWPModernPollsTextdomain) . '</p>';
                    } ?>

                    <!-- Poll Answers -->
                    <h3><?php _e('Poll Answers', FelixTzWPModernPollsTextdomain); ?></h3>
                    <div id="mpp_answers">
                        <?php
                        for ($i = 1; $i <= count($oldAnswers); $i++) {

                            echo '<div class="mpp-row mpp-answer_row ' . ((isset($_SESSION['mpp_error']['answer'][$i - 1]) ? 'mpp-error' : '')) . '" id="mpp-answer_' . $i . '">' .
                                '   <div class="mpp-input_group">' .
                                '       <div class="mpp-input_group_prepend">' .
                                '          <div class="mpp-input_group_text"><div class="mpp-answer_id">' . $i . '</div>. ' . __('Answer', FelixTzWPModernPollsTextdomain) . '</div>' .
                                '       </div>' .
                                '       <input type="text" class="mpp-input" name="mpp_answers[]" value="' . $oldAnswers[$i - 1] . '">' .
                                '       <div class="mpp-input_group_append">' .
                                '          <button type="button" data-id="' . $i . '" class="mpp-remove_answer">' . __('Remove', FelixTzWPModernPollsTextdomain) . '</button>' .
                                '       </div>' .
                                '   </div>' .
                                '</div>';

                            if (isset($_SESSION['mpp_error']['answer'][$i - 1])) {
                                echo '<p id="mpp-answer_error_' . $i . '" class="mpp-error">' . _e('This field cannot be empty. But if you dont need it, you can delete it!', FelixTzWPModernPollsTextdomain) . '</p>';
                            }
                        } ?>
                    </div>
                    <div class="mpp-row">
                        <button class="mpp-btn mpp-btn_secondary mpp-btn_ls mpp-add_answer"><?php _e('Add Answer', FelixTzWPModernPollsTextdomain) ?></button>
                    </div>

                    <h3 class="mpp-content"><?php _e('Poll Content', FelixTzWPModernPollsTextdomain); ?></h3>
                    <div class="mpp-row mpp-row_content">
                        <?php

                        $settings = array(
                            'teeny' => true,
                            'media_buttons' => false,
                            'textarea_rows' => 15,
                            'tabindex' => 1
                        );
                        wp_editor($oldContent, 'mpp_content', $settings);

                        ?>
                    </div>
                </div>

                <div class="mpp-col-4">
                    <div class="mpp-postbox">
                        <div class="mpp-postbox_title"><?php _e('Publish', FelixTzWPModernPollsTextdomain); ?></div>
                        <div class="mpp-postbox_inside">
                            <div class="mpp-postbox_content">
                                <span class="small spacer_bottom"><?php _e('Start Plan', FelixTzWPModernPollsTextdomain); ?></span>
                                <div id="mpp-startTime">
                                    <?php $this->dateField('mpp_start', $oldStart); ?>
                                    <?php $this->timeField('mpp_start', $oldStart); ?>
                                </div>
                                <span class="small spacer_bottom"><?php _e('End Plan', FelixTzWPModernPollsTextdomain); ?></span>

                                <div class="mpp-input_group spacer_bottom">
                                    <div class="mpp-input_group_prepend">
                                        <div class="mpp-input_group_text" style="padding: 4px;">
                                            <input type="checkbox" id="mpp-expire" class="" value="1"
                                                   name="mpp-expire" <?= $expireChecked ?>>
                                        </div>
                                    </div>
                                    <input type="text" class="mpp-input"
                                           value="<?php _e('This Poll never Ends', FelixTzWPModernPollsTextdomain) ?>"
                                           style=" padding: 0px 8px;font-size: 12px;" disabled>
                                </div>

                                <div id="mpp-expireTime" <?= $expireHide ?>>
                                    <?php $this->dateField('mpp_end', $oldEnd); ?>
                                    <?php $this->timeField('mpp_end', $oldEnd); ?>
                                </div>
                            </div>
                            <div class="mpp-postbox_actions">
                                <?php
                                if ($poll->active == 1) {
                                    ?>
                                    <div class="mpp-close_action">
                                        <button type="submit" name="do" value="close"
                                                class="mpp-btn mpp-btn_warning"><?php _e('Close', FelixTzWPModernPollsTextdomain) ?></button>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="mpp-open_action">
                                        <button type="submit" name="do" value="open"
                                                class="mpp-btn mpp-btn_info"><?php _e('Open_do', FelixTzWPModernPollsTextdomain) ?></button>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="mpp-publish_action">
                                    <button type="submit" name="do" value="edit"
                                            class="mpp-btn mpp-btn_primary"><?php _e('Save', FelixTzWPModernPollsTextdomain) ?></button>
                                </div>
                                <div class="mpp-clearfix"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mpp-postbox">
                        <div class="mpp-postbox_title"><?php _e('Options', FelixTzWPModernPollsTextdomain) ?></div>
                        <div class="mpp-postbox_inside">
                            <div class="mpp-postbox_content">
                                <div id="mpp-template">
                                    <span class="small spacer_bottom"><?php _e('Template', FelixTzWPModernPollsTextdomain) ?></span>
                                    <div class="mpp-input_group spacer_bottom">
                                        <select name="mpp_template" id="mpp-templateSelect" class="mpp-select">
                                            <?php
                                            foreach ($templates as $template) {
                                                if ($oldTemplate == $template->id) {
                                                    $selected_template = 'selected';
                                                } else {
                                                    $selected_template = '';
                                                }
                                                echo '<option value="' . $template->id . '" ' . $selected_template . '>' . $template->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="mpp-multipleAnswers">
                                    <span class="small spacer_bottom"><?php _e('Multiple Answers', FelixTzWPModernPollsTextdomain) ?></span>
                                    <div class="mpp_input-group spacer_bottom">
                                        <div class="mpp-input_group_text">
                                            <label for="mpp-multipleSelect">
                                                <?php _e('Allow more than one Answer?', FelixTzWPModernPollsTextdomain) ?>
                                            </label>
                                        </div>
                                        <select name="mpp_multiple_allow" id="mpp-multipleSelect"
                                                class="mpp-select mpp_flex-1">
                                            <option value="0"><?php _e('No', FelixTzWPModernPollsTextdomain); ?></option>
                                            <option value="1" <?php if ($oldMultiple > 1) echo 'selected'; ?> ><?php _e('Yes', FelixTzWPModernPollsTextdomain); ?></option>
                                        </select>
                                        <div class="mpp-input_group_text">
                                            <label for="mpp-multiple">
                                                <?php _e('Maximum Number of Answers', FelixTzWPModernPollsTextdomain) ?>
                                            </label>
                                        </div>
                                        <select name="mpp_multiple" id="mpp-multiple"
                                                class="mpp-select mpp_flex-1" <?php ($oldMultiple > 1) ? '' : 'disabled="disabled"'; ?>>
                                            <?php
                                            for ($i = 1; $i <= count($oldAnswers); $i++) {
                                                ($oldMultiple == $i) ? $selected = 'selected' : $selected = '';
                                                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mpp-resultOptions">
                                    <span class="small spacer_bottom"><?php _e('Result Handle', FelixTzWPModernPollsTextdomain); ?></span>
                                    <div class="mpp_input-group spacer_bottom">
                                        <div class="mpp_input-group-text">
                                            <input type="checkbox" id="showResultNever" name="mpp_showResultNever"
                                                   value="-1" class="mpp_checkbox-input" <?= $handleNever ?>>
                                        </div>
                                        <div class="mpp_input-group-text mpp_flex-1">
                                            <label for="showResultNever">
                                                <?php _e('Never show Result', FelixTzWPModernPollsTextdomain) ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mpp_input-group spacer_bottom">
                                        <div class="mpp_input-group-text">
                                            <input type="checkbox" id="showResultBefore" name="mpp_showResultBefore"
                                                   value="1" class="mpp_checkbox-input" <?= $handleBefore ?>>
                                        </div>
                                        <div class="mpp_input-group-text mpp_flex-1">
                                            <label for="showResultBefore">
                                                <?php _e('Show Result before Vote', FelixTzWPModernPollsTextdomain) ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mpp_input-group spacer_bottom">
                                        <div class="mpp_input-group-text">
                                            <input type="checkbox" id="showResultAfter" name="mpp_showResultAfter"
                                                   value="0" class="mpp_checkbox-input" <?= $handleAfter ?>>
                                        </div>
                                        <div class="mpp_input-group-text mpp_flex-1">
                                            <label for="showResultAfter">
                                                <?php _e('Show Result after Vote', FelixTzWPModernPollsTextdomain) ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<div style="display: none" id="templateAnswer">
    <div class="mpp-row mpp-answer_row" id="">
        <div class="mpp-input_group">
            <div class="mpp-input_group_prepend">
                <div class="mpp-input_group_text">
                    <div class="mpp-answer_id"></div>
                    . <?php echo __('Answer', FelixTzWPModernPollsTextdomain) ?></div>
            </div>
            <input type="text" class="mpp-input" name="mpp_answers[]">
            <div class="mpp-input_group_append">
                <button type="button"
                        class="mpp-remove_answer"><?php echo __('Remove', FelixTzWPModernPollsTextdomain) ?></button>
            </div>
        </div>
    </div>
</div>