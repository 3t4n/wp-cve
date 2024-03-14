<?php
/*
Plugin Ext: TPG Get Posts
Plugin URI: http://www.tpginc.net/wordpress-plugins/
Description: tpg_get_posts front-end processing extension
Version: 3.5.1
*/


class tpg_gp_process_ext extends tpg_gp_process {

	//allowable tags for line formatting
	private $meta_tags  = array("auth","cat","cmt","dp","dm","tag","tm","tp");
	//line fmt template
	private $byline_fmt = array(' ','auth','dp');          //sep,meta_tag,meta_tag....
	private $metaline_fmt   = array('&nbsp;&nbsp;|&nbsp;&nbsp;','cmt','cat','tag');    //sep,meta_tag,meta_tag...
	//tag format template
	private $auth_fmt   = array('','By ','');              //sep,b4,af
	private $cat_fmt    = array(', ','Filed under: ','');    //sep,b4,af
	private $cmt_fmt    = array(' No Comments &#187;', ' 1 Comment &#187;', ' Comments &#187;');  //nocmt,1-cmt, multi-cmt
	private $dp_fmt     = array('F j, Y',' on ','');            //fmt,b4,af
	private $dm_fmt     = array('F j, Y',' last maintained on: ','');               //fmt,b4,af
	private $tag_fmt    = array(', ','<b>Tags:</b> ','');         //sep,b4,af
	private $tm_fmt     = array('H:m:s',' ','');               //fmt,b4,af
	private $tp_fmt     = array('H:m:s',' ','');               //fmt,b4,af

	private $model_fmt	= array(
		'byline_fmt' => array(' ','auth','dp'),          //sep,meta_tag,meta_tag....
		'metaline_fmt'   => array('&nbsp;&nbsp;|&nbsp;&nbsp;','cmt','cat','tag'),    //sep,meta_tag,meta_tag...
		'auth_fmt'   => array('','By ',''),              //sep,b4,af
		'cat_fmt'    => array(', ','Filed under: ',''),    //sep,b4,af
		'cmt_fmt'    => array(' No Comments &#187;', ' 1 Comment &#187;', ' Comments &#187;'),  //nocmt,1-cmt, multi-cmt
		'dp_fmt'     => array('F j, Y',' on ',''),            //fmt,b4,af
		'dm_fmt'     => array('F j, Y',' last maintained on: ',''),               //fmt,b4,af
		'tag_fmt'    => array(', ','<b>Tags:</b> ',''),         //sep,b4,af
		'tm_fmt'     => array('H:m:s',' ',''),         //mod time  fmt,b4,af
		'tp_fmt'     => array('H:m:s',' ',''),        //post time  fmt,b4,af
					);

	function __construct($opts,$paths) {
		parent::__construct($opts,$paths);
		if ($opts['active-in-widgets']) {
			add_filter('widget_text', 'do_shortcode', 11);
		}

	}
	/**
     * pre query setup
     *
	 * custom setup to set parm before query executes
	 *
     * @param 	$query object passed by do_action
	 * @return	none
     */
	function pre_query_setup($q){
		//set home force sticky post
		$q->is_home = true;
	}
	/**
     * Extended query
     *
	 * this routine adds sticky post and pagination
	 *
     * @param 	none
	 * @return	array	selected posts
     */
	function ext_query(){

		$defaults = array(
			'numberposts' => 5, 'offset' => '',
			'cat' => 0, 'orderby' => 'date',
			'order' => 'DESC', 'include' => array(),
			'exclude' => array(), 'meta_key' => '',
			'meta_value' =>'',
			'post_type' => 'post',
			'suppress_filters' => true,
			'ignore_sticky_posts' => true,
			);

		//merge arg with defaults
		$this->q_args = wp_parse_args( $this->q_args, $defaults );

		if (array_key_exists('posts_per_page',$this->r)) {
			unset($this->q_args['numberposts']);
			if ($this->fp_pagination) {
				$paged = (get_query_var('page')) ? absint(get_query_var('page')) : 1;
			} else {
				$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
			}
			$this->q_args['paged']=$paged;
			$this->q_args['posts_per_page'] = $this->r['posts_per_page'];
		}

		//check on sticky post, invoke pre_query_setup
		if (array_key_exists('ignore_sticky_posts',$this->r)
		&& (!$this->r['ignore_sticky_posts'])) {
			add_action( 'pre_get_posts', array(&$this,'pre_query_setup'));
		}

		///set defaults  critical is numberposts to page_per_post
		if ( empty( $this->q_args['post_status'] ) )
			$this->q_args['post_status'] = ( 'attachment' == $this->q_args['post_type'] ) ? 'inherit' : 'publish';
		if ( ! empty($this->q_args['numberposts']) && empty($this->q_args['posts_per_page']) )
			$this->q_args['posts_per_page'] = $this->q_args['numberposts'];
		if ( ! empty($this->q_args['category']) )
			$this->q_args['cat'] = $this->q_args['category'];
		if ( ! empty($this->q_args['include']) ) {
			$incposts = wp_parse_id_list( $this->q_args['include'] );
			$this->q_args['posts_per_page'] = count($incposts);  // only the number of posts included
			$this->q_args['post__in'] = $incposts;
		} elseif ( ! empty($this->q_args['exclude']) )
			$this->q_args['post__not_in'] = wp_parse_id_list( $this->q_args['exclude'] );

		//$this->q_args['posts_per_page'] = $this->r['numberposts'];
		//unset($this->q_args['numberposts']);
		//echo '**r:';print_r($this->r);echo '<br>';
		//echo '**q:';print_r($this->q_args);echo '<br>';

		//get the query
		$this->tpg_query = new WP_Query( $this->q_args );

		//
		if (!has_action('pre_get_posts', array(&$this,'pre_query_setup')) === false) {
			$rsp=remove_action( 'pre_get_posts', array(&$this,'pre_query_setup'));
		}

		$posts = $this->tpg_query->posts;

		return $posts;
	}

