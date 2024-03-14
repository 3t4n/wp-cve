<?php

/**
 * @file
 * Default theme implementation to present profile items (values from user
 * account profile fields or modules).
 *
 * This template is used to loop through and render each field configured
 * for the user's account. It can also be the data from modules. The output is
 * grouped by categories.
 *
 * @see user-profile-category.tpl.php
 *      for the parent markup. Implemented as a definition list by default.
 * @see user-profile.tpl.php
 *      where all items and categories are collected and printed out.
 *
 * Available variables:
 * - $title: Field title for the profile item.
 * - $value: User defined value for the profile item or data from a module.
 * - $attributes: HTML attributes. Usually renders classes.
 *
 * @see template_preprocess_user_profile_item()
 */
?>
<?php if (!empty($title) || !empty($markup)): ?>
<div<?php if (!empty($attributes)) { print $attributes; } else { print ' class="card"'; } ?>>
  <?php if ($title): ?>
      <h4 class="card-header"><?php print $title; ?></h4>
  <?php endif; ?>
  <?php if (empty($no_margin) && empty($no_card_block)): ?>
    <div class="card-block">
  <?php endif; ?>
    <?php print $markup; ?>
  <?php if (empty($no_margin) && empty($no_card_block)): ?>
    </div>
<?php endif; ?>
</div>
<?php endif; ?>