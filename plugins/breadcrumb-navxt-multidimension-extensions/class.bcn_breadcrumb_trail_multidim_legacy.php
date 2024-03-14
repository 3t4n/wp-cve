<?php
/*  Copyright 2011-2016  John Havlik  (email : john.havlik@mtekk.us)

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
require_once(dirname(__FILE__) . '/includes/block_direct_access.php');
class bcn_breadcrumb_trail_multidim extends bcn_breadcrumb_trail
{
	//Default constructor
	function __construct()
	{
		//Need to make sure we call the constructor of bcn_breadcrumb_trail
		parent::__construct();
	}
	/**
	 * A Breadcrumb Trail Filling Function
	 * 
	 * This recursive functions fills the trail with breadcrumbs for parent terms.
	 * @param int $id The id of the term.
	 * @param string $taxonomy The name of the taxonomy that the term belongs to
	 */
	function term_parents($id, $taxonomy)
	{
		global $post;
		//Get the current category object, filter applied within this call
		$term = get_term($id, $taxonomy);
		$suffix = '<ul>' . wp_list_categories('depth=1&parent=' . $term->parent . '&exclude=' . $id . '&echo=0&taxonomy=' . $taxonomy . '&show_option_none=bcn_multidim_oopse&title_li=') . '</ul>';
		//Hide empty enteries
		if(strpos($suffix, 'bcn_multidim_oopse') !== false)
		{
			$suffix = '';
		}
		//Place the breadcrumb in the trail, uses the constructor to set the title, template, and type, get a pointer to it in return
		$breadcrumb = $this->add(new bcn_breadcrumb($term->name, $this->opt['Htax_' . $taxonomy . '_template'] . $suffix, array('taxonomy', $taxonomy), get_term_link($term, $taxonomy), $id));
		//Make sure the id is valid, and that we won't end up spinning in a loop
		if($term->parent && $term->parent != $id)
		{
			//Figure out the rest of the term hiearchy via recursion
			$this->term_parents($term->parent, $taxonomy);
		}
	}
	/**
	 * A Breadcrumb Trail Filling Function
	 * 
	 * This function fills a breadcrumb for any taxonomy archive, was previously two separate functions. Was modified to output a list of related level terms.
	 * 
	 */
	function do_archive_by_term()
	{
		global $wp_query;
		//Simmilar to using $post, but for things $post doesn't cover
		$term = $wp_query->get_queried_object();
		$suffix = '<ul>' . wp_list_categories('depth=1&parent=' . $term->parent . '&exclude=' . $term->term_id . '&echo=0&taxonomy=' . $term->taxonomy . '&show_option_none=bcn_multidim_oopse&title_li=') . '</ul>';
		//Hide empty enteries
		if(strpos($suffix, 'bcn_multidim_oopse') !== false)
		{
			$suffix = '';
		}
		//Place the breadcrumb in the trail, uses the constructor to set the title, template, and type, get a pointer to it in return
		$breadcrumb = $this->add(new bcn_breadcrumb($term->name, $this->opt['Htax_' . $term->taxonomy . '_template_no_anchor'] . $suffix, array('archive', 'taxonomy', $term->taxonomy, 'current-item'), NULL, $term->term_id));
		//If we're paged, let's link to the first page
		if($this->opt['bcurrent_item_linked'] || (is_paged() && $this->opt['bpaged_display']))
		{
			$breadcrumb->set_template($this->opt['Htax_' . $term->taxonomy . '_template'] . $suffix);
			//Figure out the anchor for current category
			$breadcrumb->set_url(get_term_link($term, $term->taxonomy));
		}
		//Get parents of current category
		if($term->parent)
		{
			$this->term_parents($term->parent, $term->taxonomy);
		}
	}
	/**
	 * A Breadcrumb Trail Filling Function
	 * 
	 * This recursive functions fills the trail with breadcrumbs for parent posts/pages.
	 * @param int $id The id of the parent page.
	 * @param int $frontpage The id of the front page.
	 */
	function post_parents($id, $frontpage)
	{
		//Use WordPress API, though a bit heavier than the old method, this will ensure compatibility with other plug-ins
		$parent = get_post($id);
		$suffix = '<ul>' . wp_list_pages('depth=1&child_of=' . $parent->post_parent . '&exclude=' . $parent->ID . '&echo=0&title_li=') . '</ul>';
		//Hide empty enteries
		if($suffix === '<ul></ul>')
		{
			$suffix = '';
		}
		//Place the breadcrumb in the trail, uses the constructor to set the title, template, and type, get a pointer to it in return
		$breadcrumb = $this->add(new bcn_breadcrumb(get_the_title($id), $this->opt['Hpost_' . $parent->post_type . '_template'] . $suffix, array('post', 'post-' . $parent->post_type), get_permalink($id), $id));
		//Make sure the id is valid, and that we won't end up spinning in a loop
		if($parent->post_parent >= 0 && $parent->post_parent != false && $id != $parent->post_parent && $frontpage != $parent->post_parent)
		{
			//If valid, recursively call this function
			$this->post_parents($parent->post_parent, $frontpage);
		}
	}
    /**
     * A Breadcrumb Trail Filling Function
     * 
     * This functions fills a breadcrumb for posts
     * 
     * @param $post WP_Post Instance of WP_Post object to create a breadcrumb for
     */
    protected function do_post(WP_Post $post)
    {
		global $page;
		$suffix = '';
		if(is_post_type_hierarchical($post->post_type))
		{
			$suffix = '<ul>' . wp_list_pages('depth=1&child_of=' . $post->post_parent . '&exclude=' . $post->ID . '&echo=0&title_li=') . '</ul>';
			//Hide empty enteries
			if($suffix === '<ul></ul>')
			{
				$suffix = '';
			}
		}
		//Place the breadcrumb in the trail, uses the bcn_breadcrumb constructor to set the title, template, and type
		$breadcrumb = $this->add(new bcn_breadcrumb(get_the_title($post), $this->opt['Hpost_' . $post->post_type . '_template_no_anchor'] . $suffix, array('post', 'post-' . $post->post_type, 'current-item'), NULL, $post->ID));
		//If the current item is to be linked, or this is a paged post, add in links
		if($this->opt['bcurrent_item_linked'] || ($page > 1 && $this->opt['bpaged_display']))
		{
			//Change the template over to the normal, linked one
			$breadcrumb->set_template($this->opt['Hpost_' . $post->post_type . '_template'] . $suffix);
			//Add the link
			$breadcrumb->set_url(get_permalink($post));
		}
		//If we have page, force it to go through the parent tree
		if($post->post_type === 'page')
		{
			//Done with the current item, now on to the parents
			$frontpage = get_option('page_on_front');
			//If there is a parent page let's find it
			if($post->post_parent && $post->ID != $post->post_parent && $frontpage != $post->post_parent)
			{
				$this->post_parents($post->post_parent, $frontpage);
			}
		}
		//Otherwise we need the follow the hiearchy tree
		else
		{
			//Handle the post's hiearchy
			$this->post_hierarchy($post->ID, $post->post_type, $post->post_parent);
		}
	}
}