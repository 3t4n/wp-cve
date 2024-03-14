<?php

/**
 *  View of Facebook button area when connected with all of Facebook Pages of connected user.
 * 
 * @uses at class-htcc-admin.php
 * @param $logout_path 
 * @param $pages array
 */

if (!defined('ABSPATH')) exit;

?>

<?php

$logout_button = [
    'logout_path' => $logout_path
];

HT_CC::view('ht-cc-admin-logout-button', $logout_button);

?>

<h5><?php _e('Connect to your Facebook page') ?></h5>
<p class="description"><?php _e('This is the page that will receive messages from your website visitors.') ?></p>
<div class="choose-page">
    <?php
    foreach ($pages as $page) { ?>
        <div class="select-page">
            <?php echo $page['name'] ?> 
            <?php if (!empty($page["remote_id"])) : ?>
                <div class="connect-page">
                    <a class="button-lazy-load" href="<?php echo $page['path']; ?>"><?php _e('Select') ?>
                        <div class="lazyload"></div>
                    </a>

                </div>           
            <?php endif; ?>
        </div>
    <?php

}
?>
</div>
