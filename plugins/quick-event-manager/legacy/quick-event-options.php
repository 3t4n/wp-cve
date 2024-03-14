<?php

function event_get_stored_options()
{
    $event = get_option( 'event_settings' );
    if ( !is_array( $event ) ) {
        $event = array();
    }
    $default = array(
        'active_buttons'        => array(
        'field1'  => true,
        'field2'  => true,
        'field3'  => true,
        'field4'  => true,
        'field5'  => true,
        'field6'  => true,
        'field7'  => true,
        'field8'  => true,
        'field9'  => true,
        'field10' => true,
        'field11' => true,
        'field12' => true,
        'field13' => false,
        'field14' => false,
    ),
        'summary'               => array(
        'field1' => 'checked',
        'field2' => 'checked',
        'field3' => 'checked',
    ),
        'label'                 => array(
        'field1'  => esc_html__( 'Short Description', 'quick-event-manager' ),
        'field2'  => esc_html__( 'Event Time', 'quick-event-manager' ),
        'field3'  => esc_html__( 'Venue', 'quick-event-manager' ),
        'field4'  => esc_html__( 'Address', 'quick-event-manager' ),
        'field5'  => esc_html__( 'Event Website', 'quick-event-manager' ),
        'field6'  => esc_html__( 'Cost', 'quick-event-manager' ),
        'field7'  => esc_html__( 'Organiser', 'quick-event-manager' ),
        'field8'  => esc_html__( 'Full Description', 'quick-event-manager' ),
        'field9'  => esc_html__( 'Places Taken', 'quick-event-manager' ),
        'field10' => esc_html__( 'Attendees', 'quick-event-manager' ),
        'field11' => esc_html__( 'Places Available', 'quick-event-manager' ),
        'field12' => esc_html__( 'Registration Form', 'quick-event-manager' ),
        'field13' => esc_html__( 'Category', 'quick-event-manager' ),
        'field14' => esc_html__( 'Sharing', 'quick-event-manager' ),
        'field17' => esc_html__( 'Allow variable donations', 'quick-event-manager' ),
    ),
        'sort'                  => 'field1,field2,field3,field4,field5,field17,field6,field7,field8,field9,field10,field11,field12,field13,field14',
        'bold'                  => array(
        'field2'  => true,
        'field10' => true,
    ),
        'italic'                => array(
        'field4' => true,
    ),
        'colour'                => array(
        'field2' => '#343838',
        'field6' => '#008C9E',
    ),
        'size'                  => array(
        'field1' => '110',
        'field2' => '120',
        'field6' => '120',
    ),
        'address_label'         => '',
        'url_label'             => '',
        'description_label'     => '',
        'cost_label'            => '',
        'dateformat'            => '',
        'date_background'       => '',
        'deposit_before_label'  => esc_html__( 'Deposit', 'quick-event-manager' ),
        'deposit_after_label'   => esc_html__( 'per person', 'quick-event-manager' ),
        'organiser_label'       => '',
        'category_label'        => esc_html__( 'Category', 'quick-event-manager' ),
        'facebook_label'        => esc_html__( 'Share on Facebook', 'quick-event-manager' ),
        'twitter_label'         => esc_html__( 'Share on Twitter', 'quick-event-manager' ),
        'ics_label'             => esc_html__( 'Download to Calendar', 'quick-event-manager' ),
        'start_label'           => esc_html__( 'From', 'quick-event-manager' ),
        'finish_label'          => esc_html__( 'until', 'quick-event-manager' ),
        'location_label'        => esc_html__( 'At', 'quick-event-manager' ),
        'address_style'         => 'italic',
        'website_link'          => 'checked',
        'show_telephone'        => 'checked',
        'whoscomingmessage'     => esc_html__( 'Look who\'s coming: ', 'quick-event-manager' ),
        'placesbefore'          => esc_html__( 'There are', 'quick-event-manager' ),
        'placesafter'           => esc_html__( 'places available.', 'quick-event-manager' ),
        'numberattendingbefore' => esc_html__( 'There are', 'quick-event-manager' ),
        'numberattendingafter'  => esc_html__( 'people coming.', 'quick-event-manager' ),
        'oneplacebefore'        => esc_html__( 'There is one place available', 'quick-event-manager' ),
        'oneattendingbefore'    => esc_html__( 'There is one person coming', 'quick-event-manager' ),
        'whoscoming'            => 'checked',
        'whosavatar'            => 'checked',
        'facebook'              => 'checked',
    );
    $event = array_merge( $default, $event );
    if ( !strpos( $event['sort'], 'field14' ) ) {
        $event['sort'] = $event['sort'] . ',field14';
    }
    return $event;
}

