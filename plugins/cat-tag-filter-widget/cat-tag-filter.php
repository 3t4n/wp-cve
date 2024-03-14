<?php
/*
Plugin Name: Cat + Tag Filter
Plugin URI: http://wordpress.org/extend/plugins/cat-tag-filter-widget/
Description: This plugin adds a widget to your WordPress site that allows your visitors to filter posts by category and tag.
Author: Ajay Verma
Version: 0.9.1
Author URI: http://ajayver.com/ 
*/
/*  Copyright 2011  Verma Ajay  (email : ajayverma1986@gmail.com)
    
*/

if ($_POST["ctf_submit"] == 1) { //if submit button was pressed
	require( '../../../wp-load.php' ); //require wordpress core functions
	$cat = '';	//initialize variables for category and tag parts of the url
	$tag = '';
	$tag_prefix = $_POST["tag_prefix"] .'/'; //get tag and category prefix
	$cat_prefix = $_POST["cat_prefix"] .'/';
	if ($_POST["tag_logic"] == 1) $tag_logic = '+'; //logic for tags (AND or OR)
	else $tag_logic = ',';
	if ($_POST["cat"] != -1) { //if a category was chosen
		$_POST['cat'] = get_term($_POST['cat'], $_POST['category_tax']); //get category slug from it's id
		$cat = '/'. $cat_prefix . $_POST['cat']->slug; //add it to category part of the url
		$tag_prefix = '/' . $_POST["tag_prefix"] . '/'; //add & to tag part of url
	} 

	if ($_POST["tag"] && $_POST["tag"][0] != -1) { //if any tags were chosen
		$tag .= $tag_prefix; //add tag prefix to tag part of url
		$i = 0;
		foreach ($_POST["tag"] as $tags){	
			if ($i > 0) $tag .= $tag_logic; //add all tags with ',' or '+' between them depends on logic
			$tag .= $tags;	
			$i++;
		}
	}	
	
	if ($cat == '' && $tag == '' && $_POST["blog_url"] != '') $url = $_POST["home_url"] . '/' . $_POST["blog_url"]; //if no category or no tag was chosen, just create a link to blog
	else $url = $_POST["home_url"]. '/' . $cat . $tag;  //else, create a link to blog + category and tag arguments
	header('Location: ' . $url); //redirect user to the created url
	exit;
	
}
else {
	if (basename($_SERVER['SCRIPT_NAME']) == basename(__FILE__)) exit('Please do not load this page directly');
	}
	
function current_tax() { //determine current tag and category
	global $wp_query, $ctf_options, $current_tax;

	$queries = $wp_query->tax_query->queries;

	if(!empty($queries)){
		foreach ($queries as $query) {
			if ($query['taxonomy'] == $ctf_options['tag_tax']) $current_tax['tags'] = $query['terms'];
			else if ($query['taxonomy'] == $ctf_options['category_tax']) $current_tax['cats'] = $query['terms'];
		}

		if(!empty($current_tax['cats'])){
			foreach ($current_tax['cats'] as $current_cat => $value) {
				$cat_id = get_term_by('slug', $value, $ctf_options['category_tax']);
				$current_tax['cats'][$current_cat] = $cat_id->term_id;
			}
		}

		return $current_tax; 
	}
}


