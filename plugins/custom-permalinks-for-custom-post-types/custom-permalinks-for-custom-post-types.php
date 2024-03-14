<?php /*
	
	**************************************************************************
	
	Plugin Name:  Custom Permalinks for Custom Post Types
	Description:  Removes the slug and customizable permalinks of custom post types
	Version:      1.0.1
	Author:       _____
	
**************************************************************************/

class P105_CustomPermalinks {
	
	// Plugin initialization
	public function __construct(){
		load_plugin_textdomain('custom-permalinks-for-custom-post-types');
		
		add_action('load-options-permalink.php', array($this, 'settings_permalink'));
	
		add_filter('post_type_link', array($this, 'custom_cpt_slug'), 10, 2);
		
		add_action('pre_get_posts', array($this, 'set_cpt_to_main_query'));
		add_filter('rewrite_rules_array', array($this, 'custom_rewrites'));
	}
	
	public function custom_cpt_slug($link, $post){
		if(strpos($link, '&p=')!==false) return $link;
		if(!isset($GLOBALS['p105_permalink_structure_cpt'])){
			$permalinks_options = get_option('p105_permalink_structure_cpt');
			$GLOBALS['p105_permalink_structure_cpt'] = $permalinks_options;
		}
		else $permalinks_options = $GLOBALS['p105_permalink_structure_cpt'];
		if(!isset($permalinks_options[$post->post_type])) return $link;
		if($permalinks_options[$post->post_type]['exclude']) return $link;
		if(isset($GLOBALS['links_cached'][$link])) return $GLOBALS['links_cached'][$link];
		$post_type_slug = trim(get_post_type_object($post->post_type)->rewrite['slug'], '/');
		if(class_exists('SitePress')){ // WPML
			$is_wpml = true;
			$translatable = apply_filters('wpml_sub_setting', false, 'custom_posts_sync_option', $post->post_type);
		}
		else $is_wpml = false;
		if($is_wpml && $translatable){
			$pos = strpos($link, '/'.$post_type_slug.'/');
			if($pos===false){ // not default language
				$post_language_info = apply_filters('wpml_post_language_details', NULL, $post->ID);
				$pos = strpos($link, '/'.$post_language_info['language_code'].'/');
				$tmp = explode('/', $link);
				if($pos!==false){
					$index = array_search($post_language_info['language_code'], $tmp);
					$post_type_slug = $tmp[$index+1];
					$new_link = substr($link, 0, $pos+3);
				}
				elseif(strpos($link, '/'.$post_language_info['language_code'].'.')!==false){
					$post_type_slug = apply_filters('wpml_get_translated_slug', $post_type_slug, $post->post_type, $post_language_info['language_code']);
					$pos = strpos($link, '/'.$post_type_slug.'/');
					if($pos!==false) $new_link = substr($link, 0, $pos);
				}
			}
			else{
				$new_link = substr($link, 0, $pos);
			}
		}
		if(!isset($new_link)) $new_link = home_url();
		if(in_array($post->post_status, array('publish', 'future', 'draft'))) {
			$priority_terms = get_option('p105_priority_terms', array());
			$permalink_cpt = $permalinks_options[$post->post_type];
			$new_link .= ($permalink_cpt['remove_slug']?'':'/'.$post_type_slug);
			$new_link .= $permalink_cpt['struct'];
			$date_ary = date_parse($post->post_date);
			$tags = array('%year%'=>$date_ary['year'], '%monthnum%'=>$date_ary['month'], '%day%'=>$date_ary['day'], '%hour%'=>$date_ary['hour'], '%minute%'=>$date_ary['minute'], '%second%'=>$date_ary['second'], '%post_id%'=>$post->ID, '%postname%'=>$post->post_name, '%author%'=>$post->post_author);
			if(is_post_type_hierarchical($post->post_type) && $post->post_parent){
				$post_parent = get_post($post->post_parent);
				$post_parent_name = $post_parent->post_name.'/';
				while($post_parent->post_parent){
					$post_parent = get_post($post_parent->post_parent);
					$post_parent_name = $post_parent->post_name.'/'.$post_parent_name;
				}
				$tags['%postname%'] = $post_parent_name.$tags['%postname%'];
			}
			foreach($tags as $tag=>$v){
				if($tag == '%author%'){
					$v = get_the_author_meta('user_nicename', $v);
				}
				if($tag == '%postname%' && strpos($link, '%'.$post->post_type.'%')!==false){ // fix missing edit post slug button
					if(isset($post_parent_name)) $new_link = str_replace($tag, $post_parent_name.$tag, $new_link);
					continue;
				}
				if(strpos($new_link, $tag)!==false){
					$new_link = str_replace($tag, $v, $new_link);
				}
			}
			foreach(get_object_taxonomies($post->post_type) as $tax){
				if($tax == 'post_tag') continue;
				if(strpos($new_link, '%'.$tax.'%')!==false){
					$terms = get_the_terms($post->ID, $tax);
					$terms_slug = '';
					if($terms){
						$terms_k = array();
						$terms_parent_index = array();
						foreach($terms as $k=>$term){
							$terms_k[$term->term_id] = $k;
							$terms_parent_index[$term->parent][] = $term->term_id;
						}
						$terms_index = array_flip($priority_terms[$tax]);
						$next_id = 0;
						$stack = array();
						while(isset($terms_parent_index[$next_id])){
							$tmp = array();
							foreach($terms_parent_index[$next_id] as $term_id){
								$tmp[$terms_index[$term_id]] = $term_id;
							}
							ksort($tmp);
							$first_key = key($tmp);
							$next_id = $tmp[$first_key];
							$terms_slug .= $terms[$terms_k[$next_id]]->slug.'/';
						}
					}
					if(!$terms_slug) $tag = '/%'.$tax.'%';
					else $tag = '%'.$tax.'%';
					$new_link = str_replace($tag, rtrim($terms_slug, '/'), $new_link);
				}
			}
			$GLOBALS['links_cached'][$link] = $new_link;
			$link = $new_link;
		}
		return $link;
	}
	