function event_get_stored_display()
{
    $display = get_option( 'qem_display' );
    if ( !is_array( $display ) ) {
        $display = array();
    }
    $default = array(
        'read_more'             => esc_html__( 'Find out more...', 'quick-event-manager' ),
        'noevent'               => esc_html__( 'No event found', 'quick-event-manager' ),
        'event_image'           => '',
        'usefeatured'           => 'checked',
        'monthheading'          => '',
        'back_to_list_caption'  => esc_html__( 'Return to Event list', 'quick-event-manager' ),
        'image_width'           => 300,
        'event_image_width'     => 300,
        'event_archive'         => '',
        'map_width'             => 200,
        'max_width'             => 40,
        'map_height'            => 200,
        'useics'                => '',
        'uselistics'            => '',
        'useicsbutton'          => esc_html__( 'Download Event to Calendar', 'quick-event-manager' ),
        'usetimezone'           => '',
        'timezonebefore'        => esc_html__( 'Timezone:', 'quick-event-manager' ),
        'timezoneafter'         => esc_html__( 'time', 'quick-event-manager' ),
        'show_map'              => '',
        'map_and_image'         => 'checked',
        'localization'          => '',
        'monthtype'             => 'short',
        'monthheadingorder'     => 'my',
        'categorydropdown'      => false,
        'categorydropdownlabel' => esc_html__( 'Select a Category', 'quick-event-manager' ),
        'categorydropdownwidth' => false,
        'categorylocation'      => 'title',
        'showcategory'          => '',
        'recentposts'           => '',
        'lightboxwidth'         => 60,
        'fullpopup'             => 'checked',
        'linktocategory'        => 'checked',
        'showuncategorised'     => '',
        'keycaption'            => esc_html__( 'Event Categories:', 'quick-event-manager' ),
        'showkeyabove'          => '',
        'showkeybelow'          => '',
        'showcategorycaption'   => esc_html__( 'Current Category:', 'quick-event-manager' ),
        'cat_border'            => 'checked',
        'catallevents'          => '',
        'catalleventscaption'   => esc_html__( 'Show All', 'quick-event-manager' ),
    );
    $display = array_merge( $default, $display );
    return $display;
}

function qem_get_stored_style()
{
    $style = get_option( 'qem_style' );
    if ( !is_array( $style ) ) {
        $style = array();
    }
    $default = array(
        'font'                => 'theme',
        'font-family'         => 'arial, sans-serif',
        'font-size'           => '1em',
        'header-size'         => '100%',
        'width'               => 600,
        'widthtype'           => 'percent',
        'event_border'        => '',
        'event_background'    => 'bgtheme',
        'event_backgroundhex' => '#FFF',
        'date_colour'         => '#FFF',
        'month_colour'        => '#343838',
        'date_background'     => 'grey',
        'date_backgroundhex'  => '#FFF',
        'month_background'    => 'white',
        'month_backgroundhex' => '#FFF',
        'date_border_width'   => '2',
        'date_border_colour'  => '#343838',
        'date_bold'           => '',
        'date_italic'         => 'checked',
        'calender_size'       => 'medium',
        'icon_corners'        => 'rounded',
        'styles'              => '',
        'uselabels'           => '',
        'startlabel'          => esc_html__( 'Starts', 'quick-event-manager' ),
        'finishlabel'         => esc_html__( 'Ends', 'quick-event-manager' ),
        'event_margin'        => 'margin: 0 0 20px 0,',
        'line_margin'         => 'margin: 0 0 8px 0,padding: 0 0 0 0',
        'use_custom'          => '',
        'custom'              => ".qem {\r\n}\r\n.qem h2{\r\n}",
        'combined'            => 'checked',
        'iconorder'           => 'default',
        'vanillaw'            => '',
        'vanillawidget'       => '',
        'vanillamonth'        => '',
        'use_head'            => '',
        'linktocategory'      => 'checked',
        'showuncategorised'   => '',
        'linktocategories'    => '',
        'keycaption'          => esc_html__( 'Event Categories:', 'quick-event-manager' ),
        'showkeyabove'        => '',
        'showkeybelow'        => '',
        'showcategory'        => '',
        'showcategorycaption' => esc_html__( 'Current Category:', 'quick-event-manager' ),
        'dayborder'           => 'checked',
        'catallevents'        => '',
        'catalleventscaption' => esc_html__( 'Show All', 'quick-event-manager' ),
        'cata'                => '',
        'catb'                => '',
        'catc'                => '',
        'catd'                => '',
        'cate'                => '',
        'catf'                => '',
        'catg'                => '',
        'cath'                => '',
        'cati'                => '',
        'catj'                => '',
        'background'          => 'bgwhite',
    );
    $style = array_merge( $default, $style );
    return $style;
}

