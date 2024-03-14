<?php
/*
collapsing categories version: 3.0.8
copyright 2007-2023 robert felty

this file is part of collapsing categories

		collapsing categories is free software; you can redistribute it and/or
    modify it under the terms of the gnu general public license as published by
    the free software foundation; either version 2 of the license, or (at your
    option) any later version.

    collapsing categories is distributed in the hope that it will be useful,
    but without any warranty; without even the implied warranty of
    merchantability or fitness for a particular purpose.  see the
    gnu general public license for more details.

    you should have received a copy of the gnu general public license
    along with collapsing categories; if not, write to the free software
    foundation, inc., 51 franklin st, fifth floor, boston, ma  02110-1301  usa
*/
global $collapsCatItems;
$collapsCatItems = array();


function add_to_includes($cat, $inexclusionarray) {
  /* add all parents to include list */
  if (in_array($cat->slug, $inexclusionarray) ||
      in_array($cat->term_id, $inexclusionarray)) {
    $includes[]= $cat->term_id;
    if ($cat->parent!=0)
      $inexclusionarray[]= $cat->parent;
      $cat2 = get_category($cat->parent);
      $moreincludes = add_to_includes($cat2,$inexclusionarray);
      if (!empty($moreincludes)) {
        foreach ($moreincludes as $include) {
          $includes[] =  $include;
        }
      }
    $children = get_categories('child_of=' . $cat->term_id);
    foreach ($children as $child) {
      $includes[]= $child->term_id;
    }
  }
  return($includes);
}

function getCollapsCatLink($cat,$catlink) {
  /* returns link to category. we use the id of the category if possible,
  because it is faster. otherwise we pass the whole category object */
  if (empty($catlink)) {
    $the_cat=$cat->term_id;
  } else {
    $the_cat=$cat;
  }
  if ($cat->taxonomy=='post_tag') {
    $link = "<a href='".get_tag_link($the_cat)."' ";
  } elseif ($cat->taxonomy=='category') {
    $link = "<a href='".get_category_link($the_cat)."' ";
  } else {
    $link = "<a href='".get_term_link($the_cat, $cat->taxonomy)."' ";
  }
  return($link);
}

function miscPosts($cat,$catlink,$subcatpostcount2, $posttext) {
  /* this function will group posts into a miscellaneous sub-category */
  global $options, $collapsCatItems, $cur_terms;
  $number = 0;
  extract($options);
  $show='expand';
  $symbol=$expandSym;
  $expanded='none';
  $theID='collapsCat-' . $cat->term_id . "-$number-misc";

  if ((in_array($cat->term_id, $cur_terms) && $expandCatPost) ||
      ($useCookies && $_COOKIE[$theID]==1)) {
    $expanded='block';
  }
  if ($expanded=='block') {
    $show='collapse';
    $symbol=$collapseSym;
  }
  $miscposts="      <li class='collapsing-categories expandable'>".
      "<span class='collapsing-categories $show'>".
      "<span class='sym'>$symbol</span>";
  if ( $linkToCat ) {
    $thislink=getCollapsCatLink($cat,$catlink);
    $miscposts.="</span>$thislink>$addMiscTitle</a>";
  } else {
    $miscposts.="$addMiscTitle</span>";
  }
  if( $showPostCount) {
    $miscposts.=' (' . $subcatpostcount2.')';
  }
  $miscposts.= "\n     <div id='$theID' style=\"display:$expanded\"><ul>\n" ;
  $miscposts.=$posttext;
  $miscposts.="    </ul></div></li> <!--misc posts -->\n";
  if ($theID!='' && !$collapsCatItems[$theID]) {
  $collapsCatItems[$theID] = "<ul>$posttext</ul>";
  }
  return($miscposts);
}

/**
 * check whether the post being displayed belongs to a given category,
 * or if that category's page itself is displayed.
 * If so, it adds all parent categories to the autoExpand array, so
 * that it is automatically expanded
*/
function checkCurrentCat($cat, $categories) {
  global $autoExpand;
	array_push($autoExpand, $cat->slug);
	if ($cat->parent!=0) {
		foreach ($categories as $cat2) {
		  if ($cat2->term_id == $cat->parent) {
			  checkCurrentCat($cat2,$categories);
		  }
		}
	}
}

