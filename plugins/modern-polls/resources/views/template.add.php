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
?>

<div class="mpp-body_wrapper">
    <div class="mpp-container">
        <h2 class="mpp-border_bottom"><?php _e('Install Template', FelixTzWPModernPollsTextdomain); ?></h2>
        <form enctype="multipart/form-data" method="post"
              action="<?php echo esc_attr(wp_unslash($_SERVER['REQUEST_URI'])); ?>">
            <?php wp_nonce_field('wp-polls_add-template'); ?>
            <input type="hidden" name="action" value="add">

            <div class="mpp-row">

                <div class="mpp-col-8">

                    <h3><?php _e('Template select', FelixTzWPModernPollsTextdomain); ?></h3>

                    <input type="file" accept=".zip" id="templateZip" name="templateZip" required>
                </div>

                <div class="mpp-col-4">
                    <div class="mpp-postbox">
                        <div class="mpp-postbox_title"><?php _e('Actions', FelixTzWPModernPollsTextdomain); ?></div>
                        <div class="mpp-postbox_inside">
                            <div class="mpp-postbox_content">
                                <div class="">
                                    <button style="width: 100%;" type="submit" name="do" value="add"
                                            class="mpp-btn mpp-btn_primary"><?php _e('Install', FelixTzWPModernPollsTextdomain) ?></button>
                                </div>
                                <div class="mpp-clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