function qem_get_stored_calendar()
{
    $calendar = get_option( 'qem_calendar' );
    if ( !is_array( $calendar ) ) {
        $calendar = array();
    }
    $default = array(
        'day'                 => '#EBEFC9',
        'calday'              => '#EBEFC9',
        'eventday'            => '#EED1AC',
        'oldday'              => '#CCC',
        'eventhover'          => '#F2F2E6',
        'eventdaytext'        => '#343838',
        'eventbackground'     => '#FFF',
        'eventtext'           => '#343838',
        'eventlink'           => 'linkpopup',
        'calendar_text'       => esc_html__( 'View as calendar', 'quick-event-manager' ),
        'calendar_url'        => '',
        'eventlist_text'      => esc_html__( 'View as a list of events', 'quick-event-manager' ),
        'eventlist_url'       => '',
        'eventlength'         => '20',
        'connect'             => '',
        'startday'            => 'sunday',
        'archive'             => 'checked',
        'archivelinks'        => 'checked',
        'prevmonth'           => esc_html__( 'Prev', 'quick-event-manager' ),
        'nextmonth'           => esc_html__( 'Next', 'quick-event-manager' ),
        'smallicon'           => 'arrow',
        'unicode'             => '\\263A',
        'eventtext'           => '#343838',
        'eventtextsize'       => '80%',
        'trigger'             => '480px',
        'eventbackground'     => '#FFF',
        'eventhover'          => '#EED1AC',
        'eventborder'         => '1px solid #343838',
        'keycaption'          => esc_html__( 'Event Key:', 'quick-event-manager' ),
        'navicon'             => 'arrows',
        'linktocategory'      => 'checked',
        'showuncategorised'   => '',
        'tdborder'            => '',
        'cellspacing'         => 3,
        'header'              => 'h2',
        'headerorder'         => 'my',
        'headerstyle'         => '',
        'eventimage'          => '',
        'imagewidth'          => '80',
        'usetootlip'          => '',
        'event_corner'        => 'rounded',
        'fixeventborder'      => '',
        'showmonthsabove'     => '',
        'showmonthsbelow'     => '',
        'monthscaption'       => esc_html__( 'Select Month:', 'quick-event-manager' ),
        'hidenavigation'      => '',
        'jumpto'              => 'checked',
        'calallevents'        => 'checked',
        'calalleventscaption' => esc_html__( 'Show All', 'quick-event-manager' ),
        'eventbold'           => '',
        'eventitalic'         => '',
        'eventbackground'     => '',
        'eventgridborder'     => '',
        'caldaytext'          => '',
        'attendeeflag'        => '',
        'attendeeflagcontent' => '',
    );
    $calendar = array_merge( $default, $calendar );
    return $calendar;
}

