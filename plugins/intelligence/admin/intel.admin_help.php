<?php
/**
 * @file
 * Administration of visitors
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */


function intel_help_page() {
  $output = '';
  $output .= '<article>';
  $output .= '<div class="card"><div class="card-block">';

  $output .= '<p class="lead">';
  $output .= Intel_Df::t('Intelligence is designed to extend Google Analytics to track meaningful visitor interactions on your website.');
  $output .= ' ' . Intel_Df::t('This tutorial will walk you through the essentials of extending Google Analytics using Intelligence to create results oriented analytics.');
  $output .= '</p>';

  $output .= '</div></div>';
  $output .= '</article>';

  return $output;
}

function intel_help_start_page() {
  $output = '';

  $output .= '<article>';
  $output .= '<div class="card"><div class="card-block">';

  $video_width = 'width="560"';
  $video_height = 'height="315"';

  $output .= '<p class="lead">';
  $output .= Intel_Df::t('Intelligence is designed to extend Google Analytics to track meaningful visitor interactions on your website.');
  $output .= ' ' . Intel_Df::t('The JumpStart will walk you through the essentials of extending Google Analytics using Intelligence to create results oriented analytics.');
  $output .= '</p>';

  $output .= '<h3>' . Intel_Df::t('Overview') . '</h3>';

  $output .= '<h4>' . Intel_Df::t('1. Actionable Analytics') . '</h4>';
  $output .= '  <div class="row">';
  $output .= '    <div class="col-md-7">';
  $output .= '      <iframe ' . $video_width . ' ' . $video_height . ' src="https://www.youtube.com/embed/L9hrxPEkFwU?list=PLmUKQxGacjpCj85hvZoIbWkqnMVMhR5RO" frameborder="0" gesture="media" allowfullscreen></iframe>';
  $output .= '    </div>';
  $output .= '    <div class="col-md-5">';
  $output .= '    </div>';
  $output .= '  </div>';

  $output .= '<h4>' . Intel_Df::t('2. Automated Engagement Tracking') . '</h4>';
  $output .= '  <div class="row">';
  $output .= '    <div class="col-md-7">';
  $output .= '      <iframe ' . $video_width . ' ' . $video_height . ' src="https://www.youtube.com/embed/Q-ygSem0cjc?list=PLmUKQxGacjpCj85hvZoIbWkqnMVMhR5RO" frameborder="0" gesture="media" allowfullscreen></iframe>';
  $output .= '    </div>';
  $output .= '    <div class="col-md-5">';
  $output .= '    </div>';
  $output .= '  </div>';

  $output .= '<h4>' . Intel_Df::t('3. Discovering Value') . '</h4>';
  $output .= '  <div class="row">';
  $output .= '    <div class="col-md-7">';
  $output .= '      <iframe ' . $video_width . ' ' . $video_height . ' src="https://www.youtube.com/embed/iRng2B-_D98?list=PLmUKQxGacjpCj85hvZoIbWkqnMVMhR5RO" frameborder="0" gesture="media" allowfullscreen></iframe>';
  $output .= '    </div>';
  $output .= '    <div class="col-md-5">';
  $output .= '    </div>';
  $output .= '  </div>';

  $output .= '<h4>' . Intel_Df::t('4. Goals & Form Tracking') . '</h4>';
  $output .= '  <div class="row">';
  $output .= '    <div class="col-md-7">';
  $output .= '      <iframe ' . $video_width . ' ' . $video_height . ' src="https://www.youtube.com/embed/zKGgz1ljchg?list=PLmUKQxGacjpCj85hvZoIbWkqnMVMhR5RO" frameborder="0" gesture="media" allowfullscreen></iframe>';
  $output .= '    </div>';
  $output .= '    <div class="col-md-5">';
  $output .= '    </div>';
  $output .= '  </div>';

  $output .= '<h4>' . Intel_Df::t('5. Valuing Events') . '</h4>';
  $output .= '  <div class="row">';
  $output .= '    <div class="col-md-7">';
  $output .= '      <iframe ' . $video_width . ' ' . $video_height . ' src="https://www.youtube.com/embed/MHGNrn2KGss?list=PLmUKQxGacjpCj85hvZoIbWkqnMVMhR5RO" frameborder="0" gesture="media" allowfullscreen></iframe>';
  $output .= '    </div>';
  $output .= '    <div class="col-md-5">';
  $output .= '    </div>';
  $output .= '  </div>';

  $output .= '<h4>' . Intel_Df::t('6. Intelligence Events') . '</h4>';
  $output .= '  <div class="row">';
  $output .= '    <div class="col-md-7">';
  $output .= '      <iframe ' . $video_width . ' ' . $video_height . ' src="https://www.youtube.com/embed/N-Sama8h7xw?list=PLmUKQxGacjpCj85hvZoIbWkqnMVMhR5RO" frameborder="0" gesture="media" allowfullscreen></iframe>';
  $output .= '    </div>';
  $output .= '    <div class="col-md-5">';
  $output .= '    </div>';
  $output .= '  </div>';

  $output .= '<h4>' . Intel_Df::t('7. Event Tag Attributes') . '</h4>';
  $output .= '  <div class="row">';
  $output .= '    <div class="col-md-7">';
  $output .= '      <iframe ' . $video_width . ' ' . $video_height . ' src="https://www.youtube.com/embed/rbnRZPDRVUQ?list=PLmUKQxGacjpCj85hvZoIbWkqnMVMhR5RO" frameborder="0" gesture="media" allowfullscreen></iframe>';
  $output .= '    </div>';
  $output .= '    <div class="col-md-5">';
  $output .= '    </div>';
  $output .= '  </div>';


  // article close
  $output .= '</div></div>';
  $output .= '</article>';

  return $output;
}