function cat_options(){ //get categories dropdown list
  global $ctf_options, $categories, $current_tax;
       
  $args = array(
  'show_option_none' => $ctf_options['all_cats_text'],
  'taxonomy'     => $ctf_options['category_tax'],
  'show_count'   => $ctf_options['cats_count'],
  'pad_counts'   => 1,
  'hierarchical' => 1,
  'selected' => $current_tax['cats'][0],
  $ctf_options['clude_cats']   	 => $ctf_options['exclude_cats'],
  'orderby' => 'name'
  );
  
  wp_dropdown_categories($args);
  
 
}
function tag_options($type){ //get tags
  global $ctf_options, $current_tax;
	$there_are_tags = true;
	$args = '';
	if($current_tax['cats'][0] && $ctf_options['corresponding_tags'] == 1){

		$cat_args = array(
			'cat'      => $current_tax[cats][0],
			'posts_per_page' => -1
		);
		query_posts($cat_args);
		if(have_posts()): while (have_posts()) : the_post();
			$all_tag_objects = get_the_terms($post->ID, $ctf_options['tag_tax']);
			if($all_tag_objects){	
				foreach($all_tag_objects as $tag) {
					if($tag->count > 0) $all_tag_ids[] = $tag->term_id;
					echo '<!--' . $tag->term_id . '-->';
				}
			}
		endwhile;
		endif;
		wp_reset_query();
		if (isset($all_tag_ids)){
		
		$tags = array_unique($all_tag_ids);
		
		if ($ctf_options['exclude_tags'] != ''){
			
			$ctf_options['exclude_tags'] = str_replace(" ", "", $ctf_options['exclude_tags']);
			$exclude = explode(',', $ctf_options['exclude_tags']);
			
			if ($ctf_options['clude_tags'] == 'include') {
				foreach ($tags as $tag){
					if (in_array($tag, $exclude)) $include .= $tag . ',';
				}
			}
			else if ($ctf_options['clude_tags'] == 'exclude') {
				foreach ($tags as $tag){
					if (!in_array($tag, $exclude)) $include .= $tag . ',';
				}
			}
			
			if ($include == '') $there_are_tags = false;
		
		}
		else {
			foreach ($tags as $tag){
				$include .= $tag . ',';
			}
		}
		$include = substr($include, 0, -1);
		
		
		$args['include'] = $include;
		}
		
		else $there_are_tags = false;
		
	
	}
	else {
		
		$args[$ctf_options['clude_tags']] = $ctf_options['exclude_tags'];
		
	}


  if ($there_are_tags) $tags = get_terms($ctf_options['tag_tax'],$args);

  
if ($type == 1){
	if ($there_are_tags){
	$options .= '<ul>';
	foreach ($tags as $tag) {
		$options .= '<li>'; 
		$options .= '<input type="checkbox" name="';
		$options .= "tag[]";
		$options .= '" value="' . $tag->slug . '"';
		if (is_array($current_tax['tags'])) {if (in_array($tag->slug, $current_tax['tags'])) $options .= ' checked ';}
		$options .= '>';
		$options .= $tag->name;
		if ($ctf_options['tags_count'] == 1) $options .= ' (' . $tag->count . ')';
		$options .= '</li>';    
	}
	$options .= '</ul>';
	}
	else $options .= '<ul><li><input type="checkbox" name="tag[]" value="-1" disabled>' .  __('No tags here', 'cat-tag-filter')  . '</li></ul>';
  }
  else {  
	if ($there_are_tags){
	$options .= '<select name="' . "tag[]" . '" id="ctf-tag-select" ><option value="-1">';
	if ($ctf_options['all_tags_text'] != '') $options .= $ctf_options['all_tags_text']; 
	else $options .=__('Any tag', 'cat-tag-filter');
	$options .= '</option>'; 
	foreach ($tags as $tag) {
		$options .= '<option value="' . $tag->slug . '"';
		if (is_array($current_tax['tags'])) {
			if (in_array($tag->slug, $current_tax['tags'])) $options .= ' selected="selected" ';
		}
		$options .= '>';
		$options .= $tag->name;
		if ($ctf_options['tags_count'] == 1) $options .= ' (' . $tag->count . ')';
		$options .= '</option>';    
		}
	$options .= '</select>';
	} 
	else $options .= '<select name="tag[]" id="ctf-tag-select" disabled ><option value="-1">' .  __('No tags here', 'cat-tag-filter')  . '</option></select>';
  }
  return $options;
}
function ctf_widget(){
	global $ctf_options; 
	
	current_tax();

  ?>

  <form action="<?php echo get_bloginfo('wpurl') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/cat-tag-filter.php'; ?>" method="post"> 
	<div id="ctf-category-selector" <?php if ($ctf_options['cat_list_hide']) echo 'style="display:none;"'; ?> >
		<?php if ($ctf_options['cat_list_label']) echo '<label for="ctf-cat-select">' . $ctf_options['cat_list_label'] . '</label>'; ?> 
		<?php  cat_options(); ?>
	</div>
	<div id="ctf-tag-selector">
		<?php if ($ctf_options['tag_list_label']) echo '<label for="ctf-tag-select">' . $ctf_options['tag_list_label'] . '</label>'; ?> 
		<?php echo tag_options($ctf_options['tag_type']); ?>  
	</div>
    <input type="hidden" name="ctf_submit" value="1" />
    <input type="hidden" name="home_url" value="<?php bloginfo('url'); ?>" /> 
	<input type="hidden" name="blog_url" value="<?php $blog_url = get_option( 'page_for_posts'); if ($blog_url != 0) echo get_page_uri($blog_url); ?>" />
	<input type="hidden" name="tag_logic" value="<?php echo $ctf_options['tag_logic'] ?>" />
	<?php $taxonomies=get_taxonomies('','','');	?>
	<input type="hidden" name="tag_prefix" value="<?php echo $taxonomies[$ctf_options['tag_tax']]->rewrite['slug'] ?>" />
	<input type="hidden" name="category_tax" value="<?php echo $ctf_options['category_tax'] ?>" />
	<input type="hidden" name="cat_prefix" value="<?php echo $taxonomies[$ctf_options['category_tax']]->rewrite['slug'] ?>" />
    <input id="ctf-submit" class="button" type="submit"  value="<?php echo $ctf_options['button_title']; ?>"/>  
  </form>  
  <?php
}
/**
 * Cat + Tag Filter Class
 */
  