function qem_get_stored_register()
{
    $register = get_option( 'qem_register' );
    if ( !is_array( $register ) ) {
        $register = array();
    }
    $default = array(
        'sort'                  => 'field1,field2,field3,field4,field5,field17,field6,field7,field8,field9,field10,field11,field12,field13,field14,field15,field16',
        'useform'               => '',
        'formwidth'             => 280,
        'usename'               => 'checked',
        'usemail'               => 'checked',
        'useblank1'             => '',
        'useblank2'             => '',
        'usedropdown'           => '',
        'usenumber1'            => '',
        'useaddinfo'            => '',
        'useoptin'              => '',
        'usechecks'             => '',
        'usechecksradio'        => '',
        'reqname'               => 'checked',
        'reqmail'               => 'checked',
        'reqblank1'             => '',
        'reqblank2'             => '',
        'reqdropdown'           => '',
        'reqnumber1'            => '',
        'formborder'            => '',
        'ontheright'            => '',
        'notificationsubject'   => esc_html__( 'New registration for', 'quick-event-manager' ),
        'title'                 => esc_html__( 'Register for this event', 'quick-event-manager' ),
        'blurb'                 => esc_html__( 'Enter your details below', 'quick-event-manager' ),
        'replytitle'            => esc_html__( 'Thank you for registering', 'quick-event-manager' ),
        'replyblurb'            => esc_html__( 'We will be in contact soon', 'quick-event-manager' ),
        'replydeferred'         => esc_html__( 'Please ensure you bring the registration fee to the event', 'quick-event-manager' ),
        'yourname'              => esc_html__( 'Your Name', 'quick-event-manager' ),
        'youremail'             => esc_html__( 'Email Address', 'quick-event-manager' ),
        'yourtelephone'         => esc_html__( 'Telephone Number', 'quick-event-manager' ),
        'yourplaces'            => esc_html__( 'Places required', 'quick-event-manager' ),
        'donation'              => esc_html__( 'Donation Amount', 'quick-event-manager' ),
        'placesposition'        => 'left',
        'yourmessage'           => esc_html__( 'Message', 'quick-event-manager' ),
        'yourattend'            => esc_html__( 'I will not be attending this event', 'quick-event-manager' ),
        'yourblank1'            => esc_html__( 'More Information', 'quick-event-manager' ),
        'yourblank2'            => esc_html__( 'More Information', 'quick-event-manager' ),
        'yourdropdown'          => esc_html__( 'Separate,With,Commas', 'quick-event-manager' ),
        'yourselector'          => esc_html__( 'Separate,With,Commas', 'quick-event-manager' ),
        'yournumber1'           => esc_html__( 'Number', 'quick-event-manager' ),
        'addinfo'               => esc_html__( 'Fill in this field', 'quick-event-manager' ),
        'captchalabel'          => esc_html__( 'Answer the sum', 'quick-event-manager' ),
        'optinblurb'            => esc_html__( 'Sign me up for email messages', 'quick-event-manager' ),
        'checkslabel'           => esc_html__( 'Select options', 'quick-event-manager' ),
        'checkslist'            => esc_html__( 'Option 1,Option 4,Option 3', 'quick-event-manager' ),
        'usemorenames'          => '',
        'morenames'             => esc_html__( 'Enter all names:', 'quick-event-manager' ),
        'useterms'              => '',
        'termslabel'            => esc_html__( 'I agree to the Terms and Conditions', 'quick-event-manager' ),
        'termsurl'              => '',
        'termstarget'           => '',
        'notattend'             => '',
        'error'                 => esc_html__( 'Please complete the form', 'quick-event-manager' ),
        'qemsubmit'             => esc_html__( 'Register', 'quick-event-manager' ),
        'whoscoming'            => '',
        'whoscomingmessage'     => esc_html__( 'Look who\'s coming: ', 'quick-event-manager' ),
        'placesbefore'          => esc_html__( 'There are', 'quick-event-manager' ),
        'placesafter'           => esc_html__( 'places available.', 'quick-event-manager' ),
        'numberattendingbefore' => esc_html__( 'There are', 'quick-event-manager' ),
        'numberattendingafter'  => esc_html__( 'people coming.', 'quick-event-manager' ),
        'eventlist'             => '',
        'eventfull'             => '',
        'eventfullmessage'      => esc_html__( 'Registration is closed', 'quick-event-manager' ),
        'waitinglist'           => '',
        'waitinglistreply'      => esc_html__( 'Your name has been added to the waiting list', 'quick-event-manager' ),
        'waitinglistmessage'    => esc_html__( 'But you can register for the waiting list', 'quick-event-manager' ),
        'moderate'              => '',
        'moderatereply'         => esc_html__( 'Your registration is awaiting approval', 'quick-event-manager' ),
        'read_more'             => esc_html__( 'Return to the event', 'quick-event-manager' ),
        'useread_more'          => '',
        'sendemail'             => get_bloginfo( 'admin_email' ),
        'qemmail'               => 'wpmail',
        'sendcopy'              => '',
        'usecopy'               => '',
        'completed'             => '',
        'copyblurb'             => esc_html__( 'Send registration details to your email address', 'quick-event-manager' ),
        'alreadyregistered'     => esc_html__( 'You are already registered for this event', 'quick-event-manager' ),
        'nameremoved'           => esc_html__( 'You have been removed from the list', 'quick-event-manager' ),
        'checkremoval'          => '',
        'spam'                  => esc_html__( 'Your Details have been flagged as spam', 'quick-event-manager' ),
        'thanksurl'             => '',
        'cancelurl'             => '',
        'allowmultiple'         => '',
        'paypal'                => '',
        'perevent'              => 'perperson',
        'couponcode'            => esc_html__( 'Coupon code', 'quick-event-manager' ),
        'ignorepayment'         => '',
        'ignorepaymentlabel'    => esc_html__( 'Pay on arrival', 'quick-event-manager' ),
        'placesavailable'       => 'checked',
        'submitbackground'      => '#343838',
        'hoversubmitbackground' => '#888888',
        'listname'              => false,
        'listblurb'             => '[name] x[places] ([telephone]) [website]',
    );
    $register = array_merge( $default, $register );
    if ( !strpos( $register['sort'], 'field15' ) ) {
        $register['sort'] = $register['sort'] . ',field15';
    }
    if ( !strpos( $register['sort'], 'field16' ) ) {
        $register['sort'] = $register['sort'] . ',field16';
    }
    return $register;
}

