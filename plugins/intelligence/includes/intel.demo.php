<?php
/**
 * @file
 * Support for Intelligence demo
 *
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_demo_settings_default() {
  $settings = array();
  $settings['css_injection'] = "
img.attachment-post-thumbnail {
  display: none !important;
}
  ";

  $goals = intel_goal_load();
  $settings['forms'] = array();
  $settings['forms']['intel_demo_contact_form'] = array(
    'tracking_event_name' => !empty($goals['contact']) ? 'form_submission__contact' : 'form_submission',
  );
  $settings['forms']['intel_demo_offer_form'] = array(
    //'tracking_event_name' => 'form_submission',
    'tracking_event_name' => '',
  );
  $settings['shortcodes'] = array();
  $settings['shortcodes']['contact_email'] = Intel_Df::l('info@example.com', 'mailto:info@example.com');
  $settings['shortcodes']['contact_telephone'] = Intel_Df::l('214.555.1212', 'tel:214.555.1212');
  $settings['shortcodes']['contact_downloads'] = Intel_Df::l('Demo Brochure', INTEL_URL . 'files/demo_brochure.pdf');
  $settings['shortcodes']['contact_sites'] = Intel_Df::l('IntelligenceWP.com', '//intelligencewp.com');

  // CTA shortcodes
  $l_options = array(
    'html' => 1,
    'attributes' => array(
      'title' => Intel_Df::t('Demo CTA A'),
      'class' => array(
        'cta-track'
      ),
    ),
  );
  $settings['shortcodes']['cta_offer_a_bottom'] = Intel_Df::l("\n" . '  <img src="http://via.placeholder.com/1280x360&text=Call+To+Action+A" width="1280" height="360">' . "\n", 'intelligence/demo/offer/alpha', $l_options);

  $l_options['attributes']['title'] = Intel_Df::t('Demo CTA B');
  $settings['shortcodes']['cta_offer_b_bottom'] = Intel_Df::l("\n" . '  <img src="http://via.placeholder.com/1280x360&text=Call+To+Action+B" width="1280" height="360">' . "\n", 'intelligence/demo/offer/beta', $l_options);

  // Offer download link shortcodes
  $l_options = array(
    'html' => 1,
    'attributes' => array(
      'title' => Intel_Df::t('Download'),
    ),
  );
  $l_options = Intel_Df::l_options_add_class('icon-link', $l_options);
  $settings['shortcodes']['download_link_offer_a'] = Intel_Df::l( "\n" . '  <i class="fa fa-arrow-circle-down" aria-hidden="true" style="font-size: 5em;"></i>' . "\n", '/wp-content/plugins/intelligence/files/demo_brochure.pdf', $l_options);

  $settings['shortcodes']['download_link_offer_b'] = Intel_Df::l( "\n" . '  <i class="fa fa-arrow-circle-down" aria-hidden="true" style="font-size: 5em;"></i>' . "\n", '/wp-content/plugins/intelligence/files/demo_brochure.pdf', $l_options);


  return $settings;
}

function intel_demo_user_load($id = NULL) {
  $users = array();

  $users['-1'] = (object) array(
    'display_name' => 'Demo User 1'
  );

  $users['-2'] = (object) array(
    'display_name' => 'Demo User 2'
  );

  $users['-3'] = (object) array(
    'display_name' => 'Demo User 3'
  );

  if ($id) {
    return !empty($users[$id]) ? $users[$id] : FALSE;
  }

  return $users;
}

function intel_demo_settings() {
  $settings = &Intel_Df::drupal_static( __FUNCTION__, -1);

  if ($settings == -1) {
    $settings = get_option('intel_demo_settings', intel_demo_settings_default());
  }

  return $settings;
}

function intel_demo_count_user_posts($userid, $post_type = '') {
  $posts = intel_demo_post_load();
  $count = 0;
  foreach ($posts as $post) {
    if (!$post_type || $post->post_type == $post_type) {
      $count += ($post->post_author == $userid);
    }
  }
  return $count;
}

function intel_demo_term_load($id = NULL) {
  $terms = &Intel_Df::drupal_static( __FUNCTION__, array());

  if (empty($terms)) {
    $term = array();
    $term['-1'] = array(
      'term_id' => -1,
      'name' => 'demo tag 1',
      'taxonomy' => 'post_tag',
      'count' => 0,
    );

    $term['-2'] = array(
      'term_id' => -2,
      'name' => 'demo tag 2',
      'taxonomy' => 'post_tag',
      'count' => 0,
    );

    $term['-3'] = array(
      'term_id' => -3,
      'name' => 'demo tag 3',
      'taxonomy' => 'post_tag',
      'count' => 0,
    );

    $term['-4'] = array(
      'term_id' => -4,
      'name' => 'demo tag 4',
      'taxonomy' => 'post_tag',
      'count' => 0,
    );

    $term['-5'] = array(
      'term_id' => -5,
      'name' => 'demo tag 5',
      'taxonomy' => 'post_tag',
      'count' => 0,
    );

    $term['-6'] = array(
      'term_id' => -6,
      'name' => 'demo tag 6',
      'taxonomy' => 'post_tag',
      'count' => 0,
    );

    $term['-7'] = array(
      'term_id' => -7,
      'name' => 'demo tag 7',
      'taxonomy' => 'post_tag',
      'count' => 0,
    );

    $term['-8'] = array(
      'term_id' => -8,
      'name' => 'demo tag 8',
      'taxonomy' => 'post_tag',
      'count' => 0,
    );

    $term['-11'] = array(
      'term_id' => -11,
      'name' => 'demo category 1',
      'taxonomy' => 'category',
      'count' => 0,
    );

    $term['-12'] = array(
      'term_id' => -12,
      'name' => 'demo category 2',
      'taxonomy' => 'category',
      'count' => 0,
    );

    $posts = intel_demo_post_load();
    foreach ($posts as $post) {
      if (!empty($post->intel_demo['terms'])) {
        foreach ($post->intel_demo['terms'] as $tid) {
          $term["$tid"]['count']++;
        }
      }
    }

    foreach ($term as $i => $t) {
      $terms[$i] = (object)$t;
    }
  }


  if ($id) {
    return !empty($terms[$id]) ? $terms[$id] : FALSE;
  }

  return $terms;
}

function intel_demo_menu_items() {
  $menu_items = array();
  $menu_items[] = array(
    'text' => 'Home',
    'path' => 'intelligence/demo/',
  );
  $menu_items[] = array(
    'text' => 'Page',
    'path' => 'intelligence/demo/page',
  );
  $menu_items[] = array(
    'text' => 'Blog',
    'path' => 'intelligence/demo/blog',
  );
  $menu_items[] = array(
    'text' => 'Contact',
    'path' => 'intelligence/demo/contact',
  );

  $menu_items = apply_filters('intel_demo_menu_items', $menu_items);

  $menu_items = apply_filters('intel_demo_menu_items_alter', $menu_items);

  return $menu_items;
}

function intel_demo_urls() {
  $urls = array();
  $blog_ids = array();
  $posts = intel_demo_posts();

  foreach ($posts as $id => $post) {
    $url = '';
    if (!empty($post->intel_demo['url'])) {
      $url = $post->intel_demo['url'];
    }
    if (!$url) {
      continue;
    }

    $urls[$url] = array(
      'entity' => array(
        'type' => 'post',
        'id' => $post->ID,
      )
    );

    if ($post->post_type == 'post') {
      array_unshift($blog_ids, $post->ID);
    }
  }

  $urls['intelligence/demo/blog'] = array(
    'entity' => array(
      'type' => 'post',
      'id' => $blog_ids,
    ),
    'wp_query' => array(
      'is_page' => false,
      'is_singular' => false,
    ),
  );

  $menu_items = apply_filters('intel_demo_urls', $urls);

  $menu_items = apply_filters('intel_demo_menu_urls', $urls);

  return $urls;
}

/*
function intel_demo_urls_0() {
  $i = -1;
  $urls = array(
    'intelligence/demo' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/page' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/contact' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/blog' => array(
      'entity' => array(
        'type' => 'post',
        'id' => array($i-4, $i-3, $i-2, $i-1, $i),
      ),
      'wp_query' => array(
        'is_page' => false,
        'is_singular' => false,
      ),
    ),
    'intelligence/demo/blog/alpha' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/blog/beta' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/blog/charlie' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/blog/delta' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/blog/echo' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/offer/alpha' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/offer/beta' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/download/alpha' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),
    'intelligence/demo/download/beta' => array(
      'entity' => array(
        'type' => 'post',
        'id' => $i--,
      ),
    ),

  );

  $menu_items = apply_filters('intel_demo_urls', $urls);

  $menu_items = apply_filters('intel_demo_menu_urls', $urls);

  return $urls;
}
*/

