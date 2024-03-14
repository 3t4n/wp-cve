<?php
/*  Copyright 2011-2020 John Havlik  (email : john.havlik@mtekk.us)

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
	 * 
	 * @return WP_Term The term we stopped at
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
		$breadcrumb = $this->add(new bcn_breadcrumb(
				$term->name,
				$this->opt['Htax_' . $taxonomy . '_template'] . $suffix,
				array('taxonomy', $taxonomy),
				get_term_link($term, $taxonomy),
				$term->term_id,
				true));
		//Make sure the id is valid, and that we won't end up spinning in a loop
		if($term->parent && $term->parent != $id)
		{
			//Figure out the rest of the term hiearchy via recursion
			//FIXME: Change to just passing in term instance (work for 7.0)
			$ret_term = $this->term_parents($term->parent, $taxonomy);
			//May end up with WP_Error, don't update the term if that's the case
			if($ret_term instanceof WP_Term)
			{
				$term = $ret_term;
			}
		}
		return $term;
	}
	/**
	 * A Breadcrumb Trail Filling Function
	 * 
	 * This function fills a breadcrumb for any taxonomy archive, was previously two separate functions. Was modified to output a list of related level terms.
	 * 
	 * @param WP_Term $term The term object to generate the breadcrumb for
	 * @param bool $is_paged Whether or not the current resource is on a page other than page 1
	 */
	protected function do_archive_by_term($term, $is_paged = false)
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
		$breadcrumb = $this->add(new bcn_breadcrumb(
				$term->name,
				$this->opt['Htax_' . $term->taxonomy . '_template_no_anchor'] . $suffix,
				array('archive', 'taxonomy', $term->taxonomy, 'current-item'),
				$this->maybe_add_post_type_arg(get_term_link($term), null, $term->taxonomy),
				$term->term_id));
		//If we're paged, let's link to the first page
		if($this->opt['bcurrent_item_linked'] || ($is_paged && $this->opt['bpaged_display']))
		{
			$breadcrumb->set_template($this->opt['Htax_' . $term->taxonomy . '_template'] . $suffix);
			//Add the link
			$breadcrumb->set_linked(true);
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
	 * 
	 * @return WP_Post The parent we stopped at
	 */
	protected function post_parents($id, $frontpage)
	{
		//Use WordPress API, though a bit heavier than the old method, this will ensure compatibility with other plug-ins
		$parent = get_post($id);
		//Only add the breadcrumb if it is non-private or we allow private posts in the breadcrumb trail
		if(apply_filters('bcn_show_post_private', get_post_status($parent) !== 'private', $parent->ID))
		{
			//Assemble our wp_list_pages arguments, filter as well
			$args = apply_filters('bcn_multidim_post_children', 'depth=1&child_of=' . $id . '&exclude=' . $id . '&echo=0&title_li=', $id);
			$suffix = '<ul>' . wp_list_pages($args) . '</ul>';
			//Hide empty enteries
			if($suffix === '<ul></ul>')
			{
				$suffix = '';
			}
			//Place the breadcrumb in the trail, uses the constructor to set the title, template, and type, get a pointer to it in return
			$breadcrumb = $this->add(new bcn_breadcrumb(
					get_the_title($id),
					$this->opt['Hpost_' . $parent->post_type . '_template'] . $suffix,
					array('post', 'post-' . $parent->post_type),
					get_permalink($id),
					$id,
					true));
		}
		//Make sure the id is valid, and that we won't end up spinning in a loop
		if($parent->post_parent >= 0 && $parent->post_parent != false && $id != $parent->post_parent && $frontpage != $parent->post_parent)
		{
			//If valid, recursively call this function
			$parent = $this->post_parents($parent->post_parent, $frontpage);
		}
		return $parent;
	}
    /**
     * A Breadcrumb Trail Filling Function
     * 
     * This functions fills a breadcrumb for posts
     * 
	 * @param WP_Post $post Instance of WP_Post object to create a breadcrumb for
	 * @param bool $force_link Whether or not to force this breadcrumb to be linked
	 * @param bool $is_paged Whether or not the current resource is on a page other than page 1
	 * @param bool $is_current_item Whether or not the breadcrumb being generated is the current item
     */
    protected function do_post($post, $force_link = false, $is_paged = false, $is_current_item = true)
    {
		//If we did not get a WP_Post object, warn developer and return early
		if(!($post instanceof WP_Post))
		{
			_doing_it_wrong(__CLASS__ . '::' . __FUNCTION__, __('$post global is not of type WP_Post', 'breadcrumb-navxt'), '5.1.1');
			return;
		}
		//If this is the current item or if we're allowing private posts in the trail add a breadcrumb
		if($is_current_item || apply_filters('bcn_show_post_private', get_post_status($post) !== 'private', $post->ID))
		{
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
			$breadcrumb = $this->add(new bcn_breadcrumb(
					get_the_title($post),
					$this->opt['Hpost_' . $post->post_type . '_template_no_anchor'] . $suffix,
					array('post', 'post-' . $post->post_type),
					get_permalink($post),
					$post->ID));
			if($is_current_item)
			{
				$breadcrumb->add_type('current-item');
			}
			//Under a couple of circumstances we will want to link this breadcrumb
			if($force_link || ($is_current_item && $this->opt['bcurrent_item_linked']) || ($is_paged && $this->opt['bpaged_display']))
			{
				//Change the template over to the normal, linked one
				$breadcrumb->set_template($this->opt['Hpost_' . $post->post_type . '_template'] . $suffix);
				//Add the link
				$breadcrumb->set_linked(true);
			}
		}
		//Done with the current item, now on to the parents
		$frontpage = get_option('page_on_front');
		//If we are to follow the hierarchy first (with hierarchy type backup), run through the post again
		if($this->opt['bpost_' . $post->post_type. '_hierarchy_parent_first'] && $post->post_parent > 0 && $post->ID != $post->post_parent && $frontpage != $post->post_parent)
		{
			//Get the parent's information
			$parent = get_post($post->post_parent);
			//Take care of the parent's breadcrumb
			$this->do_post($parent, true, false, false);
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
	 * @param string $type_str The type string variable
	 * @param int $root_id The ID for the post type root
	 * @param bool $is_paged Whether or not the current resource is on a page other than page 1
	 * @param bool $is_current_item Whether or not the breadcrumb being generated is the current item
	 */
	protected function do_root($type_str, $root_id, $is_paged = false, $is_current_item = true)
	{
		//Nothing to do for the page post type, exit early
		if($type_str === 'page')
		{
			return;
		}
		//Continue only if we have a valid root_id
		if(is_numeric($root_id))
		{
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
				$breadcrumb = $this->add(new bcn_breadcrumb(
						get_the_title($root_id),
						$this->opt['Hpost_' . $type_str . '_template_no_anchor'] . $suffix,
						array($type_str . '-root', 'post', 'post-' . $type_str),
						get_permalink($root_id),
						$root_id));
				//If we are at home, or any root page archive then we need to add the current item type
				if($is_current_item)
				{
					$breadcrumb->add_type('current-item');
				}
				//If we're not on the current item we need to setup the anchor
				if(!$is_current_item || ($is_current_item && $this->opt['bcurrent_item_linked']) || ($is_paged && $this->opt['bpaged_display']))
				{
					$breadcrumb->set_template($this->opt['Hpost_' . $type_str . '_template'] . $suffix);
					//Add the link
					$breadcrumb->set_linked(true);
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
	 * 
	 * @param bool $force_link Whether or not to force this breadcrumb to be linked
	 * @param bool $is_paged Whether or not the current resource is on a page other than page 1
	 * @param bool $is_current_item Whether or not the breadcrumb being generated is the current item
	 */
	protected function do_home($force_link = false, $is_paged = false, $is_current_item = true)
	{
		global $current_site;
		//Exit early if we're not displaying the home breadcrumb
		if(!$this->opt['bhome_display'])
		{
			return;
		}
		//Get the site name
		$site_name = get_option('blogname');
		$suffix = '';
		if(!$is_current_item || $this->opt['bhome_display_children'])
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
		//Place the breadcrumb in the trail, uses the constructor to set the title, prefix, and suffix, get a pointer to it in return
		$breadcrumb = $this->add(new bcn_breadcrumb(
				$site_name,
				$this->opt['Hhome_template_no_anchor'] . $suffix,
				array('home'),
				get_home_url(),
				null,
				false
				));
		//The old do_front_page() specific code
		if($is_current_item)
		{
			$breadcrumb->add_type('current-item');
		}
		//Under a couple of circumstances we will want to link this breadcrumb
		if($force_link || ($is_current_item && $this->opt['bcurrent_item_linked']) || ($is_paged && $this->opt['bpaged_display']))
		{
			$breadcrumb->set_template($this->opt['Hhome_template'] . $suffix);
			//Add the link
			$breadcrumb->set_linked(true);
		}
		//If we have a multi site and are not on the main site we may need to add a breadcrumb for the main site
		if($this->opt['bmainsite_display'] && !is_main_site())
		{
			//Get the site name
			$site_name = get_site_option('site_name');
			//Place the main site breadcrumb in the trail, uses the constructor to set the title, prefix, and suffix, get a pointer to it in return
			$breadcrumb = $this->add(new bcn_breadcrumb(
					$site_name,
					$this->opt['Hmainsite_template'],
					array('main-home'),
					get_home_url($current_site->blog_id),
					null,
					true
					));
		}
	}
	/**
	 * This functions outputs or returns the breadcrumb trail in list form.
	 *
	 * @deprecated 6.0.0 No longer needed, superceeded by $template parameter in display
	 * 
	 * @param bool $return Whether to return data or to echo it.
	 * @param bool $linked[optional] Whether to allow hyperlinks in the trail or not.
	 * @param bool $reverse[optional] Whether to reverse the output or not.
	 * 
	 * @return void Void if option to print out breadcrumb trail was chosen.
	 * @return string String version of the breadcrumb trail.
	 */
	public function display_list($return = false, $linked = true, $reverse = false)
	{
		if($return)
		{
			return $this->display($linked, $reverse,  "<li%3\$s>%1\$s</li>\n");
		}
		else
		{
			echo $this->display($linked, $reverse,  "<li%3\$s>%1\$s</li>\n");
		}
	}
}