class cat_tag_filter extends WP_Widget {
  /** constructor */
  function cat_tag_filter() {
    $widget_ops = array('classname' => 'cat-tag-filter', 'description' => __('Filter posts by category and tag', 'cat_tag_filter') );
    parent::WP_Widget(false, $name = 'Cat + Tag Filter', $widget_ops);	
  }

  static function install() {
		flush_rewrite_rules();
     }

  /** @see WP_Widget::widget */
  function widget($args, $instance) {	
	  $defaults = array( 'title' => __('Filter', 'cat-tag-filter'), 'button_title' => __('Show posts', 'cat-tag-filter'), 'cat_list_hide' => false,  'cat_list_label' => __('Show posts from:', 'cat-tag-filter'), 'tag_list_label' => __('With tag:', 'cat-tag-filter'), 'all_cats_text' => __('Any category', 'cat-tag-filter'), 'all_tags_text' => __('Any tag', 'cat-tag-filter'), 'corresponding_tags' => '1', 'cats_count' => 1, 'tags_count' => 0, 'tag_logic' => 1, 'tag_type' => 0, 'exclude_tags' => '', 'exclude_cats' => '', 'clude_tags' => 'exclude', 'clude_cats' => 'exclude', 'tag_tax' => 'post_tag', 'category_tax' => 'category');
    $instance = wp_parse_args( (array) $instance, $defaults );
    extract( $args );
    global $ctf_options;
    $ctf_options['title'] = apply_filters('widget_title', $instance['title']);
    $ctf_options['button_title'] = apply_filters('widget_title', $instance['button_title']);
	$ctf_options['cat_list_hide'] =  $instance['cat_list_hide'];
    $ctf_options['cat_list_label'] = apply_filters('widget_title', $instance['cat_list_label']);
    $ctf_options['tag_list_label'] = apply_filters('widget_title', $instance['tag_list_label']);
    $ctf_options['all_cats_text'] = apply_filters('widget_title', $instance['all_cats_text']);
    $ctf_options['all_tags_text'] = apply_filters('widget_title', $instance['all_tags_text']);
    $ctf_options['cats_count'] =  $instance['cats_count'];
	$ctf_options['corresponding_tags'] =  $instance['corresponding_tags'];
    $ctf_options['tags_count'] =  $instance['tags_count'];
	$ctf_options['tag_logic'] =  $instance['tag_logic'];
	$ctf_options['tag_type'] =  $instance['tag_type'];
	$ctf_options['exclude_tags'] = $instance['exclude_tags'];
	$ctf_options['clude_tags'] = $instance['clude_tags'];
	$ctf_options['exclude_cats'] = $instance['exclude_cats'];
	$ctf_options['clude_cats'] = $instance['clude_cats'];
	$ctf_options['category_tax'] = $instance['category_tax'];
	$ctf_options['tag_tax'] = $instance['tag_tax'];
    echo $before_widget; 
    if ( $ctf_options['title'] ) echo $before_title . $ctf_options['title'] . $after_title; 

    ctf_widget();
    echo $after_widget; 
  }
    /** @see WP_Widget::update */
  function update($new_instance, $old_instance) {				
  	$instance = $old_instance;
  	$instance['title'] = strip_tags($new_instance['title']);
  	$instance['button_title'] = strip_tags($new_instance['button_title']);
	$instance['cat_list_hide'] = $new_instance['cat_list_hide'];
  	$instance['cat_list_label'] = strip_tags($new_instance['cat_list_label']);
  	$instance['tag_list_label'] = strip_tags($new_instance['tag_list_label']);
  	$instance['all_cats_text'] = strip_tags($new_instance['all_cats_text']);
  	$instance['all_tags_text'] = strip_tags($new_instance['all_tags_text']);
  	$instance['cats_count'] = $new_instance['cats_count'];
	$instance['corresponding_tags'] = $new_instance['corresponding_tags'];
  	$instance['tags_count'] = $new_instance['tags_count'];
	$instance['tag_logic'] = $new_instance['tag_logic'];
	$instance['tag_type'] = $new_instance['tag_type'];
	$instance['exclude_tags'] = $new_instance['exclude_tags'];
	$instance['exclude_cats'] = $new_instance['exclude_cats'];
	$instance['clude_tags'] = $new_instance['clude_tags'];
	$instance['clude_cats'] = $new_instance['clude_cats'];
	$instance['category_tax'] = $new_instance['category_tax'];
	$instance['tag_tax'] = $new_instance['tag_tax'];
	flush_rewrite_rules();
    return $instance;
  }
  /** @see WP_Widget::form */
  function form($instance) {   
    $defaults = array( 'title' => __('Filter', 'cat-tag-filter'), 'button_title' => __('Show posts', 'cat-tag-filter'), 'cat_list_hide' => false, 'cat_list_label' => __('Show posts from:', 'cat-tag-filter'), 'tag_list_label' => __('With tag:', 'cat-tag-filter'), 'all_cats_text' => __('Any category', 'cat-tag-filter'), 'all_tags_text' => __('Any tag', 'cat-tag-filter'), 'corresponding_tags' => '1', 'cats_count' => 1, 'tags_count' => 0, 'tag_logic' => 1, 'tag_type' => 0, 'exclude_tags' => '', 'exclude_cats' => '', 'clude_tags' => 'exclude', 'clude_cats' => 'exclude', 'tag_tax' => 'post_tag', 'category_tax' => 'category' );
  	$instance = wp_parse_args( (array) $instance, $defaults ); 				
    ?>   
	<p>
		<strong>Please consider donating 10 cents if you like this plugin! :)</strong>	</p>
		<p>
	<a href="http://goo.gl/SCdKg"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" alt="donate" /></a>    
	</p>
    <p>    
      <label for="ctf-widget-title">
        <?php _e('Widget title', 'cat-tag-filter'); ?>: 
      </label>    
      <input type="text" id="ctf-widget-title" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']);?>" />   
    </p>  
	