	public function set_cpt_to_main_query($query) {
		if ( ! $query->is_main_query() || ((empty($query->query['name']) && empty($query->query['p']))) ) {
			return;
		}
		global $wpdb;
		if(empty($query->query['post_type'])){
			if(!empty($query->query['name'])){
				$post_type = $wpdb->get_var($wpdb->prepare("SELECT post_type FROM $wpdb->posts WHERE post_name='%s'", $query->query['name']));
			}
			elseif(!empty($query->query['p'])){
				$post_type = $wpdb->get_var($wpdb->prepare("SELECT post_type FROM $wpdb->posts WHERE id=%d", $query->query['p']));
			}
			else $post_type = '';
		}
		else $post_type = $query->query['post_type'];
		
		if(!$post_type) return; // fix _wp_old_slug
		
		
		$query->set('post_type', array('post', 'page', $post_type));
	}
	
	public function custom_rewrite_rule(&$args) {
		$permalinks_options = get_option('p105_permalink_structure_cpt');
		if(!$permalinks_options) return array();
		$permalink_structure = get_option('permalink_structure');
		$tags_regex = array('%year%'=>'([0-9]{4})', '%post_id%'=>'([0-9]{1,})', '%postname%'=>'([^./]+)', '%author%'=>'([^/]+)');
		$tags_regex['%monthnum%'] = $tags_regex['%day%'] = $tags_regex['%hour%'] = $tags_regex['%minute%'] = $tags_regex['%second%'] = '([0-9]{1,2})';
		$tags_redirect = array('%year%'=>'year', '%monthnum%'=>'monthnum', '%day%'=>'day', '%hour%'=>'hour', '%minute%'=>'minute', '%second%'=>'second', '%post_id%'=>'p', '%postname%'=>'name', '%author%'=>'author_name');
		$rules = array();
		$args['post_types'] = array();
		foreach($permalinks_options as $post_type=>$permalink_cpt){
			if($permalinks_options[$post_type]['exclude']) continue;
			if($permalink_cpt['remove_slug'] && $permalink_structure == $permalink_cpt['struct']) continue;
			$args['post_types'][] = $post_type.'=';
			$tags_regex_tmp = $tags_regex;
			$tags_redirect_tmp = $tags_redirect;
			foreach(get_object_taxonomies($post_type) as $tax){
				if($tax == 'post_tag') continue;
				$tags_regex_tmp['%'.$tax.'%']= '(.+)';
				$tags_redirect_tmp['%'.$tax.'%']= 'category_name';
			}
			$status = 0;
			if($permalink_cpt['remove_slug']){
				$struct = ltrim($permalink_cpt['struct'], '/');
				$redirect = 'index.php?';
			}
			else{
				$struct = '[^/]+'.$permalink_cpt['struct'];
				$redirect = 'index.php?post_type='.$post_type;
			}
			$regex = $struct;
			$tag = '%';
			$index = 0;
			for($i=0,$n=strlen($struct);$i<$n;$i++){
				if($struct[$i]=='%'){
					if($status==1){
						$tag .= '%';
						if(isset($tags_regex_tmp[$tag])){
							$index++;
							$regex = str_replace($tag, $tags_regex_tmp[$tag], $regex);
							$redirect .= '&'.$tags_redirect_tmp[$tag].'=$matches['.$index.']';
						}
						$tag = '%';
					}
					$status = 1-$status;
				}
				elseif($status==1){
					$tag .= $struct[$i];
				}
			}
			$after = 'top';
			if($permalink_cpt['struct'] == '/%postname%'){ // fix category
				$after = 'bottom';
			}
			$redirect = str_replace('?&', '?', $redirect);
			if($regex[strlen($regex)-1]=='/') $regex .= '?';
			$regex = str_replace('(.+)/', '(?:(.+)/)?', $regex);
			if(is_post_type_hierarchical($post_type)){
				$pos_cat = strpos($regex, '(?:(.+)/)?');
				$pos_pname = strpos($regex, '([^./]+)');
				if($pos_pname!==false){
					if(($pos_cat!==false && $pos_cat > $pos_pname) || $pos_cat===false){
						$regex = str_replace('([^./]+)', '(?:[^.]+/)?([^./]+)', $regex);
					}
				}
			}
			$rules[$regex.'(?:/([0-9]+))?$'] = $redirect.'&page=$matches['.($index+1).']'; // support paging
			if(post_type_supports($post_type, 'comments')){
				$rules[$regex.'/comment-page-([0-9]{1,})/?$'] = $redirect.'&cpage=$matches['.($index+1).']';
			}
			$redirect = str_replace('&name=', '&pname=', $redirect);
			$redirect = str_replace('post_type=', '&ptype=', $redirect);
			$rules[$regex.'/([^./]+)$'] = $redirect.'&attachment=$matches['.($index+1).']'; // fix attachment
		}
		
		return $rules;
	}
	
