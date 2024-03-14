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
<div class="inside bootstrap-wrapper intel-wrapper">
  <div class="intel-content half row">
    <h4 class="card-header"><?php print __('Submitter profile', 'nf_intel'); ?></h4>
    $output
    <!-- <h4 class="card-header"><?php print __('Analytics', 'nf_intel'); ?></h4> -->
    <div class="card-deck-wrapper m-b-1">
      <div class="card-deck">
        <?php print Intel_Df::theme('intel_trafficsource_block', array('trafficsource' => $submission->data['analytics_session']['trafficsource'])); ?>
        <?php print Intel_Df::theme('intel_location_block', array('entity' => $submission)); ?>
        <?php print Intel_Df::theme('intel_browser_environment_block', array('entity' => $submission)); ?>
      </div>
    </div>
    <?php print Intel_Df::theme('intel_visitor_profile_block', array('title' => __('Visit chronology', 'nf_intel'), 'markup' => $steps_table, 'no_margin' => 1)); ?>
  </div>
</div>