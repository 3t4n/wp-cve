<?php

/** 

 * Plugin Name: RSS Just Better 

 * Plugin URI: http://www.stefonthenet.com/rss-just-better-plugin-for-wordpress/

 * Description: Displays a list of RSS/Atom feed items given a feed URL and (optional) no. of items, keyword(s), cache refresh frequency. Customizable look & feel. 

 * Version: 1.4

 * Author: Stef Marchisio 

 * Author URI: http://stefonthenet.com/about/

 *

 * This program is distributed in the hope that it will be useful,

 * but WITHOUT ANY WARRANTY; without even the implied warranty of

 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

 */



/* 

This is a wordpress plugin compatible with wordpress 2.8+ as a widget; wordpress 2.5+ as shortcode.

Copyright (C) 2015  Stefania Marchisio (email: stefonthenet@gmail.com)

This program is free software; you can redistribute it and/or

modify it under the terms of the GNU General Public License

as published by the Free Software Foundation; either version 2

of the License, or (at your option) any later version.



This program is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

GNU General Public License for more details.



You should have received a copy of the GNU General Public License

along with this program; if not, write to the Free Software



Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/



/**

 * Add function to widgets_init

 * @since 0.1

 */

add_action( 'widgets_init', 'funct_RSSjb' );



/**

 * Register our widget.

 * 'WP_RSSjb' is the widget class used below.

 *

 * @since 0.1

 */

 function funct_RSSjb() {

   register_widget( 'WP_RSSjb' );

 }



/**

 * WP_RSSjb Widget class.

 * This class handles everything that needs to be handled with the widget:

 * the settings, form, display, and update.  Nice!

 *

 * @since 0.1

 */

class WP_RSSjb extends WP_Widget {



/**

 * Widget setup.

 */

function WP_RSSjb() {

 /* Widget settings. */

$widget_ops = array( 'classname' => 'rssjustbetter', 'description' => __('A customizable list of feed items given: URL and number of displayable items. Also available as shortcode. Compatible with RSS vers. 0.91, 0.92 and 2.0 & Atom 1.0.', 'RSSjb') );



/* Widget control settings. */

	$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'rss-just-better' );



/* Create the widget. */

	$this->WP_Widget( 'rss-just-better', __('RSS Just Better', 'RSSjb'), $widget_ops, $control_ops );

}



/**

 * How to display the widget on the screen.

 */

function widget( $args, $instance ) {

	extract( $args );

	/* Our variables from the widget settings. */

  	$title = apply_filters('widget_title', $instance['title'] );

	$location = $instance['location'];

	$geo = $instance['geo'];

	$gsearch = $instance['gsearch'];

	$topic = $instance['topic'];

	$feed = $instance['name'];

	$cachefeed = $instance['cachefeed'];

	$filter = $instance['filter'];

	$num = $instance['number'];

	$list = $instance['list'];

	$ltime = $instance['ltime'];

	$target= $instance['target'];

	$charex = $instance['charex'];

	$dformat = $instance['dformat'];

	$tformat = $instance['tformat'];

	$chartle = $instance['chartle'];

	// $sort = $instance['sort'];

    /* Boolean vars */

	$lkbtitle = isset( $instance['lkbtitle'] ) ? $instance['lkbtitle'] : false;

	$pubdate = isset( $instance['pubdate'] ) ? $instance['pubdate'] : false;

	$pubtime = isset( $instance['pubtime'] ) ? $instance['pubtime'] : false;

	$pubauthor = isset( $instance['pubauthor'] ) ? $instance['pubauthor'] : false;

	$excerpt = isset( $instance['excerpt'] ) ? $instance['excerpt'] : false;

	$sort = isset( $instance['sort'] ) ? $instance['sort'] : false;

	/* Before widget (defined by themes). */

	echo $before_widget;

	// if a location is entered, a Google News feed will be displayed instead

	if ($location) {

		$gsearch = urlencode($gsearch);

		$feed = "http://news.google.com/news/section?cf=all&ned=" . $location;

    		if ($geo) $feed .= "&geo=$geo";		// Google News local (usa & Ca only) search, if any entered

		if ($gsearch) $feed .= "&q=$gsearch";	// Google News free search, if any entered

     		if ($topic) $feed .= "&topic=$topic";	// Google News topic, if any entered

	}

	/* if a title is entered the title linking to the feed will be displayed (before and after widget title are defined by themes). */

	$tle = '';

	if ( $title && $feed ) {

        	if ( $lkbtitle ) $tle = "<a target='" . $target . "' href='$feed' title='$title'><img src='/wp-content/plugins/rss-just-better/rss-cube.gif' width='25px' height='25px' title=' [feed link] '></a> ";

		echo $before_title . $tle . $title . $after_title;

        }

	/* Call the function to read & display the feed content */

        echo rssjb_List($feed, $filter, $num, $ltime, $list, $target, $pubdate, $pubtime, $dformat, $tformat, $pubauthor, $excerpt, $charex, $chartle, $sort, $cachefeed);

	/* After widget (defined by themes). */

	echo $after_widget;

}