	<p>        
      <input type="checkbox" id="ctf-cat-list-hide" name="<?php echo $this->get_field_name('cat_list_hide'); ?>" value="true" <?php if ($instance['cat_list_hide'] == true) echo "checked ";?>/>
      <label for="ctf-cat-list-hide">
        <?php _e('Hide categories dropdown', 'cat-tag-filter'); ?> 
      </label>   
    </p>
		
    <p>    
      <label for="ctf-cat-list-title">
        <?php _e('Categories dropdown label', 'cat-tag-filter'); ?>: 
      </label>    
      <input type="text" id="ctf-cat-list-title" name="<?php echo $this->get_field_name('cat_list_label'); ?>" value="<?php echo esc_attr($instance['cat_list_label']);?>" />   
    </p>  
    <p>    
      <label for="ctf-all-cats-text">
        <?php _e('All categories option text', 'cat-tag-filter'); ?>: 
      </label>    
      <input type="text" id="ctf-all-cats-text" name="<?php echo $this->get_field_name('all_cats_text'); ?>" value="<?php echo esc_attr($instance['all_cats_text']);?>" />   
    </p>  
    <p>        
      <input type="checkbox" id="ctf-cats-count" name="<?php echo $this->get_field_name('cats_count'); ?>" value="1" <?php if ($instance['cats_count'] == 1) echo "checked ";?>/>
      <label for="ctf-cats-count">
        <?php _e('Show categories post count', 'cat-tag-filter'); ?> 
      </label>   
    </p>
	<p>    
      <label for="ctf-exclude_cats">
        <?php _e('Coma separated category id\'s to ', 'cat-tag-filter'); ?><select name="<?php echo $this->get_field_name('clude_cats'); ?>" id="ctf-clude-cats" >   
      <option value="exclude" <?php if ($instance['clude_cats'] == 'exclude') echo 'selected="selected" ';?> >
      <?php _e('exclude', 'cat-tag-filter'); ?>
      </option>
	  <option value="include" <?php if ($instance['clude_cats'] == 'include') echo 'selected="selected" ';?> >
      <?php _e('include', 'cat-tag-filter'); ?>
      </option></select>: 
      </label>    
      <input type="text" id="ctf-exclude_cats" name="<?php echo $this->get_field_name('exclude_cats'); ?>" value="<?php echo esc_attr($instance['exclude_cats']);?>" />   
    </p>
	
