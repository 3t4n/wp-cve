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
add_action('publish_page', 'az_cache_post_changed');
add_action('publish_post', 'az_cache_post_changed');
add_action('save_post', 'az_cache_post_changed');
add_action('delete_post', 'az_pending_delete');
add_action('deleted_post', 'az_cache_post_changed');
add_action('wp_update_post', 'az_cache_post_changed');
add_action('wp_insert_post', 'az_cache_post_changed');
add_action('check_ajax_referer', 'az_cache_check_custom');

//add_action('wp_ajax_addmeta', 'az_cache_check');
//add_action('edit_post', 'az_cache_check');
//add_action('init', 'az_cache_check');
 
/**
 * Creates a new cached object for the specified index.
 *
 * @param az_request $index the current index
 * @param int $pageid current page id displaying index (can be a post)
 * @param int $pageno current page number of index being displayed
 * @return az_index_cache cached information for current index
 */
function az_cache_get($index, $pageid, $pageno) {
	return new az_index_cache($index, $pageid, $pageno);
}

/**
 * Hook into the delete_post action so that we can get the
 * details of the post to weed out the drafts and autosaves.
 *
 * @param int $postid
 */
function az_pending_delete($postid) {
    global $az_deleted_post_type;
    $post = get_post($postid);
    $az_deleted_post_type[$postid] = $post->post_type;
    //az_trace("az_pending_delete : ".(empty($post) ? "null" : "$post"));
}

/**
 * Main entry point for processing changed posts and pages.  Hooks into 
 * functions that modify posts and pages in some way call here so that
 * the posts can be added to the dirty list of all the indexes.  Note
 * that we have to filter out changes to revisions by checking the post_type/
 *
 * @param int $postid id of post changed 
 */
function az_cache_post_changed($postid) {
    global $az_deleted_post_type;
    if (current_filter() == 'deleted_post') {
        $post_type = $az_deleted_post_type[$postid];
    } else {
        $post = get_post($postid);
        $post_type = $post->post_type;
    }
    //az_trace("HOOK CALLED az_cache_post_changed : $post_type : ".current_filter());
    if ($post_type == 'post' || $post_type == 'page') {
        //az_trace("-----------------------------------------------");
        //az_trace("az_cache_post_changed:".current_filter()." - postid = $postid");
        //az_trace("az_cache_post_changed: - post_type = $post_type");
        az_add_dirty_post($postid);
    }
}

/**
 * There is no easy way to capture changes to custom fields that are done via AJAX calls.
 * This callback is called *before* any changes are made, so while we can log that a
 * change has been made (which we do) there is no way to find out what the change is
 * and whether it affects the index or not.  There is no consistency in the way the 
 * postid is passed on the AJAX call, which is the reason for the convoluted code.
 *
 * @param object $action - not used
 */
function az_cache_check_custom($action) {
	global $wpdb;
  	//az_trace("az_cache_check:".current_filter()." - action = $action");
    $action = $_REQUEST['action'];
    if ($action == 'add-meta') {
        $postid = $_REQUEST['post_id'];
        if (empty($postid)) {
        	$meta = $_REQUEST['meta'];
            $idmeta = current(array_keys($meta));
        }
    } else if ($action == 'delete-meta') {
        $idmeta = $_REQUEST['id'];
    }
    
    if (!empty($idmeta)) {
        $postid = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_id = '$idmeta'");
    }
    
    if (!empty($postid)) {
        $post = get_post($postid);
        if ($post->post_type == 'post' || $post->post_type == 'page') {
            //az_trace("-----------------------------------------------");
            //az_trace("az_cache_check_custom: custom field modified - postid: $postid");
            //az_trace("az_cache_check_custom: - post_type = $post->post_type");
            az_add_dirty_post($postid);
        }
    }
}

/**
 * Fetch the list of dirty posts -- and only unserialize them if
 * they haven't been unserialized already.
 *
 * @return array of dirty posts.
 */
function az_get_dirty_posts() {
    //az_trace('az_get_dirty_posts');
    $dirtyposts = get_option("az_cache_dirty");
    if (is_serialized($dirtyposts)) {
        $dirtyposts = unserialize($dirtyposts); 
    }
    return $dirtyposts;
}

function az_set_dirty_posts($dirtyposts) {
	update_option("az_cache_dirty", serialize($dirtyposts));
	//az_trace("az_set_dirty_posts: saving dirtyposts:");
}

function az_add_dirty_post($postid) {
	az_acquire_cache_mutex();
    $dirtyposts = az_get_dirty_posts();
    // Only process the dirty post if its the same post as before if
    // a quarter of a second or more has elapse, otherwise we will
    // process the same changes multiple time. 
    $elapsed = az_microtime_diff($dirtyposts[0][1]);
    $elapsed = ($postid == $dirtyposts[0][0]) ? $elapsed : 1;
    //az_trace("elapsed time : ".$elapsed);
    if ($elapsed > 0.25) {
        //az_trace("******************************************************");
        $indexes = az_get_indexes("*");
        // Add the dirty post to all the indexes since we have no idea which
        // posts are displayed in which indexes.
        foreach ($indexes as $index) {
            // Add the post to the dirty list if necessary.
            if (!(is_array($dirtyposts[$index->idindex]) && in_array(intval($postid), $dirtyposts[$index->idindex]))
 	                                                     && !az_is_set($index->options, "disable-cache")) {
 	            //az_trace("az_add_dirty_post: adding postid $postid to the dirty list of $index->idindex");
                $dirtyposts[$index->idindex][] = intval($postid);
 	        } 
            // The blog may be using an HTML cache (like WP-Super-Cache), so we 
            // have to check immediately if we need to invalidate the HTML cache
            // since we won't be able to when the cached HTML of the index itself is accessed.
            $dirtyposts = az_process_dirty_post($index, $postid, $dirtyposts);
            //az_trace("dirty: id = ".$index->idindex.": ".(is_array($dirtyposts[$index->idindex]) ? implode(",", $dirtyposts[$index->idindex]) : 'empty'));
	    }
        //az_dump_dirty($dirtyposts);
    }
	// Save the postid and timestamp for checking next time we hook a function.
    $dirtyposts[0][0] = $postid;
    $dirtyposts[0][1] = microtime();
    az_set_dirty_posts($dirtyposts);
    // Release the semaphore now that we are done.
    az_release_cache_mutex();
}

/**
 * Cannot use microtime(true) because it is not available
 * in PHP4. 
 *
 * @param unknown_type $start
 * @param unknown_type $end
 * @return unknown
 */
function az_microtime_diff($start, $end = false) {
    if (!$end) {
        $end= microtime();
    }
    $start_sec = explode(" ", $start);
    $start = floatval($start_sec[0]) + floatval($start_sec[1]);
    $end_sec = explode(" ", $end);
    $end = floatval($end_sec[0]) + floatval($end_sec[1]);
    $diff = $end - $start;
    //az_trace("microtime_diff : ".$diff);
    return $diff;
}


/**
 * For the specified index, find out if the post invalidates the 
 * cache.  If it does, then call the HTML cache function to invalidate
 * the HTML cache for the page(s) the index is being displayed on. 
 *
 * @param db_index $dbindex index settings loaded from the database
 * @param int $postid id of the post that has just been modified in some way
 */
function az_process_dirty_post($index, $postid, $dirtyposts) {
    //az_trace("az_process_dirty_post: idindex = $index->idindex - postid = $postid");
    $req = new az_request();
    $req->set_vars_from_index($index);
    $cache = new az_index_cache($req, 0, 0, false);

    // First check to see if post is in index.  If it is, we assume that the
    // change might affect the index page even if it might not. 
    //az_trace("az_process_dirty_post : postid = $postid");
    //az_trace("az_process_dirty_post : cache = ".(is_array($cache->itemcache) ? implode(",", $cache->itemcache) : 'empty'));
    $dirty = az_is_set($index->options, 'disable-cache') || (is_array($cache->itemcache['id']) && in_array($postid, $cache->itemcache['id']));
    //az_trace("az_process_dirty_post: is post in index? ".($dirty ? "yes" : "no"));
    if (!$dirty) {
        // The post was not in the index, but could be a new one
        // to be added to the index.  In this case we need to run
        // the usual query against the postid to see if it should be
        // added to the index.
        $posts = $cache->query_index_items($req, array($postid), 0, true);
        $dirty = is_array($posts) && count($posts) > 0;
        //az_trace("az_process_dirty_post: is post to be added to index? ".($dirty ? "yes" : "no"));
    }
    
    // If it looks as though the index is dirty, then tell the HTML
    // cache to remove the index page.
    if ($dirty) {
        $dirtyposts = az_check_html_cache($index->idindex, $dirtyposts);
    }
    return $dirtyposts;
}