/**

 * Update the widget settings.

 */

function update( $new_instance, $old_instance ) {

	$instance = $old_instance;

	/* Strip tags for title & name 2 remove HTML (important for text inputs). */

	$instance['title'] = strip_tags($new_instance['title']);

	// vars gsearch & geo are for Google News search

	$instance['gsearch'] = strip_tags($new_instance['gsearch']);

	// vars geo is for local search (city or zipcode) and is for USA & Canada only

	$instance['geo'] = strip_tags($new_instance['geo']);

	$instance['name'] = strip_tags($new_instance['name']);

	$instance['filter'] = strip_tags($new_instance['filter']);

	$instance['number'] = strip_tags($new_instance['number']);

	$instance['charex'] = strip_tags($new_instance['charex']);

	$instance['chartle'] = strip_tags($new_instance['chartle']);

	$instance['dformat'] = strip_tags($new_instance['dformat']);

	$instance['tformat'] = strip_tags($new_instance['tformat']);

	$instance['cachefeed'] = strip_tags($new_instance['cachefeed']);

	$instance['ltime'] = strip_tags($new_instance['ltime']);

	/* No need to strip tags for drop-down menus */

	// var location & topic is for Google News search

	$instance['location'] = $new_instance['location'];

	$instance['topic'] = $new_instance['topic'];

	$instance['target'] = $new_instance['target'];

	$instance['list'] = $new_instance['list'];

	/* No need to strip tags for checkboxes */

	$instance['lkbtitle'] = $new_instance['lkbtitle'];

	$instance['pubdate'] = $new_instance['pubdate'];

	$instance['pubtime'] = $new_instance['pubtime'];

	$instance['pubauthor'] = $new_instance['pubauthor'];

	$instance['excerpt'] = $new_instance['excerpt'];

	$instance['sort'] = $new_instance['sort'];

    return $instance;

}

 /**

 * Displays the widget settings controls on the widget panel.

 * Make use of the get_field_id() and get_field_name() function

 * when creating your form elements. This handles the confusing stuff.

 */

