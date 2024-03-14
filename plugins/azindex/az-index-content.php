<?php
/*  Copyright 2008  Michael J. Walker  (email : azindex@englishmike.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Called when [az-index] is encountered in a blog page.  This is the 
 * main entry point for displaying the index.
 *
 * @param $content [az-index]
 * @return string to substitute with (i.e. the index page)
 */
function az_insert_index($params) {
    extract(shortcode_atts(array('id' => 0, 'escape' => 'false'), $params));
    if ($escape != 'true') {
        $content = az_get_index_content($id);
    } else {
        // We just want to display the shortcode itself, not a substitution.
        $content = '[az-index id="'.$id.'"]';
    }
    return $content;
}

/**
 * Build up the index page from the definition of the index and 
 * the contents of the included posts.
 *
 * @return index content to be displayed or an error message if the index doesn't exist
 */
function az_get_index_content($indexid) {
    az_trace('fn:get_index_content');
    if ($indexid != 0) {
        $index = new az_request();
        $count = $index->set_vars_from_table($indexid);
        if ($count > 0) {
            global $post;
            load_plugin_textdomain('azindex', false, 'azindex');
            $currentpage = az_is_set($index->options, 'multipage') ? intval($_GET['pgno']) : 0;
        	if ($currentpage > 0) {
                $currentpage--;
            }
        	$cache = az_cache_get($index, $post->ID, $currentpage);
        	$content = az_format_index($index, $cache->items, $cache->alphalinks, $cache->pageno, $cache->pagecount, az_current_url());
        } else if ($count !== false) {
            $content = az_error_message('invalid_index', $indexid); 
        }
    } else {
        $content = az_error_message('invalid_shortcode');
    }
    return $content;
}

/**  
 * Format the index page to be displayed. 
 *
 * @param unknown_type $items items in the index
 * @return the formatted index page to be displayed
 */
