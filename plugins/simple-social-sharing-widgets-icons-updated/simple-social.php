<?php
/*
Plugin Name: Simple Social - Sharing Widgets & Icons Updated
Version: 0.3.6
Description: Adds a set of social sharing widgets & icons after each post.
Plugin URI: http://wordpress.org/extend/plugins/simple-social-sharing-widgets-icons-updated-updated/
*/

function simple_social($content) {
    global $post, $simplesocial_icons_pixels;

    $simplesocial_permlink = get_permalink($post->ID);
    $simplesocial_enclink = urlencode($simplesocial_permlink);
    $simplesocial_title = get_the_title($post->ID);
    $simplesocial_title_4url = urlencode($simplesocial_title);
    $simplesocial_dir = get_option('home').'/wp-content/plugins/simple-social-sharing-widgets-icons-updated/icons_'.get_option('ss_iconsize',32).'/';
    $simplesocialcontent = '';

    if (!is_feed() && !is_page() && is_single()) {
        $simplesocialcontent .= '<div class="simplesocial-box">';

        // Title
        $simplesocialcontent .= '<div class="simplesocial-title" style="padding-top:10px;margin-bottom:10px;font-size:10pt;font-family:arial;font-weight:bold;">'.get_option('ss_title','Did you like this? Share it:').'</div>';

        // Twitter widget
        if (get_option('ss_twitterwidget','1')) {
            $simplesocialcontent .= '<div class=simplesocial><a href="http://twitter.com/share" data-url="'.$simplesocial_permlink.'" data-text="'.$simplesocial_title.'" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>';
        }

        // Google +1 widget
        if (get_option('ss_googlepluswidget','1')) {
            $simplesocialcontent .= '<div class=simplesocial><script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><g:plusone></g:plusone></div>';
        }

        // Facebook widget
        if (get_option('ss_facebookwidget','1')) {
            $simplesocialcontent .= '<div class=simplesocial><iframe src="http://www.facebook.com/plugins/like.php?href='.$simplesocial_enclink.'&layout=standard&show_faces=false&width=350&action=like&colorscheme=light&height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:25px;" allowTransparency="true"></iframe></div>';
        }

        // New Line
        $simplesocialcontent .= '<div style="clear:both"></div>';

        // Facebook Button
        if (get_option('ss_facebook','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,500,400)" title="Share on Facebook" style="background:url('.$simplesocial_dir.'facebook.png)" href="http://www.facebook.com/share.php?u='.$simplesocial_enclink.'&t='.$simplesocial_title_4url.'"></a>';
        }

        // Twitter Button
        if (get_option('ss_twitter','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,812,420)" title="Share on Twitter" style="background:url('.$simplesocial_dir.'twitter.png)" href="http://twitter.com/home?status='.$simplesocial_enclink.'"></a>';
        }

        // Email Button
        if (get_option('ss_email','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,435,500)" title="Email a Friend" style="background:url('.$simplesocial_dir.'email.png)" href="http://www.freetellafriend.com/tell/?heading=Share+This+Article&bg=1&option=email&url='.$simplesocial_enclink.'"></a>';
        }

        // Blogger Button
        if (get_option('ss_blogger','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,750,500)" title="Share on Blogger" style="background:url('.$simplesocial_dir.'blogger.png)" href="http://www.blogger.com/blog_this.pyra?t&u='.$simplesocial_enclink.'&n='.$simplesocial_title_4url.'&pli=1"></a>';
        }

        // Delicious Button
        if (get_option('ss_delicious','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,890,550)" title="Share on Delicious" style="background:url('.$simplesocial_dir.'delicious.png)" href="http://del.icio.us/post?url='.$simplesocial_enclink.'&title='.$simplesocial_title_4url.'"></a>';
        }

        // Digg Button
        if (get_option('ss_digg','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,812,420)" title="Share on Digg" style="background:url('.$simplesocial_dir.'digg.png)" href="http://digg.com/submit?url='.$simplesocial_enclink.'&title='.$simplesocial_title_4url.'"></a>';
        }

        // Google Button
        if (get_option('ss_google','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,750,500)" title="Share on Google" style="background:url('.$simplesocial_dir.'google.png)" href="http://www.google.com/bookmarks/mark?op=add&bkmk='.$simplesocial_enclink.'&title='.$simplesocial_title_4url.'"></a>';
        }

        // Myspace Button
        if (get_option('ss_myspace','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,812,420)" title="Share on Myspace" style="background:url('.$simplesocial_dir.'myspace.png)" href="http://www.myspace.com/Modules/PostTo/Pages/?u='.$simplesocial_enclink.'&t='.$simplesocial_title_4url.'&c='.$simplesocial_enclink.'"></a>';
        }

        // StumbleUpon Button
        if (get_option('ss_stumbleupon','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,750,500)" title="Share on StumbleUpon" style="background:url('.$simplesocial_dir.'stumbleupon.png)" href="http://www.stumbleupon.com/submit?url='.$simplesocial_enclink.'&title='.$simplesocial_title_4url.'"></a>';
        }

        // Yahoo Button
        if (get_option('ss_yahoo','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,900,550)" title="Share on Yahoo" style="background:url('.$simplesocial_dir.'yahoo.png)" href="http://buzz.yahoo.com/buzz?targetUrl='.$simplesocial_enclink.'&headline='.$simplesocial_title_4url.'"></a>';
        }

        // Reddit Button
        if (get_option('ss_reddit','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,700,500)" title="Share on Reddit" style="background:url('.$simplesocial_dir.'reddit.png)" href="http://reddit.com/submit?url='.$simplesocial_enclink.'&title='.$simplesocial_title_4url.'"></a>';
        }

        // Technorati Button
        if (get_option('ss_technorati','1')) {
            $simplesocialcontent .= '<a class=simplesocial onclick="return simplesocial(this,812,500)" title="Share on Technorati" style="background:url('.$simplesocial_dir.'technorati.png)" href="http://technorati.com/faves?sub=favthis&add='.$simplesocial_enclink.'"></a>';
        }

        // RSS Button
        if (get_option('ss_rss','1')) {
            $simplesocialcontent .= '<a class=simplesocial title="RSS Feed" style="background:url('.$simplesocial_dir.'rss.png)" href="'.get_option('home').'/?feed=rss2"></a>';
        }

        // End
        $simplesocialcontent.='<div style="clear:both"></div></div><div class="simplesocial-clear" style="clear:both;margin-bottom:20px"></div>';
    }

    return $content.$simplesocialcontent;
}


function simple_social_css() {
    if (!is_feed() && !is_page() && is_single()) {
        echo '<style type="text/css">div.simplesocial,a.simplesocial{float:left;display:block}a.simplesocial{margin-right:5px;width:'.get_option('ss_iconsize',32).'px;height:'.get_option('ss_iconsize',32).'px}a.simplesocial:hover{margin-top:-2px}</style>
            <script language="javascript">function simplesocial(t,w,h){
            window.open(t.href, \'simplesocial\', \'scrollbars=1,menubar=0,width=\'+w+\',height=\'+h+\',resizable=1,toolbar=0,location=0,status=0,left=\'+(screen.width-w)/2+\',top=\'+(screen.height-h)/3);
            return false;}</script>' . "\n";
    }
}

function simple_social_options(){
    $simple_social_icons = array('twitterwidget','facebookwidget','googlepluswidget','facebook','twitter','email','blogger','delicious','digg','google','myspace','stumbleupon','yahoo','reddit','technorati','rss');
    foreach ($simple_social_icons as $item) {
        $simple_social_pageoptions.='ss_'.$item.',';
    }
    echo '<form method="post" action="options.php"><h3>Text shown above icons:</h3><div style="padding:20px;"><input type="text" size=50 name="ss_title" value="'.get_option('ss_title','Did you like this? Share it:').'"></div><h3>Select Icon Size:</h3><div style="padding:20px;">
        <input type="radio" value="16" name="ss_iconsize" id=ss_16 '.(get_option('ss_iconsize',32)==16?'checked':'unchecked').'> <label for=ss_16>Small (16x16)</label>&nbsp;&nbsp;&nbsp;<input type="radio" '.(get_option('ss_iconsize',32)==32?'checked':'unchecked').' id=ss_32 value="32" name="ss_iconsize"> <label for=ss_32>Large (32x32)</label></div>
        <h3>Select icons/widgets to display:</h3><div style="padding:20px;">';

    foreach ($simple_social_icons as $item) {
        if($item=='twitterwidget') {
            echo '<div style="margin-bottom:10px;font-weight:bold;font-size:9pt"><input style="margin-right:10px" id=cb_'.$item.' type="checkbox" size="20" name="ss_'.$item.'" '.(get_option('ss_'.$item,true)==true?'checked':'unchecked').'><label for="cb_'.$item.'"><img src="'.get_settings('home').'/wp-content/plugins/simple-social-sharing-widgets-icons-updated/img/twitterwidget.png"></label></div>';
        } else if($item=='facebookwidget') {
            echo '<div style="margin-bottom:10px;font-weight:bold;font-size:9pt"><input style="margin-right:10px" id=cb_'.$item.' type="checkbox" size="20" name="ss_'.$item.'" '.(get_option('ss_'.$item,true)==true?'checked':'unchecked').'><label for="cb_'.$item.'"><img src="'.get_option('home').'/wp-content/plugins/simple-social-sharing-widgets-icons-updated/img/facebookwidget.png"></label></div>';
        } else if($item=='googlepluswidget') {
            echo '<div style="margin-bottom:10px;font-weight:bold;font-size:9pt"><input style="margin-right:10px" id=cb_'.$item.' type="checkbox" size="20" name="ss_'.$item.'" '.(get_option('ss_'.$item,true)==true?'checked':'unchecked').'><label for="cb_'.$item.'"><img src="'.get_option('home').'/wp-content/plugins/simple-social-sharing-widgets-icons-updated/img/googlepluswidget.png"></label></div><div style="clear:both"></div>';
        } else{
            echo '<div style="float:left;margin-right:30px;margin-bottom:10px;"><input style="margin-top:-15px;margin-right:5px" id=cb_'.$item.' type="checkbox" size="20" name="ss_'.$item.'" '.(get_option('ss_'.$item,true)==true?'checked':'unchecked').'><label for="cb_'.$item.'"><img src="'.get_option('home').'/wp-content/plugins/simple-social-sharing-widgets-icons-updated/icons_32/'.$item.'.png"></label></div>';
        }
    }


    echo '</div><div style="clear:both"></div><p class="submit"><input type="submit" class="button-primary" value="Save Changes"/>';
    wp_nonce_field('update-options');
    echo '<input type="hidden" name="page_options" value="'.$simple_social_pageoptions.'ss_title,ss_iconsize"><input type="hidden" name="action" value="update" /></form>';
}


add_action('wp_head', 'simple_social_css');
add_filter('the_content', 'simple_social');
add_filter('plugin_action_links', 'simple_social_settinglink', 10, 2);
add_action('admin_menu', 'simple_social_addmenu');

function simple_social_addmenu() {
    add_options_page("Simple Social", "Simple Social Icons", "administrator", "simple_social", "simple_social_options");
}

function simple_social_settinglink($links,$file) {
    if ($file=='simple-social-sharing-widgets-icons-updated/simple-social.php') {
        array_unshift($links,'<a href="options-general.php?page=simple_social">Settings</a>');
    }

    return $links;
}

?>