/* TODO
* add depth option
* add option to display number of comments
*/
function getSubPosts($posts, $cat2, $showPosts) {
  /* returns all the posts for a given category */
	global $postsToExclude, $options, $thisPost, $collapsCatItems;
	$posttext2='';
	if ( ! is_array($posts) || 0 == count( $posts ) ) {
		return '';
	}
	foreach ($posts as $post2) {
		if ( ( $post2->term_id != $cat2->term_id ) &&
			! in_array($post2->ID, $postsToExclude) ) {
			continue;
		}
		if (!$showPosts) {
			continue;
		}
		if (is_single() && $post2->ID == $thisPost) {
			$self=" self";
		} else {
			$self="";
		}
		$date=preg_replace("/-/", '/', $post2->date);
		$name=$post2->post_name;
		$title_text = strip_tags(__($post2->post_title),
		'collapsing-categories');
		$tmp_text = '';
		if ( isset( $postTitleLength ) && $postTitleLength > 0 && strlen( $title_text ) > $postTitleLength ) {
			$tmp_text = substr($title_text, 0, $postTitleLength );
			$tmp_text .= ' &hellip;';
		}
		$linktext = $tmp_text == '' ? $title_text : $tmp_text;
		if ( isset( $showPostDate ) && $showPostDate ) {
			$theDate = mysql2date($postDateFormat, $post2->post_date );
			if ($postDateAppend=='before') {
				$linktext = "$theDate $linktext";
			} else {
				$linktext = "$linktext $theDate";
			}
		}
		$posttext2.= "<li class='collapsing-categories item" .  $self .
		"'><a " .  "href='".get_permalink($post2).
		"' title='" . esc_attr( strip_tags( $title_text ) ) . "'>$linktext</a></li>\n";
	}
	return $posttext2;
}

function addFeedLink($feed,$cat) {
  /* returns a link to the rss feed for a given category */
  if ($feed=='text') {
    $rssLink= '<a href="' . get_category_feed_link($cat->term_id) .
        '">&nbsp;(RSS)</a>';
  } elseif ($feed=='image') {
    $rssLink= '<a href="' . get_category_feed_link($cat->term_id) .
        '">&nbsp;<img src="' .
        '/wp-includes/images/rss.png" /></a>';
  } else {
    $rssLink='';
  }
  return $rssLink;
}

