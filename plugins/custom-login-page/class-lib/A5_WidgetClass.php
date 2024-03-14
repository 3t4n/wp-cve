<?php

/**
 *
 * Class A5 Widget
 *
 * @ A5 Plugin Framework
 * Version: 1.0 beta 20160127
 *
 * Some standard functions for widgets
 *
 */

class A5_Widget extends WP_Widget {
	
	function page_checkgroup($instance, $solp = true) {
		
		$homepage = $instance['homepage'];
		$frontpage = $instance['frontpage'];
		$page = $instance['page'];
		$category = $instance['category'];
		$single = $instance['single'];
		$date = $instance['date'];
		$archive = $instance['archive'];
		$tag = $instance['tag'];
		$attachment = $instance['attachment'];
		$taxonomy = $instance['taxonomy'];
		$author = $instance['author'];
		$search = $instance['search'];
		$not_found = $instance['not_found'];
		if (true === $solp) $login_page = $instance['login_page'];
		
		$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
		$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
		
		$pages = array (
			array($base_id.'homepage', $base_name.'[homepage]', $homepage, __('Homepage', 'a5-recent-posts')),
			array($base_id.'frontpage', $base_name.'[frontpage]', $frontpage, __('Frontpage (e.g. a static page as homepage)', 'a5-recent-posts')),
			array($base_id.'page', $base_name.'[page]', $page, __('&#34;Page&#34; pages', 'a5-recent-posts')),
			array($base_id.'category', $base_name.'[category]', $category, __('Category pages', 'a5-recent-posts')),
			array($base_id.'single', $base_name.'[single]', $single, __('Single post pages', 'a5-recent-posts')),
			array($base_id.'date', $base_name.'[date]', $date, __('Archive pages', 'a5-recent-posts')),
			array($base_id.'archive', $base_name.'[archive]', $date, __('Post type archives', 'a5-recent-posts')),
			array($base_id.'tag', $base_name.'[tag]', $tag, __('Tag pages', 'a5-recent-posts')),
			array($base_id.'attachment', $base_name.'[attachment]', $attachment, __('Attachments', 'a5-recent-posts')),
			array($base_id.'taxonomy', $base_name.'[taxonomy]', $taxonomy, __('Custom Taxonomy pages (only available, if having a plugin)', 'a5-recent-posts')),
			array($base_id.'author', $base_name.'[author]', $author, __('Author pages', 'a5-recent-posts')),
			array($base_id.'search', $base_name.'[search]', $search, __('Search Results', 'a5-recent-posts')),
			array($base_id.'not_found', $base_name.'[not_found]', $not_found, __('&#34;Not Found&#34;', 'a5-recent-posts'))
		);
		
		if (true === $solp) $pages[] = array($base_id.'login_page', $base_name.'[login_page]', $login_page, __('Login Page (only available, if having a plugin)', 'a5-recent-posts')); 
		
		$checkall = array($base_id.'checkall', $base_name.'[checkall]', __('Check all', 'a5-recent-posts'));
		
		a5_checkgroup(false, false, $pages, __('Check, where you want to show the widget. By default, it is showing on the homepage and the category pages:', 'a5-recent-posts'), $checkall);
		
	}
	
	function check_output ($instance) {
	
		// get the type of page, we're actually on
	
		if (is_front_page()) $pagetype[]='frontpage';
		if (is_home()) $pagetype[]='homepage';
		if (is_page()) $pagetype[]='page';
		if (is_category()) $pagetype[]='category';
		if (is_single()) $pagetype[]='single';
		if (is_date()) $pagetype[]='date';
		if (is_archive()) $pagetype[]='archive';
		if (is_tag()) $pagetype[]='tag';
		if (is_attachment()) $pagetype[]='attachment';
		if (is_tax()) $pagetype[]='taxonomy';
		if (is_author()) $pagetype[]='author';
		if (is_search()) $pagetype[]='search';
		if (is_404()) $pagetype[]='not_found';
		if (!isset($pagetype)) $pagetype[]='login_page';
		
		// display only, if said so in the settings of the widget
		
		foreach ($pagetype as $page) if ($instance[$page]) $show_widget = true;
		
		return $show_widget;	
		
	}
	
	function textalign ($instance) {
		
		$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
		$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
		
		$options = array (array('none', __('Under image', 'a5-recent-posts')), array('right', __('Left of image', 'a5-recent-posts')), array('left', __('Right of image', 'a5-recent-posts')), array('notext', __('Don&#39;t show excerpt', 'a5-recent-posts')));
		
		a5_select($base_id.'alignment', $base_name.'[alignment]', $options, $instance['alignment'], __('Choose, whether or not to display the excerpt and whether it comes under the thumbnail or next to it.', 'a5-recent-posts'), false, array('space' => true));	
		
	}
	
	function select_heading ($instance, $alignment = false) {
		
		$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
		$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
		
		$headings = array(array('1', 'h1'), array('2', 'h2'), array('3', 'h3'), array('4', 'h4'), array('5', 'h5'), array('6', 'h6'));
		a5_select($base_id.'h', $base_name.'[h]', $headings, $instance['h'], __('Weight of the Post Title:', 'a5-recent-posts'), false, array('space' => true));
		
		if ($alignment == true) :
		
			$alignment = array (array('left', __('Left', 'a5-recent-posts')), array('right', __('Right', 'a5-recent-posts')), array('center', __('Center', 'a5-recent-posts')), array('justify', __('Justify', 'a5-recent-posts')));
			
			a5_select($base_id.'halign', $base_name.'[halign]', $alignment, $instance['halign'], __('How do you want to align the Post Title?', 'a5-recent-posts'), false, array('space' => true));
			
		endif;
		
	}
	
	function read_more($instance) {
		
		$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
		$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
		
		a5_checkbox($base_id.'readmore', $base_name.'[readmore]', $instance['readmore'], __('Check to have an additional &#39;read more&#39; link at the end of the excerpt.', 'a5-recent-posts'), array('space' => true));	
		a5_text_field($base_id.'rmtext', $base_name.'[rmtext]', $instance['rmtext'], sprintf(__('Write here some text for the &#39;read more&#39; link. By default, it is %s:', 'a5-recent-posts'), '[&#8230;]'), array('space' => true, 'class' => 'widefat'));
		a5_text_field($base_id.'rmclass', $base_name.'[rmclass]', $instance['rmclass'], __('If you want to style the &#39;read more&#39; link, you can enter a class here.', 'a5-recent-posts'), array('space' => true, 'class' => 'widefat'));
		
	}
	
} // A5_Widget

?>