function qem_get_register_style()
{
    $style = get_option( 'qem_register_style' );
    $register = qem_get_stored_register();
    if ( !is_array( $style ) ) {
        $style = array();
    }
    $default = array(
        'header'                  => '',
        'header-type'             => 'h2',
        'header-size'             => '1.6em',
        'header-colour'           => '#465069',
        'text-font-family'        => 'arial, sans-serif',
        'text-font-size'          => '1em',
        'text-font-colour'        => '#465069',
        'error-font-colour'       => '#D31900',
        'error-border'            => '1px solid #D31900',
        'form-width'              => $register['formwidth'],
        'submitwidth'             => 'submitpercent',
        'submitposition'          => 'submitleft',
        'border'                  => 'none',
        'form-border'             => '1px solid #415063',
        'input-border'            => '1px solid #415063',
        'input-required'          => '1px solid #00C618',
        'bordercolour'            => '#415063',
        'inputborderdefault'      => '1px solid #415063',
        'inputborderrequired'     => '1px solid #00C618',
        'inputbackground'         => '#FFFFFF',
        'inputfocus'              => '#FFFFCC',
        'background'              => 'theme',
        'backgroundhex'           => '#FFF',
        'submit-colour'           => '#FFF',
        'submit-background'       => $register['submitbackground'],
        'submit-hover-background' => $register['hoversubmitbackground'],
        'submit-button'           => '',
        'submit-border'           => '1px solid #415063',
        'submitwidth'             => 'submitpercent',
        'submitposition'          => 'submitleft',
        'corners'                 => 'corner',
        'line_margin'             => 'margin: 2px 0 3px 0;padding: 6px;',
    );
    $style = array_merge( $default, $style );
    return $style;
}

function qem_get_stored_payment()
{
    $payment = get_option( 'qem_payment' );
    if ( !is_array( $payment ) ) {
        $payment = array();
    }
    $default = array(
        'useqpp'              => '',
        'qppform'             => '',
        'currency'            => 'USD',
        'paypalemail'         => '',
        'useprocess'          => '',
        'message'             => '',
        'payment'             => esc_html__( 'Thank you for registering. Please bring proof of payment to the event', 'quick-event-manager' ),
        'waiting'             => esc_html__( 'Waiting for PayPal', 'quick-event-manager' ) . '...',
        'processtype'         => '',
        'processpercent'      => '',
        'processfixed'        => '',
        'qempaypalsubmit'     => esc_html__( 'Register and Pay', 'quick-event-manager' ),
        'ipn'                 => '',
        'ipnblock'            => '',
        'title'               => esc_html__( 'Payment', 'quick-event-manager' ),
        'paid'                => esc_html__( 'Complete', 'quick-event-manager' ),
        'usecoupon'           => '',
        'usependingcleardown' => '',
        'pendingcleardownmsg' => esc_html__( 'Your payment for this event did not complete, please try again. If you have any issues please contact us', 'quick-event-manager' ),
        'couponcode'          => esc_html__( 'Coupon code', 'quick-event-manager' ),
        'attendeelabel'       => esc_html__( 'Enter number of places required', 'quick-event-manager' ),
        'itemlabel'           => '[label] ' . esc_html__( 'at', 'quick-event-manager' ) . ' <em>[currency][cost]</em> ' . esc_html__( 'each', 'quick-event-manager' ) . ':',
        'totallabel'          => esc_html__( 'Total', 'quick-event-manager' ) . ':',
        'currencysymbol'      => '$',
    );
    $payment = array_merge( $default, $payment );
    
    if ( $payment['processtype'] ) {
        if ( $payment['processtype'] == 'processfixed' ) {
            $payment['processpercent'] = false;
        }
        if ( $payment['processtype'] == 'processpercent' ) {
            $payment['processfixed'] = false;
        }
    }
    
    return $payment;
}