function get_sub_cat($cat, $categories, $parents, $posts,
  $subCatCount,$subCatPostCount,$expanded, $depth) {
  /* returns all the subcategories for a given category */
  global $options, $collapsCatItems, $autoExpand, $postsToExclude,
      $totalCatPostCount, $catlink, $postsInCat, $cur_terms, $thisCatID;
  $subCatLinks='';
  $postself='';
  $number = 0;
  extract($options);
  $link2='';
  $depth++;
  $parent='';
  if (in_array($cat->term_id, $parents)) {
    foreach ($categories as $cat2) {
      $subCatLink2=''; // clear info from subCatLink2
      if ((is_category() || is_tag()) && ($cat2->term_id==$thisCatID)) {
        $self=" self";
        $parent.=" active";
      } else {
        $self="";
      }
      $parent="";
      if (in_array($cat2->slug, $autoExpand)) {
        $parent.=" parent";
      }
      if ( is_single() ) {
        $the_post = get_post();
        if ( $the_post != null && in_array( $cat2->term_id, wp_get_post_categories( $the_post->ID ) ) ) {
          $parent.=" active";
        }
      }
      if ($cat->term_id==$cat2->parent) {
        $theID='collapsCat-' . $cat2->term_id . "-$number";
		$subCatPostCount2  = CollapsCat::get_term_counts( $cat2 );
		if ( $showPosts ) {
			$posttext2 = getSubPosts($postsInCat[$cat2->term_id],$cat2, $showPosts);
		}
        $totalCatPostCount=$subCatPostCount2;
        $subCatPostCount=$subCatPostCount2;
        $expanded='none';
        if (((in_array($cat2->name, $autoExpand) ||
            in_array($cat2->slug, $autoExpand)) && $expandCatPost) ||
            ($useCookies && isset($_COOKIE[$theID]) && $_COOKIE[$theID]==1)) {
          $expanded='block';
        }
        if (!in_array($cat2->term_id, $parents)) {
          // check to see if there are more subcategories under this one
          if ($theID!='' &&  ( ! isset( $collapsCatItems[$theID] ) || ! $collapsCatItems[$theID] ) ) {
            $collapsCatItems[$theID] = "<ul>$posttext2</ul>";
          }
          $subCatCount=0;
          if ($subCatPostCount2<1 AND !$showEmptyCat) {
            continue;
          }
          if ($showPosts) {
            if ($expanded=='block') {
              $show='collapse';
              $symbol=$collapseSym;
            } else {
              $show='expand';
              $symbol=$expandSym;
            }
            $subCatLinks.=( "<li class='collapsing-categories expandable" .
                $self . $parent . "'>".
                "<span class='collapsing-categories $show'>".
                "<span class='sym'>$symbol</span>" );
          } else {
            $subCatLinks.=( "<li class='collapsing-categories item" .  $self .
                $parent . "'>" );
          }
          $link2= getCollapsCatLink($cat2,$catlink);
          if ( empty($cat2->description) ) {
            $link2 .= 'title="'.
                sprintf(__("View all posts filed under %s",
                'collapsing-categories'),
                esc_attr( strip_tags( apply_filters( 'single_cat_title', $cat2->name ) ) ) ) . '"';
          } else {
            $link2 .= 'title="' .
                esc_attr( apply_filters(
                    'description', $cat2->description, $cat) ) . '"';
          }
          $link2 .= '>';
          if ( $linkToCat ) {
            if ($showPosts) {
              $subCatLinks.='</span>';
            }
            $link2 .= apply_filters('single_cat_title', $cat2->name).
                '</a>';
          } else {
            $link2 .= apply_filters('single_cat_title', $cat2->name).  '</a>';
            if ($showPosts) {
              $link2 .= "</span>";
            }
          }
        } else {
          list ($subCatLink2, $subCatCount)=
              get_sub_cat($cat2, $categories, $parents, $posts, $subCatCount,
              $subCatPostCount2,$expanded, $depth);
          $subCatCount=1;
          $subCatPostCount=$subCatPostCount2;
          if ($subCatPostCount2<1 AND !$showEmptyCat) {
            continue;
          }
          if ($expanded=='block') {
            $show='collapse';
            $symbol=$collapseSym;
          } else {
            $show='expand';
            $symbol=$expandSym;
          }
          $subCatLinks.=( "<li class='collapsing-categories expandable" .
              $self . $parent . "'>".
              "<span class='collapsing-categories $show'>".
              "<span class='sym'>$symbol</span>" );
          $link2=getCollapsCatLink($cat2,$catlink);
          if ( empty($cat2->description) ) {
            $link2 .= 'title="'.
                sprintf( __( "View all posts filed under %s" ),
                esc_attr( strip_tags( apply_filters( 'single_cat_title', $cat2->name ) ) ) ) . '"';
          } else {
            $link2 .= 'title="' .
                esc_attr( apply_filters( 'description',
                $cat2->description, $cat2 ) ) . '"';
          }
          $link2 .= '>';
          if ( $linkToCat ) {
            $subCatLinks.='</span>';
            $link2 .= apply_filters('single_cat_title', $cat2->name).'</a>';
          } else {
            if ($showPosts || $subCatPostCount2>0) {
              $link2 .= apply_filters('single_cat_title',$cat2->name) . '</a></span>';
            } else {
              // don't include the triangles if posts are not shown and there
              // are no more subcategories
                $link2 .= apply_filters('single_cat_title',$cat2->name).'</a>';
                $subCatLinks = "      <li class='collapsing-categories item" .
                    $self . "'>";
            }
          }
        }
        if( $showPostCount) {
          $theCount=$subCatPostCount2;
          $link2 .= ' ('.$theCount.')';
        }
        $subCatLinks.= $link2 ;
        $rssLink=addFeedLink($catfeed,$cat2);
        $subCatLinks.=$rssLink;
        if (($subCatCount>0) || ($showPosts)) {
          $subCatLinks.="\n<div id='$theID' style=\"display:$expanded\">\n";
          if ($subCatCount>0 && $posttext2!='' && $addMisc) {
            $posttext2=miscPosts($cat2,$catlink,$subCatPostCount2,
                $posttext2);
          }
          if (($expanded=='block' AND $showPosts) OR $subCatCount>0)
            $subCatLinks.="<ul>";
          if ($expanded=='block') {
            $subCatLinks.="$posttext2";
          } else {
            $subCatLinks.='';
          }
        }
        // add in additional subcategory information
        $subCatLinks.="$subCatLink2";
        if ( ! empty( $theID )  && empty( $collapsCatItems[ $theID ] ) ) {
          $collapsCatItems[ $theID ] = "<ul>$posttext2" . "$subCatLink2</ul>";
        }
        // close <ul> and <li> before starting a new category
        if (($expanded=='block' AND $showPosts) OR $subCatCount>0)
          $subCatLinks.="</ul>";
        if (($subCatCount>0) || ($showPosts)) {
          $subCatLinks.= "          </div> <!--here -->\n";
        }
        $subCatLinks.= "         </li> <!-- ending subcategory -->\n";
      }
      $subCatCount+=1;
    }
  }
  return( array( $subCatLinks, $subCatCount ) );
}
function collapscat_replace_newlines($text) {
  if (strpos($text,"\n")!==false || strpos($text,"\r")!==false) {
    $text = preg_replace("/[\r\n][\r\n]?/", "<br />", $text);
  }
  return $text;
}
function collapscat_catfilter($categories) {
  global $options;
  extract($options);
	$inExclusionArray = array();
	if ( !empty($inExcludeCats )) {
		$exterms = preg_split('/\s*[,]+\s*/',$inExcludeCats);
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
        $sanitizedTitle = sanitize_title(trim($exterm));
			  $inExclusionArray[] = $sanitizedTitle;
      }
    }
	}
  for ($i=0; $i<count($categories); $i++) {
    if ($inExclude=='exclude' && !empty($inExclusionArray)) {
      if (in_array($categories[$i]->slug, $inExclusionArray) OR
          in_array($categories[$i]->term_id, $inExclusionArray)) {
        unset($categories[$i]);
      }
    }
  }
  return $categories;
}
function collapscat_orderbyfilter($orderby, $args='') {
  global $options;
  extract($options);
  if ($catSort!='') {
    if ($catSort=='catName') {
      $orderby="t.name";
    } elseif ($catSort=='catId') {
      $orderby="t.term_id";
    } elseif ($catSort=='catSlug') {
      $orderby="t.slug";
    } elseif ($catSort=='catOrder') {
      $orderby="t.term_order";
    } elseif ($catSort=='catCount') {
      $orderby="tt.count";
    }
  }
  return $orderby;
}