function intel_help_demo_page() {
  $output = '';

  $demo_mode = get_option('intel_demo_mode', 0);
  $demo_settings = get_option('intel_demo_settings', array());

  $imapi_host = intel_get_imapi_url('host');

  $ga_tid = get_option('intel_ga_tid', '');

  // current user is tracking is excluded notice
  if (is_callable('intel_is_current_user_tracking_excluded') && intel_is_current_user_tracking_excluded()) {
    $notice_vars = array(
      'inline' => 1,
      'type' => 'warning',
    );
    $notice_vars['message'] = Intel_Df::t('Your user role is set to be excluded from tracking.');
    $notice_vars['message'] .= ' ' . Intel_Df::t('You can test tracking on Intelligence demo pages but any interactions on non demo pages will not be tracked.');

    $output .= '<div class="alert alert-info">' . $notice_vars['message'] . '</div>';
  }


  $output .= '<div class="card">';
  $output .= '<div class="card-block clearfix">';

  $output .= '<p class="lead">';
  $output .= Intel_Df::t('This demo generates a small website using spoofed pages enabling you to explore how Intelligence works.');
  //$output .= ' ' . Intel_Df::t('This tutorial will walk you through the essentials of extending Google Analytics using Intelligence to create results oriented analytics.');
  $output .= '</p>';

  /*
  $l_options = Intel_Df::l_options_add_class('btn btn-info');
  $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path(), $l_options);
  $output .= Intel_Df::l( Intel_Df::t('Demo settings'), 'admin/config/intel/settings/general/demo', $l_options) . '<br><br>';
  */

  $output .= '<div class="row">';
  $output .= '<div class="col-md-6">';
  $output .= '<p>';
  $output .= '<h3>' . Intel_Df::t('First') . '</h3>';
  $output .= Intel_Df::t('Launch Google Analytics to see interactions in real-time:');
  $output .= '</p>';

  $output .= '<div>';
  $l_options = Intel_Df::l_options_add_target('ga');
  $l_options = Intel_Df::l_options_add_class('btn btn-info m-b-_5', $l_options);
  $url = 	$url = intel_get_ga_report_url('rt_event');
  $output .= Intel_Df::l( Intel_Df::t('View real-time events'), $url, $l_options);

  $output .= '<br>';

  $l_options = Intel_Df::l_options_add_target('ga');
  $l_options = Intel_Df::l_options_add_class('btn btn-info m-b-_5', $l_options);
  $url = 	$url = intel_get_ga_report_url('rt_goal');
  $output .= Intel_Df::l( Intel_Df::t('View real-time conversion goals'), $url, $l_options);
  $output .= '</div>';

  $output .= '</div>'; // end col-x-6
  $output .= '<div class="col-md-6">';

  $output .= '<p>';
  $output .= '<h3>' . Intel_Df::t('Next') . '</h3>';
  $output .= Intel_Df::t('Go to the demo site and interact with elements such as links and forms:');
  $output .= '</p>';

  $output .= '<div>';

  $l_options = Intel_Df::l_options_add_target('intel_demo');
  $l_options = Intel_Df::l_options_add_class('btn btn-info m-b-_5', $l_options);
  $output .= Intel_Df::l(Intel_Df::t('Demo home page'), 'intelligence/demo', $l_options);

  $output .= '<br>';

  $imapi_url_obj = intel_get_imapi_url('obj');
  $imapi_url_obj['path'] = '/property/' . $ga_tid . '/demo';

  $l_options = Intel_Df::l_options_add_target('intel_demo_referrer');
  $l_options = Intel_Df::l_options_add_class('btn btn-info m-b-_5', $l_options);
  $l_options = Intel_Df::l_options_add_query(array('base_urlpath' => intel()->base_url . intel()->base_path_front), $l_options);
  $output .= Intel_Df::l( Intel_Df::t('Demo referrers page'), http_build_url($imapi_url_obj), $l_options);



  $output .= '</div>';

  $output .= '</div>'; // end col-x-6
  $output .= '</div>'; // end row

  $output .= '</div>'; // end card-block
  $output .= '</div>'; // end card

  // Demo mode alert
  $notice_vars = array(
    'inline' => 1,
    'type' => 'info',
  );
  $mode = $demo_mode ? __('enabled') : __('disabled');
  $notice_vars['message'] = Intel_Df::t('Demo pages for anonymous users are currently ') . '<strong>' . $mode . '</strong>.';
  $l_options = Intel_Df::l_options_add_class('btn btn-default');
  $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path(), $l_options);
  $notice_vars['message'] .= ' ' . Intel_Df::l( Intel_Df::t('Change demo settings'), 'admin/config/intel/settings/general/demo', $l_options);

  //$output .= Intel_Df::theme('wp_notice', $notice_vars);

  //$output .= '<div class="alert alert-default">' . $notice_vars['message'] . '</div>';

  $output .= '<div class="card">';
  $output .= '<div class="card-block clearfix">';
  $output .= $notice_vars['message'];
  $output .= '</div>'; // end card-block
  $output .= '</div>'; // end card

  //$output .= '<br><br>' . Intel_Df::l(Intel_Df::t('Demo Blog Post A'), 'intelligence/demo/blog/alpha');

  return $output;
}

