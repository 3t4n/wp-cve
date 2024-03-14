<?php
/*
 * This file handles the creation of a Facebook Gallery.
 * When a page containing the "magic tag" is saved, this function will:
 *   -Fetch the album from facebook
 *   -Fill in the album content between the tags, formatting it based on the parameters in the tag
 *   -Add postmeta "_fpf_album_size" with the number of items in the album (which the user can optionally reference)
 *   -Add postmeta "_fpf_album_cover" with the Facebook URL of the cover photo, if found
 *
 * Re-saving the same page will re-fetch the album from facebook and regenerate its content again.
 */
add_action('wp_insert_post_data', 'fpf_run_main');
function fpf_run_main($data)
{
    //Don't process REVISIONS (would result in 2 fetches per save)
    if ($data['post_type'] == 'revision')
        return $data;

    //Check the content for our magic tag (and parse out everything we need if found)
    $parsed_content = fpf_find_tags($data['post_content']);
    if ($parsed_content === 0)
        return $data;
    if (isset($parsed_content['error'])) {
        $data['post_content'] .= "<br/>" . $parsed_content['error'];
        return $data;
    }

    //Connect to Facebook and generate the album content
    $album_content = fpf_fetch_album_content($parsed_content['aid'], $parsed_content);

    //Update the post we're about to save
    $data['post_content'] = $parsed_content['before'] .
        $parsed_content['startTag'] .
        $album_content['content'] .
        $parsed_content['endTag'] .
        $parsed_content['after'];

    //Set postmeta with the album's size and cover photo (can be optionally referenced by the user)
    //(Note: for some stupid reason, $data doesn't have the ID - we need to parse it out of the guid.)
    $post_ID = substr(strrchr($data['guid'], '='), 1);
    update_post_meta($post_ID, '_fpf_album_size', $album_content['count']);
    if (isset($album_content['cover'])) update_post_meta($post_ID, '_fpf_album_cover', $album_content['cover']);
    else                               delete_post_meta($post_ID, '_fpf_album_cover');

    //Done!
    return $data;
}


/**
 * Check a post's content for valid "magic tags".  If found, return:
 * $retVal['before']   //Content before the start tag
 * $retVal['after']    //Content after the end tag
 * $retVal['aid']      //The albumID parsed from the start tag
 * $retVal['startTag'] //The complete starttag
 * $retVal['endTag']   //The complete endTag
 * $retVal[....]       //Additional supported parameters found in the startTag.
 *                     //For a full list of what's available see fpf_fetch_album_content().
 *
 * If found but erroroneous, return:
 * $regVal['error'] = error message
 *
 * If not found, return 0.
 */