function intel_demo_posts($options = array()) {
  $posts = &Intel_Df::drupal_static( __FUNCTION__, array());

  $demo_settings = get_option('intel_demo_settings', intel_demo_settings_default());
  if (empty($posts)) {
    $post = array();

    $ipsums = array(
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer pulvinar molestie massa non aliquam. Nulla dolor metus, elementum vitae neque non, congue placerat sapien. Pellentesque mollis tortor in diam elementum accumsan. Fusce elementum sapien non massa imperdiet, a iaculis ante accumsan. Donec faucibus tempor velit, nec egestas libero placerat in. Nam vitae congue nisi. Integer mi elit, cursus et sapien vitae, hendrerit gravida quam. Mauris sit amet ante venenatis, posuere lectus vitae, fermentum enim. Vivamus fermentum eros mi, et elementum lorem lobortis et. Aenean consectetur mauris ac varius vehicula. Vestibulum condimentum ultrices sem, ac aliquet sapien congue et. Aenean eu felis a ligula mattis feugiat.',
      'Aenean elementum elit sed nibh sollicitudin efficitur. Maecenas tincidunt ornare accumsan. Duis eros mi, posuere ut justo at, eleifend aliquam erat. Proin in lectus eros. Ut non ullamcorper dolor. Aenean tincidunt ligula suscipit porta ullamcorper. Vivamus in placerat mi. Nam tempus lacus sit amet nisl auctor accumsan. Maecenas laoreet massa in aliquam ullamcorper. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Quisque pellentesque lacinia felis sit amet ornare. Sed a felis diam. Etiam egestas sed augue at maximus. Ut ultrices magna hendrerit porta dapibus. Morbi fermentum at nisl sed ultrices.',
      'Donec rhoncus nunc vitae turpis hendrerit, ac mattis massa ultrices. Interdum et malesuada fames ac ante ipsum primis in faucibus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur non nisl ante. Ut eu tempor mauris. Sed non massa dapibus, euismod enim at, porttitor est. Donec luctus lobortis rutrum. Cras rhoncus diam at efficitur laoreet. Phasellus rhoncus elementum risus eu fermentum. Nam sit amet ornare turpis. Suspendisse placerat, risus vel dignissim sollicitudin, tortor lorem imperdiet purus, hendrerit ultrices justo felis ut magna. Mauris dapibus gravida nibh, sed semper ante congue ac.',
      'Curabitur pharetra odio sed felis hendrerit elementum. Phasellus bibendum eget risus a mattis. Maecenas egestas maximus lacus ac tincidunt. Vivamus dapibus ut est at bibendum. Suspendisse nec justo diam. Donec fermentum, lorem ut faucibus aliquet, felis erat condimentum leo, aliquet vestibulum tellus sapien eget enim. Vivamus sit amet orci urna. Pellentesque tempus magna leo, et consequat massa maximus non. Duis quis lacinia mauris, suscipit tincidunt libero. Cras tristique molestie mauris. Duis condimentum dui sapien, non aliquam arcu mattis vitae. Nam in nulla sed est finibus ultricies. Fusce accumsan diam vel eros eleifend, nec gravida leo mollis.',
      'Nullam diam orci, maximus sed diam at, sodales vestibulum purus. Aenean tincidunt lorem blandit porta efficitur. Curabitur lacus lacus, aliquam eu facilisis ut, mollis ut orci. In finibus nisi vitae dui aliquet placerat. Proin at urna eget magna egestas interdum sit amet eu risus. Mauris dapibus feugiat convallis. Nunc et ultrices erat. Ut blandit consequat diam feugiat ultricies. Nam lobortis mauris in libero vestibulum ultricies. Proin sagittis faucibus eros in condimentum. Nullam vel urna sit amet leo faucibus tincidunt quis at enim. Mauris vestibulum sem ac odio suscipit, ut dignissim orci blandit.',
      'Ut felis magna, sodales non dictum sit amet, accumsan vel diam. Sed et nisi molestie, scelerisque lorem ut, accumsan tellus. Aenean pharetra non felis ut commodo. Nunc ut tincidunt risus. In hac habitasse platea dictumst. Vestibulum sit amet convallis nulla, nec mattis urna. Curabitur eu odio a tortor laoreet cursus. Donec vel dapibus massa. Duis iaculis lacus vel magna vehicula, hendrerit tincidunt felis vehicula. Fusce sodales purus nisi, vitae tristique libero finibus at. Suspendisse in hendrerit lectus. Cras nec est at lorem vulputate accumsan. Curabitur ornare ac mauris ut hendrerit. Integer vehicula sed leo et elementum. Donec nec malesuada nisi.',
      'Aenean ullamcorper mi nec eros vehicula, eget dictum lacus condimentum. Mauris eu sapien lacus. Fusce sagittis metus vitae vulputate pulvinar. Vivamus vestibulum, dolor vel cursus imperdiet, lectus elit egestas neque, eget consectetur eros massa a quam. Duis sit amet magna dapibus, volutpat lorem id, ullamcorper ex. Praesent aliquet erat dolor. Duis lacus ante, gravida non faucibus et, facilisis vitae erat. Phasellus rhoncus lorem in euismod euismod. Aliquam maximus placerat velit eget mattis. Sed non felis et turpis consectetur eleifend sit amet eu sapien. Fusce nec consequat mi. Ut rutrum erat lacus, at pretium diam egestas non. Nullam ac tincidunt leo. Ut ultricies lorem in sodales hendrerit. Mauris eget facilisis eros. Vivamus mollis arcu eget viverra convallis.',
      'Aenean lacinia quam ipsum, vitae convallis lorem feugiat a. Nunc pellentesque id enim vel pulvinar. Sed rhoncus tellus ipsum, nec placerat ipsum elementum vitae. In hac habitasse platea dictumst. Nulla finibus tortor consequat, aliquam tellus ac, consectetur risus. Vivamus scelerisque nibh a orci pharetra commodo. Fusce fringilla eros sagittis fringilla commodo.',
    );

    $ipsums_s = array(
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
      'Integer pulvinar molestie massa non aliquam.',
      'Nulla dolor metus, elementum vitae neque non, congue placerat sapien.',
      'Pellentesque mollis tortor in diam elementum accumsan.',
    );

    $i = -1;
    $post["$i"] = array(
      'ID' => $i,
      'post_type' => 'page',
      'post_title' => Intel_Df::t('Demo Home'),
      'post_content' => '<div class="intel-demo-featured-image"><img src="http://via.placeholder.com/1280x720&text=Welcome"></div>' . "\n\n" . $ipsums[0] . "\n\n" . $ipsums[1],
      'intel_demo' => array(
        'url' => 'intelligence/demo',
      ),
    );

    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_type' => 'page',
      'post_title' => 'Demo Page',
      'post_content' => $ipsums[0] . "\n\n" . '<strong>' . Intel_Df::t('Sales Demo Video') . '</strong>'  . "\n" . '[embed]https://www.youtube.com/watch?v=3H0aBxXzgKg[/embed]' . "\n\n" . $ipsums[1] . "\n\n" . $ipsums[2] . "\n\n" . $ipsums[3] . "\n\n" . $ipsums[4],
      //'post_content' => '<h3>' . Intel_Df::t('Sales Demo') . '</h3>' . '[embed title="test title" data-io-play-value=".55" data-io-consumed-value="1.11"]https://www.youtube.com/watch?v=3H0aBxXzgKg[/embed]',
      'intel_demo' => array(
        'url' => 'intelligence/demo/page',
      ),
    );


    $content =  '<div class="contact-wrapper">' . "\n";
    $content .= '  <div class="col-left">' . "\n";
    $content .= '    <h3>Inquiry Form</h3>' . "\n";
    $content .= '      [intel_form name="intel_demo_contact_form" title="' . Intel_Df::t('Demo Contact Form') . '"]' . "\n";
    $content .= '    </div>' . "\n";
    $content .= '    <div class="col-right">' . "\n";
    $content .= '      <h3>Contact Info</h3>' . "\n";
    $content .= '      <strong>' . Intel_Df::t('Email') . ':</strong><br>[intel_demo name="contact_email"]<br><br>' . "\n";
    $content .= '      <strong>' . Intel_Df::t('Telephone') . ':</strong><br>[intel_demo name="contact_telephone"]<br><br>' . "\n";
    $content .= '      <strong>' . Intel_Df::t('Downloads') . ':</strong><br>[intel_demo name="contact_downloads"]<br><br>' . "\n";
    $content .= '      <strong>' . Intel_Df::t('Sites') . ':</strong><br>[intel_demo name="contact_sites"]<br><br>' . "\n";
    //$content .= '      <strong>' . Intel_Df::t('Email') . ':</strong><br>' . Intel_Df::l('info@example.com', 'mailto:info@example.com') . '<br><br>' . "\n";
    //$content .= '      <strong>' . Intel_Df::t('Telephone') . ':</strong><br> ' . Intel_Df::l('214.555.1212', 'tel:214.555.1212') . '<br><br>' . "\n";
    //$content .= '      <strong>' . Intel_Df::t('Address') . ':</strong><br> ' . Intel_Df::t('123 Easy Street') . '<br>' . Intel_Df::t('Dallas, TX 75201') . '<br><br>' . "\n";
    //$content .= '      <strong>' . Intel_Df::t('Downloads') . ':</strong><br> ' . Intel_Df::l('Demo Brochure', INTEL_URL . 'files/demo_brochure.pdf') . '<br><br>' . "\n";
    //$content .= '      <strong>' . Intel_Df::t('Sites') . ':</strong><br> ' . Intel_Df::l('IntelligenceWP.com', '//intelligencewp.com') . '<br><br>' . "\n";
    $content .= '    </div>' . "\n";
    $content .= '</div>' . "\n";
    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_type' => 'page',
      'post_title' => 'Demo Contact',
      'post_content' => $content,
      'intel_demo' => array(
        'url' => 'intelligence/demo/contact',
      ),
    );

    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_author' => -2,
      'post_type' => 'post',
      'post_name' => 'intelligence/demo/blog/alpha',
      'guid' => 'intelligence/demo/blog/alpha',
      'post_title' => Intel_Df::t('Demo Post A'),
      'post_content' => $ipsums[0] . "\n\n" . '[embed]https://www.youtube.com/watch?v=3H0aBxXzgKg[/embed]' . "\n\n" . $ipsums[1] . "\n\n" . $ipsums[2] . "\n\n" . $ipsums[3] . "\n\n" . $ipsums[4],
      'post_excerpt' => $ipsums[0],
      'comment_status' => 'open',
      'intel_demo' => array(
        'terms' => array(-1, -6, -8, -11),
        'url' => 'intelligence/demo/blog/alpha',
      ),
    );

    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_author' => -2,
      'post_type' => 'post',
      'post_name' => 'intelligence/demo/blog/beta',
      'post_title' => Intel_Df::t('Demo Post B'),
      'post_content' => $ipsums[1] . "\n\n" . '[embed]https://www.youtube.com/watch?v=3H0aBxXzgKg[/embed]' . "\n\n" . $ipsums[2] . "\n\n" . $ipsums[3]  . "\n\n" . $ipsums[4],
      'post_excerpt' => $ipsums[1],
      //'comment_status' => 'open',
      'intel_demo' => array(
        'terms' => array(-2, -6, -8, -11),
        'url' => 'intelligence/demo/blog/beta',
      ),
    );

    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_author' => -2,
      'post_type' => 'post',
      'post_name' => 'intelligence/demo/blog/charlie',
      'post_title' => Intel_Df::t('Demo Post C'),
      'post_content' => $ipsums[2] . "\n\n" . $ipsums[3] . "\n\n" . '[embed]https://www.youtube.com/watch?v=3H0aBxXzgKg[/embed]' . "\n\n" . $ipsums[4]  . "\n\n" . $ipsums[5] . "\n\n" . $ipsums[0] . "\n\n" . $ipsums[1],
      'post_excerpt' => $ipsums[2],
      //'comment_status' => 'open',
      'intel_demo' => array(
        'terms' => array(-3, -7, -8, -12),
        'url' => 'intelligence/demo/blog/charlie',
      ),
    );

    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_author' => -3,
      'post_type' => 'post',
      'post_name' => 'intelligence/demo/blog/delta',
      'post_title' => Intel_Df::t('Demo Post D'),
      'post_content' => $ipsums[3] . "\n\n" . '[embed]https://www.youtube.com/watch?v=CKSI5oxnC3g[/embed]' . "\n\n" . $ipsums[4] . "\n\n" . $ipsums[5]  . "\n\n" . $ipsums[0] . "\n\n" . $ipsums[1],
      'post_excerpt' => $ipsums[3],
      //'comment_status' => 'open',
      'intel_demo' => array(
        'terms' => array(-4, -7, -8, -12),
        'url' => 'intelligence/demo/blog/delta',
      ),
    );

    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_author' => -3,
      'post_type' => 'post',
      'post_name' => 'intelligence/demo/blog/echo',
      'post_title' => Intel_Df::t('Demo Post E'),
      'post_content' => $ipsums[4] . "\n\n" . $ipsums[5]  . "\n\n" . '[embed]https://www.youtube.com/watch?v=CKSI5oxnC3g[/embed]' . "\n\n" . $ipsums[0]  . "\n\n" . $ipsums[1] . "\n\n" . $ipsums[2] . "\n\n" . $ipsums[3],
      'post_excerpt' => $ipsums[4],
      //'comment_status' => 'open',
      'intel_demo' => array(
        'terms' => array(-5, -7, -8, -12),
        'url' => 'intelligence/demo/blog/echo',
      ),
    );

    $content0 =  '<div class="contact-wrapper">' . "\n";
    $content0 .= '  <div class="col-left" style="margin-bottom: 5em;">' . "\n";
    $content0 .= "    <p class=\"lead\">{$ipsums_s[0]}</p> \n\n {$ipsums[3]}";
    $content0 .= '  </div>' . "\n";
    $content0 .= '  <div class="col-right">' . "\n";
    $content0 .= '    <h3>Get it now!</h3>' . "\n";
    //$content .= '    [intel_form name="intel_demo_offer_form"]' . "\n";
    $content1 = '  </div>' . "\n";
    $content1 .= '</div>' . "\n";
    // Demo Offer A
    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_type' => 'page',
      'post_title' => 'Demo Offer A',
      'post_content' => $content0 . '    [intel_form name="intel_demo_offer_form" title="' . Intel_Df::t('Demo Offer Form') . '" redirect="intelligence/demo/download/alpha"]' . "\n" . $content1,
      'intel_demo' => array(
        'url' => 'intelligence/demo/offer/alpha',
      ),
    );

    // Demo Offer B
    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_type' => 'page',
      'post_title' => 'Demo Offer B',
      'post_content' => $content0 . '    [intel_form name="intel_demo_offer_form" title="' . Intel_Df::t('Demo Offer Form') . '" redirect="intelligence/demo/download/beta"]' . "\n" . $content1,
      'intel_demo' => array(
        'url' => 'intelligence/demo/offer/beta',
      ),
    );

    $content0 =  '<div class="contact-wrapper" style="text-align: center; margin-bottom: 20em;">' . "\n";
    $content0 .= " <p class=\"lead\">{$ipsums_s[1]}</p> \n\n";
    $content0 .= Intel_Df::t('Download') . "<br>\n";
      $l_options = array(
      'html' => 1,
    );
    $l_options = Intel_Df::l_options_add_class('icon-link', $l_options);
    //$content .= Intel_Df::l( '<i class="fa fa-arrow-circle-down" aria-hidden="true" style="font-size: 5em;"></i>', '/wp-content/plugins/intelligence/images/setup_intel_action.png', $l_options);
    $content .= '[intel_demo name="download_link_offer_a"]';
    $content1 = "\n\n";
    $content1 .= '</div>' . "\n";
    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_type' => 'page',
      'post_title' => 'Demo Offer A Download',
      'post_content' => $content0 . '[intel_demo name="download_link_offer_a"]' . $content1,
      'intel_demo' => array(
        'url' => 'intelligence/demo/download/alpha',
      ),
    );

    $i--;
    $post["$i"] = array(
      'ID' => $i,
      'post_type' => 'page',
      'post_title' => 'Demo Offer B Download',
      'post_content' => $content0 . '[intel_demo name="download_link_offer_b"]' . $content1,
      'intel_demo' => array(
        'url' => 'intelligence/demo/download/beta',
      ),
    );

    // allow other plugins to add posts
    $post = apply_filters('intel_demo_posts', $post);

    $post_defaults = array(
      'ID' => -99,
      'post_author' => -1,
      'post_date' => current_time('mysql'),
      'post_date_gmt' => current_time('mysql', 1),
      'post_content' => '',
      'post_title' => Intel_Df::t("Page Title"),
      'post_excerpt' => "",
      'post_status' => 'static',
      'comment_status' => 'closed',
      'ping_status' => 'open',
      'post_name' => 'intelligence/demo',
      'guid' => get_bloginfo('wpurl') . '/' . 'intelligence/demo',
      'post_type' => 'page',
      'comment_count' => 0,
      //'post_modified' => current_time('mysql'),
      //'post_modified_gmt' => current_time('mysql', 1),
      'filter' => 'raw',
    );

    /*

    $post['-4']['post_content'] = $post['-4']['post_excerpt'] = $ipsums[0];
    $post['-5']['post_content'] = $post['-5']['post_excerpt'] = $ipsums[4];
    $post['-6']['post_content'] = $post['-6']['post_excerpt'] = $ipsums[1];

    $post['-4']['post_content'] .= "\n\n" . $ipsums[1] . "\n\n" . $ipsums[2] . "\n\n" . $ipsums[3];
    $post['-5']['post_content'] .= "\n\n" . '[embed]https://www.youtube.com/watch?v=A__S2YudnFI[/embed]' . "\n\n" . $ipsums[3] . "\n\n" . $ipsums[2]  . "\n\n" . $ipsums[1];
    $post['-6']['post_content'] .= "\n\n" . $ipsums[2] . "\n\n" . $ipsums[3]  . "\n\n" . $ipsums[4] . "\n\n" . $ipsums[0] . "\n\n" . $ipsums[1] . "\n\n" . $ipsums[2];

    */

    foreach ($post as $i => $p) {
      if (is_object($p)) {
        $p = (array)$p;
      }
      $posts[$i] = ($p + $post_defaults);
      if (
        (!isset($p['intel_demo']['overridable']) || $p['intel_demo']['overridable'])
        && !empty($demo_settings['posts'][$i]) && is_array($demo_settings['posts'][$i])
      ) {
        $posts[$i] = $demo_settings['posts'][$i] + $posts[$i];
      }
      $posts[$i] = (object)$posts[$i];
    }

    // allow other plugins to alter posts
    $posts = apply_filters('intel_demo_posts_alter', $posts);
  }

  return $posts;
};