function form( $instance ) {

	/* Default widget settings. */

	$defaults = array( 'title' => __('The Latest RSS Items', 'RSSjb'), 'location' => '', 'geo' => '', 'gsearch' => '', 'topic' => '', 'name' => __('http://feeds.feedburner.com/StefaniasBlog?format=xml', 'RSSjb'), 'lkbtitle' => 'off', 'filter' => '', 'number' => '5', 'ltime' => '', 'pubdate' => 'off', 'pubtime' => 'off', 'dformat' => '', 'tformat' => '', 'pubauthor' => 'on', 'excerpt' => 'off', 'charex' => '', 'chartle' => '', 'list' => 'ul', 'target' => '_blank', 'sort' => 'false', 'cachefeed' => '3600');

	$instance = wp_parse_args( (array) $instance, $defaults ); 

?>

	<!-- Widget Title: Text Input -->

	<p>

	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Widget Title:', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" /> 

	<!-- Link Title to Feed? Checkbox -->

	<input class="checkbox" type="checkbox" <?php checked( $instance['lkbtitle'], 'on' ); ?> id="<?php echo $this->get_field_id( 'lkbtitle' ); ?>" name="<?php echo $this->get_field_name( 'lkbtitle' ); ?>" title="<?php _e('Tick off if title needs to be linked to the chosen Feed URL', 'RSSjb'); ?>" /> 

	</p>

	<style>

	fieldset {

	border: 1px solid #CCA383;

	padding: 3px;

	}

	</style>

	<fieldset>

	<legend><?php _e('Generic RSS/Atom Feed URL:', 'RSSjb'); ?></legend>

	<!-- Feed URL: Text Input -->

	<p>

	<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" style="width:95%;" />

	</p>

	</fieldset>

	<p align="center">=== OR ===</p>

	<fieldset>

	<legend>Feed URL for Google News</legend>

	<!-- Location: Select Box -->

	<p>

	<label for="<?php echo $this->get_field_id( 'location' ); ?>"><?php _e('Google News Location:', 'RSSjb'); ?></label> 

	<select id="<?php echo $this->get_field_id( 'location' ); ?>" name="<?php echo $this->get_field_name( 'location' ); ?>" class="widefat" style="width:95%;">

	<option value="" <?php if ("" == $instance['location']) echo 'selected';?>> </option> 

	// Locations in news.google.com on 29 Dec 12

	<option value="es_ar" <?php if ("es_ar" == $instance['location']) echo 'selected';?>>Argentina</option> 

	<option value="au" <?php if ("au" == $instance['location']) echo 'selected';?>>Australia</option>

	<option value="nl_be" <?php if ("nl_be" == $instance['location']) echo 'selected';?>>België</option> 

	<option value="fr_be" <?php if ("fr_be" == $instance['location']) echo 'selected';?>>Belgique</option> 

	<option value="en_bw" <?php if ("en_bw" == $instance['location']) echo 'selected';?>>Botswana</option>

	<option value="pt-BR_br" <?php if ("pt-BR_br" == $instance['location']) echo 'selected';?>>Brasil</option> 

	<option value="ca" <?php if ("ca" == $instance['location']) echo 'selected';?>>Canada English</option> 

	<option value="fr_ca" <?php if ("fr_ca" == $instance['location']) echo 'selected';?>>Canada Français</option> 

	<option value="cs_cz" <?php if ("cs_cz" == $instance['location']) echo 'selected';?>>Česká republika</option> 

	<option value="es_cl" <?php if ("es_cl" == $instance['location']) echo 'selected';?>>Chile</option> 

	<option value="es_co" <?php if ("es_co" == $instance['location']) echo 'selected';?>>Colombia</option> 

	<option value="es_cu" <?php if ("es_cu" == $instance['location']) echo 'selected';?>>Cuba</option> 

	<option value="de" <?php if ("de" == $instance['location']) echo 'selected';?>>Deutschland</option> 

	<option value="es" <?php if ("es" == $instance['location']) echo 'selected';?>>España</option> 

	<option value="es_us" <?php if ("es_us" == $instance['location']) echo 'selected';?>>Estados Unidos</option> 

	<option value="en_et" <?php if ("en_et" == $instance['location']) echo 'selected';?>>Ethiopia</option> 

	<option value="fr" <?php if ("fr" == $instance['location']) echo 'selected';?>>France</option> 

	<option value="en_gh" <?php if ("en_gh" == $instance['location']) echo 'selected';?>>Ghana</option> 

	<option value="in" <?php if ("in" == $instance['location']) echo 'selected';?>>India</option> 

	<option value="en_ie" <?php if ("en_ie" == $instance['location']) echo 'selected';?>>Ireland</option> 

	<option value="en_il" <?php if ("en_il" == $instance['location']) echo 'selected';?>>Israel English</option> 

	<option value="it" <?php if ("it" == $instance['location']) echo 'selected';?>>Italia</option> 

	<option value="en_ke" <?php if ("en_ke" == $instance['location']) echo 'selected';?>>Kenya</option> 

	<option value="hu_hu" <?php if ("hu_hu" == $instance['location']) echo 'selected';?>>Magyarország</option> 

	<option value="en_my" <?php if ("en_my" == $instance['location']) echo 'selected';?>>Malaysia</option> 

	<option value="es_mx" <?php if ("es_mx" == $instance['location']) echo 'selected';?>>México</option> 

	<option value="en_na" <?php if ("en_na" == $instance['location']) echo 'selected';?>>Namibia</option> 

	<option value="nl_nl" <?php if ("nl_nl" == $instance['location']) echo 'selected';?>>Nederland</option> 

	<option value="nz" <?php if ("nz" == $instance['location']) echo 'selected';?>>New Zealand</option> 

	<option value="en_ng" <?php if ("en_ng" == $instance['location']) echo 'selected';?>>Nigeria</option> 

	<option value="no_no" <?php if ("no_no" == $instance['location']) echo 'selected';?>>Norge</option> 

	<option value="de_at" <?php if ("de_at" == $instance['location']) echo 'selected';?>>Österreich</option> 

	<option value="en_pk" <?php if ("en_pk" == $instance['location']) echo 'selected';?>>Pakistan</option> 

	<option value="es_pe" <?php if ("es_pe" == $instance['location']) echo 'selected';?>>Perú</option> 

	<option value="en_ph" <?php if ("en_ph" == $instance['location']) echo 'selected';?>>Philippines</option> 

	<option value="pl_pl" <?php if ("pl_pl" == $instance['location']) echo 'selected';?>>Polska</option> 

	<option value="pt-PT_pt" <?php if ("pt-PT_pt" == $instance['location']) echo 'selected';?>>Portugal</option> 

	<option value="de_ch" <?php if ("de_ch" == $instance['location']) echo 'selected';?>>Schweiz</option> 

	<option value="fr_sn" <?php if ("fr_sn" == $instance['location']) echo 'selected';?>>Sénégal</option> 

	<option value="en_sg" <?php if ("en_sg" == $instance['location']) echo 'selected';?>>Singapore</option> 

	<option value="en_za" <?php if ("en_za" == $instance['location']) echo 'selected';?>>South Africa</option> 

	<option value="fr_ch" <?php if ("fr_ch" == $instance['location']) echo 'selected';?>>Suisse</option> 

	<option value="sv_se" <?php if ("sv_se" == $instance['location']) echo 'selected';?>>Sverige</option> 

	<option value="en_tz" <?php if ("en_tz" == $instance['location']) echo 'selected';?>>Tanzania</option> 

	<option value="tr_tr" <?php if ("tr_tr" == $instance['location']) echo 'selected';?>>Türkiye</option> 

	<option value="uk" <?php if ("uk" == $instance['location']) echo 'selected';?>>U.K.</option> 

	<option value="us" <?php if ("us" == $instance['location']) echo 'selected';?>>U.S.</option> 

	<option value="en_ug" <?php if ("en_ug" == $instance['location']) echo 'selected';?>>Uganda</option> 

	<option value="es_ve" <?php if ("es_ve" == $instance['location']) echo 'selected';?>>Venezuela</option> 

	<option value="vi_vn" <?php if ("vi_vn" == $instance['location']) echo 'selected';?>>Việt Nam (Vietnam)</option> 

	<option value="en_zw" <?php if ("en_zw" == $instance['location']) echo 'selected';?>>Zimbabwe</option> 

	<option value="el_gr" <?php if ("el_gr" == $instance['location']) echo 'selected';?>>Ελλάδα (Greece)</option> 

	<option value="ru_ru" <?php if ("ru_ru" == $instance['location']) echo 'selected';?>>Россия (Russia)</option> 

	<option value="sr_rs" <?php if ("sr_rs" == $instance['location']) echo 'selected';?>>Србија (Serbia)</option> 

	<option value="ru_ua" <?php if ("ru_ua" == $instance['location']) echo 'selected';?>>Украина / русский (Ukraine)</option> 

	<option value="uk_ua" <?php if ("uk_ua" == $instance['location']) echo 'selected';?>>Україна / українська (Ukraine)</option> 

	<option value="iw_il" <?php if ("iw_il" == $instance['location']) echo 'selected';?>>ישראל (Israel)</option> 

	<option value="ar_ae" <?php if ("ar_ae" == $instance['location']) echo 'selected';?>>الإمارات (UAE)</option> 

	<option value="ar_sa" <?php if ("ar_sa" == $instance['location']) echo 'selected';?>>السعودية (KSA)</option> 

	<option value="ar_me" <?php if ("ar_me" == $instance['location']) echo 'selected';?>>العالم العربي (Arabic)</option> 

	<option value="ar_lb" <?php if ("ar_lb" == $instance['location']) echo 'selected';?>>لبنان (Lebanon)</option> 

	<option value="ar_eg" <?php if ("ar_eg" == $instance['location']) echo 'selected';?>>مصر (Egypt)</option> 

	<option value="hi_in" <?php if ("hi_in" == $instance['location']) echo 'selected';?>>தமிழ் (India)</option> 

	<option value="ta_in" <?php if ("ta_in" == $instance['location']) echo 'selected';?>>हिन्दी (India)</option> 

	<option value="te_in" <?php if ("te_in" == $instance['location']) echo 'selected';?>>తెలుగు (India)</option> 

	<option value="ml_in" <?php if ("ml_in" == $instance['location']) echo 'selected';?>>മലയാളം (India)</option> 

	<option value="kr" <?php if ("kr" == $instance['location']) echo 'selected';?>>한국 (Korea)</option> 

	<option value="cn" <?php if ("cn" == $instance['location']) echo 'selected';?>>中国版 (China)</option> 

	<option value="tw" <?php if ("tw" == $instance['location']) echo 'selected';?>>台灣版 (Taiwan)</option> 

	<option value="jp" <?php if ("jp" == $instance['location']) echo 'selected';?>>日本 (Japan)</option> 

	<option value="hk" <?php if ("hk" == $instance['location']) echo 'selected';?>>香港版 (Hong Kong)</option>

	// End Locations in news.google.com on 29 Dec 12

	</select>

	<br /><span style="font-size: 0.8em;">Read more about <a target="_blank" href="http://www.stefonthenet.com/2010/02/google-news-localization-codes/">Google's Localization Codes</a>.</span>

	</p>

	<!-- Local (Usa & Can only): Text Input -->

	<p>

	<label for="<?php echo $this->get_field_id( 'geo' ); ?>"><?php _e('Local (Us & Ca in English only):', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'geo' ); ?>" name="<?php echo $this->get_field_name( 'geo' ); ?>" value="<?php echo $instance['geo']; ?>" style="width:95%;" />

	</p><!-- Search Keyword(s): Text Input -->

	<fieldset>

	<legend><?php _e('Filter Results by keyword(s) OR topics (optional)', 'RSSjb'); ?></legend>

	<p>

	<label for="<?php echo $this->get_field_id( 'gsearch' ); ?>"><?php _e('Google News Keyword(s):', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'gsearch' ); ?>" name="<?php echo $this->get_field_name( 'gsearch' ); ?>" value="<?php echo $instance['gsearch']; ?>" style="width:90%;" />

	<br /><span style="font-size: 0.8em;">Learn a few <a target="_blank"  href="http://www.stefonthenet.com/googles-search-operators/">Google Search Tips</a>.</span>	</p>

	<!-- Topic: Select Box -->

	<p> 

	<label for="<?php echo $this->get_field_id( 'topic' ); ?>"><?php _e('Google News Topic:', 'RSSjb'); ?></label>

	<select id="<?php echo $this->get_field_id( 'topic' ); ?>" name="<?php echo $this->get_field_name( 'topic' ); ?>" class="widefat" style="width:90%;">	// Topic options in Google News on 29 Dec 2012

	<option value="" <?php if ('' == $instance['topic']) echo 'selected';?>>Top Stories (default)</option>

	<option value="w" <?php if ('w' == $instance['topic']) echo 'selected';?>>World</option>

	<option value="n" <?php if ('n' == $instance['topic']) echo 'selected';?>>Nation</option>

	<option value="b" <?php if ('b' == $instance['topic']) echo 'selected';?>>Business</option>

	<option value="t" <?php if ('t' == $instance['topic']) echo 'selected';?>>Sci/Tecn</option>

	<option value="tc" <?php if ('tc' == $instance['topic']) echo 'selected';?>>Technology</option>

	<option value="e" <?php if ('e' == $instance['topic']) echo 'selected';?>>Entertainment</option>

	<option value="s" <?php if ('s' == $instance['topic']) echo 'selected';?>>Sports</option>

	<option value="snc" <?php if ('snc' == $instance['topic']) echo 'selected';?>>Science</option>

	<option value="m" <?php if ('m' == $instance['topic']) echo 'selected';?>>Health</option>

	<option value="ir" <?php if ('ir' == $instance['topic']) echo 'selected';?>>Spotlight</option>

	<option value="po" <?php if ('po' == $instance['topic']) echo 'selected';?>>Most Popular</option>

	<option value="h" <?php if ('h' == $instance['topic']) echo 'selected';?>>More Top Stories</option>

	// End of Topic options in Google News on 29 Dec 2012	

	</select>

	<br /><span style="font-size: 0.8em;">Read more about <a target="_blank"  href="http://www.stefonthenet.com/2010/02/google-news-topic-codes/">Google's Topics</a>.</span>

	</p>

	</fieldset>
	</fieldset>

	<!-- Cache Refresh Frequency(sec.): Text Input -->

	<p>

	<label for="<?php echo $this->get_field_id( 'cachefeed' ); ?>"><?php _e('Cache Refresh Frequency (in sec.):', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'cachefeed' ); ?>" name="<?php echo $this->get_field_name( 'cachefeed' ); ?>" value="<?php echo $instance['cachefeed']; ?>" style="width:10%;" />

	</p>

	<!-- Sort by Title: checkbox -->

	<p>

	<label for="<?php echo $this->get_field_id( 'sort' ); ?>"><?php _e('Sort by Title (instead of date/time):', 'RSSjb'); ?></label>

	<input class="checkbox" type="checkbox" <?php checked( $instance['sort'], 'on' ); ?> id="<?php echo $this->get_field_id( 'sort' ); ?>" name="<?php echo $this->get_field_name( 'sort' ); ?>" /> 	

	</p>

	<!-- Text Filter: Text Input -->

	<p>

	<label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e('Keywords Filter:', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" value="<?php echo $instance['filter']; ?>" style="width:95%;" />

	<br /><span style="font-size: 0.8em;">Example: with [foo -bar] items need to include "foo" and not "bar" words. Case insensitive, no wildchars, quotes, booleans or exact phrases are accepted.</span>

 	</p>

	<!-- N. articles to display: Text Input -->

	<p>

	<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Max No. of Items: ', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:10%;" /> to display. If 0 then all items will be displayed.

	</p>

	<!-- Only items published in the last: Text Input -->

	<p>

	<label for="<?php echo $this->get_field_id( 'ltime' ); ?>"><?php _e('Only items published in the last: ', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'ltime' ); ?>" name="<?php echo $this->get_field_name( 'ltime' ); ?>" value="<?php echo $instance['ltime']; ?>" style="width:10%;" /> hours.

	</p>

	<!-- Limit/Truncate title to ... chars: Text Input -->

	<p>

	<label for="<?php echo $this->get_field_id( 'chartle' ); ?>"><?php _e('Limit Item title to ', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'chartle' ); ?>" name="<?php echo $this->get_field_name( 'chartle' ); ?>" value="<?php echo $instance['chartle']; ?>" style="width:10%;" /> chars.

	</p>

	<!-- Show Publication Date? Checkbox -->

	<p>

	<label for="<?php echo $this->get_field_id( 'pubdate' ); ?>"><?php _e('Show item date?', 'RSSjb'); ?></label>

	<input class="checkbox" type="checkbox" <?php checked( $instance['pubdate'], 'on' ); ?> id="<?php echo $this->get_field_id( 'pubdate' ); ?>" name="<?php echo $this->get_field_name( 'pubdate' ); ?>" /> 

	<label for="<?php echo $this->get_field_id( 'dformat' ); ?>"><?php _e(' Date Format ', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'dformat' ); ?>" name="<?php echo $this->get_field_name( 'dformat' ); ?>" value="<?php echo $instance['dformat']; ?>" style="width:20%;" /> chars.

	</p>	<!-- Show Publication Time? Checkbox -->

	<p>

	<label for="<?php echo $this->get_field_id( 'pubtime' ); ?>"><?php _e('Show item time?', 'RSSjb'); ?></label>

	<input class="checkbox" type="checkbox" <?php checked( $instance['pubtime'], 'on' ); ?> id="<?php echo $this->get_field_id( 'pubtime' ); ?>" name="<?php echo $this->get_field_name( 'pubtime' ); ?>" /> 

	<label for="<?php echo $this->get_field_id( 'tformat' ); ?>"><?php _e(' Time Format ', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'tformat' ); ?>" name="<?php echo $this->get_field_name( 'tformat' ); ?>" value="<?php echo $instance['tformat']; ?>" style="width:20%;" /> chars.

	<br /><span style="font-size: 0.8em;">Learn how to customize you <a target="_blank" href="http://codex.wordpress.org/Formatting_Date_and_Time">Date and Time Formats</a>.</span>

	</p>

	<!-- Show Excerpt? Checkbox -->

	<p>

	<label for="<?php echo $this->get_field_id( 'excerpt' ); ?>"><?php _e('Show excerpt?', 'RSSjb'); ?></label>

	<input class="checkbox" type="checkbox" <?php checked( $instance['excerpt'], 'on' ); ?> id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" />

	<label for="<?php echo $this->get_field_id( 'charex' ); ?>"><?php _e(' and limit it to ', 'RSSjb'); ?></label>

	<input id="<?php echo $this->get_field_id( 'charex' ); ?>" name="<?php echo $this->get_field_name( 'charex' ); ?>" value="<?php echo $instance['charex']; ?>" style="width:10%;" /> chars.

	<br /><span style="font-size: 0.8em;">(Warning. the excerpt may contain formatting/images: might not be suitable for sidebars)</span>

         </p>

	<!-- List Type: Select Box -->

	<p>

	<label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php _e('List Type:', 'RSSjb'); ?></label> 

	<select id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>" class="widefat" style="width:95%;">

	<option value="ul" <?php if ('ul' == $instance['list']) echo 'selected';?>>Unordered (or Dotted) List (default)</option>

	<option value="ol" <?php if ('ol' == $instance['list']) echo 'selected';?>>Ordered (or Numbered) List</option>

	</select>

	</p>

	<!-- Target: Select Box -->

	<p>

	<label for="<?php echo $this->get_field_id( 'target' ); ?>"><?php _e('Target:', 'RSSjb'); ?></label> 

	<select id="<?php echo $this->get_field_id( 'target' ); ?>" name="<?php echo $this->get_field_name( 'target' ); ?>" class="widefat" style="width:95%;">

	<option value="_blank" <?php if ('_blank' == $instance['target']) echo 'selected';?>>Open link in a new window (default)</option>

	<option value="_self" <?php if ('_self' == $instance['target']) echo 'selected';?>>Open link in the same window</option>

	</select>

	</p>

	<!-- Show Plugin Homepage? Checkbox -->

	<p>

	<label for="<?php echo $this->get_field_id( 'pubauthor' ); ?>"><?php _e('Show footer [link to this plugin homepage]?', 'RSSjb'); ?></label>

	<input class="checkbox" type="checkbox" <?php checked( $instance['pubauthor'], 'on' ); ?> id="<?php echo $this->get_field_id( 'pubauthor' ); ?>" name="<?php echo $this->get_field_name( 'pubauthor' ); ?>" /> 

	<br /><span style="font-size: 0.9em;">(please, say yes)</span>

	</p>

<?php

	}

}

