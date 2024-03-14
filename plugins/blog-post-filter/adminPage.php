<?php
class BlogPostFilterAdminPage{
	function BlogPostFilterAdminPage(){
		add_action('admin_init', array(&$this, 'registerOptionSetting'));

		add_posts_page (
			__('post filtering', 'blog-post-filter'), //string $page_title,
			__('post filtering', 'blog-post-filter'), //string $menu_title,
			'manage_options', 			//string $capability,
			'blog-post-filter-setting', //string $menu_slug,
			array(&$this, 'setting') //callback $function = ''
			);
	}

	private $optionValues;

	function showCategories($parentid = ''){
		$categories = get_categories(array('parent' => $parentid, 'hide_empty' => 0));
		if(count($categories)<1) return;

		echo '<ol>';
			foreach($categories as $category){
				echo '<li>';
					echo '<input type="checkbox" name="blogPostFilterCategories['.$category->cat_ID.']" id="blogPostFilterCategories_'.$category->cat_ID.'" value="1" '.($this->optionValues[$category->cat_ID]=='0'? '': 'checked').' >';
					echo '<label for="blogPostFilterCategories_'.$category->cat_ID.'">' . $category->name;
					echo '('.$category->category_count.')' . '</label>';
					$this->showCategories($category->cat_ID);
				echo '</li>';
			}
		echo '</ol>';
	}

	function showFilterStickyPost() {
		$filterSticky = get_option('blogPostFilterStickyPosts');
		$checkedYes = ($filterSticky == 1)? 'checked': '';
		$checkedNo  = ($filterSticky == 0)? 'checked': '';
		echo '<p>';
			echo '<span>'.__('Would you like to filter sticky posts', 'blog-post-filter').'?</span>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<input type="radio" name="blogPostFilterStickyPosts" value="1" id="bpfsp_yes" ' .$checkedYes. ' />';
			echo '<label for="bpfsp_yes">' .__('Yes'). '</label>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<input type="radio" name="blogPostFilterStickyPosts" value="0" id="bpfsp_no"  ' .$checkedNo.  ' />';
			echo '<label for="bpfsp_no">' .__('No').' ('. __('Default').') '. '</label>';
		echo '</p>';
	}

	function setting(){
		echo '<div class="wrap">';
		echo '<h1>'.__('Post Filtering', 'blog-post-filter').'</h1>';
		echo '<form method="post" action="options.php">';
			settings_fields('blog-post-filter-option-group');
			//do_settings_section('blog-post-filter-option-group');

			echo '<h2>'.__('Sticky Post Filtering', 'blog-post-filter').'</h2>';
			echo '<p>'.__('By default, sticky posts will not be filtered. Select "Yes" below, to filter them', 'blog-post-filter').'.</p>';
			$this->showFilterStickyPost();

			echo '<h2>'.__('Select Categories', 'blog-post-filter').'</h2>';
			echo '<p>'.__('Please select the categories that you would like show their posts on the front page', 'blog-post-filter').'.</p>';
			echo '<p>'.__('Only posts that are at least in one of selected categories will be shown on the front page', 'blog-post-filter').'.</p>';

				$this->optionValues = get_option('blogPostFilterCategories');

				$this->showCategories(0);

				submit_button();
			echo '</form>';
		echo '</div>';
	}

	function registerOptionSetting(){
		register_setting(
			'blog-post-filter-option-group',
			'blogPostFilterCategories',
			array(
				'sanitize_callback'=>array(&$this, 'validateOption') )
			);
		register_setting(
			'blog-post-filter-option-group',
			'blogPostFilterStickyPosts',
			array('default'=> '0')
			);
	}

	function validateOption($values){
		$input = array();
		$categories = get_categories(array('hide_empty' => 0));
		foreach($categories as $cat)
			$input[$cat->cat_ID] = ($values[$cat->cat_ID] == 1)? 1: 0;
		return $input;
	}

}