function intel_demo_post_load($id = NULL, $options = array()) {
  $posts = intel_demo_posts($options);

  if ($id) {
    return !empty($posts[$id]) ? $posts[$id] : FALSE;
  }

  return $posts;
}

function intel_demo_the_posts($posts){
  global $wp;
  global $wp_query;
  global $wp_rewrite;

  global $intel_demo_page_url; // used to stop double loading

  if (isset($intel_demo_page_url)) {
    return $posts;
  }

  $menu_items = intel_demo_menu_items();

  $urls = intel_demo_urls();

  $url_index = array();
  foreach ($urls as $url => $v) {
    if (!empty($v['entity']['id']) && is_numeric($v['entity']['id'])) {
      $url_index[$v['entity']['id']] = $url;
    }
  }

  // determine current page url
  $page_url = '';
  if (!empty($wp->request)) {
    $page_url = strtolower($wp->request);
  }
  elseif (!empty($wp->query_vars['page_id'])) {
    $page_url = $wp->query_vars['page_id'];
  }

  if ( empty($intel_demo_page_url) && !empty($urls[$page_url]) ) {
    // stop interferring with other $posts arrays on this page (only works if the sidebar is rendered *after* the main page)
    $intel_demo_page_url = $page_url;

    $demo_settings = get_option('intel_demo_settings', intel_demo_settings_default());

    wp_enqueue_script( 'intel_demo', INTEL_URL . 'js/intel.demo.js', array( 'jquery' ), intel()->get_version(), false );
    wp_enqueue_style( 'intel_demo', INTEL_URL . 'css/intel.demo.css', array(), intel()->get_version(), 'all');
    wp_enqueue_style( 'intel_wpb-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );

    if (!empty($demo_settings['css_injection'])) {
      wp_add_inline_style( 'intel_demo', $demo_settings['css_injection'] );
    }
    if (!empty($demo_settings['js_injection'])) {
      wp_add_inline_script( 'intel_demo', $demo_settings['js_injection'] );
    }

    // suppress warnings on demo pages;
    error_reporting(E_ERROR);


    $wp_query_data = !empty($urls[$intel_demo_page_url]['wp_query']) ? $urls[$intel_demo_page_url]['wp_query'] : array();
    $posts_data = array();
    $blog_ids = $urls['intelligence/demo/blog']['entity']['id'];
    if ($urls[$intel_demo_page_url]['entity']) {
      $ids = $urls[$intel_demo_page_url]['entity']['id'];
      if (!is_array($ids)) {
        $ids = array($ids);
      }
      foreach ($ids as $id) {
        $posts_data[] = intel_demo_post_load($id);
      }
    }

    // build menu
    $menu = '';
    foreach ($menu_items as $i) {
      if ($menu) {
        $menu .= ' | ';
      }
      $menu .= Intel_Df::l($i['text'], $i['path'], !empty($i['options']) ? $i['options'] : array());
    }

    $demo_terms = intel_demo_term_load();

    $posts = array();
    $post_count = count($posts_data);

    // GA tracking code embed
    $parse_url = Intel_Df::drupal_parse_url($_SERVER['REQUEST_URI']);
    $intel = intel();
    $intel_embed_ga_tracking_code = get_option('intel_embed_ga_tracking_code', '');
    if (!empty($_GET['action'])) {
      if ($_GET['action'] == 'intel_embed_ga_tracking_code') {
        if (current_user_can('admin intel') && !empty($_GET['nonce']) && wp_verify_nonce( $_GET['nonce'], 'intel_embed_ga_tracking_code' )) {
          update_option('intel_embed_ga_tracking_code', 'analytics');

          // set cookie to signal to dispay ack message
          intel_setcookie('intel_demo_embed_ga_tracking_code_enabled', '1');

          // setup redirect
          $l_options = Intel_Df::l_options_add_query($parse_url['query']);
          unset($l_options['query']['action']);
          unset($l_options['query']['nonce']);
          Intel_Df::drupal_goto($parse_url['path'], $l_options);
          exit;
        }
      }
    }
    if (!empty($_COOKIE['intel_demo_embed_ga_tracking_code_enabled'])) {
      $msg = Intel_Df::t('Google Analytics tracking code is now embeded by Intelligence.');
      $notice_vars = array(
        'type' => 'success',
        'message' => $msg,
      );
      $embed_notice = Intel_Df::theme('wp_notice', $notice_vars);
      intel_deletecookie('intel_demo_embed_ga_tracking_code_enabled');
    }

    if (!$intel_embed_ga_tracking_code) {
      $msg = Intel_Df::t('Google Analytics tracking code has not been detected.');
      if (current_user_can('admin intel')) {
        $msg .= ' ' . Intel_Df::t('Would you like Intelligence to embed the tracking code?');
        $l_options = Intel_Df::l_options_add_class('button');
        $l_options['query'] = $parse_url['query'];
        $l_options['query']['action'] = 'intel_embed_ga_tracking_code';
        $l_options['query']['nonce'] = wp_create_nonce( 'intel_embed_ga_tracking_code' );
        $msg .= '<br><br>' . Intel_Df::l(Intel_Df::t('Yes, enable tracking code embed'), $parse_url['path'], $l_options);
      }
      $notice_vars = array(
        'type' => 'error',
        'message' => $msg,
        'class' => array(
          'ga-embed-error'
        ),
      );
      $embed_notice = Intel_Df::theme('wp_notice', $notice_vars);
    }


    foreach ($posts_data as $i => $post_data) {
      // create a fake virtual page
      $post = new stdClass;
      $post_data = (array)$post_data;
      foreach ($post_data as $k => $v) {
        $post->{$k} = $v;
      }
      //$_post = new WP_Post( $post );
      $_post = $post;

      //$excerpt = get_the_excerpt( $_post );
      //$excerpt = apply_filters( 'get_the_excerpt', $post->post_excerpt, $post );

      $content = '';
      $excerpt = '';
      if ($post_count > 1) {
        $wp_query->query_vars['title'] = 'bob';
      }
      if ($i == 0) {
        $content .= '<div class="intel-demo-menu">' . $menu . '</div>';
        $excerpt .= '<div class="intel-demo-menu">' . $menu . '</div>';
      }

      $content .= '<div class="intel-demo-article">';

      $notice_vars = array(
        'message' => Intel_Df::t('A Google Analytics embed code has not been detected.'),
      );
      $content .= $embed_notice;

      if ($_post->post_type == 'post') {
        if ($post_count == 1) {
          $content .= '<div class="intel-demo-featured-image"><img src="http://via.placeholder.com/1280x720&text=Featured+Image"></div>';
        }
        else {
          $excerpt .= '<div class="intel-demo-featured-image"><img src="http://via.placeholder.com/1280x360&text=Featured+Image"></div>';
        }
      }

      if ($_post->post_type == 'post' && $post_count == 1) {
        $content .= '<div class="intel-demo-social-share social-wrapper social-share">' . intel_demo_social_share_buttons($post) . '</div>';
      }

      if ($_post->post_type == 'post' && $post_count == 1) {
        $terms = array(
          'category' => array(),
          'post_tag' => array(),
        );

        if (!empty($_post->intel_demo['terms'])) {
          foreach ($_post->intel_demo['terms'] as $tid) {
            if (!empty($demo_terms[$tid])) {
              $t = $demo_terms[$tid];
              $terms[$t->taxonomy][] = $t->name;
            }
          }
        }
        $content .= '<div class="intel-demo-terms terms-wrapper">';
        $content .= '<div class="intel-demo-term-category term-wrapper term-category">' . Intel_Df::t('Categories') . ': ';
        if (!empty($terms['category'])) {
          $content .= implode(', ', $terms['category']);
        }
        else {
          $content .= Intel_Df::t('(none)');
        }
        $content .= '</div>';
        $content .= '<div class="intel-demo-term-tag term-wrapper term-tag">' . Intel_Df::t('Tags') . ': ';
        if (!empty($terms['post_tag'])) {
          $content .= implode(', ', $terms['post_tag']);
        }
        else {
          $content .= Intel_Df::t('(none)');
        }
        $content .= '</div></div>';

      }

      $content .= '<div class="intel-demo-body">' . $_post->post_content . '</div>';
      $excerpt .= '<div class="intel-demo-body">' . $_post->post_excerpt . '</div>';

      // close intel-demo-article
      $content .= '</div>';

      if ($_post->post_type == 'post' && $post_count == 1) {
        $content .= '<div class="intel-demo-social-share social-wrapper social-share">' . intel_demo_social_share_buttons($post) . '</div>';
      }

      if ($_post->post_type == 'post') {
        if ($post_count == 1) {
          if (!empty($_post->intel_demo['terms']) && in_array(-12, $_post->intel_demo['terms'])) {
            $content .= '<div class="intel-demo-cta">[intel_demo name="cta_offer_b_bottom"]</div>';
          }
          else {
            $content .= '<div class="intel-demo-cta">[intel_demo name="cta_offer_a_bottom"]</div>';
          }

          $content .= '<div class="intel-demo-nav-links">';

          $latest_post = intel_demo_post_load($blog_ids[0]);
          $oldest_post = intel_demo_post_load($blog_ids[count($blog_ids) - 1]);

          $l_options = array(
            'html' => 1,
            'attributes' => array(
              'class' => 'nav-oldest'
            ),
          );
          $content .= '<div class="intel-demo-nav-previous">';
          //$content .= Intel_df::l('&lt;&lt; ' . str_replace('Demo ', '', $oldest_post->post_title), $oldest_post->post_name, $l_options);
          $content .= Intel_df::l('&lt;&lt; ' . Intel_Df::t('Oldest'), $oldest_post->post_name, $l_options);
          if ($_post->ID != $blog_ids[count($blog_ids) - 1]) {
            $next_post = intel_demo_post_load($_post->ID + 1);
            $l_options['attributes']['class'] = "nav-prev";
            //$content .= ' ' . Intel_df::l('&lt; ' .  str_replace('Demo ', '', $next_post->post_title), $next_post->post_name, $l_options);
            $content .= ' &nbsp;' . Intel_df::l('&lt; ' . Intel_Df::t('Prev'), $next_post->post_name, $l_options);

          }
          $content .= '</div>';

          $content .= '<div class="intel-demo-nav-next">';
          if ($_post->ID != $blog_ids[0]) {
            $next_post = intel_demo_post_load($_post->ID - 1);
            $l_options['attributes']['class'] = "nav-next";
            //$content .= Intel_df::l(str_replace('Demo ', '', $next_post->post_title) . ' &gt;', $next_post->post_name, $l_options);
            $content .= Intel_df::l(Intel_Df::t('Next') . ' &gt;', $next_post->post_name, $l_options);
          }
          $l_options['attributes']['class'] = "nav-latest";
          //$content .= ' ' . Intel_df::l(str_replace('Demo ', '', $latest_post->post_title) . ' &gt;&gt;', $latest_post->post_name, $l_options);
          $content .= ' &nbsp;' . Intel_df::l(Intel_Df::t('Latest') . ' &gt;&gt;', $latest_post->post_name, $l_options);

          $content .= '</div>';

          $content .= '</div>';

        }
      }


      $content .= '<div class="intel-demo-footer">';

      if ($post_count == 1) {
        $content .= '<div class="intel-demo-social-profile social-wrapper social-profile">' . intel_demo_social_profile_buttons($post) . '</div>';
      }

      $content .= '</div>';

      // if post list page, set content/excerpt fields based on settings
      if ($post_count > 1) {
        $_post->post_content = '';
        $_post->post_excerpt = '';
        if (empty($demo_settings['post_list_content_fields']) || $demo_settings['post_list_content_fields'] == 'content') {
          $_post->post_content = '<div class="intel-demo-post-content">' . $excerpt . '</div>';
        }
        if (empty($demo_settings['post_list_content_fields']) || $demo_settings['post_list_content_fields'] == 'excerpt') {
          $_post->post_excerpt = '<div class="intel-demo-post-content post-excerpt">' . $excerpt . '</div>';
        }
      }
      else {
        $_post->post_content = '<div class="intel-demo-post-content">' . $content . '</div>';
        $_post->excerpt = '';
      }

      $posts[] = $_post;
    }


    // configure wp_query to make this page look real
    $wp_query_defaults = array(
      'is_page' => true,
      'is_singular' => true,
      'is_home' => false,
      'is_archive' => false,
      'is_category' => false,
      'is_404' => false,
    );

    $wp_query_data += $wp_query_defaults;

    foreach ($wp_query_data as $k => $v) {
      $wp_query->{$k} = $v;
    }

    unset($wp_query->query["error"]);
    $wp_query->query_vars["error"]="";

    // remove permalink structure so demo blog posts will not follow site pattern
    $wp_rewrite->permalink_structure = '';

    add_filter( 'pre_option_permalink_structure' , 'intel_demo_option_permalink_structure', -10, 2 );

    add_filter( 'pre_option_intel_content_selector' , 'intel_demo_option_intel_content_selector', -10, 2 );
  }

  return $posts;
}

/* couldnt get this to work
function intel_demo_wp_title($title, $sep, $seplocation = NULL) {
  intel_d($title);
  intel_d($sep);
  intel_d($seplocation);
  return $title;
}
//add_filter('wp_title', 'intel_demo_wp_title', 10, 3);
*/

function intel_demo_social_share_buttons($post) {
  $urls_info = array(
    'facebook' => array(
      'hostpath' => 'https://www.facebook.com/sharer.php',
      'icon_class' => 'fa fa-facebook-square',
      'query' => array(
        'url' => 'u',
      ),
    ),
    'googleplus' => array(
      'hostpath' => 'https://plus.google.com/share',
      'icon_class' => 'fa fa-google-plus-square',
      'query' => array(
        'url' => 'url',
      ),
    ),
    'linkedin' => array(
      'hostpath' => 'https://www.linkedin.com/shareArticle',
      'icon_class' => 'fa fa-linkedin-square',
      'query' => array(
        'url' => 'url',
        'title' => 'title',
      ),
    ),
    'pinterest' => array(
      'hostpath' => 'https://pinterest.com/pin/create/bookmarklet/',
      'icon_class' => 'fa fa-pinterest-square',
      'query' => array(
        'url' => 'url',
        'title' => 'description',
        'img' => 'media',
      ),
    ),
    'twitter' => array(
      'hostpath' => 'https://twitter.com/intent/tweet',
      'icon_class' => 'fa fa-twitter-square',
      'query' => array(
        'url' => 'url',
        'title' => 'text',
      ),
    ),
  );
  $platforms = array(
    'twitter',
    'facebook',
    'googleplus',
    'linkedin',
    'pinterest'
  );
  $output = '';
  $intel = intel();
  foreach ($platforms as $platform) {
    $url_info = $urls_info[$platform];
    $url = '';
    if (!empty($url_info['query']['url'])) {
      $url .= $url ? '&' : '';
      $url .= $url_info['query']['url'] . '=' . $intel->base_url . $intel->base_path . $post->post_name;
    }
    if (!empty($url_info['query']['title'])) {
      $url .= $url ? '&' : '';
      $url .= $url_info['query']['title'] . '=' . $post->post_title;
    }

    if ($url) {
      $url = '?' . $url;
    }

    $url = $url_info['hostpath'] . $url;

    $l_option = array(
      'html' => 1,
    );
    $l_option = Intel_Df::l_options_add_target('_blank', $l_option);
    $l_option = Intel_Df::l_options_add_class(array('social-link','social-share'), $l_option);
    $output .= Intel_Df::l('<i class="' . $url_info['icon_class'] . ' social-icon" aria-hidden="true"></i>', $url, $l_option);
  }

  $output = '<div class="social-share-text social-text">' . Intel_Df::t('Share Post') . ' &raquo; </div><div class="social-share-buttons social-buttons">' . $output . '</div>';
  //$output = '<div class="social-wrapper social-share"><div class="social-share-text">' . Intel_Df::t('Share Post') . ' >> </div>' . $output . '</div>';

  return $output;
}

function intel_demo_social_profile_buttons($post) {
  $urls_info = array(
    'facebook' => array(
      'hostpath' => 'http://www.facebook.com/levelten',
      'icon_class' => 'fa fa-facebook-square',
    ),
    'googleplus' => array(
      'hostpath' => 'https://plus.google.com/share',
      'icon_class' => 'fa fa-google-plus-square',
    ),
    'linkedin' => array(
      'hostpath' => 'https://www.linkedin.com/shareArticle',
      'icon_class' => 'fa fa-linkedin-square',
    ),
    'pinterest' => array(
      'hostpath' => 'https://pinterest.com/pin/create/bookmarklet/',
      'icon_class' => 'fa fa-pinterest-square',
    ),
    'twitter' => array(
      'hostpath' => '//twitter.com/levelten',
      'icon_class' => 'fa fa-twitter-square',
    ),
    'youtube' => array(
      'hostpath' => 'http://www.youtube.com/user/resultsorientedweb?sub_confirmation=1',
      'icon_class' => 'fa fa-youtube-square',
    ),
  );
  $platforms = array(
    'twitter',
    'facebook',
    'googleplus',
    'linkedin',
    'youtube',
  );
  $output = '';
  $intel = intel();
  foreach ($platforms as $platform) {
    $url_info = $urls_info[$platform];

    $url = $url_info['hostpath'];

    $l_option = array(
      'html' => 1,
    );
    $l_option = Intel_Df::l_options_add_target('_blank', $l_option);
    $l_option = Intel_Df::l_options_add_class(array('social-link', 'social-share'), $l_option);
    $output .= Intel_Df::l('<i class="' . $url_info['icon_class'] . ' social-icon" aria-hidden="true"></i>', $url, $l_option);
  }


  $output = '<div class="social-profile-text social-text">' . Intel_Df::t('Follow Us') . ' &raquo; </div><div class="social-profile-buttons social-buttons">' . $output . '</div>';
  //$output = '<div class="social-wrapper social-profile">' . Intel_Df::t('Follow Us') . ' >> ' . $output . '</div>';


  return $output;
}



/**
 * Overrides WordPress options set in $intel_wp_config_options global.
 *
 * @param $value
 * @param $name
 * @return array|mixed|object|string|void
 */
function intel_demo_option_permalink_structure($value, $name) {
  return '%postname%';
}

add_shortcode('intel_demo', 'intel_demo_shortcode');
function intel_demo_shortcode($vars) {
  $demo_settings = intel_demo_settings();

  if (isset($demo_settings['shortcodes'][$vars['name']])) {
    return $demo_settings['shortcodes'][$vars['name']];
  }

  return '(' . Intel_Df::t('Shortcode not found') . ')';
}

/*
function intel_demo_post_link( $post_link, $post, $leavename ) {
  intel_d($post_link);
  if ( 'post' == $post->post_type) {
    return $post_link . 'hi';
  }

  return $post_link;
}
add_filter( 'post_type_link', 'intel_demo_post_link', 10, 3 );
*/

/**
 * Form Test function
 */
function intel_demo_contact_form($form, &$form_state, $options = array()) {
  $form_state['options'] = $options;

  $account = wp_get_current_user();
  
  $form_id = $form_state['build_info']['form_id'];
  $form_def = array(
    'formType' => 'intel_form',
    'formId' => $form_id,
    'formTitle' => ucwords(str_replace('_', ' ', $form_id)),
    'selector' => "form#{$form_id}",
    'trackView' => 1,
  );
  intel_add_page_intel_push(array('formtracker:trackForm', $form_def));

  $form['givenName'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('First name'),
    '#default_value' => !empty($account->user_firstname) ? $account->user_firstname : Intel_Df::t('Tommy'),
    //'#description' => Intel_Df::t('Input family name.'),
    '#required' => 1,
  );

  $form['familyName'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Last name'),
    '#default_value' => !empty($account->user_lastname) ? $account->user_lastname : Intel_Df::t('Tester'),
    //'#default_value' => !empty($defaults['test']) ? $defaults['test'] : '',
    //'#description' => Intel_Df::t('Input family name.'),
  );

  $form['email'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Email'),
    '#default_value' => !empty($account->user_email) ? $account->user_email : '',
    //'#default_value' => !empty($defaults['test']) ? $defaults['test'] : '',
    //'#description' => Intel_Df::t('Input family name.'),
    '#required' => 1,
  );

  $form['message'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Message'),
    '#default_value' => !empty($account->user_email) ? $account->user_email : '',
    //'#default_value' => !empty($defaults['test']) ? $defaults['test'] : '',
    //'#description' => Intel_Df::t('Input family name.'),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Submit'),
    '#prefix' => '<br>',
  );

  $form_id = $form_state['build_info']['form_id'];
  $form_uri = ':intel_form:' . $form_id;
  $form_title = '';
  if (!empty($form_state['options']['form_title'])) {
    $form_title = $form_state['options']['form_title'];
  }
  elseif (!empty($form_state['form_title'])) {
    $form_title = $form_state['form_title'];
  }
  else {
    $form_title = ucwords(str_replace('_', ' ', $form_id));
  }

  $def = array(
    'selector' => 'form#intel_demo_contact_form',
    'trackView' => get_option('intel_form_track_view_default', ''),
    'formType' => 'intel_form',
    'formTitle' => $form_title,
    'formId' => $form_id,
  );
  intel_add_page_intel_push(array('formtracker:trackForm', $def));

  return $form;
}

function intel_demo_contact_form_validate($form, &$form_state) {
  //$_SESSION['intel_weform_test']['time0'] = microtime (TRUE);
}

function intel_demo_contact_form_submit($form, &$form_state) {
  $vars = intel_demo_form_submit_intel_vars($form, $form_state);

  // process submission data
  intel_process_form_submission($vars);

  $msg = Intel_Df::t('Thank you for contacting us. We will get back to you shortly.');
  Intel_Df::drupal_set_message($msg, 'status');

  // since the form is embedded with a shortcode, the page has already built and
  // page alter already been sent
  // print io commands directly to page

  //$script = intel()->tracker->get_pushes_script();
  //print "\n$script";
}

function intel_demo_form_submit_intel_vars($form, &$form_state) {
  $values = $form_state['values'];

  $form_id = $form_state['build_info']['form_id'];

  $demo_settings = get_option('intel_demo_settings', intel_demo_settings_default());

  $demo_form_settings = array();
  if (!empty($demo_settings['forms'][$form_id])) {
    $demo_form_settings = $demo_settings['forms'][$form_id];
  }

  // get initialied var structure
  $vars = intel_form_submission_vars_default();

  // create pointer aliases
  $submission = &$vars['submission'];
  $track = &$vars['track'];

  // set visitor properties from webform values
  $vp_info = intel()->visitor_property_info();
  foreach ($values as $k => $v) {
    if (!empty($vp_info['data.' . $k])) {
      $vars['visitor_properties']['data.' . $k] = $v;
    }
  }
  //$vars['visitor_properties']

  // set type of submission, e.g. gravityform, cf7, webform
  $submission->type = 'intel_form';
  // if form type allows multiple form, set id of form that was submitted
  $submission->fid = $values['form_id'];
  // if form submision creates a submission record, set it here
  $submission->fsid = 0;
  //$submission->submission_uri = "/wp-admin/admin.php?page=gf_entries&view=entry&id={$submission->fid}&lid={$submission->fsid}";
  // set title of form
  if (!empty($form_state['options']['form_title'])) {
    $submission->form_title = $form_state['options']['form_title'];
  }
  elseif (!empty($form_state['form_title'])) {
    $submission->form_title = $form_state['form_title'];
  }
  else {
    $submission->form_title = ucwords(str_replace('_', ' ', $values['form_id']));
  }

  // set submission values

  // array of values not to be saved
  $ignore = array(
    'submit' => 1,
    'form_build_id' => 1,
    'form_token' => 1,
    'form_id' => 1,
    'op' => 1,
  );
  foreach ($form_state['values'] as $k => $v) {
    if (empty($ignore[$k])) {
      // stores values as part of submission data
      $vars['submission_values'][$k] = $v;
      // stored for hooks
      $vars['form_values'][$k] = $v;
    }
  }
  //intel_d($submission);
  //intel_d($form_state);
  //Intel_Df::watchdog('intel form_state.values', print_r($form_state['values'], 1));
  //Intel_Df::watchdog('intel submission', print_r($submission, 1));

  if (!empty($demo_form_settings['tracking_event_name'])) {
    $track['name'] = $demo_form_settings['tracking_event_name'];
  }
  else {
    $track['name'] = 'form_submission';
  }

  if (!empty($demo_form_settings['tracking_event_value'])) {
    $track['value'] = $demo_form_settings['tracking_event_value'];
  }

  if (!empty($demo_form_settings['tracking_event_value_contact_exists'])) {
    $track['value_contact_exists'] = $demo_form_settings['tracking_event_value_contact_exists'];
  }

  /*
  $goals = intel_goal_load();
  if (!empty($goals['contact'])) {
    $track['name'] = 'form_submission__contact';
  }
  else {
    $track['name'] = 'form_submission';
  }
  */

  return $vars;
}

function intel_demo_offer_form($form, &$form_state, $options = array()) {
  $form = intel_demo_contact_form($form, $form_state, $options);
  unset($form['message']);
  return $form;
}

function intel_demo_offer_form_submit($form, &$form_state) {

  $vars = intel_demo_form_submit_intel_vars($form, $form_state);

  // process submission data
  intel_process_form_submission($vars);

  if (!empty($form_state['options']['redirect'])) {
    $path = $form_state['options']['redirect'];
  }
  else {
    $path = Intel_Df::current_path();
    $path = str_replace('/offer/', '/offer-response/', $path);
  }

  // append cache busting query
  if (function_exists('intel_cache_busting_url')) {
    $path = intel_cache_busting_url($path);
  }
  //$url = Intel_Df::url($path);
  intel_save_flush_page_intel_pushes();

  Intel_Df::drupal_goto($path);
}


function intel_demo_option_intel_content_selector($value, $name) {
  return 'div.intel-demo-article';
}