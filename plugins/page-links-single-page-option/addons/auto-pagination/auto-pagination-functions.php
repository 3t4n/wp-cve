<?php
/**
 * Auto Pagination
 *
 * @category Auto_Pagination
 * @package Page_Links
 */

/**
 * Auto Pagination Functions
 *
 * @category Auto_Pagination_Functions
 * @package Page_Links
 */

class SH_AutoPag_Functions {



    /**
     * PHP 5 Constructor function
     *
     * @return void
    */
    public function __construct() {
        add_action('wp_head', array($this, 'remove_nextpage'));
        add_filter('get_the_excerpt', array($this, 'generate_excerpt'), 1);
        add_filter('the_content', array($this, 'add_pagination'), 51);
        add_filter('generate_pagination', create_function('$output,$page,$pages,$args', 'return $output;'), 10, 4);
        add_action('wp_enqueue_scripts', array($this, 'add_pagination_style'));
		add_action( 'add_meta_boxes', array( $this, 'pagination_metabox' ) );
		add_action( 'save_post', array($this,'pagination_metabox_render_save') );
    }

    public function get_default_args() {
        
		$args = array();
        $args['before'] = '<p>Pages:';
        $args['after'] = '</p>';
        $args['link_before'] = '';
        $args['link_after'] = '';
        $args['pagelink'] = '%page%';
        $args['echo-tag'] = '1';
        $args['seperator'] = '|';
        $args['wrapper_tag'] = 'div';
        $args['wrapper_class'] = 'page-link';
        $args['wrapper_id'] = '';
        $args['link_wrapper'] = '';
        $args['link_wrapper_class'] = '';
        $args['link_wrapper_outter'] = '';
        $args['link_wrapper_outter_class'] = '';
		
		return $args;
    }

    public function add_pagination_style() {
        wp_register_style('auto-pagination-style', plugins_url('auto-pagination.css', __FILE__));
        wp_enqueue_style('auto-pagination-style');
    }

    public function remove_nextpage() {
        global $post, $sh_page_links;
        
        if (!$post->post_content || $post->post_content == '')
            return;
            
        $options =  $sh_page_links->get_options();
		
		$option = get_post_meta($post->ID,"single_override_pagination",true);
        $ignore = false;
        
		if ($option == false || $option == "default" || $option == '') {
			if (isset($options['auto_pagination']) && $options['auto_pagination'] == 1)
				$ignore = true;
		} else {
			if ($option == "ignore")
				$ignore = true;
        }
        
		if ($ignore) {
            $post->post_content = str_replace("<!--nextpage-->", "", $post->post_content);
        } else {
            $post->post_content = str_replace("<!--nextpage-->", "<!--sh_nextpage-->", $post->post_content);
        }

        if (get_post_meta( $post->ID, 'single_not_paginate', true ) != 'yes') {
            add_filter('body_class', function($classes){
                $classes[] = 'plp-on';
                return $classes;
            });
        }

    }

    public function pagination_metabox() {
		global $sh_page_links;
		$options = $sh_page_links->get_options();
		$cps = unserialize($options['single_view']['enabled_posts']);
        foreach ($cps as $cp) {
			add_meta_box( 'pagination_metabox_single', '<span class="plp_logo"></span>' . __( 'Override Pagination Settings?', SH_PAGE_LINKS_DOMAIN ), array( $this, 'pagination_metabox_render' ), $cp, 'side');
		}
    }