	/**
     * format the arguments from command line
     *
	 * the cateory__and is a double underling, but test for single
	 *
     * @param 	void
	 * @return	void
     */
	function ext_args(){
		//echo " r: ",print_r($this->r);echo "<br>";
		//array_map('trim',explode(",", $this->r['fields']));

		if ($this->r['cat_link'] == "true") {
			$this->cat_link = true;
		} else {
			$this->cat_link = false;
		}

		if ($this->r['tag_link'] == "true") {
			$this->tag_link = true;
		} else {
			$this->tag_link = false;
		}


		$key_found=false;

		//reformat to array
		$this->reformat_to_array();

		//category__and
		if (array_key_exists('category_and',$this->r)) {
			$this->r=array('category__and'=>$this->r['category_and'])+$this->r;
			unset($this->r['category_and']);
		}
		if (array_key_exists('category__and',$this->r) ) {
			$_list=$this->name_to_id($this->r['category__and']);
			$this->r['category__and'] = array_map('trim',explode (",",$_list));
			$key_found=true;
		}

		//category__in
		if (array_key_exists('category_in',$this->r) ) {
			$this->r=array('category__in'=>$this->r['category_in']) + $this->r;
			unset($this->r['category_in']);;
		}
		if (array_key_exists('category__in',$this->r) ) {
			$_list=$this->name_to_id($this->r['category__in']);
			$this->r['category__in'] = array_map('trim',explode (",",$_list));
			$key_found=true;
		}

		//category__not_in
		if (array_key_exists('category_not_in',$this->r) ) {
			$this->r=array('category__not_in'=>$this->r['category_not_in'])+$this->r;
			unset($this->r['category_not_in']);
		}
		if (array_key_exists('category__not_in',$this->r) ) {
			$_list=$this->name_to_id($this->r['category__not_in']);
			$this->r['category__not_in'] = array_map('trim',explode (",",$_list));
			$key_found=true;
		}
		//clean up array if key found
		if ($key_found) {
			$this->r['category'] = '';
			$this->r['category_name'] = '';
			$this->r['cat'] = '';
		}

		//tag parameters
		if (array_key_exists('tag_and',$this->r)) {
			$this->r=array($this->r['tag__and']=>$this->r['tag_and']) +$this->r;
			unset($this->r['tag_and']);
		}
		if (array_key_exists('tag__and',$this->r) ) {
			$this->r['tag__and'] = array_map('trim',explode (",",$this->name_to_id($this->r['tag__and'],'post_tag')));
		}

		//tag__in
		if (array_key_exists('tag_in',$this->r) ) {
			$this->r=array($this->r['tag__in']=>$this->r['tag_in']) + $this->r;
			unset($this->r['tag_in']);
		}
		if (array_key_exists('tag__in',$this->r) ) {
			$this->r['tag__in'] = array_map('trim',explode (",",$this->name_to_id($this->r['tag__in'],'post_tag')));
		}

		//tag__not_in
		if (array_key_exists('tag_not_in',$this->r) ) {
			$this->r=array('tag__not_in'=>$this->r['tag_not_in'])+$this->r;
			unset($this->r['tag_not_in']);
		}
		if (array_key_exists('tag__not_in',$this->r) ) {
			$this->r['tag__not_in'] = array_map('trim',explode (",",$this->name_to_id($this->r['tag__not_in'],'post_tag')));
		}

		//tag_slug
		if (array_key_exists('tag_slug_and',$this->r)) {
			$this->r=array('tag_slug__and'=>$this->r['tag_slug_and'])+$this->r;
			unset($this->r['tag_slug_and']);
		}
		if (array_key_exists('tag_slug__and',$this->r) ) {
			$this->r['tag_slug__and'] = array_map('trim',explode (",",$this->r['tag_slug__and']));
		}

		if (array_key_exists('tag_slug_in',$this->r) ) {
			$this->r=array('tag_slug__in'=>$this->r['tag_slug_in']) + $this->r;
			unset($this->r['tag_slug_in']);
		}
		if (array_key_exists('tag_slug__in',$this->r) ) {
			$this->r['tag_slug__in'] = array_map('trim',explode (",",$this->r['tag_slug__in']));
		}

		//square brackets cannot be passed in shortcode, sub () and replace with []
		$json_srch=array( '(',')' );
		$json_repl=array( '[',']' );
		//meta query
		if (array_key_exists('meta_query',$this->r) ) {
			$_str=str_replace($json_srch,$json_repl,$this->r['meta_query']);
			$_arr= json_decode($_str,true);
			if (!$_arr) {
				echo 'json encode failed: <br />'.$this->r['meta_query'].'<br />';
			} else {
				$this->r['meta_query']=$_arr;
			}
		}

		//tax query
		if (array_key_exists('tax_query',$this->r) ) {
			$_str=str_replace($json_srch,$json_repl,$this->r['tax_query']);
			$_arr= json_decode($_str,true);
			if (!$_arr) {
				echo 'json encode failed: <br />'.$this->r['tax_query'].'<br />';
			} else {
				$this->r['tax_query']=$_arr;
			}
		}

		//set true/false to boolean
		if (array_key_exists('ignore_sticky_posts',$this->r))  {
			if ($this->r['ignore_sticky_posts'] == "false" || $this->r['ignore_sticky_posts'] == 0 ) {
				$this->r['ignore_sticky_posts'] = false;
			} else {
				$this->r['ignore_sticky_posts'] = true;
			}
		}

		//check for extension formatting args
		$this->fmt_args();
	 }