/**
 * This function must be called if a process outside the dirty mutex semaphore
 * wants to flush the cache -- for example, two calls are made from the admin
 * pages, since they do not have the semaphore lock.
 *
 * @param int $idindex id of index whose pages are to be flushed
 */
function az_flush_html_cache($idindex) {
    az_acquire_cache_mutex();
    $dirtyposts = az_get_dirty_posts();
    az_check_html_cache($idindex, $dirtyposts);
    unset($dirtyposts[$idindex]);
    az_set_dirty_posts($dirtyposts);
    az_release_cache_mutex();
} 

function az_check_html_cache($idindex, $dirtyposts) {
    //az_trace("az_check_html_cache: $idindex");
    $dirtypages = $dirtyposts['pages'][$idindex];
    if (is_array($dirtypages)) {
        for ($i = 0; $i < count($dirtypages); $i++) {
            //az_trace("az_check_html_cache: removing page id ".$dirtypages[$i]." from HTML cache.");
            if (function_exists('wp_cache_post_change')) {
                wp_cache_post_change($dirtypages[$i]);
            }
        }
        unset($dirtyposts['pages'][$idindex]);
    }
    return $dirtyposts;
}

function az_reset_dirty_posts($dirtyposts, $idindex) {
	$dirtyposts[$idindex] = array();
    az_set_dirty_posts($dirtyposts);
}

class az_index_cache {
	var $index;            // Current index
	var $pageno;           // Current page number
    var $pagecount;        // Number of pages in the index
	var $items;            // Array of items;
    var $itemcache;        // Cache of item ids stored in database
	var $alphalinks;       // Array of alphabetical links
	var $ignorechars;      // Characters to ignore in a sort
	var $is_multipage;     // True if a multipage index
	var $is_multibyte;     // True if the index contains multibyte strings
    var $has_alphalinks;   // True if alphalinks being used
    var $cache_disabled;   // True if caching is disabled for this index
    var $convertchars;     // True if the index items must be converted to single-byte characters (for sorting)
    var $charmapper;       // If required, used to map multi-byte characters to the equivalent base character
    var $languagetable;    // Name of the language table to use when building the index grouping and alpha links
    var $locale;           // Name of the locale to use during the sorting of the index 
        
    /**
     * Constructor for the az_index_cache class.  This function either rebuilds the
     * cached index from cache data stored in the database or, if there is no stored
     * cache, or changes to the blog make the cache out of date, then the index cache
     * is built from scratch.
     *
     * @param az_request $index index to be displayed, include all the current settings
     * @param int $pageid id of page or post displaying the index
     * @param int $pageno the index of the page in the index to be displayed
     * @param boolean $buildcache if true (default) then fully build the cache 
     * @return az_index_cache the index cache
     */
    function az_index_cache($index, $pageid, $pageno, $buildcache = true) {
        //$time_start = microtime(true);
		$this->cache_disabled = az_is_set($index->options, 'disable-cache');
        $this->has_alphalinks = az_is_set($index->options, 'alpha-links');
        $this->is_multipage = az_is_set($index->options, 'multipage') && $index->perpage > 0;
        
        $this->index = $index;
		$this->pageno = $pageno;
        $this->itemcache = unserialize($index->itemcache);
        
        // Set up NLS options.
        $this->is_multibyte = az_is_set($index->options, 'nls') && function_exists('mb_strpos');
        
        if ($this->is_multibyte) {
            if (az_is_set($index->options, 'nls-equiv') && !empty($index->nlsequiv)) {
                $this->languagetable = $index->nlsequiv;
            } else {
                // Set this to be the default mapping table -- works in most instances.
                $this->languagetable = AZ_DEFAULT_LANGUAGE_TABLE;
            }
            // Only specify the locale if the index option is turned on and a locale is specified
            if (az_is_set($index->options, 'nls-locale') && !empty($index->nlslocale)) {
                $this->locale = $index->nlslocale;
            }
        }
        
        $this->ignorechars = az_get_ignorechars($index, $this->is_multibyte);
        $this->convertchars = false;

        if ($buildcache) {
            // This is the main path for building or rebuilding the cache
            $this->add_current_page_to_cache($index->id, $pageid);
        
            if ($this->cache_disabled || $this->is_dirty()) {
       		    $this->build_index();
                $this->write_cache();
            } else {
                $this->build_index_from_cache();
            }
        }
        //$time_end = microtime(true);
        //az_println("Cache execution in: "+(($time_end - $time_start)*1000)."ms");
	}

	/**
	 * Build the index from scratch.  All items in the index need to be loaded from the database
	 * and then sorted before the correct page of the index and its contents can be display. This
	 * can be quite slow for very large indexes.
	 */
    function build_index() {
    	$this->itemcache = 0;
        $this->items = $this->get_index_items($this->index);
        if ($this->is_multipage) {
            $this->pagecount = ceil(count($this->items) / $this->index->perpage);
            $this->pageno = ($this->pageno < 0) ? 0 : ($this->pageno >= $this->pagecount ? $this->pagecount - 1 : $this->pageno);
        }
        if ($this->has_alphalinks) {
            $this->alphalinks = $this->collect_links($this->items, $this->index->perpage);
        }
        $this->items = az_slice_array($this->items, $this->pageno, $this->pagecount, $this->index->perpage, $this->is_multipage);
	}

	/**
	 * Rebuild the index to be displayed from the cache.  This can be much faster than
	 * doing if from scratch because the index does not have to be sorted, and so only the
	 * items on the current page of the index need to be queried from the database.
	 */
	function build_index_from_cache() {
        if ($this->has_alphalinks) {
            $this->alphalinks = unserialize($this->index->linkcache);
        }
        if ($this->is_multipage) {
            $this->pagecount = ceil(count($this->itemcache['id']) / $this->index->perpage);
            $this->pageno = ($this->pageno < 0) ? 0 : ($this->pageno >= $this->pagecount ? $this->pagecount - 1 : $this->pageno);
        }
        $ids = az_slice_array($this->itemcache['id'], $this->pageno, $this->pagecount, $this->index->perpage, $this->is_multipage);
        if (!empty($this->itemcache['key'])) {
            //az_println("There are keys in the cache");
            $keys = az_slice_array($this->itemcache['key'], $this->pageno, $this->pagecount, $this->index->perpage, $this->is_multipage);
        }
        $this->items = $this->get_index_items($this->index, $ids, $keys);
	}	
	
    function write_cache() {
    	global $wpdb;
        
    	$id = $this->index->id;
    	
        if (!$this->cache_disabled) {
            $itemcache = serialize($this->itemcache);
            $linkcache = serialize($this->alphalinks);
        }
    	
        $query = "UPDATE ".AZ_TABLE." SET itemcache = '$itemcache', linkcache = '$linkcache' WHERE idindex = $id";
        $rc = $wpdb->query($query);
        //az_println("fn:write_cache : query = ".$query."<br/> rc = ".$rc);
    }

