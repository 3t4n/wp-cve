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
<div id="intel-profile" class="profile"<?php print $attributes; ?>>
    <?php if (isset($header)): ?>
      <div id="header" class="header row">
        <div id="header-sidebar" class="sidebar <?php print ($view_mode == 'half') ? 'col-xs-3' : 'col-md-2 col-xs-3';?>">
        <?php if (!empty($elements['picture']['#markup'])): ?>
          <div id="picture" class="picture">
            <?php print $elements['picture']['#markup']; ?>
          </div>
        <?php endif; ?>
        </div>
        <div id="header-main" class="main <?php print ($view_mode == 'half') ? 'col-xs-9' : 'col-md-10 col-xs-9';?>">
          <?php if (!empty($elements['title']['#markup'])): ?>
            <h1 class="title">
              <?php print $elements['title']['#markup']; ?>
            </h1>
          <?php endif; ?>
          <?php if (!empty($elements['subtitle']['#markup'])): ?>
            <h2 class="subtitle">
              <?php print $elements['subtitle']['#markup'] ?>
            </h2>
          <?php endif; ?>
          <?php if (!empty($header_content)): ?>
            <div class="content">
              <?php foreach ($header_content as $v) {
                print isset($v['#markup']) ? $v['#markup'] : '';
              } ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
   
    <?php if (!empty($summary)): ?>
      <div id="summary" class="summary row">
        <div class="col-xs-12">
        <?php foreach ($summary as $v) {
          print isset($v['#markup']) ? $v['#markup'] : '';
        } ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if (!empty($sidebar) || !empty($main)): ?>
      <div id="profile-body" class="profile-body row">
        <?php if (!empty($sidebar)): ?>
          <div id="sidebar" class="sidebar col-md-3">
            <?php foreach ($sidebar as $v) {
              print $v['#markup'];
            } ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($main)): ?>
          <div id="main" class="col-md-9">
            <?php foreach ($main as $v) {
              print $v['#markup'];
            } ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
</div>