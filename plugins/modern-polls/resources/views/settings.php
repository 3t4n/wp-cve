<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       views/settings.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

$log_ip = $settings->log_ip;
$log_cookie = $settings->log_cookie;
$log_user = $settings->log_user;
$log_time = $settings->log_expire;

$closed_poll = $settings->closed_poll;

?>
<div class="mpp-body_wrapper">
    <div class="mpp-container">
        <form method="post" action="<?php echo esc_attr(wp_unslash($_SERVER['REQUEST_URI'])); ?>">
            <div class="mpp-container_head mpp-border_bottom">
                <h2 class=""><?php _e('Settings', FelixTzWPModernPollsTextdomain); ?></h2>

                <div class="">
                    <button type="submit" name="do" value="save"
                            class="mpp-btn mpp-btn_primary"><?php _e('Save changes', FelixTzWPModernPollsTextdomain) ?></button>
                </div>
                <div class="mpp-clearfix"></div>
            </div>
            <ul class="mpp-nav mpp-nav_tabs">
                <li class="mpp-nav_item">
                    <a class="mpp-nav_link mpp-active"
                       data-href="poll"><?php _e('Poll', FelixTzWPModernPollsTextdomain) ?></a>
                </li>
                <li class="mpp-nav_item">
                    <a class="mpp-nav_link"
                       data-href="logging"><?php _e('Logging', FelixTzWPModernPollsTextdomain) ?></a>
                </li>
            </ul>
            <div class="mpp-tab_content">
                <div class="mpp-tab_pane mpp-tab_pane_fade mpp-tab_pane_show mpp-active" id="mpp-poll">
                    <span class="small spacer_bottom"><?php _e('Closed Polls', FelixTzWPModernPollsTextdomain) ?></span>
                    <div class="mpp-input_group spacer_bottom">
                        <div class="mpp-input_group_text">
                            <label for="mpp_poll_closed">
                                <?php _e('When Poll is Closed', FelixTzWPModernPollsTextdomain) ?>
                            </label>
                        </div>
                        <select name="mpp_poll_closed" class="mpp-select mpp_flex-1" id="mpp_poll_closed">
                            <option value="0" <?= ($closed_poll == 0) ? 'selected' : ''; ?>><?php _e('Display the Result', FelixTzWPModernPollsTextdomain); ?></option>
                            <option value="1" <?= ($closed_poll == 1) ? 'selected' : ''; ?>><?php _e('Display disabled Form', FelixTzWPModernPollsTextdomain); ?></option>
                            <option value="2" <?= ($closed_poll == 2) ? 'selected' : ''; ?>><?php _e('Hide', FelixTzWPModernPollsTextdomain); ?></option>
                        </select>
                    </div>
                </div>
                <div class="mpp-tab_pane mpp-tab_pane_fade" id="mpp-logging">
                    <span class="small spacer_bottom"><?php _e('Log Voters', FelixTzWPModernPollsTextdomain) ?></span>
                    <div class="mpp_input-group spacer_bottom">
                        <div class="mpp_input-group spacer_bottom">
                            <div class="mpp_input-group-text">
                                <input type="checkbox" id="logbyip" name="mpp_log_ip" value="1"
                                       class="mpp_checkbox-input" <?= ($log_ip) ? 'checked' : ''; ?>>
                            </div>
                            <div class="mpp_input-group-text mpp_flex-1">
                                <label for="logbyip">
                                    <?php _e('Log Users by IP-Address', FelixTzWPModernPollsTextdomain) ?>
                                </label>
                            </div>
                        </div>
                        <div class="mpp_input-group spacer_bottom">
                            <div class="mpp_input-group-text">
                                <input type="checkbox" id="logbycookie" name="mpp_log_cookie" value="1"
                                       class="mpp_checkbox-input" <?= ($log_cookie) ? 'checked' : ''; ?>>
                            </div>
                            <div class="mpp_input-group-text mpp_flex-1">
                                <label for="logbycookie">
                                    <?php _e('Log Users by Cookie', FelixTzWPModernPollsTextdomain) ?>
                                </label>
                            </div>
                        </div>
                        <div class="mpp_input-group spacer_bottom">
                            <div class="mpp_input-group-text">
                                <input type="checkbox" id="logbyuser" name="mpp_log_user" value="1"
                                       class="mpp_checkbox-input" <?= ($log_user) ? 'checked' : ''; ?>>
                            </div>
                            <div class="mpp_input-group-text mpp_flex-1">
                                <label for="logbyuser">
                                    <?php _e('Log Users by Username', FelixTzWPModernPollsTextdomain) ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <span class="small spacer_bottom"><?php _e('Log Time', FelixTzWPModernPollsTextdomain) ?></span>
                    <div class="mpp_input-group spacer_bottom">
                        <div class="mpp_input-group spacer_bottom ">
                            <div class="mpp_input-group-text">
                                <input style="text-align: center" type="text" pattern="[0-9]{1,}" id="logtime"
                                       name="mpp_log_time" class="mpp-input" value="<?= $log_time ?>">
                            </div>
                            <div class="mpp_input-group-text mpp_flex-1">
                                <label for="logtime">
                                    <?php _e('Time until Log expires (in minutes)', FelixTzWPModernPollsTextdomain) ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>