function intel_help_tutorial_page() {
  $output = '';

  $output .= '<article>';
  $output .= '<div class="card"><div class="card-block">';

  $output .= '<p class="lead">';
  $output .= Intel_Df::t('Intelligence is designed to extend Google Analytics to track meaningful visitor interactions on your website.');
  $output .= ' ' . Intel_Df::t('This tutorial will walk you through the essentials of extending Google Analytics using Intelligence to create results oriented analytics.');
  $output .= '</p>';

  $output .= '<h3>' . Intel_Df::t('Extending Google Analytics') . '</h3>';
  $output .= '<p>';
  $output .= Intel_Df::t('The default installation of Google Analytics tracks everything based on pageviews.');
  $output .= ' ' . Intel_Df::t('Pageviews provide a lot of default metrics such as sessions, page hits and time on site.');
  $output .= ' ' . Intel_Df::t('However, the default installation does not track more meaningful interactions such as when someone clicks on an important link or submits a form.');
  $output .= '</p>';

  $output .= '<p>';
  $output .= Intel_Df::t('GA provides several advanced features such as events and goals that can be used to provide more insightful data.');
  $output .= ' ' . Intel_Df::t('Intelligence helps streamline implementing these advanced features on your site.');
  $output .= ' ' . Intel_Df::t('The key is to understand how Intelligence events can be leveraged to track Google Analytics events and goals.');
  $output .= '</p>';

  $output .= '<p>';
  $output .= ' ' . Intel_Df::t('For this tutorial we will want to view the events we are sending to Google Anatlyics.');
  $output .= ' ' . Intel_Df::t('You can view events in near realtime in GA reports.');
  $output .= '<br><br><label>' . Intel_Df::t('Launch the Google Analytics realtime events report:') . '</label>';
  $l_options = Intel_Df::l_options_add_target('ga');
  $l_options = Intel_Df::l_options_add_class('btn btn-info', $l_options);
  $url = "https://analytics.google.com/analytics/web/#realtime/rt-event/" . intel_get_ga_profile_slug() . "/%3Fmetric.type%3D5/";
  $output .= '<br>' . Intel_Df::l( Intel_Df::t('Google Analytics real-time events report'), $url, $l_options);
  $output .= '</p>';

  $output .= '<h3>' . Intel_Df::t('Pre-defined events') . '</h3>';
  $output .= '<p>';
  $output .= Intel_Df::t('Intelligence provides several pre-defined events that automatically start tracking after installation.');
  $output .= ' ' . Intel_Df::t('You may already see some of these events in the realtime events report.');
  $output .= ' ' . Intel_Df::t('Lets look at how these work.');
  $output .= '</p>';

  $output .= '<div class="alert alert-info">';
  $output .= '<label>' . Intel_Df::t('Try it: triggering link events') . '</label>';
  $items = array();
  $l_options = Intel_Df::l_options_add_target('wp_admin');
  $items[] = Intel_Df::t('Add a new page to your site to use as a sandbox for this tutorial by going to !link', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Pages > Add new'), 'wp-admin/post-new.php?post_type=page', $l_options)
  ));
  $items[] = Intel_Df::t('Set the page title to "Intelligence Sandbox" so that the permalink slug is set to "intelligence-sandbox" after the page is publish.');
  $input = '<textarea rows="6" style="width: 100%">';
  $l_options = Intel_Df::l_options_add_target('_blank');
  $input  .= "<strong>" . Intel_Df::t('File download link') . ":</strong>\n";
  $input  .= Intel_Df::l( Intel_Df::t('Download now'), '/wp-content/plugins/intelligence/images/setup_intel_action.png', $l_options);
  $input  .= "\n\n<strong>" . Intel_Df::t('External link') . ":</strong>\n";
  $input  .= Intel_Df::l( Intel_Df::t('Visit Intelligence for WordPress'), 'http://intelligencewp.com', $l_options);
  $input  .= "\n\n<strong>" . Intel_Df::t('Email link') . ":</strong>\n";
  $input  .= Intel_Df::l( Intel_Df::t('Email us at info@example.com'), 'mailto:info@example.com', $l_options);
  $input  .= "\n\n<strong>" . Intel_Df::t('Telephone link') . ":</strong>\n";
  $input  .= Intel_Df::l( Intel_Df::t('Call Us at 214-555-1212'), 'tel:214-555-1212', $l_options);
  $input  .= '</textarea>';
  $items[] = Intel_Df::t('Copy and past the following content into the content edit input box as text so the links are clickable:') . $input;
  $items[] = Intel_Df::t('Click the "Publish" button to create the page.');
  $l_options = Intel_Df::l_options_add_target('intel_sb_page');
  $items[] = Intel_Df::t('View the !sb_link and click on the links. Notice the events coming through in the GA realtime events report.', array(
    '!sb_link' => Intel_Df::l( Intel_Df::t('sandbox page'), 'intelligence-sandbox', $l_options)
  )) . ' ' . Intel_Df::t('It typically takes 5-10 seconds for the event hit, category and action to show up in Google Analytics.');

  $output .= Intel_Df::theme('item_list', array('type' => 'ol', 'items' => $items));
  $output .= '</div>';

  $output .= '<p>';
  $output .= Intel_Df::t('The pre-defined events are provided by Intelligence add-ons and scripts.');
  $output .= ' ' . Intel_Df::t('The events triggered in the above exercise were provided by the LinkTracker script.');
  $output .= ' ' . Intel_Df::t('You can enable more pre-defined events by installing add-ons or enabling scripts.');
  $l_options = Intel_Df::l_options_add_target('wp_admin_intel_events');
  $output .= ' ' . Intel_Df::t('You can view the available events in the Intelligence events admin at !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Intelligence > Settings > Events'), 'admin/config/intel/settings/intel_event', $l_options)
  ));
  $output .= '</p>';

  $output .= '<h3>' . Intel_Df::t('Custom events') . '</h3>';
  $output .= '<p>';
  $output .= ' ' . Intel_Df::t('Developers can programmatically add events in plugins and themes using the Intelligence event API.');
  $output .= ' ' . Intel_Df::t('If your not a developer, or just prefer to use the admin, you can create custom Intelligence events using WordPress\' admin.');
  $output .= '</p>';

  $output .= '<div class="alert alert-info">';
  $output .= '<label>' . Intel_Df::t('Try it: create a custom pageview event') . '</label><br>';
  $output .= Intel_Df::t('In this exercise we will trigger an event when someone views the Intelligence sandbox page.');
  $items = array();
  $l_options = Intel_Df::l_options_add_target('wp_admin');
  $items[] = Intel_Df::t('Add a new Intelligence event using by going to !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Intelligence > Settings > Events > Add event'), 'admin/config/intel/settings/intel_event/add', $l_options)
  ));
  $l_options = Intel_Df::l_options_add_query(array(
    'title' => Intel_Df::t('Tutorial sandbox view'),
    'key' => Intel_Df::t('tutorial_sandbox_view'),
    'enable_pages' => Intel_Df::t('intelligence-sandbox'),
  ), $l_options);
  $item2 = array(
    'data' => Intel_Df::t('Input the following into the fields on the Add event form or !link:', array(
      '!link' => Intel_Df::l( Intel_Df::t('autofill the form fields'), 'admin/config/intel/settings/intel_event/add', $l_options)
    )),
    'children' => array(
      '<label>' . Intel_Df::t('General > Title') . ':</label> <pre>' . Intel_Df::t('Tutorial sandbox view') . '</pre>',
      '<label>' . Intel_Df::t('General > Id') . ':</label> <pre>' . Intel_Df::t('tutorial_sandbox_view') . '</pre>',
      '<label>' . Intel_Df::t('Availability > Page list') . ':</label> <pre>' . Intel_Df::t('intelligence-sandbox') . '</pre>',
    ),
  );
  $items[] = $item2;
  $items[] = Intel_Df::t('Click the "Add event" button at the bottom of the form to create your event.');
  $l_options = Intel_Df::l_options_add_target('intel_sb_page');
  $items[] = Intel_Df::t('Trigger the event by going to the !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Intelligence sandbox page'), 'intelligence-sandbox', $l_options)
  ));
  $output .= Intel_Df::theme('item_list', array('type' => 'ol', 'items' => $items));
  $output .= '</div>';

  $output .= '<p>';
  $output .= Intel_Df::t('You just created an event that was only enabled on the sandbox page.');
  $output .= ' ' . Intel_Df::t('This style of event is useful to track when visitors hit an important page on your site.');
  $output .= ' ' . Intel_Df::t('You can use the availablity settings to enable an event on a single page, multiple pages or the entire website.');
  $output .= '</p>';

  $output .= '<p>';
  $output .= ' ' . Intel_Df::t('Often times you will want to track when a visitor preforms a specific interaction with a specific element of a page like the link events at the start of the tutorial.');
  $output .= ' ' . Intel_Df::t('You can create events that monitor specific page elements then trigger when a particular event happens like clicking a type of link or mousing over an image.');
  $output .= ' ' . Intel_Df::t('To setup this style of event we simply add trigger fields to the event defined in the event admin form.');
  $output .= ' ' . Intel_Df::t('This will cause the event to not trigger automatically when the page loads, but instead to bind to a selector designated page element and wait for the given trigger on event.');
  $output .= '</p>';

  $output .= '<div class="alert alert-info">';
  $output .= '<label>' . Intel_Df::t('Try it: create a custom page interaction event') . '</label><br>';
  $items = array();
  $l_options = Intel_Df::l_options_add_target('wp_admin');
  $items[] = Intel_Df::t('Add a new Intelligence event using by going to !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Intelligence > Settings > Events > Add event'), 'admin/config/intel/settings/intel_event/add', $l_options)
  ));
  $l_options = Intel_Df::l_options_add_query(array(
    'title' => Intel_Df::t('Tutorial link click'),
    'key' => Intel_Df::t('tutorial_link_click'),
    'selector' => Intel_Df::t('a.track-tutorial-link'),
    'on_event' => Intel_Df::t('click'),
    'enable_pages' => Intel_Df::t('intelligence-sandbox'),
  ), $l_options);
  $item2 = array(
    'data' => Intel_Df::t('Input the following into the fields on the Add event form or !link:', array(
      '!link' => Intel_Df::l( Intel_Df::t('autofill the form fields'), 'admin/config/intel/settings/intel_event/add', $l_options)
    )),
    'children' => array(
      '<label>' . Intel_Df::t('General > Title') . ':</label> <pre>' . Intel_Df::t('Tutorial link click') . '</pre>',
      '<label>' . Intel_Df::t('General > Id') . ':</label> <pre>' . Intel_Df::t('tutorial_link_click') . '</pre>',
      '<label>' . Intel_Df::t('Event trigger > Selector') . ':</label> <pre>' . Intel_Df::t('a.track-tutorial-link') . '</pre>',
      '<label>' . Intel_Df::t('Event trigger > On event') . ':</label> <pre>' . Intel_Df::t('click') . '</pre>',
      '<label>' . Intel_Df::t('Availability > Page list') . ':</label> <pre>' . Intel_Df::t('intelligence-sandbox') . '</pre>',
    ),
  );
  $items[] = $item2;
  $items[] = Intel_Df::t('Click the "Add event" button at the bottom of the form to create your event.');
  $input = '<textarea rows="4" style="width: 100%">';
  //$l_options = Intel_Df::l_options_add_target('tutorial');
  $l_options = Intel_Df::l_options_add_class('track-tutorial-link');
  $input  .= "<strong>" . Intel_Df::t('Tutorial link') . ":</strong>\n";
  $input  .= Intel_Df::l( Intel_Df::t('Click the tutorial link'), 'admin/help', $l_options);
  $input  .= '</textarea>';
  $items[] = Intel_Df::t('Edit the sandbox page to add the new link to be tracked to the bottom of the existing content:') . $input;
  $l_options = Intel_Df::l_options_add_target('intel_sb_page');
  $items[] = Intel_Df::t('View the !link and click on the tutorial link to trigger the event.', array(
    '!link' => Intel_Df::l( Intel_Df::t('sandbox page'), 'intelligence-sandbox', $l_options)
  ));
  $output .= Intel_Df::theme('item_list', array('type' => 'ol', 'items' => $items));
  $output .= '</div>';

  $output .= '<p>';
  $output .= ' ' . Intel_Df::t('Notice that the tutorial link tag contains the class "track-tutorial-link" and the event definition\'s event trigger selector is "a.track-tutorial-link" targeting links with that class.');
  $output .= ' ' . Intel_Df::t('If you have worked with WordPress JavaScript, you will likely recognize the event triggers as jQuery.');
  $l_options = Intel_Df::l_options_add_target('jQuery');
  $output .= ' ' . Intel_Df::t('If you are not familiar with jQuery, its fairily easy to pick up !selector_link and !on_link to track practically anything on a page.', array(
      '!selector_link' => Intel_Df::l( Intel_Df::t('selectors'), 'https://www.w3schools.com/jquery/jquery_selectors.asp', $l_options),
      '!on_link' => Intel_Df::l( Intel_Df::t('events'), 'https://www.w3schools.com/jquery/jquery_events.asp', $l_options),
    ));
  $output .= '</p>';

  $output .= '<h3>' . Intel_Df::t('Triggering Goals') . '</h3>';
  $output .= '<p>';
  $output .= Intel_Df::t('Intelligence configures Google Analytics goals so they can be triggered by events.');
  $output .= ' ' . Intel_Df::t('Combining the power of goals and events enables you to track almost anything as valued interactions.');
  $output .= ' ' . Intel_Df::t('For example, lets say the tutorial link we created was deemed so important that we wanted to trigger a goal.');
  $output .= ' ' . Intel_Df::t('We can do this by changing the event defintion mode from "Standard event" to "Goal event".');
  $output .= '</p>';

  $output .= '<div class="alert alert-info">';
  $output .= '<label>' . Intel_Df::t('Try it: trigger a goal event') . '</label><br>';
  $items = array();
  $l_options = Intel_Df::l_options_add_target('wp_admin');
  $items[] = Intel_Df::t('Edit the tutorial link click event by going to !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Intelligence > Settings > Events > Tutorial link click'), 'admin/config/intel/settings/intel_event/tutorial_link_click/edit', $l_options)
  ));
  $items[] = Intel_Df::t('Change the Google Analytics event fields > Mode to "Goal event"');
  $items[] = Intel_Df::t('Set the Google Analytics event fields > Goal dropdown to any available goal.');
  $items[] = Intel_Df::t('Click the "Save event" button at the bottom of the form save your changes.');
  $l_options = Intel_Df::l_options_add_target('intel_sb_page');
  $items[] = Intel_Df::t('View the !link and click on the tutorial link to trigger the event. You may need to refresh the page for the new event defintion to be loaded.', array(
    '!link' => Intel_Df::l( Intel_Df::t('sandbox page'), 'intelligence-sandbox', $l_options)
  ));
  $l_options = Intel_Df::l_options_add_target('ga');
  $url = "https://analytics.google.com/analytics/web/#realtime/rt-event/" . intel_get_ga_profile_slug() . "/%3Fmetric.type%3D5/";
  $url2 = "https://analytics.google.com/analytics/web/#realtime/rt-goal/" . intel_get_ga_profile_slug() . "/%3Fmetric.type%3D6/";
  $items[] = Intel_Df::t('In the Google Analytics !events_link switch to the !conv_link to review the triggered goal.', array(
    '!events_link' => Intel_Df::l( Intel_Df::t('realtime events report'), $url, $l_options),
    '!conv_link' => Intel_Df::l( Intel_Df::t('realtime conversions report'), $url2, $l_options),
  ));
  $output .= Intel_Df::theme('item_list', array('type' => 'ol', 'items' => $items));
  $output .= '</div>';

  $output .= '<p>';
  $output .= Intel_Df::t('Notice that by setting the mode to goal event, a goal name followed by a + sign was append to the event category.');
  $output .= ' ' . Intel_Df::t('This signals to Google Analytics to trigger the goal enabling any event to be able to trigger a goal.');
  $output .= '</p>';

  $output .= '<h3>' . Intel_Df::t('Summary') . '</h3>';
  $output .= '<p>';
  $output .= Intel_Df::t('This tutorial was a quick review of essentials for tracking valued interactions on your site.');
  $output .= ' ' . Intel_Df::t('Pre-defined events provided by the core Intelligence plugin, add-ons and scripts provide convenient out-of-the-box methods for tracking many common interactions.');
  $output .= ' ' . Intel_Df::t('Those seeking more custom and in-depth analysis will like want to mix in some custom defined events.');
  $output .= '</p>';

  $output .= '<p>';
  $output .= ' ' . Intel_Df::t('There are many more tools and options available in the Intelligence framework.');
  $l_options = Intel_Df::l_options_add_target('intelligencewp');
  $output .= ' ' . Intel_Df::t('A good place to dive into more advanced methods is the !link.', array(
      '!link' => Intel_Df::l( Intel_Df::t('Event Tracking Guide'), 'http://intelligencewp.com/wiki/event-tracking', $l_options)
    ));
  $output .= '</p>';



  $output .= '</div></div>';
  $output .= '</article>';

  return $output;
}

