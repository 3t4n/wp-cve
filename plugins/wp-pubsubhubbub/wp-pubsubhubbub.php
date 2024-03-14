<?php
/*
Plugin Name: WP Pubsubhubbub
Plugin URI: http://github.com/padraic/wordpress-pubsubhubbub/
Description: Implements a Pubsubhubbub Real-Time Publisher informing Planet Earth of your blog updates now, not later, with support for multiple Hubs and the most recent emerging practices. Edit the Hubs in use on the <a href="./options-general.php?page=wp-pubsubhubbub/wp-pubsubhubbub">WP Pubsubhubbub settings page</a>
Version: 1.2.0
Author: Padraic Brady
Author Email: padraic.brady@yahoo.com
Author URI: http://blog.astrumfutura.com
*/

/**
Copyright (c) 2009, Padraic Brady
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.

    * Redistributions in binary form must reproduce the above copyright notice,
      this list of conditions and the following disclaimer in the documentation
      and/or other materials provided with the distribution.

    * Neither the name of Padraic Brady nor the names of its
      contributors may be used to endorse or promote products derived from this
      software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
 * Set up a functioning path for loading the Pubsubhubbub library files
 * needed. Only those required are packaged, imported from latest svn/git HEAD.
 */
define('WPPSH_ROOT', dirname(__FILE__));
define('WPPSH_LIBRARY', WPPSH_ROOT  . '/library');
require_once WPPSH_LIBRARY . '/Zend/Pubsubhubbub/Publisher.php';

// All custom functions/options prefixed with "wppsh_" to avoid name clashes

/**
 * Add an action hook to initiate Pubsubhubbub Publisher notifications
 * to all configured Hubs on blog updates including comment saves/edits
 */
add_action('publish_post', 'wppsh_notify_hubs');
add_action('wp_set_comment_status', 'wppsh_notify_hubs_comments');
add_action('comment_post', 'wppsh_notify_hubs_comments');

/**
 * Issue a notification to all utilised Hubs about the current update. Seems
 * silly but any Subscriber can subscribe to any feed URL, i.e. it could be
 * Atom 1.0 or RSS 2.0. RSS 1.0 and RDF feeds are included since these are
 * nevertheless valid subscription targets.
 *
 * Subscribers should be encouraged to subscribe to Atom 1.0 and RSS 2.0
 * feeds, simply because processing these may be easier and simpler for most
 * modern feed parsing solutions.
 */
function wppsh_notify_hubs($id, $comments = false) {
    try {
        $publisher = new Zend_Pubsubhubbub_Publisher;
        if (!$comments) {
            $feeds = array_unique(array(
                get_bloginfo('rss2_url'),
                get_bloginfo('atom_url'),
                get_bloginfo('rss_url'),
                get_bloginfo('rdf_url')
            ));
        } else {
            $comment = get_comment($id);
            $pid = get_post($comment->comment_post_ID)->ID;
            $commentsRss2Url = get_bloginfo('comments_rss2_url');
            $feeds = array_unique(array(
                $commentsRss2Url,
                str_replace('rss2', 'atom', $commentsRss2Url),
                get_bloginfo('rss2_url') . '&p=' . $pid,
                get_bloginfo('atom_url') . '&p=' . $pid
            ));
        }
        $publisher->addUpdatedTopicUrls($feeds);
        $hubs = explode("\n", trim(wppsh_get_hubs()));
        foreach ($hubs as $url) {
            $publisher->addHubUrl(trim($url));
        }
        $publisher->notifyAll();
    } catch (Exception $e) {
        // Do not report errors - would interrupt posting of blog entry.
    }
}

/**
 * Simple wrapper to pass Comment ID to notify function
 */
function wppsh_notify_hubs_comments($commentId)
{
    wppsh_notify_hubs($commentId, true);
}

/**
 * Return the array of Hubs supported by this blog. If none are defined by
 * the user, we'll assume they are using the current Google reference hub
 * with a Superfeedr failover Hub to prevent any single points of failure.
 * This is a convenient default but the user should be encouraged to
 * deliberately select this, or other Hubs for clarity.
 */
function wppsh_get_hubs() {
    $hub = get_option('wppsh_hub_urls');
    if (!$hub) {
        return "http://pubsubhubbub.appspot.com\nhttp://superfeedr.com/hubbub";
    } else {
        return $hub;
    }
}

/**
 * In order for Pubsubhubbub to operate, all feeds must contain a <link> tag
 * under the Atom 1.0 XML Namespace with a "rel" attribute value of "hub" and a
 * "href" attribute value indicating the Hub's endpoint URL. This <link> may be
 * repeated to indicate the blog notifies multiple Hubs of updates.
 * Subscribers may subscribe to one or more of these Hubs.
 *
 * Callback functions are declared after this list. Where the Atom 1.0
 * namespace is not already declared in the root element of the feed, it is
 * instead declared inline to prevent any conflicting/duplicated namespace
 * declarations from other plugins.
 */
add_action('atom_head', 'wppsh_add_atom10_links');
add_action('rss2_head', 'wppsh_add_rss20_links');
add_action('rdf_header', 'wppsh_add_rss10_links');
add_action('rss_head', 'wppsh_add_rss092_links');

/**
 * Add Hub support for comment feeds
 */
add_action('commentsrss2_head', 'wppsh_add_rss20_links');
add_action('comments_atom_head', 'wppsh_add_atom10_links');

function wppsh_add_links($rss = null) {
    $namespacePrefix = '';
    $namespaceDeclaration = ' ';
    if (!is_null($rss)) {
        $namespacePrefix = 'atom:';
        if ($rss !== '2.0') {
            $namespaceDeclaration = ' xmlns:atom="http://www.w3.org/2005/Atom" ';
        }
    }
    $hubs = explode("\n", trim(wppsh_get_hubs()));
    $out = '';
    foreach ($hubs as $url) {
        $out .= '<' . $namespacePrefix . 'link rel="hub" href="'
        . trim($url) . '"' . $namespaceDeclaration . '/>' . "\n\t";
    }
    echo $out;
}

/**
 * Parameter wrapper functions to add links with correct namespacing.
 * Doing it this way also avoids the need to tamper with the XML root
 * element's namespace declarations.
 */
function wppsh_add_atom10_links() {
    wppsh_add_links(null);
}
function wppsh_add_rss20_links() {
    wppsh_add_links('2.0');
}
function wppsh_add_rss10_links() {
    wppsh_add_links('2.0');
}
function wppsh_add_rss092_links() {
    wppsh_add_links('0.92');
}

/**
 * Create Administration Interface Hook and a function to write out
 * the necessary HTML.
 */
add_action('admin_menu', 'wppsh_include_options_page');

function wppsh_include_options_page() {
    add_options_page('WP Pubsubhubbub Settings', 'WP Pubsubhubbub', 8, __FILE__, 'wppsh_write_options_page');
}

function wppsh_write_options_page() {
    include WPPSH_ROOT . '/options.phtml';
}
