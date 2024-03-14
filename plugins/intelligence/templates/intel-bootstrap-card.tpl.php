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
<?php if ($header || $body || $footer): ?>
<div<?php print !empty($attributes) ? $attributes : ' class="card"'; ?>>
  <?php if ($header): ?>
    <div<?php print !empty($header_attributes) ? $header_attributes : ' class="card-header"'; ?>>
      <?php print $header; ?>
    </div>
  <?php endif; ?>
  <?php if ($body): ?>
    <?php print $body; ?>
  <?php endif; ?>
  <?php if ($footer): ?>
    <div<?php print !empty($footer_attributes) ? $footer_attributes : ' class="card-footer"'; ?>>
      <?php print $footer; ?>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>