<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.managetos
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$field = $displayData['field'];

$vik = VAPApplication::getInstance();

?>

<div class="inspector-form" id="inspector-option-form">

  <div class="inspector-fieldset">

    <form id="tos-form-<?php echo (int) $field['id']; ?>">

      <!-- TOS NAME - Textarea -->

      <?php echo $vik->openControl(JText::translate('VAPMANAGECUSTOMF1')); ?>
        <textarea name="name" style="resize: vertical;height: 80px;"><?php echo JText::translate($field['name']); ?></textarea>
      <?php echo $vik->closeControl(); ?>

      <!-- TOS LINK - Textarea -->

      <?php echo $vik->openControl(JText::translate('VAPMANAGECUSTOMF5')); ?>
        <textarea name="poplink" style="resize: vertical;height: 80px;"><?php echo JText::translate($field['poplink']); ?></textarea>
      <?php echo $vik->closeControl(); ?>

      <!-- TOS ID - Hidden -->

      <input type="hidden" name="id" value="<?php echo (int) $field['id']; ?>" />

    </form>

  </div>

</div>
