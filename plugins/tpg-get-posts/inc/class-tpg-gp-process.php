<?php
/*
  tpg_get_posts front-end processing
*/

//class tpg_gp_process extends tpg_get_posts {
class tpg_gp_process {
	//default parameter array
	private $model_attr = array(
		  'numberposts'      => '5',
		  'offset'           => '',
		  'cat'				 => '',
		  'category'         => '',
		  'category_name'    => '',
		  'tag'              => '',
		  'orderby'          => 'date',
		  'end-of-parms'     => '---------',
		  'show_entire'      => 'false',
		  'show_excerpt'     => 'false',
		  'shorten_title'    => '',
		  'shorten_content'  => '',
		  'text_ellipsis'    => ' ...',
		  'ul_class'         => '',
		  'title_tag'        => 'h2',
		  'title_link'       => 'true',
		  'cat_link'	     => 'true',
		  'tag_link'         => 'true',
		  'thumbnail_size'	 => 'thumbnail',
		  'thumbnail_only'	 => 'false',
		  'thumbnail_link'	 => 'true',
		  'thumbnail_title'  => 'false',
		  'fp_pagination'	 => 'false',
		  'page_next'	     => 'false',
		  'page_prev_text'   => '&laquo; Previous', 
		  'page_next_text'   => 'Next &raquo;',   
		  'mag_layout'       => 'false',
		  'fi_layout'        => 'false',
		  'show_fi_error'    => 'false',
		  'more_link_text'   => '(read more...)',
		  'filter_query'     => '',
		  'filter_title'     => '',
          'filter_byline'    => '',
          'filter_content'   => '',
          'filter_metadata'  => '',
          'filter_thumbnail' => '',
		  'query_by_date'    => 'false',
		  'fields'           => 'title, byline, content, metadata',
		  'field_classes'    => '',
		  );
		  
	private $model_field_class = array(
									'posts_wrapper'=>'tpg-get-posts',
									'post_wrapper'=>'tpg-get-posts-post',
									'post_title'=>'tpg-title-class',
								 	'post_content'=>'tpg-content-class', 
								 	'post_metadata'=>'tpg-metadata-class', 
								 	'post_byline'=>'tpg-byline-class',
									'post_thumbnail'=>'tpg-thumbnail-class',
									'thumbnail_align'=>'alignleft',
									'post_excerpt'=>'tpg-excerpt-class',
									'mag_content'=>'tpg-mag-class',
									'fi_content'=>'tpg-fi-class',
									'ul_class'=>'tpg-ul-class',
									'pagination'=>'tpg-pagination-class',
									'page-next'=>'tpg-next',
									'page-prev'=>'tpg-prev',
									'more_link'=>'more-link',
								 );
								 
	//initialized from model each time processed	  
	private $default_attr = array();
	//variables 
	private $gp_prem = false;
	private	$short_content= false;
	private	$sc_style='w';
	private	$sc_len='20';
	private $ellip='';	
	
	// values for thumbnail size
	public $thumbnail_sizes = array('none','thumbnail', 'medium', 'large', 'full');
	public $thumbnail_size = 'thumbnail';
	
	// constructor
	function __construct($opts,$paths) {
		$this->gp_opts=$opts;
		$this->gp_paths=$paths;
		
		// Register the short code
		add_shortcode('tpg_get_posts', array(&$this, 'tpg_get_posts_gen'));
		add_action( 'wp_enqueue_scripts', array($this,'gp_load_inc') );
		
		/*
		 * Function to execute shortcodes in text widget
		 */		
		if ($this->gp_opts['valid-lic'] && $this->gp_opts['active-in-widgets']) {
			add_filter('widget_text', 'do_shortcode', 11);
		}
	}
	
	/*
	 *	gp_load_inc
	 *  enque css, js and other items for proc page
	 *
	 * @package WordPress
	 * @subpackage tpg_get_posts
	 * @since 0.1
	 *
	 * enque the css, js and other items only when on front end.
	 * 	
	 * @param    null
	 * @return   null
	 */
	public function gp_load_inc(){
		//enque css style 
		$tgp_css = "tpg-get-posts-style.css";
		//check if file exists with path
		if (file_exists($this->gp_paths['css'].$tgp_css)) {
			wp_enqueue_style('tpg_get_posts_css',$this->gp_paths['css_url'].$tgp_css);
		}
		if (file_exists($this->gp_paths['theme']."user-get-posts-style.css")) {
			wp_enqueue_style('user_get_posts_css',$this->gp_paths['theme_url']."user-get-posts-style.css");
		}
	}
		
	
	/*
	 * format routine for metadata category 
	 *
	 * @package WordPress
	 * @subpackage tpg_get_posts
	 * @since 2.8
	 *
	 * 
	 * @param    type    $id    post id
	 * @return   string         category ids for selection
	 *
	 */
		 
