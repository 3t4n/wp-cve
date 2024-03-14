<?php 
/***************************************
* Abort if called outside of WordPress
***************************************/
defined('ABSPATH') or die("Access Denied.");

function yada_wiki_scripts() {
	wp_enqueue_style( 'yada-wiki', plugins_url('../css/yadawiki.css', __FILE__) );
}

function yada_wiki_shortcode( $atts ) {
	extract( shortcode_atts( array( 
		'link' => '', 
		'show' => '', 
		'anchor' => '',
	), $atts ) ); 
	
	$link = sanitize_text_field($link);
	$show = sanitize_text_field($show);
	$anchor = sanitize_text_field($anchor);
	$anchor = preg_replace("/[^A-Za-z0-9._:-]/",'',$anchor);
	
	return get_yada_wiki_link( $link, $show, $anchor );
}

function get_yada_wiki_link( $wiki_page, $link_text, $anchor_jump ){
	$wiki_page   = trim($wiki_page);
	$wiki_page   = html_entity_decode($wiki_page);
	$link_text 	 = trim($link_text);
	$anchor_jump = trim($anchor_jump);
	$site 		 = get_option('siteurl');
	$target = get_posts(
	    array(
	        'post_type'              => 'yada_wiki',
	        'title'                  => $wiki_page,
	        'post_status'            => 'all',
	        'numberposts'            => 1,
	        'update_post_term_cache' => false,
	        'update_post_meta_cache' => false,           
	        'orderby'                => 'post_date ID',
	        'order'                  => 'ASC',
	    )
	);
	if($target) { $target=$target[0]; } 
	
	if($anchor_jump) {
		$firstchar = substr($anchor_jump,0,1);
		if ($firstchar != '#') {
			$anchor_jump = '#'.$anchor_jump;
		}
	}

	if(!$link_text){ $link_text = $wiki_page; }

	if($target && current_user_can('edit_posts')){
        if ($target->post_status == 'trash') {
            $just_text = '<span class="wikilink-trash">'.$link_text.'</span>';
            return $just_text;
        }
        elseif ($target->post_status == 'draft' || $target->post_status == 'auto-draft' || $target->post_status == 'pending' || $target->post_status == 'future') {
            $permalink = get_permalink($target->ID).$anchor_jump;
            return '<a href="'.$permalink.'" class="wikilink-pending">'.$link_text.'</a>';
        }
        elseif ($target->post_status == 'private') {
            $permalink = get_permalink($target->ID).$anchor_jump;
            return '<a href="'.$permalink.'" class="wikilink-private">'.$link_text.'</a>';
        }
        elseif ($target->post_status == 'publish') {
            $permalink = get_permalink($target->ID).$anchor_jump;
            return '<a href="'.$permalink.'" class="wikilink-published">'.$link_text.'</a>';
        }
        else {
            $permalink = get_permalink($target->ID).$anchor_jump;
            return '<a href="'.$permalink.'" class="wikilink-other">'.$link_text.'</a>';
        }
	} elseif ($target) {
        if ($target->post_status == 'trash' || $target->post_status == 'draft' || $target->post_status == 'auto-draft' || $target->post_status == 'pending' || $target->post_status == 'future') {
			$just_text = '<span class="wikilink-no-edit">'.$link_text.'</span>';
			return $just_text;
        }
        elseif ($target->post_status == 'private') {
            $permalink = get_permalink($target->ID).$anchor_jump;
            return '<a href="'.$permalink.'" class="wikilink-private">'.$link_text.'</a>';
        }
        elseif ($target->post_status == 'publish') {
            $permalink = get_permalink($target->ID).$anchor_jump;
            return '<a href="'.$permalink.'" class="wikilink-published">'.$link_text.'</a>';
        }		
        else {
            $permalink = get_permalink($target->ID).$anchor_jump;
            return '<a href="'.$permalink.'" class="wikilink-other">'.$link_text.'</a>';
        }
	} else {
		if ( current_user_can('edit_posts') ){
			$slug  = urlencode($wiki_page);
			$new_link = admin_url( 'post-new.php?post_type=yada_wiki&post_title='.$slug );
			return '<a href="'.$new_link.'" title="This wiki page does not yet exist. Create it (requires valid access/permissions)" class="wikilink-new">'.$link_text.'</a>';
		} 
		else{
			$just_text = '<span class="wikilink-no-edit">'.$link_text.'</span>';
			return $just_text;
		}
	}
}

//toc shortcode
function yada_wiki_toc_shortcode( $atts ) {
	extract( shortcode_atts( array( 
		'show_toc' => '', 
		'category' => '', 
		'order' => '', 
	), $atts ) ); 
	
	$show_toc 	= sanitize_text_field($show_toc);
	$category 	= sanitize_text_field($category);
	$order 		= sanitize_text_field($order);
	
	return get_yada_wiki_toc( $show_toc, $category, $order );
}