	public function custom_rewrites($rules){
		$args = array();
		$new_rules = $this->custom_rewrite_rule($args);
		// insert new rules after the taxonomies and before the posts rules
		$index = array_search('.*wp-register.php$', array_keys($rules));
		$rules = array_slice($rules, 0, $index, true) + $new_rules + array_slice($rules, $index, null, true);
		// remove overlap rules
		foreach($rules as $regex=>$redirect){
			if(strpos($regex, '+/([^/]+)/?$')!==false && strpos($redirect, 'attachment')!==false){
				unset($rules[$regex]);
			}
			if(strpos($regex, 'comment-page-')!==false && str_replace($args['post_types'], '', $redirect) != $redirect){
				unset($rules[$regex]);
			}
			// remove default post type rules, fix for .html
			if(strpos($regex, '([^/]+)(?:/([0-9]+))?/?$')!==false && str_replace($args['post_types'], '', $redirect) != $redirect){
				unset($rules[$regex]);
			}
			if(strpos($regex, '/(.+?)(?:/([0-9]+))?/?$')!==false && str_replace($args['post_types'], '', $redirect) != $redirect){
				unset($rules[$regex]);
			}
		}
		return $rules;
	}
	
	public function settings_permalink(){
		if(isset($_POST['permalink_structure_cpt'])){
			$permalink_structure_cpt = $_POST['permalink_structure_cpt'];
			// sanitizing array
			array_walk($permalink_structure_cpt, function(&$value, &$key) {
				$value['remove_slug'] = (int)sanitize_key($value['remove_slug']);
				$value['exclude'] = (int)sanitize_key($value['exclude']);
				$value['struct'] = esc_url_raw($value['struct']);
			});
			
			$priority_terms = $_POST['priority_terms'];
			// sanitizing array
			array_walk($priority_terms, function(&$value, &$key) {
				foreach($value as $k=>$v) $value[$k] = (int)sanitize_key($v);
			});
			
			update_option('p105_permalink_structure_cpt', $permalink_structure_cpt);
			update_option('p105_priority_terms', $priority_terms);
			
			return;
		}
		add_settings_section('p105_permalink_section', __('Custom Permalinks for Custom Post Types', 'custom-permalinks-for-custom-post-types'), array( $this, 'permalink_section' ), 'permalink');
		
	}
	
