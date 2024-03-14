<?php
/**
 * @file
 * ExtraWatch - Real-time visitor dashboard and stats
 * @package ExtraWatch
 * @version 4.0
 * @revision 53
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2021 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

function extrawatch_add_support_link( $plugin_meta, $pluginFile, $pluginData, $status ) {
    if ( strpos( $pluginFile, basename("extrawatch.php") ) !== false) {
        $plugin_meta[] = '<br/><br/>' . extrawatch_add_social_links();
    }
    return $plugin_meta;
}

function extrawatch_add_social_links( $prefix = '' ) {

    $socialLink = '<style type="text/css">
                            div.' . $prefix . '_social_links > iframe {
                                max-height: 1.5em;
                                vertical-align: middle;
                                padding: 5px 2px 0px 0px;
                            }
                            iframe[id^="twitter-widget"] {
                                max-width: 10.3em;
                            }
                            iframe#fb_like_' . $prefix . ' {
                                max-width: 6em;
                            }
                            span > iframe {
                                vertical-align: middle;
                            }
                        </style>';
    $socialLink .= '<a href="https://twitter.com/ExtraWatch" class="twitter-follow-button" data-show-count="true" data-dnt="true" data-show-screen-name="false">Follow</a>';
    $socialLink .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
    $socialLink .= '<iframe id="fb_like_' . $prefix . '" src="http://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FExtraWatch&width=100&layout=button_count&action=like&show_faces=false&share=false&height=21" height="21"></iframe>';

    return $socialLink;

}

add_filter( 'plugin_row_meta', 'extrawatch_add_support_link', 10, 4 );