    public function pagination_metabox_render($post) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'pagination_override' );
		$value = get_post_meta($post->ID,"single_override_pagination",true);
		?>
		<select id="single_override_pagination" name="single_override_pagination">
			<option value="default"><?php _e("Maintain Global Settings (default).", SH_PAGE_LINKS_DOMAIN ); ?></option>
			<option value="ignore" <?php if ($value == "ignore") echo "SELECTED"; ?>><?php _e("Ignore inline &lt;!--nextpage--&gt; tags.", SH_PAGE_LINKS_DOMAIN); ?></option>
			<option value="noignore" <?php if ($value == "noignore") echo "SELECTED"; ?>><?php _e("Accomodate inline &lt;!--nextpage--&gt; tags.", SH_PAGE_LINKS_DOMAIN); ?></option>
		</select>
        <br /><br />
        <?php
        $value = get_post_meta($post->ID,"single_not_paginate",true);
        $postType = get_post_type_object(get_post_type($post));
        $ptype = '';
        if ($postType)
            $ptype = $postType->labels->singular_name;
        ?>
        <input type="checkbox" id="single_not_paginate" name="single_not_paginate" value="yes" <?php checked( 'yes', $value, true ); ?> /><label for="single_not_paginate"><?php _e("Don't paginate this", SH_PAGE_LINKS_DOMAIN); echo ' ' . strtolower($ptype); ?></label>
		<?php
	}
	
    public function pagination_metabox_render_save($post_id) {
		
		if ( ! isset( $_POST['pagination_override'] ) || ! wp_verify_nonce( $_POST['pagination_override'], plugin_basename( __FILE__ ) ) )
			return;
		
		update_post_meta($post_id, 'single_override_pagination', $_POST['single_override_pagination']);
		update_post_meta($post_id, 'single_not_paginate', $_POST['single_not_paginate']);
	}



    /**
     * Adds Excerpt. Fix the excerpt function.
     *
     * @global object $sh_page_links
     * @param string $content
     * @return string
     */
    public function generate_excerpt($text) {

        $raw_excerpt = $text;
        if ($text=="") {

            $text = get_the_content('');

            $text = strip_shortcodes( $text );

            $text = str_replace(']]>', ']]&gt;', $text);

            $excerpt_length = apply_filters( 'excerpt_length', 55 );
            $excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
            $text = wp_trim_words( $text, $excerpt_length, $excerpt_more );

        }
        
        return $text;
    }



    /**
     * Adds pagination
     *
     * @global object $sh_page_links
     * @param string $content
     * @return string
     */
    public function add_pagination($content) {
        global $sh_page_links, $auto_paged, $post, $sh_single_view, $pages_count;

        //Check if is set to ignore pagination
        if (get_post_meta( $post->ID, 'single_not_paginate', true ) == 'yes')
            return $content;

		$options = $sh_page_links->get_options();
		
        $content = str_replace("<!--nextpage-->", "", $content);
		
        $post_type = get_post_type($post);
		$enabled = unserialize($options['single_view']['enabled_posts']);
		if (!in_array($post_type, $enabled))
			return $content;
		
        $auto_paged = 0;
        
		$elipsis = $options['scrolling_pagination']['elipsis'];
		$firstpage = $options['scrolling_pagination']['firstpage'];
		$lastpage = $options['scrolling_pagination']['lastpage'];
		
        $auto_options = $options['auto_pagination'];
        $pages_array = $this->get_pages($content, $auto_options);
		
        $pages = count($pages_array);
        $pages_count = $pages;
		
        if ($pages > 1) {
            $auto_paged = 1;
            $page = (is_single() || is_page()) ? get_query_var('page') : 0;
            $output = "";
            $page_style_args = $options['pagination_styles'];

            $singlepage = !empty($_GET['singlepage']) ? 1 : 0;
            if (! ($singlepage==1 && $options['pagination_styles']['use_ajax']==0))
                $output = $this->generate_pagination($page, $pages, $page_style_args, $elipsis);
			
			$wrapper_tag_output = "%s";
			
			/*
             * Add Single View
             */
			$show_globally = !empty($options['single_view']['view_single_link']) ? $options['single_view']['view_single_link'] : 0;
			if ($show_globally) {
                if (! ($singlepage==1 && $options['pagination_styles']['use_ajax']==0))
                    $wrapper_tag_output .= $sh_single_view->add_single_page($options['pagination_styles']['seperator'],'','');
                else
                    $wrapper_tag_output .= $sh_single_view->add_single_page('','','');
            }
			
            if (! ($singlepage==1 && $options['pagination_styles']['use_ajax']==0)) {

                if (!empty($page_style_args['wrapper_tag'])) {
                        
                    $wrapper_tag = $page_style_args['wrapper_tag'];
                    $wrapper_id = empty($page_style_args['wrapper_id']) ? "" : " id=\"{$page_style_args['wrapper_id']}\"";
                    $wrapper_class = empty($page_style_args['wrapper_class']) ? " class=\"auto-paginate-links\"" : " class=\"auto-paginate-links {$page_style_args['wrapper_class']}\"";
                    $wrapper_tag_output = "<". $wrapper_tag ." ". $wrapper_id ." ". $wrapper_class .">"
                            . $page_style_args['before']
                            . $wrapper_tag_output
                            . $page_style_args['after']
                            . "</". $wrapper_tag .">";
                } else {
                    $after = isset($page_style_args['single']) ? $page_style_args['single'] : $page_style_args['after'];
                    $wrapper_tag_output = 
                            $page_style_args['before']
                            . $wrapper_tag_output
                            . $after;
                }

            } else {

                if (!empty($page_style_args['wrapper_tag'])) {
                        
                    $wrapper_tag = $page_style_args['wrapper_tag'];
                    $wrapper_id = empty($page_style_args['wrapper_id']) ? "" : " id=\"{$page_style_args['wrapper_id']}\"";
                    $wrapper_class = empty($page_style_args['wrapper_class']) ? " class=\"auto-paginate-links\"" : " class=\"auto-paginate-links {$page_style_args['wrapper_class']}\"";
                    $wrapper_tag_output = "<". $wrapper_tag ." ". $wrapper_id ." ". $wrapper_class .">"
                            . $wrapper_tag_output
                            . "</". $wrapper_tag .">";
                }                

            }

			$output = sprintf($wrapper_tag_output, $output);
			
            global $current_page;
            $page_key = 0;
            if ($page > 0) {
                $page_key = $page - 1;
            }
            if ($current_page !== null) {
                $page_key = $current_page;
            }

			if ($singlepage) {
				$content = implode("\n",$pages_array);
			} else {
				$content = $pages_array[$page_key];
			}

            if ($current_page === null)
                $content = $content . $output;
			
        }
        return $content;
    }



    /**
     * Retrieves page array based on content
     *
     * @global object $post WordPress post object
     * @param string $content
     * @param array $options
     * @return array
     */
    public function get_pages($content, $options = false) {
        global $post;
        if (!$options) {
            global $sh_page_links;
            $options = $sh_page_links->get_options();
            $options = $options['auto_pagination'];
        }


        // check if page already has <!--nextpage-->
        if (substr_count($content, "<!--nextpage-->") !== 0) {
			
			$content_array = explode("<!--nextpage-->", $content);
			
            return $content_array; 
        }
        $exploded_content = explode("\n", $content);
        $paragraph_count = 0;
        $content_pages = array();
        foreach ($exploded_content as $paragraph) {
            $content_pages[] = '';
            if ($this->_is_not_paragraph($paragraph)) {
                $content_pages[$paragraph_count] .= $paragraph;
            } else {
                $content_pages[$paragraph_count] .= $paragraph;
                $paragraph_count++;
            }
        }
		
        if ($options['break_type'] == 0) {

            //Break per paragraph
            $modified_exploded_content = $content_pages;        
            $options['total_paras']=count($modified_exploded_content);
            array_walk($modified_exploded_content, array($this, 'walker_insert_nextpage'), $options);

        } elseif ($options['break_type'] == 1) {
            //Break per total of pages
            $page_count = (int)$options['paragraph_count'];
            if ( (!$page_count) || ($page_count < 2) )
                $page_count = 2;

            $i = 0;
            $j = 0;
            $break_on = $paragraph_count/$page_count;
            if ($break_on < 1)
                $break_on == 1;
            $excess = $paragraph_count%$page_count;
            $modified_exploded_content = array();
            foreach ($content_pages as $page) {
                $i++;
                if ($i > $break_on) {
                    if ($excess > 0) {
                        $excess--;
                        $modified_exploded_content[$j] = $page . "\n<!--sh_nextpage-->";
                        $j++;
                        $i = 0;
                        continue;
                    } else {
                        $modified_exploded_content[$j] = "\n<!--sh_nextpage-->";
                    }
                    $i = 1;
                    $j++;
                }
                $modified_exploded_content[$j] = $page;
            }

        } elseif ($options['break_type'] == 2) {
            
            //Break per number of words
            $words_count = (int)$options['paragraph_count'];
            if ( (!$words_count) || ($words_count < 50) )
                $words_count = 50;

            $i = 0;
            $j = 0;
            $modified_exploded_content = $array;
            foreach ($content_pages as $page) {
                $modified_exploded_content[$i] .= $page;
                $j = $j + str_word_count($page);
                if ($j >= $words_count) {
                    $j = 0;
                    $i++;
                    $modified_exploded_content[$i] .= "\n<!--sh_nextpage-->";
                }
            }

        }

        $paged_content = implode("\n", $modified_exploded_content);
        $page_content_array = explode("<!--sh_nextpage-->", $paged_content);    
        $page_content_array = array_filter($page_content_array,array($this, 'filter_trim'));
        return $page_content_array;
    }



    /**
     * Helper method for generating pagination.
     *
     * @param integer $page
     * @param array $pages
     * @param array $args
     * @return string
     */
     public function generate_pagination($page, $pages, $args, $elipsis) {
		global $singlepage;
		
		if (!$args) {
			$args = $this->get_default_arguments();
		}
		
        if ($page == 0)
            $page = 1;
        $output = "";
        
        $singlepage = !empty($_GET['singlepage']) ? 1 : 0;
        if ($singlepage==1 && $options['pagination_styles']['use_ajax']==0) {
            return "";
        }

        // Build our link list...
		$title = get_the_title();
        for ($i = 1; $i < ($pages + 1); $i = $i + 1) {

            if ($i==$page) {
                $link_html_open = '<span class="plp-active-page">';
                $link_html_close = '</span>';
            } else {
                $link_html_open = _wp_link_page($i);
                $link_html_close = '</a>';
            }

			
            // Link Wrapper Inner
            $link_wrapper_open = "";
            $link_wrapper_close = "";
            if (!empty($args['link_wrapper'])) {
				$link_wrapper = $args['link_wrapper'];
                $link_class = " class=\"" . $args['link_wrapper_class'] . "\"";
                if ($link_wrapper) {
                    $link_wrapper_open = "<{$link_wrapper}{$link_class}>";
                    $link_wrapper_close = "</{$link_wrapper}>";
                }
            }

            // Link Wrapper Outter
            $link_wrapper_outter_open = "";
            $link_wrapper_outter_close = "";
            if (!empty($args['link_wrapper_outter'])) {
				$link_wrapper = $args['link_wrapper_outter'];
                $link_class = " class=\"" . $args['link_wrapper_outter_class'] . "\"";
                if ($link_wrapper) {
                    $link_wrapper_outter_open = "<{$link_wrapper}{$link_class}>";
                    $link_wrapper_outter_close = "</{$link_wrapper}>";
                }
                
            }

			
            $link_html_formatted = " "
                    . $link_wrapper_outter_open
					. $link_html_open
                    . $link_wrapper_open
					. str_replace(array('%page%','%title%'), array($i,$title), $args['pagelink'])
					. $link_wrapper_close
                    . $link_html_close
                    . $link_wrapper_outter_close
					. " ";
                  
			if ($i != $pages)
				$link_html_formatted .= $args['seperator'];
			
			if($i > 3)
				$link_html_formatted .= $elipsis;
				
			
            $output .= $link_html_formatted;
        }
		
        return apply_filters('generate_pagination', $output, $page, $pages, $args, $elipsis);
    }



    /**
     * Walker method for inserting nextpage code.
     *
     * @param array $paragraph
     * @param integer $count
     * @param array $args
     *
     * @return void
     */
    public function walker_insert_nextpage(&$paragraph, $count, $args) {
        $count = $count + 1;
        $paragraph_count = (int) $args['paragraph_count'];
		if ( (!$paragraph_count) || ($paragraph_count < 3 ) )
			$paragraph_count = 3;
        $paged = $count / $paragraph_count;
        if (is_integer($paged) && $count != $args['total_paras']) {
            $paragraph = $paragraph . "\n<!--sh_nextpage-->";
        }
    }


    
     /**
     * Filter method for trimming strings.
     *
     * @param array $page
     *
     * @return void
     */
    public function filter_trim($page) {
        $page = trim($page);
        if ($page == '') {
            return false;
        } else {
            return true;
        }
    }



    /**
     * @return boolean
     */
    private function _is_not_paragraph($string) {
        $first = substr($string, 0, 20);
        if (!preg_match('%(<p[^>]*>.*?</p>)%i', $first)) {
            if (substr_count($first, '<p')) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }



    /**
     * Retrieve default arguments from Pagination Styles
     *
     * 
     * @return array
     */
    public function get_default_arguments() {
        $page_styles_args = array();
		if ($this->_has_pag_styles()) {
			global $sh_pagstyles_functions;
            $page_styles_args = apply_filters('wp_link_pages_args', $sh_pagstyles_functions->add_arg_values(array()));
		} else {
			
			$page_styles_defaults = $this->get_default_args();
            $page_styles_args = apply_filters('wp_link_pages_args', $page_styles_defaults);
            
        }
        return $page_styles_args;
    }


    
    /**
     * Checks if Pagination Styles is active
     *
     * @return boolean
     */
    private function _has_pag_styles() {
        return class_exists('SH_PageLinks_PagStyles_Bootstrap');
    }
	
	
}