	public function permalink_section(){
		?>
		<script type="text/javascript">
			(function($) {
				$(document).ready(function(){
					$('.remove_cpt_slug').change(function(e) {
						if($(this).is(':checked')){
							$(this).closest('td').find('code>span').hide();
						}
						else $(this).closest('td').find('code>span').show();
					});
					
					$('.exclude_cpt').change(function(e) {
						if($(this).is(':checked')){
							$(this).closest('td').find('fieldset').attr('disabled','disabled');
							$(this).closest('td').find('.permalink_structure_cpt').attr('readonly','readonly');
							$(this).closest('td').find('.remove_cpt_slug').attr('onclick','return false;').attr('readonly','readonly');
						}
						else{
							$(this).closest('td').find('fieldset').removeAttr('disabled');
							$(this).closest('td').find('.permalink_structure_cpt').removeAttr('readonly');
							$(this).closest('td').find('.remove_cpt_slug').removeAttr('onclick').removeAttr('readonly');
						}
					});
					
					$('.structure-tags button').click(function(e) {
						var txt = $(this).closest('td').find('input.permalink_structure_cpt'), txtVal = txt.val(), txtToAdd = $(this).text();
						if($(this).hasClass('active')){
							if(txtVal.indexOf('%/'+txtToAdd+'/%')>=0) txt.val(txtVal.replace('/'+txtToAdd+'/', '/'));
							else if(txtVal.indexOf('%/'+txtToAdd)>=0) txt.val(txtVal.replace('/'+txtToAdd, ''));
							else if(txtVal.indexOf(txtToAdd+'/%')>=0) txt.val(txtVal.replace(txtToAdd+'/', ''));
							else txt.val(txtVal.replace('/'+txtToAdd+'/', ''));
							$(this).removeClass('active');
							return false;
						}
						if(txt.hasClass('focused')){
							var caretStartPos = txt[0].selectionStart, caretEndPos = txt[0].selectionEnd;
						}
						else{
							var caretStartPos = txtVal.length, caretEndPos = caretStartPos;
						}
						if(txtVal[caretStartPos-1]!='/') txtToAdd = '/'+txtToAdd;
						if(txtVal[caretEndPos]!='/') txtToAdd = txtToAdd+'/';
						var caretAfterPos = (txtVal.substring(0, caretStartPos) + txtToAdd).length;
						txt.val(txtVal.substring(0, caretStartPos) + txtToAdd + txtVal.substring(caretEndPos));
						$(this).addClass('active');
						txt.trigger('focusout');
						txt[0].selectionStart = txt[0].selectionEnd = caretAfterPos;
						txt.focus();
					});
					
					$('input.permalink_structure_cpt').focusin(function() {
						$(this).addClass('focused');
					});
					$('input.permalink_structure_cpt').focusout(function() {
						var txt = $(this);
						txt.closest('td').find('button').each(function(index, value){
							if(txt.val().indexOf($(this).text()) < 0){
								$(this).removeClass('active');
							}
							else $(this).addClass('active');
						});
					});
					$('#taxonomies_order ul').sortable({
						cursor: 'move',
						update: function (event, ui) {
							$(this).closest('.tax').find('.primary_term').text($(this).find('li:first').text());
						}
					});
					
					$('#taxonomies_order .tax').click(function(e) {
						$(this).toggleClass('active').find('.panel').toggle();
					});
				});
			})( jQuery );
		</script>
		<style type="text/css">
			td label {vertical-align:baseline; margin-right:10px;}
			.structure-tags li {float:left;margin-right:5px;}
			
			#taxonomies_order .tax {display:inline-block; background:#e0e0e0; padding:2px 30px 2px 10px; margin-right:10px; margin-bottom:10px; min-width:200px; cursor:pointer; position:relative;}
			#taxonomies_order .tax .toggle-indicator {position:absolute; top:1px; right:5px;}
			#taxonomies_order .tax .toggle-indicator:after {position:absolute; content:"\f140"; top:0; right:0; font:400 20px/1 dashicons;}
			#taxonomies_order .tax.active .toggle-indicator:after {content:"\f142";}
			#taxonomies_order .tax .panel {position:absolute; left:0; right:0; background:#e0e0e0; padding:5px 10px; z-index:1;}
			#taxonomies_order .tax .note {font-size:11px; font-style:italic;}
			#taxonomies_order .tax ul {}
			#taxonomies_order .tax ul>li {background:#fff; padding:2px 10px; font-size:11px; cursor:all-scroll; margin-top:3px;}
			#taxonomies_order .tax ul>li.child {margin-left:10px;}
		</style>
		<?php
		$permalinks_options = get_option('p105_permalink_structure_cpt');
		$priority_terms = get_option('p105_priority_terms', array());
		// Add settings fields to the permalink page
		$GLOBALS['taxs_used'] = array();
		foreach(get_post_types( array('public' => true, '_builtin' => false), 'objects' ) as $post_type) {
			if(empty($permalinks_options[$post_type->name])) $permalink_cpt = array('struct'=>'/%postname%', 'remove_slug'=>0, 'exclude'=>0);
			else $permalink_cpt = $permalinks_options[$post_type->name];
			add_settings_field('permalink_cpt_'.$post_type->name, __($post_type->label), array($this, 'cpt_field_callback'), 'permalink', 'p105_permalink_section', array('post_type'=>$post_type->name, 'permalink_cpt'=>$permalink_cpt));
		}
		if(isset($permalink_cpt)){
			wp_enqueue_script('jquery-ui-sortable');
			add_settings_field('permalink_cpt_terms', __('Set primary term', 'custom-permalinks-for-custom-post-types'), array($this, 'taxs_order_field_callback'), 'permalink', 'p105_permalink_section', $priority_terms);
		}
		else echo 'No Custom Post Type found!';
	}
	
