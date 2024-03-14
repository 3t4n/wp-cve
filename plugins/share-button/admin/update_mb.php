<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

$header = array(
		'title' => __('MaxButtons Social Share Addon','mbsocial')
);

$admin = mbSocial()->admin();
$admin->header($header);

$action = 'install-plugin';
$slug = 'maxbuttons';
$url = wp_nonce_url(
  add_query_arg(
    array(
        'action' => $action,
        'plugin' => $slug
    ),
    admin_url( 'update.php' )
  ),
  $action.'_'.$slug
);

?>
<div class='mb-message warning'>
    <h3><?php _e('Update MaxButtons', 'mb-social'); ?></h3>
    <p>
<?php printf(__('MaxButtons Social Share requires at least %s version %s of MaxButtons or MaxButtons PRO</a> . You are running version %s. A newer version is required to function properly. Sorry for the inconvience.', 'mbsocial'),  "<a href='$url'>", MBSOCIAL_REQUIRED_MB, MAXBUTTONS_VERSION_NUM, '</a>'); ?>
</p>

<p><?php printf(__('You can find older versions of this plugin on the %s WordPress site %s', 'mbsocial'), '<a href="https://wordpress.org/plugins/share-button/advanced/" target="_blank">', '</a>' ); ?></p>
</div>