function get_collapscat_fromdb($args='') {
  global $expandSym,$collapseSym, $wpdb,$options,$wp_query,
      $autoExpand, $postsToExclude, $postsInCat;
  include('defaults.php');
  $options=wp_parse_args($args, $defaults);
  extract($options);
  include('symbols.php');
	$inExclusionArray = array();
	if ( !empty($inExcludeCats )) {
		$exterms = preg_split('/[,]+/',$inExcludeCats);
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
					$sanitizedTitle = sanitize_title($exterm);
			  $inExclusionArray[] = $sanitizedTitle;
				if (empty($inExclusions))
					$inExclusions = "'$sanitizedTitle'";
				else
					$inExclusions .= ", '$sanitizedTitle'";
			}
		}
	}
	if ( empty($inExclusions) || $inExclude=='include' ) {
		$inExcludeQuery = "";
  } else {
    $inExcludeQuery ="AND t.slug NOT IN ($inExclusions)";
  }

  if ($postSort!='') {
    if ($postSort=='postDate') {
      $postSortColumn="ORDER BY p.post_date";
    } elseif ($postSort=='postId') {
      $postSortColumn="ORDER BY p.id";
    } elseif ($postSort=='postTitle') {
      $postSortColumn="ORDER BY p.post_title";
    } elseif ($postSort=='postComment') {
      $postSortColumn="ORDER BY p.comment_count";
    } elseif ($postSort=='postOrder') {
      $postSortColumn="ORDER BY p.menu_order";
    }
  }
	if ($defaultExpand!='') {
		$autoExpand = preg_split('/,\s*/',$defaultExpand);
  } else {
	  $autoExpand = array();
  }
  /* Now allowing custom taxonomies, but we put this in for backwards compatibility */
  if (isset($catTag) && !isset($taxonomy))
    $taxonomy = $catTag;
	if ($taxonomy == 'tag') {
	  $taxonomyQuery= "'post_tag'";
	} elseif ($taxonomy == 'both') {
	  $taxonomyQuery= "'category','post_tag'";
	} elseif ($taxonomy == 'cat') {
	  $taxonomyQuery= "'category'";
	} else {
	  $taxonomyQuery= "'$taxonomy'";
  }
 /* we also need to specify post types */
 $post_type_query = "AND post_type='$post_type'";
	if ($olderThan > 0) {
		$now = date('U');
		$olderThanQuery= "AND  date(post_date) > '" .
			date('Y-m-d', $now-date('U',$olderThan*60*60*24)) . "'";
	} else {
    $olderThanQuery = '';
  }


  $posts = NULL;
  if ($showPosts) {
    $postsInCat=array();
    $postquery= "select ID, slug, date(post_date) as date, post_status,
         post_type, post_date, post_author, post_title, post_name, name, object_id,
         t.term_id from $wpdb->term_relationships AS tr, $wpdb->posts AS p,
         $wpdb->terms AS t, $wpdb->term_taxonomy AS tt
         WHERE tt.term_id = t.term_id
         AND object_id=ID
         $olderThanQuery
         AND post_status='publish'
         AND tr.term_taxonomy_id = tt.term_taxonomy_id
         AND tt.taxonomy IN ($taxonomyQuery) $post_type_query $postSortColumn $postSortOrder";
    $posts= $wpdb->get_results($postquery);
    foreach ($posts as $post) {
      if ( empty( $postsInCat[$post->term_id] ) ) {
        $postsInCat[$post->term_id]=array();
      }
      array_push($postsInCat[$post->term_id], $post);
    }
  }
  add_filter('description', 'collapscat_replace_newlines');
  add_filter('get_terms', 'collapscat_catfilter');
  add_filter('get_terms_orderby', 'collapscat_orderbyfilter');
  $args = array('order' => $catSortOrder);
  if ($showEmptyCat)
    $args['hide_empty'] = false;
  $categories = get_terms($taxonomy, $args);
  if ( ! is_array( $posts ) ) {
    $totalPostCount = 0;
  } else {
    $totalPostCount=count( $posts );
  }
  if ($totalPostCount>5000) {
    $options['showPosts']=false;
    $showPosts=false;
  }
  $includeCatArray=array();
  $parents=array();
  foreach ($categories as $cat) {
    // if only including certain categories, we build an array of those
    // category ids
    if ($inExclude=='include' && !empty($inExclusionArray)) {
      $includes = add_to_includes($cat, $inExclusionArray);
      if (!empty($includes)) {
        $includeCatArray = array_merge($includeCatArray, $includes);
      }
    }
    if ($cat->parent!=0) {
      array_push($parents, $cat->parent);
    }
  }
  $includeCatArray = array_unique($includeCatArray);
	$postsToExclude=array();
	if ($excludeAll==1) {
		foreach ($posts as $post) {
			if (in_array($post->slug, $inExclusionArray)) {
				array_push($postsToExclude, $post->ID);
			}
		}
	}
  // add in computed options to options array
  $computedOptions = compact('includeCatArray', 'expandSym', 'collapseSym');
  $options = array_merge($options, $computedOptions);
  if ($debug==1) {
    echo "<li style='display:none' >";
    echo "\ncollapsCat options:\n";
    print_r($options);
    echo "\npostsToExclude:\n";
    print_r($postsToExclude);
    echo "\nCATEGORY QUERY RESULTS\n";
    print_r($categories);
    echo "POST QUERY:\n $postquery\n";
    echo "\nPOST QUERY RESULTS\n";
    print_r($posts);
    echo "</li>";
  }

  remove_filter( 'get_terms', 'collapscat_catfilter' );
  remove_filter( 'get_terms_orderby', 'collapscat_orderbyfilter' );
  remove_filter( 'description', 'collapscat_replace_newlines' );

  return(array($posts, $categories, $parents, $options));
}