    /**
     * Save the page/post id of the page the index is being shown on.  This is done
     * so that we can invalidate the page cached in WP-Super-Cache if it becomes dirty.
     *
     * @param int $idindex current index id
     * @param int $pageid page id the current index is being displayed on
     */
    function add_current_page_to_cache($idindex, $pageid) {
        //az_trace("add_current_page_to_cache: indexid = $idindex; pageid = $pageid");
        if ($pageid > 0) {
            az_acquire_cache_mutex();
            $dirtyposts = az_get_dirty_posts();
            if (!(is_array($dirtyposts['pages'][$idindex]) && in_array(intval($pageid), $dirtyposts['pages'][$idindex]))) {
                 $dirtyposts['pages'][$idindex][] = $pageid;
                 az_set_dirty_posts($dirtyposts);
                 //az_trace("   added to dirtyposts: ".implode(",", $dirtyposts['pages'][$idindex]));
            }
            az_release_cache_mutex();
        }
    }

    /**
     * Test to see if the index cache is dirty (i.e. out of date).  If there is no cache,
     * or any of the dirty items causes a change to the content and/or order of the index
     * then the index cache is dirty and needs to be updated.
     *
     * @return boolean true if the index cache is dirty and needs to be recreated
     */
    function is_dirty() {
        $dirty = false;
        $idindex = $this->index->id;
        az_acquire_cache_mutex();
    	$dirtyposts = az_get_dirty_posts($idindex);
    	if (empty($this->itemcache)) {
            // If there is no cache for the index then, by default, the index is dirty.
    	    $dirty = true;
            az_reset_dirty_posts($dirtyposts, $idindex);
    	} else if (!empty($dirtyposts)) {
    	    // If there is a cache, then check to see if the items in the dirty list invalidate it
            $dirty = $this->process_dirty_items($this->index, $this->itemcache, $dirtyposts[$idindex]);
            az_reset_dirty_posts($dirtyposts, $idindex);
    	}
        //az_dump_dirty(az_get_dirty_posts());
    	az_release_cache_mutex();
        //az_trace("is_dirty: cache: ".(!empty($this->itemcache) ? implode(",", $this->itemcache) : "empty"));
        //az_trace("is_dirty: dirty: ".(!empty($dirtyposts) ? implode(",", $dirtyposts[$idindex]) : "empty"));
        //az_trace("is_dirty: cache is ".($dirty ? "dirty" : "clean"));
        return $dirty;
	}

	/**
	 * This function tests to see if an item in the dirty list has changed in such a way 
	 * that invalidates the order and/or size of the index.  If the item is already in the
	 * index, then we put it and the previous and next items in the index (in their current
	 * order) and execute the index's sort.  If the resulting order is different, then we
	 * know that the cache is now invalid.  This function also works for the cases where
	 * an item has been deleted or removed from the index, or where a new item needs to be
	 * added. 
	 *
	 * @param $index index being tested
	 * @param $indexitems current ordered array of cached items
	 * @param $dirtyitems array of dirty items to be checked out
	 * @return boolean true if the index cache is found to be dirty
	 */
	function process_dirty_items($index, $indexitems, $dirtyitems) {
        //az_trace("process dirty items: dirty items = ".implode(",", $dirtyitems));
        $dirty = false;
		foreach ($dirtyitems as $postid) {
            $position = array_keys($indexitems['id'], $postid);
            if (empty($position)) {
                //az_trace("process_dirty_items : empty array");
                $position[] = false;
            }
            //az_trace("   process dirty items: ids in index = ".count($indexitems['id'])." : ".implode(",", $indexitems['id']));
            //az_trace("   process dirty items: positions = ".count($position)." : ".implode(",", $position));
            foreach ($position as $pos) {
                //az_trace("   process_dirty_items : processing next item:".($pos === false ? "false" : $pos));
                unset($testhit);
                // Fetch the previous postid in the index (if not at the start)
                if ($pos > 0) {
                    $testhit[] = $indexitems['id'][$pos - 1];
                    if ($indexitems['key'] != 0) {
                        $testkey[] = $indexitems['key'][$pos - 1]; 
                    }
                }
                $testhit[] = $postid;
                if (pos !== false && $indexitems['key'] != 0) {
                     $testkey[] = $indexitems['key'][$pos]; 
                }
                // Fetch the next postid in the index (if not at the end)                              
                if ($pos !== false && $pos < count($indexitems['id']) - 1) { 
                    $testhit[] = $indexitems['id'][$pos + 1];
                    if ($indexitems['key'] != 0) {
                        $testkey[] = $indexitems['key'][$pos + 1]; 
                    }
                }
                // Now put them through the usual retrieval and sort.
                $items = $this->get_index_items($index, $testhit, $testkey, true, true);
                $result = $this->get_item_ids($items);
                // Now check to see if the sort order is the same as before or different.
                $diff = array_diff_assoc($testhit, $result);
            
                //az_trace("     test: ".implode(",", $testhit));
                //az_trace("   result: ".implode(",", $result));
                //az_trace("     diff: ".implode(",", $diff));
                //az_trace("    test1: ".((count($diff) > 0 && $pos !== false) ? "true" : "false"));
                //az_trace("    test2: ".((count($diff) == 0 && $pos === false) ? "true" : "false"));
                
                // If there is a difference, the index cache is now invalid.
                if ((count($diff) > 0 && $pos !== false) || (count($diff) == 0 && $pos === false)) {
            	    $dirty = true;
                	//az_trace("   the result is that the cache is dirty");
                  	break 2;  // Break out of both loops.
                }
            }
            // No difference found, reset and go to the next dirty item.
            unset($testhit);
		}
        return $dirty;
	}
	
    /**
     * Get the items to be added to the index and sort them in the specified order.
     *
     * @param $index the parameters specificed for the index
     * @return items to be included in the index
     */
    function get_index_items($index, $ids = 0, $keys = 0, $revalidate = false, $revalidatesort = false) {
        //az_println('fn:get_index_items: pageid: '.$post->ID);
        $this->convertchars = AZ_OS_WIN && $this->is_multibyte && (empty($ids) || $revalidatesort);

        $posts = $this->query_index_items($index, $ids, $keys, $revalidate);
        //az_println("fn:get_index_items : query = ".$query."<br/> rc = ".$posts." - count = ".count($posts));

        // Build an array of items from the query results.
        $items = array();
        $hasfilter = has_filter('azindex_item');
        
        foreach ($posts as $row) {
            $item = $this->get_post_index_info($index, $row, $hasfilter);
            if ($item != null) {
                $items[] = $item;
            }
        }
        
        // Sort the items into alphabetical order, as specified.
        if ($items && (empty($ids) || $revalidatesort)) {
            // Both globals only used in the compare function.
            global $az_nonalphaend, $az_multibyte;
            $az_multibyte = $this->is_multibyte;
            
            // Determine where to put the non-alpha starting entries by setting a signed variable.
            $az_nonalphaend = az_is_set($index->options, 'non-alpha-end') ? -1 : 1;
            
            // Sort the index using the specified comparison function.
            $comparefn = (az_is_set($index->options, 'custom-sort') && trim($index->customsort) != '') ? $index->customsort : 'az_compare';
            
            // If NLS support is turned on, then set the appropriate locale.
            if ($this->is_multibyte) {
                if (!empty($this->locale)) {
                    $defaultlocale = setlocale(LC_COLLATE, null);            
                    setlocale(LC_COLLATE, $this->locale);
                } else {
                    // This seems to improve ths chances of collating working
                    // properly in the default case.
                    setlocale(LC_COLLATE, setlocale(LC_COLLATE, null));
                }
            }
            usort($items, $comparefn);
            
            // Reset the locale to the default, if necessary.
            if (!empty($defaultlocale)) {
                setlocale(LC_COLLATE, $defaultlocale);
            }
            
            if (!$this->cache_disabled && !$revalidate) {
                // Add the ids of the sorted items to the item cache
                unset($this->itemcache);
                //az_println("caching the index: ".$index->head);
                if ($index->head == 'tags' || $index->head == 'cats') {
                    $keys = true;
                    //az_println("putting keys into the cached index : ");
                }
                for ($i = 0; $i < count($items); $i++) {
                    $this->itemcache['id'][] = $items[$i]['id'];
                    if ($keys) {
                        $this->itemcache['key'][] = $items[$i]['key'];
                    }
                }
            }
        }
        return $items;    
    }