	/**
     * reformat to array
     *
     * Loop throuth refmt array and if the array-key exists,
     * and if the value is not an array,
     * change the value in $this->r from a string to an array
	 *
     * @param 	void
	 * @return	void
     */
	 function reformat_to_array(){
		$refmt_arr = array(
					'author__in',
					'author__not_in',
					'post_parent__in',
					'post_parent__not_in',
					'post__in',
					'post__not_in',
					'post_name__in',
					'post_type',
					'post_status',
					);

		foreach ($refmt_arr as $parm_key) {
			if (array_key_exists($parm_key,$this->r) ) {
				if (!is_array($this->r[$parm_key])) {
					$this->r[$parm_key] = array_map('trim',explode (",",$this->r[$parm_key]));
				}
			}
		}
	 }
	 /**
     * check for fmt args
     *
	 *
     * @param 	void
	 * @return	void
     */
	 function fmt_args(){
	 	//copy default from model array to work variable
		foreach ($this->model_fmt as $key => $val){
			$this->{$key}=$val;
		}
		//extract format args from args and apply parms to fmt lines
		foreach ($this->r as $key => $val) {
			if (array_key_exists($key,$this->model_fmt)) {
				//if it passes edit, update fmt line
				if ($this->edit_fmt($key,$val)) {
					$this->{$key}=explode (",",$val);
				}
				unset($this->r[$key]);
			}
		}

	 }

	 /**
     * edit the fmt args
     *
     * @param	string $fmt field
	 * @param   array  $fmt line
     * @return	bool
	 *
	 * @TODO add edit to verify valid parms passed
     */
	function edit_fmt($key,$value){
		$bool=true;
//		switch ($key){
//			case "byline_fmt":
//				foreach ($value as $val){
//
//				}
//				break;
//			case "metaline_fmt":
//				break;
//		}
 	    return $bool;
	}