    <p>    
      <label for="ctf-tag-list-title">
        <?php _e('Tags dropdown label', 'cat-tag-filter'); ?>: 
      </label>    
      <input type="text" id="ctf-tag-list-title" name="<?php echo $this->get_field_name('tag_list_label'); ?>" value="<?php echo esc_attr($instance['tag_list_label'])?>" />   
    </p>  
    <p>    
      <label for="ctf-all-tags-text">
        <?php _e('All tags option text', 'cat-tag-filter'); ?>: 
      </label>    
      <input type="text" id="ctf-all-tags-text" name="<?php echo $this->get_field_name('all_tags_text'); ?>" value="<?php echo esc_attr($instance['all_tags_text']);?>" />   
    </p>  
	<p>
	<label for="ctf-tag-type">
	<?php _e('How to show tags:', 'cat-tag-filter'); ?> 
	</label>
	<select name="<?php echo $this->get_field_name('tag_type'); ?>" id="ctf-tag-type" >   
      <option value="0" <?php if ($instance['tag_type'] == 0) echo 'selected="selected" ';?> >
      <?php _e('Dropdown list', 'cat-tag-filter'); ?>
      </option>   
	  <option value="1" <?php if ($instance['tag_type'] == 1) echo 'selected="selected" ';?> >
      <?php _e('Checkboxes', 'cat-tag-filter'); ?>
      </option>
    </select>
	</p>
	<p>
	<label for="ctf-tag-logic">
	<?php _e('Logic operator for multiple tags:', 'cat-tag-filter'); ?> 
	</label>
	<select name="<?php echo $this->get_field_name('tag_logic'); ?>" id="ctf-tag-logic" >   
      <option value="0" <?php if ($instance['tag_logic'] == 0) echo 'selected="selected" ';?> >
      <?php _e('OR', 'cat-tag-filter'); ?>
      </option>   
	  <option value="1" <?php if ($instance['tag_logic'] == 1) echo 'selected="selected" ';?> >
      <?php _e('AND', 'cat-tag-filter'); ?>
      </option>
    </select>
	</p>
    <p>        
      <input type="checkbox" id="ctf-tags-count" name="<?php echo $this->get_field_name('tags_count'); ?>" value="1" <?php if ($instance['tags_count'] == 1) echo "checked ";?>/>
      <label for="ctf-tags-count">
        <?php _e('Show tags post count', 'cat-tag-filter'); ?> 
      </label>   
    </p>  
	<p>        
      <input type="checkbox" id="ctf-corresponding-tags" name="<?php echo $this->get_field_name('corresponding_tags'); ?>" value="1" <?php if ($instance['corresponding_tags'] == 1) echo "checked ";?>/>
      <label for="ctf-corresponding-tags">
        <?php _e('Show only corresponding tags for current category', 'cat-tag-filter'); ?> 
      </label>   
    </p> 
	<p>    
      <label for="ctf-exclude_tags">
        <?php _e('Coma separated tag id\'s to ', 'cat-tag-filter'); ?><select name="<?php echo $this->get_field_name('clude_tags'); ?>" id="ctf-clude-tags" >   
      <option value="exclude" <?php if ($instance['clude_tags'] == 'exclude') echo 'selected="selected" ';?> >
      <?php _e('exclude', 'cat-tag-filter'); ?>
      </option>
	  <option value="include" <?php if ($instance['clude_tags'] == 'include') echo 'selected="selected" ';?> >
      <?php _e('include', 'cat-tag-filter'); ?>
      </option></select>: 
      </label>    
      <input type="text" id="ctf-exclude_tags" name="<?php echo $this->get_field_name('exclude_tags'); ?>" value="<?php echo esc_attr($instance['exclude_tags']);?>" />   
    </p>
	<p>    
      <label for="ctf-tag-tax">
        <?php _e('Custom taxonomy name for tags', 'cat-tag-filter'); ?>: 
      </label>    
      <input type="text" id="ctf-tag-tax" name="<?php echo $this->get_field_name('tag_tax'); ?>" value="<?php echo esc_attr($instance['tag_tax']);?>" />   
    </p>  
	<p>    
      <label for="ctf-category-tax">
        <?php _e('Custom taxonomy name for categories', 'cat-tag-filter'); ?>: 
      </label>    
      <input type="text" id="ctf-category-tax" name="<?php echo $this->get_field_name('category_tax'); ?>" value="<?php echo esc_attr($instance['category_tax']);?>" />   
    </p>  
	<ul><?php _e('List of registered taxonomies', 'cat-tag-filter'); ?>:
	<?php 
$taxonomies=get_taxonomies('','names'); 
foreach ($taxonomies as $taxonomy ) {
  echo '<li>'. $taxonomy. '</li>';
}
?></ul>
    <p>    
      <label for="ctf-button-title">
        <?php _e('Button title', 'cat-tag-filter'); ?>: 
      </label>    
      <input type="text" id="ctf-button-title" name="<?php echo $this->get_field_name('button_title'); ?>" value="<?php echo esc_attr($instance['button_title']);?>" />   
    </p>  
	