/**

 * The following code is for the shortcode

 * @since 0.2

 */

/**

 * Add shortcode to wordpress

 */

// add_shortcode('RSSjb', 'RSSjb_funct');

add_shortcode('RSSjb', array('RSSjbClass', 'RSSjb_funct'));

/**

 * Shortcode Class and Function

 */

class RSSjbClass {

	function RSSjb_funct($atts) {

     	extract(shortcode_atts(array(

		// attributes for Google News

		"gsearch" => '',

		"topic" => '',

   		"location" => '',

		"geo" => '',

		"feed" => '', // required

    	"filter" => '',

     	"num" => '5',

     	"ltime" => '',

     	"list" => 'ul',

     	"target" => '_blank',

     	"pubdate" => 'false',

     	"pubtime" => 'false',

		"dformat" => get_option('date_format'),

		"tformat" => get_option('time_format'),

   		"pubauthor" => 'true',

  		"excerpt" => 'false',

   		"charex" => '',

   		"chartle" => '',

   		"title" => '',

   		"link" => 'false',

		"sort" => 'false',

   		"cachefeed" => '3600'

	), $atts));

	// if a location is entered, a Google News feed will be displayed instead

	if ($location) {

		$gsearch = urlencode($gsearch);

		$feed = "http://news.google.com/news/section?cf=all&ned=" . $location;

     		if ($geo) $feed .= "&geo=$geo";

     		if ($gsearch) $feed .= "&q=$gsearch";

     		if ($topic) $feed .= "&topic=$topic";

	}

	// a feed URL needs to be present either entered by the user or created to display Google News

	if ($feed) {

		/* Display title and/or link-to-feed from given attributes */

     		$tle = '';

     		if ( filter_var($link, FILTER_VALIDATE_BOOLEAN) ) { 

			$tle = "<a target='$target' href='$feed'><img src='/wp-content/plugins/rss-just-better/rss-cube.gif' width='25px' height='25px' alt=' [feed link] '></a> "; 

		}

      		if ( $title ) { 

     			$tle .= "<b>$title</b>"; 

    		} 

		// call the function to read and display the feed items list

		return $tle . rssjb_List($feed, $filter, $num, $ltime, $list, $target, $pubdate, $pubtime, $dformat, $tformat, $pubauthor, $excerpt, $charex, $chartle, $sort, $cachefeed);

	} else {

		return '<br>RSS or Atom Feed URL not provided. This shortcode does require the attribute feed or location (if Google News).<br /> Ex: <code>[RSSjb feed = "http://your-rss-or-atom-feed-URL-here"]</code>.'; 

	}

	}

}