    function get_item_ids($items) {
        $ids = array();
        if (is_array($items)) {
            for ($i = 0; $i < count($items); $i++) {
                $ids[] = $items[$i]['id'];
            }
        }
        return $ids;
    }
    
    /**
     * Query the items to be added to the index from the blog's database.
     *
     * @param $index the parameters specificed for the index
     * @return items to be included in the index
     */
    function query_index_items($index, $idlist = 0, $keys = 0, $revalidate = false) {
        global $wpdb;
        //az_trace('fn:query_index_items: index id: '.$index->id);

        $fields = 'ID, post_title, post_excerpt, post_author';
        $notags = false; 
        $nocats = false;
        // If we're querying categories or tags we need to include
        // the term_id field from the term_taxonomy table.
        if ($index->head == 'cats') {
            $fields .= ', tax2.term_id';
            $nocats = true;
        } else if ($index->head == 'tags') {
            $fields .= ', tax1.term_id';
            $notags = true;
        } 
        
        $query = "SELECT DISTINCT $fields FROM $wpdb->posts";
        $selection = "'post'";
        if (az_is_set($index->options, 'include-pages')) {
            if (az_is_set($index->options, 'include-pages-exclude-posts')) {
                $selection = "'page'";
            } else {
                $selection .= ",'page'";
            }
        }
        $where = "post_status = 'publish' AND post_type IN ($selection)";
        
        // We just want to validate whether the specified post(s) belong in the index.
        if ($revalidate && !empty($idlist)) {
            $ids = implode(",", $idlist);
            $where .= " AND ID IN (".$ids.")";
        }
        if (!$revalidate && !empty($idlist)) {
            $ids = implode(",", $idlist);
            if ($nocats || $notags) {
                $terms = new az_terms($index->catids, $index->tagids, az_is_set($index->options, 'child-cats'));
            }
            if (!$index->head == 'cats') {
                $where .= " AND tax2.taxonomy = 'category'";
            } else if ($index->head == 'tags') {
                $where .= " AND tax1.taxonomy = 'post_tag'";
            }
            $where = " WHERE ID IN (".$ids.") AND $where ORDER BY FIELD(ID, $ids)";
            //az_println("query_index_items - fast query for cached items");
        } else {
            // Process the terms in the index settings.
            $terms = new az_terms($index->catids, $index->tagids, az_is_set($index->options, 'child-cats'));
            
            // Process the included and excluded categories.
            if (!empty($terms->excats)) {
                $excats .= " AND $wpdb->posts.ID NOT IN (SELECT DISTINCT object_id FROM $wpdb->term_relationships" 
                          ." INNER JOIN $wpdb->term_taxonomy AS tax3 ON ($wpdb->term_relationships.term_taxonomy_id = tax3.term_taxonomy_id"
                          ." AND tax3.taxonomy = 'category' AND tax3.term_id IN($terms->excats)))";
            }
            if (!empty($terms->incats)) {
                $query .= " INNER JOIN $wpdb->term_relationships AS rel2 ON ($wpdb->posts.ID = rel2.object_id";
                if (!empty($terms->excats)) {
                    $query .= $excats;
                }
                $query .= ") INNER JOIN $wpdb->term_taxonomy AS tax2 ON (rel2.term_taxonomy_id = tax2.term_taxonomy_id"
                         ." AND tax2.taxonomy = 'category' AND tax2.term_id IN ($terms->incats))";
                $nocats = false;
            } else if (!empty($terms->excats)) {
                $query .= " INNER JOIN $wpdb->term_relationships AS rel2 ON ($wpdb->posts.ID = rel2.object_id";
                $query .= $excats.")";
            }   

            // Process the included and excluded tags.
            if (!empty($terms->extags)) {
                $extags .= " AND $wpdb->posts.ID NOT IN (SELECT DISTINCT object_id FROM $wpdb->term_relationships" 
                         ." INNER JOIN $wpdb->term_taxonomy AS tax4 ON ($wpdb->term_relationships.term_taxonomy_id = tax4.term_taxonomy_id"
                         ." AND tax4.taxonomy = 'post_tag' AND tax4.term_id IN($terms->extags)))";
            }
                
            if (!empty($terms->intags)) {
                $query .= " INNER JOIN $wpdb->term_relationships AS rel1 ON ($wpdb->posts.ID = rel1.object_id";
                if (!empty($terms->extags)) {
                    $query .= $extags;
                }
                $query .= ") INNER JOIN $wpdb->term_taxonomy AS tax1 ON (rel1.term_taxonomy_id = tax1.term_taxonomy_id"
                                              ." AND tax1.taxonomy = 'post_tag' AND tax1.term_id IN ($terms->intags))";
                $notags = false;
            } else if (!empty($terms->extags)) {
                $query .= " INNER JOIN $wpdb->term_relationships AS rel1 ON ($wpdb->posts.ID = rel1.object_id";
                $query .= $extags.")";
            }
            $where = " WHERE $where";
        }
        
        // If were sorting on tags or categories and we didn't specify any tags
        // or categories to be included in the sort then we need to do these 
        // joins to get the term_id field included in the results.
        if ($nocats) {
            $query .= " INNER JOIN $wpdb->term_relationships AS rel4 ON ($wpdb->posts.ID = rel4.object_id)"
                     ." INNER JOIN $wpdb->term_taxonomy AS tax2 ON (rel4.term_taxonomy_id = tax2.term_taxonomy_id"
                     ." AND tax2.taxonomy = 'category')";
        } else if ($notags) {
            $query .= " INNER JOIN $wpdb->term_relationships AS rel3 ON ($wpdb->posts.ID = rel3.object_id)"
                     ." INNER JOIN $wpdb->term_taxonomy AS tax1 ON (rel3.term_taxonomy_id = tax1.term_taxonomy_id"
                     ." AND tax1.taxonomy = 'post_tag')";
        }
        $posts = $wpdb->get_results($query.$where);
        //az_trace("fn:get_index_items : query = ".$query.$where."<br/> rc = ".$posts." - count = ".count($posts));
        
        // If this is an index where multiple items are allowed
        // then we need to do some post-processing of the query
        // to get the items in the correct order.
        if (!empty($keys)) {
            $posts = $this->process_key_results($posts, $idlist, $keys);
        }

        // Now filter out the remaining posts that should not be in the list.
        if (empty($idlist) && !empty($index->headkeyids)) {
            $posts = $this->az_filter_posts($posts, $index->headkeyids, $index->head);
        }
        return $posts;    
    }

    /**
     * If sorting by tags/terms, filter out those that are not
     * selected to be in the index.
     *
     * @param array $posts array of posts
     * @param string $termids comma separated list of terms
     * @param string $type type of terms -- cats/tags
     */
    function az_filter_posts($posts, $termids, $type) {
        // Split the tag/cat ids into two lists.
        if ($type == 'tags') {
            $terms = new az_terms(null, $termids, false);
            $exterm = $terms->intags;  // Note, exludes returned in intags (not a bug!)
        } else if ($type == 'cats') {
            $terms = new az_terms($termids, null, az_is_set($index->options, 'child-cats'));
            $exterm = $terms->incats;  // Note, exludes returned in incats (not a bug!)
        }
//      // Remove all posts that don't have tag/cat in the include list.
//      if (!empty($interm)) {
//          $interm = explode(',', $interm);
//          for ($i = count($posts) - 1; $i >= 0; $i--) {
//              if (!in_array($posts[$i]->term_id, $interm)) {
//                  unset($posts[$i]);
//              }
//          }
//      }
        // Remove all posts that do have tag/cat in the exclude list
        if (!empty($exterm)) {
            $exterm = explode(',', $exterm);
            for ($i = count($posts) - 1; $i >= 0; $i--) {
                if (in_array($posts[$i]->term_id, $exterm)) {
                    unset($posts[$i]);
                }
            }
        }
        return $posts;        
    }
    
