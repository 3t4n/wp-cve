<?php
/*  Copyright 2011-2017 John Havlik  (email : john.havlik@mtekk.us)

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
class bcn_breadcrumb_trail_multidim_children extends bcn_breadcrumb_trail
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
	protected function term_parents($id, $taxonomy)
	{
		global $post;
		//Get the current category object, filter applied within this call
		$term = get_term($id, $taxonomy);
		//Assemble our wp_list_categories arguments, filter as well
		$args = apply_filters('bcn_multidim_term_children', 'depth=1&parent=' . $id . '&echo=0&taxonomy=' . $taxonomy . '&show_option_none=bcn_multidim_oopse&title_li=', $id, $taxonomy);
		$suffix = '<ul>' . wp_list_categories($args) . '</ul>';
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
	protected function do_archive_by_term()
	{
		global $wp_query;
		//Simmilar to using $post, but for things $post doesn't cover
		$term = $wp_query->get_queried_object();
		//Assemble our wp_list_categories arguments, filter as well
		$args = apply_filters('bcn_multidim_term_children', 'depth=1&parent=' . $term->term_id . '&echo=0&taxonomy=' . $term->taxonomy . '&show_option_none=bcn_multidim_oopse&title_li=', $term->term_id, $term->taxonomy);
		$suffix = '<ul>' . wp_list_categories($args) . '</ul>';
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
	protected function post_parents($id, $frontpage)
	{
		//Use WordPress API, though a bit heavier than the old method, this will ensure compatibility with other plug-ins
		$parent = get_post($id);
		//Assemble our wp_list_pages arguments, filter as well
		$args = apply_filters('bcn_multidim_post_children', 'depth=1&child_of=' . $id . '&exclude=' . $id . '&echo=0&title_li=', $id);
		$suffix = '<ul>' . wp_list_pages($args) . '</ul>';
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
    protected function do_post($post)
    {
		global $page;
		//If we did not get a WP_Post object, warn developer and return early
		if(!is_object($post) || get_class($post) !== 'WP_Post')
		{
			_doing_it_wrong(__CLASS__ . '::' . __FUNCTION__, __('$post global is not of type WP_Post', 'breadcrumb-navxt'), '5.1.1');
			return;
		}
		$suffix = '';
		if(is_post_type_hierarchical($post->post_type))
		{
			//Assemble our wp_list_pages arguments, filter as well
			$args = apply_filters('bcn_multidim_post_children', 'depth=1&child_of=' . $post->ID . '&exclude=' . $post->ID . '&echo=0&title_li=', $post->ID);
			$suffix = '<ul>' . wp_list_pages($args) . '</ul>';
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
	/**
	 * A Breadcrumb Trail Filling Function 
	 *
	 * Handles only the root page stuff for post types, including the "page for posts"
	 * 
	 * TODO: this still needs to be cleaned up
	 */
	protected function do_root()
	{
		global $post, $wp_query, $current_site;
		//If this is an attachment then we need to change the queried object to the parent post
		if(is_attachment())
		{
			$type = get_post($post->post_parent);
			//If the parent of the attachment is a page, exit early (works around bug where is_single() returns true for an attachment to a page)
			if($type->post_type == 'page')
			{
				return;
			}
		}
		else
		{
			//Simmilar to using $post, but for things $post doesn't cover
			$type = $wp_query->get_queried_object();
		}
		$root_id = -1;
		$type_str = '';
		//Find our type string and root_id
		$this->find_type($type, $type_str, $root_id);
		//We only need the "blog" portion on members of the blog, and only if we're in a static frontpage environment
		if($root_id > 1 || $this->opt['bblog_display'] && get_option('show_on_front') == 'page' && (is_home() || is_single() || is_tax() || is_category() || is_tag() || is_date()))
		{
			//If we entered here with a posts page, we need to set the id
			if($root_id < 0)
			{
				$root_id = get_option('page_for_posts');
			}
			$frontpage_id = get_option('page_on_front');
			//We'll have to check if this ID is valid, e.g. user has specified a posts page
			if($root_id && $root_id != $frontpage_id)
			{
				//Assemble our wp_list_pages arguments, filter as well
				$args = apply_filters('bcn_multidim_post_children', 'depth=1&child_of=' . $root_id . '&exclude=' . $root_id . '&echo=0&title_li=', $root_id);
				$suffix = '<ul>' . wp_list_pages($args) . '</ul>';
				//Hide empty enteries
				if($suffix === '<ul></ul>')
				{
					$suffix = '';
				}
				//Place the breadcrumb in the trail, uses the constructor to set the title, template, and type, we get a pointer to it in return
				$breadcrumb = $this->add(new bcn_breadcrumb(get_the_title($root_id), $this->opt['Hpost_' . $type_str . '_template_no_anchor'] . $suffix, array($type_str . '-root', 'post', 'post-' . $type_str), NULL, $root_id));
				//If we are at home, then we need to add the current item type
				if(is_home())
				{
					$breadcrumb->add_type('current-item');
				}
				//If we're not on the current item we need to setup the anchor
				if(!is_home() || (is_paged() && $this->opt['bpaged_display']) || (is_home() && $this->opt['bcurrent_item_linked']))
				{
					$breadcrumb->set_template($this->opt['Hpost_' . $type_str . '_template'] . $suffix);
					//Figure out the anchor for home page
					$breadcrumb->set_url(get_permalink($root_id));
				}
				//Done with the "root", now on to the parents
				//Get the blog page
				$bcn_post = get_post($root_id);
				//If there is a parent post let's find it
				if($bcn_post->post_parent && $bcn_post->ID != $bcn_post->post_parent && $frontpage_id != $bcn_post->post_parent)
				{
					$this->post_parents($bcn_post->post_parent, $frontpage_id);
				}
			}
		}
	}
	/**
	 * A Breadcrumb Trail Filling Function
	 * 
	 * This functions fills a breadcrumb for the home page.
	 */
	protected function do_home()
	{
		global $post, $current_site;
		//On everything else we need to link, but no current item (pre/suf)fixes
		if($this->opt['bhome_display'])
		{
			$frontpage_id = get_option('page_on_front');
			$suffix = '';
			//Assemble our wp_list_pages arguments, filter as well
			$args = apply_filters('bcn_multidim_post_children', 'depth=1&child_of=' . $frontpage_id . '&exclude=' . $frontpage_id . '&echo=0&title_li=', $frontpage_id);
			$suffix = '<ul>' . wp_list_pages($args) . '</ul>';
			//Hide empty enteries
			if($suffix === '<ul></ul>')
			{
				$suffix = '';
			}
			//Get the site name
			$site_name = get_option('blogname');
			//Place the breadcrumb in the trail, uses the constructor to set the title, prefix, and suffix, get a pointer to it in return
			$breadcrumb = $this->add(new bcn_breadcrumb($site_name, $this->opt['Hhome_template'] . $suffix, array('home'), get_home_url()));
			//If we have a multi site and are not on the main site we need to add a breadcrumb for the main site
			if($this->opt['bmainsite_display'] && !is_main_site())
			{
				//Get the site name
				$site_name = get_site_option('site_name');
				//Place the main site breadcrumb in the trail, uses the constructor to set the title, prefix, and suffix, get a pointer to it in return
				$breadcrumb = $this->add(new bcn_breadcrumb($site_name, $this->opt['Hmainsite_template'], array('main-home'), get_home_url($current_site->blog_id)));
			}
		}
	}
	/**
	 * A Breadcrumb Trail Filling Function
	 * 
	 * This functions fills a breadcrumb for the front page.
	 */
	protected function do_front_page()
	{
		global $post, $current_site;
		$suffix = '';
		if($this->opt['bhome_display_children'])
		{
			$frontpage_id = get_option('page_on_front');
			//Assemble our wp_list_pages arguments, filter as well
			$args = apply_filters('bcn_multidim_post_children', 'depth=1&child_of=' . $frontpage_id . '&exclude=' . $frontpage_id . '&echo=0&title_li=', $frontpage_id);
			$suffix = '<ul>' . wp_list_pages($args) . '</ul>';
			//Hide empty enteries
			if($suffix === '<ul></ul>')
			{
				$suffix = '';
			}
		}
		//Get the site name
		$site_name = get_option('blogname');
		//Place the breadcrumb in the trail, uses the constructor to set the title, prefix, and suffix, get a pointer to it in return
		$breadcrumb = $this->add(new bcn_breadcrumb($site_name, $this->opt['Hhome_template_no_anchor'] . $suffix, array('home', 'current-item')));
		//If we're paged, let's link to the first page
		if($this->opt['bcurrent_item_linked'] || (is_paged() && $this->opt['bpaged_display']))
		{
			$breadcrumb->set_template($this->opt['Hhome_template'] . $suffix);
			//Figure out the anchor for home page
			$breadcrumb->set_url(get_home_url());
		}
		//If we have a multi site and are not on the main site we may need to add a breadcrumb for the main site
		if($this->opt['bmainsite_display'] && !is_main_site())
		{
			//Get the site name
			$site_name = get_site_option('site_name');
			//Place the main site breadcrumb in the trail, uses the constructor to set the title, prefix, and suffix, get a pointer to it in return
			$breadcrumb = $this->add(new bcn_breadcrumb($site_name, $this->opt['Hmainsite_template'], array('main-home'), get_home_url($current_site->blog_id)));
		}
	}
}