/*

From URL: http://www.rssboard.org/rss-2-0-1#hrelementsOfLtitemgt

(The latest specification of an RSS feed (2.0.1). Backcompatible with versions 2.0, 0.92, 0.91)

A channel may contain any number of <item>s. An item may represent a "story" -- much like a story in a newspaper or magazine; if so its description is a synopsis of the story, and the link points to the full story. An item may also be complete in itself, if so, the description contains the text (entity-encoded HTML is allowed), and the link and title may be omitted. All elements of an item are optional, however at least one of title or description must be present. 

*/

function rssjb_List($feed, $filter, $num, $ltime, $list, $target, $pubdate, $pubtime, $dformat, $tformat, $pubauthor, $excerpt, $charex, $chartle, $sort, $cachefeed) {

	require_once (ABSPATH . WPINC . '/class-feed.php');

	// decoding needed when you use a shortcode as URLs are encoded by the shortcode

	$feed = html_entity_decode($feed);  

	// fetch feed using simplepie. Returns a standard simplepie object

	// $rss = fetch_feed($feed);

	// cannot use fetch_feed simplepie function as I want to be able to reorder list of items

	$rss = new SimplePie();

	$rss->set_feed_url($feed);

	// if $sort, disable (default) ordering by reverse chronological order. the order will normally be (direct) chronological

	if ($sort == "c" || $sort == "C") $rss->enable_order_by_date(false);

	$rss->set_cache_class('WP_Feed_Cache');

	$rss->set_file_class('WP_SimplePie_File');

	$rss->set_cache_duration(apply_filters('wp_feed_cache_transient_lifetime', $cachefeed, $feed));

	

	// do_action_ref_array( 'wp_feed_options', array( &$feed, $feed ) );

	$rss->init();

	$rss->handle_content_type();

	// end of replacing fetch_feed simplepie 

	// Checks that the object is created correctly 

	if (is_wp_error( $rss )) 

		$flist = "<br>Ooops...this plugin cannot read your feed at this time. The error message is: <b><i>" . $rss->get_error_message() . "</i></b>.<br>It might be temp. not available or - if you use it for the first time - you might have mistyped the url or a misformatted RSS or Atom feed is present.<br>Please, check if your browser can read it instead by clicking here: <a target='_blank' href='$feed'>$feed</a>.<br> if not or if it only reads it at times or randomly, contact the feed URL provider. If your feed is always read by your browser, contact me at stefonthenet AT gmail DOT com with your RSS/Atom feed URL and the shortcode or widget params you want to use it with; <br>";

	else {

   		// Figure out how many total items there are, but limit it to the given param $num. 

		$nitems = $rss->get_item_quantity($num);

		if ($nitems == 0) $flist = "<li>No items found in feed URL: $feed. You requested $num items.</li>";

		else {

		// Build an array of all the items, starting with element 0 (first element).

		$rss_items = $rss->get_items(0, $nitems); 

		// Sort by title the $sort as boolean is to keep compatibility with version 1.1

		if (filter_var($sort, FILTER_VALIDATE_BOOLEAN) || $sort == "t" || $sort == "T") asort($rss_items); 

		$flist = "<div class='rssjb'><$list>\n";

		foreach ( $rss_items as $item ) {

			// get title from feed

			$title = esc_html( $item->get_title() );

			$exclude = false;

			$include = 0;

			$includetest = false;

			if ($filter) {

				$aword = explode( ' ' , $filter );

				// count occurences of each $ainclude element in $title

				foreach ( $aword as $word ) {

					if ( substr($word,0,1) == "-" ) {

						// this word needs to be excluded as it starts with a -

						stripos( $title, substr($word,1) )===false?$exclude=false:$exclude=true;

						// if it finds the word that excludes the item, then breaks the loop

						if ($exclude) break;

					} else {

						$includetest=true;

					// this word needs to be included

						if ( stripos( $title, $word )!==false) $include++;

						// if it finds the word that includes the item, then set the nclusion variable

						// i cannot break the look as i might still find exclusion words 

					}

 				}

			} // if ($filter)

			// either (at least one occurrence of searched words is found AND no excluded words is found)

			if ( !$exclude AND ($includetest===false or $include>0) ) {

			if ( $ltime==='' OR ($ltime>'' AND (time() < strtotime("+$ltime minutes", strtotime($item->get_date()))) ) ) 		{

				// get description from feed

				$desc = esc_html( $item->get_description() );

				$flist .= '<li> ';

				// get title from feed

				$title = cdataize( $item->get_title() );

				// get description from feed

				$desc = cdataize( $item->get_description() );

				// sanitize title and description

				$title = sanitxt( $title );

				$desc = sanitxt( $desc );

				if ($chartle>=1) $title=substr($title, 0, $chartle).'...';

				if ( $title || $desc ) {

					if ( $item->get_permalink() ) {

						$permalink = esc_url( $item->get_permalink() );

					   	$motext = isset( $desc ) ? $desc : $title;

					   	// writes the link

						$flist .= "<a class='title' target='$target' href='$permalink'";

						if (!filter_var($excerpt, FILTER_VALIDATE_BOOLEAN)) $flist .= " title='".substr($motext,0,400)."...'";

					$flist .= ">";

					}

					// writes the title

					$flist .= isset( $title ) ? $title : $motext;

					// end the link (anchor tag)

					if ( $permalink ) $flist .= '</a>'; 

					if ( $item->get_date() ) {

						$datim = strtotime( $item->get_local_date() );

						if ( filter_var($pubdate, FILTER_VALIDATE_BOOLEAN) ) {

							if (empty($dformat)) $dformat = get_option('date_format');

							$flist .= ' - <span class="datetime">' . date_i18n( $dformat, $datim ) . '</span>';

						}

              				if ( filter_var($pubtime, FILTER_VALIDATE_BOOLEAN) ) {

							if (empty($tformat)) $tformat = get_option('time_format');

							$flist .= ' at <span class="datetime">' . date_i18n ( $tformat, $datim ) . '</span>'; 

						}

					}

					if ($desc && filter_var($excerpt, FILTER_VALIDATE_BOOLEAN)) {

						$flist .= '<br/><span class="excerpt">';

						if ( $charex >= 1 && $charex<strlen($desc) ) { 

							$flist .= substr($motext, 0, $charex) . " [<a target='$target' href='$permalink'>...</a>]";

						} else {

							$flist .= $motext;

						}	

						$flist .= '</span>';

					}

				} else {

					$flist .= '<li>No standard <item> in file.';

				}

				$flist .= '</li>';

			} // if ($ltime...)

			} // if ($exclude...)

		} // foreach

		$flist .= "</$list></div>\n";

		} // else of if ($nitems == 0)

	} // else of if (is_wp_error( $rss ))

 	// if pubauthor has been selected

	if ( filter_var($pubauthor, FILTER_VALIDATE_BOOLEAN) ) {

		$flist .= '<p style="text-align:center; font-size: 0.8em">powered by <a target="_blank" href="http://www.stefonthenet.com/rss-just-better-plugin-for-wordpress/">RSS Just Better</a> 1.4 plugin</p>';

	}

	return $flist;

}