function az_format_index($index, $items, $indexchars, $currentpage, $pagecount, $pagelink) {
    //az_trace('fn:format_content: options = '.$index->options);
    
    // Add carriage returns to the start of each entry to make it easier to read the source HTML.
    $cr = chr(10);
    $idindex = $index->id;
    $options = $index->options;
    // Query the option settings for the index.
    $striped = az_is_set($options, 'custom-css-striping') && $index->subhead != 'none';
    $grouped = az_is_set($options, 'group-subhead');
    $alphalinks = az_is_set($options, 'alpha-links');
    $alphahead = az_is_set($options, 'alpha-head');
    $alphaheadpage = az_is_set($options, 'alpha-head-page');
    $alphaheadcol = az_is_set($options, 'alpha-head-col');
    $addspaces = az_is_set($options, 'add-spaces');
    $multipage = az_is_set($options, 'multipage');
    $comment = "<!-- Index page generated using the WordPress AZIndex plugin version ".AZ_PLUGIN_VERSION."by English Mike (http://azindex.englishmike.net) -->";
    $anchor = 'azindex-'.$idindex;
    $indexfilter = 'azindex_display_index';
    $itemfilter = 'azindex_display_item';
    $cr = chr(10);
    
    // Fetch the correct stylesheet for the currently set options. 
    $stylesheet = az_get_stylesheet($options, $index->cols, $grouped ? $index->cssgroup : $index->csssingle);

    $output = '';
    if (!empty($items)) {

        // Set the default separator if the user hasn't set one.
        if (empty($index->headsep)) {
            $index->headsep = ' - ';
        }
        
        // If this is a multipage index then set up the count of items on a page and the page links.
        if ($multipage && $index->perpage > 0) {
            $pagelinks = az_format_pagelinks($idindex, $currentpage, $pagecount, $pagelink, $options);
        } else {
            $index->perpage = count($items);
        }
        
        // If this is a multipage index and we're not on the first page
        // then the array contains one extra item at the beginning which
        // is the last item on the previous page - this is to ensure we
        // format and link the first item on this page correctly. 
        $first = ($currentpage > 0) ? 1 : 0;
        $last = $first + $index->perpage;
        
        // Calculate the number of items in each column.
        // Note: no account is taken for the size of the items, so
        // the columns will probably be uneven to some degree.
        $incol = ceil($index->perpage / $index->cols);
        
        // Fetch the previous heading if were not starting from the beginning.
        if ($first > 0) { 
            $prevhead = $items[$first - 1]['sort-head'];
            $previnitial = $items[$first - 1]['initial'];
        }
        for ($col = 0; $col < $index->cols; $col++) {                        
            $output .= '<div class="azindex"><ul>';
            $odd = false;
            $curitem = -1;
            for ($i = 0; $i < $incol; $i++) {
                $spacer = '';
                $heading = '';
                $charlink = '';

                // If we've reached the end of the items then stop now.
                if ($curitem == $last - 1) {
                    break;
                }
                
                // Fetch the next item.
                $curitem = $first + $col * $incol + $i;
                $item = $items[$curitem];
                if (!empty($item) && $curitem < $last) {
                    // Check to see if next entry starts with a different letter.
                    $initial = $item['initial'];
                    if (strcmp($initial, $previnitial)) {
                        // If we need an alphalink, add it to the list.
                        if ($alphalinks) {
                            $charlink = ' id="char_'.bin2hex($initial).'"';
                        }
                        if ($alphahead) {
                            $heading = '<li><h2><a href="#'.$anchor.'" title="'.__('Return to the top', 'azindex').'"'.($alphalinks ? $charlink : '').'>'.$initial.'</a></h2></li>'.$cr;
                            $charlink = '';
                        }
                        // If add-spaces set, then add the spacer.
                        if ($addspaces && $curitem > $first && $i > 0) {
                            $spacer = '<li class="spacer"></li>';
                        }
                    } else if ($alphahead) { 
                        if ($alphaheadcol && $i == 0 || $alphaheadpage && $curitem == $first) {
                            $heading = '<li><h2>'.$initial.'<span class="azcont">&nbsp;&nbsp;('.__('continued', 'azindex').')</span></h2></li>'.$cr;
                        }
                    }

                    // Output the index item.
                    
                    // Check to see if the items with the same heading are grouped together.
                    // Note: if at the top of a column, always output the heading.
                    $link = get_permalink($item['id']);
                    if ($grouped) {
                        if ($item['sort-head'] != $prevhead || $i == 0) {
                            $cont = '';
                            if (!empty($prevhead)) {
                                $output .= '</li>'.$cr.$spacer.$heading;
                                if ($striped && $i > 0) {
                                    $odd = !$odd;
                                }
                            } else {
                                $output .= $heading;
                            }
                            if ($item['sort-head'] == $prevhead) {
                                $cont = '<span class="azcont"> ('.__('continued', 'azindex').')</span>';
                            }
                            $output .= '<li'.$charlink.($odd ? ' class="azalt"' : '').'>'.'<span class="head">'.$item['head'].$cont.'</span>'.$cr;
                        }
                        $output .= '<span class="subhead"><a href="'.$link.'">'.$item['subhead'].'</a></span>'.$cr;
                        if (!empty($item['desc'])) {
                            $output .= '<span class="desc">'.$item['desc'].'</span>'.$cr;
                        }
                    } else {
                        $output .= $spacer.$heading.'<li'.$charlink.($odd ? ' class="azalt"' : '').'>';
                        
                        // Call any display item filters
                        if (has_filter($itemfilter)) {
                            $item = apply_filters($itemfilter, $item, $idindex);
                        }
                        
                        if (!empty($item['output'])) {
                            // If the filter passed us back some HTML, then use it.
                            $output .= $item['output'];
                        } else {
                            // Otherwise generate the output as usual.
                            $output .= '<a href="'.$link.'"><span class="head">';
                            $output .= $item['head'].'</span>';
                            if (!empty($item['subhead'])) {
                                $output .= $index->headsep.'<span class="subhead">'.$item['subhead'].'</span></a>';
                            } else {
                                $output .= '</a>';
                            }
                            if (!empty($item['desc'])) {
                                $output .= '<span class="desc">'.$item['desc'].'</span>';
                            }
                        }
                        $output .= '</li>'.$cr;
                        if ($striped) {
                            $odd = !$odd;
                        }
                    }
                    $prevhead = $item['sort-head'];
                    $previnitial = $item['initial'];
                }
            }
            if ($grouped) {
                // Add in "(more)" to indicate that there are more items for this
                // heading on the next page or column.
                $next = $first + $col * $incol + $i;
                if ($next < count($items) && $item['sort-head'] == $items[$next]['sort-head']) {
                    $output .= '<span class="subhead"><span class="azcont">('.__('more...', 'azindex').')</span></span>';
                }
                $output .= '</li>'.$cr;
                if ($striped) {
                    $odd = !$odd;
                }
            }
            $output .= '</ul></div>';
        }
        
        // Reset the block formatting.
        $output .= '<div style="clear:both;"></div>';

        // Add the alphabetical links we have been collecting.
        $linkspacer = '<div class="azlinkspacer"></div>';
        if ($alphalinks) {
            $output = $linkspacer.az_format_alphalinks($idindex, $indexchars, $currentpage, $pagecount, $pagelink, $index->customlinks, $options).$output;
        }

        // Add the page links to the index page.
        if ($multipage && $index->perpage > 0) {
            $linkabove = az_is_set($options, 'multipage-links-above');
            $linkbelow = az_is_set($options, 'multipage-links-below');
            if ($linkabove || !($linkabove || $linkbelow)) {
                $output = $linkspacer.$pagelinks.$output;
            }
            if ($linkbelow) {
                $output .= $pagelinks.$linkspacer;
            }
        }
        
        // Finally, piece together the whole index.
        $output = '<div id="'.$anchor.'">'.$stylesheet.$output.'</div>';

        // And call any display index filters
        if (has_filter($indexfilter)) {
            $output = apply_filters($indexfilter, $output, $idindex, $items, $first, $index->perpage);
        }
        
        $output = $comment.$output;
    }
    return $output;
}

