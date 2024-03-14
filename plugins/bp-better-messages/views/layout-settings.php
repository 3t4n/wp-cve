<?php
/**
 * Settings page
 */
defined( 'ABSPATH' ) || exit;

global $wpdb;
$websocket_allowed = Better_Messages()->functions->can_use_premium_code_premium_only();

$all_roles = get_editable_roles();
$roles = $all_roles;
if (isset($roles['administrator'])) unset($roles['administrator']);

$wp_roles = $roles;

$roles['bm-guest'] = [
    'name' => _x('Guests', 'Settings page', 'bp-better-messages' )
];
$all_roles['bm-guest'] = [
    'name' => _x('Guests', 'Settings page', 'bp-better-messages' )
];
?>
<style type="text/css">
    .bpbm-tab{
        display: none;
    }

    .bpbm-tab.active{
        display: block;
    }

    .bpbm-subtab{
        display: none;
    }

    .bpbm-subtab.active{
        display: block;
    }

    td.attachments-formats ul{
        display: inline-block;
        vertical-align: top;
        padding: 0 30px 0 0;
        margin-top: 5px;
    }

    td.attachments-formats ul > strong{
        display: block;
        margin-bottom: 5px;
    }

    .cols{
        overflow: hidden;
    }

    .cols .col{
        width: 49%;
        float: left;
    }

    .cols .col.secondary-col{
        padding-left: 2%;
    }

    @media only screen and (max-width: 1050px){
        .cols .col{
            width: 100%;
            float: none;
            padding-left: 0 !important;
        }
    }


    .bm-switcher-table{
        width: auto;
    }


    @media only screen and (min-width: 783px) {
        .bm-switcher-table td {
            padding-left: 20px;
            width: 1px;
            white-space: nowrap;
        }

        .bm-switcher-table .th-left-pd{
            padding-left: 20px;
        }

        .bm-switcher-table th {
            padding-right: 20px;
        }
    }


    @media only screen and (max-width: 782px) {
        .bm-switcher-table{
            padding: 10px !important;
        }
    }

    .bpbm-tab .form-table th{
        width: auto;
    }

    .bpbm-tab#customization .form-table th{
        width: 200px;
    }

    .bpbm-subtab .form-table th{
        width: auto;
    }

    .bpbm-subtab#customization .form-table th{
        width: 200px;
    }

    input[type=checkbox], input[type=radio]{
        margin: 0 5px 0 0;
    }

    .bp-better-messages-facebook,
    .bp-better-messages-facebook:hover,
    .bp-better-messages-facebook:focus{
        background: #3b5998;
        display: inline-block;
        width: 300px;
        max-width: 100%;
        text-align: center;
        color: white;
        cursor: pointer;
        text-decoration: none;
        padding: 10px;
        font-size: 16px;
        margin-top: 22px;
    }


    .bp-better-messages-roadmap,
    .bp-better-messages-roadmap:hover,
    .bp-better-messages-roadmap:focus{
        background: #3b3d89;
        display: inline-block;
        width: 300px;
        max-width: 100%;
        text-align: center;
        color: white;
        cursor: pointer;
        text-decoration: none;
        padding: 10px;
        font-size: 16px;
        margin-top: 10px;
    }

    .bp-better-messages-trial,
    .bp-better-messages-trial:hover,
    .bp-better-messages-trial:focus{
        background: #2271b1;
        display: inline-block;
        width: 300px;
        max-width: 100%;
        text-align: center;
        color: white;
        cursor: pointer;
        text-decoration: none;
        padding: 10px;
        font-size: 16px;
        margin-top: 20px;
    }

    .bp-better-messages-connection-check{
        display: block;
        margin: 10px 0;
        color: #856404;
        background-color: #fff3cd;
        border: 1px solid #f9e4a6;
        padding: 15px;
        line-height: 24px;
        max-width: 550px;
    }
    .bp-better-messages-connection-check.bpbm-error{
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .bp-better-messages-connection-check.bpbm-ok{
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    @-moz-keyframes bpbm-spin { 100% { -moz-transform: rotate(360deg); } }
    @-webkit-keyframes bpbm-spin { 100% { -webkit-transform: rotate(360deg); } }
    @keyframes bpbm-spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }

    .bp-better-messages-roles-list{
        max-height: 250px;
        overflow: auto;
        background: white;
        padding: 15px;
        border: 1px solid #ccc;
    }

    .bp-better-messages-roles-list td,
    .bp-better-messages-roles-list th{
        padding: 5px;
    }

    .role-block-empty + table{
        display: none;
    }

    .delete-row{
        cursor: pointer;
    }

    .bm-docs-btn,
    .bm-customize-btn{
        display: inline-flex !important;
        align-items: center;
    }

</style>
<script type="text/javascript">
    var reactions;

    function changeRestrictMode(){
        var checked = jQuery('input[name="restrictRoleType"]:checked');

        var allowed = true;
        if( checked.val() === 'disallow' ){
            allowed = false;
        }

        if( allowed ){
            jQuery('.restrictRoleBlockAllowed').show();
            jQuery('.restrictRoleBlockDisAllowed').hide();
        } else {
            jQuery('.restrictRoleBlockAllowed').hide();
            jQuery('.restrictRoleBlockDisAllowed').show();
        }
    }

    jQuery(document).ready(function ($) {

        $("#bpbm-tabs > a").on('click touchstart', function(event){
            event.preventDefault();
            event.stopPropagation();

            if( $(this).hasClass('nav-tab-active') ) return false;

            var selector = $(this).attr('href');
            window.history.pushState("", "", selector);

            $('#bpbm-tabs > a').removeClass('nav-tab-active');
            $('.bpbm-tab').removeClass('active');

            $(this).addClass('nav-tab-active');
            $(selector).addClass('active');
        });

        $(".bpbm-sub-tabs > a").on('click touchstart', function(event){
            event.preventDefault();
            event.stopPropagation();

            if( $(this).hasClass('nav-tab-active') ) return false;

            var container = $(this).closest('.bpbm-tab');
            var selector = $(this).attr('href');
            window.history.pushState("", "", selector);

            container.find('.bpbm-sub-tabs > a').removeClass('nav-tab-active');
            container.find('.bpbm-subtab').removeClass('active');

            $(this).addClass('nav-tab-active');
            container.find(selector).addClass('active');
        });

        var hash = location.href.split('#')[1];
        if(typeof hash != 'undefined'){
            var hasharray = hash.split('_');
            let mainTab = hasharray[0];
            var selector = jQuery("#bpbm-tabs > a[href='#"+ mainTab +"']");
            jQuery('#bpbm-tabs > a').removeClass('nav-tab-active');
            jQuery('.bpbm-tab').removeClass('active');

            jQuery( selector ).addClass('nav-tab-active');
            jQuery( '#' + mainTab ).addClass('active');
            reactions = $('input[name="enableReactions"]');
            reactions.on('change', changeReactionStatuses)

            changeReactionStatuses();

            if( typeof hasharray[1] === 'string' ){
                jQuery( 'a.nav-tab[href="#' + hash + '"]' ).click();
            }
        }

        $(window).on('hashchange', function() {
            var hash = location.href.split('#')[1];
            if(typeof hash != 'undefined'){
                var selector = jQuery("#bpbm-tabs > a[href='#"+ hash + "']");
                jQuery('#bpbm-tabs > a').removeClass('nav-tab-active');
                jQuery('.bpbm-tab').removeClass('active');

                jQuery( selector ).addClass('nav-tab-active');
                jQuery( '#' + hash ).addClass('active');
            }
        });

        $('input[name="mechanism"]').change(function () {
            var mechanism = $('input[name="mechanism"]:checked').val();

            $('.ajax, .websocket').hide();
            $('.' + mechanism).show();

            if(mechanism == 'websocket'){
                $('input[name="miniChatsEnable"]').attr('disabled', false);
                $('input[name="miniThreadsEnable"]').attr('disabled', false);
            } else {
                $('input[name="miniChatsEnable"]').attr('disabled', true);
                $('input[name="miniThreadsEnable"]').attr('disabled', true);
            }

            changeMessageStatuses();
        });

        changeTemplate();

        $('input[name="template"]').change(function () {
            changeTemplate();
        });

        function changeTemplate(){
            var template = $('input[name="template"]:checked').val();

            if(template === 'standard'){
                $('input[name="modernLayout"').attr('disabled', true);
            } else {
                $('input[name="modernLayout"').attr('disabled', false);
            }
        }

        changeGuest();

        $('input[name="guestChat"],select[name="chatPage"]').change(function () {
            changeGuest();
        });

        function changeGuest(){
            var active = $('input[name="guestChat"]').is(':checked');
            var chatPage = $('select[name="chatPage"]').val();
            var warning = $('#guest-warning');

            if( ! active ){
                warning.hide();
            } else {
                if( chatPage > 0 ){
                    warning.hide();
                } else {
                    warning.show();
                }
            }
        }

        function changeReactionStatuses(){
            var reactionsOptions = $('input[name="enableReactionsPopup"]')
            if( reactions.is(':checked') ){
                reactionsOptions.attr('disabled', false);
            } else {
                reactionsOptions.attr('disabled', true);
            }
        }

        var messageStatuses = $('input[name="messagesStatus"]');
        messageStatuses.on('change', changeMessageStatuses)
        function changeMessageStatuses(){
            var possibleToEnable = $('input[name="mechanism"]:checked').val() === 'websocket';

            var messageStatusesOptions = $('input[name="messagesStatusList"],input[name="messagesStatusDetailed"]')
            if( possibleToEnable ){
                messageStatuses.attr('disabled', false);
                if( messageStatuses.is(':checked') ){
                    messageStatusesOptions.attr('disabled', false);
                } else {
                    messageStatusesOptions.attr('disabled', true);
                }
            } else {
                messageStatuses.attr('disabled', false);
                messageStatusesOptions.attr('disabled', true);
            }

        }

        var singleThreadCheckbox = $('input[name="singleThreadMode"]');
        var newThreadCheckbox = $('input[name="newThreadMode"]');

        function changeThreadMode(){
            if( newThreadCheckbox.is(':checked') ){
                singleThreadCheckbox.attr('disabled', true )
            } else {
                singleThreadCheckbox.attr('disabled', false )
            }
            if( singleThreadCheckbox.is(':checked') ){
                newThreadCheckbox.attr('disabled', true )
            } else {
                newThreadCheckbox.attr('disabled', false )
            }
        }

        changeThreadMode();
        newThreadCheckbox.on( 'change', changeThreadMode);
        singleThreadCheckbox.on( 'change', changeThreadMode);

        var restrictMode = $('input[name="restrictRoleType"]');

        restrictMode.on( 'change', changeRestrictMode )
        changeRestrictMode();

        function serializeFormToJson(form) {
            let formData = new FormData(form);
            let jsonObject = {};

            for (const [key, value] of formData.entries()) {
                if( key.endsWith("[]") ){
                    let lastKey = key.replace(/\[\]$/, "");

                    if( ! jsonObject[lastKey] ){
                        jsonObject[lastKey] = [];
                    }

                    jsonObject[lastKey].push(value);
                    continue;
                }

                let keys = key.split('[').map(k => k.replace(']', '')).filter(Boolean);

                let lastKey = keys.pop();
                let obj = jsonObject;


                keys.forEach(k => {
                    if ( ! obj[k] ) {
                        obj[k] = {};
                    }
                    obj = obj[k];
                });

                if ( lastKey ) {
                    obj[lastKey] = value;
                } else {
                    if ( ! Array.isArray(obj) ) {
                        obj = [];
                    }
                    obj.push(value);
                }
            }

            return JSON.stringify(jsonObject);
        }

        var form = $('#bm-settings-form');
        var btn = form.find('input#submit');

        form.on('submit', function(event){
            event.preventDefault();

            btn.attr( 'disabled', 'disabled' );

            const data = serializeFormToJson(form[0]);
            jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                'action' : 'better_messages_admin_save_settings',
                'nonce'  : '<?php echo wp_create_nonce( 'bm-save-settings' ); ?>',
                'data'   : data
            }).done(function () {
                location.reload()
            }).fail(function () {
                btn.attr( 'disabled', false );

                alert('Failed to save settings')
            })
        })
    });