//output toc
function get_yada_wiki_toc( $show_toc, $category, $order ){
	$show_toc  	= trim($show_toc);
	$category  	= trim($category);
	$order  	= trim($order);
	$order_direction = "ASC";
	
	if($category != "") {
		// Output page title to all in the category
		if($order == "") {
			$order = "title";
		} else if ($order == "datedesc") {
			$order = "date";
			$order_direction = "DESC";
		}
		$args = array( 
			'posts_per_page' 	=> -1, 
			'offset'			=> 0,
			'post_type' 		=> 'yada_wiki',
			'tax_query'			=> array(
									array(
										'taxonomy' => 'wiki_cats', 
										'field' => 'name', 
										'terms' => $category, 
									),
								   ),
			'orderby' 			=> $order,
			'order' 			=> $order_direction,
			'post_status' 		=> 'publish'
		); 	
		$cat_list = get_posts( $args );
		$cat_output = '<ul class="wiki-cat-list">';
		foreach ( $cat_list as $item ) {
			$cat_output = $cat_output . '<li class="wiki-cat-item"><a class="wiki-cat-link" href="' . esc_url(get_post_permalink($item->ID)) . '">'.$item->post_title.'</a></li>';
		}
		$cat_output = $cat_output . '</ul>';
		return $cat_output;
	}
	else if($show_toc == true) {
		
		$the_toc = get_posts(
		    array(
		        'post_type'              => 'yada_wiki',
		        'title'                  => 'TOC',
		        'post_status'            => 'all',
		        'numberposts'            => 1,
		        'update_post_term_cache' => false,
		        'update_post_meta_cache' => false,           
		        'orderby'                => 'post_date ID',
		        'order'                  => 'ASC',
		    )
		);
		if($the_toc) { $the_toc=$the_toc[0]; } 
		
		if (! isset($the_toc) ) {
		    return __('A wiki article with the title of TOC was not found.', 'yada_wiki_domain');
		} 
		else {
			$toc_status = get_post_status( $the_toc );
			
			if( $toc_status == "publish" ) {
				$has_content = $the_toc->post_content;
				if ($has_content) {
					$the_content = apply_filters( 'the_content', $the_toc->post_content );
					return $the_content;				
				} else {
					return __('The TOC has no content.', 'yada_wiki_domain');
				}				
			} else {
				return __('The TOC has not been published.', 'yada_wiki_domain');
			}	
		}
	}
}

//index shortcode
function yada_wiki_index_shortcode($atts) {
	extract( shortcode_atts( array( 
		'type' => '',
		'category' => '',
		'columns' => '',
	), $atts ) ); 
	$type = sanitize_text_field($type);	
	$category = sanitize_text_field($category);	
	$columns = sanitize_text_field($columns);	
	
	if($type=="pages") {
		//all wiki pages in a table
		return get_yada_wiki_index_pages($type, $category, $columns);	
	} else if (!empty($category)) {
		//all pages of a category in a table
		return get_yada_wiki_index_category($type, $category, $columns);	
	} else {
		//all wiki pages that have a category in a table
		return get_yada_wiki_index($type, $category, $columns);	
	}
}

//output grid index of all wiki pages
function get_yada_wiki_index_pages($type, $category, $columns) {
	$theOutput = "";
	if(is_numeric($columns) == false) {
		$columns = 1;
	}
	$columns = $columns + 1;

	// Yada Wiki All Index Page
	global $wpdb;
	$query = "
		SELECT 
			$wpdb->posts.post_title, $wpdb->posts.id
		FROM 
			$wpdb->posts
		WHERE 
			$wpdb->posts.post_status = 'publish'
			AND $wpdb->posts.post_type = 'yada_wiki'
			AND $wpdb->posts.post_title <> 'TOC'
		ORDER BY 
			$wpdb->posts.post_title ASC
	";
	$wikiposts = $wpdb->get_results($query, OBJECT);
	if(!empty($wikiposts)){
		$counter = 1;
		$theOutput = $theOutput . '<div class="ywtable">';
		foreach($wikiposts as $wiki_post){
			$thePermalink = esc_url(get_post_permalink($wiki_post->id));
			if($counter==1) {
				$theOutput = $theOutput . '<div class="ywrow">';
			}
			$theOutput = $theOutput . '<div class="ywcolumn" data-label="Wiki Article"><a href="' . $thePermalink . '" class="wikicatlink">' . $wiki_post->post_title . '</a></div>';
			$counter = $counter + 1;
			if($counter==$columns) {
				$theOutput = $theOutput . '</div>';		
				$counter = 1;		
			}
		}
		if ($counter!=1){
			for ($x=1; $x<=$columns-$counter; $x++) {
				$theOutput = $theOutput .  '<div class="ywcolumn" data-label="Wiki Article"></div>';
			}
			$theOutput = $theOutput . '</div>';
		}				
		$theOutput = $theOutput . '</div>';
	}
	$query = "";
	$wikiposts = "";
	return $theOutput;		
}