    /**
     * Clean up the results array if there are keys present.  The items need 
     * to be reordered and then truncated to remove excess results from the
     * query.
     *
     * @param array $posts array posts from query
     * @param array $ids ids from cache
     * @param array $keys keys from cache
     * @return sorted and truncated array
     */
    function process_key_results($posts, $ids, $keys) {

        //az_println("process_key_results");
        for ($i = 0; $i < count($ids); $i++) {
            //az_println($ids[$i].' : '.$posts[$i]->ID." : ".$posts[$i]->term_id." : ".$posts[$i]->post_title);
            if ($posts[$i]->ID != $ids[$i]) {
                for ($j = $i; $posts[$j]->ID != $ids[$i] && $j < count($posts); $j++);
                $temp = $posts[$i];
                $posts[$i] = $posts[$j];
                $posts[$j] = $temp; 
            }
            $posts[$i]->term_id = $keys[$i];
        }
        $count = count($posts);
        while ($i < $count) {
            unset($posts[$i++]);
        }
        return $posts;
    }
    
    function get_post_index_info($index, $row, $hasfilter) {
        $item = null;
        $head = ltrim($this->get_post_item($row->ID, $row->post_title, $row->post_excerpt, 
                                          $row->post_author, $row->term_id, $index->head, $index->headkey));        
        if (!empty($head)) {

            // Remove any characters we want to ignore during the search.
            $sorthead = empty($this->ignorechars) ? $head : trim(az_ltrim($head, $this->ignorechars));

            $subhead = $this->get_post_item($row->ID, $row->post_title, $row->post_excerpt, $row->post_author, $row->term_id, $index->subhead, $index->subheadkey);
            $desc = $this->get_post_item($row->ID, $row->post_title, $row->post_excerpt, $row->post_author, $row->term_id, $index->desc, $index->desckey);
            $sortsubhead = empty($this->ignorechars) ? $subhead : ltrim(az_ltrim($subhead, $this->ignorechars));
            $sortdesc = empty($this->ignorechars) ? $desc : ltrim(az_ltrim($desc, $this->ignorechars));

            // If we have a multi-entry index (indexed off tags, categories, or
            // custom fields then we need to store a second key to distinguish
            // between the post entries.
            if ($index->head == 'cats' || $index->head == 'tags') {
                $key = $row->term_id;
            }
            
            // Don't add item to the index if there is no heading.
            $item = array('id' => $row->ID,
                          // 'initial' => 0,  // Set after the filter is called.
                          'head' => $head,
                          'subhead' => $subhead,
                          'desc' => $desc,
                          'sort-head' => $sorthead,
                          'sort-subhead' => $sortsubhead,
                          'sort-desc' => $sortdesc,
                          'key' => $key
                         );
                         
            if ($hasfilter) {
                $item = apply_filters('azindex_item', $item, $index->id);
            }
            // Set the fields the filter is not allowed to change, again.
            $item['id'] = $row->ID;
            $item['key'] = $key;
            // Fetch the equivalent initial for the entry, (i.e. unaccented characters)
            $item['initial'] = $this->get_base_initial($item['sort-head']);
            
            // If we are running on windows then we have to deconvert before sorting. 
            if ($this->convertchars) {
                $item['sort-head'] = utf8_decode($item['sort-head']);
                $item['sort-subhead'] = utf8_decode($item['sort-subhead']);
                $item['sort-desc'] = utf8_decode($item['sort-desc']);
            }
        }
        return $item;
    }

    function get_base_initial($title) {
        if ($this->is_multibyte) {
            $char = mb_substr($title, 0, 1, "UTF-8");
            // Check the length of the character in bytes.
            if (strlen($char) > 1) {
                // If it's a multibyte character then obtain the 
                // base unaccented character, if there is one.
                if (empty($this->charmapper)) {
                    $this->charmapper = az_get_collation_mapper($this->languagetable);
                }
                $char = az_map_char($char, $this->charmapper);
            } else {
                $char = strtoupper($title[0]);
            }
        } else if (function_exists("mb_substr")) {
            $char = mb_strtoupper(mb_substr($title, 0, 1, "UTF-8"));
        } else {
            $char = strtoupper($title[0]);
        }
        return $char;
    }
    
    /**
     * Get the item for a post specified in the index as the heading,
     * subheading, or description. 
     *
     * @param $type type of item to retrieve
     * @param $key item key, if the item is a custom field
     * @return item's value
     */
    function get_post_item($postid, $title, $excerpt, $author, $term_id, $type, $key = false) {
        switch ($type) {
            case 'title':
                $item = $title;
                break; 
            case 'excerpt':
                $item = $excerpt;
                break; 
            case 'author':
                $item = get_author_name($author);
                break;
            case 'cats':
                $item = get_term_field('name', $term_id, 'category', 'raw');
                break;
            case 'tags':
                $item = get_term_field('name', $term_id, 'post_tag', 'raw');
                break;     
            case 'custom':
                // Only the first custom field found with key is used.
                $item = get_post_custom_values($key, $postid);
                $item = $item[0];
                break;
        }   
        return $item;
    }

    /**
     * Collect the alphabetical links for the items in the index. Links are
     * created for the first item to begin with each letter.
     *
     * @param $items the items in the index
     * @param $perpage number of items per page
     * @return an array characters and what page of the index they are on 
    */
    function collect_links($items, $perpage) {
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            if (!empty($item)) {
                $char = $item['initial'];
                // Now we should be byte compatible with prevhead if we're going to match.
                if (strcmp($char, $prevchar)) {
                    $page = $this->is_multipage ? intval($i / $perpage) : 0;
                    $indexchars[] = array('char' => $char, 'page' => $page);
                }
                $prevchar = $char;
            }
        }
        return $indexchars;
    }
}
/**
 * Compare function for sorting the index into alphabetical order, first using the 
 * heading, then the subheading, then the description.  Non-alpha numbers will be 
 * sorted to the top of the list (could make this an option at some point). 
 *
 * @param $in1 first index item to compare
 * @param $in2 second index item to compare
 * @return comparison result
 */
function az_compare($in1, $in2) {
    global $az_nonalphaend, $az_multibyte;
    $rc = az_strcoll($in1['sort-head'], $in2['sort-head'], $az_multibyte);
    if ($rc == 0) {
        $rc = az_strcoll($in1['sort-subhead'], $in2['sort-subhead'], $az_multibyte);            
        if ($rc == 0) {
            $rc = az_strcoll($in1['sort-desc'], $in2['sort-desc'], $az_multibyte);            
        }
    } else {
        if (!empty($in1['initial']) && !empty($in2['initial'])) {
            if ($az_nonalphaend != 0) {
                // Check to see if either heading starts with a non-alphanumeric character.
                if ($az_multibyte) {
                    // Note this will still not work with some multi-byte character.
                    // Solution is to exclude them from the headings using ignore option.
                    $pos1 = mb_ereg_match('[[:upper:][:lower:][:digit:]]', $in1['initial']);
                    $pos2 = mb_ereg_match('[[:upper:][:lower:][:digit:]]', $in2['initial']);
                    //az_trace("COMP:".$in1['initial'].":".$in2['initial'].": $pos1 : $pos2");
                } else {                
                    $pos1 = ereg('[[:alnum:]]', $in1['initial']);
                    $pos2 = ereg('[[:alnum:]]', $in2['initial']);
                    //az_trace("COMP:".$in1['initial'].":".$in2['initial'].": $pos1 : $pos2 : $az_nonalphaend");
                }
                if ($pos1 === false && !($pos2 === false)) {
                    $rc = -1 * $az_nonalphaend;
                } else if ($pos2 === false && !($pos1 === false)) {
                    $rc = 1 * $az_nonalphaend;
                }
            }
        }   
    }
    return $rc;
}

function az_slice_array($array, $pageno, $pagecount, $perpage, $multipage) {
        
    $start = 0;
    $length = count($array);
    
    if ($multipage) {
        $length = $perpage;
        if ($pageno > 0) {
            // Keep the last item on the previous page in the front of the array if
            // we are not on the first (or only) page of the index. 
            $start = $pageno * $perpage - 1;
            $length++; 
        }
        if ($pageno < $pagecount - 1) {
            // Keep the first item on the next page at the end of the array if
            // we are not on the last (or only) page of the index. 
            $length++; 
        }
    }
    return array_slice($array, $start, $length);
}