/**
 * Add links to the top of the index page(s), if required.  Note that the order in which 
 * the links are added is the order of the charactes in the constant AZ_INDEXCHARS.  Any
 * character links which are not in this constant will be added to the front of the list.
 *
 * @param $indexchars characters in the index
 * @param $currentpage the current page of the index
 * @param $pagecount the number of pages in the index
 * @param $pagelink the URL of the main index page
 * @param $options specified options
 * @return the output containing the index. 
 */
function az_format_alphalinks($idindex, $indexchars, $currentpage, $pagecount, $pagelink, $customlinks, $options) {
    
    $is_mb = az_is_set($options, 'nls') && function_exists('mb_strpos');
    $tworows = az_is_set($options, 'alpha-links-two-rows');
    $chars =  az_is_set($options, 'custom-links') ? $customlinks : AZ_INDEXCHARS;
    $gap = '&nbsp;'.($tworows ? '&nbsp;' : '');
    $paged = az_is_set($options, 'multipage');
    $append = az_is_set($options, 'non-alpha-end');
    $pgno = strpos($pagelink, '?') !== false ? '&pgno=' : '?pgno=';
    $add_unused = az_is_set($options, 'alpha-links-unused') && az_strlen($chars, $is_mb) > 0;
    $title = __('Go to the letter', 'azindex');
    $alphafilter = 'azindex_alpha_links';
    $cr = chr(10);

    // First build an array of all the link information, if necessary.
    if ($add_unused || has_filter($alphafilter)) {
        
        // Set up an array with the included characters as keys.
        // Note: the insertion order is the order maintained throughout,
        // and is the correct sorted alphabetical order. 
        for ($i = 0; $i < az_strlen($chars, $is_mb); $i++) {
            $char = az_substr($chars, $i, 1, $is_mb);
            $links[az_substr($chars, $i, 1, $is_mb)] = false;
        }
        
        // Saved the originally specified number of links
        $link_count = count($links);
        
        // Now add the link items to the array for the characters that exist.
        // Note that all links are added, even those which are not in the
        // character list supplied by the user (these are tacked on to the
        // end of the array). 
        foreach ($indexchars as $item) {
            $links[$item['char']] = $item;
        }
    }
    
    // Check to see if the unused characters are to be output along with the alphalinks.
    if ($add_unused) {

        // Calculate where to split the index, if necessary.
        $half = ($link_count - (count($links) - $link_count) * ($append ? -1 : 1)) / 2;
        
        // Now generate the output from the data in the array.        
        $count = 0;
        foreach ($links as $char => $link) {
            $html = '';
            if ($tworows && $count >= $half) {
                $output .= '</div><div class="azlinks">';
                $half *= 3;
            } else if ($count > 0 && $count != $link_count && $count < count($links)) {
                $html = $gap;
            }
            if ($link !== false) {  
                $pg = (!$paged || $link['page'] == $currentpage) ? '' : $pagelink.$pgno.($link['page'] + 1);
                $html .= '<span class="azlink"><a href="'.$pg.'#char_'.bin2hex($char).'" title="'.$title.' '.$char.'">'.$char.'</a></span>';
            } else {
                $html .= '<span class="azlink azdisabled">'.$char.'</span>';
            }
            if ($count < $link_count) {
                $output .= $html;
            } else {
                $nonalpha .= $html;
            }
            $count++;
        }

        // Add the non-alphanumeric characters to the front or end of the index links.
        if (!empty($nonalpha)) {
            // If we have some non-alpha links, then put them in the right place.
            if ($append) {
                $output .= $gap.trim($nonalpha);
            } else {
                $output = $nonalpha.$gap.trim($output);
            }
        }
    } else {
        // Output the alphalinks, on two rows if specified.
        $half = count($indexchars) / 2;
        for ($i = 0; $i < count($indexchars); $i++) {
            if ($tworows && $i >= $half) {
                $output .= '</div><div class="azlinks">';
                $half *= 3;
            } else if ($i > 0) {
                $output .= $gap;
            }
            $pg = (!$paged || $indexchars[$i]['page'] == $currentpage) ? '' : $pagelink.$pgno.($indexchars[$i]['page'] + 1);
            $output .= '<span class="azlink"><a href="'.$pg.'#char_'.bin2hex($indexchars[$i]['char']).'" title="'.$title.' '.$indexchars[$i]['char'].'">'.$indexchars[$i]['char'].'</a></span>';
        }
    }
    $output = '<div class="azlinks">'.$output.'</div>'.$cr;
    if (has_filter($alphafilter)) {
        $output = apply_filters($alphafilter, $output, $idindex, $links, $pagelink, $currentpage + 1);
    }
    return $output;
}

