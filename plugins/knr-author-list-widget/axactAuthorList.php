<?php
/*


-=[ Notes ]=-
	
	If the user hasn't set the custom sort order but has selected the Custom order
	option, we order the items by ID in ascending order.
	
	You might want your DBA to create an index on the axact_author_order field for better performance
*/

/*

-=[ Wish List ]=-

Make the following customizable
- Display name instead of First Name + Last Name
- gravatar size
- Dropdown CSS class

Add support for using AuthorPic as avatar

Change the widget options 'Show As Ordered List' and 'Show As Dropdown' into a dropdown list 
with the following options:
- Unordered list
- Ordered list
- Dropdown list

Create tutorials to show the possibilities when using Axact Author List (CSS 'tricks')

Use a cleaner way of passing parameters to functions. (array? array+extract? class? 
For an array, options array or new array?).
*/

//TODO: Make post-count optional


define('CR', "\n");
$loadedOptions = null;

$axactal_defaults = array(
	'title' => '',
	'includeAuthorsWoPosts' => false,
	'includeContributors' => false,
	'includeAdmin' => false,
	'includeEditors' => false,
	'authorLimit' => 0,
	'sort' => null,
	'sortReverse' => null,
	'unorderedListClass' => null,
	'listItemClass' => null,
	'spanCountClass' => null,
	'spanAuthorClass' => null,
	'authorLinkClass' => null,
	'showCount' => null,
	'countBeforeName' => null,
	'moreAuthorsLink' => null,
	'moreAuthorsText' => null,
	'showAvatarMode' => null,
	'showAsDropdown' => false,
	'dropdownUnselectedText' => '',
	'showAsOrderedList' => false,
	'noShowUrlFilter' => null
);

