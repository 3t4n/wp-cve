<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       views/created.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/
?>

<div class="mpp-body_wrapper">
    <div class="mpp-container">
        <h2 class="mpp-border_bottom"><?php _e('Poll created', FelixTzWPModernPollsTextdomain); ?></h2>
        <h3>
            <?php _e('To use this Poll in Frontend, use the Poll Button in the Text Editor or copy & paste the Poll shortcut.', FelixTzWPModernPollsTextdomain); ?>
        </h3>
        <h4><?php _e('Poll Shortcut', FelixTzWPModernPollsTextdomain); ?>: [mpp id="<?= $result ?>"]</h4>
        <h4><?php _e('Poll ID', FelixTzWPModernPollsTextdomain); ?>: <?= $result ?></h4>
    </div>
</div>