function fpf_find_tags($post_content)
{
    //Start by splitting the content at startTag, and check for "none" or "too many" occurrences
    global $fpf_identifier;
    $result = preg_split("/(\<!--[ ]*" . $fpf_identifier . "[ ]*?([\d_-]+).*?--\>)/", $post_content, -1, PREG_SPLIT_DELIM_CAPTURE);
    if (count($result) < 4)            //No tags found
        return 0;
    if (count($result) > 4)            //Too many tags found
        return array('error' => __("Sorry, this plugin currently supports only one Facebook gallery per page.", 'facebook-photo-fetcher') . "<br />");
    $retVal = array();
    $retVal['before']   = $result[0];
    $retVal['startTag'] = $result[1];
    $retVal['aid']      = $result[2];
    $retVal['after']    = $result[3];

    //Now search the remaining content and split it at the endTag, again checking for "none" or "too many"
    $result = preg_split("/(\<!--[ ]*\/" . $fpf_identifier . "[ ]*--\>)/", $retVal['after'], -1, PREG_SPLIT_DELIM_CAPTURE);
    if (count($result) < 3)
        return array('error' => __("Missing gallery end-tag.", 'facebook-photo-fetcher') . "<br />");
    if (count($result) > 3)
        return array('error' => __("Duplicate gallery end-tag found.", 'facebook-photo-fetcher') . "<br />");
    $retVal['endTag'] = $result[1];
    $retVal['after']  = $result[2];

    //Check for optional params in the startTag:
    if (preg_match('/cols=(\d+)/', $retVal['startTag'], $matches))     $retVal['cols']     = $matches[1];
    if (preg_match('/start=(\d+)/', $retVal['startTag'], $matches))    $retVal['start']    = $matches[1];
    if (preg_match('/max=(\d+)/', $retVal['startTag'], $matches))      $retVal['max']      = $matches[1];
    if (preg_match('/swapHead=(\d+)/', $retVal['startTag'], $matches)) $retVal['swapHead'] = $matches[1] ? true : false;
    if (preg_match('/hideHead=(\d+)/', $retVal['startTag'], $matches)) $retVal['hideHead'] = $matches[1] ? true : false;
    if (preg_match('/hideDesc=(\d+)/', $retVal['startTag'], $matches)) $retVal['hideDesc'] = $matches[1] ? true : false;
    if (preg_match('/hideCaps=(\d+)/', $retVal['startTag'], $matches)) $retVal['hideCaps'] = $matches[1] ? true : false;
    if (preg_match('/noLB=(\d+)/', $retVal['startTag'], $matches))     $retVal['noLB']     = $matches[1] ? true : false;
    if (preg_match('/hideCred=(\d+)/', $retVal['startTag'], $matches)) $retVal['hideCred'] = $matches[1] ? true : false;
    if (preg_match('/rand=(\d+)/', $retVal['startTag'], $matches))     $retVal['rand']     = $matches[1];
    if (preg_match('/orderby=(\w+)/', $retVal['startTag'], $matches))  $retVal['orderby']  = $matches[1];
    return apply_filters('fpf_parse_params', $retVal);
}



/**
 * Given a Facebook AlbumID, fetch its content and return:
 * $retVal['content'] - The generated HTML content we'll use to display the album
 * $retVal['cover']   - The Facebook album's cover photo (if set)
 * $retVal['count']   - The number of SHOWN photos in the album
 *
 * $params is a array of extra options, parsed from the startTag by fpf_find_tags().
 * For a list of supported options and their meanings see the $defaults array below.
 */