function axact_getAuthorListData(
    $aIncludeAuthorsWithoutPosts = true,    //affects the SQL
    $aIncludeEditors = true,                //affects the SQL
    $aIncludeAdministrators = true,         //affects the SQL
    $aAuthorLimit = -1,                     //affects the SQL
    $aIncludeContributors = true,           //affects the SQL
    $aOrderBy = null,                       //affects the SQL
    $aOrderReverse = false,                 //affects the SQL
	$behaviorShowMoreLink = false,			//affects the SQL (returns an extra row to determine if we should show the more link)
    $aNoPostCount = 0                       //Reserved for future use to perform query optimization
) {
    global $wpdb;

    $behaviorSqlJoinMode_Post = $aIncludeAuthorsWithoutPosts ? 'LEFT OUTER' : '';
    $behaviorSqlFilter_MatchEditors = $aIncludeEditors ? " OR capa.meta_value LIKE '%editor%'" : '';
    $behaviorSqlFilter_MatchAdmins = $aIncludeAdministrators ? " OR capa.meta_value LIKE '%admin%'" : '';
    $behaviorSqlFilter_MatchContribs = $aIncludeContributors ? " OR capa.meta_value LIKE '%contrib%'" : '';
    $includeIds						 = " AND usr.ID in (2,4,5)";
	$behaviorSqlFilter_Limit = $aAuthorLimit > 0 ? "LIMIT {$aAuthorLimit}" : '';
    $aAuthorLimitPlusOne = $aAuthorLimit + 1;
    $behaviorSqlFilter_LimitPlusOne = $aAuthorLimit > 0 ? "LIMIT {$aAuthorLimitPlusOne}" : '';
    //$behaviorShowMoreLink = ($aMoreAuthorsLink != '');
    if ($aAuthorLimit > 0 && (!$behaviorShowMoreLink)) $behaviorSqlFilter_LimitPlusOne = $behaviorSqlFilter_Limit;

    //Build the ORDER BY query for different sort options
    if ('fname' == $aOrderBy || 'lname' == $aOrderBy || 'post_count' == $aOrderBy || 'ID' == $aOrderBy || 'display_name' ==  $aOrderBy)
        $behaviorSqlSort = 'ORDER BY '.$aOrderBy;
    else if ('flname' == $aOrderBy)
        $behaviorSqlSort = 'ORDER BY fname, lname';
    else if ('lfname' == $aOrderBy)
        $behaviorSqlSort = 'ORDER BY lname, fname';
    else if ('custom' == $aOrderBy)
        $behaviorSqlSort = 'ORDER BY usr.axact_author_order, ID';
    if ('' != $behaviorSqlSort && 'custom' != $behaviorSqlSort) $behaviorSqlSort .= ($aOrderReverse ? ' DESC' : '');
    
    $userrows = $wpdb->get_results("
SELECT
usr.ID,
usr.user_email,
fnametab.meta_value AS fname,
lnametab.meta_value as lname,
cnt.post_count
FROM {$wpdb->users} usr
JOIN {$wpdb->usermeta} capa ON usr.ID = capa.user_id AND capa.meta_key LIKE '%capabilities'
LEFT OUTER JOIN {$wpdb->usermeta} fnametab ON usr.ID = fnametab.user_id AND fnametab.meta_key = 'first_name'
LEFT OUTER JOIN {$wpdb->usermeta} lnametab ON usr.ID = lnametab.user_id AND lnametab.meta_key = 'last_name'
{$behaviorSqlJoinMode_Post} JOIN (
    SELECT post_author, COUNT(1) post_count
    FROM {$wpdb->posts}
    WHERE post_type='post'
    AND post_status='publish'
    GROUP BY post_author
) cnt ON usr.ID = cnt.post_author
WHERE capa.meta_value LIKE '%author%'{$behaviorSqlFilter_MatchContribs}{$behaviorSqlFilter_MatchEditors}{$behaviorSqlFilter_MatchAdmins}{$includeIds}
{$behaviorSqlSort}
{$behaviorSqlFilter_LimitPlusOne}
    ");
	
    return (array) $userrows;    
}

function axact_getAvatar($aMode, $aId, $aSize) {
    if (1 == $aMode) {
        return get_avatar($aId, $aSize);
    } else {
        return '';
    }
}

function getAuthorInfo_ddl_axactAuthorList(
    $aIncludeAuthorsWithoutPosts = true,    //affects the SQL
    $aIncludeEditors = true,                //affects the SQL
    $aIncludeAdministrators = true,         //affects the SQL
    $aAuthorLimit = -1,                     //affects the SQL
    $aIncludeContributors = true,           //affects the SQL
    $aOrderBy = null,                       //affects the SQL
    $aOrderReverse = false,                 //affects the SQL
    $aUnorderedListClass = '',
    $aListItemClass = '',
    $aSpanCountClass = '',
    $aSpanAuthorClass = '',
    $aAuthorLinkClass = '',
    $aShowCount = true,
    $aCountBeforeName = false,
    $aMoreAuthorsLink = '',
    $aMoreAuthorsText = '...',
    $aShowAvatarMode = 0,
    $aDropdownUnselectedText = '',
    $aShowAsOrderedList = false
    ) {
    $behaviorShowCount = $aShowCount;
    $behaviorCountBeforeName = $aCountBeforeName;    
    
    $userrows_arr = axact_getAuthorListData($aIncludeAuthorsWithoutPosts, $aIncludeEditors, $aIncludeAdministrators, $aAuthorLimit, $aIncludeContributors, $aOrderBy, $aOrderReverse, ($aMoreAuthorsLink != ''), 0);

    $blogUrl = get_bloginfo('url');

    $axactAuthListDdl_Script = '<script>
    function axactAuthorListDdl() {
        mapper = new Object();        
    ';

    echo '<select id="axactAuthorListDdl" name="axactAuthorListDdl" onchange="axactAuthorListDdl();">';
    echo '<option value="" selected="selected">'.$aDropdownUnselectedText.'</option>';

	$behaviorShowMoreLink = ($aMoreAuthorsLink != '');    
    
    //loop over each of the rows in the result set
    for ($i = 0; $i < count($userrows_arr); $i++) {
        
        //We have an author limit set and we've reached it
        if ($aAuthorLimit > 0 && $i == $aAuthorLimit) {
            
            //if we're supposed to show the more link
            if ($behaviorShowMoreLink) {
                $axactAuthListDdl_Script .= 'mapper["more"] = "'. htmlspecialchars($aMoreAuthorsLink).'";
                ';
                            
                $moreMarkup = "
                    <option value=\"more\">
                    $aMoreAuthorsText
                    </option>
                ";
            
                //output the actual 'more' markup
                echo $moreMarkup;
            }
            
            break;
        }
        
        $row = $userrows_arr[$i];
        $row->post_count=$row->post_count?$row->post_count:0;
       // $authorUrl = $blogUrl . '/?author=' . $row->ID;
		//yumna
		$authorUrl = get_author_posts_url( $row->ID);
        //set the mapping for the Javascript
        $axactAuthListDdl_Script .= 'mapper["a' . $row->ID . '"] = "'. htmlspecialchars($authorUrl).'";
        ';
        
        //We shouldn't need this since the user must have entered a first name and last name, but just in case
        if ($row->lname == null && $row->fname == null) {
            $tempUser = get_userdata($row->ID);
            if ($tempUser->nickname != null)
                $row->fname = $tempUser->nickname;
            else
                $row->fname = $tempUser->user_login;
        }
        
        
        $authorNameMarkup = "
            {$row->fname} {$row->lname}
        ";
        
        
        $countMarkup='';
        if ($behaviorShowCount) {
            $countMarkup = '('.$row->post_count.')';
        }
        
        echo '
        <option value="'.$row->ID.'">'.
        ($behaviorCountBeforeName?($countMarkup.' '.$authorNameMarkup):($authorNameMarkup.' '.$countMarkup))
        .'</option>'.CR;
    }
    
    echo '</select>';
    $axactAuthListDdl_Script .= '
    theSelAuthor = document.getElementById("axactAuthorListDdl").value;
        if ("" != theSelAuthor) {
			if ("more" != theSelAuthor)
				theSelAuthor = "a"+theSelAuthor;
            location.href = mapper[theSelAuthor];
        }
    }</script>';
    echo $axactAuthListDdl_Script;
}

//Output the list items (LI tags)
function getAuthorInfo_axactAuthorList(
	$aIncludeAuthorsWithoutPosts = true,    //affects the SQL
	$aIncludeEditors = true,                //affects the SQL
	$aIncludeAdministrators = true,         //affects the SQL
	$aAuthorLimit = -1,                     //affects the SQL
	$aIncludeContributors = true,           //affects the SQL
	$aOrderBy = null,                       //affects the SQL
	$aOrderReverse = false,                 //affects the SQL
	$aUnorderedListClass = '',
	$aListItemClass = '',
	$aSpanCountClass = '',
	$aSpanAuthorClass = '',
	$aAuthorLinkClass = '',
	$aShowCount = true,
	$aCountBeforeName = false,
	$aMoreAuthorsLink = '',
	$aMoreAuthorsText = '...',
    $aShowAvatarMode = 0,
    $aDropdownUnselectedText = '',
    $aShowAsOrderedList = false
	) {

    if ($aShowAsOrderedList)    
	if (isset($aUnorderedListClass) && '' != $aUnorderedListClass)
	        echo '                        <ol class="'.$aUnorderedListClass.'">'.CR;
	else
	        echo '                        <ol>'.CR;
    else
	if (isset($aUnorderedListClass) && '' != $aUnorderedListClass)
	        echo '                        <ul class="'.$aUnorderedListClass.'">'.CR;
	else
        	echo '                        <ul>'.CR;
        
	$behaviorShowCount = $aShowCount;
	$behaviorCountBeforeName = $aCountBeforeName;	
	
	$markupListItemClass = '';
	if (isset($aListItemClass) && '' != $aListItemClass)
		$markupListItemClass = ' class="' . $aListItemClass . '"';

	$markupAuthorLinkClass = '';
	if (isset($aAuthorLinkClass) && '' != $aAuthorLinkClass)
		$markupAuthorLinkClass = ' class="' . $aAuthorLinkClass . '"';

	$markupSpanCountClass = '';
	if (isset($aSpanCountClass) && '' != $aSpanCountClass)
		$markupSpanCountClass = ' class="' . $aSpanCountClass . '"';

	$markupSpanAuthorClass = '';
	if (isset($aSpanAuthorClass) && '' != $aSpanAuthorClass)
		$markupSpanAuthorClass = ' class="' . $aSpanAuthorClass . '"';

	$userrows_arr = axact_getAuthorListData($aIncludeAuthorsWithoutPosts, $aIncludeEditors, $aIncludeAdministrators, $aAuthorLimit, $aIncludeContributors, $aOrderBy, $aOrderReverse, ($aMoreAuthorsLink != ''), 0);

    $blogUrl = get_bloginfo('url');
    $outerMoreLink = ''; //used for ordered lists

	$behaviorShowMoreLink = ($aMoreAuthorsLink != '');    
	
    //loop over each of the rows in the result set
	for ($i = 0; $i < count($userrows_arr); $i++) {
        
        //We have an author limit set and we've reached it
		if ($aAuthorLimit > 0 && $i == $aAuthorLimit) {
			
            //if we're supposed to show the more link
            if ($behaviorShowMoreLink) {			
				$authorNameMarkup = "
					<a href=\"$aMoreAuthorsLink\"{$markupAuthorLinkClass}>
					$aMoreAuthorsText
					</a>
				";
				if ('' != $markupSpanAuthorClass)
					$authorNameMarkup = '<span'.$markupSpanAuthorClass.'>'.$authorNameMarkup.'</span>';
				
				
				$countMarkup='';
			
                if ($aShowAsOrderedList)
                    $outerMoreLink = '<p>'.$authorNameMarkup.'</p>';
                else
            	    //output the actual 'more' markup
				    echo '
				    <li'.$markupListItemClass.'>'.
				    $authorNameMarkup
				    .'</li>'.CR;
                
			}
			
			break;
		}
		
		$row = $userrows_arr[$i];
	//foreach((array) $userrows as $row) {
		$row->post_count=$row->post_count?$row->post_count:0;
		//$authorUrl = $blogUrl . '/?author=' . $row->ID;
		$authorUrl = get_author_posts_url($row->ID);
        
		//We shouldn't need this since the user must have entered a first name and last name, but just in case
		if ($row->lname == null && $row->fname == null) {
			$tempUser = get_userdata($row->ID);
			if ($tempUser->nickname != null)
				$row->fname = $tempUser->nickname;
			else
				$row->fname = $tempUser->user_login;
		}
		
		
		$authorNameMarkup = "
			<a href=\"$authorUrl\" title=\"Posts by {$row->fname} {$row->lname}\"{$markupAuthorLinkClass}>
			{$row->fname} {$row->lname}
			</a>
		";
		if ('' != $markupSpanAuthorClass)
			$authorNameMarkup = '<span'.$markupSpanAuthorClass.'>'.$authorNameMarkup.'</span>';
		
		
		$countMarkup='';
		if ($behaviorShowCount) {
			$countMarkup = '('.$row->post_count.')';
			if ('' != $markupSpanCountClass)
				$countMarkup = '<span'.$markupSpanCountClass.'>'.$countMarkup.'</span>';
		}
		
        if (0 != $aShowAvatarMode)
        $avatarPic = axact_getAvatar($aShowAvatarMode, $row->user_email, 16);
        else
        $avatarPic = '';
        
		echo '
		<li'.$markupListItemClass.'>'.
        $avatarPic.
		($behaviorCountBeforeName?($countMarkup.' '.$authorNameMarkup):($authorNameMarkup.' '.$countMarkup))
		.'</li>'.CR;
	}
    
    if ($aShowAsOrderedList) {
    echo '                        </ol>'.CR;
    } else
    echo '                        </ul>'.CR;
}

//writes the unordered list tags and passes options to the getAuthorInfo_axactAuthorList function
function writeMarkup_axactAuthorList($options) {
	if ($options == null || count($options) == 0) {
		$options = getCurrentOptions_axactAuthorList(); //supporting legacy code
	}

    if (isset($options['showAsDropdown']) && $options['showAsDropdown'])
        getAuthorInfo_ddl_axactAuthorList(
            $options['includeAuthorsWoPosts'], 
            $options['includeEditors'], 
            $options['includeAdmin'], 
            isset($options['authorLimit'])?$options['authorLimit']:-1, 
            $options['includeContributors'], 
            $options['sort'], 
            $options['sortReverse'],
            $options['unorderedListClass'],
            $options['listItemClass'],
            $options['spanCountClass'],
            $options['spanAuthorClass'],
            $options['authorLinkClass'],
            $options['showCount'],
            $options['countBeforeName'],
            $options['moreAuthorsLink'],
            $options['moreAuthorsText'],
            $options['showAvatarMode'],
            $options['dropdownUnselectedText'],
            $options['showAsOrderedList']
            );
    else    
    	getAuthorInfo_axactAuthorList(
		    $options['includeAuthorsWoPosts'], 
		    $options['includeEditors'], 
		    $options['includeAdmin'], 
		    isset($options['authorLimit'])?$options['authorLimit']:-1, 
		    $options['includeContributors'], 
		    $options['sort'], 
		    $options['sortReverse'],
		    $options['unorderedListClass'],
		    $options['listItemClass'],
		    $options['spanCountClass'],
		    $options['spanAuthorClass'],
		    $options['authorLinkClass'],
		    $options['showCount'],
		    $options['countBeforeName'],
		    $options['moreAuthorsLink'],
		    $options['moreAuthorsText'],
            $options['showAvatarMode'],
            $options['dropdownUnselectedText'],
            $options['showAsOrderedList']        
		    );
}

//sets the default options
function getDefaultOptionArray_axactAuthorList() {
	$defaultTitle = 'Authors';
	$defaultIncludeAuthorsWoPosts = true;
	$defaultIncludeContributors = true;
	$defaultIncludeEditors = true;
	$defaultIncludeAdmin = false;
	$defaultAuthorLimit = -1;
	$defaultSort = 'none';
	$defaultSortReverse = false;
	$defaultUnorderedListClass = ''; //CSS class for UL tag below heading tag
	$defaultListItemClass = ''; //CSS class for LI tag for each author in the list
	$defaultSpanCountClass = ''; //CSS class for SPAN tag for the post-count in the list items
	$defaultSpanAuthorClass = ''; //CSS class for SPAN tag for each author in the list items
	$defaultAuthorLinkClass = ''; //CSS class for A HREF tag for each author in the list
	$defaultShowCount = true;
	$defaultCountBeforeName = false;
	$defaultMoreAuthorsLink = '';
    $defaultShowAvatarMode = 0;
    $defaultShowAsDropdown = false;
    $defaultDropdownUnselectedText = '';
    $defaultShowAsOrderedList = false;
    $defaultNoShowUrlFilter = '';

	return array('title'=>$defaultTitle,
			'includeAuthorsWoPosts'=>$defaultIncludeAuthorsWoPosts,
			'includeContributors'=>$defaultIncludeContributors,
			'includeAdmin'=>$defaultIncludeAdmin,
			'includeEditors'=>$defaultIncludeEditors,
			'authorLimit'=>$defaultAuthorLimit,
			'sort'=>$defaultSort,
			'sortReverse'=>$defaultSortReverse,
			'unorderedListClass'=>$defaultUnorderedListClass,
			'listItemClass'=>$defaultListItemClass,
			'spanCountClass'=>$defaultSpanCountClass,
			'spanAuthorClass'=>$defaultSpanAuthorClass,
			'authorLinkClass'=>$defaultAuthorLinkClass,
			'showCount'=>$defaultShowCount,
			'countBeforeName'=>$defaultCountBeforeName,
			'moreAuthorsLink'=>$defaultMoreAuthorsLink,
			'moreAuthorsText'=>$defaultMoreAuthorsText,
            'showAvatarMode'=>$defaultShowAvatarMode,
            'showAsDropdown'=>$defaultShowAsDropdown,
            'dropdownUnselectedText'=>$defaultDropdownUnselectedText,
            'showAsOrderedList'=>$defaultShowAsOrderedList,
			'noShowUrlFilter'=>$defaultNoShowUrlFilter
		);
}

//reads the plugin options
function getCurrentOptions_axactAuthorList() {
	if ($loadedOptions == null) { //prevent reading options more than once in the same request
		$options = get_option('widget_axactAuthorList');
		if ( !is_array($options) ) {
			$options = getDefaultOptionArray_axactAuthorList();
		}
		$loadedOptions = $options;
	}
	return $loadedOptions; //return $options;
}

/*
function axact_checkForSkip($pattern, $currUrl) {
	if (!$pattern) return false;	
	return ereg($pattern, $currUrl);
}
*/

/*
//outputs the 'skeleton' code
function widget_axactAuthorList($args) {
	$options = getCurrentOptions_axactAuthorList();	
	$retValSkip = axact_checkForSkip($options['noShowUrlFilter'], $_SERVER['REQUEST_URI']);
	if ($retValSkip === 1) {
		return;	
	}
	extract($args);
	echo $before_widget;
	echo $before_title;

	echo $options['title'];

	echo $after_title;
	writeMarkup_axactAuthorList($options);
	echo $after_widget;	
}
*/

//widget settings in Admin
/*no longer in use
 control_axactAuthorList() {
	$options = getCurrentOptions_axactAuthorList();
	
	if ($_POST["axactAuthorList-submit"]) {
		$options['title'] = strip_tags(stripslashes($_POST['axactAuthorList-title']));
		$options['includeAuthorsWoPosts'] = isset($_POST['axactAuthorList-incAuthorsWoPosts']);
		$options['includeContributors'] = isset($_POST['axactAuthorList-incContributors']);
		$options['includeAdmin'] = isset($_POST['axactAuthorList-incAdmin']);
		$options['includeEditors'] = isset($_POST['axactAuthorList-incEditors']);
		$options['authorLimit'] = sanitize_text_field( $_POST['axactAuthorList-authorLimit']);
		$options['sort'] = sanitize_text_field( $_POST['axactAuthorList-sort']);
		$options['sortReverse'] = isset($_POST['axactAuthorList-sortReverse']);
		$options['unorderedListClass'] = sanitize_html_class( $_POST['axactAuthorList-unorderedListClass']);
		$options['listItemClass'] = sanitize_html_class( $_POST['axactAuthorList-listItemClass']);
		$options['spanCountClass'] = sanitize_html_class(  $_POST['axactAuthorList-spanCountClass']);
		$options['spanAuthorClass'] = sanitize_html_class( $_POST['axactAuthorList-spanAuthorClass']);
		$options['authorLinkClass'] = sanitize_html_class( $_POST['axactAuthorList-authorLinkClass']);
		$options['showCount'] = isset($_POST['axactAuthorList-showCount']);
		$options['countBeforeName'] = isset($_POST['axactAuthorList-countBeforeName']);
		$options['moreAuthorsLink'] = sanitize_text_field( $_POST['axactAuthorList-moreAuthorsLink']);
		$options['moreAuthorsText'] = sanitize_text_field( $_POST['axactAuthorList-moreAuthorsText']);
        $options['showAvatarMode'] = sanitize_html_class( $_POST['axactAuthorList-showAvatarMode']);
        $options['showAsDropdown'] = isset($_POST['axactAuthorList-showAsDropdown']);
        $options['dropdownUnselectedText'] = sanitize_text_field( $_POST['axactAuthorList-dropdownUnselectedText']);
        $options['showAsOrderedList'] = isset($_POST['axactAuthorList-showAsOrderedList']);
        $options['noShowUrlFilter'] = sanitize_text_field($_POST['axactAuthorList-noShowUrlFilter']);        
		
		update_option('widget_axactAuthorList', $options);
	}
	
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
	$includeAuthorsWoPosts = $options['includeAuthorsWoPosts'];
	$includeContributors = $options['includeContributors'];
	$includeAdmin = $options['includeAdmin'];
	$includeEditors = $options['includeEditors'];
	$authorLimit = $options['authorLimit'];
	$sortOrder = $options['sort'];
	$sortOrderReverse = $options['sortReverse'];
	$unorderedListClass = $options['unorderedListClass'];
	$listItemClass = $options['listItemClass'];
	$spanCountClass = $options['spanCountClass'];
	$spanAuthorClass = $options['spanAuthorClass'];
	$authorLinkClass = $options['authorLinkClass'];
	$showCount = $options['showCount'];
	$countBeforeName = $options['countBeforeName'];
	$moreAuthorsLink = $options['moreAuthorsLink'];
	$moreAuthorsText = $options['moreAuthorsText'];
    $showAvatarMode = $options['showAvatarMode'];
    $showAsDropdown = $options['showAsDropdown'];
    $dropdownUnselectedText = $options['dropdownUnselectedText'];
    $showAsOrderedList = $options['showAsOrderedList'];
    $noShowUrlFilter = $options['noShowUrlFilter'];

	echo '<p style="font-weight: bold;">General Options</p>';

	
	echo '<p>
		<label for="axactAuthorList-title">' . __('Title:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-title" name="axactAuthorList-title" type="text" value="'.$title.'" />
		</p>';
	echo '<p>
		<label for="axactAuthorList-incAuthorsWoPosts">' . __('Include Authors With 0 Posts:') . '</label>
		<input id="axactAuthorList-incAuthorsWoPosts" name="axactAuthorList-incAuthorsWoPosts" type="checkbox"'.($includeAuthorsWoPosts?' checked="checked"':'').' />
		</p>';
	echo '<p>
		<label for="axactAuthorList-incContributors">' . __('Include Contributors:') . '</label>
		<input id="axactAuthorList-incContributors" name="axactAuthorList-incContributors" type="checkbox"'.($includeContributors?' checked="checked"':'').' />
		</p>';
	echo '<p>
		<label for="axactAuthorList-incAdmin">' . __('Include Administrators:') . '</label>
		<input id="axactAuthorList-incAdmin" name="axactAuthorList-incAdmin" type="checkbox"'.($includeAdmin?' checked="checked"':'').' />
		</p>';
	echo '<p>
		<label for="axactAuthorList-incEditors">' . __('Include Editors:') . '</label>
		<input id="axactAuthorList-incEditors" name="axactAuthorList-incEditors" type="checkbox"'.($includeEditors?' checked="checked"':'').' />
		</p>';
	echo '<p>
		<label for="axactAuthorList-authorLimit">' . __('Author Limit:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-authorLimit" name="axactAuthorList-authorLimit" type="text" value="'.$authorLimit.'" />
		<br />
		<small>Enter -1 or 0 for no limit</small>
		</p>';
	echo '<p>
		<label for="axactAuthorList-moreAuthorsLink">' . __('More Authors Link:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-moreAuthorsLink" name="axactAuthorList-moreAuthorsLink" type="text" value="'.$moreAuthorsLink.'" />
		<br />
		<small>Leave blank for no "more" link</small>
		</p>';
	echo '<p>
		<label for="axactAuthorList-moreAuthorsText">' . __('More Authors Text:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-moreAuthorsText" name="axactAuthorList-moreAuthorsText" type="text" value="'.$moreAuthorsText.'" />
		</p>';
    echo '<p>
        <label for="axactAuthorList-showAsOrderedList">' . __('Show As Ordered List:') . '</label>
        <input id="axactAuthorList-showAsOrderedList" name="axactAuthorList-showAsOrderedList" type="checkbox"'.($showAsOrderedList?' checked="checked"':'').' />
        </p>';
	echo '<p>
		<label for="axactAuthorList-sort">' . __('Sort with:') . '</label>
		<select name="axactAuthorList-sort" id="axactAuthorList-sort">
		<option value="fname"'.($sortOrder == 'fname'?' selected="selected"':'').'>First Name</option>
		<option value="lname"'.($sortOrder == 'lname'?' selected="selected"':'').'>Last Name</option>
		<option value="flname"'.($sortOrder == 'flname'?' selected="selected"':'').'>First & Last Name</option>
		<option value="lfname"'.($sortOrder == 'lfname'?' selected="selected"':'').'>Last & First Name</option>
		<option value="display_name"'.($sortOrder == 'display_name'?' selected="selected"':'').'>Display Name</option>
		<option value="post_count"'.($sortOrder == 'post_count'?' selected="selected"':'').'>No. of Posts</option>
		<option value="ID"'.($sortOrder == 'ID'?' selected="selected"':'').'>Author Registration Date</option>
		<option value="custom"'.($sortOrder == 'custom'?' selected="selected"':'').'>Custom</option>
		<option value="none"'.($sortOrder == 'none'?' selected="selected"':'').'>No Sorting</option>
		</select>
		<br />
		<small>With "Custom" sort order, you have to go to the "Author Order" setting page to manually set the sort order</small>
		</p>';
	echo '<p>
		<label for="axactAuthorList-sortReverse">' . __('Sort Reverse:') . '</label>
		<input id="axactAuthorList-sortReverse" name="axactAuthorList-sortReverse" type="checkbox"'.($sortOrderReverse?' checked="checked" ':'').' />
		<br />
		<small>Reverses the sort order set by "Sort with"</small>
		</p>';
    echo '<p>
        <label for="axactAuthorList-showAvatarMode">' . __('Show Avatar:') . '</label>
        <select name="axactAuthorList-showAvatarMode" id="axactAuthorList-showAvatarMode">
        <option value="0"'.($showAvatarMode == 0?' selected="selected"':'').'>None</option>
        <option value="1"'.($showAvatarMode == 1?' selected="selected"':'').'>Gravatars</option>
        </select>
        </p>';
    echo '<p>
        <label for="axactAuthorList-showAsDropdown">' . __('Show As Dropdown:') . '</label>
        <input id="axactAuthorList-showAsDropdown" name="axactAuthorList-showAsDropdown" type="checkbox"'.($showAsDropdown?' checked="checked"':'').' />
        </p>';
    echo '<p>
        <label for="axactAuthorList-dropdownUnselectedText">' . __('Dropdown Unselected Text:') . '</label>
        <input style="width: 200px;" id="axactAuthorList-dropdownUnselectedText" name="axactAuthorList-dropdownUnselectedText" type="text" value="'.$dropdownUnselectedText.'" />
        </p>';
	echo '<p>
		<label for="axactAuthorList-noShowUrlFilter">' . __('Do not show for URLs matching:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-noShowUrlFilter" name="axactAuthorList-noShowUrlFilter" type="text" value="'.$noShowUrlFilter.'" />
        </p>';

	echo '<p style="font-weight: bold;">Markup Options</p>';


	echo '<p>
		<label for="axactAuthorList-unorderedListClass">' . __('List Class:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-unorderedListClass" name="axactAuthorList-unorderedListClass" type="text" value="'.$unorderedListClass.'" />
		</p>';
	echo '<p>
		<label for="axactAuthorList-listItemClass">' . __('List Item Class:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-listItemClass" name="axactAuthorList-listItemClass" type="text" value="'.$listItemClass.'" />
		</p>';
	echo '<p>
		<label for="axactAuthorList-spanCountClass">' . __('Span Count Class:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-spanCountClass" name="axactAuthorList-spanCountClass" type="text" value="'.$spanCountClass.'" />
		</p>';
	echo '<p>
		<label for="axactAuthorList-spanAuthorClass">' . __('Span Author Class:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-spanAuthorClass" name="axactAuthorList-spanAuthorClass" type="text" value="'.$spanAuthorClass.'" />
		</p>';
	echo '<p>
		<label for="axactAuthorList-authorLinkClass">' . __('Author Link Class:') . '</label>
		<input style="width: 200px;" id="axactAuthorList-authorLinkClass" name="axactAuthorList-authorLinkClass" type="text" value="'.$authorLinkClass.'" />
		</p>';
	echo '<p>
		<label for="axactAuthorList-showCount">' . __('Show Count:') . '</label>
		<input id="axactAuthorList-showCount" name="axactAuthorList-showCount" type="checkbox"'.($showCount?' checked="checked"':'').' />
		</p>';
	echo '<p>
		<label for="axactAuthorList-countBeforeName">' . __('Display Count Before Name:') . '</label>
		<input id="axactAuthorList-countBeforeName" name="axactAuthorList-countBeforeName" type="checkbox"'.($countBeforeName?' checked="checked"':'').' />
		</p>';
	
	
	echo '<input type="hidden" id="axactAuthorList-submit" name="axactAuthorList-submit" value="1" />';
}

/*
//initializes the widget (ensure author ordering column is present)
function axactAuthorList_init() {
	global $wpdb;
	$wpdb->show_errors();

	$query1 = $wpdb->query("SHOW COLUMNS FROM $wpdb->users LIKE 'axact_author_order'");
	
	if ($query1 == 0) {
		$wpdb->query("ALTER TABLE $wpdb->users ADD `axact_author_order` INT( 4 ) NULL DEFAULT '0'");
	}
		
	register_sidebar_widget(__('Axact Author List'), 'widget_axactAuthorList');    
	register_widget_control(__('Axact Author List'), 'control_axactAuthorList', 250, 400);
}
*/

//Adds the custom author order page to the settings menu of the Admin
  function axactAuthorWidget_menu() {
	add_options_page('axactCustomAuthorSort', 'Axact Author List', 'manage_options', __FILE__, 'axactAuthorWidget_menufunc_caller');
		
	add_action('admin_init', 'axactAuthorWidget_menufunc');	
  }
 



  function axactAuthorWidget_menufunc_caller(){

  ?>
<h1>Sort</h1>	  
<?php 
	global $wpdb;
	$sqlAuthorListQuery = "
SELECT
usr.ID,
fnametab.meta_value AS fname,
lnametab.meta_value as lname
FROM {$wpdb->users} usr
JOIN {$wpdb->usermeta} capa ON usr.ID = capa.user_id AND capa.meta_key LIKE '%capabilities%'
LEFT OUTER JOIN {$wpdb->usermeta} fnametab ON usr.ID = fnametab.user_id AND fnametab.meta_key = 'first_name'
LEFT OUTER JOIN {$wpdb->usermeta} lnametab ON usr.ID = lnametab.user_id AND lnametab.meta_key = 'last_name'
WHERE capa.meta_value NOT LIKE '%subscribe%'
ORDER BY usr.axact_author_order, ID
	";	

	$authorRows = $wpdb->get_results($sqlAuthorListQuery);
	
    echo CR.'<p>If you require any assistance with this plugin, feel free to contact me on developer.yumna'.'@gmail.com
    or leave me a comment on my blog at http://www.axactsoft.com/ .</p>'.CR;
	echo CR.'<h1>Custom Author Ordering</h1>'.CR;
	echo '<small>This page is for only for ordering the authors manually. For other settings (and to set the sort order to "custom"), go to the Widgets page under Appearance, add the Axact Author List widget to your sidebar and click "Edit".</small>'.CR;
	echo '<p id="authorListInstructions">Please order the authors below by dragging &amp; dropping them at the desired position.</p>'.CR;
	
	echo '<ul id="author-list">'.CR;
    
    $blogUrl = get_bloginfo('url');
foreach((array) $authorRows as $row) {
		//$authorUrl = $blogUrl . '/?author=' . $row->ID;
		//yumna
		$authorUrl = get_author_posts_url( $row->ID);
		//We shouldn't need this since the user must have entered a first name and last name, but just in case
		if ($row->lname == null && $row->fname == null) {
			$tempUser = get_userdata($row->ID);
			if ($tempUser->nickname != null)
				$row->fname = $tempUser->nickname;
			else
				$row->fname = $tempUser->user_login;
		}		
		
		echo '<li id="listItem_'.$row->ID.'"><span id="et" class="handle" style="background-color: navy; color: white; width: 150px; display: block; padding: 0px 0px 0px 2px;">'.$row->fname.' '.$row->lname.'</span></li>'.CR;
	}	
	echo '</ul>'.CR;
	
	echo <<<STATUSDIV
	<div id="statusInfo"></div>
STATUSDIV;


 }
 
  //Admin menu for custom ordering of authors
  function axactAuthorWidget_menufunc() {
/*
* I would like to mention that Wil Linssen's tutorial was really helpful in creating 
* the drag-drop interface with jQuery UI Sortable for manually ordering the authors
*/
	global $wpdb;
	
$dirloc=dirname(__FILE__);
$dirloc=str_ireplace('\\', '/', $dirloc);
$dirloc='..'.substr($dirloc,stripos($dirloc,'/wp-content')).'/';



  }
  function axactAuthor_inline_script() {

	$dirloc=dirname(__FILE__);
	$dirloc=str_ireplace('\\', '/', $dirloc);
	$dirloc='..'.substr($dirloc,stripos($dirloc,'/wp-content')).'/';
	$admin_ajax_url = admin_url( 'admin-ajax.php' );
	
	$params = array(
	'dirloc' => $dirloc,
	'ajaxurl' => $admin_ajax_url
	);
	
	
    wp_enqueue_script( 'axact_sorting', plugin_dir_url( __FILE__ ) . 'js/axact_sorting.js', array('jquery'), '1.0' );
	wp_localize_script( 'axact_sorting', 'dirloc_var', $params );

	}
add_action( 'admin_enqueue_scripts', 'axactAuthor_inline_script' );


  
  function axactAuthorWidget_menu_js() {

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');	  
  }
  
  
//add_action("plugins_loaded", "axactAuthorList_init");
  add_action('admin_menu', 'axactAuthorWidget_menu');
  add_action('admin_menu', 'axactAuthorWidget_menu_js');
  add_action( 'wp_ajax_axactAuthorList_CustomSortSave', 'axactAuthorList_CustomSortSave' );
  
  function axactAuthorList_CustomSortSave(){
	  
	  global $wpdb;

foreach ($_GET['listItem'] as $position => $item) :

	if (filter_var($position, FILTER_VALIDATE_INT)!==false && filter_var($item, FILTER_VALIDATE_INT)!==false):
		$iterSql = "UPDATE $wpdb->users SET axact_author_order = $position WHERE ID = $item";
		$wpdb->query($iterSql);
	else:
		echo '<p>Invalid data. '."position: $position, item: $item".'</p>';
	endif;
endforeach;
echo 'Saved on '.date('r');
	  
  }

?>