function qem_get_stored_autoresponder()
{
    global  $qem_fs ;
    $auto = get_option( 'qem_autoresponder' );
    if ( !is_array( $auto ) ) {
        $auto = array();
    }
    $fromemail = get_bloginfo( 'admin_email' );
    $title = get_bloginfo( 'name' );
    $default = array(
        'enable'                   => '',
        'whenconfirm'              => 'aftersubmission',
        'subject'                  => esc_html__( 'You have registered for ', 'quick-event-manager' ),
        'subjecttitle'             => 'checked',
        'subjectdate'              => '',
        'message'                  => esc_html__( 'Thank you for registering, we will be in contact soon. If you have any questions please reply to this email.', 'quick-event-manager' ),
        'useeventdetails'          => '',
        'eventdetailsblurb'        => esc_html__( 'Event Details', 'quick-event-manager' ),
        'useregistrationdetails'   => 'checked',
        'registrationdetailsblurb' => esc_html__( 'Your registration details', 'quick-event-manager' ),
        'sendcopy'                 => 'checked',
        'fromname'                 => $title,
        'fromemail'                => $fromemail,
        'permalink'                => '',
    );
    $auto = array_merge( $default, $auto );
    return $auto;
}

function qem_get_stored_incontext()
{
    $payment = get_option( 'qem_incontext' );
    if ( !is_array( $payment ) ) {
        $payment = array();
    }
    $default = array(
        'useincontext'    => false,
        'useapi'          => 'paypal',
        'merchantid'      => '',
        'api_username'    => '',
        'api_password'    => '',
        'api_key'         => '',
        'secret_key'      => '',
        'publishable_key' => '',
        'stripeimage'     => '',
    );
    $payment = array_merge( $default, $payment );
    return $payment;
}

function qem_get_stored_sandbox()
{
    $payment = get_option( 'qem_sandbox' );
    if ( !is_array( $payment ) ) {
        $payment = array();
    }
    $default = array(
        'useincontext' => false,
        'useapi'       => 'paypal',
        'merchantid'   => '',
        'api_username' => '',
        'api_password' => '',
        'api_key'      => '',
    );
    $payment = array_merge( $default, $payment );
    return $payment;
}

function qem_get_stored_api()
{
    $api = get_option( 'qem_api' );
    if ( !is_array( $api ) ) {
        $api = array();
    }
    $default = array(
        'validating'            => esc_html__( 'Validating payment information...', 'quick-event-manager' ),
        'waiting'               => esc_html__( 'Waiting for Payment Gateway...', 'quick-event-manager' ),
        'errortitle'            => esc_html__( 'There is a problem', 'quick-event-manager' ),
        'errorblurb'            => esc_html__( 'Your payment could not be processed. Please try again', 'quick-event-manager' ),
        'technicalerrorblurb'   => esc_html__( 'There seems to be a technical issue, contact an administrator!', 'quick-event-manager' ),
        'failuretitle'          => esc_html__( 'Order Failure', 'quick-event-manager' ),
        'failureblurb'          => esc_html__( 'The payment has not been completed.', 'quick-event-manager' ),
        'failureanchor'         => esc_html__( 'Try again', 'quick-event-manager' ),
        'pendingtitle'          => esc_html__( 'Payment Pending', 'quick-event-manager' ),
        'pendingblurb'          => esc_html__( 'The payment has been processed, but confimration is currently pending. Refresh this page for real-time changes to this order.', 'quick-event-manager' ),
        'pendinganchor'         => esc_html__( 'Refresh This Page', 'quick-event-manager' ),
        'confirmationtitle'     => esc_html__( 'Registration Confirmation', 'quick-event-manager' ),
        'confirmationblurb'     => esc_html__( 'The transaction has been completed successfully. Keep this information for your records.', 'quick-event-manager' ),
        'confirmationreference' => esc_html__( 'Payment Reference:', 'quick-event-manager' ),
        'confirmationamount'    => esc_html__( 'Amount Paid:', 'quick-event-manager' ),
        'confirmationanchor'    => esc_html__( 'Register another person', 'quick-event-manager' ),
    );
    $api = array_merge( $default, $api );
    return $api;
}