</script>
<div class="wrap">
    <h1><?php _ex( 'Better Messages', 'Settings page', 'bp-better-messages' ); ?></h1>
    <div class="nav-tab-wrapper" id="bpbm-tabs">
        <a class="nav-tab nav-tab-active" id="general-tab" href="#general"><?php _ex( 'General', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="chat-tab" href="#messaging"><?php _ex( 'Messaging', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="integrations-tab" href="#integrations"><?php _ex( 'Integrations', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="mini-widgets-tab" href="#mini-widgets"><?php _ex( 'Mini Widgets', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="mobile-tab" href="#mobile"><?php _ex( 'Mobile', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="attachments-tab" href="#attachments"><?php _ex( 'Attachments', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="notifications-tab" href="#notifications"><?php _ex( 'Notifications', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="rules-tab" href="#rules"><?php _ex( 'Restrictions', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="calls-tab" href="#calls"><?php _ex( 'Calls', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="group-calls-tab" href="#group-calls"><?php _ex( 'Group Calls', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="customization-tab" href="#customization"><?php _ex( 'Customization', 'Settings page', 'bp-better-messages' ); ?></a>

        <a class="nav-tab" id="shortcodes-tab" href="#shortcodes"><?php _ex( 'Shortcodes', 'Settings page', 'bp-better-messages' ); ?></a>
        <a class="nav-tab" id="tools-tab" href="#tools"><?php _ex( 'Tools', 'Settings page', 'bp-better-messages' ); ?></a>
    </div>
    <form id="bm-settings-form" action="" method="POST">
        <?php wp_nonce_field( 'bp-better-messages-settings' ); ?>
        <div id="general" class="bpbm-tab active">
            <div class="cols">
                <div class="col">
                    <table class="form-table">
                        <tbody>
                        <tr valign="top" class="">
                            <th scope="row" valign="top">
                                <?php _ex( 'Better Messages Location', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'Choose the page where Better Messages will be located', 'Settings page', 'bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <?php
                                $defaults = array(
                                    'depth'                 => 0,
                                    'child_of'              => 0,
                                    'selected'              => 0,
                                    'echo'                  => 1,
                                    'name'                  => 'page_id',
                                    'id'                    => '',
                                    'class'                 => '',
                                    'show_option_none'      => '',
                                    'show_option_no_change' => '',
                                    'option_none_value'     => '',
                                    'value_field'           => 'ID',
                                );

                                $option_none = _x('Select page',  'Settings page', 'bp-better-messages');

                                if( class_exists( 'BuddyPress' ) ){
                                    $option_none =  _x('Show in BuddyPress profile',  'Settings page','bp-better-messages');
                                } else if( defined('ultimatemember_version') ){
                                    $option_none =  _x('Show in Ultimate Member profile',  'Settings page','bp-better-messages');
                                }

                                $option_none = apply_filters( 'better_messages_location_none', $option_none );

                                $parsed_args = wp_parse_args( array(
                                    'show_option_none' => $option_none,
                                    'name' => 'chatPage',
                                    'selected' => $this->settings[ 'chatPage' ],
                                    'option_none_value' => '0'
                                ), $defaults );

                                global $sitepress;
                                if( defined('ICL_LANGUAGE_CODE') && !! $sitepress ){
                                    $backup_code = ICL_LANGUAGE_CODE;
                                    $default_code = $sitepress->get_default_language();
                                    $sitepress->switch_lang( $default_code );
                                    $pages  = get_pages( $parsed_args );
                                    $sitepress->switch_lang( $backup_code );
                                } else {
                                    $pages  = get_pages( $parsed_args );
                                }

                                // Back-compat with old system where both id and name were based on $name argument.
                                if ( empty( $parsed_args['id'] ) ) {
                                    $parsed_args['id'] = $parsed_args['name'];
                                }

                                $output = "<select name='" . esc_attr( $parsed_args['name'] ) . "' id='" . esc_attr( $parsed_args['id'] ) . "'>\n";

                                if ( $parsed_args['show_option_none'] ) {
                                    $output .= "\t<option value=\"" . esc_attr( $parsed_args['option_none_value'] ) . '">' . $parsed_args['show_option_none'] . "</option>\n";
                                }

                                if( class_exists('AsgarosForum') ) {
                                    $output .= "\t<option value=\"asgaros-forum\" " . selected($parsed_args['selected'], 'asgaros-forum', false) . ">" . _x('Show in Asgaros Forum Profile',  'Settings page', 'bp-better-message' ) . "</option>\n";
                                }

                                if( class_exists('WooCommerce') ) {
                                    $output .= "\t<option value=\"woocommerce\" " . selected($parsed_args['selected'], 'woocommerce', false) . ">" . _x('Show in WooCommerce My Account',  'Settings page', 'bp-better-message' ) . "</option>\n";
                                }



                                if ( ! empty( $pages ) ) {
                                    $output .= walk_page_dropdown_tree( $pages, $parsed_args['depth'], $parsed_args );
                                }

                                $output .= "</select>\n";

                                echo $output;
                                ?>

                                <p><?php echo sprintf(_x('You can use <code>%s</code> shortcode to place chat in specific place of your selected page, if you not used this shortcode all page content will be replaced.', 'Settings page', 'bp-better-messages'), '[bp-better-messages]'); ?></p>
                                <p><?php echo sprintf(_x('If first shortcode does not work (this can happen when using some page builders) - use <code>%s</code> shortcode instead.', 'Settings page', 'bp-better-messages'), '[better_messages]'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _ex( 'Guest Chat', 'Settings page','bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'Enable Guest Chat functionality for not logged in users which allow to use private messaging system as logged in users', 'Settings page','bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <input type="checkbox" name="guestChat" <?php checked( $this->settings[ 'guestChat' ], '1' ); ?> value="1">

                                <div id="guest-warning" class="bp-better-messages-connection-check bpbm-error" style="margin: 20px 0;display: none;">
                                    <small><?php _ex( 'Please select WordPress page as Better Messages Location for Guest Chat to work properly', 'Settings page','bp-better-messages' ); ?></small>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row" style="width: 300px">
                                <?php _ex( 'Refresh mechanism', 'Settings page', 'bp-better-messages' ); ?>
                            </th>
                            <td>
                                <fieldset>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span><?php _ex( 'Refresh mechanism', 'Settings page', 'bp-better-messages' ); ?></span></legend>
                                        <label><input type="radio" name="mechanism" value="ajax" <?php checked( $this->settings[ 'mechanism' ], 'ajax' ); ?> <?php if($websocket_allowed) echo 'disabled'; ?>> <?php _ex( 'AJAX', 'Settings page', 'bp-better-messages' ); ?>
                                        </label>
                                        <br>
                                        <label><input type="radio" name="mechanism" value="websocket" <?php checked( $this->settings[ 'mechanism' ], 'websocket' ); ?> <?php if(! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium()) echo 'disabled'; ?>>
                                            <?php _ex( 'WebSocket', 'Settings page', 'bp-better-messages' ); ?>
                                            <?php Better_Messages()->functions->license_proposal(); ?>
                                        </label>
                                    </fieldset>
                                </fieldset>
                            </td>
                        </tr>

                        <tr class="ajax"
                            style="<?php if ( $this->settings[ 'mechanism' ] == 'websocket' ) echo 'display:none;'; ?>">
                            <th scope="row">
                                <?php _ex( 'Conversation Refresh Interval', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'AJAX refresh interval on open conversation', 'Settings page', 'bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _ex( 'Conversation Refresh Interval', 'Settings page', 'bp-better-messages' ); ?></span></legend>
                                    <label><input type="number" name="thread_interval" value="<?php echo esc_attr( $this->settings[ 'thread_interval' ] ); ?>"></label>
                                </fieldset>
                            </td>
                        </tr>

                        <tr class="ajax"
                            style="<?php if ( $this->settings[ 'mechanism' ] == 'websocket' ) echo 'display:none;'; ?>">
                            <th scope="row">
                                <?php _ex( 'Site Refresh Interval', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'AJAX refresh interval on other sites pages', 'Settings page', 'bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _ex( 'Conversation Refresh Interval', 'Settings page', 'bp-better-messages' ); ?></span></legend>
                                    <label><input type="number" name="site_interval" value="<?php echo esc_attr( $this->settings[ 'site_interval' ] ); ?>"></label>
                                </fieldset>
                            </td>
                        </tr>

                        <tr style="<?php if ( $this->settings[ 'mechanism' ] != 'websocket' ) echo 'display:none;'; ?>">
                            <th scope="row">
                                <?php _ex( 'Enable Encryption', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'Encrypts all sensitive content before transfer to websocket server and decrypt on client site with special secret keys not known by our side.', 'Settings page', 'bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <fieldset>
                                    <input type="checkbox" checked disabled value="1" />
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <?php _ex( 'Enable Browser Database Encryption', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'Encrypts all sensitive content in local browser database to enhance the security', 'Settings page', 'bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <fieldset>
                                    <input name="encryptionLocal" type="checkbox" <?php checked( $this->settings[ 'encryptionLocal' ], '1' ); ?> value="1" <?php if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                                    <?php Better_Messages()->functions->license_proposal(); ?>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row" style="width: 300px">
                                <?php _ex( 'Messages styling', 'Settings page', 'bp-better-messages' ); ?>
                            </th>
                            <td>
                                <fieldset>
                                    <fieldset>
                                        <label><input type="radio" name="template" value="standard" <?php checked( $this->settings[ 'template' ], 'standard' ); ?>>
                                            <?php _ex( 'Standard', 'Settings page', 'bp-better-messages' ); ?>
                                        </label>
                                        <br>
                                        <label><input type="radio" name="template" value="modern" <?php checked( $this->settings[ 'template' ], 'modern' ); ?>>
                                            <?php _ex( 'Modern', 'Settings page', 'bp-better-messages' ); ?>
                                        </label>
                                    </fieldset>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row" style="width: 300px">
                                <?php _ex( 'Modern messages layout', 'Settings page', 'bp-better-messages' ); ?>
                            </th>
                            <td>
                                <fieldset>
                                    <fieldset>
                                        <label><input type="radio" name="modernLayout" value="left" <?php checked( $this->settings[ 'modernLayout' ], 'left' ); ?>>
                                            <?php _ex( 'My messages at left side', 'Settings page', 'bp-better-messages' ); ?>
                                        </label>
                                        <br>
                                        <label><input type="radio" name="modernLayout" value="right" <?php checked( $this->settings[ 'modernLayout' ], 'right' ); ?>>
                                            <?php _ex( 'My messages at right side', 'Settings page', 'bp-better-messages' ); ?>
                                        </label>
                                        <br>
                                        <label><input type="radio" name="modernLayout" value="leftAll" <?php checked( $this->settings[ 'modernLayout' ], 'leftAll' ); ?>>
                                            <?php _ex( 'All messages at left side', 'Settings page', 'bp-better-messages' ); ?>
                                        </label>
                                    </fieldset>
                                </fieldset>
                            </td>
                        </tr>

                        <tr valign="top" class="">
                            <th scope="row" valign="top">
                                <?php _ex( 'More customization options', 'Settings page','bp-better-messages' ); ?>
                            <td>
                                <?php $url = Better_Messages()->customize->customization_link([
                                    //'section' => 'better_messages_general'
                                    'panel' => 'better_messages'
                                ]); ?>
                                <a href="<?php echo $url; ?>"  class="button bm-customize-btn" target="_blank"><?php _ex( 'Customization', 'Settings page','bp-better-messages' ); ?> <span class="dashicons dashicons-external"></span></a>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <?php _ex( 'User Statuses', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'Allow users to set their status: Online, Away or Do not disturb', 'Settings page', 'bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <fieldset>
                                    <label>
                                        <input type="checkbox" name="userStatuses" <?php checked( $this->settings[ 'userStatuses' ], '1' ); ?> value="1" <?php if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() || $this->settings[ 'mechanism' ] == 'ajax') echo 'disabled'; ?>>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>

                        <tr valign="top" class="">
                            <th scope="row" valign="top">
                                <?php _ex( 'Combined View', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'Always show conversation list on left side of conversation', 'Settings page', 'bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <input name="combinedView" type="checkbox" <?php checked( $this->settings[ 'combinedView' ], '1' ); ?> value="1" />
                            </td>
                        </tr>

                        <tr valign="top" class="">
                            <th scope="row" valign="top">
                                <?php _ex( 'Full Screen Mode', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'Show full screen button for desktop browsers', 'Settings page', 'bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <input name="desktopFullScreen" type="checkbox" <?php checked( $this->settings[ 'desktopFullScreen' ], '1' ); ?> value="1" />
                            </td>
                        </tr>

                        <tr valign="top" class="">
                            <th scope="row" valign="top">
                                <?php _ex( 'Show My Profile Button', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'Show my profile button in the messages interface', 'Settings page', 'bp-better-messages' ); ?></p>

                            </th>
                            <td>
                                <input name="myProfileButton" type="checkbox" <?php checked( $this->settings[ 'myProfileButton' ], '1' ); ?> value="1" />
                            </td>
                        </tr>

                        <tr valign="top" class="">
                            <th scope="row" valign="top">
                                <?php _ex( 'Enable Administration', 'Settings page', 'bp-better-messages' ); ?>
                                <p style="font-size: 10px;"><?php _ex( 'Enable administration page in WordPress admin', 'Settings page', 'bp-better-messages' ); ?></p>
                            </th>
                            <td>
                                <input name="messagesViewer" type="checkbox" <?php checked( $this->settings[ 'messagesViewer' ], '1' ); ?> value="1" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col secondary-col">
                    <a class="bp-better-messages-facebook" href="https://www.facebook.com/groups/bpbettermessages/" target="_blank"><span class="dashicons dashicons-facebook"></span> Join Facebook Group</a>
                    <br>
                    <a class="bp-better-messages-roadmap" href="https://www.wordplus.org/roadmap" target="_blank"><span class="dashicons dashicons-schedule"></span> Roadmap & Feature Suggestions</a>
                    <br>
                    <a class="bp-better-messages-roadmap" style="background: #20441e" href="https://www.wordplus.org/bm-translate" target="_blank">
                        <span class="dashicons dashicons-translation"></span> <?php _ex('Translate Better Messages', 'Settings page', 'bp-better-messages'); ?>
                    </a>

                    <?php
                    if( ! bpbm_fs()->is_trial_utilized() && ! Better_Messages()->functions->can_use_premium_code() ){
                        $url = bpbm_fs()->get_trial_url();
                        echo '<br><a class="bp-better-messages-trial" href="' . $url . '">Start WebSocket 3 Days Trial</a>';
                    }
                    ?>
                    <?php if( bpbm_fs()->is_premium() && ! Better_Messages()->functions->can_use_premium_code() ){
                        $user = bpbm_fs()->get_user();

                        $url = '#';

                        if( $user ) {
                            $url = admin_url('admin.php?page=bp-better-messages-account');
                        } else{
                            $url = bpbm_fs()->get_reconnect_url();
                        }
                        ?>
                        <div class="bp-better-messages-connection-check bpbm-error">
                            <p><?php _ex('This website using WebSocket plugin version, but has no active license attached.', 'Settings page', 'bp-better-messages'); ?></p>
                            <p><?php echo sprintf(_x('If you have license, and it must be attached to this website, try to press sync button in <a href="%s">your account</a>.', 'Settings page','bp-better-messages'), $url); ?></p>
                        </div>
                    <?php } else if( Better_Messages()->functions->can_use_premium_code() ){
                    if( ! class_exists('Better_Messages_WebSocket') ) {  ?>
                        <div class="bp-better-messages-connection-check bpbm-error">
                            <p><?php echo sprintf(_x('Seems like this website has active WebSocket License, but you are still using free version of plugin. If you already have WebSocket version installed, then delete free version completely. Try to download and install plugin from <a href="%s">your account</a> page.', 'Settings page', 'bp-better-messages'), $url); ?></p>
                        </div>
                    <?php
                    } else { ?>
                        <div class="bp-better-messages-connection-check">
                            <p><?php echo sprintf(_x('This website has domain name <b>%s</b>',  'Settings page','bp-better-messages'), Better_Messages_WebSocket()->site_id); ?></p>
                            <p class="bpbm-checking-sync"><span class="dashicons dashicons-update-alt" style="animation:bpbm-spin 4s linear infinite;"></span> <?php _ex('Double-checking if WebSocket server know about this domain and sync is fine', 'Settings page', 'bp-better-messages'); ?></p>
                        </div>
                        <script type="text/javascript">
                            jQuery(document).ready(function($){
                                var checking = $('.bpbm-checking-sync');
                                $.post('https://license.bpbettermessages.com/checksyncv4.php', {
                                    site_id    : '<?php
                                        $site = bpbm_fs()->get_site();
                                        if( $site) {
                                            echo bpbm_fs()->get_site()->id;
                                        } else {
                                            echo 'false';
                                        } ?>',
                                    domain     : '<?php echo Better_Messages_WebSocket()->site_id; ?>',
                                    secret_key : '<?php echo base64_encode(Better_Messages_WebSocket()->secret_key); ?>'
                                }, function(response){
                                    if( response.success ){
                                        checking.parent().addClass('bpbm-ok');
                                        var message = '<span class="dashicons dashicons-yes-alt"></span> <?php echo esc_attr_x('All good, WebSocket server know about this domain, all should be working good.', 'Settings page', 'bp-better-messages'); ?>';
                                    } else {
                                        checking.parent().addClass('bpbm-error');

                                        var message = '<span class="dashicons dashicons-dismiss"></span> <?php echo esc_attr_x('WebSocket server dont know about this domain, realtime functionality will not work. If you just activated the license at this website need to wait some time for system to sync your license with websocket servers. It usually takes up to 15 minutes, but in some cases can take longer.', 'Settings page', 'bp-better-messages'); ?>';

                                        if( response.data.license_attached !== false ){
                                            message += '<br><br>This license is currently attached to <strong>' + response.data.license_attached + '</strong>';
                                        }
                                    }

                                    if( response.data.locked_to !== false ){
                                        message += '<br><br>This license is currently locked to <strong>' + response.data.locked_to + '</strong>';
                                        message += '<br><br> <span class="button bpbm-unlock-license" data-domain="' + response.data.locked_to + '">Unlock license from ' + response.data.locked_to + '</span>';
                                    } else {
                                        message += '<br><br>This license is currently not locked. Its recommended to lock your license to your live domain.';
                                        message += '<br><br> <span class="button bpbm-lock-license">Lock license to <?php echo Better_Messages_WebSocket()->site_id; ?></span>';
                                    }

                                    checking.html( message );

                                    $('.bpbm-unlock-license').click(function(event){
                                        var domain = $('.bpbm-unlock-license').attr('data-domain');
                                        if( confirm( 'Confirm the unlock of license from ' + domain ) ) {
                                            $.post('https://license.bpbettermessages.com/changeLock.php', {
                                                domain: domain,
                                                secret_key: '<?php echo base64_encode(Better_Messages_WebSocket()->secret_key); ?>',
                                                action: 'unlock'
                                            }, function (response) {
                                                location.reload();
                                            });
                                        }
                                    });

                                    $('.bpbm-lock-license').click(function(event){
                                        if( confirm( 'Confirm the lock of license to <?php echo Better_Messages_WebSocket()->site_id; ?>' ) ) {
                                            $.post('https://license.bpbettermessages.com/changeLock.php', {
                                                domain: '<?php echo Better_Messages_WebSocket()->site_id; ?>',
                                                secret_key: '<?php echo base64_encode(Better_Messages_WebSocket()->secret_key); ?>',
                                                action: 'lock'
                                            }, function (response) {
                                                location.reload();
                                            });
                                        }
                                    });
                                });
                            });
                        </script>
                    <?php } } ?>
                </div>
            </div>
        </div>

        <div id="messaging" class="bpbm-tab">
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <?php _ex( 'Starting new conversation', 'Settings page','bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Settings related to starting new conversations by website members', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr>
                                    <td>
                                        <input name="fastStart" type="checkbox" <?php checked( $this->settings[ 'fastStart' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Fast Start', 'Settings page','bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'When clicking the Private Message button user will be immediately redirected to new conversation instead of new conversation screen', 'Settings page','bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="singleThreadMode" type="checkbox"  <?php checked( $this->settings[ 'singleThreadMode' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Prevent creating multiple conversations with same member', 'Settings page','bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'This will not allow members to create new private conversation if member already have conversation with recipient', 'Settings page','bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="newThreadMode" type="checkbox"  <?php checked( $this->settings[ 'newThreadMode' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Force creating new conversation even if its already exists with member', 'Settings page','bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'This will not allow members to continue the existing conversation from New Conversation screen', 'Settings page','bp-better-messages' ); ?></p>
                                    </th>
                                </tr>

                                <tr valign="top" class="">
                                    <td>
                                        <input name="disableGroupThreads" type="checkbox" <?php checked( $this->settings[ 'disableGroupThreads' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Disable Conversations with multiple participants', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Don`t allow to create conversations with multiple participants', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>

                                <tr valign="top" class="">
                                    <td>
                                        <input name="disableSubject" type="checkbox" <?php checked( $this->settings[ 'disableSubject' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Disable subject', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Disable subject when starting new conversation', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( 'This will also disable subjects for already started conversations', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php _ex( 'Searching users', 'Settings page','bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Settings related to searching users while starting the conversation', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="disableUsersSearch" type="checkbox" <?php checked( $this->settings[ 'disableUsersSearch' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Disable users search', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Disables suggestions when starting new conversation. (Admins can search all users even if this option is disabled).', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>

                                <tr valign="top" class="">
                                    <td>
                                        <input name="searchAllUsers" type="checkbox" <?php if($this->settings[ 'disableUsersSearch' ] === '1') echo 'disabled'; ?> <?php checked( $this->settings[ 'searchAllUsers' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Search all users', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enable search among all users when starting new conversation', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( 'Otherwise search works only within friends. (Admins can search all users even if this option is disabled).', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>

                                <tr valign="top" class="">
                                    <td>
                                        <input name="enableUsersSuggestions" type="checkbox" <?php if($this->settings[ 'disableUsersSearch' ] === '1') echo 'disabled'; ?> <?php checked( $this->settings[ 'enableUsersSuggestions' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Users suggestions', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enable users suggestions on new conversations screen for the fast selection of users', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( 'Friends are listed first, after that listed lastly active users', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php _ex( 'Conversations functions', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="pinnedThreads" type="checkbox" <?php checked( $this->settings[ 'pinnedThreads' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Enable Pinned Conversations', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Users will be able to pin specific conversations to the top of conversations list', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                <tr>
                    <th scope="row">
                        <?php _ex( 'Messages functions', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="enableReplies" type="checkbox" <?php checked( $this->settings[ 'enableReplies' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Enable Replies', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Users will be able to select messages to reply', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="allowEditMessages" type="checkbox" <?php checked( $this->settings[ 'allowEditMessages' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>

                                        <?php _ex( 'Allow users to edit messages', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Allow users to edit their messages only', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>

                                <tr valign="top" class="">
                                    <td>
                                        <input name="pinnedMessages" type="checkbox" <?php checked( $this->settings[ 'pinnedMessages' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Allow users to pin messages', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Allow conversation moderators to pin messages to the top of messages list', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>

                                <tr valign="top" class="">
                                    <td>
                                        <input name="allowDeleteMessages" type="checkbox" <?php checked( $this->settings[ 'allowDeleteMessages' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Allow users to delete messages', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Allow users to delete their messages only', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr valign="top">
                                    <td style="padding:0"></td>
                                    <th style="padding:0">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td style="padding:0">
                                                    <input style="vertical-align: middle" id="delete-method-1" type="radio" name="deleteMethod" value="delete" <?php checked( $this->settings[ 'deleteMethod' ], 'delete' ); ?>>
                                                    <label for="delete-method-1">
                                                        <?php _ex( 'Delete message completely', 'Settings page', 'bp-better-messages' ); ?>
                                                        <p style="font-size: 10px;"><?php _ex( 'Delete message completely so it will dissapear from messages list', 'Settings page', 'bp-better-messages' ); ?></p>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0">
                                                    <input style="vertical-align: middle" id="delete-method-2" type="radio" name="deleteMethod" value="replace" <?php checked( $this->settings[ 'deleteMethod' ], 'replace' ); ?>>
                                                    <label for="delete-method-2">
                                                        <?php _ex( 'Replace message content', 'Settings page', 'bp-better-messages' ); ?>
                                                        <p style="font-size: 10px;"><?php _ex( 'Replace message content with "This message was deleted" label', 'Settings page', 'bp-better-messages' ); ?></p>
                                                    </label>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="oEmbedEnable" type="checkbox" <?php checked( $this->settings[ 'oEmbedEnable' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Enable oEmbed for popular services', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'oEmbed YouTube, Vimeo, VideoPress, Flickr, DailyMotion, Kickstarter, Meetup.com, Mixcloud, SoundCloud and more', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="enableNiceLinks" type="checkbox" <?php checked( $this->settings[ 'enableNiceLinks' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Enable link previews', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Nice links finds link in user messages, fetching title and description if available and shows it at the bottom of message', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php _ex( 'Messages reactions', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="enableReactions" type="checkbox" <?php checked( $this->settings[ 'enableReactions' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>

                                        <?php _ex( 'Enable Reactions', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Users will be able to react messages with emojis', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( 'You can select reactions in Integrations -> Emojis Tab', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="enableReactionsPopup" type="checkbox" <?php checked( $this->settings[ 'enableReactionsPopup' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Detailed Reactions', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Users will be able to see who reacted to the message', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php _ex( 'Messages status', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input type="checkbox" name="messagesStatus" <?php checked( $this->settings[ 'messagesStatus' ], '1' ); ?> value="1" <?php if(! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() || $this->settings[ 'mechanism' ] == 'ajax') echo 'disabled'; ?>>
                                    </td>
                                    <th>
                                        <?php _ex( 'Messages Status', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enable messages status functionality, which show if your message was sent, delivered or seen', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input type="checkbox" name="messagesStatusList" <?php checked( $this->settings[ 'messagesStatusList' ], '1' ); ?> value="1" <?php if(! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() || $this->settings[ 'mechanism' ] == 'ajax') echo 'disabled'; ?>>
                                    </td>
                                    <th>
                                        <?php _ex( 'Messages Status in Conversation list', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Show messages statuses in conversation list', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input type="checkbox" name="messagesStatusDetailed" <?php checked( $this->settings[ 'messagesStatusDetailed' ], '1' ); ?> value="1" <?php if(! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() || $this->settings[ 'mechanism' ] == 'ajax') echo 'disabled'; ?>>
                                    </td>
                                    <th>
                                        <?php _ex( 'Detailed Messages Status', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Allow to see who seen the message in group conversations', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php _ex( 'Multiple participants functions', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="privateThreadInvite" type="checkbox" <?php checked( $this->settings[ 'privateThreadInvite' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Allow invite more participants to private conversations', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Allow users to invite more participants to private conversations converting them to group conversation', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( '(admins can add more participants even if this option is disabled)', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="allowGroupLeave" type="checkbox" <?php checked( $this->settings[ 'allowGroupLeave' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Allow users to leave conversations with multiple participants', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Allow users to leave conversations with multiple participants (creator can`t leave conversation he started)', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php _ex( 'Deleted conversations', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="deletedBehaviour" type="radio" <?php checked( $this->settings[ 'deletedBehaviour' ], 'ignore' ); ?> value="ignore" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Ignore when starting new conversation', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'The user will not be proposed to continue deleted conversation', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>

                                <tr valign="top" class="">
                                    <td>
                                        <input name="deletedBehaviour" type="radio" <?php checked( $this->settings[ 'deletedBehaviour' ], 'include' ); ?> value="include" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Include when starting new conversation', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'If the user already has conversation with the selected recipient, but its deleted, it will be proposed to continue conversation by restoring it', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php _ex( 'Miscellaneous', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="disableEnterForDesktop" type="checkbox" <?php checked( $this->settings[ 'disableEnterForDesktop' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Disable Send on Enter for Desktop devices', 'Settings page','bp-better-messages' ); ?>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="deleteMessagesOnUserDelete" type="checkbox" <?php checked( $this->settings[ 'deleteMessagesOnUserDelete' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Delete user messages when his account is deleted from website', 'Settings page','bp-better-messages' ); ?>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>


        <div id="mini-widgets" class="bpbm-tab">
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <?php _ex( 'Widgets', 'Settings page','bp-better-messages' ); ?>

                        <?php $url = Better_Messages()->customize->customization_link([
                            'section' => 'better_messages_mini_widgets'
                        ]); ?>

                        <br><br>

                        <a href="<?php echo $url; ?>"  class="button bm-customize-btn" target="_blank"><?php _ex( 'Customization', 'Settings page','bp-better-messages' ); ?> <span class="dashicons dashicons-external"></span></a>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input type="checkbox" name="miniThreadsEnable" <?php checked( $this->settings[ 'miniThreadsEnable' ], '1' ); ?> value="1" <?php if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() || $this->settings[ 'mechanism' ] == 'ajax') echo 'disabled'; ?>>
                                    </td>
                                    <th>
                                        <?php _ex( 'Mini Conversations', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enables mini conversation list widget fixed to the bottom of browser window', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input type="checkbox" name="miniChatsEnable" <?php checked( $this->settings[ 'miniChatsEnable' ], '1' ); ?> value="1" <?php if(! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() || $this->settings[ 'mechanism' ] == 'ajax') echo 'disabled'; ?>>
                                    </td>
                                    <th>
                                        <?php _ex( 'Mini Chats', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enables mini chats fixed to the bottom of browser window', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="enableMiniCloseButton" type="checkbox" <?php checked( $this->settings[ 'enableMiniCloseButton' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Add close button', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Adds additional close button when widgets are opened', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <?php _ex( 'Mini Chats', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="miniChatAudioCall" type="checkbox" <?php checked( $this->settings[ 'miniChatAudioCall' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                                    </td>
                                    <th>
                                        <?php _ex( 'Audio Call Button', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Add audio call button to the mini chats', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="miniChatVideoCall" type="checkbox" <?php checked( $this->settings[ 'miniChatVideoCall' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                                    </td>
                                    <th>
                                        <?php _ex( 'Video Call Button', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Add video call button to the mini chats', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="miniChatDisableSync" type="checkbox" <?php checked( $this->settings[ 'miniChatDisableSync' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                                    </td>
                                    <th>
                                        <?php _ex( 'Disable Sync Between Tabs', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Disable synchronization of mini chats between browser tabs.', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( 'If enabled the open mini chats will not be saved and will not be synced between browser tabs', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>

        <div id="mobile" class="bpbm-tab">
            <table class="form-table">
                <tbody>

                <tr>
                    <th scope="row">
                        <?php _ex( 'Mobile Mode', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="mobileFullScreen" type="checkbox" <?php checked( $this->settings[ 'mobileFullScreen' ], '1' ); ?> value="1" />
                                        <script type="text/javascript">
                                            jQuery('input[name="mobileFullScreen"]').on('change', function(event){
                                                var autoFullScreen = jQuery('input[name="autoFullScreen"]');
                                                var tapToOpen = jQuery('input[name="tapToOpenMsg"]');
                                                if( event.target.checked ){
                                                    autoFullScreen.prop('disabled', false);
                                                    tapToOpen.prop('disabled', false);
                                                } else {
                                                    autoFullScreen.prop('disabled', true);
                                                    tapToOpen.prop('disabled', true);
                                                }
                                            })
                                        </script>
                                    </td>
                                    <th>
                                        <?php _ex( 'Enable Full Screen Mode', 'Settings page','bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enable full screen mode for Mobile Devices', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;color: green;"><strong><?php _ex( 'Recommended', 'Settings page', 'bp-better-messages' ); ?></strong></p>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="autoFullScreen" type="checkbox" <?php checked( $this->settings[ 'autoFullScreen' ], '1' ); ?> <?php if($this->settings[ 'mobileFullScreen' ] == '0') echo 'disabled'; ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Auto open Full Screen Mode', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Auto open full screen mode when opening messages page', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;color: green;"><strong><?php _ex( 'Recommended', 'Settings page', 'bp-better-messages' ); ?></strong></p>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="tapToOpenMsg" type="checkbox" <?php checked( $this->settings[ 'tapToOpenMsg' ], '1' ); ?> <?php if($this->settings[ 'mobileFullScreen' ] == '0') echo 'disabled'; ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Tap To Open Message', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Add "Tap to open" message to message container', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <?php _ex( 'Mobile Chat at Any Page', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td>
                                        <input name="mobilePopup" type="checkbox" <?php checked( $this->settings[ 'mobilePopup' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Enable', 'Settings page','bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enable Mobile Chat at Any Page', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( 'Adds button fixed to the right corner on mobile devices, on click fully featured messaging will appear in full screen mode', 'Settings page','bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <th colspan="2" style="padding-left: 20px; padding-bottom: 0">
                                        <?php _ex( 'Position', 'Settings page','bp-better-messages' ); ?>
                                    </th>
                                </tr>
                                <tr valign="top" class="">
                                    <td colspan="2">
                                        <fieldset>
                                            <fieldset>
                                                <label><input type="radio" name="mobilePopupLocation" value="left" <?php checked( $this->settings[ 'mobilePopupLocation' ], 'left' ); ?>>
                                                    <?php _ex( 'Left', 'Settings page', 'bp-better-messages' ); ?>
                                                </label>
                                                <br>
                                                <label><input type="radio" name="mobilePopupLocation" value="right" <?php checked( $this->settings[ 'mobilePopupLocation' ], 'right' ); ?>>
                                                    <?php _ex( 'Right', 'Settings page', 'bp-better-messages' ); ?>
                                                </label>
                                            </fieldset>
                                        </fieldset>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <?php _ex( 'Mobile Onsite Notifications', 'Settings page','bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <td colspan="2">
                                        <p style="font-weight: bold;margin-bottom: 5px;"><?php _ex( 'Position', 'Settings page','bp-better-messages' ); ?></p>
                                        <fieldset>
                                            <fieldset>
                                                <label><input type="radio" name="mobileOnsiteLocation" value="auto" <?php checked( $this->settings[ 'mobileOnsiteLocation' ], 'auto' ); ?>>
                                                    <?php _ex( 'Automatic position', 'Settings page', 'bp-better-messages' ); ?>
                                                    <p style="font-size: 10px;"><?php _ex( 'Show popup notifications on bottom, but in conversation screen shows it on top to not overlay over the reply area.', 'Settings page', 'bp-better-messages' ); ?></p>
                                                </label>
                                                <br>
                                                <label><input type="radio" name="mobileOnsiteLocation" value="top" <?php checked( $this->settings[ 'mobileOnsiteLocation' ], 'top' ); ?>>
                                                    <?php _ex( 'Always on Top', 'Settings page', 'bp-better-messages' ); ?>
                                                    <p style="font-size: 10px;"><?php _ex( 'Always show popup notifications on top', 'Settings page', 'bp-better-messages' ); ?></p>
                                                </label>
                                                <br>
                                                <label><input type="radio" name="mobileOnsiteLocation" value="bottom" <?php checked( $this->settings[ 'mobileOnsiteLocation' ], 'bottom' ); ?>>
                                                    <?php _ex( 'Always on Bottom', 'Settings page', 'bp-better-messages' ); ?>
                                                    <p style="font-size: 10px;"><?php _ex( 'Always show popup notifications on bottom', 'Settings page', 'bp-better-messages' ); ?></p>
                                                </label>
                                            </fieldset>
                                        </fieldset>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Margin from bottom for Mobile Chat button (px)', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input type="number" name="mobilePopupLocationBottom" value="<?php echo esc_attr( $this->settings[ 'mobilePopupLocationBottom' ] ); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Dont show Mobile Chat button', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Dont show mobile chat button to following roles', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <ul class="bp-better-messages-roles-list">
                            <?php foreach( $roles as $slug => $role ){ ?>
                                <li><input id="<?php echo $slug; ?>_10" type="checkbox" name="restrictMobilePopup[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ 'restrictMobilePopup' ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_10"><?php echo $role['name']; ?></label></li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Disable Send on Enter for Mobile Devices', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input name="disableEnterForTouch" type="checkbox" <?php checked( $this->settings[ 'disableEnterForTouch' ], '1' ); ?> <?php if($this->settings[ 'mobileFullScreen' ] == '0') echo 'disabled'; ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Hide Possible Overlaying Elements', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'If in mobile view something overlaying the messages enable this option', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="hidePossibleBreakingElements" type="checkbox" <?php checked( $this->settings[ 'hidePossibleBreakingElements' ], '1' ); ?> <?php if($this->settings[ 'mobileFullScreen' ] == '0') echo 'disabled'; ?> value="1" />
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        <div id="attachments" class="bpbm-tab">
            <?php
            $formats = wp_get_ext_types();
            unset($formats['code']);
            ?>
            <table class="form-table">
                <tbody>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable files', 'Settings page','bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Enable file sharing between users', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="attachmentsEnable" type="checkbox" <?php checked( $this->settings[ 'attachmentsEnable' ], '1' ); ?> value="1" />
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Hide Attachments', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Hides attachments from media gallery', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="attachmentsHide" type="checkbox" <?php checked( $this->settings[ 'attachmentsHide' ], '1' ); ?> value="1" />
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Random file names', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Changes file names to random to improve users privacy', 'Settings page','bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="attachmentsRandomName" type="checkbox" <?php checked( $this->settings[ 'attachmentsRandomName' ], '1' ); ?> value="1" />
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Allow to capture photos', 'Settings page','bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow to capture photos from user webcam', 'Settings page','bp-better-messages' ); ?></p>
                        <p style="font-size: 10px;"><?php _ex( '.jpg or .png format must be enabled', 'Settings page','bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="attachmentsAllowPhoto" type="checkbox" <?php checked( $this->settings[ 'attachmentsAllowPhoto' ], '1' ); ?> value="1" />
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Delete attachment after', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Set 0 to not delete attachments', 'Settings page','bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="attachmentsRetention" type="number" value="<?php esc_attr_e( $this->settings[ 'attachmentsRetention' ] ); ?>"/> days
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Max attachment size', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input name="attachmentsMaxSize" type="number" value="<?php esc_attr_e( $this->settings[ 'attachmentsMaxSize' ] ); ?>"/> Mb
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Max number of attachments per message', 'Settings page', 'bp-better-messages' ); ?>
                        <p><?php _ex( 'Set 0 to not limit', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="attachmentsMaxNumber" type="number" value="<?php esc_attr_e( $this->settings[ 'attachmentsMaxNumber' ] ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _ex( 'Allowed formats', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td class="attachments-formats">
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _ex( 'Allowed formats', 'Settings page', 'bp-better-messages' ); ?></span>
                            </legend>
                            <?php foreach($formats as $type => $extensions){
                                ?>
                                <ul>
                                    <strong><?php echo ucfirst($type); ?></strong>
                                    <?php foreach($extensions as $ext){ ?>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="attachmentsFormats[]" value="<?php echo $ext; ?>" <?php if(in_array($ext, $this->settings[ 'attachmentsFormats' ])) echo 'checked="checked"'; ?>>
                                                <?php echo $ext; ?>
                                            </label>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div id="notifications" class="bpbm-tab">
            <table class="form-table">
                <tbody>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Browser Tab Notifications', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Show unread conversations number in website title (browser tab)', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="titleNotifications" type="checkbox" <?php checked( $this->settings[ 'titleNotifications' ], '1' ); ?> value="1" />
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Mute Conversations', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'When enabled users will be able to mute conversations', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="allowMuteThreads" type="checkbox" <?php checked( $this->settings[ 'allowMuteThreads' ], '1' ); ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Send email notifications every (minutes)', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Set to 0 to disable', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input type="number" name="notificationsInterval" value="<?php echo esc_attr( $this->settings[ 'notificationsInterval' ] ); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Send email after user is not online for (minutes)', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Set to 0 to always send email notifications to user', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input type="number" name="notificationsOfflineDelay" value="<?php echo esc_attr( $this->settings[ 'notificationsOfflineDelay' ] ); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Stop messages notifications to be added to BuddyPress Notifications Bell', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'This will work only with setting above', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="stopBPNotifications" type="checkbox" <?php checked( $this->settings[ 'stopBPNotifications' ], '1' ); ?> value="1" />
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable Browser Push Notifications', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to enable web push notifications, so they can receive messages even with closed website', 'Settings page', 'bp-better-messages' ); ?></p>
                        <p style="font-size: 10px;"><?php _ex( 'Also adds notifications, when user has website opened in other tab', 'Settings page', 'bp-better-messages' ); ?></p>
                        <p style="font-size: 10px;"><?php _ex( 'Supported in all major browsers like: Chrome, Opera, Firefox, IE, Edge and others', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <?php
                        $disabled = ! BP_Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium();
                        if( apply_filters( 'better_messages_3rd_party_push_active', false ) ) {
                            $disabled = true;
                        }
                        ?>
                        <input name="enablePushNotifications" type="checkbox" <?php checked( $this->settings[ 'enablePushNotifications' ], '1' ); ?> value="1" <?php  if( $disabled ) echo 'disabled'; ?> />
                        <?php echo apply_filters('better_messages_push_message_in_settings', ''); ?>
                        <?php BP_Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <?php _ex( 'Onsite notifications position', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <fieldset>
                                <label><input type="radio" name="onsitePosition" value="right" <?php checked( $this->settings[ 'onsitePosition' ], 'right' ); ?>>
                                    <?php _ex( 'Right', 'Settings page', 'bp-better-messages' ); ?>
                                </label>
                                <br>
                                <label><input type="radio" name="onsitePosition" value="left" <?php checked( $this->settings[ 'onsitePosition' ], 'left' ); ?>>
                                    <?php _ex( 'Left', 'Settings page', 'bp-better-messages' ); ?>
                                </label>
                            </fieldset>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Disable onsite notifications about new messages', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input name="disableOnSiteNotification" type="checkbox" <?php checked( $this->settings[ 'disableOnSiteNotification' ], '1' ); ?> value="1" />
                    </td>
                </tr>


                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Additional realtime onsite notifications', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><a href="https://www.wordplus.org/knowledge-base/additional-on-site-notifications/" target="_blank"><?php _ex('How it works?', 'Settings page', 'bp-better-messages'); ?></a></p>
                    </th>
                    <td>
                        <div style="position: relative">
                            <?php $license_message = Better_Messages()->functions->license_proposal( true );
                            if( ! empty( $license_message ) ) { ?>
                                <div style="box-sizing: border-box;position:absolute;background: #ffffffb8;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                    <?php echo $license_message; ?>
                                </div>
                            <?php } ?>
                            <ul class="bp-better-messages-roles-list">
                                <li>
                                    <input id="friendsOnSiteNotifications" type="checkbox" name="friendsOnSiteNotifications" value="1" <?php checked( $this->settings[ 'friendsOnSiteNotifications' ], '1' ); ?> <?php if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> >
                                    <label for="friendsOnSiteNotifications"><?php _ex('BuddyPress Friends', 'Settings page', 'bp-better-messages'); ?></label>
                                </li>
                                <li>
                                    <input id="groupsOnSiteNotifications" type="checkbox" name="groupsOnSiteNotifications" value="1" <?php checked( $this->settings[ 'groupsOnSiteNotifications' ], '1' ); ?> <?php if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> >
                                    <label for="groupsOnSiteNotifications"><?php _ex('BuddyPress Groups', 'Settings page', 'bp-better-messages'); ?></label>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Auto create BuddyPress Email template if its missing', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;">
                            <?php _ex( 'You need to disable it only if you modified email template and plugin try to replace it', 'Settings page', 'bp-better-messages' ); ?>
                        </p>
                    </th>
                    <td>
                        <input name="createEmailTemplate" type="checkbox" <?php checked( $this->settings[ 'createEmailTemplate' ], '1' ); ?> <?php  if( ! function_exists('bp_send_email') ) echo 'disabled'; ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Message notification sound volume', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'From 0 to 100 (0 to disable)', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input type="number" name="notificationSound" min="0" max="100" value="<?php echo esc_attr( $this->settings[ 'notificationSound' ] ); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Message sent sound volume', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'From 0 to 100 (0 to disable)', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input type="number" name="sentSound" min="0" max="100" value="<?php echo esc_attr( $this->settings[ 'sentSound' ] ); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Incoming call sound volume', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'From 0 to 100 (0 to disable)', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input type="number" name="callSound" min="0" max="100" value="<?php echo esc_attr( $this->settings[ 'callSound' ] ); ?>" <?php  if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> >
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Outgoing call sound volume', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'From 0 to 100 (0 to disable)', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input type="number" name="dialingSound" min="0" max="100" value="<?php echo esc_attr( $this->settings[ 'dialingSound' ] ); ?>" <?php  if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> >
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div id="rules" class="bpbm-tab">
            <table class="form-table">
                <tbody>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Allow users to restrict who can start conversations with them', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to select who start conversations with them in plugin user settings', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="allowUsersRestictNewThreads" type="checkbox" <?php checked( $this->settings[ 'allowUsersRestictNewThreads' ], '1' ); ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Allow users to block other users', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to block other users from sending them messages (admins cant be blocked)', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="allowUsersBlock" type="checkbox" <?php checked( $this->settings[ 'allowUsersBlock' ], '1' ); ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Restrict user role from blocking other users', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Selected roles will not be able to block other users if previous option is enabled', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <div style="
                            display: flex;
                            flex-wrap: nowrap;
                            justify-content: center;
                            align-items: center;
                            flex-direction: row;
                            width: 100%;
                        ">
                            <div style="width: 100%;margin-right: 5px">
                                <h4><?php _ex("Roles which can't block other users", 'Settings page', 'bp-better-messages'); ?></h4>
                                <ul class="bp-better-messages-roles-list">
                                    <?php foreach( $roles as $slug => $role ){ ?>
                                        <li><input id="<?php echo $slug; ?>_block" type="checkbox" name="restrictBlockUsers[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ "restrictBlockUsers" ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_block"><?php echo $role['name']; ?></label></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div style="width: 100%">
                                <h4><?php _ex("Roles which can't be blocked by other users", 'Settings page', 'bp-better-messages'); ?></h4>
                                <ul class="bp-better-messages-roles-list">
                                    <?php foreach( $roles as $slug => $role ){ ?>
                                        <li><input id="<?php echo $slug; ?>_block_2" type="checkbox" name="restrictBlockUsersImmun[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ "restrictBlockUsersImmun" ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_block_2"><?php echo $role['name']; ?></label></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>


                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Rate limiting new conversations (seconds)', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Limit new conversations creation to prevent users spam', 'Settings page', 'bp-better-messages' ); ?></p>
                        <p style="font-size: 10px;"><?php _ex( 'Set to 0 to disable', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input type="number" name="rateLimitNewThread" value="<?php echo esc_attr( $this->settings[ 'rateLimitNewThread' ] ); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Restrict users from deleting conversations', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Disable users from being able to delete conversation (admin always can delete)', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="restrictThreadsDeleting" type="checkbox" <?php checked( $this->settings[ 'restrictThreadsDeleting' ], '1' ); ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Role to Role restrictions', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Disable users from being able to write each other based on user role', 'Settings page', 'bp-better-messages' ); ?></p>
                        <p style="font-size: 10px;"><?php _ex( 'Resrticted users will not also appear in search results', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <div class="bp-better-messages-roles-list" style="max-height: initial;">
                            <div style="margin-bottom: 5px"><?php _ex( 'By default all users', 'Settings page', 'bp-better-messages' ); ?>:</div>

                            <div style="margin-bottom: 8px;">
                                <label style="display:block;margin-bottom:5px;">
                                    <input type="radio" name="restrictRoleType" value="allow" <?php checked( $this->settings[ 'restrictRoleType' ], 'allow' ); ?> />
                                    <?php _ex( 'Allowed to message everyone', 'Settings page', 'bp-better-messages' ); ?>
                                </label>
                                <label style="display:block;">
                                    <input type="radio" name="restrictRoleType" value="disallow" <?php checked( $this->settings[ 'restrictRoleType' ], 'disallow' ); ?> />
                                    <?php _ex( 'Not allowed to message everyone', 'Settings page', 'bp-better-messages' ); ?>
                                </label>
                            </div>

                            <div class="restrictRoleBlockAllowed" style="margin-bottom: 8px;">
                                <p style="color:#727272;font-style:italic;font-size:90%;"><?php _ex( 'Users are able to message each other by default, to restrict some roles add the rules below', 'Settings page', 'bp-better-messages' ); ?></p>
                            </div>

                            <div class="restrictRoleBlockDisAllowed" style="margin-bottom: 8px;">
                                <p style="color:#727272;font-style:italic;font-size:90%;"><?php _ex( 'Users are not able to message each other by default, add rules below to define roles which will be able to message other users', 'Settings page', 'bp-better-messages' ); ?></p>

                                <div style="margin-top: 5px;">
                                    <label><?php _ex( 'Message', 'Settings page', 'bp-better-messages' ); ?></label>
                                    <br>
                                    <input style="margin-top: 5px;width: 100%;" type="text" name="restrictRoleMessage" value="<?php esc_attr_e(wp_unslash($this->settings['restrictRoleMessage'])); ?>">
                                </div>
                            </div>

                            <?php
                            $roleBlock = $this->settings['restrictRoleBlock'];

                            if( count( $roleBlock ) === 0 ){ ?>
                                <div class="role-block-empty"><?php echo esc_attr_x('No rules added',  'Settings page', 'bp-better-messages'); ?></div>
                            <?php } ?>

                            <table style="margin:0 -8px;">
                                <thead>
                                <tr>
                                </tr>
                                <tr>
                                    <th><?php _ex( 'From', 'Settings page', 'bp-better-messages' ); ?></th>
                                    <th><?php _ex( 'To', 'Settings page', 'bp-better-messages' ); ?></th>
                                    <th class="restrictRoleBlockAllowed"><?php _ex( 'Message', 'Settings page', 'bp-better-messages' ); ?></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="role-block-rows">
                                <?php foreach( $roleBlock as $index => $value ){ ?>
                                    <tr>
                                        <td>
                                            <select name="restrictRoleBlock[<?php esc_attr_e($index); ?>][from]" data-name="restrictRoleBlock[index][from]">
                                                <?php foreach( $roles as $slug => $role ){
                                                    echo '<option value="' . esc_attr( $slug ) . '" ' . selected( $value['from'], $slug, false ) . '>' . esc_attr( $role['name'] ) . '</option>';
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="restrictRoleBlock[<?php esc_attr_e($index); ?>][to]" data-name="restrictRoleBlock[index][to]">
                                                <?php foreach( $all_roles as $slug => $role ){
                                                    echo '<option value="' . esc_attr( $slug ) . '" ' . selected( $value['to'], $slug, false ) . '>' . esc_attr( $role['name'] ) . '</option>';
                                                } ?>
                                            </select>
                                        </td>
                                        <td style="width: 100%" class="restrictRoleBlockAllowed">
                                            <input type="text" style="width: 100%" name="restrictRoleBlock[<?php esc_attr_e($index); ?>][message]" data-name="restrictRoleBlock[index][message]" value="<?php esc_attr_e(wp_unslash($value['message'])); ?>">
                                        </td>
                                        <td><span class="delete-row"><span class="dashicons dashicons-trash"></span></span></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                            <div style="margin: 10px 0 0;">
                                <button id="addRoleBlockRow" class="button"><?php _ex( 'Add new rule', 'Settings page', 'bp-better-messages' ); ?></button>

                                <table style="display: none">
                                    <tbody>
                                    <tr id="dummyRoleBlockRow">
                                        <td>
                                            <select name="restrictRoleBlock[index][from]">
                                                <?php foreach( $roles as $slug => $role ){
                                                    echo '<option value="' . esc_attr( $slug ) . '" disabled>' . esc_attr( $role['name'] ) . '</option>';
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="restrictRoleBlock[index][to]">
                                                <?php foreach( $all_roles as $slug => $role ){
                                                    echo '<option value="' . esc_attr( $slug ) . '" disabled>' . esc_attr( $role['name'] ) . '</option>';
                                                } ?>
                                            </select>
                                        </td>
                                        <td style="width: 100%" class="restrictRoleBlockAllowed">
                                            <input type="text" style="width: 100%" name="restrictRoleBlock[index][message]" disabled value="<?php echo esc_attr_x('You cannot send messages to this user', 'Settings page', 'bp-better-messages'); ?>">
                                        </td>
                                        <td><span class="delete-row"><span class="dashicons dashicons-trash"></span></span></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <script type="text/javascript">
                                    jQuery(document).ready(function( $ ){
                                        $('#addRoleBlockRow').click(function( event ){
                                            event.preventDefault();

                                            var rows      = $('.role-block-rows');
                                            var rowsCount = rows.find('> tr').length;
                                            var dummyRow  = '<tr>' + $('#dummyRoleBlockRow').html().replaceAll('[index]', '[' + rowsCount + ']').replaceAll('disabled', '') + '</tr>';

                                            rows.append(dummyRow);
                                            $('.role-block-empty').remove();

                                            changeRestrictMode();
                                        });


                                        $('.role-block-rows').on('click', '.delete-row', function( event ){
                                            event.preventDefault();

                                            var button = $(this);
                                            var tr = button.closest('tr');
                                            tr.remove();

                                            changeRestrictMode();

                                            $('.role-block-rows tr').each(function(){
                                                var tr = $(this);
                                                var index = tr.index();

                                                tr.find('[data-name]').each(function(){
                                                    var el   = $(this);
                                                    var name = el.attr('data-name').replaceAll('[index]', '[' + index + ']');

                                                    el.attr( 'name', name );
                                                });

                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </td>
                </tr>


                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Rate limiting for new replies', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Limit max amount of replies within timeframe', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <div class="bp-better-messages-roles-list">
                            <table style="width: 100%">
                                <thead>
                                <tr>
                                    <th><?php _ex('Role', 'Settings page', 'bp-better-messages'); ?></th>
                                    <th><?php _ex('Limitation (0 to disable)', 'Settings page', 'bp-better-messages'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach( $roles as $slug => $role ){
                                    $value = 0;
                                    $type  = 'hour';

                                    if( isset($this->settings['rateLimitReply'][$slug])){
                                        $value = $this->settings['rateLimitReply'][$slug]['value'];
                                        $type  = $this->settings['rateLimitReply'][$slug]['type'];
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $role['name']; ?></td>
                                        <td>
                                            <input name="rateLimitReply[<?php echo $slug; ?>][value]" type="number" min="0" value="<?php esc_attr_e($value); ?>">
                                            <span><?php _ex('messages per', 'Settings page', 'bp-better-messages'); ?></span>
                                            <select name="rateLimitReply[<?php echo $slug; ?>][type]">
                                                <option value="hour" <?php selected( $type, 'hour' ); ?>><?php _ex('Hour', 'Settings page', 'bp-better-messages'); ?></option>
                                                <option value="day" <?php selected( $type, 'day' ); ?>><?php _ex('Day', 'Settings page', 'bp-better-messages'); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Message when rate limit for new replies reached', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input type="text" style="width: 100%" name="rateLimitReplyMessage" value="<?php esc_attr_e(wp_unslash($this->settings['rateLimitReplyMessage'])); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Restrict the creation of a new conversation', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Selected roles will not be allowed to start new conversations', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <ul class="bp-better-messages-roles-list">
                            <?php foreach( $roles as $slug => $role ){ ?>
                                <li><input id="<?php echo $slug; ?>_1" type="checkbox" name="restrictNewThreads[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ 'restrictNewThreads' ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_1"><?php echo $role['name']; ?></label></li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Message when the creation of a new conversation restricted', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input id="<?php echo $slug; ?>_2" type="text" style="width: 100%" name="restrictNewThreadsMessage" value="<?php esc_attr_e(wp_unslash($this->settings['restrictNewThreadsMessage'])); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Remove new conversation button for restricted users', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input name="restrictNewThreadsRemoveNewThreadButton" type="checkbox" <?php checked( $this->settings[ 'restrictNewThreadsRemoveNewThreadButton' ], '1' ); ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Restrict new replies', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Selected roles will not be allowed to reply', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <ul class="bp-better-messages-roles-list">
                            <?php foreach( $roles as $slug => $role ){ ?>
                                <li><input id="<?php echo $slug; ?>_3" type="checkbox" name="restrictNewReplies[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ 'restrictNewReplies' ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_3"><?php echo $role['name']; ?></label></li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Message when new replies are restricted', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input id="<?php echo $slug; ?>_4" type="text" style="width: 100%" name="restrictNewRepliesMessage" value="<?php esc_attr_e(wp_unslash($this->settings['restrictNewRepliesMessage'])); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Restrict from viewing message', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Selected roles will see message configured below instead of real message', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <ul class="bp-better-messages-roles-list">
                            <?php foreach( $roles as $slug => $role ){ ?>
                                <li><input id="<?php echo $slug; ?>_5" type="checkbox" name="restrictViewMessages[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ 'restrictViewMessages' ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_5"><?php echo $role['name']; ?></label></li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Content of messages when user is restricted from viewing message', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input id="<?php echo $slug; ?>_6" type="text" style="width: 100%" name="restrictViewMessagesMessage" value="<?php esc_attr_e(wp_unslash($this->settings['restrictViewMessagesMessage'])); ?>">
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Restrict access to mini widgets', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Selected roles will not see selected widgets', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <div style="
                            display: flex;
                            flex-wrap: nowrap;
                            justify-content: center;
                            align-items: center;
                            flex-direction: row;
                            width: 100%;
                        ">
                            <div style="width: 100%;">
                                <h4><?php _ex( 'Mini Conversations', 'Settings page', 'bp-better-messages' ); ?></h4>
                                <div style="position: relative">
                                    <?php $license_message = Better_Messages()->functions->license_proposal( true );
                                    if( ! empty( $license_message ) ) { ?>
                                        <div style="box-sizing: border-box;position:absolute;background: #ffffffb8;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                            <?php echo $license_message; ?>
                                        </div>
                                    <?php } ?>
                                    <ul class="bp-better-messages-roles-list">
                                        <?php foreach( $roles as $slug => $role ){ ?>
                                            <li><input id="<?php echo $slug; ?>_7" type="checkbox" name="restrictViewMiniThreads[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ 'restrictViewMiniThreads' ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_7"><?php echo $role['name']; ?></label></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>

                            <div style="width: 100%;margin: 5px;">
                                <h4><?php _ex( 'Mini Friends', 'Settings page', 'bp-better-messages' ); ?></h4>
                                <ul class="bp-better-messages-roles-list">
                                    <?php foreach( $wp_roles as $slug => $role ){ ?>
                                        <li><input id="<?php echo $slug; ?>_8" type="checkbox" name="restrictViewMiniFriends[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ 'restrictViewMiniFriends' ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_8"><?php echo $role['name']; ?></label></li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <div style="width: 100%">
                                <h4><?php _ex( 'Mini Groups', 'Settings page', 'bp-better-messages' ); ?></h4>
                                <ul class="bp-better-messages-roles-list">
                                    <?php foreach( $wp_roles as $slug => $role ){ ?>
                                        <li><input id="<?php echo $slug; ?>_9" type="checkbox" name="restrictViewMiniGroups[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ 'restrictViewMiniGroups' ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_9"><?php echo $role['name']; ?></label></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>


                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Bad Words List', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'One word per line', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <textarea name="badWordsList" style="width: 100%;height: 200px;" placeholder="word 1&#10;word 2"><?php esc_attr_e(wp_unslash($this->settings[ 'badWordsList' ])); ?></textarea>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Message when word from Bad Words List is found in message', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input type="text" style="width: 100%" name="restrictBadWordsList" value="<?php esc_attr_e(wp_unslash($this->settings['restrictBadWordsList'])); ?>">
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        <div id="calls" class="bpbm-tab">
            <?php if(Better_Messages()->functions->can_use_premium_code() && ! is_ssl() ){ ?>
                <div class="bp-better-messages-connection-check bpbm-error" style="margin: 20px 0;">
                    <p><?php echo esc_attr_x('Website must to have SSL certificate in order to audio and video calls work.', 'Settings page', 'bp-better-messages'); ?></p>
                    <p><?php echo esc_attr_x('This is security requirements by browsers. Contact your hosting company to enable SSL certificate at your website.', 'Settings page', 'bp-better-messages'); ?></p>
                    <p><small><?php echo esc_attr_x('This notice will be hidden when website will work via HTTPS', 'Settings page', 'bp-better-messages'); ?></small></p>
                </div>
            <?php } ?>

            <table class="form-table">
                <tbody>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable Video Calls', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to make video calls between each other', 'Settings page', 'bp-better-messages' ); ?></p>
                        <p style="font-size: 10px;"><?php _ex( 'Video calls are possible only with websocket version, its using most secure and modern WebRTC technology to empower video chats.', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="videoCalls" type="checkbox" <?php checked( $this->settings[ 'videoCalls' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row" valign="top">
                        <?php _ex( 'Video Quality', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <select name="callQuality" <?php if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?>>
                            <option <?php selected( $this->settings[ 'callQuality' ], '360' ); ?> value="360">360p</option>
                            <option <?php selected( $this->settings[ 'callQuality' ], '540' ); ?> value="540">540p</option>
                            <option <?php selected( $this->settings[ 'callQuality' ], '720' ); ?> value="720">720p</option>
                            <option <?php selected( $this->settings[ 'callQuality' ], '1080' ); ?> value="1080">1080p</option>
                        </select>
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable Audio Calls', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to make audio calls between each other', 'Settings page', 'bp-better-messages' ); ?></p>
                        <p style="font-size: 10px;"><?php _ex( 'Audio calls are possible only with websocket version, its using most secure and modern WebRTC technology to empower audio calls.', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="audioCalls" type="checkbox" <?php checked( $this->settings[ 'audioCalls' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>

                <tr>
                    <th>
                        <?php _ex( 'Limit calls only to the friends', 'Settings page','bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow only friends to make calls between each other (admins always can call)', 'Settings page','bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="callsLimitFriends" type="checkbox" <?php disabled( ! Better_Messages()->functions->is_friends_active() || ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium()  ); ?> <?php checked( $this->settings[ 'callsLimitFriends' ], '1' ); ?> value="1" />
                        <?php BP_Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Revert Mute Voice & Hide Video icons', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Makes mute and hide video icons to appear in reverse way', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="callsRevertIcons" type="checkbox" <?php checked( $this->settings[ 'callsRevertIcons' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Call time limit before call marked as missed (seconds)', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _ex( 'Call Request Time Limit', 'Settings page', 'bp-better-messages' ); ?></span></legend>
                            <label>
                                <input type="number" name="callRequestTimeLimit" value="<?php echo esc_attr( $this->settings[ 'callRequestTimeLimit' ] ); ?>" <?php if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?>>
                            </label>
                            <?php Better_Messages()->functions->license_proposal(); ?>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Missed call message when user was offline', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Leaving message about missed call for user if user was offline at that moment.', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="offlineCallsNotifications" type="checkbox" <?php checked( $this->settings[ 'offlineCallsNotifications' ], '1' ); ?> value="1" <?php if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Allow call even when user is offline', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <input name="offlineCallsAllowed" type="checkbox" <?php checked( $this->settings[ 'offlineCallsAllowed' ], '1' ); ?> value="1" <?php if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Restrict calls', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Selected roles will not be allowed to call, but they will be able to receive calls still', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <div style="position: relative">
                            <?php $license_message = Better_Messages()->functions->license_proposal( true );
                            if( ! empty( $license_message ) ) { ?>
                                <div style="box-sizing: border-box;position:absolute;background: #ffffffb8;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                    <?php echo $license_message; ?>
                                </div>
                            <?php } ?>
                            <ul class="bp-better-messages-roles-list">
                                <?php foreach( $roles as $slug => $role ){ ?>
                                    <li><input id="<?php echo $slug; ?>_calls_1" type="checkbox" name="restrictCalls[]" value="<?php echo $slug; ?>" <?php if(in_array($slug, $this->settings[ 'restrictCalls' ])) echo 'checked="checked"'; ?>><label for="<?php echo $slug; ?>_calls_1"><?php echo $role['name']; ?></label></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 320px;">
                        <?php _ex( 'Message when call is restricted', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <div style="position: relative">
                            <?php $license_message = Better_Messages()->functions->license_proposal( true );
                            if( ! empty( $license_message ) ) { ?>
                                <div style="box-sizing: border-box;position:absolute;background: #ffffffb8;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                    <?php echo $license_message; ?>
                                </div>
                            <?php } ?>
                            <input type="text" style="width: 100%" name="restrictCallsMessage" value="<?php esc_attr_e(wp_unslash($this->settings['restrictCallsMessage'])); ?>">
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>


            <p style="color: #0c5460;background-color: #d1ecf1;border: 1px solid #d1ecf1;padding: 15px;line-height: 24px;max-width: 550px;">
                <a href="https://www.wordplus.org/knowledge-base/how-video-calls-works/" target="_blank">How video/audio calls works?</a><br>
            </p>
        </div>

        <div id="group-calls" class="bpbm-tab">
            <h3><?php _ex( 'Group Video Chat', 'Settings page', 'bp-better-messages' ); ?></h3>
            <p><?php _ex( 'Group audio chat allows to start high definition video & voice group chat up to 16 people per 1 conversation.', 'Settings page', 'bp-better-messages' ); ?></p>

            <table class="form-table">
                <tbody>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable Video Chat for Groups', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to start group video chats in Group Chats', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="groupCallsGroups" type="checkbox" <?php checked( $this->settings[ 'groupCallsGroups' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable Video Chat for Conversations', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to start group video chats in conversations with many participants', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="groupCallsThreads" type="checkbox" <?php checked( $this->settings[ 'groupCallsThreads' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                </tbody>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable Video Chat for Chat Rooms', 'Settings page','bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to start group video chats in chat rooms', 'Settings page','bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="groupCallsChats" type="checkbox" <?php checked( $this->settings[ 'groupCallsChats' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                </tbody>
            </table>

            <h3><?php _ex( 'Group Audio Chat', 'Settings page', 'bp-better-messages' ); ?></h3>
            <p><?php _ex( 'Group audio chat allowing your user to start high definition voice only group chat up to 50 people per 1 conversation.', 'Settings page', 'bp-better-messages' ); ?></p>


            <table class="form-table">
                <tbody>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable Audio Chat for Groups', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to start group audio chats in Group Chats', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="groupAudioCallsGroups" type="checkbox" <?php checked( $this->settings[ 'groupAudioCallsGroups' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable Audio Chat for Conversations', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to start group audio chats in conversations with many participants', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="groupAudioCallsThreads" type="checkbox" <?php checked( $this->settings[ 'groupAudioCallsThreads' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                </tbody>
                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Enable Audio Chat for Chat Rooms', 'Settings page','bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow users to start group audio chats in chat rooms', 'Settings page','bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="groupAudioCallsChats" type="checkbox" <?php checked( $this->settings[ 'groupAudioCallsChats' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code()  || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                        <?php Better_Messages()->functions->license_proposal(); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div id="customization" class="bpbm-tab">
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row" valign="top">
                        <?php _ex( 'Colors', 'Settings page', 'bp-better-messages' ); ?>
                    </th>
                    <td>
                        <?php $url = Better_Messages()->customize->customization_link([
                            'panel' => 'better_messages'
                        ]); ?>
                        <a href="<?php echo $url; ?>"  class="button bm-customize-btn" target="_blank"><?php _ex( 'Customization', 'Settings page','bp-better-messages' ); ?> <span class="dashicons dashicons-external"></span></a>
                    </td>
                </tr>
                <tr>
                    <th scope="row" valign="top">
                        <?php _ex( 'Messenger Height', 'Settings page', 'bp-better-messages' ); ?>
                    </th>

                    <td>
                        <fieldset>
                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <tr valign="top" class="">
                                    <th scope="row" valign="top" class="th-left-pd">
                                        <?php _ex( 'Fixed Header Height', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'If your website has fixed header specify its height in pixels.', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( 'This needed for correct scrolling in some cases', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                    <td>
                                        <input type="number" name="fixedHeaderHeight" value="<?php echo esc_attr( $this->settings[ 'fixedHeaderHeight' ] ); ?>">
                                    </td>
                                </tr>

                                <tr valign="top" class="">
                                    <th scope="row" valign="top" class="th-left-pd">
                                        <?php _ex( 'Min Height of Messages Container', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Min Height of Messages Container in pixels', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                    <td>
                                        <input type="number" name="messagesMinHeight" value="<?php echo esc_attr( $this->settings[ 'messagesMinHeight' ] ); ?>">
                                    </td>
                                </tr>

                                <tr valign="top" class="">
                                    <th scope="row" valign="top" class="th-left-pd">
                                        <?php _ex( 'Max Height of Messages Container', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Max Height of Messages Container in pixels', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( 'Set 9999 to use all available window height', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                    <td>
                                        <input type="number" name="messagesHeight" value="<?php echo esc_attr( $this->settings[ 'messagesHeight' ] ); ?>">
                                    </td>
                                </tr>

                                <tr valign="top" class="">
                                    <th scope="row" valign="top" class="th-left-pd">
                                        <?php _ex( 'Side conversation list width', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Side conversation list width when Combined View is enabled in pixels', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                    <td>
                                        <input type="number" name="sideThreadsWidth" value="<?php echo esc_attr( $this->settings[ 'sideThreadsWidth' ] ); ?>">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </td>
                </tr>


                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Allow to disable sound notification', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Allow user disable sound notifications in their user settings', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="allowSoundDisable" type="checkbox" <?php checked( $this->settings[ 'allowSoundDisable' ], '1' ); ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Disable Search', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Disables search functionality', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="disableSearch" type="checkbox" <?php checked( $this->settings[ 'disableSearch' ], '1' ); ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Disable Favorite Messages', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Disables favorite messages functionality', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="disableFavoriteMessages" type="checkbox" <?php checked( $this->settings[ 'disableFavoriteMessages' ], '1' ); ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Disable User Settings', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Disables user settings button', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="disableUserSettings" type="checkbox" <?php checked( $this->settings[ 'disableUserSettings' ], '1' ); ?> value="1" />
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top">
                        <?php _ex( 'Disable New Conversation Screen', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-size: 10px;"><?php _ex( 'Disables new conversation button and screen (admin will always see it)', 'Settings page', 'bp-better-messages' ); ?></p>
                    </th>
                    <td>
                        <input name="disableNewThread" type="checkbox" <?php checked( $this->settings[ 'disableNewThread' ], '1' ); ?> value="1" />
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <?php
        $active_integration = 'integrations_bm-buddypress';
        if( defined('ultimatemember_version') ){
            $active_integration = 'integrations_bm-ultimate-member';
        }
        if( class_exists('PeepSo') ){
            $active_integration = 'integrations_bm-peepso';
        }
        ?>
        <div id="integrations" class="bpbm-tab">
            <div class="nav-tab-wrapper bpbm-sub-tabs">
                <a class="nav-tab <?php if($active_integration === 'integrations_bm-buddypress') echo 'nav-tab-active'; ?>" id="integrations_bm-buddypress-tab" href="#integrations_bm-buddypress">BuddyPress & BuddyBoss</a>
                <a class="nav-tab <?php if($active_integration === 'integrations_bm-ultimate-member') echo 'nav-tab-active'; ?>" id="integrations_bm-ultimate-member-tab" href="#integrations_bm-ultimate-member">Ultimate Member</a>
                <a class="nav-tab <?php if($active_integration === 'integrations_bm-peepso') echo 'nav-tab-active'; ?>" id="integrations_bm-peepso-tab" href="#integrations_bm-peepso">PeepSo</a>
                <a class="nav-tab" id="integrations_other-plugins-tab" href="#integrations_bm-other-plugins"><?php _ex( 'Other Plugins', 'Settings page', 'bp-better-messages' ); ?></a>
                <a class="nav-tab" id="integrations_mycred-tab" href="#integrations_mycred">MyCRED</a>
                <a class="nav-tab" id="integrations_mycred-tab" href="#integrations_gamipress">GamiPress</a>
                <a class="nav-tab" id="integrations_stickers-tab" href="#integrations_stickers"><?php _ex( 'GIFs & Stickers', 'Settings page', 'bp-better-messages' ); ?></a>
                <a class="nav-tab" id="integrations_emojies-tab" href="#integrations_bm-emojies"><?php _ex( 'Emojis', 'Settings page','bp-better-messages' ); ?></a>
            </div>

            <div id="integrations_bm-peepso" class="bpbm-subtab <?php if($active_integration === 'integrations_bm-peepso') echo 'active'; ?>">
                <?php if( ! class_exists('PeepSo') ){ ?>
                    <div class="bp-better-messages-connection-check bpbm-error" style="margin: 20px 0;">
                        <p><?php echo sprintf(esc_html_x('Website must to have %s plugin to be installed.', 'Settings page', 'bp-better-messages'), '<a href="https://www.wordplus.org/peepso" target="_blank">PeepSo</a>'); ?></p>
                        <p><small><?php echo esc_attr_x('This notice will be hidden when PeepSo plugin is installed', 'Settings page', 'bp-better-messages'); ?></small></p>
                    </div>
                <?php } ?>
                <table class="form-table">
                    <tbody>

                    <tr>
                        <th scope="row">
                            <?php _ex( 'PeepSo Integration', 'Settings page','bp-better-messages' ); ?>
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>

                                    <tr>
                                        <td>
                                            <input name="peepsoHeader" type="checkbox" <?php checked( $this->settings[ 'peepsoHeader' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Enable PeepSo Header at Messages Page', 'Settings page', 'bp-better-messages' ); ?>
                                        </th>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input name="psForceMiniChat" type="checkbox" <?php disabled( ! class_exists('PeepSo') ); ?>  <?php checked( $this->settings[ 'psForceMiniChat' ] && class_exists('PeepSo'), '1' ); ?> value="1" <?php  if( ! BP_Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() || ! class_exists('PeepSo') ) echo 'disabled'; ?> />
                                        </td>
                                        <th>
                                            <?php _ex( 'Advanced Mini Chats', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Open mini chat instead of redirecting to standalone messages page, when clicking private messages button in members directory or member profile', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php printf(_x( 'Mini Chats must be enabled. Ensure they are enabled <a href="%s">here</a>', 'Settings page', 'bp-better-messages' ), '#mini-widgets'); ?></p>
                                            <?php BP_Better_Messages()->functions->license_proposal(); ?>
                                        </th>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input name="peepsoProfileVideoCall" type="checkbox" <?php checked( $this->settings[ 'peepsoProfileVideoCall' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                                        </td>
                                        <th>
                                            <?php _ex( 'Video Call button in user profile', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Add video call button to user profile', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <?php Better_Messages()->functions->license_proposal(); ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="peepsoProfileAudioCall" type="checkbox" <?php checked( $this->settings[ 'peepsoProfileAudioCall' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                                        </td>
                                        <th>
                                            <?php _ex( 'Audio Call button in user profile', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Add audio call button to user profile', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <?php Better_Messages()->functions->license_proposal(); ?>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            PeepSo Friends
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input name="PSonlyFriendsMode" type="checkbox" <?php disabled( ! class_exists('PeepSoFriendsPlugin') ); ?>  <?php checked( $this->settings[ 'PSonlyFriendsMode' ] && class_exists('PeepSoFriendsPlugin'), '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Only Friends Mode', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Allow only friends to send messages each other', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php _ex( 'This will also remove not friends users from search results.', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://www.peepso.com/features/#friends" target="_blank">PeepSo - Friends addon</a>'); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="PSminiFriendsEnable" <?php disabled( ! class_exists('PeepSoFriendsPlugin') ); ?> <?php checked( $this->settings[ 'PSminiFriendsEnable' ] && class_exists('PeepSoFriendsPlugin'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mini Widget', 'Settings page', 'bp-better-messages' ); ?>

                                            <p style="font-size: 10px;"><?php _ex( 'Enables mini friends list widget fixed to the bottom of browser window', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="PScombinedFriendsEnable" <?php disabled( ! class_exists('PeepSoFriendsPlugin') ); ?> <?php checked( $this->settings[ 'PScombinedFriendsEnable' ] && class_exists('PeepSoFriendsPlugin'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Combined View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Friends in left column of Combined view', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="PSmobileFriendsEnable" <?php disabled( ! class_exists('PeepSoFriendsPlugin') ); ?> <?php checked( $this->settings[ 'PSmobileFriendsEnable' ] && class_exists('PeepSoFriendsPlugin'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mobile View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Friends as tab at bottom of Mobile View', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            PeepSo Groups
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input name="PSenableGroups" type="checkbox" <?php if ( ! class_exists( 'PeepSoGroupsPlugin' ) ) echo 'disabled'; ?> <?php checked( $this->settings[ 'PSenableGroups' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Enable Messages', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Enable messages for PeepSo groups', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://www.peepso.com/features/#groups" target="_blank">PeepSo - Groups addon</a>'); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="PSenableGroupsFiles" type="checkbox" <?php if ( ! class_exists( 'PeepSoGroupsPlugin' ) ) echo 'disabled'; ?> <?php checked( $this->settings[ 'PSenableGroupsFiles' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Enable file uploading', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Enable file uploading PeepSo group messages', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="PSenableGroupsEmails" type="checkbox" <?php if ( ! class_exists( 'PeepSoGroupsPlugin' ) ) echo 'disabled'; ?> <?php checked( $this->settings[ 'PSenableGroupsEmails' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Enable Email Notifications', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'When enabled users will receive email notifications for Group Chats', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input name="PSenableGroupsPushs" type="checkbox" <?php if ( ! class_exists('PeepSoGroup') ) echo 'disabled'; ?> <?php checked( $this->settings[ 'PSenableGroupsPushs' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Push Notifications', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'When enabled users will receive push notifications for Group Chats', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <?php Better_Messages()->functions->license_proposal(); ?>
                                        </th>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" name="PSminiGroupsEnable" <?php disabled( ! class_exists('PeepSoGroup') ); ?> <?php checked( $this->settings[ 'PSminiGroupsEnable' ] && class_exists('PeepSoGroup'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mini Widget', 'Settings page', 'bp-better-messages' ); ?>

                                            <p style="font-size: 10px;"><?php _ex( 'Enables mini groups list widget fixed to the bottom of browser window', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="PScombinedGroupsEnable" <?php disabled( ! class_exists('PeepSoGroup') ); ?> <?php checked( $this->settings[ 'PScombinedGroupsEnable' ] && class_exists('PeepSoGroup'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Combined View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Groups in left column of Combined view', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="PSmobileGroupsEnable" <?php disabled( ! class_exists('PeepSoGroup') ); ?> <?php checked( $this->settings[ 'PSmobileGroupsEnable' ] && class_exists('PeepSoGroup'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mobile View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Groups as tab at bottom of Mobile View', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div id="integrations_bm-other-plugins" class="bpbm-subtab">

                <table class="form-table">
                    <tbody>

                    <tr>
                        <th scope="row">
                            <?php printf(_x( '%s Integration', 'Settings page','bp-better-messages' ), '<a href="https://www.wordplus.org/multivendorx" target="_blank">MultiVendorX</a>'); ?>

                            <?php if( ! defined('MVX_PLUGIN_VERSION') ){ ?>
                                <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://www.wordplus.org/multivendorx" target="_blank">MultiVendorX</a>'); ?></p>
                            <?php } ?>
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>

                                    <tr>
                                        <td>
                                            <input name="MultiVendorXIntegration" type="checkbox" <?php checked( $this->settings[ 'MultiVendorXIntegration' ], '1' ); ?> value="1" />
                                        </td>
                                        <th style="padding-bottom:0;">
                                            <?php _ex( 'Enable Live Chat for Vendors', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'When enabled vendors will be able to activate live chat feature in their stores, which will allow buyers easily contact vendors via live chat', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th style="padding-top: 0;">
                                            <p style="font-size: 12px;font-weight: 400;">
                                                <?php _ex( 'If you are using custom page builder or button does not show up due to different reasons, you can use this shortcode to show the button at your product page', 'Settings page', 'bp-better-messages' ); ?>
                                                <input readonly="" type="text" style="margin: 0;width: 100%;padding-left: 5px;font-size: 12px;" onclick="this.focus();this.select()" value="[better_messages_multivendorx_product_button]">
                                            </p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php printf(_x( '%s Integration', 'Settings page','bp-better-messages' ), '<a href="https://www.wordplus.org/hivepress" target="_blank">HivePress</a>'); ?>

                            <?php if( ! function_exists('hivepress') ){ ?>
                                <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://www.wordplus.org/hivepress" target="_blank">HivePress</a>'); ?></p>
                            <?php } ?>
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>

                                    <tr>
                                        <td>
                                            <input name="hivepressIntegration" type="checkbox" <?php checked( $this->settings[ 'hivepressIntegration' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Enable Live Chat for Vendors', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'When enabled visitors will be able to contact vendors easily using Send Message button at the listing page and Vendors profile', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php printf(_x( '%s Integration', 'Settings page','bp-better-messages' ), '<a href="https://www.wordplus.org/wpjobmanager" target="_blank">WP Job Manager</a>'); ?>

                            <?php if( ! class_exists('WP_Job_Manager') ){ ?>
                                <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://www.wordplus.org/wpjobmanager" target="_blank">WP Job Manager</a>'); ?></p>
                            <?php } ?>
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>

                                    <tr>
                                        <td>
                                            <input name="wpJobManagerIntegration" type="checkbox" <?php checked( $this->settings[ 'wpJobManagerIntegration' ], '1' ); ?> value="1" />
                                        </td>
                                        <th style="padding-bottom:0;">
                                            <?php _ex( 'Enable Live Chat for Job Listings', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'When enabled visitors will be able to contact job listing authors easily using Send Message button at the listing page', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th style="padding-top: 0;">
                                            <p style="font-size: 12px;font-weight: 400;">
                                                <?php _ex( 'If you are using custom page builder or button does not show up due to different reasons, you can use this shortcode to show the button at your job listing page', 'Settings page', 'bp-better-messages' ); ?>
                                                <input readonly="" type="text" style="margin: 0;width: 100%;padding-left: 5px;font-size: 12px;" onclick="this.focus();this.select()" value="[better_messages_wp_job_manager_listing_button]">
                                            </p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php printf(_x( '%s Integration', 'Settings page','bp-better-messages' ), '<a href="https://www.wordplus.org/dokan" target="_blank">Dokan Marketplace</a>'); ?>

                            <?php if( ! class_exists('WeDevs_Dokan') ){ ?>
                                <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://www.wordplus.org/dokan" target="_blank">Dokan Marketplace</a>'); ?></p>
                            <?php } ?>
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>

                                    <tr>
                                        <td>
                                            <input name="dokanIntegration" type="checkbox" <?php checked( $this->settings[ 'dokanIntegration' ], '1' ); ?> value="1" />
                                        </td>
                                        <th style="padding-bottom:0;">
                                            <?php _ex( 'Enable Live Chat for Vendors', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'When enabled vendors will be able to activate live chat feature in their stores, which will allow buyers easily contact vendors via live chat', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th style="padding-top: 0;">
                                            <p style="font-size: 12px;font-weight: 400;">
                                                <?php _ex( 'If you are using custom page builder or button does not show up due to different reasons, you can use this shortcode to show the button at your product page', 'Settings page', 'bp-better-messages' ); ?>
                                                <input readonly="" type="text" style="margin: 0;width: 100%;padding-left: 5px;font-size: 12px;" onclick="this.focus();this.select()" value="[better_messages_dokan_product_button]">
                                            </p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php printf(_x( '%s Integration', 'Settings page','bp-better-messages' ), '<a href="https://wordpress.org/plugins/bbpress/" target="_blank">bbPress</a>'); ?>

                            <?php if(  ! class_exists( 'bbPress' )  ){ ?>
                                <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://wordpress.org/plugins/bbpress/" target="_blank">bbPress</a>'); ?></p>
                            <?php } ?>
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input name="bbPressAuthorDetailsLink" type="checkbox" <?php checked( $this->settings[ 'bbPressAuthorDetailsLink' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Show link in bbPress author details', 'Settings page', 'bp-better-messages' ); ?></th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <?php printf(_x( '%s Integration', 'Settings page','bp-better-messages' ), '<a href="https://www.wordplus.org/jetengine" target="_blank">JetEngine</a>'); ?>

                            <?php if( ! function_exists('jet_engine') ){ ?>
                                <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://www.wordplus.org/jetengine" target="_blank">JetEngine</a>'); ?></p>
                            <?php } ?>

                        </th>
                        <td>

                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>

                                    <tr>
                                        <td>
                                            <input name="jetEngineAvatars" type="checkbox" <?php checked( $this->settings[ 'jetEngineAvatars' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Use Avatars', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Avatars from Jet Engine Profile Builder will be displayed in Better Messages interface', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div id="integrations_bm-emojies" class="bpbm-subtab">
                <style type="text/css">
                    .smilesList{
                    }

                    .widefat-standard td, .widefat-standard th {
                        padding: 8px 10px;
                    }

                    .smilesList .smilesListItem{
                        display: inline-block;
                        vertical-align: top;
                        margin: 0 5px 5px;
                        cursor: pointer;
                        font-size: 22px;
                    }

                    .smilesList .smilesListItem.disabled{
                        opacity: 0.5;
                    }

                    .reactions-emojies{
                        background: white;
                        border: 1px solid #ccc;
                        padding: 5px 5px;
                    }
                    .reactions-emojies tr{}
                    .reactions-emojies tr td{
                        padding: 5px 5px;
                    }

                    .reactions-emojies tr td .dashicons-trash{
                        cursor: pointer;
                    }

                    .reactions-selector{}
                    .reactions-selector img{
                        display: inline-block;
                        vertical-align: top;
                        margin: 0 5px 5px;
                        cursor: pointer;
                    }
                </style>
                <table class="form-table">
                    <tbody>
                    <?php
                    $emoji_sets = Better_Messages_Emojis()->emoji_sets;
                    $selected_set = $this->settings['emojiSet'];
                    ?>
                    <tr>
                        <th scope="row" style="white-space: nowrap">
                            <?php _ex( 'Emojis Set', 'Settings page', 'bp-better-messages' ); ?>
                        </th>

                        <td>
                            <select name="emojiSet">
                                <?php foreach( $emoji_sets as $key => $label ){
                                    echo '<option value="' . $key . '" ' . selected( $selected_set, $key, false ). '>' . $label . '</option>';
                                } ?>
                            </select>
                        </td>
                    </tr>
                    <?php

                    $emoji_list = [];
                    $unified = [];

                    $dataset    = Better_Messages_Emojis()->getDataset();
                    $emojis     = $dataset['emojis'];
                    $spriteUrl  = Better_Messages_Emojis()->getSpriteUrl();

                    foreach ( $dataset['emojis'] as $key => $item ){
                        $_unified = $item['skins'][0]['unified'];
                        $unified[ $_unified ] = $key;
                    }

                    $backgroundSize = $dataset['sheet']['cols'] * 100 . '% ' . $dataset['sheet']['rows'] * 100 . '%';

                    foreach( $dataset['categories'] as $category ){
                        $category_name = ucfirst($category['id']);

                        if( ! isset( $emoji_list[ $category_name ] ) ){
                            $emoji_list[ $category_name ] = [];
                        }

                        foreach( $category['emojis'] as $emojiName ){
                            $emoji_list[$category_name][$emojiName] = $dataset['emojis'][$emojiName];
                        }
                    }

                    $reactions = Better_Messages_Reactions::instance()->get_reactions();
                    ?>
                    <tr>
                        <th scope="row" style="white-space: nowrap">
                            <?php _ex( 'Emojis for Reactions', 'Settings page', 'bp-better-messages' ); ?>
                        </th>
                        <td>
                            <table class="reactions-emojies">
                                <?php foreach( $reactions as $unicode => $name ){
                                    if( ! isset( $unified[$unicode] ) ) continue;

                                    $emoji = $emojis[ $unified[$unicode] ];

                                    echo '<tr>';
                                    echo '<td>';
                                    $x_pos = ($emoji['skins'][0]['x'] > 0 ) ? ( ( 100 / ( $dataset['sheet']['cols'] - 1 ) ) * $emoji['skins'][0]['x'] ) . '%' : '0%';
                                    $y_pos = ($emoji['skins'][0]['y'] > 0 ) ? ( ( 100 / ( $dataset['sheet']['rows'] - 1 ) ) * $emoji['skins'][0]['y'] ) . '%' : '0%';
                                    echo '<span style="display:block;background-position: ' . $x_pos . ' ' . $y_pos . ';background-image:url(' . $spriteUrl . ');width: 25px;height: 25px;background-size: ' . $backgroundSize . '" alt="" src="">';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<input type="text" name="reactionsEmojies[' . esc_attr( $unicode ) . ']" value="' . esc_attr(wp_unslash($name)) . '">';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<span class="dashicons dashicons-trash"></span>';
                                    echo '</td>';
                                    echo '</tr>';
                                }

                                echo '<tr class="newReactionRow">';
                                echo '<td colspan="2">';
                                echo '<button id="addNewReaction" class="button">' . _x( 'Add new reaction', 'Settings page', 'bp-better-messages' ) . '</button>';
                                echo '</td>';
                                echo '</tr>';

                                ?>
                            </table>

                            <div class="reactions-selector" style="display: none">
                                <h4><?php _ex( 'Select Emoji for new reaction', 'Settings page', 'bp-better-messages' ); ?></h4>
                                <div class="reactions-selector-emojies"></div>
                            </div>

                            <script type="text/javascript">
                                jQuery(document).ready(function( $ ) {
                                    var selectEvent;

                                    jQuery('.reactions-emojies tbody').sortable({
                                        stop: function( event, ui ) {
                                            //alert('sorting finished');
                                            //calculateNewValue();
                                        }
                                    });

                                    $('.reactions-selector-emojies').on('click', '> span', function(event){
                                        event.preventDefault();

                                        var imageHtml = this.outerHTML;
                                        var unicode   = $(this).attr('data-unicode');

                                        var newRow = '<tr><td>' + imageHtml + '</td><td><input type="text" name="reactionsEmojies[' + unicode + ']" value=""></td><td><span class="dashicons dashicons-trash"></span></td></tr>';

                                        $(newRow).insertBefore( $('.newReactionRow') );
                                        $('.reactions-selector').hide();
                                        $('.reactions-selector-emojies').html('');
                                    });

                                    $('.reactions-emojies').on('click', 'tr .dashicons-trash', function(event){
                                        event.preventDefault();

                                        var button = $(this);
                                        var table  = button.closest('table');
                                        var row    = button.closest('tr');
                                        var rows = table.find('tr').length;

                                        row.remove();
                                    });

                                    $('.reactions-emojies').on('click', '> .emojione', function(event){
                                        event.preventDefault();

                                        var imageHtml = this.outerHTML;
                                        var unicode   = $(this).attr('data-unicode');

                                        var newRow = '<tr><td>' + imageHtml + '</td><td><input type="text" name="reactionsEmojies[' + unicode + ']" value=""></td><td><span><span class="dashicons dashicons-trash"></span></span></td></tr>';

                                        $(newRow).insertBefore( $('.newReactionRow') );
                                        $('.reactions-selector').hide();
                                        $('.reactions-selector-emojies').html('');
                                    });

                                    $('#addNewReaction').on('click', function (event) {
                                        event.preventDefault();

                                        var html = '';
                                        $('.smilesListItem > span').each(function(){
                                            html += this.outerHTML;
                                        });

                                        $('.reactions-selector').show();
                                        $('.reactions-selector-emojies').html(html);
                                    });
                                });
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="white-space: nowrap">
                            <?php _ex( 'Emojis for Selector', 'Settings page', 'bp-better-messages' ); ?>
                        </th>
                        <td>
                            <p class="bp-better-messages-connection-check" style="background: white;color: black;border-color: #ccc;margin-bottom: 10px;"><?php _ex( 'Click on emoji to enable or disable it, drag it to change it position in emoji selector', 'Settings page', 'bp-better-messages' ); ?></p>
                            <table>
                                <tbody>
                                <?php
                                $sorting = get_option('bm-emoji-set-2');

                                foreach( $emoji_list as $category => $emojies ){
                                    $category = strtolower($category);
                                    echo '<tr><th style="padding: 0.2em 0 1em">' . ucfirst($category) . '</th></tr>';
                                    echo '<tr class="emoji-category" data-category="' . $category . '">';
                                    echo '<td style="padding: 0">';

                                    //$emojies = array_reverse($emojies, true);

                                    $order = [];

                                    if( isset( $sorting[$category] ) ){
                                        $order = $sorting[$category];

                                        $sorted_first = [];
                                        foreach( $emojies as $shortcode => $unicode ){
                                            if( in_array( $shortcode, $order ) ){
                                                $sorted_first[ $shortcode ] = $unicode;
                                                unset( $emojies[$shortcode] );
                                            }
                                        }

                                        uksort($sorted_first, function($key1, $key2) use ($order) {
                                            return (array_search($key1, $order) > array_search($key2, $order));
                                        });

                                        $emojies = $sorted_first + $emojies;
                                    }

                                    echo '<div class="smilesList">';
                                    foreach( $emojies as $key => $emoji ){
                                        $disabledClass = ( $order && ! in_array($key, $order) ) ? ' disabled' : '';
                                        echo '<div class="smilesListItem' . $disabledClass . '" data-shortcode="' . $emoji['id'] . '">';
                                        $x_pos = ($emoji['skins'][0]['x'] > 0 ) ? ( ( 100 / ( $dataset['sheet']['cols'] - 1 ) ) * $emoji['skins'][0]['x'] ) . '%' : '0%';
                                        $y_pos = ($emoji['skins'][0]['y'] > 0 ) ? ( ( 100 / ( $dataset['sheet']['rows'] - 1 ) ) * $emoji['skins'][0]['y'] ) . '%' : '0%';
                                        echo '<span data-unicode="' . $emoji['skins'][0]['unified'] . '" style="display:block;background-position: ' . $x_pos . ' ' . $y_pos . ';background-image:url(' . $spriteUrl . ');width: 25px;height: 25px;background-size: ' . $backgroundSize . '">';
                                        echo '</div>';
                                    }
                                    echo '</div>';

                                    echo '</td>';
                                    echo '</tr>';

                                }
                                ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <input type="hidden" name="emojiSettings" value="">
                <script type="text/javascript">
                    var input = jQuery('input[name="emojiSettings"]');

                    function calculateNewValue(){
                        var result = {};
                        var categories = jQuery('tr.emoji-category');
                        categories.each(function(){
                            var item = jQuery( this );
                            var category = item.data('category');

                            result[category] = [];
                            var smileList = item.find('.smilesList');
                            var smiles = smileList.find('> .smilesListItem:not(.disabled)');

                            smiles.each(function(){
                                var shortcode = jQuery(this).data('shortcode');

                                result[category].push(shortcode);
                            });
                        });

                        input.val( JSON.stringify( result ) );
                    }

                    jQuery('.smilesList .smilesListItem').click(function(){
                        jQuery(this).toggleClass('disabled');
                        calculateNewValue();
                    });

                    jQuery(document).ready(function(){
                        jQuery('.smilesList').sortable({
                            stop: function( event, ui ) {
                                //alert('sorting finished');
                                calculateNewValue();
                            }
                        });
                    });
                </script>
            </div>

            <div id="integrations_bm-ultimate-member" class="bpbm-subtab <?php if($active_integration === 'integrations_bm-ultimate-member') echo 'active'; ?>">
                <?php if( ! defined('ultimatemember_version') ){ ?>
                    <div class="bp-better-messages-connection-check bpbm-error" style="margin: 20px 0;">
                        <p><?php echo sprintf(esc_html_x('Website must to have %s plugin to be installed.', 'Settings page', 'bp-better-messages'), '<a href="https://wordpress.org/plugins/ultimate-member/" target="_blank">Ultimate Member</a>'); ?></p>
                        <p><small><?php echo esc_attr_x('This notice will be hidden when Ultimate Member plugin is installed', 'Settings page', 'bp-better-messages'); ?></small></p>
                    </div>
                <?php } ?>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row">
                            <?php _ex( 'Ultimate Member Integration', 'Settings page','bp-better-messages' ); ?>
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input name="umForceMiniChat" type="checkbox" <?php disabled( ! defined('ultimatemember_version') ); ?>  <?php checked( $this->settings[ 'umForceMiniChat' ], '1' ); ?> value="1" <?php  if( ! BP_Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() || ! defined('ultimatemember_version') ) echo 'disabled'; ?> />
                                        </td>
                                        <th>
                                            <?php _ex( 'Advanced Mini Chats', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Open mini chat instead of redirecting to standalone messages page, when clicking private messages button in members directory or member profile', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php printf(_x( 'Mini Chats must be enabled. Ensure they are enabled <a href="%s">here</a>', 'Settings page', 'bp-better-messages' ), '#mini-widgets'); ?></p>
                                            <?php BP_Better_Messages()->functions->license_proposal(); ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="umProfilePMButton" type="checkbox" <?php checked( $this->settings[ 'umProfilePMButton' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'User Profile - Private Message Button', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Show Private Message button in user profiles', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="UMuserListButton" type="checkbox" <?php checked( $this->settings[ 'UMuserListButton' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Show Private Message Link at Members List', 'Settings page','bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'You can enable this to show private message button at your members list', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            Ultimate Member Friends
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input name="umOnlyFriendsMode" type="checkbox" <?php disabled( ! class_exists('UM_Friends_API') ); ?>  <?php checked( $this->settings[ 'umOnlyFriendsMode' ] && class_exists('UM_Friends_API'), '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Only Friends Mode', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Allow only friends to send messages each other', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php _ex( 'This will also remove not friends users from search results.', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://ultimatemember.com/extensions/friends/" target="_blank">Ultimate Member - Friends addon</a>'); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="UMminiFriendsEnable" <?php disabled( ! class_exists('UM_Friends_API') ); ?> <?php checked( $this->settings[ 'UMminiFriendsEnable' ] && class_exists('UM_Friends_API'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mini Widget', 'Settings page', 'bp-better-messages' ); ?>

                                            <p style="font-size: 10px;"><?php _ex( 'Enables mini friends list widget fixed to the bottom of browser window', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="UMcombinedFriendsEnable" <?php disabled( ! class_exists('UM_Friends_API') ); ?> <?php checked( $this->settings[ 'UMcombinedFriendsEnable' ] && class_exists('UM_Friends_API'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Combined View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Friends in left column of Combined view', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="UMmobileFriendsEnable" <?php disabled( ! class_exists('UM_Friends_API') ); ?> <?php checked( $this->settings[ 'UMmobileFriendsEnable' ] && class_exists('UM_Friends_API'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mobile View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Friends as tab at bottom of Mobile View', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Ultimate Member Groups
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input name="UMenableGroups" type="checkbox" <?php if ( ! class_exists('UM_Groups') ) echo 'disabled'; ?> <?php checked( $this->settings[ 'UMenableGroups' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Enable Messages', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Enable messages for Ultimate Member Groups', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://ultimatemember.com/extensions/groups/" target="_blank">Ultimate Member - Groups addon</a>'); ?></p>
                                        </th>
                                    </tr>


                                    <tr>
                                        <td>
                                            <input name="UMenableGroupsFiles" type="checkbox" <?php if ( ! class_exists('UM_Groups') ) echo 'disabled'; ?> <?php checked( $this->settings[ 'UMenableGroupsFiles' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Enable file uploading', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Enable file uploading in Ultimate Member Groups messages', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input name="UMenableGroupsEmails" type="checkbox" <?php if ( ! class_exists('UM_Groups') ) echo 'disabled'; ?> <?php checked( $this->settings[ 'UMenableGroupsEmails' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Enable Email Notifications', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'When enabled users will receive email notifications for Group Messages', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>


                                    <tr>
                                        <td>
                                            <input name="UMenableGroupsPushs" type="checkbox" <?php if ( ! class_exists('UM_Groups') ) echo 'disabled'; ?> <?php checked( $this->settings[ 'UMenableGroupsPushs' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Push Notifications', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'When enabled users will receive push notifications for Group Chats', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <?php Better_Messages()->functions->license_proposal(); ?>
                                        </th>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="checkbox" name="UMminiGroupsEnable" <?php disabled( ! class_exists('UM_Groups') ); ?> <?php checked( $this->settings[ 'UMminiGroupsEnable' ] && class_exists('UM_Groups'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mini Widget', 'Settings page', 'bp-better-messages' ); ?>

                                            <p style="font-size: 10px;"><?php _ex( 'Enables mini groups list widget fixed to the bottom of browser window', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="UMcombinedGroupsEnable" <?php disabled( ! class_exists('UM_Groups') ); ?> <?php checked( $this->settings[ 'UMcombinedGroupsEnable' ] && class_exists('UM_Groups'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Combined View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Groups in left column of Combined view', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="UMmobileGroupsEnable" <?php disabled( ! class_exists('UM_Groups') ); ?> <?php checked( $this->settings[ 'UMmobileGroupsEnable' ] && class_exists('UM_Groups'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mobile View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Groups as tab at bottom of Mobile View', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Only Followers Mode', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'Allow only send message if user following the user or followed by the user', 'Settings page', 'bp-better-messages' ); ?></p>
                            <p style="font-size: 10px;"><?php printf(_x( '%s must be installed', 'Settings page', 'bp-better-messages' ), '<a href="https://ultimatemember.com/extensions/followers/" target="_blank">Ultimate Member - Followers addon</a>'); ?></p>
                        </th>
                        <td>
                            <input name="umOnlyFollowersMode" type="checkbox" <?php disabled( ! class_exists('UM_Followers_API') ); ?>  <?php checked( $this->settings[ 'umOnlyFollowersMode' ] && class_exists('UM_Followers_API'), '1' ); ?> value="1" />
                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>

            <div id="integrations_stickers" class="bpbm-subtab">
                <h1 style="padding-top: 20px">GIPHY Integration</h1>
                <?php
                $giphy_error = get_option( 'bp_better_messages_giphy_error', false );
                if( !! $giphy_error ){
                    echo '<div class="notice notice-error">';
                    echo '<p><b>GIPHY Error:</b> ' . $giphy_error . '</p>';
                    echo '</div>';
                }
                ?>

                <table class="form-table">
                    <tbody>
                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'GIPHY API Key', 'Settings page', 'bp-better-messages' ); ?>
                            <p><?php _ex('Leave this field empty to disable giphy', 'Settings page', 'bp-better-messages'); ?></p>
                            <p><a href="https://developers.giphy.com/docs/api#quick-start-guide" target="_blank"><?php _ex('How to create GIPHY API key', 'Settings page', 'bp-better-messages'); ?></a></p>
                        </th>
                        <td>
                            <input name="giphyApiKey" type="text" style="width: 100%"  value="<?php esc_attr_e(wp_unslash($this->settings['giphyApiKey'])); ?>" />
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'GIPHY Content rating', 'Settings page', 'bp-better-messages' ); ?>
                            <p><?php echo sprintf(_x('GIPHY Content Rating <a href="%s" target="_blank">Learn more</a>',  'Settings page','bp-better-messages'), 'https://developers.giphy.com/docs/optional-settings#rating'); ?></p>
                        </th>
                        <td>
                            <input name="giphyContentRating" type="text" style="width: 100%"  value="<?php esc_attr_e(wp_unslash($this->settings['giphyContentRating'])); ?>" />
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'GIPHY Language', 'Settings page', 'bp-better-messages' ); ?>
                            <p><?php echo sprintf(_x('GIPHY Language <a href="%s" target="_blank">Learn more</a>',  'Settings page','bp-better-messages'), 'https://developers.giphy.com/docs/optional-settings#language-support'); ?></p>
                        </th>
                        <td>
                            <input name="giphyLanguage" type="text" style="width: 100%"  value="<?php esc_attr_e(wp_unslash($this->settings['giphyLanguage'])); ?>" />
                        </td>
                    </tr>
                    </tbody>
                </table>

                <h1>Stipop.io Stickers Integration</h1>
                <p style="font-size: 1rem;background: white;border: 1px solid #ccc;padding: 15px;">
                    <strong>Stipop.io changed their plans and allows only 20 monthly active users instead of 10000 for free.</strong>
                    <br><br>
                    If you have more than 20 monthly users active using stickers, consider disabling stickers or subscribe to Stipop.io paid options.
                    <br><br>
                    To activate stickers you need to register <a href="https://www.wordplus.org/stipopregister" target="_blank">here</a> and insert API Key which you will get after registration in the settings below.
                    <br><br>
                    Because of this limitations and very expensive plans of Stipop.io, it's planned to find and integrate other sticker provider.
                </p>

                <?php
                $stipop_error = get_option( 'bp_better_messages_stipop_error', false );
                if( !! $stipop_error ){
                    echo '<div class="notice notice-error">';
                    echo '<p><b>Stipop Error:</b> ' . $stipop_error . '</p>';
                    echo '</div>';
                }
                ?>
                <table class="form-table">
                    <tbody>
                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Stipop.io API Key', 'Settings page', 'bp-better-messages' ); ?>
                            <p><?php _ex('Leave this field empty to disable stickers', 'Settings page', 'bp-better-messages'); ?></p>
                        </th>
                        <td>
                            <input name="stipopApiKey" type="text" style="width: 100%"  value="<?php esc_attr_e(wp_unslash($this->settings['stipopApiKey'])); ?>" />
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Language', 'Settings page', 'bp-better-messages' ); ?>
                            <p><?php _ex('Two letter language code for showing stickers which best fits this language', 'Settings page', 'bp-better-messages'); ?></p>
                            <p><?php _ex('For example (en, ko, es)', 'Settings page', 'bp-better-messages'); ?></p>
                        </th>
                        <td>
                            <input name="stipopLanguage" type="text" style="width: 100%"  value="<?php esc_attr_e(wp_unslash($this->settings['stipopLanguage'])); ?>" />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div id="integrations_bm-buddypress" class="bpbm-subtab <?php if($active_integration === 'integrations_bm-buddypress') echo 'active'; ?>">
                <?php if ( ! class_exists( 'BuddyPress' ) ) { ?>
                    <div class="bp-better-messages-connection-check bpbm-error" style="margin: 20px 0;">
                        <p><?php echo sprintf(esc_html_x('Website must to have %s plugin to be installed.', 'Settings page', 'bp-better-messages'), '<a href="https://wordpress.org/plugins/buddypress/" target="_blank">BuddyPress</a>'); ?></p>
                        <p><small><?php echo esc_attr_x('This notice will be hidden when BuddyPress plugin is installed', 'Settings page', 'bp-better-messages'); ?></small></p>
                    </div>
                <?php } ?>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row">
                            <?php _ex( 'BuddyPress Integration', 'Settings page','bp-better-messages' ); ?>
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input name="bpForceMiniChat" type="checkbox" <?php disabled( ! class_exists( 'BuddyPress' ) ); ?>  <?php checked( $this->settings[ 'bpForceMiniChat' ] && class_exists( 'BuddyPress' ), '1' ); ?> value="1" <?php  if( ! BP_Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() || ! class_exists( 'BuddyPress' ) ) echo 'disabled'; ?> />
                                        </td>
                                        <th>
                                            <?php _ex( 'Advanced Mini Chats', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Open mini chat instead of redirecting to standalone messages page, when clicking private messages button in members directory or member profile', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php printf(_x( 'Mini Chats must be enabled. Ensure they are enabled <a href="%s">here</a>', 'Settings page', 'bp-better-messages' ), '#mini-widgets'); ?></p>
                                            <?php BP_Better_Messages()->functions->license_proposal(); ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="profileAudioCall" type="checkbox" <?php checked( $this->settings[ 'profileAudioCall' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                                        </td>
                                        <th>
                                            <?php _ex( 'Audio Call button in user profile', 'Settings page','bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Add audio call button to user profile', 'Settings page','bp-better-messages' ); ?></p>
                                            <?php Better_Messages()->functions->license_proposal(); ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="profileVideoCall" type="checkbox" <?php checked( $this->settings[ 'profileVideoCall' ], '1' ); ?> value="1" <?php  if( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) echo 'disabled'; ?> />
                                        </td>
                                        <th>
                                            <?php _ex( 'Video Call button in user profile', 'Settings page','bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Add video call button to user profile', 'Settings page','bp-better-messages' ); ?></p>
                                            <?php Better_Messages()->functions->license_proposal(); ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="userListButton" type="checkbox" <?php checked( $this->settings[ 'userListButton' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Show Private Message Link at Members List', 'Settings page','bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Some themes does not shows private message button automatically.', 'Settings page','bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php _ex( 'You can enable this to show private message button at your members list if its missing', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>

                                    <tr>
                                        <th colspan="2" style="padding-left: 20px;">
                                            BuddyPress Profile Slug
                                            <p style="font-size: 10px;"><?php _ex( 'Change messages tab URL slug in BuddyPress profile ("messages" slug is not allowed)', 'Settings page','bp-better-messages' ); ?></p>

                                            <input style="margin: 10px 0 0;padding: 0 8px;" type="text" name="bpProfileSlug" value="<?php echo esc_attr( wp_unslash($this->settings[ 'bpProfileSlug' ]) ); ?>">
                                        </th>
                                    </tr>

                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _ex( 'BuddyPress Friends', 'Settings page','bp-better-messages' ); ?>
                        </th>
                        <td>
                            <fieldset>
                                <table class="widefat bm-switcher-table">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input name="friendsMode" type="checkbox" <?php disabled( ! function_exists('friends_check_friendship') ); ?>  <?php checked( $this->settings[ 'friendsMode' ] && function_exists('friends_check_friendship'), '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Only Friends Mode', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Allow only friends to send messages each other', 'Settings page', 'bp-better-messages' ); ?></p>
                                            <p style="font-size: 10px;"><?php _ex( 'This will also remove not friends users from search results.', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="miniFriendsEnable" <?php disabled( ! function_exists('friends_get_friend_user_ids') ); ?> <?php checked( $this->settings[ 'miniFriendsEnable' ] && function_exists('friends_get_friend_user_ids'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mini Widget', 'Settings page', 'bp-better-messages' ); ?>

                                            <p style="font-size: 10px;"><?php _ex( 'Enables mini friends list widget fixed to the bottom of browser window', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="combinedFriendsEnable" <?php disabled( ! function_exists('friends_get_friend_user_ids') ); ?> <?php checked( $this->settings[ 'combinedFriendsEnable' ] && function_exists('friends_get_friend_user_ids'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Combined View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Friends in left column of Combined view', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="mobileFriendsEnable" <?php disabled( ! function_exists('friends_get_friend_user_ids') ); ?> <?php checked( $this->settings[ 'mobileFriendsEnable' ] && function_exists('friends_get_friend_user_ids'), '1' ); ?> value="1">
                                        </td>
                                        <th>
                                            <?php _ex( 'Mobile View', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Shows Friends as tab at bottom of Mobile View', 'Settings page','bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'BuddyPress Groups', 'Settings page', 'bp-better-messages' ); ?>
                        </th>
                        <td>

                            <table class="widefat bm-switcher-table">
                                <tbody>

                                <tr>
                                    <td>
                                        <input name="enableGroups" type="checkbox" <?php if ( ! bm_bp_is_active( 'groups' ) ) echo 'disabled'; ?> <?php checked( $this->settings[ 'enableGroups' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Enable Messages', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enable messages for BuddyPress groups', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>

                                <tr>
                                    <td>
                                        <input name="enableGroupsFiles" type="checkbox" <?php if ( ! bm_bp_is_active( 'groups' ) ) echo 'disabled'; ?> <?php checked( $this->settings[ 'enableGroupsFiles' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Enable File Uploading', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enable file uploading in BuddyPress Groups Messages', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="enableMiniGroups" type="checkbox" <?php if ( ! bm_bp_is_active( 'groups' ) ) echo 'disabled'; ?> <?php checked( $this->settings[ 'enableMiniGroups' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Mini Widget', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enables mini groups widget fixed to the bottom of browser window', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="combinedGroupsEnable" type="checkbox" <?php if ( ! bm_bp_is_active( 'groups' ) ) echo 'disabled'; ?> <?php checked( $this->settings[ 'combinedGroupsEnable' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Combined View', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Shows Groups in left column of Combined view', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="mobileGroupsEnable" <?php disabled( ! bm_bp_is_active( 'groups' ) ); ?> <?php checked( $this->settings[ 'mobileGroupsEnable' ], '1' ); ?> value="1">
                                    </td>
                                    <th>
                                        <?php _ex( 'Mobile View', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Shows Groups as tab at bottom of Mobile View', 'Settings page','bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="enableGroupsEmails" type="checkbox" <?php if ( ! bm_bp_is_active( 'groups' ) ) echo 'disabled'; ?> <?php checked( $this->settings[ 'enableGroupsEmails' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Email Notifications', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'When enabled users will receive email notifications for Group Chats', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <input name="enableGroupsPushs" type="checkbox" <?php if ( ! bm_bp_is_active( 'groups' ) ) echo 'disabled'; ?> <?php checked( $this->settings[ 'enableGroupsPushs' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Push Notifications', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'When enabled users will receive push notifications for Group Chats', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <?php Better_Messages()->functions->license_proposal(); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="padding-left: 20px;">
                                        <?php _ex( 'URL Slug', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Change messages tab URL slug in BuddyPress group', 'Settings page', 'bp-better-messages' ); ?></p>

                                        <input type="text" name="bpGroupSlug" style="margin: 10px 0 0;padding: 0 8px;" value="<?php echo esc_attr( wp_unslash($this->settings[ 'bpGroupSlug' ]) ); ?>">
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Other', 'Settings page', 'bp-better-messages' ); ?>
                        </th>
                        <td>

                            <table class="widefat bm-switcher-table">
                                <tbody>
                                <?php if(  function_exists( 'bbapp_send_push_notification' ) ) { ?>
                                    <tr>
                                        <td>
                                            <input name="bpAppPush" type="checkbox" <?php checked( $this->settings[ 'bpAppPush' ], '1' ); ?> value="1" />
                                        </td>
                                        <th>
                                            <?php _ex( 'Enable BuddyBoss App Push Notifications', 'Settings page', 'bp-better-messages' ); ?>
                                            <p style="font-size: 10px;"><?php _ex( 'Enable push notifications on new messages in BuddyBoss application when user is offline.', 'Settings page', 'bp-better-messages' ); ?></p>
                                        </th>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>
                                        <input name="bpFallback" type="checkbox" <?php checked( $this->settings[ 'bpFallback' ], '1' ); ?> value="1" />
                                    </td>
                                    <th>
                                        <?php _ex( 'Enable fallback when sending new message', 'Settings page', 'bp-better-messages' ); ?>
                                        <p style="font-size: 10px;"><?php _ex( 'Enable fallback when sending function with buddypress native messages_new_message function.', 'Settings page', 'bp-better-messages' ); ?></p>
                                        <p style="font-size: 10px;"><?php _ex( 'This is disabled by default as it can cause issues with some plugins.', 'Settings page', 'bp-better-messages' ); ?></p>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tbody>
                </table>
            </div>

            <div id="integrations_mycred" class="bpbm-subtab ">
                <?php if ( ! class_exists( 'myCRED_Core' ) ) { ?>
                    <div class="bp-better-messages-connection-check bpbm-error" style="margin: 20px 0;">
                        <p><?php echo sprintf(esc_html_x('Website must to have %s plugin to be installed.', 'Settings page', 'bp-better-messages'), '<a href="https://www.wordplus.org/mc" target="_blank">MyCRED</a>'); ?></p>
                        <p><small><?php echo esc_attr_x('This notice will be hidden when MyCRED plugin is installed', 'Settings page', 'bp-better-messages'); ?></small></p>
                    </div>
                <?php } ?>
                <table class="form-table">
                    <tbody>
                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Price for new message in the conversation', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'Use 0 if this is free', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div class="bp-better-messages-roles-list">
                                <table style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th><?php _ex('Role', 'Settings page', 'bp-better-messages'); ?></th>
                                        <th><?php _ex('Price', 'Settings page', 'bp-better-messages'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach( $wp_roles as $slug => $role ){
                                        $value = 0;

                                        if( isset($this->settings['myCredNewMessageCharge'][$slug])){
                                            $value = $this->settings['myCredNewMessageCharge'][$slug]['value'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $role['name']; ?></td>
                                            <td>
                                                <input name="myCredNewMessageCharge[<?php echo $slug; ?>][value]" type="number" min="0" value="<?php esc_attr_e($value); ?>">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top" style="width: 320px;">
                            <?php _ex( 'Message when user can`t send new reply', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'HTML Allowed', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <input type="text" style="width: 100%" name="myCredNewMessageChargeMessage" value="<?php esc_attr_e(wp_unslash($this->settings['myCredNewMessageChargeMessage'])); ?>">
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Price for new starting new conversation', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'The charge will be applied additionally to the message price', 'Settings page', 'bp-better-messages' ); ?></p>
                            <p style="font-size: 10px;"><?php _ex( 'Use 0 if this is free', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div class="bp-better-messages-roles-list">
                                <table style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th><?php _ex('Role', 'Settings page', 'bp-better-messages'); ?></th>
                                        <th><?php _ex('Price', 'Settings page', 'bp-better-messages'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach( $wp_roles as $slug => $role ){
                                        $value = 0;

                                        if( isset($this->settings['myCredNewThreadCharge'][$slug])){
                                            $value = $this->settings['myCredNewThreadCharge'][$slug]['value'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $role['name']; ?></td>
                                            <td>
                                                <input name="myCredNewThreadCharge[<?php echo $slug; ?>][value]" type="number" min="0" value="<?php esc_attr_e($value); ?>">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top" style="width: 320px;">
                            <?php _ex( 'Message when user can`t start new conversation', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'HTML Allowed', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <input type="text" style="width: 100%" name="myCredNewThreadChargeMessage" value="<?php esc_attr_e(wp_unslash($this->settings['myCredNewThreadChargeMessage'])); ?>">
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Price for private calls', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'The charge will be applied to the caller. Users are billed per minute. First minute is charged immediately after call started.', 'Settings page', 'bp-better-messages' ); ?></p>
                            <p style="font-size: 10px;"><?php _ex( 'Use 0 if this is free', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div style="position: relative">
                                <?php $license_message = Better_Messages()->functions->license_proposal( true );
                                if( ! empty( $license_message ) ) { ?>
                                    <div style="box-sizing: border-box;position:absolute;background: #ffffffb8;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                        <?php echo $license_message; ?>
                                    </div>
                                <?php } ?>
                                <div class="bp-better-messages-roles-list">
                                    <table style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th><?php _ex('Role', 'Settings page', 'bp-better-messages'); ?></th>
                                            <th><?php _ex('Price', 'Settings page', 'bp-better-messages'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach( $wp_roles as $slug => $role ){
                                            $value = 0;

                                            if( isset($this->settings['myCredCallPricing'][$slug])){
                                                $value = $this->settings['myCredCallPricing'][$slug]['value'];
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $role['name']; ?></td>
                                                <td>
                                                    <input name="myCredCallPricing[<?php echo $slug; ?>][value]" type="number" min="0" value="<?php esc_attr_e($value); ?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top" style="width: 320px;">
                            <?php _ex( 'Message when user can`t start new call', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'HTML Allowed', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div style="position: relative">
                                <?php $license_message = Better_Messages()->functions->license_proposal( true );
                                if( ! empty( $license_message ) ) { ?>
                                    <div style="box-sizing: border-box;position:absolute;background: #ffffff;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                        <?php echo $license_message; ?>
                                    </div>
                                <?php } ?>
                                <input type="text" style="width: 100%" name="myCredCallPricingStartMessage" value="<?php esc_attr_e(wp_unslash($this->settings['myCredCallPricingStartMessage'])); ?>">
                            </div>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top" style="width: 320px;">
                            <?php _ex( 'Message when user has no enough point to continue call', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'HTML Allowed', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div style="position: relative">
                                <?php $license_message = Better_Messages()->functions->license_proposal( true );
                                if( ! empty( $license_message ) ) { ?>
                                    <div style="box-sizing: border-box;position:absolute;background: #ffffff;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                        <?php echo $license_message; ?>
                                    </div>
                                <?php } ?>
                                <input type="text" style="width: 100%" name="myCredCallPricingEndMessage" value="<?php esc_attr_e(wp_unslash($this->settings['myCredCallPricingEndMessage'])); ?>">
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>


            <div id="integrations_gamipress" class="bpbm-subtab ">
                <?php if ( ! class_exists( 'GamiPress' ) ) { ?>
                    <div class="bp-better-messages-connection-check bpbm-error" style="margin: 20px 0;">
                        <p><?php echo sprintf(esc_html_x('Website must to have %s plugin to be installed.', 'Settings page', 'bp-better-messages'), '<a href="https://www.wordplus.org/gamipress" target="_blank">GamiPress</a>'); ?></p>
                        <p><small><?php echo esc_attr_x('This notice will be hidden when GamiPress plugin is installed', 'Settings page', 'bp-better-messages'); ?></small></p>
                    </div>
                <?php } ?>
                <table class="form-table">
                    <tbody>

                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Point Type', 'Settings page', 'bp-better-messages' ); ?>
                        </th>
                        <td>
                            <?php if( function_exists('gamipress_get_points_types') ){ ?>
                                <?php $point_types = gamipress_get_points_types();
                                if( count( $point_types ) === 0 ){
                                    echo sprintf( __( 'No points types configured, visit %s to configure some points types.', 'gamipress' ), '<a href="' . admin_url( 'edit.php?post_type=points-type' ) . '" target="_blank">' . __( 'this page', 'gamipress' ) . '</a>' );
                                } else {
                                    ?>
                                    <select name="GamiPressPointType">
                                        <?php foreach( $point_types as $slug => $point_type ){ ?>
                                            <option <?php selected( $this->settings[ 'GamiPressPointType' ], $slug ); ?> value="<?php echo esc_attr( $slug ); ?>"><?php echo $point_type['singular_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php
                                }
                            } else { ?>
                                <div class="bp-better-messages-connection-check bpbm-error" style="margin: 0;">
                                    <p><?php echo sprintf(esc_html_x('Website must to have %s plugin to be installed.', 'Settings page', 'bp-better-messages'), '<a href="https://www.wordplus.org/gamipress" target="_blank">GamiPress</a>'); ?></p>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Price for new message in the conversation', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'Use 0 if this is free', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div class="bp-better-messages-roles-list">
                                <table style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th><?php _ex('Role', 'Settings page', 'bp-better-messages'); ?></th>
                                        <th><?php _ex('Price', 'Settings page', 'bp-better-messages'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach( $wp_roles as $slug => $role ){
                                        $value = 0;

                                        if( isset($this->settings['GamiPressNewMessageCharge'][$slug])){
                                            $value = $this->settings['GamiPressNewMessageCharge'][$slug]['value'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $role['name']; ?></td>
                                            <td>
                                                <input name="GamiPressNewMessageCharge[<?php echo $slug; ?>][value]" type="number" min="0" value="<?php esc_attr_e($value); ?>">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top" style="width: 320px;">
                            <?php _ex( 'Message when user can`t send new reply', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'HTML Allowed', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <input type="text" style="width: 100%" name="GamiPressNewMessageChargeMessage" value="<?php esc_attr_e(wp_unslash($this->settings['GamiPressNewMessageChargeMessage'])); ?>">
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Price for new starting new conversation', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'The charge will be applied additionally to the message price', 'Settings page', 'bp-better-messages' ); ?></p>
                            <p style="font-size: 10px;"><?php _ex( 'Use 0 if this is free', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div class="bp-better-messages-roles-list">
                                <table style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th><?php _ex('Role', 'Settings page', 'bp-better-messages'); ?></th>
                                        <th><?php _ex('Price', 'Settings page', 'bp-better-messages'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach( $wp_roles as $slug => $role ){
                                        $value = 0;

                                        if( isset($this->settings['GamiPressNewThreadCharge'][$slug])){
                                            $value = $this->settings['GamiPressNewThreadCharge'][$slug]['value'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $role['name']; ?></td>
                                            <td>
                                                <input name="GamiPressNewThreadCharge[<?php echo $slug; ?>][value]" type="number" min="0" value="<?php esc_attr_e($value); ?>">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top" style="width: 320px;">
                            <?php _ex( 'Message when user can`t start new conversation', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'HTML Allowed', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <input type="text" style="width: 100%" name="GamiPressNewThreadChargeMessage" value="<?php esc_attr_e(wp_unslash($this->settings['GamiPressNewThreadChargeMessage'])); ?>">
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top">
                            <?php _ex( 'Price for private calls', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'The charge will be applied to the caller. Users are billed per minute. First minute is charged immediately after call started.', 'Settings page', 'bp-better-messages' ); ?></p>
                            <p style="font-size: 10px;"><?php _ex( 'Use 0 if this is free', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div style="position: relative">
                                <?php $license_message = Better_Messages()->functions->license_proposal( true );
                                if( ! empty( $license_message ) ) { ?>
                                    <div style="box-sizing: border-box;position:absolute;background: #ffffffb8;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                        <?php echo $license_message; ?>
                                    </div>
                                <?php } ?>
                                <div class="bp-better-messages-roles-list">
                                    <table style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th><?php _ex('Role', 'Settings page', 'bp-better-messages'); ?></th>
                                            <th><?php _ex('Price', 'Settings page', 'bp-better-messages'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach( $wp_roles as $slug => $role ){
                                            $value = 0;

                                            if( isset($this->settings['GamiPressCallPricing'][$slug])){
                                                $value = $this->settings['GamiPressCallPricing'][$slug]['value'];
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $role['name']; ?></td>
                                                <td>
                                                    <input name="GamiPressCallPricing[<?php echo $slug; ?>][value]" type="number" min="0" value="<?php esc_attr_e($value); ?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top" style="width: 320px;">
                            <?php _ex( 'Message when user can`t start new call', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'HTML Allowed', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div style="position: relative">
                                <?php $license_message = Better_Messages()->functions->license_proposal( true );
                                if( ! empty( $license_message ) ) { ?>
                                    <div style="box-sizing: border-box;position:absolute;background: #ffffff;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                        <?php echo $license_message; ?>
                                    </div>
                                <?php } ?>
                                <input type="text" style="width: 100%" name="GamiPressCallPricingStartMessage" value="<?php esc_attr_e(wp_unslash($this->settings['GamiPressCallPricingStartMessage'])); ?>">
                            </div>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" valign="top" style="width: 320px;">
                            <?php _ex( 'Message when user has no enough point to continue call', 'Settings page', 'bp-better-messages' ); ?>
                            <p style="font-size: 10px;"><?php _ex( 'HTML Allowed', 'Settings page', 'bp-better-messages' ); ?></p>
                        </th>
                        <td>
                            <div style="position: relative">
                                <?php $license_message = Better_Messages()->functions->license_proposal( true );
                                if( ! empty( $license_message ) ) { ?>
                                    <div style="box-sizing: border-box;position:absolute;background: #ffffff;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                        <?php echo $license_message; ?>
                                    </div>
                                <?php } ?>
                                <input type="text" style="width: 100%" name="GamiPressCallPricingEndMessage" value="<?php esc_attr_e(wp_unslash($this->settings['GamiPressCallPricingEndMessage'])); ?>">
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>


        </div>

        <div id="tools" class="bpbm-tab">
            <table class="form-table">
                <tbody>
                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 150px;">
                        <?php _ex( 'Export Settings', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-weight: normal"><?php _ex('Copy settings, so you can import them later to another website', 'Settings page', 'bp-better-messages'); ?></p>
                    </th>
                    <td>
                        <?php
                        //$options = get_option( 'bp-better-chat-settings', array() );
                        //echo base64_encode(json_encode($options));
                        ?>
                        <textarea id="export-settings" readonly style="width: 100%;height: 200px;" onclick="this.focus();this.select()"></textarea>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 150px;">
                        <?php _ex( 'Import Settings', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-weight: normal"><?php _ex('Paste settings copied before', 'Settings page', 'bp-better-messages'); ?></p>
                    </th>
                    <td>
                        <textarea id="bpbm-import-area" style="width: 100%;height: 200px;"></textarea>
                        <button id="bpbm-import-settings" class="button" style="display:none;">Import</button>

                        <script type="text/javascript">

                            var button = jQuery('#tools-tab');

                            var bpbmsettingsLoaded = false;
                            function loadSettingsBase64(){
                                if( bpbmsettingsLoaded ){
                                    return false;
                                }

                                bpbmsettingsLoaded = true;

                                jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                                    'action'   : 'bp_messages_admin_export_options',
                                    'nonce'    : '<?php echo wp_create_nonce( 'bpbm-import-options' ); ?>'
                                }, function(response){
                                    jQuery('#export-settings').val(response);
                                });
                            }

                            jQuery(document).ready(function() {
                                if (button.hasClass('nav-tab-active')) {
                                    loadSettingsBase64();
                                }
                            });

                            button.click(function( event ){
                                loadSettingsBase64();
                            });

                            jQuery('#bpbm-import-area').change(function( event ){
                                var settings = jQuery(this).val();

                                if( settings.trim() === '' ){
                                    jQuery('#bpbm-import-settings').hide();
                                } else {
                                    jQuery('#bpbm-import-settings').show();
                                }
                            });

                            jQuery('#bpbm-import-settings').click(function( event ){
                                event.preventDefault();
                                var settingsArea = jQuery('#bpbm-import-area');
                                var settings = settingsArea.val();

                                if( settings.trim() !== '' ){
                                    jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                                        'action'   : 'bp_messages_admin_import_options',
                                        'settings' : settings,
                                        'nonce'    : '<?php echo wp_create_nonce( 'bpbm-import-options' ); ?>'
                                    }, function(response){
                                        alert(response.data);
                                        if( response.success ){
                                            location.reload();
                                        }
                                    });
                                }
                            });
                        </script>
                    </td>
                </tr>


                <?php
                $tables = Better_Messages_Rest_Api_DB_Migrate()->get_tables();
                ?>
                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 150px;">
                        <?php _ex( 'Database', 'Settings page','bp-better-messages' ); ?>
                    </th>
                    <td>
                        <table class="widefat widefat-standard " style="padding: 10px">
                            <tbody>
                            <tr>
                                <th colspan="2">
                                    <?php _ex( 'Database info', 'Settings page','bp-better-messages' ); ?>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _ex( 'UTF8MB4 Encoding', 'Settings page','bp-better-messages' ); ?>
                                </th>
                                <td><?php
                                    if( $wpdb->has_cap( 'utf8mb4' ) ){
                                        echo '<span style="color:green">';
                                        _ex('Supported', 'Settings page','bp-better-messages' );
                                        echo '</span>';
                                    } else {
                                        echo '<span style="color:red">';
                                        _ex('Not Supported', 'Settings page','bp-better-messages' );
                                        echo '</span>';
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <th colspan="2">
                                    <?php _ex( 'Database tables', 'Settings page','bp-better-messages' ); ?>
                                </th>
                            </tr>
                            <?php foreach( $tables as $table ){
                                $exists = false;
                                $collation = '';
                                $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) );

                                if ( $wpdb->get_var( $query ) == $table ) {
                                    $exists = true;

                                    $table_info = $wpdb->get_row($wpdb->prepare('SHOW TABLE STATUS WHERE NAME LIKE %s;', $table));
                                    if( $table_info && isset( $table_info->Collation ) ){
                                        $collation = $table_info->Collation;
                                    }
                                }
                                ?>
                                <tr>
                                    <th><?php echo $table; ?></th>
                                    <td><?php
                                        if( $exists ){
                                            echo '<span style="color:green">';
                                            _ex('Table exists', 'Settings page','bp-better-messages' );
                                            if( $collation ){
                                                echo ' (' . $collation . ')';
                                            }
                                            echo '</span>';
                                        } else {
                                            echo '<span style="color:red">';
                                            _ex('Table not exists', 'Settings page','bp-better-messages' );
                                            echo '</span>';
                                        }
                                        ?></td>
                                </tr>
                            <?php } ?>
                            <?php
                            $last_sync = get_option('bm_sync_user_roles_index_finish', false);
                            $last_sync = ( $last_sync ) ? date('d-m-Y H:i:m', $last_sync) : '-';

                            $next_sync = wp_next_scheduled( 'better_messages_sync_user_index_weekly' );
                            $next_sync = ( $next_sync ) ? date('d-m-Y H:i:m', $next_sync) : '-';
                            ?>
                            <tr>
                                <th>
                                    <?php _ex( 'User Index', 'Settings page','bp-better-messages' ); ?>
                                </th>
                                <td>
                                    <p><?php _ex( 'Last full synchronization', 'Settings page','bp-better-messages' ); ?>: <?php echo $last_sync; ?></p>
                                    <p><?php _ex( 'Next full synchronization', 'Settings page','bp-better-messages' ); ?>: <?php echo $next_sync; ?></p>

                                    <p><span id="bm-sync-users" class="button"><?php _ex( 'Synchronize now', 'Settings page','bp-better-messages' ); ?></span></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _ex( 'Convert to UTF8MB4', 'Settings page','bp-better-messages' ); ?>
                                    <p style="font-weight:normal;font-size:13px"><?php _ex( 'Convert database tables to utf8mb4. You do not need to do this if your database tables are already converted to utf8mb4.', 'Settings page','bp-better-messages' ); ?></p>
                                </th>
                                <td>
                                    <span id="bm-convert-database" class="button"><?php _ex( 'Convert', 'Settings page','bp-better-messages' ); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _ex( 'Reset Database', 'Settings page','bp-better-messages' ); ?>
                                    <p style="font-weight:normal;font-size:13px"><?php _ex( 'Complete delete database data and reinstall the tables', 'Settings page','bp-better-messages' ); ?></p>
                                </th>
                                <td>
                                    <span id="bm-reset-database" class="button" style="color:#d63638;border-color:#d63638;"><?php _ex( 'Reset Database', 'Settings page','bp-better-messages' ); ?></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>

            <script type="text/javascript">
                var resetBtn = jQuery('#bm-reset-database');

                resetBtn.on('click', function(event){
                    event.preventDefault();
                    var confirm = prompt('Please confirm the deletion of all database messages and reinstalling tables. This action is not reversible. Please make backup before doing this. Write RESET to confirm.');

                    if( confirm === 'RESET' ){
                        jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                            'action'   : 'better_messages_admin_reset_database',
                            'nonce'    : '<?php echo wp_create_nonce( 'bm-reset-database' ); ?>'
                        }, function(response){
                            alert( response )
                        });
                    }
                });

                var convertBtn = jQuery('#bm-convert-database');

                convertBtn.on('click', function(event){
                    event.preventDefault();
                    var _confirm = confirm('Please confirm the action.');

                    if( _confirm ){
                        jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                            'action'   : 'better_messages_admin_convert_database',
                            'nonce'    : '<?php echo wp_create_nonce( 'bm-convert-database' ); ?>'
                        }, function(response){
                            alert( response )
                            location.reload();
                        });
                    }

                });

                var syncUserBtn = jQuery('#bm-sync-users');

                syncUserBtn.on('click', function(event) {
                    event.preventDefault();

                    if( syncUserBtn.hasClass('disabled') ) return;

                    var _confirm = confirm('Please confirm the action. If you have many users, this action can take few minutes to complete.');

                    if (_confirm) {
                        syncUserBtn.addClass('disabled');

                        jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                            'action': 'better_messages_admin_sync_users',
                            'nonce': '<?php echo wp_create_nonce('bm-sync-users'); ?>'
                        }, function (response) {
                            alert(response)
                            location.reload();
                        });
                    }
                });
            </script>
        </div>

        <div id="shortcodes" class="bpbm-tab">
            <table class="form-table">
                <tbody>
                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 350px;">
                        <?php _ex( 'Unread messages counter', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-weight: normal"><?php _ex('Show unread messages counter anywhere in your website', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('To add this shortcode to your menu item you can use <a href="https://wordpress.org/plugins/shortcode-in-menus/" target="_blank">Shortcode in Menus</a> plugin.', 'Settings page', 'bp-better-messages'); ?></p>

                        <a href="https://www.better-messages.com/docs/shortcodes/better_messages_unread_counter" class="button bm-docs-btn" target="_blank">Docs <span class="dashicons dashicons-external"></span></a>
                    </th>
                    <td>
                        <input readonly type="text" style="width: 100%;" onclick="this.focus();this.select()" value='[better_messages_unread_counter hide_when_no_messages="1" preserve_space="1"]'>
                    </td>
                </tr>
                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 350px;">
                        <?php _ex( 'My messages URL', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-weight: normal"><?php _ex('Return url to logged in user inbox', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php
                            $result = do_shortcode('[better_messages_my_messages_url]');
                            if( ! empty( $result ) ) {
                                _ex('For example: ', 'Settings page', 'bp-better-messages');
                                echo '<strong>' . $result . '</strong>';
                            }
                            ?></p>

                        <a href="https://www.better-messages.com/docs/shortcodes/better_messages_my_messages_url" class="button bm-docs-btn" target="_blank">Docs <span class="dashicons dashicons-external"></span></a>
                    </th>
                    <td>
                        <input readonly type="text" style="width: 100%;" onclick="this.focus();this.select()" value='[better_messages_my_messages_url]'>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 350px;">
                        <?php _ex( 'Private Message Button', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-weight: normal"><?php _ex('Shows private message button', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('This shortcode will try to find user_id from environment, for example author of post and display Private Message button.', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('You can force user id with user_id="1" attribute.', 'Settings page', 'bp-better-messages'); ?></p>

                        <a href="https://www.better-messages.com/docs/shortcodes/better_messages_pm_button" class="button bm-docs-btn" target="_blank">Docs <span class="dashicons dashicons-external"></span></a>
                    </th>
                    <td>
                        <input readonly type="text" style="width: 100%;" onclick="this.focus();this.select()" value='[better_messages_pm_button text="Private Message" subject="Have a question to you" message="Lorem Ipsum is simply dummy text of the printing and typesetting industry." target="_self" class="extra-class" fast_start="0" url_only="0"]'>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 350px;">
                        <?php _ex( 'Single Conversation Display', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-weight: normal"><?php _ex('Shows single conversation at any page', 'Settings page', 'bp-better-messages'); ?></p>

                        <a href="https://www.better-messages.com/docs/shortcodes/better_messages_single_conversation" class="button bm-docs-btn" target="_blank">Docs <span class="dashicons dashicons-external"></span></a>
                    </th>
                    <td>
                        <input readonly type="text" style="width: 100%;" onclick="this.focus();this.select()" value='[better_messages_single_conversation thread_id="55"]'>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 350px;">
                        <?php _ex( 'Mini Chat Button', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-weight: normal"><?php _ex('Shows mini chat button (opens mini chat with user on click)', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('This button will work only if Mini Chats option is enabled', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('This shortcode will try to find user_id from environment, for example author of post and display Mini Chat button.', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('You can force user id with user_id="1" attribute.', 'Settings page', 'bp-better-messages'); ?></p>
                        <a href="https://www.better-messages.com/docs/shortcodes/better_messages_mini_chat_button" class="button bm-docs-btn" target="_blank">Docs <span class="dashicons dashicons-external"></span></a>
                    </th>
                    <td style="position: relative">
                        <div style="position: relative">
                            <?php $license_message = Better_Messages()->functions->license_proposal( true );
                            if( ! empty( $license_message ) ) { ?>
                                <div style="box-sizing: border-box;position:absolute;background: #ffffff;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                    <?php echo $license_message; ?>
                                </div>
                            <?php } ?>
                            <input readonly type="text" style="width: 100%;" onclick="this.focus();this.select()" value='[better_messages_mini_chat_button text="Private Message" class="extra-class"]'>
                        </div>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 350px;">
                        <?php _ex( 'Video Call Button', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-weight: normal"><?php _ex('Shows video call button', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('This shortcode will try to find user_id from environment, for example author of post and display Video Call button.', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('You can force user id with user_id="1" attribute.', 'Settings page', 'bp-better-messages'); ?></p>
                        <a href="https://www.better-messages.com/docs/shortcodes/better_messages_video_call_button" class="button bm-docs-btn" target="_blank">Docs <span class="dashicons dashicons-external"></span></a>
                    </th>
                    <td>
                        <div style="position: relative">
                            <?php $license_message = Better_Messages()->functions->license_proposal( true );
                            if( ! empty( $license_message ) ) { ?>
                                <div style="box-sizing: border-box;position:absolute;background: #ffffff;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                    <?php echo $license_message; ?>
                                </div>
                            <?php } ?>
                            <input readonly type="text" style="width: 100%;" onclick="this.focus();this.select()" value='[better_messages_video_call_button text="Video Call" url_only="0" class="extra-class"]'>
                        </div>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" valign="top" style="width: 350px;">
                        <?php _ex( 'Audio Call Button', 'Settings page', 'bp-better-messages' ); ?>
                        <p style="font-weight: normal"><?php _ex('Shows audio call button', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('This shortcode will try to find user_id from environment, for example author of post and display Audio Call button.', 'Settings page', 'bp-better-messages'); ?></p>
                        <p style="font-weight: normal"><?php _ex('You can force user id with user_id="1" attribute.', 'Settings page', 'bp-better-messages'); ?></p>
                        <a href="https://www.better-messages.com/docs/shortcodes/better_messages_audio_call_button" class="button bm-docs-btn" target="_blank">Docs <span class="dashicons dashicons-external"></span></a>
                    </th>

                    <td style="position: relative">
                        <div style="position: relative">
                            <?php $license_message = Better_Messages()->functions->license_proposal( true );
                            if( ! empty( $license_message ) ) { ?>
                                <div style="box-sizing: border-box;position:absolute;background: #ffffff;width: 100%;height: 100%;text-align: center;display: flex;align-items: center;justify-content: center;">
                                    <?php echo $license_message; ?>
                                </div>
                            <?php } ?>
                            <input readonly type="text" style="width: 100%;" onclick="this.focus();this.select()" value='[better_messages_audio_call_button text="Audio Call" url_only="0" class="extra-class"]'>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        <p class="submit">
            <input type="submit" name="save" id="submit" class="button button-primary" value="<?php _ex( 'Save Changes', 'Settings page', 'bp-better-messages' ); ?>">
        </p>
    </form>
</div>