	public function cpt_field_callback($args){
		$permalink_cpt = $args['permalink_cpt'];
		$post_type_slug = trim(get_post_type_object($args['post_type'])->rewrite['slug'], '/');
		echo '<code>'.home_url().'<span'.($permalink_cpt['remove_slug']?' style="display:none"':'').'>/'.$post_type_slug.'</span></code>';
		?>
		<input type="hidden" name="permalink_structure_cpt[<?php echo $args['post_type']; ?>][remove_slug]" value="0"/>
		<input type="hidden" name="permalink_structure_cpt[<?php echo $args['post_type']; ?>][exclude]" value="0"/>
		<input name="permalink_structure_cpt[<?php echo $args['post_type']; ?>][struct]" type="text" value="<?php echo esc_attr($permalink_cpt['struct']); ?>" <?php echo ($permalink_cpt['exclude']?'readonly':''); ?> class="regular-text code permalink_structure_cpt"> <label><input type="checkbox" class="remove_cpt_slug" name="permalink_structure_cpt[<?php echo $args['post_type']; ?>][remove_slug]" value="1" <?php echo ($permalink_cpt['remove_slug']?'checked':'').($permalink_cpt['exclude']?' readonly onclick="return false;"':''); ?>/> <?php _e('Remove CPT slug', 'custom-permalinks-for-custom-post-types'); ?></label> <label><input type="checkbox" class="exclude_cpt" name="permalink_structure_cpt[<?php echo $args['post_type']; ?>][exclude]" value="1" <?php echo ($permalink_cpt['exclude']?'checked':''); ?>/> <?php _e('Exclude this CPT', 'custom-permalinks-for-custom-post-types'); ?></label>
		<fieldset class="structure-tags hide-if-no-js"<?php echo ($permalink_cpt['exclude']?' disabled="disabled"':''); ?>>
			<p><?php _e('Available tags', 'custom-permalinks-for-custom-post-types'); ?>:</p>
			<ul role="list">
			<?php
			$tags = array('%year%', '%monthnum%', '%day%', '%hour%', '%minute%', '%second%', '%post_id%', '%postname%', '%author%');
			foreach(get_object_taxonomies($args['post_type']) as $tax){
				if(in_array($tax, array('post_tag', 'language', 'post_translations'))) continue;
				$tags[]= '%'.$tax.'%';
				$GLOBALS['taxs_used'][$tax] = $tax;
			}
			foreach($tags as $tag) echo '<li><button type="button" class="button button-secondary'.(strpos($permalink_cpt['struct'], $tag)!==false?' active':'').'">'.$tag.'</button></li>';
			?>
			</ul>
		</fieldset>
		<?php
	}
	