	 /**
     * Format the by line
     *
     * @param	object $post
     * @return	string $_byline
     */
	function fmt_post_byline($post){

		$_byline = '<div ';
		if (isset($this->classes_arr["post_byline"])) {
			$_byline .= ' class="'.$this->classes_arr["post_byline"].'"';
		}
		$_byline .= '>';
		$_byline .= $this->build_line($this->byline_fmt,$post);
		$_byline .= '</div>';
		return $_byline;
	}

	/**
     * Format the metadata line
     *
     * @param	object $post
     * @return	string $_metadata
     */
	function fmt_post_metadata($post){

		$_metadata = '<div ';
		if (isset($this->classes_arr["post_metadata"])) {
			$_metadata .= 'class="'.$this->classes_arr["post_metadata"].'"';
		}
		$_metadata .= '>';
		$_metadata .= $this->build_line($this->metaline_fmt,$post);
		$_metadata .= '</div>';
		return $_metadata;
	}

	/**
     * build a display line from values in array
     *
     * @param	array $byline_fmt or metaline_fmt
     * @return	string   byline or metadata
     */
	function build_line($_arr,$post){
		$_sep = $_arr[0];
		unset($_arr[0]);
		$wk='';

		foreach ($_arr as $key) {
			switch ($key){
				case "auth":
					$wk .= $this->auth_fmt[1].get_the_author().$this->auth_fmt[2];
					break;
				case "cat":
					$wk .= $this->cat_fmt[1].$this->get_my_cats($post->ID,$this->cat_fmt[0]).$this->cat_fmt[2];
					break;
				case "cmt":
					ob_start();
					comments_popup_link($this->cmt_fmt[0], $this->cmt_fmt[1], $this->cmt_fmt[2]);
					$wk .= ob_get_clean();
					break;
				case "dp":
					$wk .= $this->dp_fmt[1].mysql2date($this->dp_fmt[0], $post->post_date).$this->dp_fmt[2];
					break;
				case "dm":
					$wk .= $this->dm_fmt[1].mysql2date($this->dm_fmt[0], $post->post_modified).$this->dm_fmt[2];
					break;
				case "tag":
					$wk .= $this->tag_fmt[1].$this->get_my_tags($post->ID,$this->tag_fmt[0]).$this->tag_fmt[2];
					break;
				case "tm":
					$wk .= $this->tm_fmt[1].mysql2date($this->tm_fmt[0], $post->post_modified).$this->tm_fmt[2];
					break;
				case "tp":
					$wk .= $this->tp_fmt[1].mysql2date($this->tp_fmt[0], $post->post_date).$this->tp_fmt[2];
					break;
			}

			$wk .= $_sep;
		}
		return trim($wk,$_sep);

	}

	/**
     * Magazine layout
     *
	 * output the image with all post text floating to the right
	 *
     * @param	object	$post
	 * @param	num		id
     * @return	char	$wkcontent
     */
	function magazine_layout($post,$id) {
		$content='';
		// add thumbnail to content
		$content = $this->fmt_post_thumbnail($post);
		// wrap tile, content & meta with div
		$content .= '<div class="'.$this->classes_arr['mag_content'].'">';

		foreach ( $this->fields_list as $field ) {
			$wkcontent='';
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
					$wkcontent = $this->filter_post_content($wkcontent,$post);
					break;
				case "metadata":
					$wkcontent = $this->fmt_post_metadata($post);
					$wkcontent = $this->filter_post_metadata($wkcontent,$post);
					break;
			}
			$content .= $wkcontent;
		}

