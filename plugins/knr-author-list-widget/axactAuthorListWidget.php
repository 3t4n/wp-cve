<?php
/*
Plugin Name: Axact Author List
Description: Lists the authors, contributors, editors, and administrators for the blog.
Author: Yamna Tatheer
Author URI: https://theemeraldtech.com/
Version: 3.1.1
Plugin URI: https://theemeraldtech.com/
*/
include(dirname(__FILE__).'/'.'axactAuthorList.php');

class axactAuthorListWidget extends WP_Widget {
	public function __construct() {
		parent::WP_Widget(false, 'Axact Author List');
		$this->axact_checkDbSchema();
	}
	
	private function axact_checkDbSchema() {
	//Copied across from axactAuthorList_init
		global $wpdb;
		$wpdb->show_errors();

		$query1 = $wpdb->query("SHOW COLUMNS FROM $wpdb->users LIKE 'axact_author_order'");
		
		if ($query1 == 0) {
			$wpdb->query("ALTER TABLE $wpdb->users ADD `axact_author_order` INT( 4 ) NULL DEFAULT '0'");
		}	
	}
	
	private function axact_checkForSkip($pattern, $currUrl) {
		if (!$pattern) return false;	
		return ereg($pattern, $currUrl);
	}
	
	public function widget($args, $instance) {
		if (!isset($axactal_defaults)) global $axactal_defaults;
		$instance = wp_parse_args($instance, $axactal_defaults);

		$options = $instance;
		//if ($instance == null || count($instance) == 0) {
		//	$options = get_option('widget_axactAuthorList'); //supporting legacy code
		//}
		$retValSkip = $this->axact_checkForSkip($options['noShowUrlFilter'], $_SERVER['REQUEST_URI']);
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
	
	public function update($new_instance, $old_instance) {		
		if (!isset($axactal_defaults)) global $axactal_defaults;
		
		$instance = wp_parse_args($old_instance, $axactal_defaults);

		foreach($instance as $i_key => $i_val) {
			if (is_bool($instance[$i_key]))
				$instance[$i_key] = isset($new_instance[$i_key])?true:false;
			else
				$instance[$i_key] = isset($new_instance[$i_key])?$new_instance[$i_key]:null;
		}
		
		return $instance;      	
	}
	
	public function form($instance) {
		//if ($instance == null || count($instance) == 0) {
		//	$instance = get_option('widget_axactAuthorList'); //supporting legacy code
		//}
		if (!isset($axactal_defaults)) global $axactal_defaults;
		
		$instance = wp_parse_args($instance, $axactal_defaults);
		
		//validate that its a text
		$title = sanitize_text_field($instance['title']);
		$title = esc_attr(htmlspecialchars($title, ENT_QUOTES) );
		$title_fieldId = $this->get_field_id('title');
		$title_fieldName = $this->get_field_name('title');
		
		//validate that its an int
		$includeAuthorsWoPosts = intval($instance['includeAuthorsWoPosts']);
		$includeAuthorsWoPosts = esc_attr(htmlspecialchars($includeAuthorsWoPosts, ENT_QUOTES) );
		$includeAuthorsWoPosts_fieldId = $this->get_field_id('includeAuthorsWoPosts');
		$includeAuthorsWoPosts_fieldName = $this->get_field_name('includeAuthorsWoPosts');
		
		//validate that its an int
		$includeContributors = intval($instance['includeContributors']);
		$includeContributors = esc_attr(htmlspecialchars($includeContributors, ENT_QUOTES) );
		$includeContributors_fieldId = $this->get_field_id('includeContributors');
		$includeContributors_fieldName = $this->get_field_name('includeContributors');

		//validate that its an int
		$includeAdmin = intval($instance['includeAdmin']);
		$includeAdmin = esc_attr(htmlspecialchars($includeAdmin, ENT_QUOTES) );
		$includeAdmin_fieldId = $this->get_field_id('includeAdmin');
		$includeAdmin_fieldName = $this->get_field_name('includeAdmin');
		
		//validate that its an int
		$includeEditors = intval($instance['includeEditors']);		
		$includeEditors = esc_attr(htmlspecialchars($includeEditors, ENT_QUOTES) );
		$includeEditors_fieldId = $this->get_field_id('includeEditors');
		$includeEditors_fieldName = $this->get_field_name('includeEditors');

		//validate that its an int
		$authorLimit = intval($instance['authorLimit']);				
		$authorLimit = esc_attr(htmlspecialchars($authorLimit, ENT_QUOTES));
		$authorLimit_fieldId = $this->get_field_id('authorLimit');
		$authorLimit_fieldName = $this->get_field_name('authorLimit');

		//validate that its text
		$sortOrder = sanitize_text_field($instance['sort']);			
		$sortOrder = esc_attr(htmlspecialchars($sortOrder, ENT_QUOTES) );
		$sortOrder_fieldId = $this->get_field_id('sort');
		$sortOrder_fieldName = $this->get_field_name('sort');

		//validate that its text
		$sortOrderReverse = sanitize_text_field($instance['sortReverse']);		
		$sortOrderReverse = esc_attr(htmlspecialchars($sortOrderReverse, ENT_QUOTES) );
		$sortOrderReverse_fieldId = $this->get_field_id('sortReverse');
		$sortOrderReverse_fieldName = $this->get_field_name('sortReverse');

		//validate that its text
		$unorderedListClass = sanitize_text_field($instance['unorderedListClass']);	
		$unorderedListClass = esc_attr(htmlspecialchars($unorderedListClass, ENT_QUOTES) );
		$unorderedListClass_fieldId = $this->get_field_id('unorderedListClass');
		$unorderedListClass_fieldName = $this->get_field_name('unorderedListClass');

		//validate that its text
		$listItemClass = sanitize_text_field($instance['listItemClass']);		
		$listItemClass = esc_attr(htmlspecialchars($listItemClass, ENT_QUOTES) );
		$listItemClass_fieldId = $this->get_field_id('listItemClass');
		$listItemClass_fieldName = $this->get_field_name('listItemClass');
		
		//validate that its text
		$spanCountClass = sanitize_text_field($instance['spanCountClass']);		
		$spanCountClass = esc_attr(htmlspecialchars($spanCountClass, ENT_QUOTES) );
		$spanCountClass_fieldId = $this->get_field_id('spanCountClass');
		$spanCountClass_fieldName = $this->get_field_name('spanCountClass');

		//validate that its text
		$spanAuthorClass = sanitize_text_field($instance['spanAuthorClass']);				
		$spanAuthorClass = esc_attr(htmlspecialchars($spanAuthorClass, ENT_QUOTES) );
		$spanAuthorClass_fieldId = $this->get_field_id('spanAuthorClass');
		$spanAuthorClass_fieldName = $this->get_field_name('spanAuthorClass');

		//validate that its text
		$authorLinkClass= sanitize_text_field($instance['authorLinkClass']);				
		$authorLinkClass = esc_attr(htmlspecialchars($authorLinkClass, ENT_QUOTES) );
		$authorLinkClass_fieldId = $this->get_field_id('authorLinkClass');
		$authorLinkClass_fieldName = $this->get_field_name('authorLinkClass');

		//validate that its text
		$showCount= sanitize_text_field($instance['showCount']);
		$showCount = esc_attr(htmlspecialchars($showCount, ENT_QUOTES));
		$showCount_fieldId = $this->get_field_id('showCount');
		$showCount_fieldName = $this->get_field_name('showCount');

		//validate that its text
		$countBeforeName= sanitize_text_field($instance['countBeforeName']);		
		$countBeforeName = esc_attr(htmlspecialchars($countBeforeName, ENT_QUOTES));
		$countBeforeName_fieldId = $this->get_field_id('countBeforeName');
		$countBeforeName_fieldName = $this->get_field_name('countBeforeName');
		
		//validate that its text
		$moreAuthorsLink= sanitize_text_field($instance['moreAuthorsLink']);	
		$moreAuthorsLink = esc_attr(htmlspecialchars($moreAuthorsLink, ENT_QUOTES));
		$moreAuthorsLink_fieldId = $this->get_field_id('moreAuthorsLink');
		$moreAuthorsLink_fieldName = $this->get_field_name('moreAuthorsLink');

		//validate that its text
		$moreAuthorsText= sanitize_text_field($instance['moreAuthorsText']);	
		$moreAuthorsText = esc_attr(htmlspecialchars($moreAuthorsText, ENT_QUOTES));
		$moreAuthorsText_fieldId = $this->get_field_id('moreAuthorsText');
		$moreAuthorsText_fieldName = $this->get_field_name('moreAuthorsText');

		//validate that its number
		$showAvatarMode= intval($instance['showAvatarMode']);
		$showAvatarMode = esc_attr(htmlspecialchars($showAvatarMode, ENT_QUOTES));
		$showAvatarMode_fieldId = $this->get_field_id('showAvatarMode');
		$showAvatarMode_fieldName = $this->get_field_name('showAvatarMode');

		//validate that its number
		$showAsDropdown= intval($instance['showAsDropdown']);		
		$showAsDropdown = esc_attr(htmlspecialchars($showAsDropdown, ENT_QUOTES));
		$showAsDropdown_fieldId = $this->get_field_id('showAsDropdown');
		$showAsDropdown_fieldName = $this->get_field_name('showAsDropdown');

		//validate that its text
		$dropdownUnselectedText= sanitize_text_field($instance['dropdownUnselectedText']);
		$dropdownUnselectedText = esc_attr(htmlspecialchars($dropdownUnselectedText, ENT_QUOTES));
		$dropdownUnselectedText_fieldId = $this->get_field_id('dropdownUnselectedText');
		$dropdownUnselectedText_fieldName = $this->get_field_name('dropdownUnselectedText');

		//validate that its number
		$showAsOrderedList= intval($instance['showAsOrderedList']);
		$showAsOrderedList = esc_attr(htmlspecialchars($showAsOrderedList, ENT_QUOTES));
		$showAsOrderedList_fieldId = $this->get_field_id('showAsOrderedList');
		$showAsOrderedList_fieldName = $this->get_field_name('showAsOrderedList');

		//validate that its text
		$noShowUrlFilter= sanitize_text_field($instance['noShowUrlFilter']);
		$noShowUrlFilter = esc_url(htmlspecialchars($noShowUrlFilter, ENT_QUOTES));
		$noShowUrlFilter_fieldId = $this->get_field_id('noShowUrlFilter');
		$noShowUrlFilter_fieldName = $this->get_field_name('noShowUrlFilter');
		
			//validate that its text
		$exclude_ids= sanitize_text_field($instance['exclude_ids']);
		$exclude_ids = esc_attr(htmlspecialchars($exclude_ids, ENT_QUOTES));
		$exclude_ids = $this->get_field_id('exclude_ids');
		$exclude_ids = $this->get_field_name('exclude_ids');

		

	echo '<p style="font-weight: bold;">General Options</p>';
	
	echo '<p>
		<label for="'.$title_fieldId.'">' . __('Title:') . '</label>
		<input style="width: 200px;" id="'.$title_fieldId.'" name="'.$title_fieldName.'" type="text" value="'.$title.'" />
		</p>';
		
	echo '<p>
		<label for="'.$includeAuthorsWoPosts_fieldId.'">' . __('Include Authors With 0 Posts:') . '</label>
		<input id="'.$includeAuthorsWoPosts_fieldId.'" name="'.$includeAuthorsWoPosts_fieldName.'" type="checkbox"'.($includeAuthorsWoPosts?' checked="checked"':'').' />
		</p>';
		
	echo '<p>
		<label for="'.$includeContributors_fieldId.'">' . __('Include Contributors:') . '</label>
		<input id="'.$includeContributors_fieldId.'" name="'.$includeContributors_fieldName.'" type="checkbox"'.($includeContributors?' checked="checked"':'').' />
		</p>';

	echo '<p>
		<label for="'.$includeAdmin_fieldId.'">' . __('Include Administrators:') . '</label>
		<input id="'.$includeAdmin_fieldId.'" name="'.$includeAdmin_fieldName.'" type="checkbox"'.($includeAdmin?' checked="checked"':'').' />
		</p>';
		
	echo '<p>
		<label for="'.$includeEditors_fieldId.'">' . __('Include Editors:') . '</label>
		<input id="'.$includeEditors_fieldId.'" name="'.$includeEditors_fieldName.'" type="checkbox"'.($includeEditors?' checked="checked"':'').' />
		</p>';

	echo '<p>
		<label for="'.$authorLimit_fieldId.'">' . __('Author Limit:') . '</label>
		<input style="width: 200px;" id="'.$authorLimit_fieldId.'" name="'.$authorLimit_fieldName.'" type="text" value="'.$authorLimit.'" />
		<br />
		<small>Enter -1 or 0 for no limit</small>
		</p>';

	echo '<p>
		<label for="'.$moreAuthorsLink_fieldId.'">' . __('More Authors Link:') . '</label>
		<input style="width: 200px;" id="'.$moreAuthorsLink_fieldId.'" name="'.$moreAuthorsLink_fieldName.'" type="text" value="'.$moreAuthorsLink.'" />
		<br />
		<small>Leave blank for no "more" link</small>
		</p>';

	echo '<p>
		<label for="'.$moreAuthorsText_fieldId.'">' . __('More Authors Text:') . '</label>
		<input style="width: 200px;" id="'.$moreAuthorsText_fieldId.'" name="'.$moreAuthorsText_fieldName.'" type="text" value="'.$moreAuthorsText.'" />
		</p>';

    echo '<p>
        <label for="'.$showAsOrderedList_fieldId.'">' . __('Show As Ordered List:') . '</label>
        <input id="'.$showAsOrderedList_fieldId.'" name="'.$showAsOrderedList_fieldName.'" type="checkbox"'.($showAsOrderedList?' checked="checked"':'').' />
        </p>';
		
	echo '<p>
		<label for="'.$sortOrder_fieldId.'">' . __('Sort with:') . '</label>
		<select name="'.$sortOrder_fieldName.'" id="'.$sortOrder_fieldId.'">
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
		<small>With "Custom" sort order, you have to go to the "Axact Author List" setting page to manually set the sort order</small>
		</p>';
		
	echo '<p>
		<label for="'.$sortOrderReverse_fieldId.'">' . __('Sort Reverse:') . '</label>
		<input id="'.$sortOrderReverse_fieldId.'" name="'.$sortOrderReverse_fieldName.'" type="checkbox"'.($sortOrderReverse?' checked="checked" ':'').' />
		<br />
		<small>Reverses the sort order set by "Sort with"</small>
		</p>';
		
    echo '<p>
        <label for="'.$showAvatarMode_fieldId.'">' . __('Show Avatar:') . '</label>
        <select name="'.$showAvatarMode_fieldName.'" id="'.$showAvatarMode_fieldId.'">
        <option value="0"'.($showAvatarMode == 0?' selected="selected"':'').'>None</option>
        <option value="1"'.($showAvatarMode == 1?' selected="selected"':'').'>Gravatars</option>
        </select>
        </p>';
		
    echo '<p>
        <label for="'.$showAsDropdown_fieldId.'">' . __('Show As Dropdown:') . '</label>
        <input id="'.$showAsDropdown_fieldId.'" name="'.$showAsDropdown_fieldName.'" type="checkbox"'.($showAsDropdown?' checked="checked"':'').' />
        </p>';
		
    echo '<p>
        <label for="'.$dropdownUnselectedText_fieldId.'">' . __('Dropdown Unselected Text:') . '</label>
        <input style="width: 200px;" id="'.$dropdownUnselectedText_fieldId.'" name="'.$dropdownUnselectedText_fieldName.'" type="text" value="'.$dropdownUnselectedText.'" />
        </p>';
		
	echo '<p>
		<label for="'.$noShowUrlFilter_fieldId.'">' . __('Do not show for URLs matching:') . '</label>
		<input style="width: 200px;" id="'.$noShowUrlFilter_fieldId.'" name="'.$noShowUrlFilter_fieldName.'" type="text" value="'.$noShowUrlFilter.'" />
        </p>';

	echo '<p style="font-weight: bold;">Markup Options</p>';


	echo '<p>
		<label for="'.$unorderedListClass_fieldId.'">' . __('List Class:') . '</label>
		<input style="width: 200px;" id="'.$unorderedListClass_fieldId.'" name="'.$unorderedListClass_fieldName.'" type="text" value="'.$unorderedListClass.'" />
		</p>';
		
	echo '<p>
		<label for="'.$listItemClass_fieldId.'">' . __('List Item Class:') . '</label>
		<input style="width: 200px;" id="'.$listItemClass_fieldId.'" name="'.$listItemClass_fieldName.'" type="text" value="'.$listItemClass.'" />
		</p>';
		
	echo '<p>
		<label for="'.$spanCountClass_fieldId.'">' . __('Span Count Class:') . '</label>
		<input style="width: 200px;" id="'.$spanCountClass_fieldId.'" name="'.$spanCountClass_fieldName.'" type="text" value="'.$spanCountClass.'" />
		</p>';
		
	echo '<p>
		<label for="'.$spanAuthorClass_fieldId.'">' . __('Span Author Class:') . '</label>
		<input style="width: 200px;" id="'.$spanAuthorClass_fieldId.'" name="'.$spanAuthorClass_fieldName.'" type="text" value="'.$spanAuthorClass.'" />
		</p>';
		
	echo '<p>
		<label for="'.$authorLinkClass_fieldId.'">' . __('Author Link Class:') . '</label>
		<input style="width: 200px;" id="'.$authorLinkClass_fieldId.'" name="'.$authorLinkClass_fieldName.'" type="text" value="'.$authorLinkClass.'" />
		</p>';
		
	echo '<p>
		<label for="'.$showCount_fieldId.'">' . __('Show Count:') . '</label>
		<input id="'.$showCount_fieldId.'" name="'.$showCount_fieldName.'" type="checkbox"'.($showCount?' checked="checked"':'').' />
		</p>';
		
	echo '<p>
		<label for="'.$countBeforeName_fieldId.'">' . __('Display Count Before Name:') . '</label>
		<input id="'.$countBeforeName_fieldId.'" name="'.$countBeforeName_fieldName.'" type="checkbox"'.($countBeforeName?' checked="checked"':'').' />
		</p>';
	
	echo '<p>
		<label for="'.$exclude_ids.'">' . __('Exclude Ids:') . '</label>
		<input id="'.$exclude_ids.'" name="'.$exclude_ids.'" type="text"'.($exclude_ids?' checked="checked"':'').' />
		</p>';
	
	}
}


add_action( 'widgets_init', function(){ return register_widget("axactAuthorListWidget");} ); 
?>