function intel_help_tutorial_2_page() {
  $output = '';

  $demo_mode = get_option('intel_demo_mode', 0);
  $demo_settings = get_option('intel_demo_settings', array());

  if (empty($demo_mode)) {
    $msg = Intel_Df::t('Demo is currently disabled. Go to demo settings to enable.');
    Intel_Df::drupal_set_message($msg, 'warning');
  }

  include_once INTEL_DIR . 'includes/class-intel-form.php';
  include_once INTEL_DIR . 'admin/intel.admin_demo.php';
  $options = array(
    'tutorial' => 1,
  );
  $demo_form = Intel_Form::drupal_get_form('intel_admin_demo_settings', $options);

  $output .= '<article>';
  $output .= '<div class="card"><div class="card-block">';

  $l_options = Intel_Df::l_options_add_class('btn btn-info');
  $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path(), $l_options);
  $output .= Intel_Df::l( Intel_Df::t('Demo settings'), 'admin/config/intel/settings/general/demo', $l_options) . '<br><br>';

  $output .= '<p>';
  $output .= ' ' . Intel_Df::t('For this tutorial we will want to view the events we are sending to Google Anatlyics.');
  $output .= ' ' . Intel_Df::t('You can view events in near realtime in GA reports.');
  $output .= '<br><br><label>' . Intel_Df::t('Launch the Google Analytics realtime events report:') . '</label>';
  $l_options = Intel_Df::l_options_add_target('ga');
  $l_options = Intel_Df::l_options_add_class('btn btn-info', $l_options);
  $url = "https://analytics.google.com/analytics/web/#realtime/rt-event/" . intel_get_ga_profile_slug() . "/%3Fmetric.type%3D5/";
  $output .= '<br>' . Intel_Df::l( Intel_Df::t('Google Analytics real-time events report'), $url, $l_options);
  $output .= '</p>';

  // Exercise: Create goal
  $goal_title = Intel_Df::t('ToFu registration');
  $output .= '<div class="alert alert-info">';
  $output .= '<label>' . Intel_Df::t('Try it: creating a goal') . '</label><br>';
  $output .= Intel_Df::t('In this exercise we will add a goal to track form submissions for educational offers like eBooks.');
  $items = array();
  $l_options = Intel_Df::l_options_add_target('wp_admin');
  $items[] = Intel_Df::t('Add a new Intelligence goal using by going to !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Intelligence > Settings > Events > Add event'), 'admin/config/intel/settings/goal/add', $l_options)
  ));
  $desc = Intel_Df::t('Top-of-the-Funnel lead submission generated by educational offers targeting visitors in the research stage of the buying cycle.');
  $l_options = Intel_Df::l_options_add_query(array(
    'title' => $goal_title,
    'description' => $desc,
    //'key' => Intel_Df::t('tofu_submission'),
  ), $l_options);
  $item2 = array(
    'data' => Intel_Df::t('Input the following into the fields on the Add goal form or !link:', array(
      '!link' => Intel_Df::l( Intel_Df::t('autofill the form fields'), 'admin/config/intel/settings/goal/add', $l_options)
    )),
    'children' => array(
      '<label>' . Intel_Df::t('Title') . ':</label> <pre>' . $goal_title . '</pre>',
      '<label>' . Intel_Df::t('Description') . ':</label> <pre>' . $desc . '</pre>',
    ),
  );
  $items[] = $item2;


  $items[] = Intel_Df::t('Click the "Add goal" button at the bottom of the form to create your event.');
  $l_options = Intel_Df::l_options_add_target('intel_sb_page');
  $output .= Intel_Df::theme('item_list', array('type' => 'ol', 'items' => $items));
  $output .= '</div>';


  // Exercise: Set form goal trigger

  $fields_inc = array(
    'form_build_id' => 1,
    'form_token' => 1,
    'form_id' => 1,
    'save' => 1,
    'general' => array(
      'intel_demo_mode' => 1,
    ),
  );

  //$demo_form['general']['intel_demo_mode']['#type'] = 'hidden';

  $fields = array(
    'forms' => array(
      'forms_intel_demo_offer_form' => 1,
    ),
  ) + $fields_inc;

  $form = $demo_form;
  foreach ($form as $k => $v) {
    if (substr($k, 0, 1) == '#') {
      continue;
    }
    if (!empty($fields[$k])) {
      $children = Intel_Df::element_children($form[$k]);
      foreach ($children as $kk) {
        if (empty($fields[$k][$kk])) {
          unset($form[$k][$kk]);
        }
      }
    }
    else {
      unset($form[$k]);
    }
  }

  // open collapsed fieldset
  $form['forms']['#collapsible'] = 0;
  $form['forms']['forms_intel_demo_offer_form']['#collapsible'] = 0;
  $form['forms']['forms_intel_demo_offer_form']['#collapsed'] = 0;

  $output .= '<div class="alert alert-info">';
  $output .= '<label>' . Intel_Df::t('Try it: set form submission goal') . '</label><br>';
  //$output .= Intel_Df::t('In this exercise we will add a goal to track form submissions for educational offers like eBooks.');
  $items = array();
  $l_options = array();
  $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path());
  $l_options['query']['focus'] = 'forms.forms_intel_demo_offer_form';
  $items[] = Intel_Df::t('Edit the offer form settings by going to !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Intelligence > Settings > General > Demo'), 'admin/config/intel/settings/general/demo', $l_options)
  ));
  $items[] = Intel_Df::t('Go to the fields for Form settings > Order Form.');
  $items[] = Intel_Df::t('Set the') . ' <label>' . Intel_Df::t('Tracking event') . '</label> ' . Intel_Df::t('to') . ' <pre>' . $goal_title . '</pre>';
  $output .= Intel_Df::theme('item_list', array('type' => 'ol', 'items' => $items));
  $output .= Intel_Df::render($form);
  $output .= '</div>';

  // Exercise: Landingpage view event

  $output .= '<div class="alert alert-info">';
  $output .= '<label>' . Intel_Df::t('Try it: create a custom pageview event') . '</label><br>';
  $output .= Intel_Df::t('In this exercise we will trigger an event when someone views demo offer landingpage.');
  $items = array();
  $l_options = Intel_Df::l_options_add_target('wp_admin');
  $items[] = Intel_Df::t('Add a new Intelligence event using by going to !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Intelligence > Settings > Events > Add event'), 'admin/config/intel/settings/intel_event/add', $l_options)
  ));
  $title = Intel_Df::t('Landingpage view');
  $key = Intel_Df::t('landingpage_view');
  $enable_pages = 'intelligence/demo/offer/*';
  $l_options = Intel_Df::l_options_add_query(array(
    'title' => $title,
    'key' => $key,
    'enable_pages' => $enable_pages,
  ), $l_options);
  $item2 = array(
    'data' => Intel_Df::t('Input the following into the fields on the Add event form or !link:', array(
      '!link' => Intel_Df::l( Intel_Df::t('autofill the form fields'), 'admin/config/intel/settings/intel_event/add', $l_options)
    )),
    'children' => array(
      '<label>' . Intel_Df::t('General > Title') . ':</label> <pre>' . $title . '</pre>',
      '<label>' . Intel_Df::t('General > Id') . ':</label> <pre>' . $key . '</pre>',
      '<label>' . Intel_Df::t('Availability > Page list') . ':</label> <pre>' . $enable_pages . '</pre>',
    ),
  );
  $items[] = $item2;
  $items[] = Intel_Df::t('Click the "Add event" button at the bottom of the form to create your event.');
  $l_options = Intel_Df::l_options_add_target('intel_sb_page');
  $items[] = Intel_Df::t('Trigger the event by going to a !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('demo offer landingpage'), 'intelligence/demo/offer/alpha' , $l_options)
  ));
  $output .= Intel_Df::theme('item_list', array('type' => 'ol', 'items' => $items));
  $output .= '</div>';

  // Exercise: CTA click event

  $output .= '<div class="alert alert-info">';
  $output .= '<label>' . Intel_Df::t('Try it: create a custom page interaction event') . '</label><br>';
  $items = array();
  $l_options = Intel_Df::l_options_add_target('wp_admin');
  $items[] = Intel_Df::t('Add a new Intelligence event using by going to !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Intelligence > Settings > Events > Add event'), 'admin/config/intel/settings/intel_event/add', $l_options)
  ));
  $title = Intel_Df::t('CTA click');
  $key = Intel_Df::t('cta_click');
  $mode_title = Intel_Df::t('Valued event');
  $mode = 'valued';
  $value = 1;
  $selector = 'a.cta-track';
  $on_event = 'click';
  $enable_pages = 'intelligence/demo/*';
  $l_options = Intel_Df::l_options_add_query(array(
    'title' => $title,
    'key' => $key,
    'mode' => $mode,
    'value' => $value,
    'selector' => $selector,
    'on_event' => $on_event,
    'enable_pages' => $enable_pages,
  ), $l_options);
  $item2 = array(
    'data' => Intel_Df::t('Input the following into the fields on the Add event form or !link:', array(
      '!link' => Intel_Df::l( Intel_Df::t('autofill the form fields'), 'admin/config/intel/settings/intel_event/add', $l_options)
    )),
    'children' => array(
      '<label>' . Intel_Df::t('General > Title') . ':</label> <pre>' . $title . '</pre>',
      '<label>' . Intel_Df::t('General > Id') . ':</label> <pre>' . $key . '</pre>',
      '<label>' . Intel_Df::t('Google Analytics event fields > Mode') . ':</label> <pre>' . $mode_title . '</pre>',
      '<label>' . Intel_Df::t('Google Analytics event fields > Value') . ':</label> <pre>' . $value . '</pre>',
      '<label>' . Intel_Df::t('Event trigger > Selector') . ':</label> <pre>' . $selector . '</pre>',
      '<label>' . Intel_Df::t('Event trigger > On event') . ':</label> <pre>' . $on_event . '</pre>',
      '<label>' . Intel_Df::t('Availability > Page list') . ':</label> <pre>' . $enable_pages . '</pre>',
    ),
  );
  $items[] = $item2;
  $items[] = Intel_Df::t('Click the "Add event" button at the bottom of the form to create your event.');
  $l_options = Intel_Df::l_options_add_target('demo');
  $items[] = Intel_Df::t('View a !link, scroll to the bottom and click on the call-to-action link to trigger the event.', array(
    '!link' => Intel_Df::l( Intel_Df::t('blog post'), 'intelligence/demo/blog/alpha', $l_options)
  ));
  $output .= Intel_Df::theme('item_list', array('type' => 'ol', 'items' => $items));
  $output .= '</div>';



  // Exercise: element overrides

  $fields_inc = array(
    'form_build_id' => 1,
    'form_token' => 1,
    'form_id' => 1,
    'save' => 1,
  );

  $fields = array(
      'shortcodes' => array(
        'shortcodes_download_link_offer_a' => 1,
        'shortcodes_download_link_offer_b' => 1,
      ),
    ) + $fields_inc;

  $form = $demo_form;
  foreach ($form as $k => $v) {
    if (substr($k, 0, 1) == '#') {
      continue;
    }
    if (!empty($fields[$k])) {
      $children = Intel_Df::element_children($form[$k]);
      foreach ($children as $kk) {
        if (empty($fields[$k][$kk])) {
          unset($form[$k][$kk]);
        }
      }
    }
    else {
      unset($form[$k]);
    }
  }

  // open collapsed fieldset
  $form['shortcodes']['#collapsible'] = 0;
  $form['shortcodes']['#collapsed'] = 0;
  $form['shortcodes']['shortcodes_download_link_offer_a']['#rows'] = 4;
  $form['shortcodes']['shortcodes_download_link_offer_b']['#rows'] = 4;

  $output .= '<div class="alert alert-info">';
  $output .= '<label>' . Intel_Df::t('Try it: customize event tracking attributes') . '</label><br>';
  //$output .= Intel_Df::t('In this exercise we will add a goal to track form submissions for educational offers like eBooks.');
  $items = array();
  $l_options = array();
  $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path());
  $l_options['query']['focus'] = 'forms.forms_intel_demo_offer_form';
  $items[] = Intel_Df::t('Edit the offer form settings by going to !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Admin > Intelligence > Settings > General > Demo'), 'admin/config/intel/settings/general/demo', $l_options)
  ));
  $items[] = Intel_Df::t('Go to the fields for Shortcodes > download_link_offer_a & _b.');
  $final_a = <<<EOL