/**
 * Generate the page links for a multipage index.
 *
 * @param $currentpage index of current page
 * @param $pagecount total number of pages
 * @param $pagelink the index page URL
 * @param $options the current options
 * @return the formatted page links
 */
function az_format_pagelinks($idindex, $currentpage, $pagecount, $pagelink, $options) {
    $pagefilter = 'azindex_page_links';
    $maxpages = AZ_MAXPAGELINKS;
    $maxside = $maxpages / 2;
    $pgno = strpos($pagelink, '?') !== false ? '&pgno=' : '?pgno=';
    $pgup = min($currentpage + $maxpages + 1, $pagecount);
    $pgdn = max(1, $currentpage - $maxpages + 1);
    $title = __("Go to page", 'azindex');
    $anchor = '#azindex-'.$idindex;
    $cr = chr(10);
        
    if ($pagecount > $maxpages) {
        $output .= '<span class="azlinknav '.($currentpage > 0 ? '"><a href="'.$pagelink.$pgno.$pgdn.$anchor.'" title="'.sprintf(__('Back %d pages', 'azindex'), $maxpages).'">&lt;&lt;</a>' : 'aznavdisabled">&lt;&lt;').'</span>&nbsp;';
    } 
    $output .= '<span class="azlinknav '.($currentpage <= 0 ? 'aznavdisabled">&lt;' : '"><a href="'.$pagelink.$pgno.$currentpage.$anchor.'" title="'.__('Previous page', 'azindex').'">&lt;</a>').'</span>&nbsp;';
    $start = 0;
    $end = $pagecount;
    if ($pagecount > $maxpages && $currentpage > $maxside) {
        $output .= '..&nbsp;';
        $start = min($currentpage - $maxside, $pagecount - $maxpages - 1);
    }
    $end = min($start + $maxpages + 1, $pagecount);
    for ($i = $start; $i < $end; $i++) {
        $output .= '<span class="azlink '.($i == $currentpage ? 'azdisabled">'.($i + 1) : '"><a href="'.$pagelink.$pgno.($i + 1).$anchor.'" title="'.$title.' '.($i + 1).'">'.($i + 1).'</a>').'</span>&nbsp;';
    }
    if ($pagecount > $maxpages && $currentpage < $pagecount - $maxside - 1) {
        $output .= '..&nbsp;';
    }
    $output .= '<span class="azlinknav '.($currentpage >= $pagecount - 1 ? 'aznavdisabled">&gt;' : '"><a href="'.$pagelink.$pgno.($currentpage + 2).$anchor.'" title="'.__('Next page', 'azindex').'">&gt;</a>').'</span>';
    if ($pagecount > $maxpages) {
        $output .= '&nbsp;<span class="azlinknav '.($currentpage < $pagecount - 1 ? '"><a href="'.$pagelink.$pgno.$pgup.$anchor.'" title="'.sprintf(__('Forward %d pages', 'azindex'), $maxpages).'">&gt;&gt;</a>' : 'aznavdisabled">&gt;&gt;').'</span>';
    }
        
    $output = '<div class="azpagelinks">'.$output.'</div>'.$cr;
    if (has_filter($pagefilter)) {
        $output = apply_filters($pagefilter, $output, $idindex, $pagelink, $currentpage + 1, $pagecount, $anchor);
    }
    return $output;
}