//output grid index of wiki posts for a single category
function get_yada_wiki_index_category($type, $category, $columns) {
	$theOutput = "";
	if(is_numeric($columns) == false) {
		$columns = 1;
	} else {
	
	}
	$columns = $columns;

	if($type=="category-name") {
		// in wp_query the name is called 'title'
		$order = "title";			
	}
	else if($type=="category-slug") {
		// in wp_query the slug is called 'name'
		$order = "name";
	}
	else {
		// in wp_query the name is called 'title'
		$order = "title";
	}
	if(!empty($category)){
		$tocpost = get_posts(
			array(
				'post_type'          => 'yada_wiki',
				'title'                  => 'TOC',
				'post_status'            => 'publish',
				'numberposts'            => 1,
			)
		);

		if ( ! empty( $tocpost ) ) {
			$toc_post_id = $tocpost[0]->ID;
		} else {
			$toc_post_id = '';
		}	

		$args = array(
			'showposts' => -1,
			'post_type' => 'yada_wiki',
			'post_status' 		=> 'publish',
			'tax_query' => array(
				array(
				'taxonomy' => 'wiki_cats',
				'field' => 'name',
				'terms' => $category,
				'include_children' => false,
			)),
			'exclude' => $toc_post_id,
			'orderby' => $order,
			'order' => 'ASC',
		);
		$wikiposts = get_posts( $args);
		$postcounter = 0;
		$endrow = false;
		if(!empty($wikiposts)){
			$theOutput = $theOutput . '<div class="ywtable">';
			$theOutput = $theOutput . '<div class="ywrow"><br>&nbsp;&nbsp;' . $category .  '<br>&nbsp;&nbsp;</div>';
			foreach($wikiposts as $wiki_post){
				if($postcounter==0) {
					$theOutput = $theOutput . '<div class="ywrow">';
					$endrow = false;
				}
				$thePermalink = esc_url(get_permalink($wiki_post));
				$postcounter = $postcounter + 1;
				$theOutput = $theOutput . '<div class="ywcolumn-alt" data-label="Wiki Article"><a href="' . $thePermalink . '" class="wikicatlink">' . $wiki_post->post_title . '</a></div>';
				if($postcounter==$columns) {
					$theOutput = $theOutput . '</div>';		
					$postcounter = 0;	
					$endrow = true;
				}
			}
			if ($postcounter!=0){
				for ($x=1; $x<=$columns-$postcounter; $x++) {
					$theOutput = $theOutput .  '<div class="ywcolumn-alt" data-label="Wiki Article"></div>';
				}
			}					
			if($endrow == false) {
				$theOutput = $theOutput . '</div>';	
				$endrow = true;	
			}		
			$theOutput = $theOutput . '</div>';
		}
	}
	if($endrow == false) {
		$theOutput = $theOutput . '</div>';	
		$endrow = true;	
	}
	$wikiposts = "";
	$categories = "";
	$tocpost = "";
	return $theOutput;		
}