<a href="/wp-content/plugins/intelligence/files/demo_brochure.pdf" title="Download" class="icon-link io-click-mode--valued io-click-value--1" data-io-title="Demo eBook A" data-io-uri=":isbn:978-1-939575-00-5">
  <i class="fa fa-arrow-circle-down" aria-hidden="true" style="font-size: 5em;"></i>
</a>
EOL;
  $final_b = <<<EOL
<a href="/wp-content/plugins/intelligence/files/demo_brochure.pdf" title="Download" class="icon-link" data-io-title="Demo eBook B" data-io-uri=":isbn:978-1-939575-00-6" data-io-click-mode="valued" data-io-click-value=".50">
  <i class="fa fa-arrow-circle-down" aria-hidden="true" style="font-size: 5em;"></i>
</a>
EOL;
  $item2 = array(
    'data' => Intel_Df::t('Add the following elements to the anchor tag for download_link_offer_a:'),
    'children' => array(
      '<label>' . Intel_Df::t('Class') . ':</label> <pre>' . 'io-click-mode--valued' . '</pre>',
      '<label>' . Intel_Df::t('Class') . ':</label> <pre>' . 'io-click-value--1' . '</pre>',
      '<label>' . Intel_Df::t('Attribute') . ':</label> <pre>' . 'data-io-title="Demo eBook A"' . '</pre>',
      '<label>' . Intel_Df::t('Attribute') . ':</label> <pre>' . 'data-io-uri=":isbn:978-1-939575-00-5"' . '</pre>' .
      '<br>' . Intel_Df::t('The final markup should be:') . '<pre>' . htmlspecialchars($final_a) . '</pre>',
    ),
  );
  $items[] = $item2;
  $item2 = array(
    'data' => Intel_Df::t('Add the following elements to the anchor tag for download_link_offer_b:'),
    'children' => array(
      '<label>' . Intel_Df::t('Attribute') . ':</label> <pre>' . 'data-io-click-mode="valued"' . '</pre>',
      '<label>' . Intel_Df::t('Attribute') . ':</label> <pre>' . 'data-io-click-value=".50"' . '</pre>',
      '<label>' . Intel_Df::t('Attribute') . ':</label> <pre>' . 'data-io-title="Demo eBook B"' . '</pre>',
      '<label>' . Intel_Df::t('Attribute') . ':</label> <pre>' . 'data-io-uri=":isbn:978-1-939575-00-6"' . '</pre>' .
      '<br>' . Intel_Df::t('The final markup should be:') . '<pre>' . htmlspecialchars($final_b) . '</pre>',
    ),
  );
  $items[] = $item2;
  $l_options = array();
  $l_options = Intel_Df::l_options_add_target('demo');
  $items[] = Intel_Df::t('Visit the !linka or !linkb and test the download link.', array(
    '!linka' => Intel_Df::l( Intel_Df::t('Offer A Download page'), 'intelligence/demo/download/alpha', $l_options),
    '!linkb' => Intel_Df::l( Intel_Df::t('Offer B Download page'), 'intelligence/demo/download/beta', $l_options),
  ));
  $output .= Intel_Df::theme('item_list', array('type' => 'ol', 'items' => $items));
  $output .= '<br>' . Intel_Df::render($form);
  $output .= '</div>';


  // close card, article
  $output .= '</div></div>';
  $output .= '</article>';

  return $output;
}