/**
 * Get the stylesheet needed for the current style of index. 
 *
 * @param $options specified options
 * @param $cols number of columns in the index.  If cols is zero, then
 *              we are fetching the stylesheet for the admin panel 
 * @return output containing the stylesheet
 */
function az_get_stylesheet($options, $cols, $customcss) {

    // Add tidy carriage returns to the end of each line.
    $cr = chr(10);

    // Don't want the "style" tag if we're going to display it in the admin panel.
    if ($cols > 0) {
        // Adjust the width a little downwards to account for IE rounding errors.
        $output = '<style type="text/css">'.$cr;
        $output .= '.azindex {width:'.(intval(100 / $cols) - 0.1).'%;float:left;}';    
    }

    // If custom css is required, set it.
    if (az_is_set($options, 'custom-css')) {
       $content = $customcss; 
    }
    
    if (empty($content)) {
        if (az_is_set($options, 'group-subhead')) {
            $content = '.azindex .head {font-weight:bold}'.$cr
                      .'.azindex .subhead {clear:left; float:left; padding-left:10px;}'.$cr
                      .'.azindex .desc {clear:left; float:left; font-size:80%; padding-left:20px;}'.$cr
                      .'.azindex .head .azcont {font-size:90%;font-style:italic;}'.$cr
                      .'.azindex .subhead .azcont {font-size:90%;font-style:italic;}'.$cr;
                      
        } else {
            $content = '.azindex .head {}'.$cr
                      .'.azindex .subhead {}'.$cr
                      .'.azindex .desc {float:left; font-size:80%; padding-left:10px;}'.$cr;
        }
    
        $content .= '.azindex {padding:20px 0 20px 0}'.$cr
                   .'.azindex h2 { padding-top:0;margin-top:0}'.$cr
                   .'.azindex h2 .azcont {font-size:50%;font-style:italic;}'.$cr
                   .'.azindex ul {list-style:none; padding:0 5px 0 5px; margin:0;}'.$cr
                   .'.azindex ul li {clear:both; padding-top:5px;}'.$cr
                   .'.azindex ul li.azalt {float:left; width:100%; background-color:lightgray;}'.$cr
                   .'.azindex .spacer {height:20px;}'.$cr
                   .'.azlinks {text-align:center;}'.$cr
                   .'.azlinkspacer {height:20px;}'.$cr
                   .'.azpagelinks {text-align:center;}'.$cr;
    }

    $output .= $content;
    
    if ($cols > 0) {
        $output .='</style>';
    }
    return $output;
}

/**
 * Test to see if a specific option is set for the index.
 *
 * @param $options all the options set for the current index
 * @param $option the option to test for
 * @return true if the option is set for this index
 */
function az_is_set($options, $option) {
    return strpos($options, $option) !== false;
}

function az_get_ignorechars($index, $is_multibyte) {
	$chars = az_is_set($index->options, 'ignore-chars') ? az_htmlspecialchars_decode($index->ignorechars, ENT_QUOTES) : null;
    return $chars;
}

/**
 * Get the current page URL from the HTTP headers.
 */