		// complete mag div
		$content .= '</div>';
		return $content;
	}

	/**
     * Featured image layout
     *
	 * output the image with all post text below
	 *
     * @param	object	$post
	 * @param	num		id
     * @return	char	$wkcontent
     */
	function feat_image_layout($post,$id) {
		//if thumbnail image class passed use it else default to center
		if (!array_key_exists('thumbnail_img',$this->r)) {
			$this->classes_arr['thumbnail_img']='aligncenter';
		}

		// wrap tile, content & meta with div
		$content = '<div class="'.$this->classes_arr['fi_content'].'">';

		// add thumbnail to content
		$content .= $this->fmt_post_thumbnail($post);
		//}

		foreach ( $this->fields_list as $field ) {
			$wkcontent='';
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
					$wkcontent = $this->filter_post_content($wkcontent,$post);
					break;
				case "metadata":
					$wkcontent = $this->fmt_post_metadata($post);
					$wkcontent = $this->filter_post_metadata($wkcontent,$post);
					break;
			}
			$content .= $wkcontent;
		}

		// complete fi div
		$content .= '</div>';
		return $content;
	}

	/**
     * set_thumbnail_attr
     *
	 * set the attributes for the thumbnail
	 *
	 * @param   obj     $post   (post object)
     * @param 	array	$attr
     * @return	array	$attr
     */
	function set_thumbnail_attr($post,$_attr) {

		if ($this->thumbnail_title == 'post') {
			$_attr['title'] = get_post($post->ID)->post_title;
		} elseif ($this->thumbnail_title == 'image') {
			// * images are stored as custom posts - use get_post for title, description, caption
			// * Image title is post_title
			// * description is post_content
			// * caption is post_excerpt
			// * alt text is   $alt = get_post_meta(get_post_thumbnail_id($post->ID),'_wp_attachment_image_alt',true);
			// *
			$_meta = get_post(get_post_thumbnail_id($post->ID));
			if ($_meta) {
				$_attr['title'] = $_meta->post_title;
			} else {
				$_attr['title'] = '';
			}
		}
		return $_attr;
	}

	/**
     * pst_title_filter
     *
	 * call the custom function defined in the  user custom functions file
	 *
     * @param 	string	$content
     * @param 	string	custom function parms
     * @return	string	$content
     */
	function pst_title_filter($content,$cfp) {
		if (function_exists('tpg_gp_pst_title_filter') ) {
			$content = tpg_gp_pst_title_filter($content,$cfp);
		}
		return $content;
	}
	/**
     * pst_byline_filter
     *
	 * call the custom function defined in the  user custom functions file
	 *
     * @param 	string	$content
     * @param 	string	custom function parms
     * @return	string	$content
     */
	function pst_byline_filter($content,$cfp) {
		if (function_exists('tpg_gp_pst_byline_filter') ) {
			$content = tpg_gp_pst_byline_filter($content,$cfp);
		}
		return $content;
	}

	/**
     * pst_content_filter
     *
	 * call the custom function defined in the  user custom functions file
	 *
     * @param 	string	$content
     * @param 	string	custom function parms
     * @return	string	$content
     */
	function pst_content_filter($content,$cfp) {
		if (function_exists('tpg_gp_pst_content_filter') ) {
			$content = tpg_gp_pst_content_filter($content,$cfp);
		}
		return $content;
	}
	/**
     * pst_metadata_filter
     *
	 * call the custom function defined in the  user custom functions file
	 *
     * @param 	string	$content
     * @param 	string	custom function parms
     * @return	string	$content
     */
	function pst_metadata_filter($content,$cfp) {
		if (function_exists('tpg_gp_pst_metadata_filter') ) {
			$content = tpg_gp_pst_metadata_filter($content,$cfp);
		}
		return $content;
	}
	/**
     * pre_post_filter
     *
	 * call the custom function defined in the  user custom functions file
	 *
     * @param 	string	$content
     * @param 	string	custom function parms
     * @return	string	$content
     */
	function pre_post_filter($content,$cfp) {
		if (function_exists('tpg_gp_pre_post_filter') ) {
			$content = tpg_gp_pre_post_filter($content,$cfp);
		}
		return $content;
	}
	/**
     * pst_post_filter
     *
	 * call the custom function defined in the user custom functions file
	 *
     * @param 	string	$content
     * @param 	string	custom function parms
     * @return	string	$content
     */
	function pst_post_filter($content,$cfp) {
		if (function_exists('tpg_gp_pst_post_filter') ) {
			$content = tpg_gp_pst_post_filter($content,$cfp);
		}
		return $content;
	}
	/**
     * pre_plugin_filter
     *
	 * call the custom function defined in the  user custom functions file
	 *
     * @param 	string	$content
     * @param 	string	custom function parms
     * @return	string	$content
     */
	function pre_plugin_filter($content,$cfp) {
		if (function_exists('tpg_gp_pre_plugin_filter') ) {
			$content = tpg_gp_pre_plugin_filter($content,$cfp);
		}
		return $content;
	}
	/**
     * pst_plugin_filter
     *
	 * call the custom function defined in the user custom functions file
	 *
     * @param 	string	$content
     * @param 	string	custom function parms
     * @return	string	$content
     */
	function pst_plugin_filter($content,$cfp) {
		if (function_exists('tpg_gp_pst_plugin_filter') ) {
			$content = tpg_gp_pst_plugin_filter($content,$cfp);
		}
		return $content;
	}

}//end class
?>