function az_acquire_cache_mutex() {
    global $az_mutex;
    if (!isset($az_mutex)) {
        $az_mutex = new az_mutex(1, "az_sem_cache");
    }
    return $az_mutex->acquire();
}

function az_release_cache_mutex() {
    global $az_mutex;
    return $az_mutex->release();
}

class az_mutex {
    var $id;
    var $sem_id;
    var $is_acquired = false;
    var $use_flock = false;
    var $filename = '';
    var $filepointer;

    function az_mutex($id, $filename = '') {
        if (AZ_OS_WIN || !function_exists('sem_get')) {
            $this->use_flock = true;
        }
        
        $this->id = $id;

        if ($this->use_flock) {
            if (empty($filename)){
                az_trace("Mutex:no filename specified");
            } else {
                $this->filename = $filename;
            }
        } else {
            if (!($this->sem_id = sem_get($this->id, 1))) {
                az_trace("Mutex:Error getting semaphore");
            }
        }
    }

    function acquire() {
        if ($this->use_flock) {
            if (($this->filepointer = @fopen($this->filename, "w+")) == false) {
                az_trace("Mutex:error opening mutex file<br>");
                return false;
            }
            if (flock($this->filepointer, LOCK_EX) == false) {
                az_trace("Mutex:error locking mutex file");
                return false;
            }
        } else {
            if (!sem_acquire($this->sem_id)) {
                az_trace("Mutex:error acquiring semaphore");
                return false;
            }
        }
        $this->is_acquired = true;
        return true;
    }

    function release() {
        if (!$this->is_acquired) {
            return true;
        }

        if ($this->use_flock) {
            if (flock($this->filepointer, LOCK_UN) == false) {
                az_trace("Mutex:error unlocking mutex file");
                return false;
            }
            fclose($this->filepointer);
        } else {
            if (!sem_release($this->sem_id)) {
                az_trace("Mutex:error releasing semaphore");
                return false;
            }
        }

        $this->is_acquired = false;
        return true;
    }

    function getId() {
        return $this->sem_id;
    }
}

/**
 * Class encapsulating the code for processing the terms (categories and tags) 
 * specified in the settings for an index.  Responsible for separating out 
 * the included and excluded terms and for finding all the children of the
 * specified categories.
 *
 */
class az_terms {
    var $incats; // Included category ids
    var $intags; // Included tag ids
    var $excats; // Excluded category ids
    var $extags; // Excluded tag ids
    var $tree;   // Category relationship tree extracted from database
    
    /**
     * Constructor for the az_terms class. Does all the necessary processing
     * on the terms ready to be used for querying the database for the index.
     *
     * @param string $cats comma separated string of category ids
     * @param string $tags comma separated string of tag ids
     * @param boolean $include_children true if child categories are to be included
     */
    function az_terms($cats, $tags, $include_children) {
        $terms = $this->split_terms($cats);
        $this->incats = $terms['include'];
        $this->excats = $terms['exclude'];
        $terms = $this->split_terms($tags);
        $this->intags = $terms['include'];
        $this->extags = $terms['exclude'];
        
        if ($include_children) {
            $this->tree = $this->get_cat_tree();
            $this->incats = $this->get_children($this->incats);
            $this->excats = $this->get_children($this->excats);
        }
        //az_println('incats = '.$this->incats);
        //az_println('excats = '.$this->excats);
        //az_println('intags = '.$this->intags);
        //az_println('extags = '.$this->extags);
    }
    
    /**
     * Split the list of terms into those for terms to be included 
     * and those for terms to be excluded (prefixed with ~ sign)
     *
     * @param string $termids comma separated list of term ids (categories or tags).
     * @return array containing two strings for 'include' and 'exclude' term ids. 
     */
    function split_terms($termids) {
        // Only do the split if there is a ~ sign in the string.
        if (!(strpos($termids, '~') === false)) {
            $ids = explode(',', $termids);
            foreach ($ids as $id) {
                if ($id[0] == '~') {
                    $exclude .= trim($id, '~').',';
                } else {
                    $include .= $id.',';
                }
            }
            $termlist['exclude'] = trim($exclude, ',');
            $termlist['include'] = trim($include, ',');
            
        } else if (!empty($termids)) {
            // No exclude terms, just copy into the include array. 
            $termlist['include'] = $termids;
        }
        return $termlist;
    }

    function get_children($idlist) {
        if (!empty($idlist) && !empty($this->tree)) {
            $ids = explode(",", $idlist);
            foreach ($ids as $id) {
                $children = $this->find_children(null, $id);
                if (!empty($children)) { 
                    $idlist .= ','.implode(',', $children);
                }
            }
        }
        return $idlist;
    }
    
    function find_children($children, $id) {
        foreach ($this->tree as $cat) {
            if ($cat['parent'] == $id) {
                $children[] = $cat['id'];
                $children = $this->find_children($children, $cat['id']);                                         
            }
        }
        return $children;
    }

    function get_cat_tree() {
        global $wpdb;
        $query = "SELECT * FROM $wpdb->term_taxonomy WHERE taxonomy = 'category' AND parent != 0";
        $result = $wpdb->get_results($query);
        foreach ($result as $row) {
            $tree[] = array('id' => $row->term_id, 'parent' => $row->parent);
        }
        return $tree;
    }
}

function az_map_char($char, $mapper) {
    $pos = mb_strpos($mapper[1], $char, 0, "UTF-8");
    if ($pos !== false) {
        $char = mb_substr($mapper[0], $pos, 1, "UTF-8");
    }
    return $char;
}