function az_current_url() {
    $url = "http://" . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $pos = strpos($url, "pgno=") - 1;
    // If there is already a pgno parameter in the url, remove it.
    if ($pos > 0) {
        $end = strpos($url, "&", $pos + 1);
        if (!$end) {
            // The pgno is at the end of the url, just truncate it. 
            $end = strlen($url); 
        } else {
            // The pgno is not the last parameter, so remove the pgno and the following & concatenator.
            $pos++;
            $end++;
        }
        $url = substr_replace($url, "", $pos, $end - $pos);
    }
    return $url;
}

function az_error_message($id, $param = '', $blink = true) {
    $output = '<div style="color:red">'; 
    switch ($id) {
        case 'invalid_index': 
            $output .= '<br/>AZINDEX ERROR: There is no index with the id of "'.$param.'" available.';
            break; 
        case 'invalid_shortcode': 
            $output .= '<br/>AZINDEX ERROR: Invalid az-index short-code found. A non-zero <em>id</em> parameter must be specified.';
            break; 
    }
    if ($blink) { 
        $output .= '<br/>Please notify blog/site administration of the problem.';
    }
    $output .= '<br/>(Message from the AZIndex plugin)<br/></div>';
    return $output;
}

/**
 * Unicode aware replacement for ltrim.
 *
 * Trimming can corrupt a Unicode string by replacing single bytes from a
 * multi-byte sequence. Used in a default manner, ltrim is UTF-8 safe, but
 * with the optional charlist variable specified it can corrupt strings.
 *
 * @see ltrim
 * @param string $str  string to trim
 * @param string $charlist  list of characters to trim
 * @return string  trimmed string
 */
function az_ltrim($str, $charlist='') {
    if (strlen($charlist) == 0) {
        $str = ltrim($str);
    } else {
        $str = utf8_char_replace($str);
        $charlist = preg_quote($charlist,'#');
        $str = preg_replace('#^['.$charlist.']+#u','',$str);
    }
    return $str;
}

/**
 * Unicode aware replacement for rtrim.
 *
 * @see rtrim
 * @param string $str  string to trim
 * @param string $charlist  list of characters to trim
 * @return string  trimmed string
 */
function az_rtrim($str, $charlist='') {
    if (strlen($charlist) == 0) {
        $str = rtrim($str);
    } else {
        $str = utf8_char_replace($str);
        $charlist = preg_quote($charlist,'#');
        $str = preg_replace('#['.$charlist.']+$#u','',$str);
    }
    return $str;
}

/**
 * Unicode aware replacement for trim.
 *
 * @see trim
 * @param string $str  string to trim
 * @param string $charlist  list of characters to trim
 * @return string  trimmed string
 */
function az_trim($str, $charlist='')
{
    if (strlen($charlist)==0) {
        return trim($str);
    } else {
        return az_ltrim(az_rtrim($str, $charlist), $charlist);
    }
} 

function az_strpos($string, $chr, $is_multibyte) {
    return $is_multibyte ? mb_strpos($string, $chr, 0, "UTF-8") : strpos($string, $chr);
}

function az_strlen($string, $is_multibyte) {
    return $is_multibyte ? mb_strlen($string, "UTF-8") : strlen($string);
}

function az_substr($string, $pos, $len, $is_multibyte) {
    return $is_multibyte ? mb_substr($string, $pos, $len, "UTF-8") : substr($string, $pos, $len);
}

function az_strcoll($s1, $s2, $is_multibyte) {
    return $is_multibyte ? strcoll($s1, $s2) : strcasecmp($s1, $s2);
}

function utf8_char_replace($str) {

    $utf8 = array(chr(0xe2).chr(0x80).chr(0x98), 
                  chr(0xe2).chr(0x80).chr(0x99), 
                  chr(0xe2).chr(0x80).chr(0x9c), 
                  chr(0xe2).chr(0x80).chr(0x9d),
                  chr(0xe2).chr(0x80).chr(0x9a),
                  chr(0xe2).chr(0x80).chr(0x9e),
                  chr(0xe2).chr(0x80).chr(0x93),
                  chr(0xe2).chr(0x80).chr(0x94),
                  chr(0xe2).chr(0x80).chr(0xa6));
                   
    $base = array("'", "'", '"', '"', "'", '"', '-', '-', '...');              
    $str = str_replace($utf8, $base, $str);
    return $str;
}
?>