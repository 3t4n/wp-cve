<?php

/**
 * @file
 * Default theme implementation to present all user profile data.
 *
 * This template is used when viewing a registered member's profile page,
 * e.g., example.com/user/123. 123 being the users ID.
 *
 * Use render($user_profile) to print all profile items, or print a subset
 * such as render($user_profile['user_picture']). Always call
 * render($user_profile) at the end in order to print all remaining items. If
 * the item is a category, it will contain all its profile items. By default,
 * $user_profile['summary'] is provided, which contains data on the user's
 * history. Other data can be included by modules. $user_profile['user_picture']
 * is available for showing the account picture.
 *
 * Available variables:
 *   - $user_profile: An array of profile items. Use render() to print them.
 *   - Field variables: for each field instance attached to the user a
 *     corresponding variable is defined; e.g., $account->field_example has a
 *     variable $field_example defined. When needing to access a field's raw
 *     values, developers/themers are strongly encouraged to use these
 *     variables. Otherwise they will have to explicitly specify the desired
 *     field language, e.g. $account->field_example['en'], thus overriding any
 *     language negotiation rule that was previously applied.
 *
 * @see user-profile-category.tpl.php
 *   Where the html is handled for the group.
 * @see user-profile-item.tpl.php
 *   Where the html is handled for each item in the group.
 * @see template_preprocess_user_profile()
 *
 * @ingroup themeable
 */
?>
<div class="wrap bootstrap-wrapper intel-wrapper <?php print $class; ?>">
  <h1><?php print $title; ?></h1>

  <?php if (!empty($navbar)) : ?>
    <?php print $navbar; ?>
  <?php endif ?>

  <?php if (!empty($breadcrumbs)) : ?>
    <?php print $breadcrumbs; ?>
  <?php endif ?>

  <?php if (!empty($messages)) {
    $classes = array(
      'error' => 'alert-danger',
      'warning' => 'alert-warning',
      'status' => 'alert-info',
      'success' => 'alert-success',
    );
    foreach ($messages as $type => $mm) {
      $class = $classes[$type];
      foreach ($mm as $msg) {
        print '<div class="alert alert-dismissible ' . $class . '" role="alert">';
        print '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        print $msg . "</div>\n";
      }
    }
  }
  ?>

  <div class="intel-content full">
    <?php print $markup; ?>
  </div>

</div>