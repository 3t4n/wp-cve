<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       views/templates.php
 * @date       11.06.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/
?>

<div class="mpp-body_wrapper">
    <div class="mpp-container">
        <div class="mpp-container_head mpp-border_bottom">
            <h2 class=""><?php _e('Templates', FelixTzWPModernPollsTextdomain); ?></h2>

            <div class="">
                <form method="post" action="<?php echo esc_attr(wp_unslash($_SERVER['REQUEST_URI'])) ?>">
                    <button type="submit" name="action" value="add"
                            class="mpp-btn mpp-btn_primary"><?php _e('Install New', FelixTzWPModernPollsTextdomain) ?></button>
                </form>
            </div>
            <div class="mpp-clearfix"></div>
        </div>
        <div class="mpp-row">
            <?php foreach ($templates as $template) { ?>
                <div class="mpp-col-3">
                    <div class="mpp-card">
                        <div class="mpp-card_header">
                            <?= $template->name ?>

                            <?php if ($template->id != 1 && $template->id != 2) { ?>
                                <form method="post"
                                      action="<?php echo esc_attr(wp_unslash($_SERVER['REQUEST_URI'])) ?>">
                                    <input type="hidden" name="template_id" value="<?= $template->id ?>">
                                    <button type="submit" class="mpp-btn mpp-btn_danger mpp-tooltip mpp-template_btn"
                                            name="action" value="delete">
                                        <span class="mpp-tooltiptext mpp-tooltip-bottom mpp-template_tooltip"><?= __('Delete', FelixTzWPModernPollsTextdomain) ?></span>
                                        <i class="mpp-icon-trash"></i>
                                    </button>
                                </form>
                            <?php } ?>

                        </div>
                        <div class="mpp-card_body">
                            <img src="<?php echo esc_url(plugins_url('resources/views/templates/' . $template->dir . '/preview.png', FelixTzWPModernPollsFile)) ?>"
                                 alt="">
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