function qem_get_addons()
{
    global  $qem_fs ;
    return array();
}

function qem_stored_guest()
{
    $guest = get_option( 'qem_guest' );
    if ( !is_array( $guest ) ) {
        $guest = array();
    }
    $default = array(
        'title'                       => esc_html__( 'Create an Event', 'quick-event-manager' ),
        'blurb'                       => esc_html__( 'Complete the form below to add your own event', 'quick-event-manager' ),
        'thankstitle'                 => esc_html__( 'Thank you for submitting your event', 'quick-event-manager' ),
        'thanksblurb'                 => esc_html__( 'View all current events', 'quick-event-manager' ),
        'allowimage'                  => false,
        'imagesize'                   => 100000,
        'pendingblurb'                => esc_html__( 'Your event is awaiting review and will be published soon.', 'quick-event-manager' ),
        'errormessage'                => esc_html__( 'Please complete all marked fields', 'quick-event-manager' ),
        'errorduplicate'              => esc_html__( 'An Event with that Title already exists...', 'quick-event-manager' ),
        'errorcaptcha'                => esc_html__( 'The captcha answer is incorrect', 'quick-event-manager' ),
        'errorimage'                  => esc_html__( 'There is an error with the chosen image', 'quick-event-manager' ),
        'errorenddate'                => esc_html__( 'The event ends before it starts', 'quick-event-manager' ),
        'noui'                        => '',
        'event_title_checked'         => 'checked',
        'event_details_checked'       => '',
        'event_tags_checked'          => '',
        'event_category_checked'      => '',
        'event_date_checked'          => 'checked',
        'event_end_date_checked'      => '',
        'event_start_checked'         => '',
        'event_finish_checked'        => '',
        'event_desc_checked'          => '',
        'event_location_checked'      => '',
        'event_address_checked'       => '',
        'event_link_checked'          => '',
        'event_anchor_checked'        => '',
        'event_cost_checked'          => '',
        'event_donation_checked'      => '',
        'event_forms_checked'         => '',
        'event_image_checked'         => '',
        'event_register_checked'      => '',
        'event_pay_checked'           => '',
        'event_image_upload_checked'  => '',
        'event_captcha_checked'       => 'checked',
        'event_author_checked'        => 'checked',
        'event_author_email_checked'  => 'checked',
        'event_title_use'             => 'checked',
        'event_details_use'           => 'checked',
        'event_tags_use'              => '',
        'event_category_use'          => '',
        'event_date_use'              => 'checked',
        'event_end_date_use'          => 'checked',
        'event_start_use'             => 'checked',
        'event_finish_use'            => 'checked',
        'event_desc_use'              => 'checked',
        'event_location_use'          => 'checked',
        'event_address_use'           => 'checked',
        'event_link_use'              => 'checked',
        'event_anchor_use'            => 'checked',
        'event_cost_use'              => 'checked',
        'event_donation_use'          => '',
        'event_organiser_use'         => 'checked',
        'event_telephone_use'         => 'checked',
        'event_forms_use'             => 'checked',
        'event_image_upload_use'      => '',
        'event_register_use'          => '',
        'event_captcha_use'           => 'checked',
        'event_author_use'            => 'checked',
        'event_author_email_use'      => 'checked',
        'event_title_caption'         => esc_html__( 'Event Title', 'quick-event-manager' ),
        'event_details_caption'       => esc_html__( 'Event Details', 'quick-event-manager' ),
        'event_tags_caption'          => esc_html__( 'Tags', 'quick-event-manager' ),
        'event_category_caption'      => esc_html__( 'Category', 'quick-event-manager' ),
        'event_date_caption'          => esc_html__( 'Start Date', 'quick-event-manager' ),
        'event_end_date_caption'      => esc_html__( 'End Date', 'quick-event-manager' ),
        'event_start_caption'         => esc_html__( 'Start Time', 'quick-event-manager' ),
        'event_finish_caption'        => esc_html__( 'End Time', 'quick-event-manager' ),
        'event_desc_caption'          => esc_html__( 'Description', 'quick-event-manager' ),
        'event_location_caption'      => esc_html__( 'Location', 'quick-event-manager' ),
        'event_address_caption'       => esc_html__( 'Address', 'quick-event-manager' ),
        'event_link_caption'          => esc_html__( 'Website', 'quick-event-manager' ),
        'event_anchor_caption'        => esc_html__( 'Display As', 'quick-event-manager' ),
        'event_cost_caption'          => esc_html__( 'Cost', 'quick-event-manager' ),
        'event_donation_caption'      => esc_html__( 'Donation?', 'quick-event-manager' ),
        'event_organiser_caption'     => esc_html__( 'Organiser', 'quick-event-manager' ),
        'event_telephone_caption'     => esc_html__( 'Telephone', 'quick-event-manager' ),
        'event_register_caption'      => esc_html__( 'Registration Form', 'quick-event-manager' ),
        'event_image_upload_caption'  => esc_html__( 'Event Image', 'quick-event-manager' ),
        'event_captcha_label_caption' => esc_html__( 'Captcha', 'quick-event-manager' ),
        'event_author_caption'        => esc_html__( 'Author Name', 'quick-event-manager' ),
        'event_author_email_caption'  => esc_html__( 'Author Email', 'quick-event-manager' ),
        'event_forms_caption'         => esc_html__( 'Event Forms', 'quick-event-manager' ),
        'event_title'                 => esc_html__( 'Event Title', 'quick-event-manager' ),
        'event_details'               => esc_html__( 'Event Details', 'quick-event-manager' ),
        'event_tags'                  => esc_html__( 'Tags', 'quick-event-manager' ),
        'event_category'              => '1',
        'event_date'                  => esc_html__( 'Start Date', 'quick-event-manager' ),
        'event_end_date'              => esc_html__( 'End Date', 'quick-event-manager' ),
        'event_start'                 => esc_html__( 'Start Time', 'quick-event-manager' ),
        'event_finish'                => esc_html__( 'End Time', 'quick-event-manager' ),
        'event_desc'                  => esc_html__( 'Description', 'quick-event-manager' ),
        'event_location'              => esc_html__( 'Venue', 'quick-event-manager' ),
        'event_address'               => esc_html__( 'Address', 'quick-event-manager' ),
        'event_link'                  => esc_html__( 'Website', 'quick-event-manager' ),
        'event_anchor'                => esc_html__( 'Website Name', 'quick-event-manager' ),
        'event_cost'                  => esc_html__( 'Cost', 'quick-event-manager' ),
        'event_donation'              => esc_html__( 'Is a Donation?', 'quick-event-manager' ),
        'event_organiser'             => esc_html__( 'Organiser', 'quick-event-manager' ),
        'event_telephone'             => esc_html__( 'Telephone', 'quick-event-manager' ),
        'event_forms'                 => esc_html__( 'Event Forms', 'quick-event-manager' ),
        'event_register'              => esc_html__( 'Add a registration form to your event', 'quick-event-manager' ),
        'event_image_details'         => esc_html__( 'jpg, gif or png only. Max file size 100kb', 'quick-event-manager' ),
        'event_image_upload'          => esc_html__( 'Event Image (jpg, gif or png only. Max file size 100kb)', 'quick-event-manager' ),
        'event_captcha_label'         => esc_html__( 'Anti spam Captcha', 'quick-event-manager' ),
        'event_author'                => esc_html__( 'Your Name', 'quick-event-manager' ),
        'event_author_email'          => esc_html__( 'Your Email', 'quick-event-manager' ),
    );
    $guest = array_merge( $default, $guest );
    return $guest;
}

function qem_guest_list()
{
    $event = array(
        'event_title',
        'event_date',
        'event_end_date',
        'event_start',
        'event_finish',
        'event_desc',
        'event_location',
        'event_address',
        'event_link',
        'event_anchor',
        'event_number',
        'event_cost',
        'event_donation',
        'event_organiser',
        'event_telephone',
        'event_details',
        'event_register',
        'event_tags',
        'event_author',
        'event_author_email',
        'event_image_upload',
        'event_category',
        'event_repeat',
        'theday',
        'thenumber',
        'therepetitions',
        'thewmy'
    );
    return $event;
}