//output grid index of wiki posts for all categoriea
function get_yada_wiki_index($type, $category, $columns) {
	$theOutput = "";
	if(is_numeric($columns) == false) {
		$columns = 1;
	}

	if($type=="all-categories-name") {
		// in wp_query the name is called 'title'
		$order = "title";			
	}
	else if($type=="all-categories-slug") {
		// in wp_query the slug is called 'name'
		$order = "name";
	}
	else {
		// in wp_query the name is called 'title'
		$order = "title";
	}

	// we want to exclude the TOC
	$tocpost = get_posts(
		array(
			'post_type'          => 'yada_wiki',
			'title'                  => 'TOC',
			'post_status'            => 'publish',
			'numberposts'            => 1,
		)
	);

	if ( ! empty( $tocpost ) ) {
		$toc_post_id = $tocpost[0]->ID;
	} else {
		$toc_post_id = '';
	}	

	$categories = get_terms( array(
		'taxonomy'   => 'wiki_cats',
		'hide_empty' => true,
		'orderby' => $order,
		'order' => 'ASC',
	) );	
	
	$catnames = wp_list_pluck( $categories, 'name' );

	if(!empty($categories)){
		$categoryHierarchy = array();
		sort_terms_hierarchically($categories, $categoryHierarchy);
		$theOutput = $theOutput . '<div class="ywtable">';
		foreach ($categoryHierarchy as $category) {
			if($category->parent==0) {
				$cat_parent = $category;
				$category_name = $category->name;
				$args = array(
					'showposts' => -1,
					'post_type' => 'yada_wiki',
					'post_status' 		=> 'publish',
					'tax_query' => array(
						array(
						'taxonomy' => 'wiki_cats',
						'field' => 'name',
						'terms' => array($category_name),
						'include_children' => false,
					)),
					'exclude' => $toc_post_id,
					'orderby' => $order,
					'order' => 'ASC',
				);
				$wikiposts = get_posts( $args);
				$postcounter = 0;
				$endrow = false;
				$theOutput = $theOutput . '<div class="ywrow" style="font-weight:bold;line-height:2.5;">&nbsp;&nbsp;' . $category_name .  '</div>';
				foreach($wikiposts as $wiki_post){
					if($endrow==true) {
						$endrow=false;
					}
					if($postcounter==0) {
						$theOutput = $theOutput . '<div class="ywrow">';
						$endrow=false;
					}
					$thePermalink = esc_url(get_permalink($wiki_post));					
					$postcounter = $postcounter + 1;
					$theOutput = $theOutput . '<div class="ywcolumn-alt" data-label="Wiki Article"><a href="' . $thePermalink . '" class="wikicatlink">' . $wiki_post->post_title . '</a></div>';
					if( $postcounter == $columns ) {
						$theOutput = $theOutput . '</div>';		
						$postcounter = 0;		
						$endrow = true;
					}
				}
				if ($postcounter!=0){
					for ($x=1; $x<=$columns-$postcounter; $x++) {
						$theOutput = $theOutput .  '<div class="ywcolumn-alt" data-label="Wiki Article"></div>';
					}
				}	
				if($endrow == false) {
					$theOutput = $theOutput . '</div>';	
					$endrow = true;	
				}
				$theOutput = $theOutput . '<div class="ywrow">&nbsp;</div>';
				$wikiposts = '';	
				$postcounter = 0;
			
				$category_children = $category->children;
				if(!empty($category_children)) {
					foreach ($category_children as $child) {
						$category_child_name = $child->name;
						$args = array(
							'showposts' => -1,
							'post_type' => 'yada_wiki',
							'post_status' 		=> 'publish',
							'tax_query' => array(
								array(
								'taxonomy' => 'wiki_cats',
								'field' => 'name',
								'terms' => array($category_child_name),
								'include_children' => false,
							)),
							'exclude' => $toc_post_id,
							'orderby' => $order,
							'order' => 'ASC',
						);
						$childposts = get_posts( $args);		
						$postcounter = 0;
						$endrow = false;
						$theOutput = $theOutput . '<div class="ywrow" style="font-weight:bold;line-height:1.5;">&nbsp;&nbsp;' . $cat_parent->name .  '<br>&nbsp; - ' . $child->name .  '</div>';
						foreach($childposts as $child_post){
							if($postcounter==0) {
								$theOutput = $theOutput . '<div class="ywrow">';
								$endrow = false;
							}
							$thePermalink = esc_url(get_permalink($child_post));						
							$theOutput = $theOutput . '<div class="ywcolumn-alt" data-label="Wiki Article"><a href="' . $thePermalink . '" class="wikicatlink">' . $child_post->post_title . '</a></div>';
							$postcounter = $postcounter + 1;
							if( $postcounter == $columns ) {
								$theOutput = $theOutput . '</div>';		
								$postcounter = 0;		
								$endrow = true;
							}
						}
						if ($postcounter!=0){
							for ($x=1; $x<=$columns-$postcounter; $x++) {
								$theOutput = $theOutput .  '<div class="ywcolumn-alt" data-label="Wiki Article"></div>';
							}
						}	
						if($endrow == false) {
							$theOutput = $theOutput . '</div>';	
							$endrow = true;	
						}
						$theOutput = $theOutput . '<div class="ywrow">&nbsp;</div>';			
						$childposts = "";		
						$postcounter = 0;
					}		
				}			
			}
		}
		$categories = "";
		$catnames = "";	
		$tocpost = "";
		return $theOutput;
	}		
}

/**
 * From: http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list - pospi
 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
 * placed under a 'children' member of their parent term.
 * @param Array   $cats     taxonomy term objects to sort
 * @param Array   $into     result array to put them in
 * @param integer $parentId the current parent ID to put them in
 */
function sort_terms_hierarchically(Array &$cats, Array &$into, $parentId = 0)
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        sort_terms_hierarchically($cats, $topCat->children, $topCat->term_id);
    }
}

