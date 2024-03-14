<?php
namespace MBSocial;

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;

$networks = MBSocial()->networks()->get();


$network_array = array();
$networkBlock = collections::getBlock('networkBlock');

foreach($networks as $network)
{
  $name = $network->get('network');
  $native = $network->get('is_native');

  if (! $network->get('is_editable'))
  {
      continue;
  }

  $label = $network->get_nice_name();
  $icon_args = array(
      'icon' => $network->get('icon'),
      'icon_type' => $network->get('icon_type'),
  );
  if ($icon_args['icon_type'] == 'image')
  {
    $icon_args['image_id'] = $network->get('icon_image_id');
    $icon_args['image_size'] = $network->get('icon_image_size');
    $icon_args['image_url'] = $network->get('icon_image_url');
  }

  $icon = MBSocial()->admin()->renderIcon($icon_args); //$networkBlock->renderIcon($network);

  $network_array[$name] = array(
      'label' => $label,
      'icon' => $icon,
    );

}

ksort($network_array);

?>

<div class='network_selection'>
  <a href='#add' class='add-custom-network button mb-ajax-action' data-action='show-customnetworks'><?php _e('Add Other Networks', 'mbsocial'); ?></a>

  <ul>
  <?php foreach ($network_array as $name => $item):

  ?>

  <li class='mb-ajax-action' data-action='network-settings' data-param='<?php echo $name ?>'>
      <?php echo $item['icon'] ?> <span class='label'><?php echo $item['label']; ?></span>

  </li>

<?php endforeach; ?>
  </ul>

</div>

<form method="POST" class="mb-ajax-form" data-action="network-save">
  <div class='network_editor option-container'>
    <div class='title'><?php _e("Settings", 'mbsocial'); ?> </div>
      <div class='inside'>

          <h4><?php _e("Welcome to the Network Editor!", 'mbsocial'); ?></h4>
          <p>You can change default Network Options using the selection on the left hand</p>
          <p>You can select additional networks with the 'add other networks' button</p>

      </div>

  </div>
</form>