function list_categories( $posts, $categories, $parents, $options ) {
  /* returns a list of categories, and optionally subcategories and posts,
  which can be collapsed or expanded with javascript */
  global $collapsCatItems, $wpdb,$options,$wp_query, $autoExpand,
      $postsToExclude, $totalCatPostCount, $thisCatID,
      $cur_terms, $thisPost, $wp_rewrite, $catlink, $postsInCat, $showPostDate;
  CollapsCat::$term_counts = CollapsCat::map_term_counts( $posts, $categories );
  $number = 0;
  $addMisc = false;
  extract($options);
  $collapsCatText='';
  $cur_terms = array();
  if (is_single()) {
    $tmp_terms = wp_get_object_terms($wp_query->post->ID, $taxonomy);
    foreach ($tmp_terms as $tmp_cat) {
      $cur_terms[] = $tmp_cat->term_id;
    }
    $thisPost = $wp_query->post->ID;
    foreach ($categories as $cat) {
      if (!empty($cur_terms) && (in_array($cat->term_id, $cur_terms))) {
        checkCurrentCat($cat,$categories);
      }
    }
  }
  if ( is_category() || is_tax() ) {
    $thisCatID = $wp_query->get_queried_object_id();
    $thisCat = get_term($thisCatID, $taxonomy);
    checkCurrentCat($thisCat,$categories);
  }
  $catlink = $wp_rewrite->get_category_permastruct();

  $the_post = get_post();
  foreach( $categories as $cat ) {
    $totalCatPostCount=0;
    if ($inExclude=='include' && !empty($includeCatArray)) {
      if (!in_array($cat->term_id, $includeCatArray) &&
          !in_array($cat->post_parent, $includeCatArray)) {
        continue;
      }
    }
    if ($cat->parent!=0 )
      continue;
    if (is_archive() && ($cat->term_id==$thisCatID)) {
      $self=" self";
      $parent.=" active";
    } else {
      $self="";
    }
    if (in_array($cat->slug, $autoExpand)) {
      $parent=" parent";
    } else {
      $parent="";
    }
    if ( is_single() && in_array( $cat->term_id, wp_get_post_categories( $the_post->ID ) ) ) {
      $parent.=" active";
    }
    $rssLink=addFeedLink($catfeed,$cat);
    $subCatPostCount=0;
    $subCatCount=0;
    list ($subCatLinks, $subCatCount)=
        get_sub_cat($cat, $categories, $parents, $posts,
        $subCatCount,$subCatPostCount,'none',0);
    if ( ! empty( $postsInCat[$cat->term_id]  ) ) {
		$subCatPostCount2 = CollapsCat::get_term_counts( $cat );
		if ( $showPosts ) {
			$posttext2 = getSubPosts($postsInCat[$cat->term_id], $cat, $showPosts);
		}
    } else {
      $subCatPostCount2 = $cat->count;
    }

	$theCount = CollapsCat::get_term_counts( $cat );

    if ($theCount>0 || $showEmptyCat) {
      $expanded='none';
      $theID='collapsCat-' . $cat->term_id . "-$number";
      if (((in_array($cat->name, $autoExpand) ||
          in_array($cat->slug, $autoExpand)) && $expandCatPost) ||
          ($useCookies && isset($_COOKIE[$theID]) && $_COOKIE[$theID]==1)) {
        $expanded='block';
      }
      if ($showPosts || $subCatCount>0 || $showEmptyCat) {
        if ($expanded=='block') {
          $show='collapse';
          $symbol=$collapseSym;
        } else {
          $show='expand';
          $symbol=$expandSym;
        }
        $span= "      <li class='collapsing-categories expandable" .  $self .
            $parent . "'>".
            "<span class='collapsing-categories $show'>".
            "<span class='sym'>$symbol</span>";
      } else {
        $span = "      <li class='collapsing-categories item" .  $self. "'>";
      }
      $link=getCollapsCatLink($cat,$catlink);
      if ( empty($cat->description) ) {
        $link .= 'title="'.
            sprintf(__("View all posts filed under %s",
            'collapsing-categories'),
            esc_attr( apply_filters(
                 'single_cat_title', $cat->name ) ) ) . '"';
      } else {
        $link .= 'title="' . esc_attr( apply_filters(
            'description', $cat->description, $cat) ) . '"';
      }
      $link .= '>';
      if ( $linkToCat ) {
        $link .= apply_filters('single_cat_title', $cat->name).'</a>';
        if ($showPosts || $subCatPostCount>0 || $subCatCount>0) {
          $span.='</span>';
        }
      } else {
        if ($showPosts || $subCatPostCount>0) {
          $link .= apply_filters('single_cat_title',$cat->name) . '</a></span>';
        } else {
          // don't include the triangles if posts are not shown and there
          // are no more subcategories
            $link .= apply_filters('single_cat_title',$cat->name).'</a>';
            $span = "      <li class='collapsing-categories item'>";
        }
      }
      // Now print out the post info
      $posttext='';
      if( ! empty($postsInCat[$cat->term_id]) ) {
		$subCatPostCount = CollapsCat::get_term_counts( $cat );
		if ( $showPosts ) {
			$posttext = getSubPosts($posts, $cat, $showPosts);
		}
      }
      if( $showPostCount) {
        $link .= ' (' . $theCount.')';
      }
      $link.=$rssLink;
      if ($theCount<1 && !$showEmptyCat) {
        $link='';
        $span='';
      }
      if ($showTopLevel) {
        $collapsCatText.=$span . $link;
        if (($subCatCount>0) || ($showPosts)) {
          $collapsCatText .= "\n     <div id='$theID'" .
              " style=\"display:$expanded\">\n";
        }
      }
      if ($showPosts) {
        if ($subCatPostCount>0 && $subCatLinks!='' && $addMisc) {
          $posttext = (miscPosts($cat,$catlink,$subCatPostCount2,$posttext));
        }
      }
      /* we only actually add the posts if it is expanded. Otherwise we add
         the posts dynamically to the dom from a javascript array
         However, we can't have an empty ul, so we create one emtpy li here */
      if ($postsBeforeCats) {
        $text =$posttext . $subCatLinks;
      } else {
        $text = $subCatLinks . $posttext;
      }
      if ($theID!='' && ! isset($collapsCatItems[$theID]) || ! $collapsCatItems[$theID] ) {
        $collapsCatItems[$theID] = "<ul>$text</ul>";
      }
      if ($expanded!='block' && $showTopLevel && $showPosts) {
        $posttext='';
      }
      if ($postsBeforeCats) {
        $text =$posttext . $subCatLinks;
      } else {
        $text = $subCatLinks . $posttext;
      }
      if ($text!='')
        $collapsCatText .= "<ul>$text</ul>";
      if ($showTopLevel) {
        if ($subCatCount>0 || $showPosts) {
          $collapsCatText .= "        </div> <!--there -->\n";
        }
        $collapsCatText .= "      </li> <!-- ending category -->\n";
      }
    } // end if theCount>0
  }
  return(array($collapsCatText, $postsInCat));
}

function get_count_from_categories( $categories, $cat_id ) {
	foreach ( $categories as $category ) {
		if ( $cat_id === $category->term_id ) {
			return $category->count;
		}
	}
	return 0;
}

?>