	public function taxs_order_field_callback($args){
		?>
		<div id="taxonomies_order">
		<?php
		foreach($GLOBALS['taxs_used'] as $tax){
			$terms = get_terms($tax, array('hide_empty'=>false));
			if($terms){
				if(isset($args[$tax])){
					$terms_index = array_flip($args[$tax]);
					foreach($terms as $term){
						$terms_index[$term->term_id] = $term;
					}
					foreach($terms_index as $term_id=>$term){
						if(!is_object($term)) unset($terms_index[$term_id]); // unset terms deleted
					}
					$terms = $terms_index;
				}
				reset($terms);
				$first_key = key($terms);
				$primary_term = $terms[$first_key]->name;
			}
			else $primary_term = '';
			echo '<div class="tax">'.$tax.': <span class="primary_term">'.$primary_term.'</span><span class="toggle-indicator" aria-hidden="true"></span>';
			echo '<div class="panel" style="display:none;"><div class="note">'.__('Drag & drop to rearrange order', 'custom-permalinks-for-custom-post-types').'</div><ul>';
			foreach($terms as $term){
				echo '<li class="sortable-item'.($term->parent?' child':'').'">'.$term->name.'<input type="hidden" name="priority_terms['.$tax.'][]" value="'.$term->term_id.'" /></li>';
			}
			echo '</ul></div></div>';
		}
		?>
		</div>
		<?php
	}
	
}

// Start plugin
add_action('init', 'P105_CustomPermalinks', 11);
function P105_CustomPermalinks() {
	$P105_CustomPermalinks = new P105_CustomPermalinks();
}
