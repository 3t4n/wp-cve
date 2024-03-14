<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use MaxButtons\maxButton as maxButton;
$button = MB()->getClass('button'); // this loads the maxblocks template files


$title = (Install::isPro()) ? __('Wordpress Share Buttons PRO', 'mbsocial') : __('WordPress Share Buttons','mbsocial');
$header = array(
		'title' => $title,
    "tabs_active" => true,
);

$mainclass = 'maxbuttons-social-editor';
$admin = mbSocial()->admin();
$admin->header($header);

?>

  <div class='mb_tab network_tab'>
    <div class="title">
      <span class="dashicons dashicons-list-view"></span>
      <span class='title'><?php _e('Network editor', 'mbsocial') ?></span>
    </div>
    <div class="option-container">
        <div class="title"><?php _e('Network editor','mbsocial'); ?></div>
        <div class="inside">
              <?php include_once('page_networks.php'); ?>
        </div>
    </div>
  </div> <!-- mb_tab -->