  <?php 

  }

} // class Cat + Tag Filter
// register Cat + Tag Filter widget
	if (function_exists('load_plugin_textdomain'))
	{
		load_plugin_textdomain('cat-tag-filter', '/' .PLUGINDIR. '/' .dirname(plugin_basename(__FILE__)) . '/languages/' );
	}
add_action('widgets_init', create_function('', 'return register_widget("cat_tag_filter");')); 

function add_rewrite_rules() {
    global $wp_rewrite;
 	$ctf_options = get_option('widget_cat_tag_filter');
	foreach ($ctf_options as $widget_options){
	 	if ($widget_options['category_tax']){
	 	
	 		$taxonomies = get_taxonomies('','','');
		
		    $new_rules = array(
		    	$taxonomies[$widget_options['category_tax']]->rewrite['slug'] . '/(.+?)/' . $taxonomies[$widget_options['tag_tax']]->rewrite['slug'] . '/(.+?)/page/?([0-9]{1,})/?$' => 'index.php?' . $taxonomies[$widget_options['category_tax']]->query_var . '=' . $wp_rewrite->preg_index(1) . '&' . $taxonomies[$widget_options['tag_tax']]->query_var . '=' . $wp_rewrite->preg_index(2) . '&paged=' . $wp_rewrite->preg_index(3),
		        $taxonomies[$widget_options['category_tax']]->rewrite['slug'] . '/(.+?)/' . $taxonomies[$widget_options['tag_tax']]->rewrite['slug'] . '/(.+?)/?$' => 'index.php?' . $taxonomies[$widget_options['category_tax']]->query_var . '=' . $wp_rewrite->preg_index(1) . '&' . $taxonomies[$widget_options['tag_tax']]->query_var . '=' . $wp_rewrite->preg_index(2)
		         
		    );
		    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

	 		}
	 	}
 	

 	
	}
	

add_action( 'generate_rewrite_rules', 'add_rewrite_rules' );

register_activation_hook( 'cat-tag-filter-widget/cat-tag-filter.php', array('cat_tag_filter', 'install') );

?>