	function get_my_cats($id,$_sep='') { 
		// init flds
		if ($_sep == ''){
			$_sep=', ';
		}
		$tpg_cats =''; 
		//if categories exist, process them
		if(get_the_category($id)){ 
			//loop through each cat for the post id
			foreach(get_the_category($id) as $cat) {
				//get the category
				$cat_name = $cat->name; 
				if ($this->cat_link) {
					$tpg_cats .='<a href="'.get_category_link($cat->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $cat->name ) ) . '">'.$cat->cat_name.'</a>'.$_sep;        
				} else {
					$tpg_cats .= $cat->cat_name.$_sep;
				}
			}
		}
		return trim($tpg_cats,$_sep);
	}
	
	/**
	 * format routine for tag metadata selection
	 *
	 * @package WordPress
	 * @subpackage tpg_get_posts
	 * @since 2.8
	 *
	 * if request by tag is made, this is a preprocess routine to 
	 * convert creates a list of tags in comma delimited format
	 * 
	 * @param    type    $id    	post id
	 * @return   string             string of the tags for selecting posts
	 *
	 */
	
	function get_my_tags($id,$_sep='') {
		// init $tpg_tags fld
		if ($_sep == ''){
			$_sep=', ';
		}
		$tpg_tags =''; 
		// if tags exist, process them
		if(get_the_tags($id)){ 
			// loop through each tag for the post id
			foreach(get_the_tags($id) as $tag) {
				if ($this->tag_link) {
					$tpg_tags .='<a href="'.get_tag_link($tag->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $tag->name ) ) . '">'.$tag->name.'</a>'.$_sep;
				} else {
					$tpg_tags .= $tag->name.$_sep;
				}
			}
		}
		if ($tpg_tags == "") $tpg_tags = "No Tags ";
		return trim($tpg_tags,$_sep);
	}
	
	/**
	 * shorten text to fixed length or complete word less than length
	 *
	 * @package WordPress
	 * @subpackage tpg_get_posts
	 * @since 2.8
	 *
	 * to control formatting, sometimes it is necessary to restrict a text field to 
	 * a specific length or the last word less than the length 
	 * 
	 * @param    string  $style			the code value of c or w
	 * @param    string  $len			length of the output text
	 * @param    string  $text			the string to be shortened
	 * @return   string  $text			the shortened text string
	 *
	 */
	
	function shorten_text($style='w', $len='20', $text, $ellipsis) {
		//if style is w and the next char is space change style to c
		if ($style == 'w') {
			if (substr($text,$len,1) == " ") {$style = 'c';}
		}
		
		// if style is c shorten to char and truncate
		// if style is w shorten to last complete word
		switch ($style) {
			case 'c' :
				$text = substr($text,0,$len);
				break;
			case 'w' :
				if (strlen($text) <= $len) {
					$text = $text; //do nothing
				} else {
					$text = preg_replace('/\s+?(\S+)?$/', '', substr($text, 0, $len+1));				
				}
				break;
		}
		
		$text .= $ellipsis;                     // add elipse
		return $text;
	}
	
	/**
	 * name or slug to id
	 *
	 * convert cat names or slugs to ids to allow mutltiple selections 
	 * check for id, then slug and finally name as name in not unique and
	 * 
	 * @param    string   $_list   	list of comma separated cat names 
	 * @param    stirng   $_type	valid taxomony (category,post_tag,link_category or custom)
	 * @return   string   $_ids     list of comma separated cat ids
	 *
	 */
	function name_to_id($_list,$_tax='category'){
	//if category_names passed, convert to cat_id
		$_sep=",";
		$_ids='';
		$_nam_list = explode(",", $_list);
		
		//loop to get cat id and replace cat_names with cat ids
		foreach ($_nam_list as $value) {
			//added to allow for names in ext functions
			// if numeric, assume it is an id
			if (is_numeric($value )) {
				$_ids .= $value.$_sep;
			} else {
				//see if slug works
				$_id_val = get_term_by( 'slug', $value, $_tax );
				if ($_id_val) {
				$_ids .= $_id_val->term_id.$_sep;		
				} else {
					// test for category name
					$_id_val = get_term_by( 'name', $value, $_tax );
					if ($_id_val ) {
						$_ids .= $_id_val->term_id.$_sep;
					}
				}
			}
		}
		return trim($_ids,$_sep);
	}

	/**
	 * register filters
	 *
	 * @package WordPress
	 * @subpackage tpg_get_posts
	 * @since 3.5
	 *
	 * if a filter function name has been passed in the parameters
	 * then register the filter for the shortcode
	 *
	 * all filters have 2 parms: string & post obj
	 *
	 * @param    void
	 * @return   void
	 *
	 */
	public function register_filters() {
		// register query filter
		if ($this->r['filter_query'] != '') {
			add_filter ( 'tgp_filter_query_parms', $this->r['filter_query'], 15, 1 );
			echo "registered query ". $this->r['filter_query']."<br />";
		}

		// register title filter
		if ($this->r['filter_title'] != '') {
			add_filter ( 'tgp_filter_title', $this->r['filter_title'], 15, 2 );
		}

        // register byine filter
        if ($this->r['filter_byline'] != '') {
            add_filter ( 'tgp_filter_byline', $this->r['filter_byline'], 15, 2 );
        }

        // register content filter
        if ($this->r['filter_content'] != '') {
            add_filter ( 'tgp_filter_content', $this->r['filter_content'], 15, 2 );
        }

        // register metadata filter
        if ($this->r['filter_metadata'] != '') {
            add_filter ( 'tgp_filter_metadata', $this->r['filter_metadata'], 15, 2 );
        }

        // register thumbnail filter
        if ($this->r['filter_thumbnail'] != '') {
            add_filter ( 'tgp_filter_thumbnail', $this->r['filter_thumbnail'], 15, 2 );
        }
    }

    /**
     * remove filters
     *
     * @package WordPress
     * @subpackage tpg_get_posts
     * @since 3.5
     *
     * if a filter function name has been passed in the parameters
     * then remove the filter when shortcode terminates
     *
     * all filters have 2 parms: string & post obj
     *
     * @param    void
     * @return   void
     *
     */
    public function remove_filters() {
		// remove query filter
		if ($this->r['filter_query'] != '') {
			remove_filter ( 'tgp_filter_query_parms', $this->r['filter_query'], 15 );
		}

        // remove title filter
        if ($this->r['filter_title'] != '') {
            remove_filter ( 'tgp_filter_title', $this->r['filter_title'], 15 );
        }

        // remove byine filter
        if ($this->r['filter_byline'] != '') {
            remove_filter ( 'tgp_filter_byline', $this->r['filter_byline'], 15 );
        }

        // remove content filter
        if ($this->r['filter_content'] != '') {
            remove_filter ( 'tgp_filter_content', $this->r['filter_content'], 15 );
        }

        // remove metadata filter
        if ($this->r['filter_metadata'] != '') {
            remove_filter ( 'tgp_filter_metadata', $this->r['filter_metadata'], 15 );
        }

        // remove thumbnail filter
        if ($this->r['filter_thumbnail'] != '') {
            remove_filter ( 'tgp_filter_thumbnail', $this->r['filter_thumbnail'], 15 );
        }
    }

	/**
	 * get the posts
	 *
	 * @package WordPress
	 * @subpackage tpg_get_posts
	 * @since 2.8
	 *
	 * set the args, accepting args from shortcode and merging with default
	 * accept any args for the extension 
	 * normalize cat to cat id
	 * look for legacy codes and convert to newer code 
	 * set the fields 
	 * now get the posts and format
	 * 
	 * @param    array    $args   		values from the shortcode passed to this routine
	 * @return   string   $content      the selected formated posts
	 *
	 */

	public function tpg_get_posts_gen($args = '') {
		global $post;

		//set default from model; required to refresh default with mulitple calls
		$this->default_attr = $this->model_attr;
			
		//loop through attributes and if key does not exist add to begin of array 
		if ($args != '') {
			foreach ($args as $key => $value) {
				if (array_key_exists ($key,$this->default_attr)) {
					continue;
				} else {
					$this->default_attr=array($key=>$value)+$this->default_attr;
				}
			}
			reset($args);
		}
		
		reset($this->default_attr);
		
		//now apply any options passed to the default array
		$this->r = shortcode_atts($this->default_attr,$args );
		
		//if cat replaces category & category_name
		if ($this->r['cat'] != '') {
			$_list=$this->name_to_id($this->r['cat']);
			$this->r['cat'] = $_list;
		}
		
		//edit for legacy codes
		$this->edit_legacy_codes();

		//format args & defaults in $r
		$this->format_args(); 	
		if (method_exists($this,'ext_args')) {
			$this->ext_args();
		}
		
		//set up output fields
		$this->fields_list = array_map('trim',explode(",", $this->r['fields']));
		//edit for legacy code in plugin & show meta & byline
		$this->edit_legacy_fields();

		//initial class array with defaults from model
		$this->classes_arr = $this->model_field_class;
		//override defaults if passed
		if ($this->r['field_classes'] != '') {
			$field_classes_list = array_map('trim',explode(",", $this->r['field_classes']));
			foreach ($field_classes_list as $fcl_items) {
				$fcl_item = array_map('trim',explode('=',$fcl_items));
				$this->classes_arr[trim($fcl_item[0])] = trim($fcl_item[1]);
			}
		}

		// register filter functions if set
		$this->register_filters();

		//setup parms for query
		$this->q_args = array();
		reset ($this->r);
		//while (list($key, $value) =  each($this->r)){
		foreach ($this->r as $key => $value) {
			if ( $key == 'end-of-parms' ) {
			end( $this->r );
			break;
			}
			elseif ( $value !== '' ) {
				$this->q_args[$key] = $value;
			}
		}

		// check for query by date
		if ($this->r['query_by_date'] == 'true') {
			$this->q_args = $this->query_by_date($this->q_args);
		}
		// apply filter to query parameters
		$this->q_args = apply_filters('tgp_filter_query_parms',$this->q_args);

		//open div and begin post process
		$content = '';
		$content = $this->filter_pre_plugin($content);
		$content .= '<div class="'.$this->classes_arr['posts_wrapper'].'" >';
		if ($this->show_as_list) {
			$content .="<ul class=\"".$this->r['ul_class']."\">\n";
		}
		
		// get posts
		$tmp_post = $post;                    // save current post/page settings
		
		//echo "<br>get_post a_args:";print_r($this->q_args);echo "<br>";
		
		//if ext query function defined else use base get_posts
		if (method_exists($this,'ext_query')) {
			$posts = $this->ext_query();
		} else {
			unset($this->r['posts_per_page']);	
			$posts = get_posts($this->q_args);
		}
		
		foreach( $posts as $post ) {
			setup_postdata($post);
			$id=$post->ID;
			
			$content = $this->filter_pre_post($content);
			
			if ($this->thumbnail_only) {
				// if list wrap each post in list; if not list wrap in div
				if ($this->show_as_list) {
					$wkcontent = "<li>";
				} else {
					$wkcontent = '<div class="tpg-get-posts-thumbnail" >';
				}
				//get the thumbnail
				$t_content = $this->fmt_post_thumbnail($post);
				if ($t_content == null) {
					if ($this->show_fi_error) {
						$wkcontent .= '<p>thumbnail missing for '.$post->post_title.'</p>';
					}
				} else {
					$wkcontent .= $t_content;
				}
				//close li item or div
				if ($this->show_as_list) {
					$wkcontent .= '</li> ';
				} else {
					$wkcontent .= '</div>';
				}
				$content .=$wkcontent;
				$content = $this->filter_pst_post($content);	
				continue;
			}	
						
			// if list wrap each post in list; if not list wrap in div
			if ($this->show_as_list) {
				$content .= "  <li>";
			} else {
				$content .= '<div class="'.$this->classes_arr['post_wrapper'].'" >';
			}

			// allow magazine layout for premium version
			if (method_exists($this,'magazine_layout') && $this->mag_layout){
				$content .= $this->magazine_layout($post,$id);
			} elseif (method_exists($this,'feat_image_layout') && $this->fi_layout){
				$content .= $this->feat_image_layout($post,$id);
			} else {
				$content .= $this->post_layout($post,$id);
			}
	
			if ($this->show_as_list) {
				$content .= '</li> <hr class="tpg-get-post-li-hr" >';
			} else {
				$content .= '</div>';
			}
			$content = $this->filter_pst_post($content);
		}	
		
		if ($this->show_as_list)
			$content .= '</ul>';
		$content .= '</div><!-- #tpg-get-posts -->';
		$content = $this->filter_pst_plugin($content);
		
		//set the pagination nav
		$content = $this->fmt_pagination($content);
		
		$post = $tmp_post;            //restore current page/post settings
		$this->remove_filters();      //remove the shortcode filters
		return $content;
			
	}

	/**
	 *	query_by_date
	 *
	 * set the query arguments to select all post since x (qbd_num) periods (qbd_period)
	 *
	 * get the args
	 *     qbd_num
	 *     qbd_period
	 * and set the date_query parameters
	 *
	 * @param	array	args
	 * @return	array   args
	 *
	 */
	function query_by_date($args) {
		// get the parameters from the array and unset them
		if (array_key_exists('qbd_period', $args)) {
			$qbd_period = $args['qbd_period'];
			unset($args['qbd_period']);
		}
		else {
			$qbd_period = 'month';
		}

		if (array_key_exists('qbd_num', $args )) {
			$qbd_num = $args['qbd_num'];
			unset($args['qbd_num']);
		}
		else {
			$qbd_num = 1;
		}

		//get the wp time
		$wp_dttm = new DateTime(current_time('mysql'));
		// calc the offset
		if ($qbd_period == 'month') {
			$tm_offset = new DateInterval('P'.$qbd_num.'M');
		}
		elseif ($qbd_period == 'year') {
			$tm_offset = new DateInterval('P'.$qbd_num.'Y');
		}
		elseif ($qbd_period == 'day') {
			$tm_offset = new DateInterval('P'.$qbd_num.'D');
		}

		$wp_dttm->sub($tm_offset);

		// date_query array
		$args['date_query'] =  array(
			array(
				'after'    => array(
					'year'  => $wp_dttm->format('Y'),
					'month' => $wp_dttm->format('m'),
					'day'   => $wp_dttm->format('d'),
				),
				'compare' => '>=',
				'inclusive' => true,
			),
		);
		return $args;
	}


	/**
     * Post Layout
     * 
	 * This routine processes each post
	 *
     * @param object $post, $id
     * @return char  $content
     */
	function post_layout($post,$id) {
		$content = '';
		
		foreach ( $this->fields_list as $field ) {
			$field = trim($field);
			switch ($field) {
				case "title":
					$wkcontent = $this->fmt_post_title($post);
					$wkcontent = $this->filter_post_title($wkcontent,$post);
					break;
				case "byline":
					$wkcontent = $this->fmt_post_byline($post);
					$wkcontent = $this->filter_post_byline($wkcontent,$post);
					break;
				case "content":
					$wkcontent = $this->fmt_post_content($post,$id);
					// add thumbnail to content
					if ($this->show_thumbnail ){	
						$wkcontent = $this->fmt_post_thumbnail($post).$wkcontent;
					}
					//wrap content in div tag
					$wkcontent = $this->filter_post_content($wkcontent,$post);
					break;
				case "metadata":
					$wkcontent = $this->fmt_post_metadata($post);
					$wkcontent = $this->filter_post_metadata($wkcontent,$post);
					break;
			}
			$content .= $wkcontent;
		}

		return $content;
	}
	/**
     * format the post title
     * 
	 * This routine will format the title 
	 *
     * @param object $post
     * @return char  $wkcontent
     */
	function fmt_post_title($post) {
		$wkcontent = $post->post_title;
		$wkcontent = ($this->short_title)? $this->shorten_text($this->st_style,$this->st_len,$wkcontent,$this->ellip): $wkcontent;
		$wkcontent = apply_filters( 'the_title', $wkcontent);
		if ($this->title_link) {
			$wkcontent = $this->t_tag_beg.'<a href="'.get_permalink($post->ID).'" >'.$wkcontent.'</a>'.$this->t_tag_end;
		} else {
			$wkcontent = $this->t_tag_beg.$wkcontent.$this->t_tag_end;
		}

		$wkcontent = '<div class="'.$this->classes_arr['post_title'].'">'.$wkcontent.'</div>';

		return $wkcontent;	
	}
	
	/**
     * format the post content
     * 
	 * This routine formats the content for the post
	 *
     * @param object $post
     * @return char  $wkcontent
     */
	function fmt_post_content($post,$id) {
		// if not post entire -- show only teaser or excerpt if avaliable and requested	
		$wkcontent = $post->post_content;
		if (!$this->show_entire) {           //show only teaser
			if ($this->show_excerpt == true) {
				$e_content = $this->get_excerpt($post);
				if ($e_content == null) {
					$wkcontent = $this->get_post_content($wkcontent,$id);
				} else {
					$wkcontent = '<p class="'.$this->classes_arr['post_excerpt'].' tpg-get-posts-excerpt">'.$e_content.'</p>';
				}
			} else {
				
				$wkcontent = $this->get_post_content($wkcontent,$id);
			}
		}
		return $wkcontent;	
	}
		
	/**
     * format the post thumbnail
     * 
	 * format the thumbnail for a post
	 *
     * @param object $post
     * @return char  $wkcontent
     */
	function fmt_post_thumbnail($post) {
		$t_content='';
		$t_content = $this->get_thumbnail($post,$this->thumbnail_size,$this->classes_arr['thumbnail_align']);
		$t_content = '<div class="'.$this->classes_arr['post_thumbnail'].'">'.$t_content.'</div>';
		if ($t_content != null) {
			if ( $this->thumbnail_link) {
				$t_content = '<a href="' . get_permalink() .'">'.$t_content.'</a>';
			} 
		}
		$t_content = apply_filters('tgp_filter_thumbnail',$t_content,$post);
		return $t_content;	
	}
	
	/**
     * Get the post content
     * 
	 * This routine will parse the content at the more tag and return the short version
	 *
     * @param object $wkcontent
     * @return char  $wkcontent
     */
	function get_post_content($wkcontent,$id) {
		$has_teaser=false;
		//$wkarr = preg_split('/<!--more(.*?)?-->/', $wkcontent);
		if ( preg_match('/<!--more(.*?)?-->/', $wkcontent, $matches) ) {
 	    	$wkarr = explode($matches[0], $wkcontent, 2);
			
            if ( !empty($matches[1]) && !empty($this->more_link_text) ) {
 	        	$this->more_link_text = strip_tags(wp_kses_no_null(trim($matches[1])));
			} 
			$has_teaser = true;
		} else {
			$wkarr = array($wkcontent);
		}
		
		if ($this->short_content) {
			$wkcontent = $this->shorten_text($this->sc_style,$this->sc_len,$wkarr[0],$this->ellip);
			if (strlen($wkcontent) >0) {
				$has_teaser = true;
			} else {
				$has_teaser = false;
			}
		}else {
			$wkcontent = $wkarr[0];
		}
		if ($has_teaser) {
			$wkcontent .= apply_filters( 'the_content_more_link', ' <a href="' . get_permalink() . "#more-".$id.'" class="'.$this->classes_arr['more_link'].'">'.$this->more_link_text.'</a>', $this->more_link_text );
		}
		$wkcontent = force_balance_tags($wkcontent);
		return $wkcontent;
	}
	
	/**
     * Format the by line
     * 
     * @param	object $post
     * @return	string $_byline
     */
	function fmt_post_byline($post){
		$_byline = '';
		$_byline .= '<p ';
		if (isset($this->classes_arr["post_byline"])) {
			$_byline .= ' class="'.$this->classes_arr["post_byline"].'"';
		}	
		$_byline .= '>By '.get_the_author().' on '.mysql2date('F j, Y', $post->post_date);
		$_byline .= '</p>';
		return $_byline;
	}
	
	/**
     * Format the metadata line
     * 
     * @param	object $post
     * @return	string $_metadata
     */
	function fmt_post_metadata($post){
		$_metadata = ''; 
		$_metadata .= '<p ';
		if (isset($this->classes_arr["post_metadata"])) {
			$_metadata .= 'class="'.$this->classes_arr["post_metadata"].'"';
		}	
		$_metadata .= '>';
		ob_start();
		comments_popup_link(' No Comments &#187;', ' 1 Comment &#187;', ' % Comments &#187;');
		$_metadata .= ob_get_clean();
		$_metadata .= " | <b>Filed under:</b> ".$this->get_my_cats($post->ID)."&nbsp;&nbsp;|&nbsp;&nbsp;<b>Tags:</b> ".$this->get_my_tags($post->ID);
		$_metadata .= '</p>';
		return $_metadata;
	}
	
	/**
     * format the pagination line
     * 
	 * This routine will format the pagination line
	 *
     * @param 	chat	$content
     * @return	char	$content
     */
	function fmt_pagination($content) {
		if (array_key_exists('posts_per_page',$this->r)) {

			$pg_cnt = $this->tpg_query->max_num_pages;
			
			if ($this->page_next) {
				$content .= '<div class="'.$this->classes_arr['pagination'].'"> ';
    			$content .= '<span class="'.$this->classes_arr['page-prev'].'">';
				$content .=	get_previous_posts_link($this->r['page_prev_text']);
				$content .= '</span>';
				$content .=	'<span class="'.$this->classes_arr['page-next'].'">';
				$content .=	get_next_posts_link($this->r['page_next_text'],$pg_cnt);
				$content .='</span> ';
				$content .= '</div>';
			} else {
				if ($this->fp_pagination) {
					$_curpage=max( 1, get_query_var('page') );
				} else {
					$_curpage=max( 1, get_query_var('paged') );
				}
				$big = 99999; //'basex' => get_pagenum_link(1).'%_%',
				$link_text= paginate_links( array(
												'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
												'format' => '?paged=%#%',
												'current' => $_curpage,
												'total' => $pg_cnt,
												'prev_next' => true,
												'prev_text'    => __($this->r['page_prev_text']),
												'next_text'    => __($this->r['page_next_text']),
												'type' => 'plain',
											) );
				
				$content .=	'<div class="'.$this->classes_arr['pagination'].'">'.$link_text.'</div>';
			
			}					
		}

		return $content;
	}

	
	/**
     * filter the title content
     * 
	 * This routine calls any custom functions defined for title
	 * and passes the title thru the custom routine
	 *
     * @param 	string	$wkcontent
     * @return	string	$wkcontent
     */
	function filter_post_title($wkcontent,$post) {
		//apply custom filter
		if (method_exists($this,'pst_title_filter') && $this->cf_t ) {
			$wkcontent = $this->pst_title_filter($wkcontent,$this->cfp);
		}
		$wkcontent = apply_filters('tgp_filter_title',$wkcontent,$post);
		return $wkcontent;
	}
	
	/**
     * filter the byline content
     * 
	 * This routine calls any custom functions defined for title
	 * and passes the title thru the custom routine
	 *
     * @param 	string	$wkcontent
     * @return	string	$wkcontent
     */
	function filter_post_byline($wkcontent,$post) {
		//apply custom filter
		if (method_exists($this,'pst_byline_filter') && $this->cf_b ) {
			$wkcontent = $this->pst_byline_filter($wkcontent,$this->cfp);
		}
        $wkcontent = apply_filters('tgp_filter_byline',$wkcontent,$post);
		return $wkcontent;
	}

	/**
     * filter the post content
     * 
	 * This routine calls any custom functions defined for content
	 * and passes the content thru the_content filter
	 *
     * @param	string	$wkcontent
     * @return	string	$wkcontent
     */
	function filter_post_content($wkcontent,$post) {
		if (strlen($wkcontent) > 0) {					
			$wkcontent = '<div class="'.$this->classes_arr['post_content'].'">'.$wkcontent.'</div>';
			//apply filters for all content
			$wkcontent = apply_filters('the_content',$wkcontent);
			$wkcontent = str_replace(']]>', ']]&gt;', $wkcontent);
		}
		//apply custom filter
		if (method_exists($this,'pst_content_filter') && $this->cf_c ) {
			$wkcontent = $this->pst_content_filter($wkcontent,$this->cfp);
		}
        $wkcontent = apply_filters('tgp_filter_content',$wkcontent,$post);
		return $wkcontent;
	}
	/**
     * filter the meta content
     * 
	 * This routine calls any custom functions defined for title
	 * and passes the title thru the custom routine
	 *
     * @param 	string	$wkcontent
     * @return	string	$wkcontent
     */
	function filter_post_metadata($wkcontent,$post) {
		//apply custom filter
		if (method_exists($this,'pst_metadata_filter') && $this->cf_m ) {
			$wkcontent = $this->pst_metadata_filter($wkcontent,$this->cfp);
		}
        $wkcontent = apply_filters('tgp_filter_metadata',$wkcontent,$post);
		return $wkcontent;
	}
	
	/**
     * Pre post filter
     * 
	 * This routine calls any custom functions defined before the post is processed
	 *
     * @param 	string	$wkcontent or null
     * @return	string	$wkcontent
     */
	function filter_pre_post($wkcontent=null) {
		//apply custom filter
		if (method_exists($this,'pre_post_filter') && $this->cf_pre ) {
			$wkcontent = $this->pre_post_filter($wkcontent,$this->cfp);
		} 	
		return $wkcontent;
	}
	
	/**
     * Pst post filter
     * 
	 * This routine calls any custom functions defined after the post is processed
	 *
     * @param 	string	$wkcontent
     * @return	string	$wkcontent
     */
	function filter_pst_post($wkcontent) {
		//apply custom filter
		if (method_exists($this,'pst_post_filter') && $this->cf_pst ) {
			$wkcontent = $this->pst_post_filter($wkcontent,$this->cfp);
		} 	
		return $wkcontent;
	}
	
	/**
     * Pre plugin filter
     * 
	 * This routine calls any custom functions defined before the plugin is processed
	 *
     * @param 	string	$wkcontent or null
     * @return	string	$wkcontent
     */
	function filter_pre_plugin($wkcontent=null) {
		//apply custom filter
		if (method_exists($this,'pre_plugin_filter') && $this->cf_ppre ) {
			$wkcontent = $this->pre_plugin_filter($wkcontent,$this->cfp);
		} 	
		return $wkcontent;
	}
	
	/**
     * Pst plugin filter
     * 
	 * This routine calls any custom functions defined after the plugin is processed
	 *
     * @param 	string	$wkcontent
     * @return	string	$wkcontent
     */
	function filter_pst_plugin($wkcontent) {
		//apply custom filter
		if (method_exists($this,'pst_plugin_filter') && $this->cf_ppst ) {
			$wkcontent = $this->pst_plugin_filter($wkcontent,$this->cfp);
		} 	
		return $wkcontent;
	}

	/**
     * Get the post excerpt
     * 
     * @param object $post
     * @return char  $excerpt
     */
	function get_excerpt($post){
        
		if($post->post_excerpt){
	        return $post->post_excerpt;
        } else {
            return null;
        }
    }

    /**
     * Get the post Thumbnail
     * @see http://codex.wordpress.org/Function_Reference/get_the_post_thumbnail
     * @param 	object  $post
	 * @param   string  thumbnail size
	 * @param   string  class  default="alignleft"
	 * @return	string  $t_content or null
     * 
     */
    function get_thumbnail($post,$tn_size,$t_class="alignleft"){
		//set attributes for thumbnail
		$_attr=array();
		$_attr['class'] = $t_class;
		if ($this->thumbnail_title && method_exists($this,'set_thumbnail_attr')) {
			$_attr = $this->set_thumbnail_attr($post,$_attr);
		}

    	if (has_post_thumbnail($post->ID)) {
			$t_content = get_the_post_thumbnail($post->ID,$tn_size,$_attr);
        	$t_thumbnail = '<a href="' . get_permalink($post->ID).'">'.$t_content.'</a>';          
            return $t_content;
        } else {
            return null;
        }
    }
	
	/**
     * format the arguments from command line
     * 
     * @param 	void
	 * @return	void
     * 
     */
    function format_args(){
		$this->more_link_text = __( $this->r['more_link_text'] );
		
		if ($this->r['show_entire'] == "true") {
			$this->show_entire = true;
		} else {
			$this->show_entire = false;
		}
		
		if ($this->r['title_link'] == "true") {
			$this->title_link = true;
		} else {
			$this->title_link = false;
		}
		$this->cat_link = true;
		$this->tag_link = true;
		
		if ($this->r['ul_class'] == "") {
			$this->show_as_list = false;
		} else {
			$this->show_as_list = true;
		}
		
		if ($this->r['thumbnail_size'] == "" || $this->r['thumbnail_size'] == "none" ) {
			$this->show_thumbnail = false;
		} else {
			$this->thumbnail_size = $this->r['thumbnail_size'];
			$this->show_thumbnail = true;
		}

		if ($this->r['thumbnail_only'] == "true") {
			$this->thumbnail_only = true;
		} else {
			$this->thumbnail_only = false;
		}
		
		if ($this->r['thumbnail_link'] == "true") {
			$this->thumbnail_link = true;
		} else {
			$this->thumbnail_link = false;
		}
		
		if ($this->r['thumbnail_title'] == "false") {
			$this->thumbnail_title = false;
		} else {
			$this->thumbnail_title = $this->r['thumbnail_title'];
		}
		
		if ($this->r['show_excerpt'] == "true") {
			$this->show_excerpt = true;
		} else {
			$this->show_excerpt = false;
		}
		
		if ($this->r['mag_layout'] == "true") {
			$this->mag_layout = true;
		} else {
			$this->mag_layout = false;
		}
		
		if ($this->r['fp_pagination'] == "true") {
			$this->fp_pagination = true;
		} else {
			$this->fp_pagination = false;
		}
		
		if ($this->r['page_next'] == "true") {
			$this->page_next = true;
		} else {
			$this->page_next = false;
		}

		if ($this->r['fi_layout'] == "true") {
			$this->fi_layout = true;
		} else {
			$this->fi_layout = false;
		}
		
		if ($this->r['show_fi_error'] == "true") {
			$this->show_fi_error = true;
		} else {
			$this->show_fi_error = false;
		}
		
		//setup cust funct type & parm
		$this->edit_cust_func();
		
		// set flag to shorten text in title
		$this->ellip = $this->r['text_ellipsis'];
		
		if ($this->r['shorten_title'] == "") {
			$this->short_title = false;
		} else {
			$this->short_title = true;
			$this->st_style = substr($this->r['shorten_title'],0,1);
			$this->st_len= substr($this->r['shorten_title'],1);
		}
		
		if ($this->r['shorten_content'] == "") {
			$this->short_content = false;
		} else {
			$this->short_content = true;
			$this->sc_style = substr($this->r['shorten_content'],0,1);
			$this->sc_len= substr($this->r['shorten_content'],1);
		}
		
		//set up title tag
		if ($this->r['title_tag'] == '') {
			$this->t_tag_beg = '';
			$this->t_tag_end = '';
		} else {
			$this->t_tag_beg = '<'.$this->r['title_tag'].'>';
			$this->t_tag_end = "</".$this->r['title_tag'].">";
		}	
	}
	
	/**
     * setup custom func types & parms
     * 
     * @param 	void
	 * @return	void
     * 
     */
    function edit_cust_func() {
		//init fields
		$this->cf_ppre = false;             //plugin
		$this->cf_ppst = false;
		$this->cf_pre = false;				//pst
		$this->cf_pst = false;
		
		$this->cf_t = false;
		$this->cf_b = false;
		$this->cf_c = false;
		$this->cf_m = false;
		$this->cfp = '';
		
		
		//check for args
		if (array_key_exists('cf',$this->r)) {
			$_cf_type = explode(',',$this->r['cf']);
			foreach ($_cf_type as $t) {
				switch ($t){
					case 'ppre':
						$this->cf_ppre = true;
						break;
					case 'ppst':
						$this->cf_ppst = true;
						break;
					case 'pre':
						$this->cf_pre = true;
						break;
					case 'pst':
						$this->cf_pst = true;
						break;
					case 't':
						$this->cf_t = true;
						break;
					case 'b':
						$this->cf_b = true;
						break;
					case 'c':
						$this->cf_c = true;
						break;
					case 'm':
						$this->cf_m = true;
						break;
				}
			}
		}
		//check for user parms
		if (array_key_exists('cfp',$this->r)) {
			$this->cfp = $this->r['cfp']; 
		}
	}
	
	/**
     * edit field list
     * 
     * @param 	void
	 * @return	void
     * 
     */
    function edit_legacy_fields() {
		//edit for legacy code in fields
		$_legacy_flds = array('post_title'=>'title','post_content'=>'content');
		foreach ($_legacy_flds as $lk=>$lv) {
			$_fnd = array_search($lk,$this->fields_list);
			if ($_fnd !== false) {
				$this->fields_list[$_fnd] = $lv;
			}
		}
		
		if (array_key_exists('show_meta',$this->r)) {
			if ($this->r['show_meta'] != "true") {
				$_fnd = array_search('metadata',$this->fields_list);
				if ($_fnd !== false) {
					unset($this->fields_list[$_fnd]);
				}
			}
			unset($this->r['show_meta']);
		}
		
		if (array_key_exists('show_byline',$this->r)) {
			if ($this->r['show_byline'] != "true") {
				$_fnd = array_search('byline',$this->fields_list);
				if ($_fnd !== false) {
					unset($this->fields_list[$_fnd]);
				}
			}
			unset($this->r['show_byline']); 
		}
		
	}
	
	/**
     * check for legacy codes
     * 
     * @param 	void
	 * @return	void
     * 
     */
    function edit_legacy_codes() {
		//**legacy if category_names passed, convert to cat_id
		if ($this->r['category_name'] != '') {
			$_list=$this->name_to_id($this->r['category_name']);
			$this->r['cat'] = $_list;
			$this->r['category_name'] = "";
		}
		//** legacy if category_names passed, convert to cat_id
		if ($this->r['category'] != '') {
			$_list=$this->name_to_id($this->r['category']);
			$this->r['cat'] = $_list;
			$this->r['category'] = "";
		}
	}
	
}//end class
?>