function fpf_fetch_album_content($aid, $params)
{
    //Combine optional parameters with default values
    global $fpf_homepage;
    $defaults = array(
        'cols'    => 4,               //Number of columns of images (aka Number of images per row)
        'start'   => 0,               //The first photo index to show (aka skip some initially)
        'max'     => 99999999999,     //The max number of items to show
        'swapHead' => false,           //Swap the order of the 2 lines in the album header?
        'hideHead' => false,           //Hide the album header entirely?
        'hideDesc' => false,           //Hide just the description portion of the header
        'hideCaps' => false,           //Hide the per-photo captions on the main listing?
        'noLB'    => false,           //Suppress outputting the lightbox javascript?
        'hideCred' => false,           //Omit the "Generated by Facebook Photo Fetcher" footer (please don't :))
        'rand'    => false,           //Randomly select n photos from the album (or from photos between "start" and "max")
        'orderby' => 'normal'
    );       //Can be "normal" or "reverse" (for now)
    $params = array_merge(apply_filters('fpf_default_albumparams', $defaults), $params);
    $itemwidth = $params['cols'] > 0 ? floor(100 / $params['cols']) : 100;
    $itemwidth -= (0.5 / $params['cols']); //For stupid IE7, which rounds fractional percentages UP (shave off 0.5%, or the last item will wrap to the next row)
    $retVal = array();
    $retVal['content'] = "";
    $retVal['count'] = 0;

    //Get our saved access token (and make sure it exists)
    global $fpf_opt_access_token, $fpf_apiver;
    $access_token = get_option($fpf_opt_access_token);
    if (!$access_token) {
        $retVal['content'] = "Error 0: " . __('This plugin does not have a valid Facebook access token.  Please use your admin panel to login with Facebook.', 'facebook-photo-fetcher');
        return $retVal;
    }

    //Try to fetch the album object from Facebook, and check for common errors.
    $album_fetch_url = "https://graph.facebook.com/$fpf_apiver/$aid?access_token=$access_token&fields=id,cover_photo,count,link,name,from,created_time,description";
    $album = fpf_get($album_fetch_url);
    if (!$album || isset($album->error)) {
        if (!$album)                       $retVal['content'] = "Error 1: " . __("An unknown error occurred while trying to fetch the album (empty reply).", 'facebook-photo-fetcher');
        else if ($album->error->code == 190) $retVal['content'] = "Error 190: " . __("Invalid OAuth Access Token.  Try using the admin panel to re-validate your plugin.", 'facebook-photo-fetcher');
        else if ($album->error->code == 803) $retVal['content'] = "Error 803: " . __("Your album id doesn't appear to exist.", 'facebook-photo-fetcher');
        else if ($album->error->code == 100) $retVal['content'] = "Error 100: " . __("Your album id doesn't appear to be accessible.", 'facebook-photo-fetcher');
        return $retVal;
    }
    if (!isset($album->id) || $album->id != $aid) {
        $retVal['content'] = "Error 2: " . __("An unknown error occurred while trying to fetch the album (id mismatch).", 'facebook-photo-fetcher');
        return $retVal;
    }
    if (!isset($album->cover_photo) || $album->id != $aid) {
        $retVal['content'] = "Error 3: " . __("An error occurred while trying to fetch the album: the ID specified does not appear to be an album.", 'facebook-photo-fetcher');
        return $retVal;
    }
    if ($album->count == 0) {
        $retVal['content'] = "Error 4: " . __("An error occurred while trying to fetch the album: it appears to be empty.", 'facebook-photo-fetcher');
        return $retVal;
    }

    //Now that we know the album is OK, try to fetch its photos.  Note that as of Feb 2014, it seems like Facebook
    //won't return more than 100 photos, so I'll have to fetch them in paged groups...
    $photos = array();
    $photoGroupNum = 0;
    $debugString = "Starting to fetch $album->count photos.\nAlbum: <a href='$album_fetch_url'>$album_fetch_url</a>\n";
    $debugPhotoCount = 0;
    $fetch_url = apply_filters('fpf_fetch_url', "https://graph.facebook.com/$fpf_apiver/$aid/photos?access_token=$access_token&limit=9999&fields=name,source,picture");
    while (true) {
        //Fetch this group (as many as FB will give us at once...might not be all of them, even though I specify a limit of 9999)
        $photosThisGroup = fpf_get($fetch_url);

        //Make sure no error
        if (!$photosThisGroup || !isset($photosThisGroup->data)) {
            $retVal['content'] = "Error 5: " . __("An unknown error occurred while trying to fetch the photos (empty data).", 'facebook-photo-fetcher');
            return $retVal;
        }

        //Just for testing...
        $debugString .= "**********************************************\n";
        $debugString .= "Group: $photoGroupNum\nFetch URL: <a href='$fetch_url'>$fetch_url</a>\nItems: " . count($photosThisGroup->data) . "\n";
        $debugString .= "**********************************************\n";
        foreach ($photosThisGroup->data as $photo) $debugString .= ($debugPhotoCount++) . ") <a href='$photo->source'>$photo->source</a>\n";
        $debugString .= "\n\n";

        //If we didn't get any back, we must've already fetched all available photos - break out of this loop.
        //I don't think this should ever happen, but check just in case (to avoid infinite loop)
        if (count($photosThisGroup->data) == 0) {
            $debugString .= "--->Done: No results returned.";
            break;
        }

        //Likewise - just be sure there's no infinite loop.  I'm pretty sure no album will ever have >2000 photos.
        if ($photoGroupNum >= 20) {
            $debugString .= "--->Done: Stopped to prevent infinite loop (Limit: 2000 photos).";
            break;
        }

        //Tack these results onto our 'main overall' set of photos
        $photos = array_merge($photos, $photosThisGroup->data);

        //If we've got the total expected number of photos, we're done - break out of this loop
        if (count($photos) == $album->count) {
            $debugString .= "--->Done: Successfully fetched all " . count($photos) . " photos.";
            break;
        }

        //If the next 'paging' url isn't specified, it's telling us there are no more photos available - break out of this loop
        if (!isset($photosThisGroup->paging->next)) {
            $debugString .= "--->Done: Paging->next wasn't set.";
            break;
        }

        //Otherwise, get the URL to fetch the next group of photos & keep going.
        $fetch_url = $photosThisGroup->paging->next;
        $photoGroupNum++;
    }
    //echo "<pre>$debugString</pre>";

    //Sanity check
    //if(count($photos) != $album->count) $retVal['content'] = "<i>Warning: A size mismatch error occurred while trying to fetch the photos (the album reported $album->count entries, but only " . count($photos) . " were returned).</i><br />";

    //Run filters so we can modify the album and photo data
    $album = apply_filters('fpf_album_data', $album);
    $photos = apply_filters('fpf_photos_presort', $photos);

    //Store the filename of the album cover
    //We must do this here, prior to slicing down the array of photos.
    if (isset($album->cover_photo)) {
        foreach ($photos as $photo) {
            if (strcmp($photo->id, $album->cover_photo->id) == 0)
                $retVal['cover'] = $photo->source;
        }
    }

    //Reorder the photos if necessary
    if ($params['orderby'] == 'reverse') {
        $photos = array_reverse($photos);
    }

    //Slice the photo array as necessary
    if (count($photos) > 0) {
        //Slice the photos between "start" and "max"
        if ($params['start'] > $album->count) {
            $retVal['content'] .= "Error 6: " . sprintf(__("Start index %s is greater than the total number of photos in this album; Defaulting to 0.", 'facebook-photo-fetcher'), $params['start']) . "<br /><br />";
            $params['start'] = 0;
        }
        if ($params['max'] > $album->count - $params['start'])
            $params['max'] = $album->count - $params['start'];
        $photos = array_slice($photos, $params['start'], $params['max']);

        //If "rand" is specified, randomize the order and slice again
        if ($params['rand']) {
            shuffle($photos);
            $photos = array_slice($photos, 0, $params['rand']);
        }
    }

    //Run a filter so addons can modify/process the photos
    $photos = apply_filters('fpf_photos_postsort', $photos);

    //Create a header with some info about the album
    $retVal['count'] = count($photos);
    $headerDesc = "";
    $headerTitle = "";
    if (!$params['hideHead']) {
        /* translators: This is what displays "From (album name)," above imported albums.*/
        $headerTitle  = sprintf(__('From %s.', 'facebook-photo-fetcher'), '<a href="' . htmlspecialchars($album->link) . '">' . $album->name . '</a>');
        if (isset($album->from->id) && isset($album->created_time)) {
            /* translators: This is what displays the Facebook user & album date, above imported albums (i.e. "posted by John Smith on 1/1/2001")*/
            $headerTitle .= ' ' . sprintf(__('Posted by %s on %s', 'facebook-photo-fetcher'), '<a href="http://www.facebook.com/profile.php?id=' . $album->from->id . '">' . $album->from->name . '</a>', date('n/d/Y', strtotime($album->created_time)));
        }
        /* translators: Shows the photo count in the header above imported albums*/
        if ($retVal['count'] < $album->count) $headerTitle .= ' (' . sprintf(__('Showing %s of %s items', 'facebook-photo-fetcher'), $retVal['count'], $album->count) . ")\n";
        /* translators: Shows the photo count in the header above imported albums*/
        else                                  $headerTitle .= ' (' . sprintf(__('%s items', 'facebook-photo-fetcher'), $retVal['count']) . ")\n";
        $headerTitle .= '<br /><br />';

        if (!$params['hideDesc']) {
            if (isset($album->description)) $headerDesc = '"' . $album->description . '"<br /><br />' . "\n";
            else                             $headerDesc = "";
        }
    }

    //Output the album!  Starting with a (hidden) timestamp, then the header, then each photo.
    global $fpf_version;
    $retVal['content'] .= "<!-- ID " . $aid . " Last fetched on " . date('m/d/Y H:i:s') . " v$fpf_version-->\n";
    if ($params['swapHead'])   $retVal['content'] .= $headerTitle . $headerDesc;
    else                        $retVal['content'] .= $headerDesc . $headerTitle;
    $retVal['content'] .= "<div class='gallery fpf-gallery'>\n";
    $i = 0;
    foreach ($photos as $photo) {
        //Strip [], or WP will try to run it as shortcode
        if (!isset($photo->name)) $photo->name = "";
        $caption = preg_replace("/\[/", "(", $photo->name);
        $caption = preg_replace("/\]/", ")", $caption);

        //Strip emoji.
        //Emoji come from FB as surrogate pairs (like "\udbb8\udf2c"), which get converted to UTF8 when we json_decode() the string (see http://stackoverflow.com/questions/17445901/replace-iphone-emoji-in-html-page)
        //First, strip these (http://apps.timwhitlock.info/emoji/tables/unicode) (Code from: http://stackoverflow.com/questions/12807176/php-writing-a-simple-removeemoji-function)
        $caption = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $caption);
        $caption = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $caption);
        $caption = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $caption);
        //And here are some more (This was the range that was messing up Mark Laurich's albums: https://github.com/adamrocker/Japanese-Mobile-Emoji/blob/master/EmojiData.csv)
        $caption = preg_replace('/[\x{FE000}-\x{FE4E4}]/u', '', $caption);

        //Output this photo
        $caption = preg_replace("/\r/", "", $caption);
        $caption_with_br = htmlspecialchars(preg_replace("/\n/", "<br />", $caption));
        $caption_no_br = htmlspecialchars(preg_replace("/\n/", " ", $caption));
        if ($caption_with_br != '')
            $link = '<a rel="' . htmlspecialchars($album->link) . '" class="fbPhoto" href="' . htmlspecialchars($photo->source) . '" data-fancybox="gallery" data-caption="' . $caption_with_br . ' " ><img src="' . htmlspecialchars($photo->picture) . '" alt="" /></a>';
        else
            $link = '<a rel="' . htmlspecialchars($album->link) . '" class="fbPhoto" href="' . htmlspecialchars($photo->source) . '" data-fancybox="gallery"><img src="' . htmlspecialchars($photo->picture) . '" alt="" /></a>';
        $retVal['content'] .= "<dl class='gallery-item' style=\"width:$itemwidth%\">";
        $retVal['content'] .= "<dt class='gallery-icon'>$link</dt>";
        if (!$params['hideCaps']) {
            $retVal['content'] .= "<dd class='gallery-caption'>";
            $retVal['content'] .= mb_substr($caption_no_br, 0, 85) . (strlen($caption_no_br) > 85 ? "..." : "");
            $retVal['content'] .= "</dd>";
        }
        $retVal['content'] .= "</dl>\n";

        //Move on to the next row?
        if ($params['cols'] > 0 && ++$i % $params['cols'] == 0) $retVal['content'] .= "<br style=\"clear: both\" />\n\n";
    }
    if ($i % $params['cols'] != 0) $retVal['content'] .= "<br style=\"clear: both\" />\n\n";
    $retVal['content'] .= "</div>\n";
    if (!$params['hideCred'])    $retVal['content'] .= "<span class=\"fpfcredit\">" . sprintf(__("Generated by %s", 'facebook-photo-fetcher'), "<i>Facebook Photo Fetcher 2</i>") . "</span>\n";

    $retVal['content'] .= "<!-- End Album " . $aid . " -->\n";

    //Among many other things, WP5.0's Gutenberg editor breaks emojis coming from FB.  Fix them.
    $retVal['content'] = wp_encode_emoji($retVal['content']);

    return $retVal;
}