function az_get_collation_mapper($language) {
    switch ($language) {
        case "Czech":
            $map[0] = pack("H*", "41414141C48C44444545454549494E4E4F4F4F4FC598C5A054545555555555555959");
            $map[1] = pack("H*", "C381C384C3A1C3A4C48DC48EC48FC389C3A9C49AC49BC38DC3ADC587C588C393C396C3B3C3B6C599C5A1C5A4C5A5C39AC39CC3BAC3BCC5AEC5AFC39DC3BD");
            break;
        case "Danish":
            $map[0] = pack("H*", "4141414141414141C7BC4343C390454545454545454549494F4F4F4FC592C7BE555559595959C384C384C384C396C396C396C385");
            $map[1] = pack("H*", "C380C381C382C3A0C3A1C3A2C7BAC7BBC7BDC387C3A7C3B0C388C389C38AC38BC3A8C3A9C3AAC3ABC38DC3ADC393C394C3B3C3B4C593C7BFC39AC3BAC39CC39DC3BCC3BDC386C3A4C3A6C398C3B6C3B8C3A5");
            break;
        case "Esperanto":
            $map[0] = pack("H*", "C488C49CC4A4C4B4C59CC5AC");
            $map[1] = pack("H*", "C489C49DC4A5C4B5C59DC5AD");
            break;
        case "Estonian":
            $map[0] = pack("H*", "C5A0C5BDC395C384C396C39C");
            $map[1] = pack("H*", "C5A1C5BEC3B5C3A4C3B6C3BC");
            break;
        case "General European":
            $map[0] = pack("H*", "414141414141414141414141414141414141414141414141414141414141414141414242424242424343434343434343434343434444444444444444444444444545454545454545454545454545454545454545454545454545454545454545454546464747474747474747474747474747484848484848484848484848484848494949494949494949494949494949494949494949494949494949494A4A4A4B4B4B4B4B4B4B4B4B4B4C4C4C4C4C4C4C4C4C4C4C4C4C4C4D4D4D4D4D4D4E4E4E4E4E4E4E4E4E4E4E4E4E4E4E4E4E4E4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F4F505050505252525252525252525252525252525252525353535353535353535353535353535353535353535454545454545454545454545454545555555555555555555555555555555555555555555555555555555555555555555555555555555555555555555556565656575757575757575757575757575858585859595959595959595959595A5A5A5A5A5A5A5A5A5A5A5AC386C386C386C386C386C390C398C398C398C39EC490C4A6C4B2C4BFC581C58AC592C5A6C682C684C687C68BC68EC691C698C6A2C6A4C6A7C6ACC6B3C6B5C6B7C6B7C6B8C6BCC784C784C787C787C78AC78AC7A4C7B1C7B1C695C6BFC89CC8A2C8A4CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE86CE92CE92CE93CE94CE88CE88CE88CE88CE88CE88CE88CE88CE88CE88CE88CE88CE88CE88CE88CE88CE88CE96CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE89CE98CE98CE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE8ACE9ACE9ACE9BCE9CCE9DCE9ECE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCE8CCEA0CEA0CEA1CEA1CEA1CEA1CEA1CEA3CEA3CEA3CEA4CE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECE8ECEA6CEA6CEA7CEA8CE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCE8FCF92CF92CF9ACF9CCF9ECFA0CFA2CFA4CFA6CFA8CFAACFACCFAED082D084D085D086D086D086D088D089D08AD08BD08FD090D090D090D090D090D091D092D083D083D083D094D080D080D080D080D080D080D080D096D096D096D096D096D097D097D097D08DD08DD08DD08DD08DD08DD08DD099D08CD08CD08CD09BD09CD09DD09ED09ED09ED09FD0A0D0A1D0A2D08ED08ED08ED08ED08ED08ED08ED08ED08ED0A4D0A5D0A6D0A7D0A7D0A7D0A8D0A9D0AAD0ABD0ABD0ABD0ACD0ADD0ADD0ADD0AED0AFD1A0D1A2D1A4D1A6D1A8D1AAD1ACD1AED1B0D1B2D1B4D1B4D1B4D1B8D1BAD1BCD1BED280D28CD28ED290D292D294D296D298D29AD29CD29ED2A0D2A2D2A4D2A6D2A8D2AAD2ACD2AED2B0D2B2D2B4D2B6D2B8D2BAD2BCD2BED383D387D38BD394D398D398D398D3A0D3A8D3A8D3A8D4B1D4B2D4B3D4B4D4B5D4B6D4B7D4B8D4B9D4BAD4BBD4BCD4BDD4BED4BFD580D581D582D583D584D585D586D587D588D589D58AD58BD58CD58DD58ED58FD590D591D592D593D594D595D596E1BDB1E1BDB3E1BDB5E1BDB7E1BDBBE1BDB9");
            $map[1] = pack("H*", "C380C381C382C383C384C385C3A0C3A1C3A2C3A3C3A4C3A5C480C481C482C483C484C485C78DC78EC79EC79FC7A0C7A1C7BAC7BBC880C881C882C883C8A6C8A7E1B880E1B881E1B882E1B883E1B884E1B885E1B886E1B887C387C3A7C486C487C488C489C48AC48BC48CC48DE1B888E1B889C48EC48FE1B88AE1B88BE1B88CE1B88DE1B88EE1B88FE1B890E1B891E1B892E1B893C388C389C38AC38BC3A8C3A9C3AAC3ABC492C493C494C495C496C497C498C499C49AC49BC884C885C886C887C8A8C8A9E1B894E1B895E1B896E1B897E1B898E1B899E1B89AE1B89BE1B89CE1B89DE1B89EE1B89FC49CC49DC49EC49FC4A0C4A1C4A2C4A3C7A6C7A7C7B4C7B5E1B8A0E1B8A1C4A4C4A5C89EC89FE1B8A2E1B8A3E1B8A4E1B8A5E1B8A6E1B8A7E1B8A8E1B8A9E1B8AAE1B8ABE1BA96C38CC38DC38EC38FC3ACC3ADC3AEC3AFC4A8C4A9C4AAC4ABC4ACC4ADC4AEC4AFC4B0C4B1C78FC790C888C889C88AC88BE1B8ACE1B8ADE1B8AEE1B8AFC4B4C4B5C7B0C4B6C4B7C7A8C7A9E1B8B0E1B8B1E1B8B2E1B8B3E1B8B4E1B8B5C4B9C4BAC4BBC4BCC4BDC4BEE1B8B6E1B8B7E1B8B8E1B8B9E1B8BAE1B8BBE1B8BCE1B8BDE1B8BEE1B8BFE1B980E1B981E1B982E1B983C391C3B1C583C584C585C586C587C588C7B8C7B9E1B984E1B985E1B986E1B987E1B988E1B989E1B98AE1B98BC392C393C394C395C396C3B2C3B3C3B4C3B5C3B6C58CC58DC58EC58FC590C591C6A0C6A1C791C792C7AAC7ABC7ACC7ADC88CC88DC88EC88FC8AAC8ABC8ACC8ADC8AEC8AFC8B0C8B1E1B98CE1B98DE1B98EE1B98FE1B990E1B991E1B992E1B993E1B994E1B995E1B996E1B997C594C595C596C597C598C599C890C891C892C893E1B998E1B999E1B99AE1B99BE1B99CE1B99DE1B99EE1B99FC59AC59BC59CC59DC59EC59FC5A0C5A1C5BFC898C899E1B9A0E1B9A1E1B9A2E1B9A3E1B9A4E1B9A5E1B9A6E1B9A7E1B9A8E1B9A9C5A2C5A3C5A4C5A5C89AC89BE1B9AAE1B9ABE1B9ACE1B9ADE1B9AEE1B9AFE1B9B0E1B9B1E1BA97C399C39AC39BC39CC3B9C3BAC3BBC3BCC5A8C5A9C5AAC5ABC5ACC5ADC5AEC5AFC5B0C5B1C5B2C5B3C6AFC6B0C793C794C795C796C797C798C799C79AC79BC79CC894C895C896C897E1B9B2E1B9B3E1B9B4E1B9B5E1B9B6E1B9B7E1B9B8E1B9B9E1B9BAE1B9BBE1B9BCE1B9BDE1B9BEE1B9BFC5B4C5B5E1BA80E1BA81E1BA82E1BA83E1BA84E1BA85E1BA86E1BA87E1BA88E1BA89E1BA98E1BA8AE1BA8BE1BA8CE1BA8DC39DC3BDC3BFC5B6C5B7C5B8C8B2C8B3E1BA8EE1BA8FE1BA99C5B9C5BAC5BBC5BCC5BDC5BEE1BA90E1BA91E1BA92E1BA93E1BA94E1BA95C3A6C7A2C7A3C7BCC7BDC3B0C3B8C7BEC7BFC3BEC491C4A7C4B3C580C582C58BC593C5A7C683C685C688C68CC79DC692C699C6A3C6A5C6A8C6ADC6B4C6B6C7AEC7AFC6B9C6BDC785C786C788C789C78BC78CC7A5C7B2C7B3C7B6C7B7C89DC8A3C8A5CE91CEACCEB1E1BC80E1BC81E1BC82E1BC83E1BC84E1BC85E1BC86E1BC87E1BC88E1BC89E1BC8AE1BC8BE1BC8CE1BC8DE1BC8EE1BC8FE1BDB0E1BE80E1BE81E1BE82E1BE83E1BE84E1BE85E1BE86E1BE87E1BE88E1BE89E1BE8AE1BE8BE1BE8CE1BE8DE1BE8EE1BE8FE1BEB0E1BEB1E1BEB2E1BEB3E1BEB4E1BEB6E1BEB7E1BEB8E1BEB9E1BEBAE1BEBCCEB2CF90CEB3CEB4CE95CEADCEB5E1BC90E1BC91E1BC92E1BC93E1BC94E1BC95E1BC98E1BC99E1BC9AE1BC9BE1BC9CE1BC9DE1BDB2E1BF88CEB6CE97CEAECEB7E1BCA0E1BCA1E1BCA2E1BCA3E1BCA4E1BCA5E1BCA6E1BCA7E1BCA8E1BCA9E1BCAAE1BCABE1BCACE1BCADE1BCAEE1BCAFE1BDB4E1BE90E1BE91E1BE92E1BE93E1BE94E1BE95E1BE96E1BE97E1BE98E1BE99E1BE9AE1BE9BE1BE9CE1BE9DE1BE9EE1BE9FE1BF82E1BF83E1BF84E1BF86E1BF87E1BF8AE1BF8CCEB8CF91CE90CE99CEAACEAFCEB9CF8AE1BCB0E1BCB1E1BCB2E1BCB3E1BCB4E1BCB5E1BCB6E1BCB7E1BCB8E1BCB9E1BCBAE1BCBBE1BCBCE1BCBDE1BCBEE1BCBFE1BDB6E1BEBEE1BF90E1BF91E1BF92E1BF96E1BF97E1BF98E1BF99E1BF9ACEBACFB0CEBBCEBCCEBDCEBECE9FCEBFCF8CE1BD80E1BD81E1BD82E1BD83E1BD84E1BD85E1BD88E1BD89E1BD8AE1BD8BE1BD8CE1BD8DE1BDB8E1BFB8CF80CF96CF81CFB1E1BFA4E1BFA5E1BFACCF82CF83CFB2CF84CEA5CEABCEB0CF85CF8BCF8DE1BD90E1BD91E1BD92E1BD93E1BD94E1BD95E1BD96E1BD97E1BD99E1BD9BE1BD9DE1BD9FE1BDBAE1BFA0E1BFA1E1BFA2E1BFA6E1BFA7E1BFA8E1BFA9E1BFAACF86CF95CF87CF88CEA9CF89CF8EE1BDA0E1BDA1E1BDA2E1BDA3E1BDA4E1BDA5E1BDA6E1BDA7E1BDA8E1BDA9E1BDAAE1BDABE1BDACE1BDADE1BDAEE1BDAFE1BDBCE1BEA0E1BEA1E1BEA2E1BEA3E1BEA4E1BEA5E1BEA6E1BEA7E1BEA8E1BEA9E1BEAAE1BEABE1BEACE1BEADE1BEAEE1BEAFE1BFB2E1BFB3E1BFB4E1BFB6E1BFB7E1BFBAE1BFBCCF93CF94CF9BCF9DCF9FCFA1CFA3CFA5CFA7CFA9CFABCFADCFAFD192D194D195D087D196D197D198D199D19AD19BD19FD0B0D390D391D392D393D0B1D0B2D093D0B3D193D0B4D081D095D0B5D190D191D396D397D0B6D381D382D39CD39DD0B7D39ED39FD098D0B8D19DD3A2D3A3D3A4D3A5D0B9D09AD0BAD19CD0BBD0BCD0BDD0BED3A6D3A7D0BFD180D181D182D0A3D183D19ED3AED3AFD3B0D3B1D3B2D3B3D184D185D186D187D3B4D3B5D188D189D18AD18BD3B8D3B9D18CD18DD3ACD3ADD18ED18FD1A1D1A3D1A5D1A7D1A9D1ABD1ADD1AFD1B1D1B3D1B5D1B6D1B7D1B9D1BBD1BDD1BFD281D28DD28FD291D293D295D297D299D29BD29DD29FD2A1D2A3D2A5D2A7D2A9D2ABD2ADD2AFD2B1D2B3D2B5D2B7D2B9D2BBD2BDD2BFD384D388D38CD395D399D39AD39BD3A1D3A9D3AAD3ABD5A1D5A2D5A3D5A4D5A5D5A6D5A7D5A8D5A9D5AAD5ABD5ACD5ADD5AED5AFD5B0D5B1D5B2D5B3D5B4D5B5D5B6D5B7D5B8D5B9D5BAD5BBD5BCD5BDD5BED5BFD680D681D682D683D684D685D686E1BEBBE1BF89E1BF8BE1BF9BE1BFABE1BFB9");
            break;
        case "Hungarian":
            $map[0] = pack("H*", "41414141454549494F4FC396C396C3965555C39CC39CC39C");
            $map[1] = pack("H*", "C380C381C3A0C3A1C389C3A9C38DC3ADC393C3B3C3B6C590C591C39AC3BAC3BCC5B0C5B1");
            break;
        case "Icelandic":
            $map[0] = pack("H*", "C381C3904545C389C38DC3935555C39AC39DC39EC384C384C384C398");
            $map[1] = pack("H*", "C3A1C3B0C38BC3ABC3A9C3ADC3B3C39CC3BCC3BAC3BDC3BEC386C3A4C3A6C3B8");
            break;
        case "Latvian":
            $map[0] = pack("H*", "4141C48C4545C4A24949C4B6C4BBC5854F4FC596C5A05555");
            $map[1] = pack("H*", "C480C481C48DC492C493C4A3C4AAC4ABC4B7C4BCC586C58CC58DC597C5A1C5AAC5AB");
            break;
        case "Lithuanian":
            $map[0] = pack("H*", "414143434343C48C4949C5A055555555");
            $map[1] = pack("H*", "C484C485C486C487C488C489C48DC4AEC4AFC5A1C5AAC5ABC5B2C5B3");
            break;
        case "Polish":
            $map[0] = pack("H*", "C484C486C498C581C583C393C59AC5B9");
            $map[1] = pack("H*", "C485C487C499C582C584C3B3C59BC5BA");
            break;
        case "Romanian":
            $map[0] = pack("H*", "C482C382C38EC898C89A");
            $map[1] = pack("H*", "C483C3A2C3AEC899C89B");
            break;
        case "Roman":
            $map[0] = pack("H*", "41414545494949494F4F4F4FC5AAC5AAC5AA");
            $map[1] = pack("H*", "C480C481C492C493C4AAC4ABC4ACC4ADC58CC58DC58EC58FC5ABC5ACC5AD");
            break;
        case "Slovak":
            $map[0] = pack("H*", "4141C384C48C4444454549494C4C4C4C4E4E4F4F4F4F4F4FC3945252C5A054545555555555555959");
            $map[1] = pack("H*", "C381C3A1C3A4C48DC48EC48FC389C3A9C38DC3ADC4B9C4BAC4BDC4BEC587C588C393C396C3B3C3B6C590C591C3B4C594C595C5A1C5A4C5A5C39AC39CC3BAC3BCC5B0C5B1C39DC3BD");
            break;
        case "Slovenian":
            $map[0] = pack("H*", "C48CC490C5A0");
            $map[1] = pack("H*", "C48DC491C5A1");
            break;
        case "Spanish":
            $map[0] = pack("H*", "414145454949C3914F4F55555555");
            $map[1] = pack("H*", "C381C3A1C389C3A9C38DC3ADC3B1C393C3B3C39AC39CC3BAC3BC");
            break;
        case "Swedish":
            $map[0] = pack("H*", "4141414141414141434343434343C39045454545454545454949494949494949C5814E4E4E4E4F4F4F4F4F4F525253535355555555555559595959C385C384C384C384");
            $map[1] = pack("H*", "C380C381C382C383C3A0C3A1C3A2C3A3C387C3A7C486C487C48CC48DC3B0C388C389C38AC38BC3A8C3A9C3AAC3ABC38CC38DC38EC38FC3ACC3ADC3AEC3AFC582C391C3B1C583C584C392C393C394C3B2C3B3C3B4C598C599C59AC59BC5A1C399C39AC39BC3B9C3BAC3BBC39CC39DC3BCC3BDC3A5C386C3A4C3A6");
            break;
        case "Turkish":
            $map[0] = pack("H*", "4141C387C49E4969C3965555C39C");
            $map[1] = pack("H*", "C382C3A2C3A7C49FC4B1C4B0C3B6C39BC3BBC3BC");
            break;
    }
    return $map;
}

function az_dump_dirty($dirty, $pad = "") {
    foreach ($dirty as $key => $item) {
        if (is_array($item)) {
            if (is_string($key)) {
                 az_trace('   '.$key.':');
                 az_dump_dirty($item, "   ");   
            } else {
                 az_trace($pad."   $key = ".implode(',', $item));
            }
        } else {
            az_trace($pad."   $key = $item");
        }
    }
}
?>