function cdataize($content) {

	$content = preg_replace('/\&\#([0-9]+);/','<![CDATA[&#\\1;]]>',$content);

	$content = preg_replace('/\&\#x([0-9A-F]+);/i','<![CDATA[&#\\1;]]>',$content);

return($content);

}

function convert_smart_quotes($string) { 

    $search = array(chr(145), 

                    chr(146), 

		    chr(0x60), // grave accent

		    chr(0xB4), // acute accent

		    chr(0x91),  

		    chr(0x92),  

		    chr(0x93),  

		    chr(0x94),  

                    chr(147), 

                    chr(148), 

                    chr(151)); 

    $replace = array("'", 

                     "'", 

                     "'", 

                     "'", 

                     '"', 

                     '"', 

                     '"', 

                     '"', 

                     '"', 

                     '-'); 

return str_replace($search, $replace, $string,$countN); 

} 

function sanitxt($sanitxt) {

	// add a space between closing and opening tags

	$sanitxt = str_replace('><','> <',$sanitxt);

	// remove HTML tags

	$sanitxt = strip_tags($sanitxt);

	// remove multiple spaces

	$sanitxt = preg_replace('/\s+/', ' ', $sanitxt);

	// convert smart quotes into normal

	convert_smart_quotes($sanitxt);

	// replace quotes and ampersands with HTML chars (recommended)

	// and encode string to UTF-8. UTF-8 is default from PHP 5.4.0 only

	$sanitxt = htmlspecialchars($sanitxt,ENT_QUOTES,"UTF-8",FALSE);

return($sanitxt);

}

?>